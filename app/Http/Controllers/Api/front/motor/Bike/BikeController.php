<?php
namespace App\Http\Controllers\Api\front\motor\Bike;
use App\Http\Controllers\Api\front\motor\Bike\PlanController;
use App\Http\Controllers\Api\SystemController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Api\front\motor\Vendor\shriram\Bike\ShriramBikeController;
use App\Http\Controllers\Api\front\motor\Bike\ValidationController;
use App\Models\Shriram\{Shriram_Pincode, Shriram_planCheckout, Shriram_RTO_Master, Shriram_Vehicle_Master};
use App\Models\{Master_Vehicle_Data as DataModel, User, MasterVendor, VendorMotor, MasterMotor};
use App\Services\Api\{ShriramService};
use Illuminate\Support\Facades\{Cache, DB};
use DateTime;

class BikeController
{
    public function KnowBikeDetails(Request $request, $param = null)
    {
        $userId = $request->userid;
        saveHistory([
            'userid' => $userId,
            'vid' => '',
            'plan' => 'motor',
            'type' => 'bike',
            'type2' => "",
            'created_at' => now(),
            'updated_at' => now()
        ]);
        // dd($request);
        try {
            $method = $request->method();
            $aRequest = $request->data;

            $cachemotortype = 'cache_motortype_' . $userId;
            SetCache($cachemotortype, 'knowbike');

            $data = DataModel::where('userid', $userId)->first();
            if ($method == 'GET') {
                return response()->json([
                    'status' => true,
                    'data' => ["bikeregnumber" => $data->bikenumber ?? ''],
                    'cache' => GetCache($cachemotortype)
                ]);
            }
            $request = json_decode(json: json_encode($request->data));
            if (!empty($data)) {
                $data->bikenumber = $request->bikeregnumber;
                $data->updated_at = now();
                $response = $data->save();
            } else {
                $obj = new DataModel();
                $obj->userid = $userId;
                $obj->bikenumber = $request->bikeregnumber;
                $obj->created_at = now();
                $obj->updated_at = now();
                $response = $obj->save();
            }
            if ($response) {
                return response()->json([
                    'status' => true,
                    'message' => "Saved",
                    'cache' => GetCache($cachemotortype)
                ]);
            }
            return response()->json([
                'status' => false,
                'message' => "Data not saved or updated"
            ]);
        } catch (\Exception $e) {
            echo $e->getMessage() . " errorcode:KnowBikeDetails";
            \Log::info($e->getMessage() . " errorcode:KnowCarDetails");
        }
    }
    public function KnowBikesteptwo(Request $request, $param = null)
    {
        $userId = $request->userid;
        $aRequest = $request->data;
        $method = $request->method();

        saveHistory([
            'userid' => $userId,
            'vid' => '',
            'plan' => 'motor',
            'type' => 'bike',
            'type2' => "",
            'created_at' => now(),
            'updated_at' => now()
        ]);

        try {
            $cachemotortype = 'cache_motortype_' . $userId;
            SetCache($cachemotortype, 'knowbike');

            $data = DataModel::where('userid', $userId)->first();


            if ($method == 'GET') {
                $bikedata = $data->knowbike_reg_details ? json_decode($data->knowbike_reg_details, true) : [];
                $bikemodel = $bikedata['model'] ?? null;
                if ($bikemodel) {
                    $bikemodel = Shriram_Vehicle_Master::where('id', $bikemodel)->first(['MODEL_DESCRIPTION']);
                }
                return response()->json([
                    'status' => true,
                    'data' => $data->knowbike_reg_details ? json_decode($data->knowbike_reg_details, true) : [],
                    'model' => $bikemodel['MODEL_DESCRIPTION'] ?? null
                ]);
            }
            if ($data) {
                $regData = $data && $data->knowbike_reg_details ? json_decode($data->knowbike_reg_details, true) : [];
                $MergedData = array_replace($regData, $aRequest);
                $data->knowbike_reg_details = json_encode($MergedData);
                $data->updated_at = now();
                $sMsg = "Data updated";
                $bStatus = true;
            } else {
                $data = new DataModel();
                $data->knowbike_reg_details = json_encode($aRequest);
                $data->created_at = now();
                $data->updated_at = now();
                $sMsg = "Data saved";
                $bStatus = true;
            }


            $response = $data->save();
            if ($response) {
                $bStatus = true;
            } else {
                $bStatus = false;
                $sMsg = "Something is wrong while saving the data.";
            }
            return response()->json([
                'status' => $bStatus,
                'message' => $sMsg,

            ]);

        } catch (\Exception $e) {
            \Log::info($e->getMessage() . " errorcode:KnowBikeDetailstwo");
            $bStatus = false;
            return response()->json([
                'status' => $bStatus,
                'message' => $e->getMessage()
            ]);
        }

    }
    public function knowbikeStepthree(Request $request)
    {
        $userId = $request->userid;
        $aRequest = $request->data;
        $method = $request->method();

        if ($method == 'GET') {
            $data = DataModel::where('userid', $userId)->first(['knowbike_reg_details']);
            return response()->json([
                'status' => true,
                'data' => json_decode($data->knowbike_reg_details, true)
            ]);
        }
        $oResponse = DataModel::where('userid', $userId)->first();
        $bikedetails = json_decode($oResponse->knowbike_reg_details, true) ?? [];
        $mergeDetails = array_replace($bikedetails, $aRequest, );
        Cache::store('mysql_cache')->forget("user_" . $userId . "_bikeidv");
        Cache::store('mysql_cache')->forget("cache_" . $userId . "_bikeidv");


        setPaCover();
        try {
            if ($oResponse) {
                $oResponse->knowbike_reg_details = json_encode($mergeDetails);
                $oResponse->updated_at = now();
                $oResponse->save();

            }
            return response()->json([
                'status' => true,
            ]);
        } catch (\Exception $e) {
            echo $e->getMessage() . " errorcode:knowbikeStepthree";
            \Log::info($e->getMessage() . " errorcode:knowbikeStepthree");
        }

    }
    public function NewbikeDetails(Request $request)
    {
        $method = $request->method();
        $userId = $request->userid;
        $aRequest = $request->data;
        $cachemotortype = 'cache_motortype_' . $userId;
        SetCache($cachemotortype, 'newbike');
        try {

            if ($method == 'GET') {
                $data = DataModel::where('userid', $userId)->first();
                return response()->json([
                    'status' => true,
                    'data' => $data->rtocode
                ]);
            }

            $oData = DataModel::where('userid', $userId)->first();

            if (!empty($oData)) {
                $oData->rtocode = $aRequest['newbikecity'];
                if (GetCache($cachemotortype) == "newbike") {
                    $oData->bike_plan_type = '2';
                }
                $oData->updated_at = now();
                $sMsg = "Data saved";
                $bStatus = true;
            } else {
                $oData = new DataModel();
                $oData->userid = $userId;
                $oData->rtocode = $aRequest['newbikecity'];
                if (GetCache($cachemotortype) == "newbike") {
                    $oData->bike_plan_type = '2';
                }
                $oData->created_at = now();
                $oData->updated_at = now();
                $sMsg = "Data saved";
                $bStatus = true;
            }

            $response = $oData->save();

            if ($response) {
                return response()->json([
                    'message' => $sMsg,
                    'status' => $bStatus,
                    //'data' => $oData
                ]);
            } else {
                return response()->json([
                    'message' => 'Failed to save data',
                    'status' => false
                ]);
            }

        } catch (\Exception $e) {
            \Log::info($e->getMessage() . " errorcode:NewBikeDetails");

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);

        }
    }
    public function newbikeSteptwo(Request $request)
    {
        $userId = $request->userid;
        $method = $request->method();
        // $cacheuseridv = 'user_' . $userId . '_bikeidv';
        // DeleteCache($cacheuseridv);
        Cache::store('mysql_cache')->forget("user_" . $userId . "_bikeidv");
        Cache::store('mysql_cache')->forget("cache_" . $userId . "_bikeidv");
        // $cachebikeidv = "cache_" . $userId . "_bikeidv";
        // DeleteCache($cachebikeidv);
        setPaCover();
        try {
            $aRequest = $request->data;
            $oResponse = DataModel::where('userid', $userId)->first();

            if ($method == 'GET') {
                $bikedata = $oResponse->newbike_reg_details ? json_decode($oResponse->newbike_reg_details, true) : [];
                $bikemodel = $bikedata['model'] ?? null;
                if ($bikemodel) {
                    $bikemodel = Shriram_Vehicle_Master::where('id', $bikemodel)->first(['MODEL_DESCRIPTION']);
                }
                return response()->json([
                    'status' => true,
                    'data' => $oResponse->newbike_reg_details ? json_decode($oResponse->newbike_reg_details, true) : [],
                    'model' => $bikemodel['MODEL_DESCRIPTION'] ?? null
                ]);
            }

            if ($oResponse) {
                $oResponse->newbike_reg_details = json_encode($aRequest);
                $oResponse->updated_at = now();
                $oResponse->save();
            }
            return response()->json([
                'status' => true
            ]);

        } catch (\Exception $e) {
            \Log::info($e->getMessage() . " errorcode:newbikeSteptwo");
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }

    }
    public function bikePlan(Request $request)
    {
        try {
            $userId = $request->userid;
            $cacheaVehicleDetails = 'cache_aVehicleDetails_' . $userId;
            DeleteCache($cacheaVehicleDetails);
            $rtocity = "";
            $aNewBike = null;
            $dataModelQuery = DataModel::where('userid', $userId);
            $aResult = MasterMotor::whereNotNull('addon')->where('is_bike', '1')->where('is_comp', 1)->pluck('addon', 'id')->toArray();
            $aResulttp = MasterMotor::whereNotNull('addon')->where('is_bike', '1')->where('is_tp', 1)->pluck('addon', 'id')->toArray();
            $aResultod = MasterMotor::whereNotNull('addon')->where('is_bike', '1')->where('is_od', 1)->pluck('addon', 'id')->toArray();
            // return response()->json([
            //     'adsa'=>$aResult
            // ]);
            // dd($aResult);
            $aResult2 = $dataModelQuery->first();
            $aIsBike = MasterVendor::where('is_bike', '1')->get()->toArray();
            $aPlantype = getconstant('MOTOR.PLANTYPEQUOTE');
            $sMessage = "";
            $pacover = "";
            $cachemotortype = 'cache_motortype_' . $userId;
            if (GetCache($cachemotortype) == 'knowbike') {
                $aBikePlan = json_decode($aResult2->knowbike_reg_details, true);
                $dataModelQuery
                    ->update([
                        'bike_plan_type' => $aBikePlan['prepolitype']
                    ]);
                $registerDate = strtotime($aBikePlan['bikeregdate']);
                $registerDate = DateTime::createFromFormat('d-m-Y', $aBikePlan['bikeregdate']);
                $now = new DateTime();
                $thresholdDate1 = clone $now;
                $thresholdDate2 = clone $now;
                $thresholdDate3 = clone $now;
                $thresholdDate2->modify('-4 years -90 days');
                $thresholdDate3->modify('-5 years');
                $thresholdDate1->modify('-1 years');

                if ($aBikePlan['prepolitype'] == 'bundled') {
                    if ($registerDate <= $thresholdDate1 && $registerDate >= $thresholdDate2) {
                        unset($aPlantype['3']);
                        unset($aPlantype['2']);
                    } elseif ($registerDate >= $thresholdDate1) {
                        unset($aPlantype['3']);
                        unset($aPlantype['2']);
                    } elseif ($registerDate <= $thresholdDate2 && $registerDate >= $thresholdDate3) {
                        unset($aPlantype['3']);
                        unset($aPlantype['1']);
                    } else {
                        unset($aPlantype['1']);
                    }
                } elseif ($aBikePlan['prepolitype'] == 'odonly') {
                    if ($registerDate <= $thresholdDate1 && $registerDate >= $thresholdDate2) {
                        unset($aPlantype['3']);
                        unset($aPlantype['2']);
                    } else if ($registerDate <= $thresholdDate3) {
                        unset($aPlantype['1']);
                    } elseif ($registerDate <= $thresholdDate2 && $registerDate >= $thresholdDate3) {
                        unset($aPlantype['3']);
                        unset($aPlantype['1']);
                    } else {
                        unset($aPlantype['3']);
                        unset($aPlantype['2']);
                        unset($aPlantype['1']);
                        $sMessage = "";
                    }

                } elseif ($aBikePlan['prepolitype'] == 'comprehensive') {
                    if ($registerDate <= $thresholdDate3) {
                        unset($aPlantype['1']);
                    } else {
                        unset($aPlantype['3']);
                        unset($aPlantype['2']);
                        unset($aPlantype['1']);
                        $sMessage = "";
                    }

                } elseif ($aBikePlan['prepolitype'] == 'tponly') {
                    if ($registerDate <= $thresholdDate3) {
                        unset($aPlantype['1']);
                    } else {
                        unset($aPlantype['3']);
                        unset($aPlantype['2']);
                        unset($aPlantype['1']);
                        $sMessage = "";
                    }
                }
            } else {
                unset($aPlantype['3']);
                unset($aPlantype['1']);
            }
            $aResult2 = $dataModelQuery->first();
            $aBikeAddon = is_string($aResult2->bikeaddon)
                ? json_decode($aResult2->bikeaddon, true)
                : (array) $aResult2->bikeaddon;
            $aGetAddons = !empty($aBikeAddon['tpselectedaddon'])
                ? $aBikeAddon['tpselectedaddon']
                : (
                    !empty($aBikeAddon['selectedaddon'])
                    ? $aBikeAddon['selectedaddon']
                    : (
                        !empty($aBikeAddon['odselectedaddon'])
                        ? $aBikeAddon['odselectedaddon']
                        : []
                    )
                );

            if (!is_array($aGetAddons)) {
                $aGetAddons = json_decode($aGetAddons, true) ?? [];
            }
            if (!array_key_exists($aResult2->bike_plan_type, $aPlantype)) {
                $dataModelQuery->update([
                    'bike_plan_type' => array_key_first($aPlantype)
                ]);
            }
            $oResponse = $dataModelQuery->first();
            $aDecodedata = empty($oResponse->knowbike_reg_details) ? [] : json_decode($oResponse->knowbike_reg_details, true);
            $aNewbikedata = empty($oResponse->newbike_reg_details) ? [] : json_decode($oResponse->newbike_reg_details, true);
            $policyexp = "";
            $prepolcydate = "";
            $cachemotortype = 'cache_motortype_' . $userId;

            if (GetCache($cachemotortype) == "knowbike") {
                if ($aDecodedata['prepolitype'] == "comprehensive") {
                    $compdate = $aDecodedata['comptodate'];
                    $prepolcydate = Carbon::createFromFormat('d-m-Y', $compdate);
                } else if ($aDecodedata['prepolitype'] == "tponly") {
                    $tpdate = $aDecodedata['tptodate'];
                    $prepolcydate = Carbon::createFromFormat('d-m-Y', $tpdate);
                } else if ($aDecodedata['prepolitype'] == "odonly") {
                    $odtodate = $aDecodedata['odtodate'];
                    $prepolcydate = Carbon::createFromFormat('d-m-Y', $odtodate);
                } else if ($aDecodedata['prepolitype'] == "bundled") {
                    $regdate = $aDecodedata['bdtodate'];
                    $prepolcydate = Carbon::createFromFormat('d-m-Y', $regdate);
                }
                $today = now()->startOfDay();
                if ($prepolcydate >= $today) {
                    $policyexp = "Not Expired";
                } else {
                    $policyexp = "Expired";
                }
                $cachebikePolicyexp = 'cache_bikepolicyexp_' . $userId;
                SetCache($cachebikePolicyexp, $policyexp);
            }

            if (GetCache($cachemotortype) != "newbike") {
                $getdata = getBikeRtocityApi($request, null);
                $rtocity = !empty($getdata->RTOCITY) ? $getdata->RTOCITY : $getdata->RTONAME;
                $under = $aDecodedata['under'];
                $model = Shriram_Vehicle_Master::where('id', $aDecodedata['model'])->first();
            } else {
                $under = $aNewbikedata['under'];
                $rtocity = explode('(', $oResponse->rtocode)[0];
                $model = Shriram_Vehicle_Master::where('id', $aNewbikedata['model'])->first();
            }
            $VehicleDetails = "";
            if (GetCache($cachemotortype) == "knowbike") {
                $VehicleDetails = [
                    'city' => $rtocity,
                    'date' => $aDecodedata['bikeregdate'] ?? date('d-m-Y'),
                    'regnumber' => $aResult2->bikenumber,
                    'brand' => $aDecodedata['brand'],
                    'model' => $model['MODEL_DESCRIPTION'] ?? '',
                    'regyear' => $aDecodedata['brandyear'],
                    'variant' => $model['MODEL_DESCRIPTION'],
                    'policy_expiry' => $policyexp
                ];
            }
            if (GetCache($cachemotortype) == "newbike") {
                $VehicleDetails = [
                    'city' => $rtocity,
                    'brand' => $aNewbikedata['brand'] ?? '',
                    'model' => $model['MODEL_DESCRIPTION'] ?? '',
                    'variant' => $model['MODEL_DESCRIPTION'] ?? '',
                    'regyear' => '',
                    'policy_expiry' => ''
                ];
            }
            $aVendor = MasterVendor::where('is_bike', '1')
                ->where('isActive', '1')->select('vid', 'type', 'vendorname', 'productname', 'logo', 'isActive')
                ->get()->toArray();

            $data = [
                'status' => count($aPlantype) == 0 ? '0' : '1',
                'message' => $sMessage,
                'plantype' => $aPlantype,
                'selectedplantype' => $aResult2->bike_plan_type ?? '3',
                'addons' => $aResult,
                'tpaddons' => $aResulttp,
                'odaddons' => $aResultod,
                'pacover' => $aResult2->pacover ?? '1',
                'selectedaddons' => $aGetAddons,
                'vehicledetails' => $VehicleDetails,
                'under' => $under ?? '',
                'bikevendorlist' => $aIsBike,
                'vendor' => $aVendor
            ];
            $cacheaVehicleDetails = 'cache_aVehicleDetails_' . $userId;
            SetCache($cacheaVehicleDetails, $VehicleDetails);
            $cacheuserbikequotekey = 'user_' . $userId . '_bikequote_key';
            DeleteCache($cacheuserbikequotekey);

            return response()->json([
                'status' => true,
                'data' => $data,
                'cache' => GetCache($cachemotortype)
            ]);
        } catch (\Exception $e) {
            //echo $e->getMessage() . " errorcode:bikePlan";
            \Log::info($e->getMessage() . " errorcode:bikePlan");
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function addAddon(Request $request)
    {
        try {
            $userId = $request->userid;
            $aResult = DataModel::where('userid', $userId)->first();
            $aResult->bikeaddon = json_encode($request->data);
            $aResult->bikeaddonvalue = json_encode($request->data['addon115Amount']);
            $aResult->save();
            return response()->json([
                'status' => '1'
            ]);
        } catch (\Exception $e) {
            //echo $e->getMessage() . " errorcode:addAddon";
            \Log::info($e->getMessage() . " errorcode:addAddon");

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);

        }
    }

    public function updateIdv(Request $request)
    {
        $userId = $request->userid;
        $arequest = $request->data;
        $idv = str_replace(',', '', $arequest);
        $cachebikeidv = 'cache_' . $userId . '_bikeidv';
        SetCache($cachebikeidv, $idv);
        //Cache::store('mysql_cache')->put("user_" . $userId . "_bikeidv", $idv, now()->addHours(2));
        return response()->json([
            'status' => true,
            // 'cache' => GetCache("user_" . $request->userid . "_bikeidv") ?? null
        ]);
    }

    public function changePlanType(Request $request)
    {
        $aData = $request->data;
        $userId = $request->userid;
        try {
            $oExists = DataModel::where('userid', $userId)->first();
            if ($oExists) {
                if (!empty($aData['planetype'])) {
                    $oExists->bike_plan_type = $aData['planetype'];
                    $oExists->bikeaddon = null;
                }
                if ($aData['pacover'] == '1') {
                    $oExists->pacover = $aData['pacover'] ?? "1";
                }
                $oExists->updated_at = now();
                $oExists->save();
                $plan = $oExists->bike_plan_type;
                $cachebikeplantype = 'cache_bikeplantype' . $userId;
                SetCache($cachebikeplantype, $plan);
                return response()->json([
                    'status' => '1',
                ]);
            }
            return response()->json([
                'status' => '0'
            ]);
        } catch (\Exception $e) {
            //echo $e->getMessage() . " errorcode:changePlanType";
            \Log::info($e->getMessage() . " errorcode:changePlanType");
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function paCoverReason(Request $request)
    {
        $userId = $request->userid;
        $aData = $request->data;
        try {
            $oUser = DataModel::where('userid', $userId)->first();

            if ($oUser && isset($request->data[0])) {
                $firstItem = $request->data[0];
                $oUser->pacover_reason = $firstItem;
                $oUser->pacover = $aData['pacover'] ?? '0';
                $oUser->updated_at = now();
                $oUser->save();

                return response()->json([
                    'status' => true
                ]);
            }
        } catch (\Exception $e) {
            //echo $e->getMessage() . " errorcode:paCoverReason";
            \Log::info($e->getMessage() . " errorcode:paCoverReason");
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
