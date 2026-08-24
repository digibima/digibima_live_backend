<?php

namespace App\Http\Controllers;

use App\Http\Controllers\front\health\caresupreme;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pincode;
use App\Services\CareSupremeService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KYCController
{

    public function verifyPAN(Request $request, $panno = null, $getdob = null)
    {
        //dd($request);
        try {
           
            $panid = !empty($panno) ? $panno : $request->customerpancardno;
            $getdob = !empty($getdob) ? $getdob : $request->customerpancardDob;
            $validatorarr = empty($panno) ? $request->all() : ['customerpancardno' => $panno, 'customerpancardDob' => $getdob];
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
            $aToken = json_decode(CareSupremeService::generatePartnerToken('pan'));
            //dd($aToken);
            $sSessionID = $aToken->sSessionID;
            $tokenid = $aToken->tokenid;
            $pandata = CareSupremeService::verifyPAN($panid, $dob, $sSessionID, $tokenid);
            $decoded_pandata = json_decode($pandata);
            //dd($pandata);
            if ($decoded_pandata->responseData->status == '0') {
                return response()->json(['status' => '0', 'data' => $pandata, 'message' => 'PAN Number or DOB is wrong.']);
            } elseif ($decoded_pandata->responseData->status == 'fail') {
                return response()->json(['status' => '0', 'data' => $pandata, 'message' => 'Something is wrong.Please verify by physical document.']);
            }
            $bResponse = CareSupremeService::managePartnerToken($pandata);
            if (!$bResponse) {
                ++$errorCount;
                if ($errorCount > 2) {
                    return response()->json(['status' => '0', 'data' => $pandata, 'message' => 'Something is wrong.Please verify by physical document.']);
                }
                goto PAN;
            }
            $data = json_decode($pandata, true);
            $ckycNo = $data['getCkycEkycInputIO']['kycDetails']['personalIdentifiableData']['personalDetails']['ckycNo'];
            $pincode = $data['getCkycEkycInputIO']['kycDetails']['personalIdentifiableData']['personalDetails']['permPin'];

            !empty($panno) ? '' : session()->put('ckycno', $ckycNo);
            $kyc = "";
            $kyctype = "";
            if ($ckycNo) {
                if (empty($panno)) {
                    $kyc = '1';
                    $kyctype = 'p';
                    session()->put('kyctype', 'p');
                    session()->put('kyc', '1');
                }
            } else {
                if (empty($panno)) {
                    $kyc = '0';
                    session()->forget('kyctype');
                    session()->forget('kyc');
                }
            }
            $response = ['pandata' => $pandata, 'kyc' => !empty($panno) ? '' : $kyc, 'kyctype' => $kyctype ?? ''];
            return response()->json(json_decode(json_encode($response, JSON_UNESCAPED_SLASHES)));
        } catch (\Exception $e) {
            echo 'code:verifypan' . $e->getMessage();
        }
    }

    public function verifyAdhar(Request $request, $gender = null, $name = null, $adharno = null, $dob = null)
    {
        try {
            $validatorarr = empty($adharno) ? $request->all() : ['customerAadharGender' => $gender, 'customerAadharName' => $name, 'customerAadharno' => $adharno, 'customerAadharDob' => $dob];
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
                return response()->json(['id' => $fieldName, 'error' => $errorMessage]);
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
            $aToken = json_decode(CareSupremeService::generatePartnerToken('pan'));
            $sSessionID = $aToken->sSessionID;
            $tokenid = $aToken->tokenid;
            $adhardata = CareSupremeService::verifyAdhar($getadharno, $getdob, $getname, $getgender, $sSessionID, $tokenid);
            if (json_decode($adhardata)->responseData->status == '0') {
                return response()->json(['status' => '0', 'data' => $adhardata, 'message' => 'Adhar Number or DOB or gender is wrong .']);
            }
            $bResponse = CareSupremeService::managePartnerToken($adhardata);
            if (!$bResponse) {
                ++$errorCount;
                if ($errorCount > 2) {
                    return response()->json(['status' => '0', 'data' => $adhardata, 'message' => 'Something is wrong.Please verify by physical document.']);
                }
                goto Adhar;
            }
            $data = json_decode($adhardata, true);
            $ckycNo = $data['aadharCKYCDetailsIO']['kycDetails']['personalIdentifiableData']['personalDetails']['ckycNo'];
            $pincode = $data['getCkycEkycInputIO']['kycDetails']['personalIdentifiableData']['personalDetails']['permPin'];
            // if (Auth::check() && !empty($pincode)) {
            //     $obj = User::find(Auth::id());
            //     if ($obj) {
            //         if ($obj->pincode == $pincode) {
            //             session()->put('updatePin', '0');
            //         } else {
            //             session()->put('updatePin', '1');
            //             $obj->pincode = $pincode;
            //             $obj->save();
            //         }
            //     }
            // }
            !empty($adharno) ? '' : session()->put('ckycno', $ckycNo);
            $kyc = "";
            $kyctype = "";
            if ($ckycNo) {
                if (empty($adharno)) {
                    $kyc = '1';
                    $kyctype = 'p';
                    session()->put('kyctype', 'a');
                    session()->put('kyc', '1');
                }
            } else {
                if (empty($adharno)) {
                    $kyc = '0';
                    session()->forget('kyctype');
                    session()->forget('kyc');
                }
            }
            $response = ['adhardata' => $adhardata, 'kyc' => !empty($adharno) ? '' : $kyc, '$kyctype' => $kyctype ?? ''];
            return response()->json(json_decode(json_encode($response, JSON_UNESCAPED_SLASHES)));
        } catch (\Exception $e) {
            echo 'code:verifyadhar' . $e->getMessage();
        }
    }

    public function AutoCompletePincode(Request $request)
    {
        $pincodeData = Pincode::where('pincode', 'like', $request->pincode . '%')->pluck('district', 'pincode');
        return response()->json($pincodeData);
    }
    public function AutoCompleteDetails(Request $request)
    {
        //dd($request);
        $responseData = Pincode::where('pincode', 'like', $request->pincode . '%')->select('state', 'district', 'pincode')->get();
        return response()->json($responseData);
    }
    // public function AutoCompletePincodeDetails(Request $request)
    // {
    //     $pincodeData = Pincode::where('pincode', 'like', $request->pincode . '%')->pluck('district', 'pincode');
    //     return response()->json($pincodeData);
    // }
    public function basicPlan()
    {
        $plandata = CareSupremeService::getBasicPlan();
        return response()->json($plandata);
    }
}
