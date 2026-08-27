<?php
namespace App\Services\Api\godigit;

use App\Http\Controllers\Api\front\motor\Vendor\shriram\Bike\ShriramBikeController;
use App\Http\Controllers\Api\front\motor\Vendor\shriram\Car\ShriramCarController;
use App\Models\Shriram\{Shriram_Pincode, Shriram_planCheckout, Shriram_RTO_Master, Shriram_Prev_insurence, Shriram_Vehicle_Master};
use App\Models\{Master_Vehicle_Data as DataModel, MasterAPI, User, MotorJourney, MasterVendor, VendorMotor, MasterMotor, UserMotorDescription, Vehicle_Info};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Cache};
use Exception;
use stdClass;

class GoDigitUtilityService
{
    // public static $Username = '35327650';
    // public static $Password = 'WrE3GukZIzSDKYOotFZ';

    public static function TokenGenerate()
    {
        $url = getconstant('MOTOR.GODIGIT.API.TOKEN');
        $curl = curl_init();
        $Username = getconstant('MOTOR.GODIGIT.CREDENTIAL.TOKENUSERNAME');
        $Password = getconstant('MOTOR.GODIGIT.CREDENTIAL.TOKENPASSWORD');
        // dd($Username, $Password, $url);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                'username' => $Username,
                'password' => $Password
            ]),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        // dd($response);
        return $response;
    }

    public static function KycTokenGenerate()
    {
        $url = getconstant('MOTOR.GODIGIT.API.KYCTOKEN');
        $Username = getconstant('MOTOR.GODIGIT.CREDENTIAL.TOKENUSERNAME');
        $Password = getconstant('MOTOR.GODIGIT.CREDENTIAL.TOKENPASSWORD');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                'username' => $Username,
                'password' => $Password
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
            $sNewToken = self::KycTokenGenerate();
            $sToken = json_decode($sNewToken, true);
            $sToken = $sToken ? $sToken['access_token'] : '';
            $sDocdetails = UserMotorDescription::where('userid', $userId);
            $aDocument = $sDocdetails->first('document');
            $sdoctype = $sDocdetails->first('idnumber');
            $filePath = json_decode($aDocument->document, true);
            $identityPhotoB64 = self::FileIntoBase64($filePath['identity']['identityfront']);
            $addressPhotoB64 = self::FileIntoBase64($filePath['address']['addressfront']);
            self::initlize();
            $integartionid = getconstant('MOTOR.GODIGIT.INTEGRATIONID.OVDKYC');
            $postfeilds = json_encode(
                [
                    'kYCServiceKYCUpdationAPI' => [
                        'queryParam' => [
                            'companyFlag' => 'GI',
                            'policyNumber' => $nProposalNo,  // "D700593703",//"D601494528",// // D700593746
                        ],
                        'externalReferenceNumber' => 'edldldfkfk34g',
                        'agentCode' => getconstant('MOTOR.GODIGIT.CREDENTIAL.AGENTCODE'),
                        'policyHolderType' => 'Individual',
                        'dateOfBirth' => '1996-07-19',
                        'idVerificationDocType' => 'D25',
                        'idVerificationDoc' => [$identityPhotoB64['based64'], $identityPhotoB64['based64']],
                        'addressVerificationDocType' => 'D25',
                        'addressVerificationDoc' => [$addressPhotoB64['based64'], $addressPhotoB64['based64']],
                        'gender' => 'M',
                        'successReturnURL' => getconstant('MOTOR.GODIGIT.API.RETURNURL'),
                        'failureReturnURL' => getconstant('MOTOR.GODIGIT.API.RETURNURL')
                    ]
                ]
            );
            $url = getconstant('MOTOR.GODIGIT.API.OVDKYC');
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $postfeilds,
                CURLOPT_HTTPHEADER => array(
                    'integrationId: ' . $integartionid,
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
        $integartionid = getconstant('MOTOR.GODIGIT.INTEGRATIONID.KYCSTATUS');
        sleep(3);
        $payload = json_encode([
            'queryParam' => [
                'policyNumber' => $policyNumber,
            ]
        ]);
        SaveFile($payload, 'godigit_kysstatus_payload.txt');
        $url = getconstant('MOTOR.GODIGIT.API.KYCSTATUS');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                'integrationId: ' . $integartionid,  // 16730-0100 //27637-0100
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
        $integartionid = getconstant('MOTOR.GODIGIT.INTEGRATIONID.POLICYSTATUS');
        $payload = json_encode([
            'queryParam' => [
                'policyNumber' => $policyNumber,
            ]
        ]);
        $url = getconstant('MOTOR.GODIGIT.API.POLICYSTATUS');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                'integrationId: ' . $integartionid,  // 16730-0100 //27636-0100
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
        $integartionid = getconstant('MOTOR.GODIGIT.INTEGRATIONID.PDF');
        $payload = json_encode([
            'policyId' => $policyId,
            'headerParam' => [
                'Authorization' => $sToken
            ]
        ]);
        $url = getconstant('MOTOR.GODIGIT.API.PDF');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                'integrationId: ' . $integartionid,
                'Content-Type: application/json',
                'Authorization: Bearer ' . $sToken,
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}
