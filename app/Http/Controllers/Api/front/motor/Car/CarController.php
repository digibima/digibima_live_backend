<?php
namespace App\Http\Controllers\Api\front\motor\Car;

use App\Models\Master\{Shriram as Shriram_Vehicle_Master};
use App\Models\{Master_Vehicle_Data as DataModel, User, MasterVendor, VendorMotor, MasterMotor};
use App\Services\Api\{MotorService};
use Carbon\Carbon;
use Illuminate\Http\Request;
// use App\Services\{ShriramService};
use Illuminate\Support\Facades\{Cache};
use DateTime;

class CarController
{
    private static $msg = '';
    private static $status = '';
    private MotorService $motorService;

    public function __construct(MotorService $motorService)
    {
        $this->motorService = $motorService;
    }

    public function KnowCarDetails(
        Request $request,
    ) {
        $method = $request->method();
        $userId = $request->userid;
        $response = null;
        try {
            // session()->put('motortype', 'knowcar');
            $cachemotortype = 'cache_motortype_' . $userId;
            // $cachemotortype = 'cache_motortype_' . $userId;
            SetCache($cachemotortype, 'knowcar');

            $data = DataModel::where('userid', $userId)->first();
            if ($method == 'GET') {
                return response()->json([
                    'status' => true,
                    'data' => ['carregnumber' => $data->carnumber ?? ''],
                    'cache' => GetCache($cachemotortype)
                ]);
            }
            $request = json_decode(json_encode($request->data));
            $rcno = $request->carregnumber;
            $response = $this->motorService->VahanApi($rcno);
            SetCache($userId . '_vahandata', json_encode($response));
            $vahandata = [
                'rc_number' => $request->carregnumber,
                'brand' => $response['maker_description'] ?? '',
                'model' => $response['maker_model'] ?? '',
                'regno' => !empty($response['registration_date'])
                    ? date('d-m-Y', strtotime($response['registration_date']))
                    : '',
            ];
            SetCache('cache_vahandata_' . $userId, $vahandata);
            if (!empty($data)) {
                $data->carnumber = $request->carregnumber;
                $data->updated_at = now();
                $response = $data->save();
            } else {
                $obj = new DataModel();
                $obj->userid = $userId;
                $obj->carnumber = $request->carregnumber;
                $obj->created_at = now();
                $obj->updated_at = now();
                $response = $obj->save();
            }
            if ($response) {
                return response()->json([
                    'status' => true,
                    'message' => 'Saved',
                    'cache' => GetCache($cachemotortype)
                ]);
            }
            return response()->json([
                'status' => false,
                'message' => 'Data not saved or updated'
            ]);
        } catch (\Exception $e) {
            \Log::info($e->getMessage() . ' errorcode:KnowCarDetails');
            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function KnowCarsteptwo(Request $request)
    {
        $method = $request->method();
        $aRequest = $request->data;
        $userId = $request->userid;
        $sMsg = '';
        $bStatus = false;
        // dd($aRequest);
        try {
            $data = DataModel::where('userid', $userId)->first();
            if ($method == 'GET') {
                $vahandata = GetCache('cache_vahandata_' . $userId);
                // $cardata = $data->knowcar_reg_details ? json_decode($data->knowcar_reg_details, true) : [];
                // $carBrandId = $vahandata['brand'] ?? null;
                // $carmodel = $cardata['model'] ?? null;
                // if ($carmodel) {
                //     $carmodel = Shriram_Vehicle_Master::where('id', $carmodel)->first(['MODEL_DESCRIPTION']);
                // }
                return response()->json([
                    'status' => true,
                    'data' => $data->knowcar_reg_details ? json_decode($data->knowcar_reg_details, true) : [],
                    'vahandata' => $vahandata,
                    // 'model' => $carmodel->MODEL_DESCRIPTION ?? null
                ]);
            }
            // dd($aRequest);
            if (!empty($data)) {
                $regData = $data && $data->knowcar_reg_details ? json_decode($data->knowcar_reg_details, true) : [];
                $MergedData = array_replace($regData, $aRequest);
                $data->knowcar_reg_details = json_encode($MergedData);
                $data->updated_at = now();
                $sMsg = 'Data updated';
                $bStatus = true;
            } else {
                $data = new DataModel();
                $data->knowcar_reg_details = json_encode($aRequest);
                $data->created_at = now();
                $data->updated_at = now();
                $sMsg = 'Data saved';
                $bStatus = true;
            }

            $response = $data->save();
            if ($response) {
                $bStatus = true;
            } else {
                $bStatus = false;
                $sMsg = 'Something is wrong while saving the data.';
            }
            return response()->json([
                'status' => $bStatus,
                'message' => $sMsg,
            ]);
        } catch (\Exception $e) {
            \Log::info($e->getMessage() . ' errorcode:KnowCarDetails');
            $bStatus = false;
            return response()->json([
                'status' => $bStatus,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function knowcarStepthree(
        Request $request,
    ) {
        $method = $request->method();
        $userId = $request->userid;
        $aRequest = $request->data;

        // dd($aRequest);
        if ($method == 'GET') {
            $data = null;
            $odstart = null;
            $prepolicy = null;
            $partialdata = null;
            $response = GetCache($userId . '_vahandata');
            $response = json_decode($response, true);
            $regdate = $response['registration_date'];
            $insurance_upto = $response['insurance_upto'];
            $monthsfromexpiring = Carbon::parse($regdate)
                ->diffInMonths(Carbon::parse($insurance_upto));
            $monthsfromtoday = Carbon::parse($regdate)
                ->diffInMonths(Carbon::parse(today()->format('Y-m-d')));
            // $monthsfromtoday = 16;
            $Regdata = DataModel::where('userid', $userId)->first(['knowcar_reg_details']);
            $Regdata = json_decode($Regdata->knowcar_reg_details, true);
            if ($monthsfromtoday <= 17) {
                $odstart = Carbon::parse($insurance_upto)->subYear(3)->addDays(1);
            } elseif ($monthsfromtoday > 17 && $monthsfromtoday <= 29) {
                $odstart = Carbon::parse($insurance_upto)->subYear(2)->addDays(1);
            } elseif ($monthsfromtoday > 29 && $monthsfromtoday <= 41) {
                $odstart = Carbon::parse($insurance_upto)->subYear(1)->addDays(1);
            } else {
                $prepolicy = 'comprehensive';
            }
            // dd($prepolicy, $Regdata);
            if ($prepolicy != 'comprehensive') {
                $partialdata = [
                    'bdfromdate' => Carbon::parse($odstart)->format('d-m-Y'),
                    'bdtodate' => Carbon::parse($odstart)->addYear(1)->subDays(1)->format('d-m-Y'),
                    'bdtpfromdate' => Carbon::parse($insurance_upto)->subYear(3)->addDays(1)->format('d-m-Y'),
                    'bdtptodate' => Carbon::parse($insurance_upto)->format('d-m-Y'),
                    'prepolitype' => 'bundled',
                ];
            }
            if ($prepolicy == 'comprehensive') {
                $partialdata = [
                    'compfromdate' => Carbon::parse($odstart)->format('d-m-Y'),
                    'comptodate' => Carbon::parse($odstart)->addYear(1)->subDays(1)->format('d-m-Y'),
                    'prepolitype' => 'comprehensive',
                ];
            }
            $data = array_merge([
                'brand' => $response['maker_description'],
                'model' => $response['maker_model'],
                'bonus-button' => $Regdata['bonus-button'] ?? null,
                'carregdate' => Carbon::parse($regdate)->format('d-m-Y'),
                'brandyear' => Carbon::parse($regdate)->format('Y'),
                'ownershiptoggle' => $Regdata['ownershiptoggle'] ?? null,
                'policyclaim' => $Regdata['policyclaim'] ?? null,
                'under' => $Regdata['under'] ?? 'individual'
            ], $partialdata);
            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        }

        $oResponse = DataModel::where('userid', $userId)->first();
        $cardetails = json_decode($oResponse->knowcar_reg_details, true) ?? [];
        $mergeDetails = array_replace($cardetails, $aRequest);
        Cache::forget('user_' . $userId . '_caridv');

        Cache::forget('cache_' . $userId . '_caridv');
        // session()->forget('caridv');
        setPaCover();
        try {
            if ($oResponse) {
                $oResponse->knowcar_reg_details = json_encode($mergeDetails);
                $oResponse->updated_at = now();
                $oResponse->save();
            }
            return response()->json([
                'status' => true,
            ]);
            // return redirect()->route('car.plans', ['id' => $id]);
        } catch (\Exception $e) {
            // echo $e->getMessage() . " errorcode:knowcarSteptwo";
            \Log::info($e->getMessage() . ' errorcode:knowcarSteptwo');
            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function NewCarDetails(Request $request)
    {
        try {
            $userId = $request->userid;
            $method = $request->method();
            $aRequest = $request->data;
            $oData = DataModel::where('userid', $userId)->first();
            if ($method == 'GET') {
                $data = $oData;
                return response()->json([
                    'status' => true,
                    'data' => $data->rtocode
                ]);
            }
            $cachemotortype = 'cache_motortype_' . $userId;
            SetCache($cachemotortype, 'newcar');
            if (!empty($oData)) {
                $oData->rtocode = $aRequest['newcarcity'];
                $oData->car_plan_type = '2';
                $oData->updated_at = now();
                $sMsg = 'Data saved';
                $bStatus = true;
            } else {
                $oData = new DataModel();
                $oData->userid = $userId;
                $oData->rtocode = $aRequest['newcarcity'];
                if (GetCache($cachemotortype) == 'newcar') {
                    $oData->car_plan_type = '2';
                }
                $oData->created_at = now();
                $oData->updated_at = now();
            }
            $response = $oData->save();

            $cachemotortype = 'cache_motortype_' . $userId;
            SetCache($cachemotortype, 'newcar');

            if ($response) {
                return response()->json([
                    'message' => $sMsg,
                    'status' => $bStatus,
                    // 'data' => $oData
                ]);
            } else {
                return response()->json([
                    'message' => 'Failed to save data',
                    'status' => false
                ]);
            }
        } catch (\Exception $e) {
            \Log::info($e->getMessage() . ' errorcode:NewCarDetails');
            return response()->json([
                'status' => false,
                'message' => 'Server error occurred',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function newcarSteptwo(Request $request)
    {
        $method = $request->method();
        $userId = $request->userid;
        $aRequest = $request->data;
        // $brand = $aRequest['brand'];
        // $model = $aRequest['model'];
        // $cachemodel = 'cache_model_' . $userId;
        // SetCache($cachemodel,$model);
        Cache::forget('user_' . $userId . '_caridv');
        // session()->forget('caridv');
        Cache::forget('cache_' . $userId . '_caridv');
        setPaCover();
        try {
            $oResponse = DataModel::where('userid', $userId)->first();
            if ($method == 'GET') {
                return response()->json([
                    'status' => true,
                    'data' => json_decode($oResponse->newcar_reg_details, true)
                ]);
            }

            if ($oResponse) {
                $oResponse->newcar_reg_details = json_encode($aRequest);
                $oResponse->updated_at = now();
                $oResponse->save();
            }
            return response()->json([
                'status' => true,
            ]);
        } catch (\Exception $e) {
            // echo $e->getMessage() . " errorcode:newcarSteptwo";
            \Log::info($e->getMessage() . ' errorcode:newcarSteptwo');
            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function carPlan(Request $request)
    {
        $userId = $request->userid;
        $cacheaVehicleDetails = 'cache_aVehicleDetails_' . $userId;
        DeleteCache($cacheaVehicleDetails);
        $msg = '';
        try {
            $model = '';
            $rtocity = '';
            $registerDate = '';
            $aNewCar = null;
            $masterMotor = MasterMotor::whereNotNull('addon')
                ->where('is_car', 1)
                ->get();

            $aResult = $masterMotor
                ->where('is_comp', 1)
                ->pluck('addon', 'id')
                ->toArray();

            $aResulttp = $masterMotor
                ->where('is_tp', 1)
                ->pluck('addon', 'id')
                ->toArray();

            $aResultod = $masterMotor
                ->where('is_od', 1)
                ->pluck('addon', 'id')
                ->toArray();

            $aDiscount = $masterMotor
                ->where('discount', 1)
                ->pluck('addon', 'id')
                ->toArray();
            $dataModelQuery = DataModel::where('userid', $userId);
            $aResult2 = $dataModelQuery->first();
            $accessories = json_decode($aResult2->accessories, true);
            $aPlantype = getconstant('MOTOR.PLANTYPEQUOTE');
            // dd($aPlantype);
            $plantype = null;
            $sMessage = '';
            $pacover = '';
            $cachemotortype = 'cache_motortype_' . $userId;
            $typeee = GetCache($cachemotortype);
            if (GetCache($cachemotortype) === 'knowcar') {
                $msg = 'knowcar';
                $aCarPlan = json_decode($aResult2->knowcar_reg_details, true);
                $registerDate = strtotime($aCarPlan['carregdate']);
                $registerDate = DateTime::createFromFormat('d-m-Y', $aCarPlan['carregdate']);
                $now = new DateTime();
                $thresholdDate1 = clone $now;
                $thresholdDate2 = clone $now;
                $thresholdDate3 = clone $now;
                $thresholdDate2->modify('-2 years -90 days');
                $thresholdDate3->modify('-3 years');
                $thresholdDate1->modify('-1 years');
                if ($aCarPlan['prepolitype'] == 'bundled') {
                    if ($registerDate <= $thresholdDate1 && $registerDate >= $thresholdDate2) {
                        $msg = 'bundled';
                        unset($aPlantype['3']);
                        unset($aPlantype['2']);
                        $plantype = '1';
                    } elseif ($registerDate >= $thresholdDate1) {
                        unset($aPlantype['3']);
                        unset($aPlantype['2']);
                        $plantype = '1';
                        // unset($aPlantype['1']);
                    } elseif ($registerDate <= $thresholdDate2 && $registerDate >= $thresholdDate3) {
                        unset($aPlantype['3']);
                        unset($aPlantype['1']);
                        $plantype = '2';
                    } else {
                        unset($aPlantype['1']);
                        $plantype = '2';
                    }
                } elseif ($aCarPlan['prepolitype'] == 'odonly') {
                    if ($registerDate <= $thresholdDate1 && $registerDate >= $thresholdDate2) {
                        $msg = 'odonly';
                        unset($aPlantype['3']);
                        unset($aPlantype['2']);
                        $plantype = '1';
                    } else if ($registerDate <= $thresholdDate3) {
                        //   unset($aPlantype['3']);
                        //   unset($aPlantype['2']);
                        unset($aPlantype['1']);
                        $plantype = '2';
                    } elseif ($registerDate <= $thresholdDate2 && $registerDate >= $thresholdDate3) {
                        unset($aPlantype['3']);
                        unset($aPlantype['1']);
                        $plantype = '2';
                    } else {
                        unset($aPlantype['3']);
                        unset($aPlantype['2']);
                        unset($aPlantype['1']);
                        $sMessage = '';
                    }
                } elseif ($aCarPlan['prepolitype'] == 'comprehensive') {
                    $msg = 'comprehensive';
                    if ($registerDate <= $thresholdDate3) {
                        // unset($aPlantype['3']);
                        // unset($aPlantype['2']);
                        unset($aPlantype['1']);
                        $plantype = '2';
                    } else {
                        unset($aPlantype['3']);
                        unset($aPlantype['2']);
                        unset($aPlantype['1']);
                        $sMessage = '';
                    }
                } elseif ($aCarPlan['prepolitype'] == 'tponly') {
                    $msg = 'tponly';
                    if ($registerDate <= $thresholdDate3) {
                        // unset($aPlantype['3']);
                        // unset($aPlantype['2']);
                        unset($aPlantype['1']);
                        $plantype = '2';
                    } else {
                        unset($aPlantype['3']);
                        unset($aPlantype['2']);
                        unset($aPlantype['1']);
                        $sMessage = '';
                    }
                }
            } else {
                $msg = 'else';
                unset($aPlantype['3']);
                unset($aPlantype['1']);
                $plantype = '2';
            }
            $aResult2 = $dataModelQuery->first();
            $aCarAddon = is_string($aResult2->caraddon)
                ? json_decode($aResult2->caraddon, true)
                : (array) $aResult2->caraddon;
            $aGetAddons = !empty($aCarAddon['tpselectedaddon'])
                ? $aCarAddon['tpselectedaddon']
                : (
                    !empty($aCarAddon['selectedaddon'])
                        ? $aCarAddon['selectedaddon']
                        : (
                            !empty($aCarAddon['odselectedaddon'])
                                ? $aCarAddon['odselectedaddon']
                                : []
                        )
                );

            if (!is_array($aGetAddons)) {
                $aGetAddons = json_decode($aGetAddons, true) ?? [];
            }

            $oResponse = $dataModelQuery->first();
            $aDecodedata = empty($oResponse->knowcar_reg_details) ? [] : json_decode($oResponse->knowcar_reg_details, true);
            $aNewcardata = empty($oResponse->newcar_reg_details) ? [] : json_decode($oResponse->newcar_reg_details, true);
            $policyexp = '';
            if (GetCache($cachemotortype) == 'knowcar') {
                if ($aDecodedata['prepolitype'] == 'comprehensive') {
                    $compdate = $aDecodedata['comptodate'];
                    $prepolcydate = Carbon::createFromFormat('d-m-Y', $compdate);
                } else if ($aDecodedata['prepolitype'] == 'tponly') {
                    $tpdate = $aDecodedata['tptodate'];
                    $prepolcydate = Carbon::createFromFormat('d-m-Y', $tpdate);
                } else if ($aDecodedata['prepolitype'] == 'odonly') {
                    $odtodate = $aDecodedata['odtodate'];
                    $prepolcydate = Carbon::createFromFormat('d-m-Y', $odtodate);
                } else if ($aDecodedata['prepolitype'] == 'bundled') {
                    $regdate = $aDecodedata['bdtodate'];
                    $prepolcydate = Carbon::createFromFormat('d-m-Y', $regdate);
                }
                $today = now()->startOfDay();
                if ($prepolcydate >= $today) {
                    $policyexp = 'Not Expired';
                } else {
                    $policyexp = 'Expired';
                }
                $cachepolicyExpiry = 'cache_policyExpiry_' . $userId;
                SetCache($cachepolicyExpiry, $policyexp);
            }

            if (GetCache($cachemotortype) == 'knowcar') {
                // return response()->json(["model"=>$aDecodedata['model']]);
                $getdata = getRtocityApi($request, null);
                $rtocity = !empty($getdata->RTOCITY) ? $getdata->RTOCITY : $getdata->RTONAME;
                $under = $aDecodedata['under'];
                $model = Shriram_Vehicle_Master::where('id', $aDecodedata['model'])->first();
            } else {
                $under = $aNewcardata['under'];
                $rtocity = explode('(', $oResponse->rtocode)[0];
                $model = Shriram_Vehicle_Master::where('id', $aNewcardata['model'])->first();
            }
            $VehicleDetails = '';

            if (!empty(GetCache($cachemotortype)) && GetCache($cachemotortype) == 'knowcar') {
                $VehicleDetails = [
                    'city' => $rtocity,
                    'date' => $aDecodedata['carregdate'] ?? date('d-m-Y'),
                    'regnumber' => $aResult2->carnumber ?? '',
                    'brand' => $aDecodedata['brand'] ?? '',
                    'model' => $model['MODEL_DESCRIPTION'] ?? '',
                    'regyear' => $aDecodedata['brandyear'] ?? '',
                    'variant' => $model['MODEL_DESCRIPTION'] ?? '',
                    'policy_expiry' => $policyexp ?? ''
                ];
            }

            if (GetCache($cachemotortype) == 'newcar') {
                $VehicleDetails = [
                    'city' => $rtocity,
                    'brand' => $aNewcardata['newcarmanu'] ?? '',
                    'model' => $model['MODEL_DESCRIPTION'] ?? '',
                    'variant' => $model['MODEL_DESCRIPTION'] ?? '',
                    'regyear' => '',
                    'policy_expiry' => ''
                ];
            }

            $aVendor = MasterVendor::where('is_car', '1')
                ->where('isActive', '1')
                ->select('vid', 'type', 'vendorname', 'productname', 'logo', 'isActive')
                ->get()
                ->toArray();
            $data = [
                'status' => count($aPlantype) == 0 ? '0' : '1',
                'message' => $sMessage,
                'plantype' => $aPlantype,
                'selectedplantype' => $aResult2->car_plan_type ?? '3',
                'addons' => $aResult,
                'tpaddons' => $aResulttp,
                'odaddons' => $aResultod,
                'discount' => $aDiscount,
                'pacover' => $aResult2->pacover ?? '1',
                'selectedaddons' => $aGetAddons,
                'vehicledetails' => $VehicleDetails,
                'under' => $under ?? '',
                // 'carvendorlist' => $aIsCar,
                'vendor' => $aVendor,
                'accessories' => $accessories,
                'msg' => $msg,
                'regdate' => $registerDate,
                'modedl' => $model
            ];

            $cacheaVehicleDetails = 'cache_aVehicleDetails_' . $userId;
            SetCache($cacheaVehicleDetails, $VehicleDetails);

            Cache::forget('user_' . $userId . '_carquote_key');
            $dataModelQuery
                ->update([
                    'car_plan_type' => $plantype
                ]);
            SetCache('cache_plantype' . $userId, $plantype);
            return response()->json([
                'status' => true,
                'data' => $data,
                'cache' => GetCache($cachemotortype)
            ]);

            // return view('front.motor.car.planfilters.index', compact('data'));
        } catch (\Exception $e) {
            \Log::info($e->getMessage() . ' errorcode:carPlan');
            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function addAddon(Request $request)
    {
        $userId = $request->userid;
        // return[
        //    'asdf' => $request->data['addon115Amount']
        // ];
        try {
            // dd($request->input());
            $aResult = DataModel::where('userid', $userId)->first();
            $aResult->caraddon = json_encode($request->data);
            $aResult->caraddonvalue = json_encode($request->data['addon115Amount']);
            $aResult->save();
            return response()->json([
                'status' => true,
            ]);
        } catch (\Exception $e) {
            // echo $e->getMessage() . " errorcode:addAddon";
            \Log::info($e->getMessage() . ' errorcode:addAddon');
            return response()->json([
                'status' => false,
            ]);
        }
    }

    public function addAccessories(Request $request)
    {
        $userId = $request->userid;
        try {
            $oData = DataModel::where('userid', $userId)->first();
            if ($oData) {
                $oData->accessories = json_encode($request->data);
                $oData->updated_at = now();
                $oData->save();
                return response()->json([
                    'status' => true
                ]);
            }
        } catch (\Exception $e) {
            // echo $e->getMessage() . " errorcode:addAccessories";
            \Log::info($e->getMessage() . ' errorcode:addAccessories');
            return response()->json([
                'status' => $e->getMessage()
            ]);
        }
        return response()->json([
            'status' => false
        ]);
    }

    public function updateIdv(Request $request)
    {
        $userId = $request->userid;
        $idv = str_replace(',', '', $request->data);
        $cachecaridv = 'cache_' . $userId . '_caridv';
        // dd($idv);
        SetCache($cachecaridv, $idv);
        // Cache::put("user_" . $userId . "_caridv", $idv, now()->addHours(2));
        return response()->json([
            'status' => '1'
        ]);
    }

    public function changePlanType(Request $request)
    {
        // comprehensive=1
        // owndamage=2
        // tponly=3
        // dd($request);
        $aData = $request->data;
        $userId = $request->userid;
        // dd($aData);
        try {
            $oExists = DataModel::where('userid', $userId)->first();
            if ($oExists) {
                if (!empty($aData['planetype'])) {
                    $oExists->car_plan_type = $aData['planetype'];
                    $oExists->caraddon = null;
                }
                if ($aData['pacover'] == '1') {
                    $oExists->pacover = $aData['pacover'];
                }
                $oExists->updated_at = now();
                $oExists->save();
                $plan = $oExists->car_plan_type;
                $cacheplantype = 'cache_plantype' . $userId;
                SetCache($cacheplantype, $plan);
                return response()->json([
                    'status' => true,
                ]);
            }
            return response()->json([
                'status' => false
            ]);
        } catch (\Exception $e) {
            echo $e->getMessage() . ' errorcode:changePlanType';
            \Log::info($e->getMessage() . ' errorcode:changePlanType');
        }
    }

    public function paCoverReason(Request $request)
    {
        try {
            $userId = $request->userid;
            $oUser = DataModel::where('userid', $userId)->first();
            // return[
            //         'status' => $request->data,
            //         'data' => $request->data[0]
            //     ];
            if ($oUser && isset($request->data[0])) {
                $firstItem = $request->data[0];
                $aData = $request->data;
                $oUser->pacover_reason = $firstItem;
                $oUser->pacover = $aData['pacover'] ?? '0';
                $oUser->updated_at = now();
                $oUser->save();
                return response()->json([
                    'status' => true
                ]);
                // dd($oUser);
            }
        } catch (\Exception $e) {
            echo $e->getMessage() . ' errorcode:paCoverReason';
            \Log::info($e->getMessage() . ' errorcode:paCoverReason');
        }
        return response()->json([
            'status' => false
        ]);
    }
}
