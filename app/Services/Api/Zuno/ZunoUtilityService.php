<?php
namespace App\Services\Api\Zuno;

use stdClass;
use Carbon\Carbon;
use Illuminate\Support\Facades\{Auth, Cache};
use App\Http\Controllers\Api\front\motor\vendor\Zuno\Car\ZunoCarController;
use App\Http\Controllers\Api\front\motor\vendor\Zuno\Car\ZunoBikeController;
use App\Models\{UserMotorDescription};
use Illuminate\Http\Request;

class ZunoUtilityService
{
    private static $username = "ijc3rr25rsu65eha8audvpqeg";
    private static $password = "1abh34ras5dmjhekjqec7lblact6dokvde9cu9sfm57qiuet35l7";
    private static $apiKey = "jCe5bdB1ML2Q5vIAPOU8K2hRJvqlLtV23qoHGQlE";

    public static function generateToken()
    {
        try{
        $basicAuth = base64_encode(self::$username . ':' . self::$password);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://devapi.hizuno.com/oauth2/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Basic ' . $basicAuth
            ]
        ]);

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response, true);
    }
    catch(\Exception $e){
        return response()->json([
           'status' => false,
           'response' => $e->getMessage()
        ]);
    }
    }

    public static function ekyc()
    {
        try{
        $getToken = self::generateToken();
        $token = $getToken['access_token'];
        //return $token;
        $payload = json_encode([
            "source" => "DIGIBIMA",
            "leadId" => "test748uru81",
            "kycId" => [
                "idType" => "pan",
                "idNumber" => "Jbspk8786h"
            ],
            "proposer" => [
                "name" => "amandeep",
                "mobileNum" => "9844505621",
                "email" => "ikinsurance777@gmail.com",
                "proposerType" => "I",
                "gender" => "",
                "dobDoi" => "12/02/1999"
            ],
            "additional" => [
                "redirectUrl" => ""
            ]

        ]);

        $apiKey = self::$apiKey;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://devapi.hizuno.com/signzy/e-kyc',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                'Authorization:' . $token,
                'X-Api-Key:' . $apiKey,
                'version: 3',
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    catch(\Exception $e){
       return response()->json(
           [
            'status' => false,
            'response'=> $e->getMessage()
           ]
         );

    }
    }

     public static function FileIntoBase64($filePath)
    {
        $base64 = '';
        $image = '';
        $parts = explode("/", $filePath);
        $extension = explode(".", end($parts));
        if (file_exists($filePath)) {
            $image = file_get_contents($filePath);
            $base64 = base64_encode($image);
        } else {
            throw new \Exception("File not found.");
        }
        return ['extension' => '.' . end($extension), 'based64' => $base64];
    }

    public static function OVD(Request $request)
    { 
        try{
        $userId = $request->userid;
        $getToken = self::generateToken();
        $token = $getToken['access_token'];
        $sDocdetails = UserMotorDescription::where('userid', $userId);
        $aDocument = $sDocdetails->first('document');
        $sdoctype = $sDocdetails->first('idnumber');      
        $filePath = json_decode($aDocument->document, true);
        $identityPhotoB64 = self::FileIntoBase64($filePath['identity']['identityfront']);
        $identitybackB64 = self::FileIntoBase64($filePath['identity']['identityback']);
        $randnum = (string) random_int(1000, 99999999);
        $payload = json_encode([
            "leadId" => $randnum ?? "2336799",
            "source" => "DIGIBIMA",
            "proposer" => [
                "name" => "Yuvraj",
                "pan" => $pan ?? ""
            ],
            "uploads" => [
                "kycDocument" => [
                    "docType" => "aadhaar",
                    "extension" => "pdf",
                    "frontSide" => json_encode($identityPhotoB64['based64']) ?? "",
                    "backSide" => json_encode($identitybackB64['based64']) ?? ""
                ]
            ]
        ]);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://devapi.hizuno.com/signzy/extraction-of-documents',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                'Authorization:' . $token,
                'X-Api-Key:' . self::$apiKey,
                'version: 3',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }catch(\Exception $e){
        return response()->json(
            [
               'status'=> false,
               'response'=> $e->getMessage() 
            ]
        );
    }
    }


}






