<?php

namespace App\Services\Api\BajajMotor;
use Illuminate\Http\Request;
use Firebase\JWT\Key;
use Firebase\JWT\JWT;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use DateTime;
class KycService
{
    private static $secretKey = 'kycwsbrkmotr2023';
    private static $iv = 'kycwsbrkmotr2023';
    private static $Id = "webservice@probusinsurance.in";
    private static $imdCode = "10105697";
    private static $password = "Newpas12";
    private static $deptcode = "84";
    public static function generateToken()
    {

        $issuedAt = 1668407854;
        $expire = 1668406054;
        $payload = [
            "sub" => "KYC_WS_BROKER",
            "iat" => $issuedAt,
            "exp" => $expire,
        ];
        return JWT::encode($payload, self::$secretKey, 'HS512');

    }

    // public static function ValidateCKYCdetails($userId, $gender, $docnumber, $doctypecode, $dob, $transicationid)
    public static function ValidateCKYCdetails($userId, $gender, $docnumber, $dob, $TransactionId)
    {
        try {
            $dob = new DateTime($dob);
            $userdob = $dob->format('d-M-Y');
            $id = self::$Id;

            $payload = [
                "docTypeCode" =>"C", //$doctypecode ?? 
                "docNumber" => $docnumber ?? "JBSPK8786H",
                "fieldType" => "PROPOSAL_NUMBER",
                "fieldValue" => $TransactionId ?? "",//"12345678678", 
                "dob" =>$userdob ?? "28-Jun-1998",
                "appType" => "KYC_WS_BROKER",
                "productCode" => "8456",
                "sysType" => "OPUS",
                "locationCode" => "9906",
                "userId" => $id,
                "kycType" => "03",
                "customerType" => "I",
                "passportFileNumber" => "",
                "gender" => $gender ?? "",
                "field1" => "MOTOR",
                "field2" => ""
            ];

            // return $payload;
            $jsonPayload = json_encode($payload);

            $localKey = substr('kycwsbrkmotr2023', 0, 16);
            $localIv = substr('kycwsbrkmotr2023', 0, 16);

            $encrypted = openssl_encrypt(
                $jsonPayload,
                'AES-128-CBC',
                $localKey,
                OPENSSL_RAW_DATA,
                $localIv
            );

            $encryptedPayload = base64_encode($encrypted);
            $finalPayload = json_encode([
                "payload" => $encryptedPayload
            ]);

            $token = self::generateToken();

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.bagicuat.bajajallianz.com/csckyc/validateCkycDetails',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $finalPayload,
                CURLOPT_HTTPHEADER => [
                    'BusinessCorelationId:36c18e93-ac17-4990-8451-e1929f42ea88',
                    'Auth: Bearer' . $token,
                    'Authorization: Bearer' . $token,
                    'Content-Type: application/json',
                ],
            ]);

            $response = curl_exec($curl);
            curl_close($curl);

            $respJson = json_decode($response, true);

            if (isset($respJson['payload'])) {
                $decoded = base64_decode($respJson['payload']);

                $decryptedRaw = openssl_decrypt(
                    $decoded,
                    'AES-128-CBC',
                    $localKey,
                    OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
                    $localIv
                );

                if ($decryptedRaw !== false) {
                    $unpadded = self::iso10126_unpad($decryptedRaw);
                    $decrypted = json_decode($unpadded, true);
                } else {
                    $decrypted = null;
                }

                return [
                    'decrypted' => $decrypted
                ];
            }

