<?php

use App\Models\Proposal;
use App\Models\Shriram\{Shriram_Pincode, Shriram_planCheckout, Shriram_RTO_Master};
use Illuminate\Support\Facades\{DB, Auth, Cache};
use Illuminate\Http\Request;
use App\Models\{User, MasterHealthAddon, MasterVendor, Master_Vehicle_Data as DataModel, SearchHistory, HealthJourney, NotificationModel, DigiPayment, ResponseLog};
use thiagoalessio\TesseractOCR\TesseractOCR;
use App\Services\Api\AIService;
use Spatie\Browsershot\Browsershot;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
// use Smalot\PdfParser\Parser;

function CallApi($url, $method = "POST", $payload, $header)
{
    try {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => $header,
        ]);

        $policypdfres = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return ["status" => false, 'message' => $err];
        }
        return ["status" => true, 'data' => $policypdfres];
    } catch (\Exception $e) {
        return ["status" => false, 'message' => $e->getMessage()];
    }
}
function getBothPincodeApi(Request $request)
{
    $userId = $request->userid;
    //$user = User::find($userId);
    $data = HealthJourney::where('userid', $userId)->where('vid', getconstant("HEALTH.CARESUPREME.KEY"))->first();
    $commaddress = json_decode($data['comunication_address']);

    $oldpincode = $commaddress->oldpincode ?? "";
    $compincode = $commaddress->commcurrentpincode ?? "";
    $status = "";
    if (($oldpincode == $compincode)) {
        $status = true;
    } else {
        $status = false;
    }
    return [
        'status' => $status,
        'ppincode' => $oldpincode,
        'cpincode' => $compincode
    ];
}
function getHealthAddonsApi($vid)
{
    $aDBMaster = MasterVendor::where('vid', $vid)->get(['healthaddons']);
    $aDBMaster = json_decode($aDBMaster[0]->healthaddons, true);
    $addonIds = array_keys($aDBMaster);
    $aAddons = MasterHealthAddon::whereIn('key', $addonIds)->pluck('addon', 'key')->toArray();
    return ['list' => $aAddons ?? [], 'code' => $aDBMaster ?? [], 'keys' => $addonIds];
}

function getRtocityApi(Request $request, $regno = null)
{
    $userId = $request->userid;
    $aData = DataModel::where('userid', $userId)->first();
    $sRegNumber = $regno ?? $aData->carnumber;
    //dd($sRegNumber);
    $sRegno1 = substr($sRegNumber, 0, 2);
    $sRegNo2 = substr($sRegNumber, 2, 2);
    $sRtono = $sRegno1 . '-' . $sRegNo2;
    //return $sRtono ?? null;
    $rtocity = Shriram_RTO_Master::where('RTOCODE', $sRtono)->first();
    return $rtocity ?? null;
}

function getBikeRtocityApi(Request $request, $regno = null)
{
    $userId = $request->userid;
    $aData = DataModel::where('userid', $userId)->first();
    $sRegNumber = $regno ?? $aData->bikenumber;
    //dd($sRegNumber);
    $sRegno1 = substr($sRegNumber, 0, 2);
    $sRegNo2 = substr($sRegNumber, 2, 2);
    $sRtono = $sRegno1 . '-' . $sRegNo2;
    // return $sRtono ?? null;
    $rtocity = Shriram_RTO_Master::where('RTOCODE', $sRtono)->first();
    return $rtocity ?? null;
}

function setPaCoverApi()
{
    $dataModelQuery = DataModel::where('userid', Auth::id());
    $dataModelQuery
        ->update([
            'pacover' => '1'
        ]);
}

function ValidateAddonAge(\DateTime $regDate, int $maxYears): bool
{
    //return  $regDate;
    $todayYear = (int) (new \DateTime())->format('Y');
    $regYear = (int) $regDate->format('Y');
    $carAge = $todayYear - $regYear;
    if ($carAge < 0) {
        return false;
    }
    return $carAge < $maxYears;
}

