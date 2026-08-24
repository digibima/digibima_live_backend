<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\{User, CareToken, UltimateToken, DigiPayment, MasterAPI, Claim, NotificationModel, Inquire, PersonalAccessToken, MasterVendor};
use Illuminate\Support\Facades\{Session, Auth, DB, Validator};
use App\Services\Api\CareSupremeService;
use Illuminate\Support\Facades\Storage;

class UserController
{
    public function StatusView(Request $request)
    {
        $userId = $request->userid;
        $oUser = User::where('id', $userId)->first();
        $cNotification = NotificationModel::where('userid', $userId)->where('status', 0)
            ->select('id', 'message', 'created_at')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'message' => getconstant("MESSAGE." . $item->message),
                    'time' => $item->created_at->diffForHumans(),
                ];
            });
        return response()->json([
            'status' => true,
            'user' => $oUser->name,
            'notification' => $cNotification
        ]);
    }
    public function index(Request $request)
    {
        $userId = $request->userid;
        // $paymentIds = DigiPayment::where('is_paid', '1')
        //     ->pluck('userid')
        //     ->toArray();
        $data = [
            'user' => User::where('id', $userId)
                ->first(['id', 'name', 'email', 'dob', 'mobile', 'city'])->toArray()
        ];


        $cNotification = NotificationModel::leftJoin('digibima_payment', 'notification.paymentid', '=', 'digibima_payment.id')
            ->leftJoin('master_vendor as MasterVendor', 'digibima_payment.vid', '=', 'MasterVendor.vid')
            ->where('notification.userid', $userId)
            ->where('digibima_payment.is_paid', 1)
            ->orderBy('notification.created_at', 'desc')
            ->limit(25)
            ->select(
                'notification.id as id',
                'notification.message',
                'notification.created_at',
                'MasterVendor.type as type',
                'MasterVendor.productname as vendor',
                'digibima_payment.vid',
                'digibima_payment.policy as policyno',
                'digibima_payment.proposal as proposalno',
                'digibima_payment.premium as price',
                'digibima_payment.proposar_name as name',
                'digibima_payment.vehicle_type as vehicle_type',
            )
            ->get()
            ->map(function ($item) {
                return [
                    'notificationId' => $item->id,
                    'message' => getconstant("MESSAGE." . $item->message) . " for " . $item->vendor,
                    'time' => $item->created_at->diffForHumans(),
                    'type' => $item->type,
                    'vendor' => $item->vendor,
                    'policyno' => $item->policyno,
                    'proposalno' => $item->proposalno,
                    'price' => $item->price,
                    'proposar_name' => $item->name,
                    'vehicle_type' => $item->vehicle_type

                ];
            });

        $totalclaims = Claim::where('userid', $userId)->count();

        $policyCount = DigiPayment::where('userid', $userId)->count();

        $activecount = DigiPayment::where('userid', $userId)->whereNotNull('policy')
            ->where('policy', '!=', 0)
            ->count();

        return response()->json([
            'status' => true,
            'data' => $data,
            'notification' => $cNotification,
            'totalclaims' => $totalclaims,
            'totalpolicies' => $policyCount,
            'totalactivepolicies' => $activecount,
        ]);
    }

    public function status(Request $request)
    {

        $jResponse = UdateStatus($request);
        return response()->json([
            'status' => $jResponse['updated'] ?? [],
        ]);
        //     if($jResponse['success'] == true){
        //     return response()->json([
        //         'status' => true,
        //     ]);
        // }else{
        //     return response()->json([
        //         'status' => false,
        //     ]);
        // }
    }

   public function getUserPolicy(Request $request)
{
    $userId = $request->userid;

    $policy = DigiPayment::leftJoin(
           'master_vendor as MasterVendor',
    'digibima_payment.policy_name',
    '=',
    'MasterVendor.vid'
        )
        ->where('digibima_payment.userid', $userId)
         ->select([
            'digibima_payment.id',
            'digibima_payment.upload',

            'digibima_payment.policy',
            'digibima_payment.proposal',
            'digibima_payment.status_details',
            'digibima_payment.proposar_name',

            \DB::raw('COALESCE(MasterVendor.productname, digibima_payment.policy_name) as policy_name'),

            'digibima_payment.policy_type',
            'digibima_payment.issue_date',
            'digibima_payment.from_date',
            'digibima_payment.to_date',
            'digibima_payment.policy_pdf_path',

            'MasterVendor.productname as vendor_policy_name',
            'MasterVendor.type as vendor_policy_type',
        ])
        ->paginate(5);
        $policy->getCollection()->transform(function ($item) {

    $item->proposar_name = ucfirst(strtolower($item->proposar_name));

    if ($item->upload == "1" && !empty($item->policy_pdf_path)) {
        $item->policy_pdf_path = Storage::disk('minio')->url($item->policy_pdf_path);
    }

    $today = date('Y-m-d');

    if ($item->to_date) {

        if ($today <= $item->to_date) {
            $item->status = "Active";
        } else {
            $item->status = "Inactive";
        }

    } else {
        $item->status = "Inactive";
    }

    return $item;
});

    return ['policies' => $policy];
}
    public function userPolicy(Request $request)
    {
        $data = $this->getUserPolicy($request);
        return response()->json([
            'status' => true,
            'data' => $data

        ]);
    }

    public function userSetting(Request $request)
    {
        $userId = $request->userId;
        $data = [
            'user' => User::where('id', $userId)
                ->first(['id', 'name', 'email', 'dob', 'mobile', 'city', 'income', 'martial_status'])->toArray()
        ];
        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }
    public function userProfileUpdate(Request $request)
    {
        $userId = $request->userid;
        try {
            $userobj = User::find($userId);
            if ($userobj) {
                $userobj->name = $request->data['name'] ?? "";
                $userobj->email = $request->data['email'] ?? "";
                $userobj->gender = $request->data['gender'] ?? "";

                if (isset($request->data['usermobile']) && !empty($request->data['mobile'])) {
                    $userobj->mobile = encodeMobile($request->data['mobile']) ?? "";
                }
                $userobj->dob = $request->data['dob'] ?? "";
                $userobj->income = $request->data['income'] ?? "";
                $userobj->martial_status = $request->data['marital_status'] ?? "";
                $userobj->city = $request->data['city'] ?? "";
                $response = $userobj->save();
                if ($response) {
                    return response()->json([
                        'status' => true,
                        'message' => "Profile updated successfully"
                    ]);
                }
            }
            return response()->json([
                'status' => false,
                'message' => "User not found"
            ]);
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }



    public function CreateClaim(Request $request)
    {
        try {
            $userId = $request->userId;
            $oClaim = new Claim();
            $oClaim->userid = $userId;
            $oClaim->name = $request->data['name'] ?? "";
            $oClaim->email = $request->data['email'] ?? "";
            $oClaim->mobile = $request->data['mobile'] ?? "";
            $oClaim->policy = $request->data['policy'] ?? "";
            $oClaim->policy_number = $request->data['policy_number'] ?? "";
            $oClaim->created_at = now();
            $oClaim->updated_at = now();
            $oClaim->save();
            return response()->json([
                'status' => true
            ]);
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    // public function policyPdf(Request $request)
    // {
    //     try {
    //         $filePath = $request->data;

    //         $relativePath = $filePath;
    //         $filePath = public_path($relativePath);
    //         if (file_exists($filePath)) {
    //             return response()->download($filePath);
    //         }

    //     } catch (\Exception $e) {
    //         \Log::info($e->getMessage());
    //         return response()->json([
    //             'status' => false,
    //             'message' => $e->getMessage()
    //         ]);
    //     }
    // }


    public function Inquire(Request $request)
    {
        $userId = null;
        $token = $request->header('Authorization');
        $isToken = PersonalAccessToken::findToken($token);

        if ($isToken) {
            $userId = $isToken->tokenable_id;
            $user = Inquire::find($userId);
            if ($user) {
                $data = [
                    'status' => true,
                    'message' => 'User loggedin already.',
                ];
                return response()->json($data);
            }
        }
        try {
            $validator = Validator::make($request->data, [
                'name' => 'required|string',
                'mobile' => 'required|numeric',
                'dob' => 'required|string',
                'gender' => 'required|string',
                'email' => 'required|string',
                'product' => 'required|string',
                'promo' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ]);
            }
            $request = json_decode(json_encode($request->data));
            $encryptedMobile = encodeMobile($request->mobile);
            $user = Inquire::where('mobile', $encryptedMobile)->first();
            if ($user) {
                $user->name = $request->name;
                $user->dob = $request->dob;
                $user->gender = $request->gender ?? '';
                $user->email = $request->email ?? '';
                $user->product = $request->product ?? '';
                $user->promo = $request->promo ?? '';
                $user->updated_at = now();
                $user->save();

            } else {
                $user = new Inquire();
                $user->name = $request->name;
                $user->mobile = $encryptedMobile;
                $user->dob = $request->dob;
                $user->gender = $request->gender ?? '';
                $user->email = $request->email ?? '';
                $user->product = $request->product ?? '';
                $user->promo = $request->promo ?? '';
                $user->created_at = now();
                $user->updated_at = now();
                $user->save();
            }
            if (!$isToken) {
                $token = $user->createToken('token')->plainTextToken;
                $isToken = PersonalAccessToken::findToken($token);
            }
            return response()->json([
                'status' => true,
                'message' => 'User loggedin successfully.',
                'token' => $token,
            ]);
        } catch (\Exception $e) {
            \Log::error('Login API error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function policyPdf(Request $request)
    {
        try {
            $userId = $request->userid;
            $requestdata = $request->data;
            $proposalno = $requestdata['proposalno'] ?? '';


            $vendor = DigiPayment::where('proposal', $proposalno)->first();
            $proposal = $vendor->proposal;

            $aToken = json_decode(CareSupremeService::generatePartnerToken($request, 'createPolicy'));

            if (json_last_error() !== JSON_ERROR_NONE || !$aToken) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Invalid token response',
                    'raw' => $aToken
                ], 500);
            }

            if (!isset($aToken->sSessionID, $aToken->tokenid)) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Token keys missing',
                    'data' => $aToken
                ], 500);
            }

            $sSessionID = $aToken->sSessionID;
            $tokenid = $aToken->tokenid;

            $response = CareSupremeService::policyStatus($sSessionID, $tokenid, $proposal);
            $responseData = json_decode($response, true);


            if ($responseData['responseData']['status'] == '0') {
                return response()->json([
                    'status' => '0',
                    'data' => $responseData,
                    'message' => $responseData['responseData']['message'] ?? ''
                ]);
            }

            $policy = $responseData['intGetPolicyStatusIO']['policyNum'];
            // return $policy;

            $aTokenPdf = json_decode(CareSupremeService::generatePartnerToken($request, 'policyPdf'));
            $sSessionID = $aTokenPdf->sSessionID;
            $tokenid = $aTokenPdf->tokenid;

            $response = CareSupremeService::policyPdf($sSessionID, $tokenid, $policy);


            $decodedResponse = json_decode($response, true);

            //return $decodedResponse;

            if (($decodedResponse['responseData']['status'] ?? '0') == '0') {
                return null;

            }

            $dataPDF = $decodedResponse['intFaveoGetPolicyPDFIO']['dataPDF'];


            $pdfBinary = base64_decode($dataPDF);

            $urllocation = "upload/care_supereme_policy";
            $directory = public_path($urllocation);

            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $fileName = $policy . '.pdf';
            $filePath = $directory . '/' . $fileName;

            file_put_contents($filePath, $pdfBinary);

            //    return asset($urllocation . '/' . $fileName);
            $data = [
                'policyURL' => $fileName
                    ? url('upload/care_supereme_policy/' . $fileName)
                    : null
            ];

            return response()->json([
                'status' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

}