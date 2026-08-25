<?php
namespace App\Services\Api\godigit;

use App\Models\{Master_Vehicle_Data as DataModel, MasterAPI, User, MotorJourney, MasterVendor, VendorMotor, MasterMotor, UserMotorDescription};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Cache};
use Exception;
use stdClass;

class GoDigitUtilityService
{
    public static $Username = 'HkMfH+mws05z5uOSCEpeTQ==';
    public static $Password = 'lI42Enh/ZY2vXqQYAgbrKa3pRWoTMOyqPwMwexnF3uo=';

    public static function TokenGenerate()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://oneapi.godigit.com/OneAPI/v1/auth',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                'username' => self::$Username,
                'password' => self::$Password
            ]),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie:TS0198aefb=0138ecebf96f9531dbd1898da30784872bcbd4f716d17f5423e48c53afadbbb5cfac82ca74daafc65c6421f6c98b5da9a11308e8cc; TS017fdda2=0138ecebf9c712e9cc47f6c40dc63ff7f013cbbdf802e1651129751c8fef1eeb0ffd0be127c7edc77edfc0fdd4e7f15cedd85f8db8; Application=oneapi; TS017fdda2=0138ecebf9854330d85d57fce53cfa7c20a7f0ead02a95d7266385fbe072793808ef37cac4cd4423a656beec0d10914ef91a825bca'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public static function KycTokenGenerate()
    {
        // "username": "35327650",
        // "password": "Digit@123$"

        //  "username": "10239856",
        //     "password": "To6nkOaHNx4TImzvEX3"
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://oneapi.godigit.com/OneAPI/digit/generateAuthKey',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                'username' => self::$Username,
                'password' => self::$Password
            ]),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public static function FileIntoBase64($filePath)
    {
        $base64 = '';
        $image = '';
        $parts = explode('/', $filePath);
        $extension = explode('.', end($parts));
        if (file_exists($filePath)) {
            $image = file_get_contents($filePath);
            $base64 = base64_encode($image);
        } else {
            throw new \Exception('File not found.');
        }
        return ['extension' => '.' . end($extension), 'based64' => $base64];
    }

    public static function OVDkyc(Request $request, $nProposalNo)
    {
        try {
            $userId = $request->userid;
            // return $userId;
            $sNewToken = self::KycTokenGenerate();
            // return $sNewToken;
            $sToken = json_decode($sNewToken, true);
            $sToken = $sToken ? $sToken['access_token'] : '';
            $sDocdetails = UserMotorDescription::where('userid', $userId);
            $aDocument = $sDocdetails->first('document');
            $sdoctype = $sDocdetails->first('idnumber');
            $filePath = json_decode($aDocument->document, true);
            $identityPhotoB64 = self::FileIntoBase64($filePath['identity']['identityfront']);
            // $identitybackB64 = self::FileIntoBase64($filePath['identity']['identityback']);
            $addressPhotoB64 = self::FileIntoBase64($filePath['address']['addressfront']);
            // $addressBackB64 = self::FileIntoBase64($filePath['address']['addressback']);
            self::initlize();

            // return $identityPhotoB64['based64'];
            // return $addressPhotoB64['based64'];

            $postfeilds = json_encode(
                [
                    'kYCServiceKYCUpdationAPI' => [
                        'queryParam' => [
                            'companyFlag' => 'GI',
                            'policyNumber' => $nProposalNo,  // "D700593703",//"D601494528",// // D700593746
                        ],
                        'externalReferenceNumber' => 'edldldfkfk34g',
                        'agentCode' => '1000295',
                        'policyHolderType' => 'Individual',
                        'dateOfBirth' => '1996-07-19',
                        'idVerificationDocType' => 'D25',
                        'idVerificationDoc' => [$identityPhotoB64['based64'], $identityPhotoB64['based64']],
                        'addressVerificationDocType' => 'D25',
                        'addressVerificationDoc' => [$addressPhotoB64['based64'], $addressPhotoB64['based64']],
                        'gender' => 'M',
                        'successReturnURL' => 'http://192.168.29.219:3000/motor/car/vendor/godigit/journey',
                        'failureReturnURL' => 'http://192.168.29.219:3000/motor/car/vendor/godigit/journey'
                    ]
                ]
            );
            // return $postfeilds;
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://preprod-oneapi.godigit.com/OneAPI/v1/executor',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $postfeilds,
                CURLOPT_HTTPHEADER => array(
                    'integrationId: 23553-0100',  // 16730-0100
                    'Authorization: Bearer ' . $sToken,
                    'Content-Type: application/json',
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public static function KycStatus(Request $request, $nProposalNo)
    {
        $userId = $request->userid;
        $sNewToken = self::TokenGenerate();
        $sToken = json_decode($sNewToken, true);
        $sToken = $sToken ? $sToken['access_token'] : '';
        $policyNumber = $nProposalNo;
        sleep(3);
        $payload = json_encode([
            'queryParam' => [
                'policyNumber' => $policyNumber,
            ]
        ]);
        SaveFile($payload, 'godigit_kysstatus_payload.txt');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://preprod-oneapi.godigit.com/OneAPI/v1/executor',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                'integrationId: 28098-0100',  // 16730-0100 //27637-0100
                'Authorization: Bearer ' . $sToken,
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);
        SaveFile($response, 'godigit_kysstatus_response.txt');
        curl_close($curl);
        return $response;
    }

    public static function PolicyStatus($policyNumber = 'D700724674')
    {
        $sNewToken = self::TokenGenerate();
        $sToken = json_decode($sNewToken, true);
        $sToken = $sToken ? $sToken['access_token'] : '';
        // return $sToken;
        // $policyNumber = "D700593703";
        $payload = json_encode([
            'queryParam' => [
                'policyNumber' => $policyNumber,
            ]
        ]);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://preprod-oneapi.godigit.com/OneAPI/v1/executor',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                'integrationId: 28098-0100',  // 16730-0100 //27636-0100
                'Authorization: Bearer ' . $sToken,
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }

    public static function PolicyPdf($policyId = 'V2C90537E2EC75FD04266DD3AB14F680BAECA4A3C521B02D8E6899D6530BCB21A3F488F441D8F9B923B021D40D5D153EBC')
    {
        $sNewToken = self::TokenGenerate();
        $sToken = json_decode($sNewToken, true);
        $sToken = $sToken ? $sToken['access_token'] : '';

        $payload = json_encode([
            'policyId' => $policyId,
            'headerParam' => [
                'Authorization' => $sToken
            ]
        ]);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://preprod-oneapi.godigit.com/OneAPI/v1/executor',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                'integrationId: 28100-0100',
                'Content-Type: application/json',
                'Authorization: Bearer ' . $sToken,
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}