            //return $response;

        } catch (\Exception $e) {
            //echo '' . $e->getMessage();
            return response()->json([
                $e->getMessage()
            ]);
        }
    }
    private static function iso10126_unpad(string $data): string
    {
        $padLen = ord(substr($data, -1)); // last byte = padding length
        return substr($data, 0, -$padLen);
    }

    // public static function verifyUploadDocument($userId, $filePath, $docType, $TransactionId, $tokenid)
    // public static function verifyUploadDocument($filePath,$TransactionId, $tokenid)
    // {
    //     $fileContents = file_get_contents($filePath);
    // $base64EncodedFile = base64_encode($fileContents);

    // $allowedExtensions = ['pdf', 'jpeg', 'gif', 'bitmap', 'xls', 'xlsx', 'doc', 'docx'];
    // $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

    // // Validate extension
    // if (!in_array($extension, $allowedExtensions)) {
    //     return "Invalid document extension: $extension";
    // }
    //             $id = self::$Id;
    //             try {
    //                 $payload = [
    //                     "appType" => "KYC_WS_BROKER",
    //                     "fieldType" => "PROPOSAL_NUMBER",
    //                     "fieldValue" => $TransactionId,
    //                     "kycDocumentType" => "POA",
    //                     "kycDocumentCategory" => "E",
    //                     "documentNumber" => "5380",
    //                     "documentExtension" => $extension,
    //                     "userId" => $id,
    //                     "documentArray" => $base64EncodedFile,
    //                 ];
    //                return $payload;
    //                 $requestdata = json_encode($payload);

    //                 $token = self::generateToken();

    //                 $curl = curl_init();
    //                 curl_setopt_array($curl, [
    //                     CURLOPT_URL => 'https://api.bagicuat.bajajallianz.com/csckyc/uploadKYCDocument',
    //                     CURLOPT_RETURNTRANSFER => true,
    //                     CURLOPT_ENCODING => '',
    //                     CURLOPT_MAXREDIRS => 10,
    //                     CURLOPT_TIMEOUT => 0,
    //                     CURLOPT_FOLLOWLOCATION => true,
    //                     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //                     CURLOPT_CUSTOMREQUEST => 'POST',
    //                     CURLOPT_POSTFIELDS => $requestdata,
    //                     CURLOPT_HTTPHEADER => [
    //                         'BusinessCorelationId:36c18e93-ac17-4990-8451-e1929f42ea88',
    //                         'Auth: Bearer' . $tokenid,
    //                         'Authorization: Bearer' . $tokenid,
    //                         'Content-Type: application/json',
    //                     ],
    //                 ]);

    //                 $response = curl_exec($curl);
    //                 //\Log::info(['Response' => $response]);
    //                 curl_close($curl);
    //                 return $response;
    //             } catch (\Exception $e) {
    //                 return $e->getMessage();
    //             }

    //         } else {

    //             echo "File not found ";
    //             die;
    //         }
    //     } else {

    //         throw new Exception("File not found.");
    //     }

    // }

    public static function verifyUploadDocument($filePath, $TransactionId, $tokenid)
    {
        if (!file_exists($filePath)) {
            return "File not found!";
        }

        $fileContents = file_get_contents($filePath);
        $base64EncodedFile = base64_encode($fileContents);

        
        $allowedExtensions = ['pdf', 'jpeg', 'gif', 'bitmap', 'xls', 'xlsx', 'doc', 'docx'];

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions)) {
            return "Invalid document extension: $extension";
        }

        $id = self::$Id;

        $payload = [
            "appType" => "KYC_WS_BROKER",
            "fieldType" => "PROPOSAL_NUMBER",
            "fieldValue" => $TransactionId,
            "kycDocumentType" => "POA",
            "kycDocumentCategory" => "E",
            "documentNumber" => "5380",
            "documentExtension" => $extension,
            "userId" => $id,
            "documentArray" => $base64EncodedFile,
        ];

        $requestdata = json_encode($payload);

        try {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.bagicuat.bajajallianz.com/csckyc/uploadKYCDocument',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $requestdata,
                CURLOPT_HTTPHEADER => [
                    'BusinessCorelationId:36c18e93-ac17-4990-8451-e1929f42ea88',
                    'Auth: Bearer' . $tokenid,
                    'Authorization: Bearer' . $tokenid,
                    'Content-Type: application/json',
                ],
            ]);

            $response = curl_exec($curl);
            curl_close($curl);

            return $response;

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}