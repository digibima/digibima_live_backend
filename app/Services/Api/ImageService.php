<?php

namespace App\Services\Api;
use App\Models\HealthUserDescription;
class ImageService
{
    public static function ExtractText()
    {

        // $filename = '11758698768_Retirement-Planning-Start-Early-to-Reap-Maximum-Benefits.jpg';
        // $filepath = public_path('document/' . $filename);

        // if (file_exists($filepath)) {
        //     return "File exists!";
        // } else {
        //     return "File does NOT exist!";
        // }
        try {
            $userDocument = HealthUserDescription::where('userid', '167')->first();

            if ($userDocument) {
                $userFile = json_decode($userDocument->document, true);
                $idetity = basename($userFile['identity']['identityfront']);
                $address = basename($userFile['address']['addressfront']);
                $fullPath = public_path('document/' . $idetity);
                $relativePath = 'public/document/' . $idetity;
                //return $relativePath;
                $text = "";
                if (file_exists($relativePath)) {
                    
                    $text = fileToText($idetity);
                    return $text;

                } else if (file_exists($address)) {
                    $text = fileToText($address);
                } else {
                    $text = "File not found";
                }
            }
            //return response()->json($text);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}

