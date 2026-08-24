<?php

namespace App\Services\Api\bajajHealth;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use DateTime;

class KycService
{
    private $secretKey;
    private $Id = 'webservice@digibima.com';
    private $deptcode = '84';
    private $baseurl;

    public static function initlize()
    {
        $instance = new self();
        $instance->Id = getconstant('HEALTH.BAJAJ.CREDENTIAL.ID');
        $instance->secretKey = 'o6qJ9BEs2szci3oBzqi1U5sEzCquCblPQUClRBPdigibima@kycwsbrkmotr2023';
        $instance->baseurl = 'https://webapi.bajajgeneral.com/';
        return $instance;
    }

    public static function generateToken()
    {
        $instance = self::initlize();
        $issuedAt = 1668407854;
        $expire = 1668406054;
        $payload = [
            'sub' => 'KYC_WS_BROKER',
            'iat' => $issuedAt,
            'exp' => $expire,
        ];
        return JWT::encode($payload, $instance->secretKey, 'HS512');
    }

    public static function ValidateCKYCdetails($userId, $gender, $docnumber, $doctypecode, $dob, $transicationid)
    {
        try {
            $instance = self::initlize();
            $cachecovertype = 'cache_covertype_' . $userId;
            // return $cachecovertype;
            $productCode = null;
            $producttype = GetCache($cachecovertype) ?? '1';
            // return $producttype;
            if ($producttype == 'Individual') {
                $productCode = '8456';
            } else {
                $productCode = '8457';
            }
            if (empty($transicationid)) {
                $transicationid = 'PROP' . time() . rand(1000, 9999);
            }
            $TransactionId = $transicationid;
            // $TransactionId = $transicationid ?? RedisGet('transactionid:' . $userId);
            $date = DateTime::createFromFormat('d-m-Y', trim($dob));
            $formattedDate = $date ? $date->format('d-M-Y') : null;
            // return $formattedDate;
            // $userdob = $dob->format('d-M-Y');
            // return $TransactionId;
            // $today = now()->format('Y-m-d');
            $id = $instance->Id;
            $payload = [
                'docTypeCode' => $doctypecode ?? 'C',
                'docNumber' => $docnumber ?? 'JBSPK8786H',
                'fieldType' => 'PROPOSAL_NUMBER',
                'fieldValue' => $TransactionId,  // "12345678678",
                'dob' => $formattedDate,  // "28-Jun-1998",
                'appType' => 'KYC_WS_BROKER',
                'productCode' => $productCode,  // "8456",
                'sysType' => 'MAXIMUS',
                'locationCode' => '9906',
                'userId' => $id,
                'kycType' => '03',
                'customerType' => 'I',
                'passportFileNumber' => '',
                'gender' => $gender ?? '',
                'field1' => 'HEALTH',
                'field2' => ''
            ];

            // return $payload;
            $jsonPayload = json_encode($payload);
            SaveFile($jsonPayload, 'bajaj_kyc_request.txt');
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
            //  $decryptedRaw = openssl_decrypt(
            //         $encrypted,
            //         'AES-128-CBC',
            //         $localKey,
            //         OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
            //         $localIv
            //     );

            // return [
            //     // "decryptedRaw"=>json_decode($decryptedRaw),
            //     // "encrypted"=>$encrypted

            //     "abcf"
            // ];
            $finalPayload = json_encode([
                'payload' => $encryptedPayload
            ]);
            // return json_decode($finalPayload);
            $token = self::generateToken();
            $curl = curl_init();
            curl_setopt_array($curl, [
                // CURLOPT_URL => 'https://htapi.bagicpp.bajajallianz.com/csckyc/validateCkycDetails',
                CURLOPT_URL => $instance->baseurl . 'csckyc/validateCkycDetails',
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
                    'x-api-key: 2O5czaeucN6ROiVrUy7hu1LEyf9rX7ZF959FCKWo'
                ],
            ]);

            $response = curl_exec($curl);
            curl_close($curl);
            SaveFile($response, 'bajaj_kyc_response.txt');
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

            // return $response;
        } catch (\Exception $e) {
            // echo '' . $e->getMessage();
            return response()->json([
                $e->getMessage()
            ]);
        }
    }

    private static function iso10126_unpad(string $data): string
    {
        $padLen = ord(substr($data, -1));  // last byte = padding length
        return substr($data, 0, -$padLen);
    }

    public static function verifyUploadDocument($userId, $filePath, $docType, $TransactionId, $tokenid)
    {
        $instance = self::initlize();
        $parts = explode('/', $filePath);
        $filename = end($parts);
        // dd($filename);
        if (isset($filename)) {
            if (file_exists($filePath)) {
                $fileContents = file_get_contents($filePath);
                $base64EncodedFile = base64_encode($fileContents);
                $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                $id = $instance->Id;
                try {
                    $payload = [
                        'appType' => 'KYC_WS_BROKER',
                        'fieldType' => 'PROPOSAL_NUMBER',
                        'fieldValue' => $TransactionId,
                        'kycDocumentType' => 'POA',
                        'kycDocumentCategory' => 'E',
                        'documentNumber' => '5380',
                        'documentExtension' => $extension,
                        'userId' => $id,
                        'documentArray' => $base64EncodedFile,
                    ];
                    // return $payload;
                    $requestdata = json_encode($payload);
                    $curl = curl_init();
                    curl_setopt_array($curl, [
                        CURLOPT_URL => $instance->baseurl . 'csckyc/uploadKYCDocument',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => $requestdata,
                        CURLOPT_HTTPHEADER => [
                            'BusinessCorelationId:36c18e93-ac17-4990-8451-e1929f42ea88',
                            'Auth: Bearer' . $tokenid,
                            'Authorization: Bearer' . $tokenid,
                            'Content-Type: application/json',
                        ],
                    ]);

                    $response = curl_exec($curl);
                    \Log::info(['Response' => $response]);
                    curl_close($curl);
                    return $response;
                } catch (\Exception $e) {
                    return $e->getMessage();
                }
            } else {
                echo 'File not found ';
                die;
            }
        } else {
            throw new Exception('File not found.');
        }
    }

    public static function PaymentStatus()
    {
        try {
            $instance = self::initlize();
            $payload = [
                'pRequestId' => '11-8456-0000054131-00',
                'flag' => 'HEALTH_WS'
            ];
            $requestdata = json_encode($payload);
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $instance->baseurl . 'bagicHws/health/checkpgtransstatus',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $requestdata,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json'
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
