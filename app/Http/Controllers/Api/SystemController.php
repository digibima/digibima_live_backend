<?php

namespace App\Http\Controllers\Api;

use App\Models\PersonalAccessToken;
use App\Models\Pincode;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Log};
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;

class SystemController
{
    public static function optimize()
    {
        // Artisan::call('app:mycommand');
        // Log::info(Artisan::call('schedule:list'));
        // Log::info(Artisan::output());
        // Artisan::call('schedule:run');
        Artisan::call('optimize');
        Artisan::call('route:clear');
        Artisan::call('config:cache');
        Artisan::call('config:clear');
        Artisan::call('view:clear');

        // $output = shell_exec('composer dump-autoload');
        return redirect()->back();
    }

    public function MModeDown()
    {
        $key = '333';
        Artisan::call('down --secret=' . $key);
        return redirect(getconstant('BASE_URL') . $key);
    }

    public function MModeUp()
    {
        Artisan::call('up');
        return redirect()->route('root');
    }

    public static function Logout()
    {
        if (Auth::check()) {
            Auth::logout();
            Session::flush();
        }
        return redirect()->route('root');
    }

    public function AutoCompletePincodeApi(Request $request)
    {
        // return response()->json($request->all());
        $pincode = null;
        if ($request->has('data')) {
            $pincode = $request->data['pincode'];
        } elseif ($request->has('pincode')) {
            $pincode = $request->pincode;
        }
        $pincodeData = Pincode::where('pincode', 'like', $pincode . '%')->pluck('district', 'pincode');
        return response()->json($pincodeData);
    }

    public function LogoutToken(Request $request)
    {
        try {
            $token = $request->header('Authorization');
            if (empty($token)) {
                return response()->json([
                    'status' => false,
                    'message' => 'token not provided.'
                ]);
            }
            $isToken = $token ? PersonalAccessToken::findToken($token) : null;
            $userId = null;
            if ($isToken) {
                $userId = $isToken->tokenable_id;
                $cacheuseridv = 'user_' . $userId . '_bikeidv';
                DeleteCache($cacheuseridv);

                $cacheaVehicleDetails = 'cache_aVehicleDetails_' . $userId;
                DeleteCache($cacheaVehicleDetails);

                $cacheuseridv = 'cache_' . $userId . '_bikeidv';
                DeleteCache($cacheuseridv);

                $cacheuseridv = 'user_' . $userId . '_caridv';
                DeleteCache($cacheuseridv);

                $cacheuseridv = 'cache_' . $userId . '_caridv';
                DeleteCache($cacheuseridv);

                $cachekyctype = 'cache_kyctype_' . $userId;
                DeleteCache($cachekyctype);

                $cachekyc = 'cache_kyc_' . $userId;
                DeleteCache($cachekyc);

                $isToken->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Logged out successfully.'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Already logout.'
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return response()->json([
                Err($e)
            ], 500);
        }
    }

    public function verifyToken(Request $request)
    {
        try {
            $token = $request->header('Authorization');
            $isToken = PersonalAccessToken::findToken($token);
            // dd($isToken);
            if ($isToken) {
                $data = [
                    'status' => true,
                    'message' => 'Token vrified.',
                ];
                return response()->json($data);
            }
            return response()->json([
                'status' => false,
                'message' => 'Token not verified.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong on the server.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
