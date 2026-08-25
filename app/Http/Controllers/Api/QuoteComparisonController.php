<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Log, Storage};
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser;
use thiagoalessio\TesseractOCR\TesseractOCR;

class QuoteComparisonController extends Controller
{
    public function compare(Request $request)
    {
        ini_set('max_execution_time', '900');
        ini_set('memory_limit', '2048M');

        $request->validate([
            'files' => 'required|array|min:1|max:10',
            'files.*' => 'required|file|max:20480',
            'employeeDetails' => 'required|array',
            'employeeDetails.employeeId' => 'required',
            'employeeDetails.employeeName' => 'required',
            'employeeDetails.email' => 'required|email',
            'employeeDetails.mobile' => 'required'
        ]);

        $addons = Addon::with('aliases')->get();
        $employee = $request->employeeDetails;
        $allQuotes = [];
        $filePaths = [];

        // --- STEP 1: Files ko temporary storage me save karna ---
        foreach ($request->file('files') as $index => $file) {
            try {
                $extension = strtolower($file->getClientOriginalExtension());
                $filename = Str::random(40) . '.' . $extension;
                $path = $file->storeAs('temp_quotes', $filename, 'public');
                $filePaths[$index] = storage_path('app/public/' . $path);
            } catch (\Exception $e) {
                Log::error('File Upload Error: ' . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'File upload failed'], 500);
            }
        }

        // --- STEP 2: MULTI-STAGE ROBUST EXTRACTION POOL ---
        $extractedTexts = [];
        $tempOcrDirs = [];

