<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\PDFMail;
use App\Mail\SendMail;
use App\Models\{User, DocumentReview, Insure};
use App\Models\Review_aiPrompt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\Fpdi;
use Smalot\PdfParser\Parser;

class MailController extends Controller
{
    public function Savepdf(Request $request)
    {
        // $userId = '313';
        $userId = $request->userid;

        $request->validate([
            'policy' => 'required|file|mimes:pdf',
        ]);
        $pdf = $request->file('policy');
        // $pdf = $request->file('policy');
        $obj = DocumentReview::where('userid', $userId)->first();

        $fileName = time() . '_' . $pdf->getClientOriginalName();
        $pdf->move(public_path('upload/getdata'), $fileName);

        $absoluteFilePath = public_path('upload/getdata/' . $fileName);
        if ($obj) {
            $obj->document = json_encode(['policy' => $absoluteFilePath]);
            $obj->updated_at = now();
            $obj->save();
        } else {
            $obj = new DocumentReview();
            $obj->userid = $userId;
            $obj->document = json_encode(['policy' => $absoluteFilePath]);
            $obj->created_at = now();
            $obj->updated_at = now();
            $obj->save();
        }

        $policyNo = RedisGet('policy_number:' . $userId);

        // if (!$policyNo) {
        $response = $this->sendMail($request);
        // return $response;

        $data = $response->getData(true);

        if (!empty($data['status']) && $data['status'] == true) {
            // Decode JSON if string
            $decodedJson = $data['responce'];
            // return $decodedJson;

            if (is_string($decodedJson)) {
                $decodedJson = json_decode($decodedJson, true);
            }

            $pdf = Pdf::loadView('premiumtwo', [
                'report' => $decodedJson
            ])
                ->setPaper('a4', 'portrait')
                ->setOption('margin-top', 10)
                ->setOption('margin-bottom', 10)
                ->setOption('margin-left', 10)
                ->setOption('margin-right', 10)
                ->setOption('dpi', 96)
                ->setOption('isRemoteEnabled', true);

            $pdfData = $pdf->output();

            Mail::to('review.digibima@gmail.com')
                ->send(new SendMail($pdfData));

            return $pdf->stream('policy-report.pdf');
        }
        // }

        return response()->json([
            'status' => false,
            'message' => $data['error'] ?? 'Policy analysis failed'
        ]);

        // Mail::to('review.digibima@gmail.com')
        //     ->send(new PDFMail($absoluteFilePath));

        // return response()->json([
        //     'status' => true,
        //     'message' => 'PDF uploaded successfully'
        // ]);
    }

