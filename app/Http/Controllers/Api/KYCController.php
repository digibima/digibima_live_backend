<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\front\health\caresupreme;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pincode;
use App\Services\Api\CareSupremeService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\{Cache};

class KYCController
{
    public function genToken(Request $request)
    {
        $aToken = CareSupremeService::getTokenOnly();
        dd($aToken);
    }
    public function verifyPAN(Request $request)
    {
        //dd($request);
        // try {
            $input = $request->input('data', $request->all());
            $userId = $request->userid;
            $panid = !empty($panno) ? $panno : ($input['customerpancardno']);
            $getdob = !empty($getdob) ? $getdob : ($input['customerpancardDob']);

            $validatorarr = empty($panno) ? $input : ['customerpancardno' => $panno, 'customerpancardDob' => $getdob];

            

            $validator = Validator::make(
                $validatorarr,
                [
                    'customerpancardno' => 'required|max:10|min:10|regex:/^[A-Za-z]{5}[0-9]{4}[A-Za-z]$/',
                    'customerpancardDob' => 'required|date'
                ],
                [
                    'customerpancardno.required' => 'This field is required',
                    'customerpancardno.max' => 'Lenth should be 10 letters',
                    'customerpancardno.min' => 'Lenth should be 10 letters',
                    'customerpancardno.regex' => 'This is not valid format',
                    'customerpancardDob.required' => 'This field is required',
                    'customerpancardDob.date' => 'Not Valid Date'
                ]
            );
            if ($validator->fails()) {
                $errors = $validator->errors();
                $fieldName = $errors->keys()[0] ?? null;
                $errorMessage = $errors->first($fieldName);
                return response()->json(['id' => $fieldName, 'error' => $errorMessage]);
            }
            $dob = Carbon::createFromFormat('d-m-Y', $getdob)->format('Y-m-d');
            $errorCount = 0;
            PAN:
            $aToken = json_decode(CareSupremeService::generatePartnerToken($request, 'pan'));
            
            $sSessionID = $aToken->sSessionID;
           
            $tokenid = $aToken->tokenid;
            
            $pandata = CareSupremeService::verifyPAN($panid, $dob, $sSessionID, $tokenid);
                        
            $decoded_pandata = json_decode($pandata);
            // return $decoded_pandata ;
            //dd($pandata);
            if ($decoded_pandata->responseData->status == '0') {
                return response()->json([
                    //'status' => true,
                     'status' => false,
                    'data' => $pandata,
                    'message' => 'PAN Number or DOB is wrong.'
                ]);
            } elseif ($decoded_pandata->responseData->status == 'fail') {
                return response()->json([
                    'status' => false,
                    'data' => $pandata,
                    'message' => 'Something is wrong.Please verify by physical document.'
                ]);
            }
            $bResponse = CareSupremeService::managePartnerToken($request, $pandata);
            if (!$bResponse) {
                ++$errorCount;
                if ($errorCount > 2) {
                    return response()->json([
                        'status' => '0',
                        'data' => $pandata,
                        'message' => 'Something is wrong.Please verify by physical document.'
                    ]);
                }
                goto PAN;
            }
            $data = json_decode($pandata, true);
            $ckycNo = $data['getCkycEkycInputIO']['kycDetails']['personalIdentifiableData']['personalDetails']['ckycNo'];
            $pincode = $data['getCkycEkycInputIO']['kycDetails']['personalIdentifiableData']['personalDetails']['permPin'];

            $cacheckycno = 'cache_ckycno_' . $userId;
            !empty($panno) ? '' : SetCache($cacheckycno, $ckycNo);

            $kyc = "";
            $kyctype = "";
            if ($ckycNo) {
                if (empty($panno)) {
                    $kyc = '1';
                    $kyctype = 'p';
                    $cacheckyctype = 'cache_kyctype_' . $userId;
                    SetCache($cacheckyctype, 'p');
                    //session()->put('kyctype', 'p');
                    $cacheckyc = 'cache_kyc_' . $userId;
                    SetCache($cacheckyc, '1');
                    //session()->put('kyc', '1');
                }
            } else {
                if (empty($panno)) {
                    $kyc = '0';
                    $cacheckyctype = 'cache_kyctype_' . $userId;
                    DeleteCache($cacheckyctype);

                    $cacheckyc = 'cache_kyc_' . $userId;
                    DeleteCache($cacheckyc);
                    //session()->forget('kyctype');
                    //session()->forget('kyc');
                }
            }

            $response = [
                'status' => true,
                'pandata' => json_decode($pandata, true),
                'kyc' => !empty($panno) ? '' : $kyc,
                'kyctype' => $kyctype ?? ''
            ];

            return response()->json($response);
        // } catch (\Exception $e) {
        //      return response()->json([
        //         'status'=>false,
        //         'error' => $e->getMessage()
        //      ]);
            //echo 'code:verifypan' . $e->getMessage();
        //}
    }

