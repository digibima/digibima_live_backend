<?php
namespace App\Services;

class SMSService
{
    public function sendOTPApi($mobileNumber)
    {
        try {
            $data = $request->data;
            $mobileNumber = $data['mobile'] ?? '';
            if ($mobileNumber && !empty($mobileNumber)) {
                $otp = $this->OTPGenerator();
                $cacheKey = 'otp_' . $mobileNumber;
                $curl = curl_init();
                $array = [
                    'password' => 'a145a99c2cXX',
                    'user' => 'digibima',
                    'listsms' => [
                        [
                            'sms' => 'Dear {{1}},


Your login on the Digibima has been successfully created.


To access your policy documents, please click on the link below and log in using your registered mobile number:


https://www.digibima.com 


After login, go to the Profile section to view and download your policies. You can access and download your available policy documents from the Digibima portal anytime.


Thank you,
Digibima',
                            'mobiles' => $mobileNumber,
                            'senderid' => 'DGBIMA',
                            'tempid' => '1407172985653417707'
                        ]
                    ]
                ];
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://www.proactivesms.in/REST/sendsms',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode($array),
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json'
                    ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                Cache::store('mysql_cache')->put('genotp_', $otp);
                return response()->json([
                    'status' => true,
                    'message' => 'OTP sent Successfully',
                ]);
            }
            return response()->json([
                'status' => false,
                'message' => 'OTP not sent',
            ]);
        } catch (Exception $e) {
            return $e->getMessage() . 'errorcode: sendOTP';
        }
    }
}
