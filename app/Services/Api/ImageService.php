<?php

namespace App\Services\Api;

use App\Services\Api\AIService;
use App\Models\HealthUserDescription;

class ImageService
{
    public static function ExtractText()
    {
        try {
            //$userDocument = HealthUserDescription::where('userid', '268')->first();
            $text = "";
            //$addressfullPath = public_path('document/' . "doc4.HTML");
            $addressfullPath = public_path('document/' . "b2.pdf");
            if (file_exists($addressfullPath)) {
                $text = fileToText($addressfullPath);
            }
            $AIService = new AIService();
            $prompt = <<<EOT
Extract information from the OCR text.

Return ONLY valid JSON.

Rules:
- No markdown
- No explanation
- Missing values = ""
- Do not guess

Field mapping:
- S/O or D/O → father_name
- 10 digit number → contact
- 6 digit number → pincode
- DOB → DD/MM/YYYY
- 16 digit number → vid
- 12 digit number → card number
JSON:

{
"name":"",
"dob":"",
"father_name":"",
"mother_name":"",
"address":"",
"contact":"",
"pincode":"",
"state":"",
"vid":""
"card number":""
}

OCR TEXT:
$text
EOT;
            $rawResponse = $AIService->OllamaAI($prompt);
            $responseText = $rawResponse['response'];
            $responseText = preg_replace('/```json|```/', '', $responseText);
            $responseText = trim($responseText);
            $data = json_decode($responseText, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return $data;
            }

            return [
                'error' => 'Invalid JSON',
                'raw' => $responseText
            ];
            return response()->json($text);
            // if ($userDocument) {
            //     $userFile = json_decode($userDocument->document, true);
            //     $idetity = basename($userFile['identity']['identityfront']);
            //     $address = basename($userFile['address']['addressfront']);
            //     $identityfullPath = public_path('document/' . $idetity);
            //     $addressfullPath = public_path('document/' . $address);
            //     $relativePath = 'public/document/' . $idetity;
            //     // if (file_exists($identityfullPath)) {
            //     //     return true;
            //     // }
            //     // else{
            //     //     return false;
            //     // }
            //     // return $fullPath;
            //     if (file_exists($identityfullPath)) {
            //         $text = fileToText($identityfullPath);
            //         //return $text;
            //     } else if (file_exists($addressfullPath)) {
            //         $text = fileToText($addressfullPath);
            //     } else {
            //         $text = "File not found";
            //     }
            // }
            return response()->json($text);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