    public function verifyAdhar(Request $request, $gender = null, $name = null, $adharno = null, $dob = null)
    {
        try {
            $input = $request->input('data', $request->all());
            $userId = $request->userid;
            $validatorarr = empty($adharno) ? $input : [
                'customerAadharGender' => $gender,
                'customerAadharName' => $name,
                'customerAadharno' => $adharno,
                'customerAadharDob' => $dob
            ];

            $validator = Validator::make(
                $validatorarr,
                [
                    'customerAadharGender' => 'required',
                    'customerAadharno' => 'required|numeric',
                    'customerAadharName' => 'required',
                    'customerAadharDob' => 'required|date',
                ],
                [
                    'customerAadharGender.required' => 'Required',
                    'customerAadharno.required' => 'This field is required',
                    'customerAadharName.required' => 'This field is required',
                    'customerAadharDob.required' => 'This field is required',
                    'customerAadharDob.date' => 'Not Valid Date'
                ]
            );
            if ($validator->fails()) {
                $errors = $validator->errors();
                $fieldName = $errors->keys()[0] ?? null;
                $errorMessage = $errors->first($fieldName);
                return response()->json([
                    'id' => $fieldName,
                    'error' => $errorMessage
                ]);
            }
            $getgender = "";
            if (!empty($adharno)) {
                $getgender = $gender;
            } else {
                $getgender = $request->customerAadharGender == "Mr" ? 'M' : 'F';
            }

            $getname = !empty($adharno) ? $name : $request->customerAadharName;
            $getadharno = !empty($adharno) ? $adharno : $request->customerAadharno;
            $getdob = !empty($adharno) ? $dob : $request->customerAadharDob;
            //$dob = Carbon::createFromFormat('Y-m-d', $request->customerAadharDob)->format('d-m-Y');
            $errorCount = 0;

            Adhar:
            $aToken = json_decode(CareSupremeService::generatePartnerToken($request, 'adhar'));

            $sSessionID = $aToken->sSessionID;
            $tokenid = $aToken->tokenid;
            $adhardata = CareSupremeService::verifyAdhar(
                $getadharno,
                $getdob,
                $getname,
                $getgender,
                $sSessionID,
                $tokenid
            );

            if (json_decode($adhardata)->responseData->status == '0') {
                return response()->json([
                    'status' => '0',
                    'data' => $adhardata,
                    'message' => 'Adhar Number or DOB or gender is wrong .'
                ]);
            }
            $bResponse = CareSupremeService::managePartnerToken($request, $adhardata);

            if (!$bResponse) {
                ++$errorCount;
                if ($errorCount > 2) {
                    return response()->json([
                        'status' => '0',
                        'data' => $adhardata,
                        'message' => 'Something is wrong.Please verify by physical document.'
                    ]);
                }
                goto Adhar;
            }
            $data = json_decode($adhardata, true);
            $ckycNo = $data['aadharCKYCDetailsIO']['kycDetails']['personalIdentifiableData']['personalDetails']['ckycNo'] ?? null;
            $pincode = $data['getCkycEkycInputIO']['kycDetails']['personalIdentifiableData']['personalDetails']['permPin'] ?? null;

            $cacheckycno = 'cache_ckycno_' . $userId;

            !empty($adharno) ? '' : SetCache($cacheckycno, $ckycNo);
            $kyc = "";
            $kyctype = "";

            if ($ckycNo) {
                if (empty($adharno)) {
                    $kyc = '1';
                    $kyctype = 'p';
                    $cacheckyctype = 'cache_kyctype_' . $userId;
                    SetCache($cacheckyctype, 'a');
                    //session()->put('kyctype', 'a');
                    $cacheckyc = 'cache_kyc_' . $userId;
                    SetCache($cacheckyc, '1');
                    //session()->put('kyc', '1');
                }
            } else {
                if (empty($adharno)) {
                    $kyc = '0';
                    $cacheckyctype = 'cache_kyctype_' . $userId;
                    DeleteCache($cacheckyctype);
                    //session()->forget('kyctype');
                    $cacheckyc = 'cache_kyc_' . $userId;
                    DeleteCache($cacheckyc);
                    //session()->forget('kyc');
                }
            }
            $response = [
                'adhardata' => json_decode($adhardata, true),
                'kyc' => !empty($adharno) ? '' : $kyc,
                '$kyctype' => $kyctype ?? ''
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
            //echo 'code:verifyadhar' . $e->getMessage();
        }
    }


    public function AutoCompleteDetails(Request $request)
    {
        try {
            $data = $request->data;
            $responseData = Pincode::where('pincode', 'like', $data['pincode'] . '%')->select('state', 'district', 'pincode')->get();
            return response()->json($responseData);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
    public function basicPlan()
    {
        $plandata = CareSupremeService::getBasicPlan('plan');
        return response()->json($plandata);
    }
}