        try {
            $pool = Process::pool(function ($pool) use ($filePaths, &$tempOcrDirs) {
                foreach ($filePaths as $index => $fullPath) {
                    $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
                    // dd($extension);
                    // 1. Handle HTML Content
                    if (in_array($extension, ['html', 'htm'])) {
                        $vendorPath = base_path('vendor/autoload.php');
                        $escapedPath = addslashes($fullPath);

                        $phpCode = '
require "' . $vendorPath . '";

libxml_use_internal_errors(true);

$html = file_get_contents("' . $escapedPath . '");

$dom = new DOMDocument();
$dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING);

$text = [];

$rows = $dom->getElementsByTagName("tr");

foreach ($rows as $row) {
    $cells = [];

    foreach ($row->childNodes as $cell) {
        $value = trim(html_entity_decode($cell->textContent));

        if (!empty($value)) {
            $value = preg_replace("/\s+/", " ", $value);
            $cells[] = $value;
        }
    }

    if (!empty($cells)) {
        $text[] = implode(" | ", $cells);
    }
}

$xpath = new DOMXPath($dom);
$paragraphs = $xpath->query("//p");

foreach ($paragraphs as $p) {
    $value = trim(html_entity_decode($p->textContent));

    if (!empty($value)) {
        $text[] = preg_replace("/\s+/", " ", $value);
    }
}

echo implode(PHP_EOL, array_unique($text));
';

                        $pool->command('php -r ' . escapeshellarg($phpCode));
                    }
                    // 2. Handle Standard Images (Direct OCR)
                    elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'bmp', 'tiff', 'gif', 'webp'])) {
                        $pool->command('tesseract ' . escapeshellarg($fullPath) . ' stdout -l eng --psm 3');
                    }
                    // 3. Handle PDFs (Scanned + Digital Auto Detection)
                    elseif ($extension === 'pdf') {
                        $uniqueId = 'pdf_ocr_' . $index . '_' . time();
                        $tempOcrDir = storage_path('app/public/temp_ocr/' . $uniqueId . '/');
                        $tempOcrDirs[] = $tempOcrDir;

                        if (!file_exists($tempOcrDir)) {
                            mkdir($tempOcrDir, 0777, true);
                        }
                        $imagePrefix = $tempOcrDir . 'page';
                        $command = 'TXT=$(pdftotext ' . escapeshellarg($fullPath) . ' - 2>/dev/null); '
                            . 'CLEANED=$(echo "$TXT" | tr -d \'[:space:]\'); '
                            . 'if [ ${#CLEANED} -lt 20 ]; then '
                            . '  pdftoppm -png -r 150 ' . escapeshellarg($fullPath) . ' ' . escapeshellarg($imagePrefix) . ' && '
                            . '  tesseract ' . escapeshellarg($imagePrefix) . '-*.png stdout -l eng --psm 3 2>/dev/null; '
                            . 'else '
                            . '  echo "$TXT"; '
                            . 'fi';

                        $pool->command($command);
                    } else {
                        $pool->command("echo ''");
                    }
                }
            });

            // Pool execution start
            $responses = $pool->start()->wait();

            // Safety assignment
            foreach ($filePaths as $index => $fullPath) {
                if (isset($responses[$index]) && $responses[$index]->successful()) {
                    $rawText = $responses[$index]->output();
                    // Formatting clean karein: Extra line breaks aur spaces ko normalize karein
                    $rawText = preg_replace('/\s+/', ' ', $rawText);
                    $extractedTexts[$index] = trim($rawText);
                } else {
                    $extractedTexts[$index] = '';
                    Log::warning("Failed to extract text for file index: {$index}");
                }
            }
        } catch (\Exception $poolException) {
            Log::error('Parallel Pool Error: ' . $poolException->getMessage());
            foreach ($filePaths as $index => $fullPath) {
                $extractedTexts[$index] = '';
            }
        } finally {
            // Cleanup OCR temp files immediately
            foreach ($tempOcrDirs as $dir) {
                if (file_exists($dir)) {
                    array_map('unlink', glob("$dir/*.*"));
                    @rmdir($dir);
                }
            }
        }

        // --- STEP 3: DATA PARSING & BUSINESS EXTRACTION LOGIC ---
        foreach ($filePaths as $index => $fullPath) {
            $text = $extractedTexts[$index] ?? '';
            // dd($text);

            Log::info("PDF {$index} EXTRACTED TEXT PREVIEW", [
                'text' => substr($text, 0, 1000)
            ]);

            $companyName = $this->extractCompanyName($text);

            $isRoyal = stripos($text, 'royal sundaram') !== false || stripos($text, 'royal sundaram alliance') !== false;
            $isIcici = str_contains(strtolower($text), 'icici') || str_contains(strtolower($text), 'lombard');
            $isDigit = str_contains(strtolower($text), 'digit');
            $isLiberty = stripos($text, 'liberty') !== false || stripos($text, 'liberty general') !== false;
            $isBajaj = stripos($text, 'bajaj') !== false || stripos($text, 'bajaj allianz') !== false;
            $isTata = stripos($text, 'tata aig') !== false || stripos($text, 'tata aig general insurance') !== false;
            $isHdfc = stripos($text, 'hdfc ergo') !== false;
            $isZuno =
                stripos($text, 'zuno') !== false ||
                stripos($text, 'zuno general insurance') !== false ||
                stripos($text, 'edelweiss general insurance') !== false;
            $isShriram =
                stripos($text, 'shriram general insurance') !== false ||
                stripos($text, 'shriram general insurance co. ltd') !== false;
            $isOriental =
                stripos($text, 'oriental insurance') !== false ||
                stripos($text, 'oriental insurance company') !== false;

            $allQuotes[] = [
                'quote_name' => 'Quote_' . ($index + 1),
                'company_name' => $companyName,
                'customer_name' => $isOriental
                    ? $this->extractOrientalCustomerName($text)
                    : (
                        $isBajaj
                            ? $this->extractBajajCustomerName($text)
                            : (
                                $isHdfc
                                    ? $this->extractHdfcCustomerName($text)
                                    : (
                                        $isDigit
                                            ? $this->extractDigitCustomerName($text)
                                            : (
                                                $isShriram
                                                    ? $this->extractShriramCustomerName($text)
                                                    : (
                                                        $isZuno
                                                            ? $this->extractZunoCustomerName($text)
                                                            : (
                                                                $isLiberty
                                                                    ? $this->extractLibertyCustomerName($text)
                                                                    : (
                                                                        $isIcici
                                                                            ? $this->extractIciciCustomerName($text)
                                                                            : $this->extractCustomerName($text, $companyName)
                                                                    )
                                                            )
                                                    )
                                            )
                                    )
                            )
                    ),
                'vehicle_number' =>
                    $isOriental
                        ? $this->extractOrientalVehicleNumber($text)
                        : (
                            $isBajaj
                                ? $this->extractBajajVehicleNumber($text)
                                : (
                                    $isHdfc
                                        ? $this->extractHdfcVehicleNumber($text)
                                        : (
                                            $isDigit
                                                ? $this->extractDigitVehicleNumber($text)
                                                : $this->extractVehicleNumber($text)
                                        )
                                )
                        ),
                'premium' => $isTata
                    ? $this->extractTataPremium($text)
                    : (
                        $isOriental
                            ? $this->extractOrientalPremium($text)
                            : (
                                $isDigit
                                    ? $this->extractDigitPremium($text)
                                    : (
                                        $isHdfc
                                            ? $this->extractHdfcPremium($text)
                                            : (
                                                $isZuno
                                                    ? $this->extractZunoPremium($text)
                                                    : (
                                                        $isIcici
                                                            ? $this->extractIciciRecommendedPremium($text)
                                                            : (
                                                                $isLiberty
                                                                    ? $this->extractLibertyPremium($text)
                                                                    : (
                                                                        stripos($text, 'plan c') !== false
                                                                            ? $this->extractPlanCPremium($text)
                                                                            : $this->extractPremium($text)
                                                                    )
                                                            )
                                                    )
                                            )
                                    )
                            )
                    ),
                'idv' => $isOriental
                    ? $this->extractOrientalIdv($text)
                    : (
                        $isDigit
                            ? $this->extractDigitIdv($text)
                            : (
                                $isHdfc
                                    ? $this->extractHdfcIdv($text)
                                    : $this->extractIdv($text)
                            )
                    ),
                'addons' => $this->getAddonsByCompany($text, $addons, $isDigit, $isIcici, $isRoyal, $isLiberty, $isBajaj, $isTata, $isShriram, $isZuno, $isOriental),
            ];
            // dd($text,$allQuotes, $companyName,$isHdfc);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }

        if (empty($allQuotes)) {
            return response()->json(['success' => false, 'message' => 'No quotes found'], 400);
        }

        $comparison = $this->generateComparisonTable($allQuotes, $addons);
        // return response()->json([
        //     'success' => true,
        //     'allQuotes' => $allQuotes,
        //     'employee' => $employee,
        //     'comparison' => $comparison,
        //     'customer_name' => $allQuotes[0]['customer_name'] ?? '',
        //     'registration_number' => $allQuotes[0]['vehicle_number'] ?? ''
        // ]);

        // ==========================================
        // PDF GENERATION
        // ==========================================
        $pdf = Pdf::loadView('insurance-comparison', [
            'data' => [
                'quotes' => $allQuotes,
                'comparison' => $comparison
            ],
            'employee' => $employee,
            'customer_name' => $allQuotes[0]['customer_name'] ?? '',
            'vehicle_number' => $allQuotes[0]['vehicle_number'] ?? ''
        ]);

        $vehicleNumber = $allQuotes[0]['vehicle_number'] ?? 'quotation';
        $vehicleNumber = preg_replace('/[^A-Za-z0-9\-]/', '_', $vehicleNumber);
        $fileName = $vehicleNumber . '.pdf';

        return $pdf->download($fileName, [
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
        ]);
    }

    private function getAddonsByCompany(
        $text,
        $addons,
        $isDigit,
        $isIcici,
        $isRoyal,
        $isLiberty,
        $isBajaj,
        $isTata,
        $isShriram,
        $isZuno,
        $isOriental
    ) {
        try {
            if ($isBajaj) {
                return $this->detectBajajAddons($text, $addons);
            }
            // DIGIT
            if ($isDigit) {
                return $this->detectDigitAddons($text, $addons);
            }

            // ICICI
            if ($isIcici) {
                return $this->detectIciciAddons(
                    $this->extractIciciRecommendedPlanText($text),
                    $addons
                );
            }
            if ($isTata) {
                return $this->detectTataAigAddons($text, $addons);
            }
            // ROYAL SUNDARAM
            if ($isRoyal) {
                $planCText = $this->extractRoyalPlanCText($text);
                // dd($planCText);

                return $this->detectRoyalPlanCAddons(
                    $planCText,
                    $addons
                );
            }
            if ($isZuno) {
                return $this->detectZunoAddons(
                    $text,
                    $addons
                );
            }
            if ($isShriram) {
                return $this->detectShriramAddons(
                    $text,
                    $addons
                );
            }
            if ($isOriental) {
                return $this->detectOrientalAddons(
                    $text,
                    $addons
                );
            }
            // LIBERTY
            if ($isLiberty) {
                return $this->detectLibertyAddons($text, $addons);
            }
            if (stripos($text, 'hdfc ergo') !== false) {
                return $this->detectHdfcErgoAddons($text, $addons);
            }

            // DEFAULT
            return $this->detectAddons($text, $addons);
        } catch (\Exception $e) {
            Log::error('ADDON DETECTION ERROR: ' . $e->getMessage());

            return [];
        }
    }

    private function extractPdfText($pdfPath)
    {
        try {
            $text = '';

            // STEP 1 : NORMAL PDF PARSER
            try {
                $parser = new Parser();

                $pdf = $parser->parseFile($pdfPath);

                $text = $pdf->getText();
            } catch (\Exception $e) {
                Log::error('Normal PDF Parser Failed: ' . $e->getMessage());
            }

            // STEP 2 : OCR FALLBACK
            if (empty(trim($text)) || strlen(trim($text)) < 100) {
                Log::info('Using OCR Fallback');

                // 👇 IMAGE SERVICE WALA FUNCTION
                $text = fileToText($pdfPath);
            }

            if (!$text) {
                return '';
            }

            $text = html_entity_decode($text);

            $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');

            $text = iconv('UTF-8', 'UTF-8//IGNORE', $text);

            $text = str_replace(["\n", "\r", "\t"], ' ', $text);

            $text = preg_replace('/\s+/', ' ', $text);

            return trim($text);
        } catch (\Exception $e) {
            Log::error('PDF Text Extraction Error: ' . $e->getMessage());

            return '';
        }
    }

    private function extractZunoCustomerName($text)
    {
        if (
            preg_match(
                '/insured\s*name\s*:\s*(.*?)\s*(?:quote issuance date|intermediary name)/i',
                $text,
                $m
            )
        ) {
            return trim($m[1]);
        }

        return 'Guest';
    }

    private function detectZunoAddons($text, $addons)
    {
        $detected = [];

        foreach ($addons as $addon) {
            $detected[$addon->name] = false;
        }

        $text = strtolower(preg_replace('/\s+/', ' ', $text));

        $addonMap = [
            'Zero Depreciation' => [
                'depreciation protect',
                'zero depreciation',
                'nil depreciation'
            ],
            'Consumables' => [
                'consumable expenses protect',
                'consumable protect',
                'consumables'
            ],
            'Engine Protect' => [
                'engine protect',
                'engine protection'
            ],
            'Road Side Assistance' => [
                'roadside assistance',
                'road side assistance',
                'rsa'
            ],
            'Key Replacement' => [
                'key replacement',
                'lost key cover'
            ],
            'Return to Invoice' => [
                'return to invoice',
                'invoice protect'
            ],
            'Loss of Personal Belongings' => [
                'loss of personal belongings',
                'personal belongings'
            ],
            'Tyre Protect' => [
                'tyre protect',
                'tire protect'
            ],
            'NCB Protector Cover' => [
                'ncb protector',
                'ncb protection'
            ],
            'Legal Liability Cover for Paid Drivers' => [
                'legal liability to paid drivers',
                'legal liability to paid driver'
            ],
            'Emergency Medical Expenses ' => [
                'emergency medical expenses'
            ],
            'Emergency Hotel Accommodation' => [
                'emergency hotel accommodation'
            ],
            'Battery Cover' => [
                'battery cover'
            ],
            'Electric Vehicle Shield' => [
                'electric vehicle shield',
                'ev shield'
            ]
        ];

        foreach ($addonMap as $addonName => $keywords) {
            foreach ($keywords as $keyword) {
                if (stripos($text, $keyword) !== false) {
                    $detected[$addonName] = true;
                    break;
                }
            }
        }

        return $detected;
    }

    private function extractZunoPremium($text)
    {
        $text = preg_replace('/\s+/', ' ', $text);

        if (
            preg_match(
                '/final\s*premium(.*?)(?:important notes|zuno general insurance|$)/is',
                $text,
                $m
            )
        ) {
            preg_match_all(
                '/([\d,]+\.\d{2})/',
                $m[1],
                $amounts
            );

            if (!empty($amounts[1])) {
                $values = array_map(function ($v) {
                    return (float) str_replace(',', '', $v);
                }, $amounts[1]);

                return max($values);
            }
        }

        return null;
    }

    private function extractShriramCustomerName($text)
    {
        $text = html_entity_decode($text);
        $text = preg_replace('/\s+/', ' ', $text);

        $patterns = [
            '/customer\s*name\s*[:\-]?\s*([a-z\s]+?)(?:registration|vehicle|mobile|email)/i',
            '/insured\s*name\s*[:\-]?\s*([a-z\s]+?)(?:registration|vehicle|mobile|email)/i',
            '/proposer\s*name\s*[:\-]?\s*([a-z\s]+?)(?:registration|vehicle|mobile|email)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $m)) {
                $name = trim($m[1]);

                $name = preg_replace('/[^a-z\s]/i', '', $name);

                if (str_word_count($name) >= 2) {
                    return ucwords(strtolower($name));
                }
            }
        }

        return 'Guest';
    }

    private function detectShriramAddons($text, $addons)
    {
        $detected = [];

        foreach ($addons as $addon) {
            $detected[$addon->name] = false;
        }

        $text = strtolower($text);

        $map = [
            'Zero Depreciation' => ['nil depreciation cover'],
            'Consumables' => ['consumable'],
            'Road Side Assistance' => ['road side assistance'],
            'Engine Protect' => ['engine protect'],
            'Return to Invoice' => ['return to invoice'],
            'Tyre Protect' => ['tyre protect'],
            'Key Replacement' => ['key replacement'],
        ];

        foreach ($map as $addonName => $keywords) {
            foreach ($keywords as $keyword) {
                if (stripos($text, $keyword) !== false) {
                    $detected[$addonName] = true;
                    break;
                }
            }
        }

        return $detected;
    }

    private function extractLibertyPremium(string $text): ?string
    {
        if (preg_match('/PREMIUM COMPUTATION(.*?)(You Can Opt Other Addon|Disclaimer)/is', $text, $block)) {
            preg_match_all('/Rs\.?\s*([\d,]+\.\d{2})/', $block[1], $matches);

            if (!empty($matches[1])) {
                // Last amount = Total Policy Premium
                return str_replace(',', '', end($matches[1]));
            }
        }

        return null;
    }

    private function extractBajajPlanName($text)
    {
        $text = strtolower($text);

        if (
            preg_match(
                '/plan\s*name\*+\s*(.*?)(?:add on packages details|important note|premium)/is',
                $text,
                $match
            )
        ) {
            $planBlock = trim($match[1]);

            Log::info('BAJAJ PLAN BLOCK', [
                'block' => $planBlock
            ]);

            $plans = [
                'drive assure welcome plus',
                'drive assure economy plus',
                'drive assure prime plus',
                'drive assure drivesmart prestige',
                'drive assure drivesmart classic',
                'drive assure drivesmart premium',
                'drive assure economy',
                'drive assure welcome',
                'eco assure repair protection',
            ];

            foreach ($plans as $plan) {
                if (str_contains($planBlock, $plan)) {
                    return $plan;
                }
            }
        }

        return '';
    }

    private function detectBajajAddons($text, $addons)
    {
        $detected = [];

        // =========================
        // DEFAULT FALSE
        // =========================

        foreach ($addons as $addon) {
            $detected[$addon->name] = false;
        }

        // =========================
        // NORMALIZE TEXT
        // =========================

        $text = strtolower($text);

        $text = html_entity_decode($text);

        $text = preg_replace('/\s+/', ' ', $text);

        Log::info('BAJAJ TEXT', [
            'text' => substr($text, 0, 5000)
        ]);

        // =========================
        // PLAN NAME DETECTION
        // =========================

        $planName = $this->extractBajajPlanName($text);

        Log::info('BAJAJ PLAN DETECTED', [
            'plan' => $planName
        ]);

        // =========================
        // PLAN → ADDON MAP
        // =========================

        $planAddonMap = [
            // =====================
            // ECO ASSURE
            // =====================
            'eco assure repair protection' => [
                'Road Side Assistance',
                'Engine Protect',
                'Consumables',
                'Key Replacement',
                'Loss of Personal Belongings',
            ],
            // =====================
            // DRIVE ASSURE WELCOME
            // =====================
            'drive assure welcome' => [
                'Road Side Assistance',
                'Zero Depreciation',
            ],
            // =====================
            // DRIVE ASSURE ECONOMY
            // =====================
            'drive assure economy' => [
                'Road Side Assistance',
                'Zero Depreciation',
                'Engine Protect',
            ],
            // =====================
            // DRIVE ASSURE PRIME PLUS
            // =====================
            'drive assure prime plus' => [
                'Road Side Assistance',
                'Key Replacement',
                'Loss of Personal Belongings',
            ],
            // =====================
            // DRIVE ASSURE WELCOME PLUS
            // =====================
            'drive assure welcome plus' => [
                'Road Side Assistance',
                'Zero Depreciation',
                'Key Replacement',
                'Loss of Personal Belongings',
            ],
            // =====================
            // DRIVE ASSURE ECONOMY PLUS
            // =====================
            'drive assure economy plus' => [
                'Road Side Assistance',
                'Zero Depreciation',
                'Engine Protect',
                'Key Replacement',
                'Loss of Personal Belongings',
            ],
            // =====================
            // DRIVESMART PRESTIGE
            // =====================
            'drive assure drivesmart prestige' => [
                'Road Side Assistance',
                'Zero Depreciation',
                'Engine Protect',
                'Key Replacement',
                'Loss of Personal Belongings',
                'Consumables',
            ],
            // =====================
            // DRIVESMART CLASSIC
            // =====================
            'drive assure drivesmart classic' => [
                'Road Side Assistance',
                'Key Replacement',
                'Loss of Personal Belongings',
            ],
            // =====================
            // DRIVESMART PREMIUM
            // =====================
            'drive assure drivesmart premium' => [
                'Road Side Assistance',
                'Zero Depreciation',
                'Engine Protect',
                'Key Replacement',
                'Loss of Personal Belongings',
            ],
        ];

        // =========================
        // APPLY PLAN ADDONS
        // =========================

        if (
            !empty($planName) &&
            isset($planAddonMap[$planName])
        ) {
            foreach ($planAddonMap[$planName] as $addonName) {
                $detected[$addonName] = true;
            }
        }

        // =========================
        // EXTRA TOPUP CHECKS
        // =========================

        // =========================
        // EXTRA TOPUP CHECKS
        // =========================

        if (
            str_contains($text, 'top up cover 1: consumables expenses')
        ) {
            $detected['Consumables'] = true;
        }

        // =========================
        // TYRE PROTECT
        // =========================

        $tyreAliases = [
            'tyre safeguard',
            'tyre safe guard',
            'tyre protect',
            'tyre protection',
            'tyre cover',
            'tire safeguard',
            'tire protect',
            'tire protection',
        ];

        foreach ($tyreAliases as $alias) {
            if (str_contains($text, $alias)) {
                $detected['Tyre Protect'] = true;
                break;
            }
        }

        return $detected;
    }

    private function extractBajajCustomerName($text)
    {
        if (
            preg_match(
                '/Sub IMD Name:.*?\s+([A-Z][A-Z\s\.]+?)\s+Quotation Issued on/i',
                $text,
                $matches
            )
        ) {
            return trim($matches[1]);
        }

        return 'Guest';
    }

    private function extractBajajVehicleNumber($text)
    {
        if (
            preg_match(
                '/Registration\s+Number\s+Registration\s+Authority.*?([A-Z]{2}\d{1,2}[A-Z]{1,3}\d{1,4})/is',
                $text,
                $matches
            )
        ) {
            return trim($matches[1]);
        }

        return 'N/A';
    }

    private function detectLibertyAddons($text, $addons)
    {
        $detected = [];

        // normalize
        $text = strtolower($text);

        $text = html_entity_decode($text);

        $text = preg_replace('/\s+/', ' ', $text);

        // =========================
        // ONLY PREMIUM COMPUTATION SECTION
        // =========================

        $start = stripos($text, 'premium computation');

        $end = stripos($text, 'total own damage premium');

        if ($start !== false && $end !== false && $end > $start) {
            $text = substr(
                $text,
                $start,
                $end - $start
            );
        }
        Log::info('LIBERTY PREMIUM COMPUTATION', [
            'text' => substr($text, 0, 3000)
        ]);

        foreach ($addons as $addon) {
            $found = false;

            foreach ($addon->aliases as $alias) {
                $aliasText = strtolower(trim($alias->alias));

                // addon present in premium computation
                if (str_contains($text, $aliasText)) {
                    $found = true;

                    Log::info('LIBERTY ADDON FOUND', [
                        'addon' => $addon->name,
                        'alias' => $aliasText
                    ]);

                    break;
                }
            }

            $detected[$addon->name] = $found;
        }

        return $detected;
    }

    private function extractLibertyCustomerName($text)
    {
        // normalize text
        $text = html_entity_decode($text);

        $text = preg_replace('/\s+/', ' ', $text);

        Log::info('LIBERTY CUSTOMER NAME TEXT', [
            'text' => substr($text, 0, 1000)
        ]);

        $patterns = [
            // Customer Name MAHESH KUMAR Registration Number
            '/customer\s+name\s+([a-z\s]+?)\s+registration\s+number/i',
            // fallback
            '/customer\s+name\s+([a-z\s]+?)\s+(make|vehicle|segment|registration)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $name = trim($matches[1]);

                $name = preg_replace('/[^a-z\s]/i', '', $name);

                $name = preg_replace('/\s+/', ' ', $name);

                if (str_word_count($name) >= 2) {
                    Log::info('LIBERTY CUSTOMER FOUND', [
                        'name' => $name
                    ]);

                    return ucwords(strtolower($name));
                }
            }
        }

        return 'Guest';
    }

    private function detectHdfcErgoAddons($text, $addons)
    {
        $detected = [];

        $text = strtolower($text);

        $text = html_entity_decode($text);

        $text = preg_replace('/\s+/', ' ', $text);

        foreach ($addons as $addon) {
            $detected[$addon->name] = false;
        }

        $hdfcAddonMap = [
            'Zero Depreciation' => [
                'zero-depreciation claim',
                'zero depreciation claim',
                '**Zero-depreciation claim'
            ],
            'Engine Protect' => [
                'engine and gear-box protector',
                'engine protector'
            ],
            'Consumables' => [
                'cost of consumables',
                'consumables'
            ],
            'Return to Invoice' => [
                'return to invoice'
            ],
            'Tyre Protect' => [
                'tyre secure for private car',
                'tyre secure'
            ],
            'Loss of Personal Belongings' => [
                'loss of personal belongings cover'
            ],
            'Road Side Assistance' => [
                'enhanced roadside assistance',
                'roadside assistance'
            ],
            'Key Replacement' => [
                'Emergency Assistance Wider'
            ],
            'Emergency Medical Expenses ' => [
                'emergency medical expenses'
            ],
        ];

        foreach ($hdfcAddonMap as $addonName => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($text, strtolower($keyword))) {
                    $detected[$addonName] = true;

                    break;
                }
            }
        }

        return $detected;
    }

    private function extractHdfcIdv($text)
    {
        $text = preg_replace('/\s+/', ' ', $text);

        $patterns = [
            '/insured declared value[^0-9]{0,50}([0-9,]+)/i',
            '/idv[^0-9]{0,30}([0-9,]+)/i',
            '/vehicle idv[^0-9]{0,30}([0-9,]+)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $m)) {
                $amount = str_replace(',', '', $m[1]);

                if ($amount > 10000) {
                    return (float) $amount;
                }
            }
        }

        return null;
    }

    private function extractHdfcPremium($text)
    {
        $text = preg_replace('/\s+/', ' ', $text);

        $patterns = [
            '/premium amount for motor insurance is\s*[₹rs\.\s]*([0-9,]+(?:\.\d{2})?)/i',
            '/total premium[^0-9]{0,50}([0-9,]+(?:\.\d{2})?)/i',
            '/gross premium[^0-9]{0,50}([0-9,]+(?:\.\d{2})?)/i',
            '/final premium[^0-9]{0,50}([0-9,]+(?:\.\d{2})?)/i',
            '/premium payable[^0-9]{0,50}([0-9,]+(?:\.\d{2})?)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $m)) {
                return (float) str_replace(',', '', $m[1]);
            }
        }

        return null;
    }

    private function extractHdfcCustomerName($text)
    {
        // normalize
        $text = html_entity_decode($text);

        $text = preg_replace('/\s+/', ' ', $text);

        Log::info('HDFC CUSTOMER NAME TEXT', [
            'text' => substr($text, 0, 1000)
        ]);

        $patterns = [
            // Dear DIVYANSHU TANTER,
            '/dear\s+([a-z\s]+?),/i',
            // Dear DIVYANSHU TANTER
            '/dear\s+([a-z\s]{5,50})/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $name = trim($matches[1]);

                $name = preg_replace('/[^a-z\s]/i', '', $name);

                $name = preg_replace('/\s+/', ' ', $name);

                if (str_word_count($name) >= 2) {
                    Log::info('HDFC CUSTOMER FOUND', [
                        'name' => $name
                    ]);

                    return ucwords(strtolower($name));
                }
            }
        }

        return 'Guest';
    }

    private function extractHdfcVehicleNumber($text)
    {
        $text = strtoupper($text);

        $text = preg_replace('/\s+/', ' ', $text);

        $patterns = [
            '/REG\.?\s*NO\.?\s*[:\-]?\s*([A-Z]{2}[- ]?\d{1,2}[- ]?[A-Z]{1,3}[- ]?\d{3,4})/i',
            '/\b([A-Z]{2}[- ]?\d{1,2}[- ]?[A-Z]{1,3}[- ]?\d{3,4})\b/i'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $m)) {
                return preg_replace('/[^A-Z0-9]/', '', $m[1]);
            }
        }

        return 'N/A';
    }

    /**
     * ADDON DETECTION (ROBUST)
     */
    private function extractRoyalPlanCText($text)
    {
        // normalize
        $text = html_entity_decode($text);

        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');

        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);

        $text = str_replace("\r", "\n", $text);

        $text = preg_replace('/\s+/', ' ', $text);

        // =========================
        // FIND PLAN C
        // =========================

        $keywords = [
            'plan c',
            'premium package',
            'recommended plan',
            'comprehensive plan'
        ];

        $pos = false;

        foreach ($keywords as $keyword) {
            $pos = stripos($text, $keyword);

            if ($pos !== false) {
                break;
            }
        }

        if ($pos === false) {
            Log::warning('ROYAL PLAN C NOT FOUND');

            return $text;
        }

        // nearby chunk
        $chunk = substr($text, $pos, 5000);

        Log::info('ROYAL PLAN C CHUNK', [
            'chunk' => substr($chunk, 0, 3000)
        ]);

        return $chunk;
    }

    private function detectRoyalPlanCAddons($text, $addons)
    {
        $detected = [];

        // =========================
        // NORMALIZE
        // =========================

        $text = html_entity_decode($text);

        $text = strtolower($text);

        // IMPORTANT:
        // newline preserve karo
        $text = str_replace("\r", "\n", $text);

        // multiple spaces cleanup
        $text = preg_replace('/[ \t]+/', ' ', $text);

        // split lines
        $lines = preg_split('/\n+/', $text);

        Log::info('ROYAL PLAN C LINES', [
            'lines' => array_slice($lines, 0, 150)
        ]);

        // =========================
        // DEFAULT FALSE
        // =========================

        foreach ($addons as $addon) {
            $detected[$addon->name] = false;
        }

        // =========================
        // CHECK EACH ADDON
        // =========================

        foreach ($addons as $addon) {
            foreach ($addon->aliases as $alias) {
                $aliasText = strtolower(trim($alias->alias));

                foreach ($lines as $line) {
                    $cleanLine = trim($line);

                    if (empty($cleanLine)) {
                        continue;
                    }

                    // addon row found?
                    if (!str_contains($cleanLine, $aliasText)) {
                        continue;
                    }

                    Log::info('ROYAL ADDON ROW', [
                        'addon' => $addon->name,
                        'line' => $cleanLine
                    ]);

                    // =========================
                    // EXTRACT VALUES
                    // =========================

                    // =========================
                    // EXTRACT ONLY LAST 3 COLUMNS
                    // =========================

                    preg_match_all(
                        '/(?:^|\s)(\-|nil|0\.00|0\.0|0|[0-9,]+(?:\.\d{1,2})?)(?=\s|$)/i',
                        $cleanLine,
                        $matches
                    );

                    $values = $matches[1] ?? [];

                    Log::info('ROYAL ROW VALUES', [
                        'addon' => $addon->name,
                        'values' => $values
                    ]);

                    // We need Plan A, B, C columns
                    if (count($values) < 3) {
                        continue;
                    }

                    // TAKE ONLY LAST 3 VALUES
                    $lastThree = array_slice($values, -3);

                    // PLAN C = LAST COLUMN
                    $planCValue = strtolower(trim($lastThree[2]));

                    Log::info('ROYAL LAST THREE', [
                        'addon' => $addon->name,
                        'last_three' => $lastThree,
                        'plan_c' => $planCValue
                    ]);

                    Log::info('ROYAL PLAN C VALUE', [
                        'addon' => $addon->name,
                        'plan_c' => $planCValue
                    ]);

                    // =========================
                    // FALSE CONDITIONS
                    // =========================

                    if (
                        in_array($planCValue, [
                            '0',
                            '0.0',
                            '0.00',
                            '-',
                            'nil',
                            ''
                        ])
                    ) {
                        $detected[$addon->name] = false;
                    } else {
                        $amount = (float) str_replace(',', '', $planCValue);

                        $detected[$addon->name] = $amount > 0;
                    }

                    break 2;
                }
            }
        }

        return $detected;
    }

    private function extractIciciCustomerName($text)
    {
        $text = html_entity_decode($text);

        $text = preg_replace('/\s+/', ' ', $text);

        Log::info('ICICI CUSTOMER TEXT', [
            'text' => substr($text, 0, 2000)
        ]);

        $patterns = [
            // Customer Name : ABC XYZ
            '/customer\s*name\s*[:\-]?\s*([A-Za-z\s]+?)(?:registration|vehicle|mobile|email)/i',
            // Insured Name
            '/insured\s*name\s*[:\-]?\s*([A-Za-z\s]+?)(?:registration|vehicle|mobile|email)/i',
            // Proposer Name
            '/proposer\s*name\s*[:\-]?\s*([A-Za-z\s]+?)(?:registration|vehicle|mobile|email)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $m)) {
                $name = trim($m[1]);

                $name = preg_replace('/[^a-z\s]/i', '', $name);

                $name = preg_replace('/\s+/', ' ', $name);

                if (str_word_count($name) >= 2) {
                    return ucwords(strtolower($name));
                }
            }
        }

        return 'Guest';
    }

    private function detectIciciAddons($text, $addons)
    {
        $detected = [];

        foreach ($addons as $addon) {
            $detected[$addon->name] = false;
        }

        $text = html_entity_decode($text);
        $text = preg_replace('/\s+/', ' ', $text);

        foreach ($addons as $addon) {
            foreach ($addon->aliases as $alias) {
                $aliasText = preg_quote(trim($alias->alias), '/');

                if (
                    preg_match(
                        '/' . $aliasText . '\s+(x|\?)\s+(x|\?)/i',
                        $text,
                        $m
                    )
                ) {
                    // Recommended Plan = second symbol
                    $recommended = strtoupper($m[2]);

                    $detected[$addon->name] = ($recommended === '?');

                    break;
                }
            }
        }

        return $detected;
    }

    private function detectAddons($text, $addons)
    {
        $detected = [];

        // NORMALIZE TEXT
        $text = mb_strtolower($text, 'UTF-8');

        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);

        $text = preg_replace('/[^\w\s]/u', ' ', $text);

        $text = preg_replace('/\s+/', ' ', $text);

        $text = trim($text);

        foreach ($addons as $addon) {
            $found = false;

            foreach ($addon->aliases as $alias) {
                $aliasText = mb_strtolower($alias->alias, 'UTF-8');

                $aliasText = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $aliasText);

                $aliasText = preg_replace('/[^\w\s]/u', ' ', $aliasText);

                $aliasText = preg_replace('/\s+/', ' ', $aliasText);

                $aliasText = trim($aliasText);

                if (strlen($aliasText) < 3) {
                    continue;
                }

                // EXACT MATCH
                // EXACT MATCH
                if (Str::contains($text, $aliasText)) {
                    // ===================================
                    // CHECK ADDON AMOUNT
                    // ===================================

                    $pattern = '/'
                        . preg_quote($aliasText, '/')
                        . '[^0-9]{0,40}([0-9,]+(?:\.\d{1,2})?|nil|nill|zero)/i';

                    if (preg_match($pattern, $text, $m)) {
                        $value = strtolower(trim($m[1]));

                        // NIL / ZERO CHECK
                        if (
                            in_array($value, ['0', '0.0', '0.00', 'nil', 'nill', 'zero'])
                        ) {
                            $found = false;
                        } else {
                            $amount = (float) str_replace(',', '', $value);

                            $found = $amount > 0;
                        }
                    } else {
                        // amount not found
                        $found = false;
                    }

                    break;
                }

                // SPACE REMOVED MATCH
                $compactText = str_replace(' ', '', $text);

                $compactAlias = str_replace(' ', '', $aliasText);

                if (Str::contains($compactText, $compactAlias)) {
                    $pattern = '/'
                        . preg_quote($aliasText, '/')
                        . '[^0-9\-]{0,50}([0-9,]+(?:\.\d{1,2})?|0|0\.00|nil|nill|zero|-)/i';

                    if (preg_match($pattern, $text, $m)) {
                        $value = strtolower(trim($m[1]));

                        // REMOVE COMMA
                        $value = str_replace(',', '', $value);

                        // FALSE CONDITIONS
                        if (
                            in_array($value, [
                                '0',
                                '0.0',
                                '0.00',
                                '-',
                                'nil',
                                'nill',
                                'zero',
                                '',
                                null
                            ])
                        ) {
                            $found = false;
                        } else {
                            $amount = (float) $value;

                            $found = $amount > 0;
                        }
                    }

                    break;
                }
            }

            $detected[$addon->name] = $found;
        }

        return $detected;
    }

    private function detectDigitAddons($text, $addons)
    {
        $detected = [];

        // =========================
        // NORMALIZE TEXT
        // =========================

        $text = strtolower($text);

        $text = html_entity_decode($text);

        $text = preg_replace('/\s+/', ' ', $text);

        // =========================
        // ONLY DIGIT ADDON SECTION
        // =========================

        $start = stripos($text, 'addon(s) opted');

        if ($start !== false) {
            $text = substr($text, $start, 5000);
        }

        Log::info('DIGIT ADDON SECTION', [
            'text' => substr($text, 0, 5000)
        ]);

        // =========================
        // DIGIT SPECIFIC MAPPING
        // =========================

        $digitAddonMap = [
            'Zero Depreciation' => [
                'zero dep',
                'zero depreciation',
                'bumper to bumper'
            ],
            'Consumables' => [
                'consumable',
                'consumables'
            ],
            'Road Side Assistance' => [
                'Assistance',
                'road side assistance',
                'rsa'
            ],
            'Engine Protect' => [
                'Car Engine and Gear Box',
                'engine protect',
                'engine protection',
                'engine secure',
                'engine and gear box protect',
                'engine and gearbox protect'
            ],
            'Tyre Protect' => [
                'tyre protect',
                'tire protect',
                'tyre secure'
            ],
            'Return to Invoice' => [
                'return to invoice',
                'rti'
            ],
            'Loss of Personal Belongings' => [
                'Loss to Personal',
                'belonging',
                'loss to personal'
            ],
            'Key Replacement' => [
                'Lock Protect',
                'car key',
                'key replacement'
            ],
            'Emergency Hotel Accommodation' => [
                'accommodation',
                'emergency hotel accommodation'
            ],
            'Electric Vehicle Shield' => [
                'electric vehicle shield'
            ],
            'Battery Cover' => [
                'battery cover'
            ],
            'Legal Liability Cover for Paid Drivers' => [
                'legal liability to paid driver',
                'paid driver'
            ],
            'Third Party Liability' => [
                'third party liability'
            ],
            'Compulsory Personal Accident' => [
                'compulsory personal accident',
                'personal acciedent',
                'pa cover'
            ],
            'Emergency Medical Expenses ' => [
                'emergency medical expenses',
                'medical expense'
            ],
            'NCB Protector Cover' => [
                'ncb protector',
                'ncb protection',
            ],
        ];

        foreach ($addons as $addon) {
            $found = false;

            // =========================
            // FIRST CHECK CUSTOM MAP
            // =========================

            if (isset($digitAddonMap[$addon->name])) {
                foreach ($digitAddonMap[$addon->name] as $keyword) {
                    if (str_contains($text, strtolower($keyword))) {
                        $found = true;

                        Log::info('DIGIT ADDON FOUND', [
                            'addon' => $addon->name,
                            'keyword' => $keyword
                        ]);

                        break;
                    }
                }
            }

            // =========================
            // FALLBACK TO DATABASE ALIASES
            // =========================

            if (!$found) {
                foreach ($addon->aliases as $alias) {
                    $aliasText = strtolower(trim($alias->alias));

                    $aliasText = preg_replace('/\s+/', ' ', $aliasText);

                    if (str_contains($text, $aliasText)) {
                        $found = true;

                        Log::info('DIGIT ADDON FOUND FROM ALIAS', [
                            'addon' => $addon->name,
                            'alias' => $aliasText
                        ]);

                        break;
                    }
                }
            }

            $detected[$addon->name] = $found;
        }

        return $detected;
    }

    private function extractDigitCustomerName($text)
    {
        $text = preg_replace('/\s+/', ' ', $text);

        Log::info('DIGIT CUSTOMER TEXT', [
            'text' => substr($text, 0, 2000)
        ]);

        $patterns = [
            // Hi, RASHMI!
            '/hi,\s*([A-Z\s]{2,60})!/i',
            // Hi RASHMI RATHORE
            '/hi\s+([A-Z\s]{2,60})\s+irdan/i',
            // Hi HIRA LAL PREM JI PATEL your car details
            '/hi\s+([A-Z\s]{5,60})\s+your\s+car\s+details/i',
            // Ex HIRA LAL PREM JI PATEL -123
            '/ex\s+([A-Z\s]{5,60})\s+\-\d+/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $m)) {
                $name = trim($m[1]);

                $name = preg_replace('/[^A-Z\s]/i', '', $name);

                $name = preg_replace('/\s+/', ' ', $name);

                if (str_word_count($name) >= 1) {
                    return ucwords(strtolower($name));
                }
            }
        }

        return 'Guest';
    }

    private function extractDigitPremium($text)
    {
        $text = preg_replace('/\s+/', ' ', $text);

        if (
            preg_match(
                '/final premium(.*?)(?:for quote purpose only|note:|$)/is',
                $text,
                $match
            )
        ) {
            preg_match_all(
                '/([0-9,]+\.\d{2})/',
                $match[1],
                $amounts
            );

            if (!empty($amounts[1])) {
                $values = array_map(function ($v) {
                    return (float) str_replace(',', '', $v);
                }, $amounts[1]);

                return max($values);
            }
        }

        return null;
    }

    private function extractDigitIdv($text)
    {
        $text = preg_replace('/\s+/', ' ', $text);

        $patterns = [
            '/total\s+idv.*?([0-9]{5,})/i',
            '/vehicle\s+idv.*?([0-9]{5,})/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $m)) {
                $amount = preg_replace('/[^0-9]/', '', $m[1]);

                if ($amount > 10000) {
                    return (float) $amount;
                }
            }
        }

        return null;
    }

    private function extractOrientalCustomerName($text)
    {
        $text = preg_replace('/\s+/', ' ', $text);

        if (
            preg_match(
                '/insured\s*name\s*:\s*([a-z0-9\s]+?)\s+document\s+date/i',
                $text,
                $m
            )
        ) {
            return trim($m[1]);
        }

        return 'Guest';
    }

    private function extractOrientalIdv($text)
    {
        $text = preg_replace('/\s+/', ' ', $text);

        if (
            preg_match(
                '/basic\s+od\s+cover\s+([0-9,]+)/i',
                $text,
                $m
            )
        ) {
            return (float) str_replace(',', '', $m[1]);
        }

        return null;
    }

    private function extractOrientalPremium($text)
    {
        $text = preg_replace('/\s+/', ' ', $text);

        if (
            preg_match(
                '/total\s+amount\s+([0-9,]+(?:\.\d+)?)/i',
                $text,
                $m
            )
        ) {
            return (float) str_replace(',', '', $m[1]);
        }

        return null;
    }

    private function extractOrientalVehicleNumber($text)
    {
        $text = strtoupper($text);

        $text = preg_replace('/\s+/', ' ', $text);

        $patterns = [
            '/REGISTRATION\s*NO\.?\s*[:\-]?\s*([A-Z]{2}[0-9]{1,2}[A-Z]{1,3}[0-9]{3,4})/i',
            '/REG\.?\s*NO\.?\s*[:\-]?\s*([A-Z]{2}[0-9]{1,2}[A-Z]{1,3}[0-9]{3,4})/i',
            '/VEHICLE\s*NO\.?\s*[:\-]?\s*([A-Z]{2}[0-9]{1,2}[A-Z]{1,3}[0-9]{3,4})/i',
            '/\b([A-Z]{2}[0-9]{1,2}[A-Z]{1,3}[0-9]{3,4})\b/'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $m)) {
                return preg_replace('/[^A-Z0-9]/', '', $m[1]);
            }
        }

        return 'N/A';
    }

    private function detectOrientalAddons($text, $addons)
    {
        $detected = [];

        foreach ($addons as $addon) {
            $detected[$addon->name] = false;
        }

        $text = strtolower($text);

        $map = [
            'Zero Depreciation' => [
                'nil_depreciation'
            ],
            'Consumables' => [
                'consumable'
            ],
            'Engine Protect' => [
                'engine_protect_cover'
            ],
            'Key Replacement' => [
                'key',
                'key_replace'
            ],
            'Loss of Personal Belongings' => [
                'personal_effects'
            ],
            'Tyre Protect' => [
                'tyre_and_rim_protect'
            ],
            'Return to Invoice' => [
                'return_to_invoice'
            ],
        ];

        foreach ($map as $addonName => $keywords) {
            foreach ($keywords as $keyword) {
                if (
                    preg_match(
                        '/' . preg_quote($keyword, '/') . '\s+([0-9,]+(?:\.\d+)?)/i',
                        $text,
                        $m
                    )
                ) {
                    $amount = (float) str_replace(',', '', $m[1]);

                    if ($amount > 0) {
                        $detected[$addonName] = true;
                    }
                }
            }
        }

        return $detected;
    }

    private function detectTataAigAddons($text, $addons)
    {
        $detected = [];

        // normalize
        $text = strtolower($text);

        $text = html_entity_decode($text);

        $text = preg_replace('/\s+/', ' ', $text);

        Log::info('TATA AIG TEXT', [
            'text' => substr($text, 0, 5000)
        ]);

        // default false
        foreach ($addons as $addon) {
            $detected[$addon->name] = false;
        }

        // addon keyword map
        $tataAddonMap = [
            'Zero Depreciation' => [
                'depreciation reimbursement'
            ],
            'Return to Invoice' => [
                'Return to invoice'
            ],
            'Consumables' => [
                'consumables expenses'
            ],
            'Road Side Assistance' => [
                'road side assistance'
            ],
            'Engine Protect' => [
                'engine secure'
            ],
            'Tyre Protect' => [
                'tyre secure'
            ],
            'Key Replacement' => [
                'key replacement'
            ],
            'Loss of Personal Belongings' => [
                'loss of personal belongings'
            ],
            'Emergency Hotel Accommodation' => [
                'emergency transport and hotel expenses'
            ],
            'Emergency Medical Expenses ' => [
                'emergency medical expenses'
            ],
            'Electric Vehicle Shield' => [
                'electric vehicle'
            ],
            'Battery Cover' => [
                'battery cover'
            ],
        ];

        foreach ($tataAddonMap as $addonName => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($text, strtolower($keyword))) {
                    $detected[$addonName] = true;

                    Log::info('TATA ADDON FOUND', [
                        'addon' => $addonName,
                        'keyword' => $keyword
                    ]);

                    break;
                }
            }
        }

        return $detected;
    }

    private function extractTataPremium($text)
    {
        $text = preg_replace('/\s+/', ' ', $text);

        if (
            preg_match(
                '/total\s+premium\s*₹?\s*([0-9,]+\.\d{2})\s*([0-9,]+\.\d{2})\s*([0-9,]+\.\d{2})/i',
                $text,
                $m
            )
        ) {
            return (float) str_replace(',', '', $m[3]);
        }

        return null;
    }

    private function extractDigitVehicleNumber($text)
    {
        // =========================
        // DIGIT PDFs usually don't contain reg number
        // so fallback
        // =========================

        $vehicleNumber = $this->extractVehicleNumber($text);

        if (
            empty($vehicleNumber) ||
            $vehicleNumber === 'N/A'
        ) {
            return 'null';
        }

        return $vehicleNumber;
    }

    /**
     * COMPANY DETECTION
     */
    private function extractCompanyName($text)
    {
        // LOWERCASE TEXT
        $text = strtolower($text);

        // normalize spaces (IMPORTANT)
        $text = preg_replace('/\s+/', ' ', $text);

        $companies = [
            'oriental insurance company' => 'Oriental Insurance',
            'oriental insurance' => 'Oriental Insurance',
            'www.orientalinsurance.org.in' => 'Oriental Insurance',
            // ZUNO
            'zuno general insurance' => 'Zuno General Insurance',
            'zuno private car package policy' => 'Zuno General Insurance',
            'hizuno.com' => 'Zuno General Insurance',
            'zuno' => 'Zuno General Insurance',
            'bajaj allianz' => 'Bajaj Allianz',
            'bajaj general insurance' => 'Bajaj Allianz',
            'bajaj' => 'Bajaj Allianz',
            'Bajaj General Insurance Limited' => 'Bajaj Allianz',
            'hdfc ergo' => 'HDFC Ergo',
            'shriram general insurance co. ltd' => 'Shriram General Insurance',
            'shriram general insurance' => 'Shriram General Insurance',
            'shriram' => 'Shriram General Insurance',
            'icici lombard' => 'ICICI Lombard',
            'new india' => 'New India',
            'new india assurance' => 'New India',
            'tata aig' => 'Tata AIG',
            'tata aig general insurance' => 'Tata AIG',
            'liberty' => 'Liberty General',
            'liberty general' => 'Liberty General',
            'liberty insurance' => 'Liberty General',
            'digit insurance' => 'Digit Insurance',
            'go digit' => 'Digit Insurance',
            'digit' => 'Digit Insurance',
            'reliance' => 'Reliance General',
            'reliance general' => 'Reliance General',
            'royal sundaram' => 'Royal Sundaram',
            'royal sundaram general insurance' => 'Royal Sundaram',
            'royal sundaram alliance insurance' => 'Royal Sundaram',
            'icicilombard' => 'ICICI Lombard',
            'www.icicilombard.com' => 'ICICI Lombard',
            'bajaj general insurance limited' => 'Bajaj Allianz',
            'bajajgeneral.com' => 'Bajaj Allianz',
            'oriental' => 'Oriental Insurance',
            'risk details' => 'Oriental Insurance',
        ];

        foreach ($companies as $key => $name) {
            if (stripos($text, $key) !== false) {
                return $name;
            }
        }

        return 'Unknown';
    }

    private function extractIciciRecommendedPlanText($text)
    {
        // normalize
        $text = html_entity_decode($text);

        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');

        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);

        $text = str_replace(["\n", "\r", "\t"], ' ', $text);

        $text = preg_replace('/\s+/', ' ', $text);

        // =========================
        // FIND RECOMMENDED PLAN
        // =========================

        $pos = stripos($text, 'Recommended');

        if ($pos === false) {
            Log::warning('ICICI RECOMMENDED PLAN NOT FOUND');

            return $text;
        }

        // take nearby chunk
        $chunk = substr($text, $pos, 5000);

        Log::info('ICICI RECOMMENDED CHUNK', [
            'chunk' => substr($chunk, 0, 3000)
        ]);

        return $chunk;
    }

    private function extractCustomerName($text, $companyName)
    {
        // dd($text, str_contains(strtolower($companyName), 'digit'));
        $patterns = [];
        if (str_contains(strtolower($companyName), 'liberty')) {
            $patterns = [
                '/customer\s*name\s*([A-Z\s]+?)\s+quotation/i',
            ];
        }
        // elseif (str_contains(strtolower($companyName), 'digit')) {
        //     $patterns = [
        //         "/here's what you want\.\s*[:\-]?\s*([A-Z\s]+)/i",
        //     ];
        // }
        else {
            $patterns = [
                '/client\s*name[\s:]*([a-z\s]+)/i',
                '/name\s*[:\-]?\s*([A-Z\s]+)/i',
                '/insured\s*name\s*[:\-]?\s*([A-Z\s]+)/i',
                '/customer\s*name\s*[:\-]?\s*([A-Z\s]+)/i',
                '/proposer\s*name\s*[:\-]?\s*([A-Z\s]+)/i',
                '/client\s*name\s*[:\-]?\s*([A-Z\s]+)/i',
                '/customer\s*name[\s:]*([a-z\s]+)/i',
                '/name\s+of\s+proposer\s+mr\.?\s*([A-Z\s]+)/i',
                '/insured\s*name[\s:]*([a-z\s]+)/i',
                '/proposer\s*name[\s:]*([a-z\s]+)/i',
                // '/name[\s:]*([A-Z\s]+)/i',
                '/customer\s*name\s*([A-Z\s]+?)\s+quotation/i',
                '/name\s+of\s+proposer\s+mr\.?\s*([A-Z\s]+)/i',
                '/client\s*name[\s:]*([A-Z\s]+)/i',
                '/insured\s*name[\s:]*([A-Z\s]+)/i',
                '/proposer\s*name[\s:]*([A-Z\s]+)/i',
            ];
        }
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $name = strtolower($matches[1]);
                $name = preg_replace(
                    '/(producer|proposer|email|policy|type|rollover|imd|code|number).*/i',
                    '',
                    $name
                );

                // cleanup
                $name = preg_replace('/[^a-z\s]/i', '', $name);
                $name = preg_replace('/\s+/', ' ', $name);
                $name = trim($name);

                if (str_word_count($name) >= 2) {
                    return ucwords($name);
                }
            }
        }

        return 'Guest';
    }

    // private function extractVehicleNumber($text)
    // {
    //     // normalize
    // $text = strtoupper($text);

    // $text = str_replace(["\n", "\r", "\t"], ' ', $text);

    // $text = preg_replace('/\s+/', ' ', $text);

    // // ❌ invalid keywords
    // $invalidWords = [
    //     'CHASSIS',
    //     'ENGINE',
    //     'MODEL',
    //     'POLICY',
    //     'QUOTE',
    //     'PREMIUM',
    //     'IDV'
    // ];
    //     $patterns = [
    //         // Registration Mark & Place of Registration: HR10AL7564
    //         '/REGISTRATION\s*MARK\s*&\s*place\s*of\s* Registration.*?:\s*([A-Z]{2}[0-9]{1,2}[A-Z]{1,3}[0-9]{3,4})/i',

    //         // Registration No: KA-05-AQ-7173
    //         '/REGISTRATION\s*NO\.?\s*[:\-]?\s*([A-Z]{2}\s*-?\s*\d{1,2}\s*-?\s*[A-Z]{1,3}\s*-?\s*\d{3,4})/i',

    //         // Vehicle No
    //         '/VEHICLE\s*NO\.?\s*[:\-]?\s*([A-Z]{2}\s*-?\s*\d{1,2}\s*-?\s*[A-Z]{1,3}\s*-?\s*\d{3,4})/i',
    //         '/REGISTRATION\s*NO\.?\s*[:\-]?\s*([A-Z]{2}\s*-\s*\d{1,2}\s*-\s*[A-Z]{1,3}\s*-\s*\d{3,4})/i',

    //         '/REG\.?\s*NO\.?\s*[:\-]?\s*([A-Z]{2}\s*-\s*\d{1,2}\s*-\s*[A-Z]{1,3}\s*-\s*\d{3,4})/i',

    //         '/VEHICLE\s*NO\.?\s*[:\-]?\s*([A-Z]{2}\s*-\s*\d{1,2}\s*-\s*[A-Z]{1,3}\s*-\s*\d{3,4})/i',
    //         '/vehicle\s*number[\s:]*([a-z0-9\s-]+)/i',
    //         '/registration\s*number[\s:]*([a-z0-9\s-]+)/i',
    //         '/regn\.?\s*no\.?[\s:]*([a-z0-9\s-]+)/i',
    //         '/reg\.?\s*no\.?[\s:]*([a-z0-9\s-]+)/i',
    //         // KA-03-NW-5060
    //         '/([A-Z]{2}\s*-\s*\d{1,2}\s*-\s*[A-Z]{1,3}\s*-\s*\d{3,4})/i',

    //         // KA03NW5060
    //         '/([A-Z]{2}[0-9]{1,2}[A-Z]{1,3}[0-9]{3,4})/i',
    //         // '/registration number\s*([A-Z0-9]+)/i',

    //         '/vehicle\s*number[\s:]*([a-z0-9\s-]+)/i',

    //         '/registration\s*no[\s:]*([a-z0-9\s-]+)/i',

    //         '/regn\.?\s*no\.?[\s:]*([a-z0-9\s-]+)/i',

    //         '/reg\.?\s*no\.?[\s:]*([a-z0-9\s-]+)/i',

    //         '/([A-Z]{2}\s*-\s*\d{1,2}\s*-\s*[A-Z]{1,3}\s*-\s*\d{3,4})/i',

    //         '/([A-Z]{2}[0-9]{1,2}[A-Z]{1,3}[0-9]{3,4})/i',
    //         // '/reg\.?\s*no\.?\s*[:\-]?\s*([A-Z0-9\-]+)/i',
    //         '/registration\s*no\.?\s*[:\-]?\s*([A-Z0-9\-]+)/i',
    //         '/regn\.?\s*no\.?\s*[:\-]?\s*([A-Z0-9\-]+)/i',
    //         '/\b([A-Z]{2}[0-9]{1,2}[A-Z]{1,3}[0-9]{3,4})\b/',
    //     ];

    //     foreach ($patterns as $pattern) {
    //         if (preg_match($pattern, $text, $matches)) {

    //             $raw = strtoupper($matches[1]);

    //             // 🔥 CLEAN STEP 1: remove extra words
    //             $raw = preg_replace('/(POLICY|PLAN|MAKE|MODEL|VARIANT|EMAIL|PRODUCER|TYPE).*/i', '', $raw);

    //             // 🔥 CLEAN STEP 2: remove non-alphanumeric
    //             $raw = preg_replace('/[^A-Z0-9]/', '', $raw);

    //             // 🔥 VALIDATE INDIAN VEHICLE FORMAT
    //             if (preg_match('/^[A-Z]{2}[0-9]{1,2}[A-Z]{1,3}[0-9]{3,4}$/', $raw)) {
    //                 return $raw;
    //             }

    //             // fallback if partial
    //             return substr($raw, 0, 10);
    //         }
    //     }

    //     return null;
    // }

    /**
     * PREMIUM EXTRACTION
     */
    private function extractVehicleNumber($text)
    {
        // =========================
        // NORMALIZE TEXT
        // =========================

        $text = strtoupper($text);

        $text = str_replace(["\n", "\r", "\t"], ' ', $text);

        $text = preg_replace('/\s+/', ' ', $text);

        // =========================
        // INVALID WORDS
        // =========================

        $invalidWords = [
            'CHASSIS',
            'CHASSISNO',
            'ENGINE',
            'ENGINENO',
            'MODEL',
            'POLICY',
            'QUOTE',
            'PREMIUM',
            'IDV',
            'CUBIC',
            'SEATING',
            'MOBILE',
            'PHONE',
            'EMAIL',
            'CUSTOMER',
            'INSURED',
            'PROPOSER',
            'NAME',
            'ADDRESS',
            'PINCODE',
            'PETROL',
            'DIESEL',
            'MANUFACTURING',
            'REGISTRATION',
            'VEHICLE',
            'MAKE',
            'VARIANT',
            'TYPE'
        ];

        // =========================
        // STRONG PATTERNS FIRST
        // =========================

        $patterns = [
            // REGISTRATION MARK
            '/REGISTRATION\s*MARK[^A-Z0-9]*([A-Z]{2}\d{1,2}[A-Z]{1,3}\d{3,4})/i',
            // REGISTRATION NO
            '/REGISTRATION\s*NO\.?[^A-Z0-9]*([A-Z]{2}\s*-?\s*\d{1,2}\s*-?\s*[A-Z]{1,3}\s*-?\s*\d{3,4})/i',
            // REG NO
            '/REG\.?\s*NO\.?[^A-Z0-9]*([A-Z]{2}\s*-?\s*\d{1,2}\s*-?\s*[A-Z]{1,3}\s*-?\s*\d{3,4})/i',
            // REGN NO
            '/REGN\.?\s*NO\.?[^A-Z0-9]*([A-Z]{2}\s*-?\s*\d{1,2}\s*-?\s*[A-Z]{1,3}\s*-?\s*\d{3,4})/i',
            // VEHICLE NO
            '/VEHICLE\s*NO\.?[^A-Z0-9]*([A-Z]{2}\s*-?\s*\d{1,2}\s*-?\s*[A-Z]{1,3}\s*-?\s*\d{3,4})/i',
            // VEHICLE NUMBER
            '/VEHICLE\s*NUMBER[^A-Z0-9]*([A-Z]{2}\s*-?\s*\d{1,2}\s*-?\s*[A-Z]{1,3}\s*-?\s*\d{3,4})/i',
            // DIRECT FORMAT WITH DASH
            '/\b([A-Z]{2}\s*-\s*\d{1,2}\s*-\s*[A-Z]{1,3}\s*-\s*\d{3,4})\b/i',
            // DIRECT FORMAT WITHOUT DASH
            '/\b([A-Z]{2}\d{1,2}[A-Z]{1,3}\d{3,4})\b/i',
        ];

        // =========================
        // MATCH LOOP
        // =========================

        foreach ($patterns as $pattern) {
            if (preg_match_all($pattern, $text, $matches)) {
                foreach ($matches[1] as $match) {
                    $raw = strtoupper(trim($match));

                    // REMOVE SPACES/DASHES
                    $raw = preg_replace('/[^A-Z0-9]/', '', $raw);

                    // =========================
                    // INVALID WORD CHECK
                    // =========================

                    $isInvalid = false;

                    foreach ($invalidWords as $badWord) {
                        if (str_contains($raw, $badWord)) {
                            $isInvalid = true;

                            break;
                        }
                    }

                    if ($isInvalid) {
                        continue;
                    }

                    // =========================
                    // FINAL INDIAN VEHICLE VALIDATION
                    // =========================

                    if (
                        preg_match(
                            '/^[A-Z]{2}[0-9]{1,2}[A-Z]{1,3}[0-9]{3,4}$/',
                            $raw
                        )
                    ) {
                        Log::info('VEHICLE NUMBER FOUND', [
                            'vehicle_number' => $raw
                        ]);

                        return $raw;
                    }
                }
            }
        }

        Log::warning('NO VEHICLE NUMBER FOUND');

        return 'N/A';
    }

    private function extractIciciRecommendedPremium($text)
    {
        // CLEAN
        $text = html_entity_decode($text);

        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');

        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);

        $text = str_replace(["\n", "\r", "\t"], ' ', $text);

        $text = preg_replace('/\s+/', ' ', $text);

        // FIND SECTION
        $pos = stripos($text, 'Total Premium Payable');

        if ($pos === false) {
            Log::warning('TOTAL PREMIUM PAYABLE NOT FOUND');

            return null;
        }

        // NEARBY CHUNK
        $chunk = substr($text, $pos, 300);

        Log::info('ICICI TOTAL PREMIUM CHUNK', [
            'chunk' => $chunk
        ]);

        // EXTRACT AMOUNTS
        preg_match_all(
            '/([0-9,]+\.\d{2})/',
            $chunk,
            $matches
        );

        Log::info('ICICI TOTAL PREMIUM MATCHES', [
            'amounts' => $matches[1] ?? []
        ]);

        if (!empty($matches[1])) {
            $amounts = $matches[1];

            // FORMAT:
            // [Basic Plan, Recommended Plan]

            if (isset($amounts[1])) {
                return (float) str_replace(',', '', $amounts[1]);
            }

            return (float) str_replace(',', '', $amounts[0]);
        }

        return null;
    }

    private function extractPlanCPremium($text)
    {
        // normalize
        $text = str_replace(["\n", "\r", "\t"], ' ', $text);

        $text = preg_replace('/\s+/', ' ', $text);

        // safer lowercase copy
        $lowerText = strtolower($text);

        // find keyword safely
        $pos = stripos($lowerText, 'total premium payable');

        if ($pos !== false) {
            $chunk = substr($text, $pos, 250);

            Log::info('PLAN C CHUNK', [
                'chunk' => $chunk
            ]);

            // extract all premium amounts
            preg_match_all(
                '/([0-9,]+\.\d{2})/',
                $chunk,
                $matches
            );

            Log::info('PLAN C PREMIUM MATCHES', $matches);

            // usually last 3 values are A B C
            if (!empty($matches[1]) && count($matches[1]) >= 3) {
                $premiums = $matches[1];

                $planC = end($premiums);

                return (float) str_replace(',', '', $planC);
            }
        }

        return null;
    }

    private function extractPremium($text)
    {
        // =========================
        // NORMALIZE TEXT
        // =========================

        $text = str_replace(
            ["\n", "\r", "\t"],
            ' ',
            $text
        );

        $text = preg_replace('/\s+/', ' ', $text);

        // =========================
        // PREMIUM PATTERNS
        // =========================

        $patterns = [
            '/TOTAL\s*AMOUNT[^0-9]{0,20}([0-9,]+(?:\.\d{1,2})?)/i',
            '/TOTAL\s*PAYABLE\s*PREMIUM[^0-9]{0,20}([0-9,]+(?:\.\d{1,2})?)/i',
            '/TOTAL\s*PREMIUM\s*PAYABLE[^0-9]{0,20}([0-9,]+(?:\.\d{1,2})?)/i',
            '/FINAL\s*PREMIUM[^0-9]{0,20}([0-9,]+(?:\.\d{1,2})?)/i',
            '/TOTAL\s*POLICY\s*PREMIUM[^0-9]{0,20}([0-9,]+(?:\.\d{1,2})?)/i',
            '/TOTAL\s*PREMIUM[^0-9]{0,20}([0-9,]+(?:\.\d{1,2})?)/i',
            '/NET\s*PREMIUM[^0-9]{0,20}([0-9,]+(?:\.\d{1,2})?)/i',
            '/PREMIUM\s*PAYABLE[^0-9]{0,20}([0-9,]+(?:\.\d{1,2})?)/i',
            '/OWN\s*DAMAGE\s*PREMIUM[^0-9]{0,20}([0-9,]+(?:\.\d{1,2})?)/i',
        ];

        // =========================
        // MATCH LOOP
        // =========================

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $amount = str_replace(',', '', $matches[1]);

                Log::info('PREMIUM FOUND', [
                    'pattern' => $pattern,
                    'amount' => $amount
                ]);

                if (
                    is_numeric($amount) &&
                    (float) $amount > 1000
                ) {
                    return (float) $amount;
                }
            }
        }

        Log::warning('PREMIUM NOT FOUND');

        return null;
    }

    private function extractIdv($text)
    {
        // normalize text
        $text = str_replace(["\n", "\r", "\t"], ' ', $text);

        $text = preg_replace('/\s+/', ' ', $text);

        $patterns = [
            // MOST IMPORTANT (Digit PDFs)
            '/insured\s*declared\s*value\s*\(idv\)\s*[₹rs\.\s]*([0-9,]+)/i',
            '/total\s*idv[\s\(\)a-z]*[:\-]?\s*([0-9,]+)/i',
            '/vehicle\s*idv.*?([0-9,]{5,})/i',
            '/total\s*idv.*?([0-9,]{5,})/i',
            '/idv\s*[:\-]?\s*₹?\s*([0-9,]{5,})/i',
            '/insured\s*declared\s*value.*?([0-9,]{5,})/i',
            '/sum\s*insured.*?([0-9,]{5,})/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $amount = str_replace(',', '', $matches[1]);

                // remove garbage chars
                $amount = preg_replace('/[^0-9]/', '', $amount);

                if (is_numeric($amount) && $amount > 10000) {
                    return (float) $amount;
                }
            }
        }
        if (preg_match('/idv.*?([0-9,]{5,})/i', $text, $m)) {
            $amount = preg_replace('/[^0-9]/', '', $m[1]);

            if (is_numeric($amount) && $amount > 10000) {
                return (float) $amount;
            }
        }

        return null;
    }

    /**
     * COMPARISON TABLE
     */
    private function generateComparisonTable($allQuotes, $addons)
    {
        $comparison = [];

        foreach ($addons as $addon) {
            $row = ['addon' => $addon->name];

            foreach ($allQuotes as $quote) {
                $row[$quote['quote_name']] =
                    $quote['addons'][$addon->name] ?? false;
            }

            $comparison[] = $row;
        }

        return $comparison;
    }
}
