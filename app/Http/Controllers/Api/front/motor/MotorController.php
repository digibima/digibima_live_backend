<?php

namespace App\Http\Controllers\Api\front\motor;

use App\Http\Controllers\Api\front\health\HealthController;
use App\Http\Controllers\SystemController;
use App\Models\Shriram\{Shriram_Pincode, Shriram_planCheckout, Shriram_RTO_Master};
use App\Models\{Master_Vehicle_Data as DataModel, User, Vehicle_Info, PersonalAccessToken};
use App\Services\Api\{ShriramService};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Session, Cache, Log, DB, Gate, Validator};

class MotorController
{
    public function Login(Request $request)
    {
        $oHealth = new HealthController();
        $response = $oHealth->Login($request);
        return $response;
    }

    // public function showBrand(Request $request)
    // {
    //     $aData = $request->data;
    //     $sVehicaleType = $aData['brand'];
    //     $brands = Vehicle_Info::where('PRODUCT_CODE', $sVehicaleType)
    //         ->get(['id', 'MANUFACTURER'])
    //         ->unique('MANUFACTURER')
    //         ->sortBy('MANUFACTURER')
    //         ->values();
    //     if ($brands) {
    //         return response()->json([
    //             'status' => '1',
    //             'brand' => $brands,
    //             'date' => date('d-m-Y')
    //         ]);
    //     }
    //     return response()->json([
    //         'status' => '0',
    //         'message' => 'Not Available'
    //     ]);
    // }

    // public function showModel(Request $request)
    // {
    //     // dd($request);
    //     $aData = $request->data;
    //     $sVehicaleType = $aData['type'];
    //     $brand = $aData['brand'];

    //     $models = Vehicle_Info::where('MANUFACTURER', $brand)
    //         ->where('PRODUCT_CODE', $sVehicaleType)
    //         ->select('id', 'VEHICLE_CODE as code', 'MODEL_DESCRIPTION as model')
    //         ->orderBy('MODEL_DESCRIPTION')
    //         ->get();
    //     if ($models) {
    //         return response()->json($models);
    //     }
    //     return response()->json([
    //         'status' => '0',
    //         'message' => 'Not Available'
    //     ]);
    // }
    public function showBrand(Request $request)
    {
        $aData = $request->data;
        $sVehicaleType = $aData['brand'];
        $tables = [
            'shriram_vehicle_master',
            'godigit_vehicle_master',
        ];
        $query = null;
        foreach ($tables as $table) {
            if (is_null($query)) {
                $query = DB::table($table)
                    ->where('PRODUCT_CODE', $sVehicaleType)
                    ->select('MANUFACTURER');
            } else {
                $nextTable = DB::table($table)
                    ->where('PRODUCT_CODE', $sVehicaleType)
                    ->select('MANUFACTURER');
                $query->union($nextTable);
            }
        }
        $brands = $query
            ->get()
            ->unique('MANUFACTURER')
            ->sortBy('MANUFACTURER')
            ->values()
            ->map(function ($item, $key) {
                return [
                    'id' => $key + 1,
                    'MANUFACTURER' => $item->MANUFACTURER
                ];
            });
        // $brands = Vehicle_Info::where('PRODUCT_CODE', $sVehicaleType)
        //     ->get(['id', 'MANUFACTURER'])
        //     ->unique('MANUFACTURER')
        //     ->sortBy('MANUFACTURER')
        //     ->values();
        // dd($brands);
        if ($brands) {
            return response()->json([
                'status' => '1',
                'brand' => $brands,
                'date' => date('d-m-Y')
            ]);
        }
        return response()->json([
            'status' => '0',
            'message' => 'Not Available'
        ]);
    }

    public function showModel(Request $request)
    {
        $aData = $request->data;
        $sVehicaleType = $aData['type'];
        $brand = $aData['brand'];
        $tables = [
            'shriram_vehicle_master',
            'godigit_vehicle_master',
        ];
        $query = null;
        foreach ($tables as $table) {
            if (is_null($query)) {
                $query = DB::table($table)
                    ->where('MANUFACTURER', $brand)
                    ->where('PRODUCT_CODE', $sVehicaleType)
                    ->select('id', 'VCODE as code', 'MODEL_DESCRIPTION as model');
            } else {
                $nextTable = DB::table($table)
                    ->where('MANUFACTURER', $brand)
                    ->where('PRODUCT_CODE', $sVehicaleType)
                    ->select('id', 'VCODE as code', 'MODEL_DESCRIPTION as model');

                $query->union($nextTable);
            }
        }
        $allModels = $query->get();

        $uniqueModels = $allModels->unique(function ($item) {
            $words = explode(' ', strtoupper(trim($item->model)));
            sort($words);
            return implode(' ', $words);
        })->values();

        $models = $uniqueModels->map(function ($item, $key) {
            return [
                'id' => $key + 1,
                'code' => $item->code,
                'model' => $item->model,
            ];
        });

        if ($models->isNotEmpty()) {
            return response()->json($models);
        }

        return response()->json([
            'status' => '0',
            'message' => 'Not Available'
        ]);
    }

    public function AutoCompletePincode(Request $request)
    {
        $pincodeData = Shriram_Pincode::where('PC_CODE', 'like', $request->pincode . '%')->select('PIN_DESC', 'STATEDESC')->get();
        return response()->json($pincodeData);
    }

    public function getCity(Request $request)
    {
        $city = $request->input('data.city');
        $cities = Shriram_RTO_Master::where('RTONAME', 'like', '%' . $city . '%')
            ->orderBy('RTONAME', 'desc')
            ->limit(20)
            ->select('RTONAME', 'RTOCODE')
            ->get();

        $cities = $cities->map(function ($item) {
            return $item->RTONAME . '(' . $item->RTOCODE . ')';
        });
        return response()->json($cities);
    }

    public function verifyRto(Request $request)
    {
        // $sData = $request->data;
        // $getdata = getRtocity($request,$sData['carregnumber']);
        try {
            $sData = $request->input('data.carregnumber');

            $getdata = getRtocityApi($request, $sData);
            if ($getdata) {
                return response()->json([
                    'status' => true,
                    'message' => 'RTO code is valid'
                ]);
            }
            return response()->json([
                'status' => false,
                'message' => 'RTO code is not valid'
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function verifyBikeRto(Request $request)
    {
        $sData = $request->input('data.bikeregnumber');
        $getdata = getBikeRtocityApi($request, $sData);
        if ($getdata) {
            return response()->json([
                'status' => true,
                'message' => 'RTO code is valid',
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'RTO code is not valid'
        ]);
    }

    public function logout(Request $request)
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
            if ($isToken) {
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
                'status' => false,
                'message' => 'Something went wrong on the server.'
            ]);
        }
    }
}
