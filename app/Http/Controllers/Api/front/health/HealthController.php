<?php

namespace App\Http\Controllers\Api\front\health;

use App\Http\Controllers\Api\front\health\vendor\caresupreme\CareSupremeController;
use App\Http\Controllers\Api\front\health\vendor\ultimatecare\{planValidator, UltimateController};
use App\Http\Controllers\Api\SystemController;
use App\Models\{Insure, Master_Vehicle_Data as DataModel, User, MasterVendor, MotorPayment, CareToken, UltimateToken, PersonalAccessToken};
use App\Models\Pincode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Cache, Log, DB, Validator};

// live
class HealthController
{
    public function Login(Request $request, $param = null)
    {
        $userId = null;
        $user = null;
        $aInsureData = [];
        $token = $request->header('Authorization');
        $isToken = PersonalAccessToken::findToken($token);
        $ipAddress = $request->ip() ?? '';
        if ($isToken) {
            $userId = $isToken->tokenable_id;
            $user = User::find($userId);
            if ($user) {
                $data = [
                    'status' => true,
                    'message' => 'User loggedin already.',
                    // 'ip' => $ipAddress
                ];
                return response()->json($data);
            }
        }
        try {
            $data = (object) $request->data;
            $encryptedMobile = encodeMobile($data->mobile);
            if (isset($data->logintype)) {
                if (isset($data->name) && isset($data->gender)) {
                    goto REGISTER;
                }
                $user = User::where('mobile', $encryptedMobile)->first();
                // dd($user,$user->mobile);
                if ($user) {
                    $user->last_login = now();
                    $user->save();
                    goto ISUSER;
                } else {
                    return response()->json([
                        'status' => true,
                        'isUser' => false,
                        'mobile' => $data->mobile,
                    ]);
                }
            }
            REGISTER:
            $validator = Validator::make($request->data, [
                'name' => 'required|string|max:70',
                'mobile' => 'required|numeric',
                'pincode' => 'required|string|max:6',
                'gender' => 'required|string',
                'email' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            $request = $data;
            $pincode = substr(trim($request->pincode), 0, 6);

            $user = new User();
            $user->name = $request->name;
            $user->mobile = $encryptedMobile;
            $user->pincode = $pincode;
            $user->gender = $request->gender ?? '';
            $user->email = $request->email ?? '';
            $user->ip = $ipAddress ?? null;
            $user->created_at = now();
            $user->updated_at = now();
            $user->last_login = now();
            $user->save();
            // }
            ISUSER:
            if (!$isToken) {
                $token = $user->createToken('token')->plainTextToken;
                $isToken = PersonalAccessToken::findToken($token);
            }

            return response()->json([
                'status' => true,
                'isuser' => true,
                'user' => ($data->logintype == 'digibima') ? $user : '',
                'mobile' => $data->mobile,
                'message' => 'User loggedin successfully.',
                'insuredata' => $aInsureData,
                'token' => $token,
            ]);
        } catch (\Exception $e) {
            return response()->json(ErrMessage($e));
        }
    }

    public function saveInsure(Request $request)
    {
        $userId = $request->userid;
        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ]);
        }
        $peddata = $user->ped;
        Insure::where('proposalid', $userId)->delete();
        $insertData = [];
        DB::beginTransaction();
        try {
            $inputData = $request->data;
            // return $inputData;
            foreach ($inputData as $key => $value) {
                $insertData[] = [
                    'proposalid' => $userId,
                    'name' => $value['name'],
                    'age' => $value['age'],
                    'dob' => $value['dob'] ?? '',
                    'gender' => isset($value['gender']) ? $value['gender'] : '',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            Insure::insert($insertData);
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $peddata,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getInsureInfo(Request $request)
    {
        $userId = $request->userid;
        if ($userId) {
            $user = User::find($userId);
            $aInsureData = Insure::where('proposalid', $userId)->get()->toArray();
            return response()->json([
                'status' => true,
                'data' => $aInsureData,
                'gender' => $user->gender,
                'message' => 'Success'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Token Not Found'
        ]);
    }

    public function savePED(Request $request)
    {
        $body = $request->has('data') ? $request->data : [];
        $userId = $request->userid;
        try {
            $proposar = User::find($userId);
            if ($proposar) {
                $proposar->ped = $body;
                $proposar->save();
            }
            return response()->json([
                'status' => true,
                'message' => 'PED saved successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'PED not saved successfully'
            ]);
        }
    }

    public function savePort(Request $request)
    {
        try {
            $userId = $request->userid;
            $plantype = $request->data['plantype'];

            $cachehealthplan = 'cache_healthplan_' . $userId;
            SetCache($cachehealthplan, $plantype);

            $cacheporttenure = 'cache_porttenure_' . $userId;
            $porttenure = $request->data['tenure'] ?? '';
            SetCache($cacheporttenure, $porttenure);

            return response()->json([
                'status' => true,
                'plantype' => GetCache($cachehealthplan),
                'porttenure' => GetCache($cacheporttenure)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getPort(Request $request)
    {
        $userId = $request->userid;
        $cacheporttenure = 'cache_porttenure_' . $userId;
        $cachehealthplan = 'cache_healthplan_' . $userId;
        return response()->json([
            'status' => true,
            'plantype' => GetCache($cachehealthplan) ?? '1',
            'porttenure' => GetCache($cacheporttenure) ?? '1'
        ]);
    }

    public function QuoteView(Request $request)
    {
        // return true;
        try {
            $covertypeList = ['Individual' => 'Individual', 'Floater' => 'Floater', 'Multi Individual' => 'Multi Individual'];
            $userId = $request->userid;
            // $userObj = $userId;
            $userObj = User::find($userId);
            if ($userObj) {
                $userObj->addon = [];
                $userObj->save();
            }
            // $child = collect();
            $aInsureData = Insure::where('proposalid', $userId)->get();
            $nMemCount = count($aInsureData);
            // dd($nMemCount);
            if ($aInsureData->count() == 1) {
                $covertypeList = ['Individual' => 'Individual'];
            }
            if ($aInsureData->count() > 1) {
                $covertypeList = ['Floater' => 'Floater', 'Multi Individual' => 'Multi Individual'];
            }
            $cachecoverage = 'cache_coverage_' . $userId;
            $coverage = GetCache($cachecoverage) ?? '5';
            $cachetenure = 'cache_tenure_' . $userId;
            $tenure = GetCache($cachetenure) ?? '1';
            $coveragelist = getconstant('HEALTH.COVERAGE');
            $tenurelist = getconstant('HEALTH.TENURE');
            $addonList = getconstant('HEALTH.CARESUPREME.ADDON');
            // dd()
            $aVendor = MasterVendor::where('is_health', '1')
                ->where('isActive', '1')
                ->select('vid', 'type', 'vendorname', 'productname', 'logo', 'isActive')
                ->get()
                ->toArray();
            $cachehealthplan = 'cache_healthplan_' . $userId;
            $plantype = GetCache($cachehealthplan) ?? '1';

            //    $membercount = Insure::where('proposalid', $userId)->count();
            $covertye = $aInsureData->count() == 1 ? 'Individual' : 'Floater';
            $cachecovertype = 'cache_covertype_' . $userId;
            $covertypecache = GetCache($cachecovertype);
            if ($covertypecache) {
                SetCache($cachecovertype, $covertye);
            }
            // return $covertypecache;

            // $cacheporttenure = 'cache_porttenure_' . $userId;
            // $porttenure = GetCache($cacheporttenure);

            $cacheporttenure = 'cache_porttenure_' . $userId;
            $porttenure = GetCache($cacheporttenure);
            // return $covertypecache;
            $data = [
                'coverage' => $coverage,
                'tenure' => $tenure,
                'coveragelist' => $coveragelist,
                'tenurelist' => $tenurelist,
                'addonlist' => $addonList,
                'aInsureData' => $aInsureData,
                'plantype' => $plantype,
                // 'child' => $child,
                'error' => '',
                'vendor' => $aVendor,
                'porttenure' => $porttenure,
                'gender' => User::find($userId)->gender,
                'pincode' => User::find($userId)->pincode,
                'covertype' => $covertypecache,
                'covertypelist' => $covertypeList
            ];
            // dd( $data);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updatePincode(Request $request)
    {
        $userId = $request->userid;
        $aRequest = $request->data;
        $pincode = trim(explode('(', $aRequest['findpincode'])[0]);
        $obj = User::find($userId);

        if ($obj) {
            $obj->pincode = $pincode;
            $obj->save();
        }

        $cacheupdatepinstatus = 'cache_updatepinstatus_' . $userId;
        SetCache($cacheupdatepinstatus, '1');

        return response()->json([
            'status' => true
        ]);
    }

    public function UserInfo(Request $request)
    {
        $token = $request->header('Authorization');
        if (!empty($token)) {
            $isToken = PersonalAccessToken::findToken($token);
            if ($isToken) {
                DB::beginTransaction();
                $userId = $isToken->tokenable_id;

                $oUser = User::where('id', $userId)->first()->makeHidden(['ip', 'created_at', 'updated_at'])->toArray();

                $cachekyctype = 'cache_kyctype_' . $userId;
                $kyctype = GetCache($cachekyctype);
                DB::commit();
                return response()->json([
                    'status' => true,
                    'user' => $oUser,
                    'kyctype' => ''  // $kyctype,
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'User Not authorized'
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Token is invalid'
            ]);
        }
    }

    // public function updatePincode(Request $request)
    // {
    //     $userId = $request->userid;
    //     $pincode = trim(explode('(', $request->findpincode)[0]);
    //     $obj = User::find($userId);
    //     if ($obj) {
    //         $obj->pincode = $pincode;
    //         $obj->save();
    //     }
    //     //session()->put('updatepinstatus', '1');

    //     $cacheupdatepinstatus = 'cache_updatepinstatus_' . $userId;
    //     SetCache($cacheupdatepinstatus, '1');
    //     return redirect()->route('addon');
    // }
}