function NotificationModel($userId, $constKey = "")
{
    $paymentid = DigiPayment::where('userid', $userId)
        ->where('is_paid', '1')
        ->value('id');
    $status = 0;
    if ($paymentid) {
        $notification = new NotificationModel();
        $notification->paymentid = $paymentid;
        $notification->userid = $userId;
        $notification->message = $constKey;
        $notification->created_at = now();
        $notification->updated_at = now();
        $notification->status = $status;
        $notification->save();
        return true;
    }
    return false;
}

function UdateStatus(Request $request)
{
    $userId = $request->userid;
    $notificationId = $request->data['notificationId'];

    if ($notificationId) {
        $notification = NotificationModel::where('id', $notificationId)
            ->where('userid', $userId)
            ->first();

        if ($notification) {
            $notification->status = 1;
            $notification->updated_at = now();
            $notification->save();

            return [
                'success' => true,
                'updated' => $notification
            ];
        } else {
            return [
                'success' => false,
                'msg' => 'Notification not found.'
            ];
        }
    }

    if ($notificationId == false) {
        $updated = NotificationModel::where('userid', $userId)
            ->update([
                'status' => 1,
                'updated_at' => now()
            ]);

        return [
            'success' => true,
            'updated' => $updated
        ];
    }
    return [
        'success' => false,
        'msg' => 'Invalid notification ID.'
    ];
}


function Logfunction($userId, $vendor, $response, $req, $key)
{
    $log = new ResponseLog();
    $log->userid = $userId;
    $log->vendor = $vendor;
    $log->request = $req;
    $log->response = $response;
    $log->key = $key;
    $log->save();
}

function payment($userId, $policyNumber)
{
    $cacheproposalnumber = 'cache_proposalnumber_' . $userId;
    $proposalNumber = GetCache($cacheproposalnumber);

    $digi = DigiPayment::where('userid', $userId)
        ->where('proposal', $proposalNumber)
        ->first();

    if ($digi) {
        $digi->status = $policyNumber ? 'complete' : 'pending';
        $digi->save();
    }

    return $digi;
}
function convertHTMLToPdf($htmlFile)
{
    // 1. File ka path aur details nikaalna
    $fileInfo = pathinfo($htmlFile);

    // Sirf file ka naam (bina extension ke)
    $fileNameOnly = $fileInfo['filename'];

    // Directory jahan file rakhi hai
    $directory = $fileInfo['dirname'];

    // 2. Naya PDF path taiyar karna
    $pdfPath = $directory . '/' . $fileNameOnly . '.pdf';

    // 3. PDF generate aur save karna
    $htmlContent = File::get($htmlFile);
    $pdf = Pdf::loadHTML($htmlContent)->setOptions(['isRemoteEnabled' => true]);

    $pdf->save($pdfPath);

    // Final path return karein
    return $pdfPath;
}
// function fileToText($path)
// {
//     $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
//     $imageExtensions = ['jpg', 'jpeg', 'png', 'bmp', 'tiff', 'gif', 'webp'];
//     $text = "";
//     // 1. Handle Images
//     if (in_array($extension, $imageExtensions)) {
//         $text = (new TesseractOCR($path))
//             ->executable('/usr/bin/tesseract')
//             ->lang('eng')
//             ->run();
//     }
//     $htmlExt = ["HTML", "html"];
//     if (in_array($extension, $htmlExt)) {
//         $path = convertHTMLToPdf($path);
//     }
//     $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
//     if ($extension === 'pdf') {

//         try {

//             $parser = new \Smalot\PdfParser\Parser();
//             $pdf = $parser->parseFile($path);
//             $text = trim($pdf->getText());
//         } catch (\Exception $e) {
//             // Agar digital parse fail ho toh error log karein ya aage badhein
//         }

//         if (strlen($text) < 3) {
//             $tempDir = public_path('temp_ocr/');

//             // Folder check aur create karein
//             if (!file_exists($tempDir)) {
//                 mkdir($tempDir, 0777, true);
//             }

//             // Yeh variables command ke liye zaroori hain
//             $outputBase = $tempDir . uniqid('page_');
//             $outputPattern = $outputBase . "-%d.png";

