<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{User, Policy, DigiPayment, MasterVendor,PersonalAccessToken};
use App\Services\WhatsappServive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Exception;

class InsuranceUploadController
{
    private WhatsappServive $whatsappService;

    public function __construct(WhatsappServive $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

public function Login(Request $request)
{
    try {
        $request->validate([
            'mobile' => 'required'
        ]);

        $mobile = encodeMobile($request->mobile);

        $user = User::where('mobile', $mobile)
            ->whereIn('role', ['admin', 'employee'])
            ->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Admin or Employee not registered or not authorized',
            ], 401);
        }

        // Authorization header se existing token check
        $headerToken = $request->header('Authorization');

        $existingToken = null;

        if (!empty($headerToken)) {
            $existingToken = PersonalAccessToken::findToken($headerToken);
        }

        // Same user ka token already hai
        if (
            $existingToken &&
            $existingToken->tokenable_id == $user->id
        ) {
            $token = $headerToken;
        } else {
            // New token create
            $token = $user->createToken('insurance-upload')->plainTextToken;
        }

        return response()->json([
            'status' => true,
            'message' => 'Logged in successfully',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
            ],
        ]);

    } catch (\Throwable $e) {
        return response()->json([
            'status' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}

    public function UploadPolicy(Request $request)
    {
        if ($request->has('upload')) {
            $this->whatsappService->sendSms($request->mobile, $request->name);
            return response()->json([
                'status' => true,
                'message' => 'Policy uploaded successfully',
            ]);
        }
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:70',
                'mobile' => 'required|numeric',
                'pincode' => 'required|string|max:6',
                'gender' => 'required|string',
                'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'policynumber' => 'nullable|string|max:255',
                'policyname' => 'nullable|string|max:255',
                'policytype' => 'nullable|string|max:255',
                'issuedate' => 'nullable|date',
                'fromdate' => 'nullable|date',
                'todate' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $mobile = $request->mobile;
            $encryptedMobile = encodeMobile($mobile);

            $user = User::where('mobile', $encryptedMobile)->first(['id']);

            $userExist = true;
            if ($user) {
                SaveFile("user available:{$request->name} and {$request->mobile}", 'user.txt');
            }
            if (!$user) {
                $user = new User();
                $user->name = $request->name;
                $user->mobile = $encryptedMobile;
                $user->pincode = $request->pincode;
                $user->gender = $request->gender;
                $user->email = $request->email ?? '';
                $user->save();
                $res = $this->whatsappService->sendSms($request->mobile, $request->name);
                SaveFile("user created:{$request->name} and {$request->mobile} ", 'user.txt');
                $userExist = false;
            }
            // $this->whatsappService->sendSms($request->mobile, $request->name);
            // return response()->json([
            //     'message' => 'user avl Policy uploaded successfully',
            // ]);
            $userId = $user->id;
            $file = $request->file('document');

            $originalName = pathinfo(
                $file->getClientOriginalName(),
                PATHINFO_FILENAME
            );

            $extension = $file->getClientOriginalExtension();

            $fileName = strlen($originalName) > 10
                ? substr($originalName, 0, 10)
                : $originalName;
            $fileName = Str::uuid() . '_' . Str::slug($fileName, '_');
            $fileName .= '.' . $extension;
            $filePath = Storage::disk('minio')->putFileAs(
                'policy',
                $file,
                $fileName
            );

            $url = Storage::disk('minio')->url($filePath);
            $policy = new Policy();
            $policy->userid = $userId;
            $policy->file = $filePath;
            $policy->created_at = now();
            $policy->save();
            $digiPayment = new DigiPayment();
            $digiPayment->userid = $userId;
            $digiPayment->vid = $request->vid;
            $digiPayment->proposar_name = $request->name;
            $digiPayment->policy_pdf_path = $filePath;
            $digiPayment->upload = 1;

            // New Fields
            $digiPayment->policy = $request->policynumber;
            $digiPayment->policy_name = $request->policyname;
            $digiPayment->policy_type = $request->policytype;
            $digiPayment->issue_date = $request->issuedate;
            $digiPayment->from_date = $request->fromdate;
            $digiPayment->to_date = $request->todate;
            $digiPayment->save();

            return response()->json([
                'status' => true,
                'message' => 'Policy uploaded successfully',
                'user_id' => $userId,
                'file_path' => $filePath,
                'exist' => $userExist,
                'url' => $url
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
        }
    }

    public function getVendors(Request $request)
    {
        try {
            $vehicleType = strtolower($request->type ?? '');

            if (in_array($vehicleType, ['car', 'bike'])) {
                // Car ya Bike vendors
                // isActive ka check nahi hoga
                $vendors = MasterVendor::where(function ($q) {
                    $q
                        ->where('is_car', '1')
                        ->orWhere('is_bike', '1');
                })
                    ->get(['vid', 'vendorname'])
                    ->map(function ($vendor) {
                        $vendor->vendorname = ucwords(strtolower($vendor->vendorname));
                        return $vendor;
                    });

                $category = 'motor';
            } else {
                // Health vendors
                // Sirf is_health = 1 check hoga
                // isActive ka check nahi hoga
                $vendors = MasterVendor::where('is_health', '1')
                    ->get(['vid', 'vendorname'])
                    ->map(function ($vendor) {
                        $vendor->vendorname = ucwords(strtolower($vendor->vendorname));
                        return $vendor;
                    });

                $category = 'health';
            }

            return response()->json([
                'status' => true,
                'type' => $vehicleType,
                'category' => $category,
                'vendors' => $vendors
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function ReadPolicy() {}
    public function DeletePolicy() {}

public function Logout(Request $request)
{
    try {
        $token = $request->header('Authorization');

        if (empty($token)) {
            return response()->json([
                'status' => false,
                'message' => 'token not provided.'
            ], 401);
        }

        $isToken = PersonalAccessToken::findToken($token);

        if (!$isToken) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid token or already logout.'
            ], 401);
        }

        $userId = $isToken->tokenable_id;

        $isToken->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully.'
        ]);

    } catch (\Throwable $e) {
        return response()->json([
            'status' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
}
