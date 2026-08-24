<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class OTPVerificationController
{
    public function sendEmailOTP(Request $request)
    {
        $message = '';
        $status = '';
        $otp = OTPGenerator();
        $data = [
            'otp' => $otp,
            'email' => $request->email,
        ];
        $mail = OTPVerificationService::sendEmailOTP($data);
        if ($mail) {
            $message = 'OTP sent successfully';
            $status = '1';
        } else {
            $message = 'OTP not sent ';
            $status = '0';
        }
        return response()->json(['status' => $status, 'message' => $message]);
    }

    public function sendOTP(Request $request)
    {
        try {
            $mobileNumber = $request->mobile;
            $otp = '123456';  // $this->OTPGenerator();
            $obj = User::where('mobile', $mobileNumber)->first();
            session()->put('genotp', Crypt::encrypt($otp));
            return response()->json(['status' => '1', 'message' => 'OTP sent Successfully', 'userstatus' => $obj ? '1' : '0', 'name' => $obj ? $obj->name : '', 'pincode' => $obj ? $obj->pincode : '']);
            // $curl = curl_init();
            // $array = [
            //     "listsms" => [
            //         [
            //             "sms" => "Dear Patron,\n\nYour verification OTP is: " . $otp . "\n\nTeam Digibima",
            //             "mobiles" => $mobileNumber,
            //             "senderid" => "DGBIMA",
            //             "tempid" => "1407172985653417707",
            //             "entityid" => "1401717150000068130",
            //             "unicode" => "0"
            //         ]
            //     ]
            // ];
            // curl_setopt_array($curl, array(
            //     CURLOPT_URL => 'https://api.celitix.com/rest/sms/sendsms',
            //     CURLOPT_RETURNTRANSFER => true,
            //     CURLOPT_ENCODING => '',
            //     CURLOPT_MAXREDIRS => 10,
            //     CURLOPT_TIMEOUT => 0,
            //     CURLOPT_FOLLOWLOCATION => true,
            //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //     CURLOPT_CUSTOMREQUEST => 'POST',
            //     CURLOPT_POSTFIELDS => json_encode($array),
            //     CURLOPT_HTTPHEADER => array(
            //         'key: e87f55c326XX',
            //         'Content-Type: application/json'
            //     ),
            // ));
            // $response = curl_exec($curl);
            // $isArray = json_decode($response,true);
            // if (empty($isArray) || !is_array($isArray) ) {
            //     return response()->json(['status' => '0', 'message' => 'Failed to send OTP']);
            // }
            // curl_close($curl);
            // if (json_decode($response)->smslist->sms->reason == "success") {
            //     session()->put('genotp', Crypt::encrypt($otp));
            //     return response()->json(['status' => '1', 'message' => 'OTP sent Successfully', 'userstatus' => $obj ? '1' : '0', 'name' => $obj ? $obj->name : '', 'pincode' => $obj ? $obj->pincode : '']);
            // }
            // return response()->json(['status' => '0', 'message' => 'Failed to send OTP']);
        } catch (Exception $e) {
            echo $e->getMessage() . 'errorcode: sendOTP';
        }
    }

    public function sendOTPApi(Request $request)
    {
        try {
            $data = $request->data;
            $mobileNumber = $data['mobile'] ?? '';
            if ($mobileNumber && !empty($mobileNumber)) {
                $otp = $this->OTPGenerator();
                $cacheKey = 'otp_' . $mobileNumber;
                // Cache::store('mysql_cache')->forget($cacheKey);
                Cache::store('mysql_cache')->put($cacheKey, $otp);
                // $obj = User::where('mobile', $mobileNumber)->first();
                $curl = curl_init();
                $array = [
                    'password' => 'a145a99c2cXX',
                    'user' => 'digibima',
                    'listsms' => [
                        [
                            'sms' => "Dear Patron,\n\nYour verification OTP is: " . $otp . "\n\nTeam Digibima",
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

    public function verifyOTP(Request $request)
    {
        $mobileNumber = $request->mobile;
        $otpNumber = $request->otp;
        if (Crypt::decrypt(session('genotp')) == $otpNumber) {
            session()->forget('genotp');
            session()->put('verifyotp', '1');
            return response()->json(['status' => '1', 'message' => 'OTP Verified Successfully']);
        }
        return response()->json(['status' => '0', 'message' => 'Failed to Verify OTP']);
    }

    public function verifyOTPApi(Request $request)
    {
        // $status = false;
        $data = $request->data;
        $mobileNumber = $data['mobile'] ?? '';
        $otpNumber = $data['otp'] ?? '';
        $cacheKey = 'otp_' . $mobileNumber;
        $cachedOtp = Cache::store('mysql_cache')->get($cacheKey);
        if ($cachedOtp && $cachedOtp == $otpNumber) {
            Cache::store('mysql_cache')->forget($cacheKey);
            // $status = true;
            return response()->json([
                'status' => true,
                'message' => 'OTP verified successfully.'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Failed to Verify OTP'
        ]);
    }

    public function OTPGenerator()
    {
        $otp = rand(100000, 999999);
        return $otp;
    }
}