    public function sendMail(Request $request)
    {
        try {
            $userId = '443';
            // $userId = $request->userid;
            $obj = DocumentReview::where('userid', $userId)->first();
            if (!$obj) {
                return response()->json(['error' => 'Document not found'], 404);
            }
            $document = json_decode($obj->document, true);
            $filePath = $document['policy'] ?? null;
            if (!$filePath || !file_exists($filePath)) {
                return response()->json([
                    'error' => 'File not found on server',
                    'path' => $filePath
                ], 404);
            }
            $input = escapeshellarg($filePath);
            $tmpPdf = '/tmp/clean_' . uniqid() . '.pdf';
            exec(
                "qpdf --password='' --decrypt $input " . escapeshellarg($tmpPdf) . ' 2>&1',
                $out,
                $code
            );
            if (!file_exists($tmpPdf)) {
                return response()->json([
                    'error' => 'PDF could not be processed',
                    'details' => implode("\n", $out)
                ], 400);
            }
            $parser = new Parser();
            $pdf = $parser->parseFile($tmpPdf);
            $extractedText = trim($pdf->getText());

            // Force UTF-8 encoding safely
            $extractedText = mb_convert_encoding($extractedText, 'UTF-8', 'UTF-8');
            // Remove invalid UTF-8 characters
            $extractedText = iconv('UTF-8', 'UTF-8//IGNORE', $extractedText);
            // Remove control characters except new lines
            $extractedText = preg_replace('/[^\P{C}\n]+/u', '', $extractedText);
            unlink($tmpPdf);
            if ($extractedText === '') {
                return response()->json([
                    'error' => 'No extractable text found. PDF appears to be scanned.'
                ], 422);
            }

            $inputText = substr($extractedText, 0, 12000);

            $response = Http::withHeaders([
                'Authorization' => env('OPEN_API'),
                'Content-Type' => 'application/json',
            ])
                ->timeout(120)
                ->connectTimeout(30)
                ->post('https://api.openai.com/v1/responses', [
                    'model' => 'gpt-4.1-nano',
                    'max_output_tokens' => 128000,
                    'text' => [
                        'format' => [
                            'type' => 'json_object'
                        ]
                    ],
                    'instructions' => 'You are an expert Health Insurance analyst. STRICTLY return output in JSON format only (no extra text). Output must be a single valid JSON object.

Scope:
- Review ONLY Health Insurance policies. If the document is not a Health Insurance policy, return JSON with an error message and stop.

Important exclusions (do NOT use these for recommendation, plan ranking, or comparison):
- Ignore "Double Sum Insured", "Lock the Clock", "Age Lock", "Freeze your age" benefits. 
- You may mention them only if they exist in the policy as "additional benefit (ignored for recommendation)", but do NOT include them in scoring or selecting plans.

Tasks:
1) Review the uploaded Health Insurance policy and extract key details.
2) Compare:
   - "Existing plan opted by user" vs "Actual complete set of add-ons/features available in the SAME plan".
   - Identify opted vs not opted add-ons.
   - Recommend missing add-ons that fit the user profile and family structure.
3) You have Strictly Suggest up to 2 Different better alternative plans and dont suggest the plan which is exiting from ONLY this list:
   (i) Care Supreme 
   (ii) Star Assure 
   (iii) Star Super Star (preferred)
   (iv) Ultimate Care 
   (v) Tata AIG Medicare Select 
   (vi) Tata AIG Medicare Premier 
   (vii) Aditya Birla Activ One
   (viii) Bajaj My Health Care 
   (ix) Niva Bupa Reassure 2.0
   (x) Niva Bupa Reassure 3.0 
   (xi) HDFC Optima Secure 
   (xii) ICICI Lombard Elevate 
   (xiii) Star Women Care  

Plan suggestion rules:
- Do NOT give preference based on claim ratio.
- Compare approximate premiums: existing premium vs suggested plan premium; prefer similar premium options. If all are much higher, you may still suggest the best value options.
- Family rule override:
  - If family composition is 2 adults + 1 child → alternate_better_plan_suggestions MUST include ONLY "Star Assure" (max 1 plan).
  - If family composition is 2 adults + 2 children → you may suggest any plan(s) from allowed list (max 2).
- Strictly Extract each and every details of the existing plan 

Coverage comparison requirement (MUST INCLUDE ALL 29 every time):
Always output ALL the following 29 coverages/features in comparison_table and detailed_comparison_existing_vs_all_addons.
If any field is missing in policy or not applicable, set value exactly to Not Available.

List of 29 coverages/features:
1. Room Rent
2. Professional fees
3. ICU
4. Automatic Restoration
5. Cumulative Bonus/No Claim Bonus
6. Organ Donor Expenses
7. Dental Check-up
8. Tele-consultation
9. Home Care Treatment
10. Domiciliary Hospitalisation
11. Pre Hospitalisation
12. Post Hospitalisation
13. Day care Treatment
14. Modern Treatments
15. AYUSH Treatment
16. Road Ambulance
17. Air Ambulance
18. Premium Waiver
19. Wellness Program
20. Domestic Medical Second Opinion
21. Consumables
22. International Second Opinion
23. Annual Health Check-up
24. Limitless Care
25. Future Shield
26. Maternity Expenses
27. Quick Shield
28. Compassionate Visit
29. Freeze your age 

Table Rules:
- comparison_table: MUST strictly contain exactly 4 columns for each of the 29 features:
  * feature: The name of the coverage
  * existing_policy: Value from the current uploaded policy
  * plan_recommendation_1: Value from the first suggested alternative plan
  * plan_recommendation_2: Value from the second suggested alternative plan

- detailed_comparison_existing_vs_all_addons: Same structure as comparison_table with all 29 features

- detail_Description_for_all_addon: Array of objects with feature and description for all 29 features. Take descriptions from general insurance knowledge.

- recommendations_table: Array of objects with plan, sum_insured, and description for why user should choose this plan

- not_opted_addons: Array showing only those add-ons that exist for that particular policy and were not selected by user

Output JSON schema (must include all keys exactly as shown):
{
  "policyholder_details": {
    "name": "extracted name",
    "address": "extracted address",
    "date_of_birth": "extracted DOB",
    "gender": "extracted gender",
    "age": extracted age,
    "client_id": "extracted client ID",
    "state_code": extracted state code,
    "policy_number": "extracted policy number",
    "coverage_type": "Individual/Family Floater",
    "policy_start_date": "extracted start date",
    "policy_end_date": "extracted end date",
    "premium_paid": extracted premium,
    "premium_payment_mode": "extracted mode",
    "nominee": {
      "name": "extracted nominee name",
      "relationship": "extracted relationship",
      "age": extracted age,
      "claim_percentage": extracted percentage
    }
  },
  "insured_members": [
    {
      "name": "member name",
      "date_of_birth": "member DOB",
      "age": member age,
      "relationship": "relationship",
      "pre_existing_diseases": ["disease1", "disease2"],
      "coverage_since": "coverage start date"
    }
  ],
  "existing_plan": {
    "plan_name": "extracted plan name",
    "cover_type": "Individual/Family Floater",
    "policy_period_start": "start date",
    "policy_period_end": "end date",
    "sum_insured": sum insured amount,
    "premium": premium amount
  },
  "all_actual_addons_available_in_plan": ["addon1", "addon2", ...],
  "opted_addons_by_user": ["opted addon1", "opted addon2", ...],
  "not_opted_addons": ["not opted addon1", "not opted addon2", ...],
  "recommendations_on_addons": {
    "recommended_addons": ["addon1", "addon2"],
    "reason": "reason for recommendation"
  },
  "detailed_comparison_existing_vs_all_addons": [
    {"feature": "Room Rent", "existing_policy": "value", "plan_recommendation_1": "value", "plan_recommendation_2": "value"},
    ... (all 29 features)
  ],
  "alternate_better_plan_suggestions": ["plan1", "plan2"],
  "recommendations_table": [
    {"plan": "plan name", "sum_insured": "amount", "description": "description"},
    {"plan": "plan name", "sum_insured": "amount", "description": "description"}
  ],
  "summary_recommendation": "detailed summary recommendation text",
  "detail_Description_for_all_addon": [
    {"feature": "Room Rent", "description": "description"},
    ... (all 29 features)
  ],
  "disclaimer": "This is an AI-generated report. For the best advice, please contact your insurance advisor. The response from Digibima Company is not related to this matter."
}

Formatting rules:
- JSON only. No markdown, no extra text.
- Use consistent wording: Not Available for missing values.
- Keep tables complete with all 29 features.
- Strictly suggest 2 plans in recommendations_table.
- If the PDF text looks incomplete (scanned) or insufficient, return JSON with an error and what is missing.',
                    'input' => 'This is a JSON analysis task.
            Analyze the following insurance policy document and respond in JSON only:
            ' . $extractedText
                ]);

            $rawText = $response->json()['output'][0]['content'][0]['text'];
            $decodedJson = json_decode($rawText, true);

            // ------------------------------------------------------------
            // DATABASE OVERRIDE: Replace recommendations_table with actual data from Review_aiPrompt table
            // ------------------------------------------------------------
            $suggestedPlanNames = $decodedJson['alternate_better_plan_suggestions'] ?? [];

            if (!empty($suggestedPlanNames)) {
                $plansFromDb = Review_aiPrompt::whereIn('product_name', $suggestedPlanNames)->get();
                $recommendations = [];

                foreach ($plansFromDb as $plan) {
                    // Decode sum_insured JSON (e.g., "[500000, 700000, ...]")
                    $sumInsuredOptions = json_decode($plan->sum_insured, true);
                    // Decode features JSON (array of objects)
                    $featuresArray = json_decode($plan->features, true);

                    // Choose the first sum insured value (or customize logic)
                    $selectedSumInsured = is_array($sumInsuredOptions) ? ($sumInsuredOptions[0] ?? 'Not Available') : 'Not Available';

                    // Build description from feature names (e.g., "Infinity Bonus, In-Patient Care, ...")
                    $featureNames = [];
                    if (is_array($featuresArray)) {
                        foreach ($featuresArray as $feature) {
                            $featureNames[] = $feature['name'] ?? '';
                        }
                    }
                    $description = 'Key features: ' . implode(', ', array_filter($featureNames));

                    $recommendations[] = [
                        'plan' => $plan->product_name,
                        'sum_insured' => $selectedSumInsured,
                        'description' => $description,
                    ];
                }

                // Replace the AI-generated recommendations_table with our database values
                if (!empty($recommendations)) {
                    $decodedJson['recommendations_table'] = $recommendations;
                }

                // Optional: also sync the alternate_better_plan_suggestions to ensure only plans that exist in DB are listed
                $dbPlanNames = $plansFromDb->pluck('product_name')->toArray();
                if (!empty($dbPlanNames)) {
                    $decodedJson['alternate_better_plan_suggestions'] = $dbPlanNames;
                }
            }

            $policyNumber = $decodedJson['policyholder_details']['policy_number'] ?? '';
            RedisSet('policy_number:' . $userId, $policyNumber);

            return response()->json([
                'status' => true,
                'responce' => $decodedJson
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function sendresponce(Request $request)
    {
        $userId = $request->userid;
        $obj = DocumentReview::where('userid', $userId)->first();

        if (!$obj) {
            return response()->json(['error' => 'Document not found'], 404);
        }
        $document = json_decode($obj->document, true);
        $filePath = $document['policy'] ?? null;

        if (!$filePath || !file_exists($filePath)) {
            return response()->json([
                'error' => 'File not found on server',
                'path' => $filePath
            ], 404);
        }
        $input = escapeshellarg($filePath);
        $tmpPdf = '/tmp/clean_' . uniqid() . '.pdf';
        exec(
            "qpdf --password='' --decrypt $input " . escapeshellarg($tmpPdf) . ' 2>&1',
            $out,
            $code
        );
        if (!file_exists($tmpPdf)) {
            return response()->json([
                'error' => 'PDF could not be processed',
                'details' => implode("\n", $out)
            ], 400);
        }
        $parser = new Parser();
        $pdf = $parser->parseFile($tmpPdf);
        $extractedText = trim($pdf->getText());

        // Force UTF-8 encoding safely
        $extractedText = mb_convert_encoding($extractedText, 'UTF-8', 'UTF-8');

        // Remove invalid UTF-8 characters
        $extractedText = iconv('UTF-8', 'UTF-8//IGNORE', $extractedText);

        // Remove control characters except new lines
        $extractedText = preg_replace('/[^\P{C}\n]+/u', '', $extractedText);
        unlink($tmpPdf);
        if ($extractedText === '') {
            return response()->json([
                'error' => 'No extractable text found. PDF appears to be scanned.'
            ], 422);
        }

        $response = Http::withHeaders([
            'Authorization' => env('OPEN_API'),
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/responses', [
            'model' => 'gpt-4.1-nano',
            'max_output_tokens' => 2000,
            'text' => [
                'format' => [
                    'type' => 'json_object'
                ]
            ],
            'instructions' => 'Return the response strictly in JSON format.',
            'input' => 'This is a JSON analysis task.
                    Analyze the following insurance policy document and respond in JSON only:
                    ' . $extractedText
        ]);

        $resp = $response->json();

        if (!isset($resp['output'][0]['content'][0]['text'])) {
            return response()->json([
                'error' => 'Invalid OpenAI response',
                'full_response' => $resp
            ], 500);
        }

        $rawText = $resp['output'][0]['content'][0]['text'];
        $decodedJson = json_decode($rawText, true);
        // $cover_type = $decodedJson['cover_type'] ?? '';
        // $sum_insured = $decodedJson['sum_insured'] ?? '';

        return response()->json($decodedJson);
    }

    public function TexttoPdf(Request $request)
    {
        $userId = '142';
        // $userId = $userId = $request->userid;

        $user = User::findOrFail($userId);

        $obj = DocumentReview::where('userid', $userId)->first();
        if (!$obj) {
            return response()->json(['error' => 'Document not found'], 404);
        }

        $document = json_decode($obj->document, true);

        if (!isset($document['policy'])) {
            return response()->json(['error' => 'Policy file missing'], 404);
        }

        $file = $document['policy'];

        // Resolve PDF path
        $existingPdfPath = str_contains($file, '/')
            ? $file
            : public_path('upload/getdata/' . $file);

        if (!file_exists($existingPdfPath)) {
            return response()->json(['error' => 'Policy PDF not found'], 404);
        }

        $insure = Insure::where('proposalid', $userId)->first();

        // Front page data
        $viewData = [
            'customer_name' => $user->name,
            'policy_number' => $document['policy_number'] ?? '',
            'policy_type' => $document['policy_type'] ?? '',
            'policy_period' => $document['policy_period'] ?? '',
            'insurer' => $insure->insurer_name ?? '',
            'insurer_address' => $insure->insurer_address ?? '',
            'insurer_irdai' => $insure->irdai_no ?? '',
            'insurer_cin' => $insure->cin ?? '',
            'insurer_phone' => $insure->helpline ?? '',
            'insurer_whatsapp' => $insure->whatsapp ?? '',
            'insurer_email' => $insure->email ?? '',
            'insurer_website' => $insure->website ?? '',
        ];

        // Generate front page
        $frontPdfPath = public_path("upload/frontpage_{$userId}.pdf");

        // Pdf::loadView('healthfrontpage', $viewData)
        //     ->save($frontPdfPath);

        // PDF::loadView('healthfrontpage', $viewData)
        //     ->setOption('isRemoteEnabled', true)
        //     ->save($frontPdfPath);

        // PDF::loadView('healthfrontpage', $viewData)
        //     ->setPaper('a4', 'portrait')
        //     ->setOption('dpi', 96)
        //     ->setOption('isRemoteEnabled', true)
        //     ->save($frontPdfPath);

        PDF::loadView('healthfrontpage', $viewData)
            ->setPaper('a4', 'portrait')
            ->setOption('margin-top', '0')
            ->setOption('margin-bottom', '0')
            ->setOption('margin-left', '0')
            ->setOption('margin-right', '0')
            ->setOption('dpi', 96)
            ->setOption('isRemoteEnabled', true)
            ->save($frontPdfPath);

        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($frontPdfPath);
        for ($i = 1; $i <= $pageCount; $i++) {
            $tpl = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tpl);
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($tpl);
        }

        $stream = StreamReader::createByFile($existingPdfPath);

        try {
            $pageCount = $pdf->setSourceFile($stream);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'PDF decryption failed',
                'details' => $e->getMessage()
            ], 500);
        }

        for ($i = 1; $i <= $pageCount; $i++) {
            $tpl = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tpl);
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($tpl);
        }

        $finalPath = public_path("upload/final_policy_{$userId}.pdf");
        $pdf->Output($finalPath, 'F');

        // return response()->download($finalPath);
        return response()->file($finalPath);
    }

    public function Download()
    {
        $pdf = PDF::loadView('premiumtwo')  // assign to variable
            ->setPaper('a4', 'portrait')
            ->setPaper('a4', 'portrait')
            ->setOption('margin-top', '0')
            ->setOption('margin-bottom', '0')
            ->setOption('margin-left', '0')
            ->setOption('margin-right', '0')
            ->setOption('dpi', 96)
            ->setOption('isRemoteEnabled', true);
        return $pdf->download('premium.pdf');
    }

    public function Viewpdf(Request $request)
    {
        $userId = '313';

        $response = $this->sendMail($request);

        $data = $response->getData(true);

        if (!empty($data['status']) && $data['status'] == true) {
            // Decode JSON if string
            $decodedJson = $data['responce'];
            // return $decodedJson;

            if (is_string($decodedJson)) {
                $decodedJson = json_decode($decodedJson, true);
            }

            $pdf = Pdf::loadView('premiumtwo', [
                'report' => $decodedJson
            ])
                ->setPaper('a4', 'portrait')
                ->setOption('margin-top', 10)
                ->setOption('margin-bottom', 10)
                ->setOption('margin-left', 10)
                ->setOption('margin-right', 10)
                ->setOption('dpi', 96)
                ->setOption('isRemoteEnabled', true);

            return $pdf->stream('policy-report.pdf');
        }

        if (empty($data['status']) && $data['status'] == true) {
            return response()->json([
                'status' => false,
                'message' => $data['error'] ?? 'Policy analysis failed'
            ]);
        }
    }
}