//             $output = [];
//             $returnVar = 0;
//             // Command build karein
//             $command = "gs -dNOPAUSE -dBATCH -sDEVICE=png16m -r300 -sOutputFile=" . escapeshellarg($outputPattern) . " " . escapeshellarg($path) . " 2>&1";
//             exec($command, $output, $returnVar);
//             // Debugging agar command fail ho
//             if ($returnVar !== 0) {
//                 return [
//                     'Message' => 'ImageMagick/Ghostscript Error. Check policy.xml or installation.',
//                     'Command' => $command,
//                     'Error_Detail' => $output
//                 ];
//             }

//             // Bani hui images ko dhoondein
//             $files = glob($outputBase . "-*.png");

//             if (empty($files)) {
//                 return "Error: Images generate nahi ho payi. Path ya permissions check karein.";
//             }

//             // OCR process shuru karein
//             foreach ($files as $file) {
//                 try {
//                     $ocrInstance = new TesseractOCR($file);
//                     $text .= $ocrInstance->executable('/usr/bin/tesseract')
//                         ->lang('eng')
//                         ->run() . "\n";
//                 } catch (\Exception $e) {
//                     $text .= "[OCR Error on one page: " . $e->getMessage() . "]\n";
//                 }

//                 // Kaam khatam hone ke baad temporary image delete karein
//                 if (file_exists($file)) {
//                     unlink($file);
//                 }
//             }
//         }
//         return $text;
//     }
//     throw new \Exception("Unsupported file type: {$extension}");
// }

function fileToText($path)
{
    $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    $imageExtensions = ['jpg', 'jpeg', 'png', 'bmp', 'tiff', 'gif', 'webp'];
    $text = "";

    // 1. Handle HTML: Direct text extraction (No PDF conversion needed!)
    if (in_array($extension, ["html", "htm", "HTML"])) {
        $htmlContent = file_get_contents($path);
        // HTML tags remove karke saaf text nikalna
        return trim(html_entity_decode(strip_tags($htmlContent)));
    }

    // 2. Handle Images
    if (in_array($extension, $imageExtensions)) {
        return (new TesseractOCR($path))
            ->executable('/usr/bin/tesseract')
            ->lang('eng')
            ->run();
    }

    // 3. Handle PDF
    if ($extension === 'pdf') {
        // 1. Pehle digital parse try karein
        try {
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($path);
            $text = trim($pdf->getText());
        } catch (\Exception $e) {
            // Handle exception if needed
        }

        // 2. Agar digital parse fail ho (Scanned PDF ho)
        if (strlen($text) < 3) {
            $tempDir = public_path('temp_ocr/' . uniqid('pdf_', true) . '/');

            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0777, true);
            }

            try {
                // FASTEST IMAGE CONVERSION: pdftoppm use karein (Ghostscript se bohot tej hai)
                // Yeh command saare pages ko ek baar me png bana degi: page-1.png, page-2.png...
                $imagePrefix = $tempDir . 'page';
                $convertCmd = "pdftoppm -png -r 150 " . escapeshellarg($path) . " " . escapeshellarg($imagePrefix) . " 2>&1";
                exec($convertCmd, $output, $returnVar);

                if ($returnVar === 0) {
                    // TESSERACT OPTIMIZATION: Ek text file list banayein taaki Tesseract ek hi baar me sab read kar le
                    $images = glob($tempDir . 'page-*.png');
                    natsort($images); // Pages ko sahi order me sort karein (page-1, page-2...)

                    if (!empty($images)) {
                        $listFile = $tempDir . 'list.txt';
                        file_put_contents($listFile, implode("\n", $images));

                        // Tesseract ko list file de dein, yeh bina PHP loop ke ek baar me saara text nikal dega
                        $text = (new TesseractOCR($listFile))
                            ->executable('/usr/bin/tesseract')
                            ->lang('eng')
                            ->run();
                    }
                } else {
                    $text = "[PDF to Image conversion failed]";
                }
            } catch (\Exception $e) {
                $text = "[OCR Processing Error: " . $e->getMessage() . "]";
            } finally {
                // Kaam khatam hone ke baad temp folder aur uski saari files delete karein (Disk clean up)
                if (file_exists($tempDir)) {
                    array_map('unlink', glob("$tempDir/*.*"));
                    rmdir($tempDir);
                }
            }
        }

        return trim($text);
    }

    throw new \Exception("Unsupported file type: {$extension}");
}
