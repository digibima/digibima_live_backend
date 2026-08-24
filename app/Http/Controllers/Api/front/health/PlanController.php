<?php
namespace App\Http\Controllers\Api\front\health;
use App\Http\Controllers\Api\front\health\vendor\ultimatecare\{planValidator, UltimateController};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Cache, Log};
use App\Http\Controllers\Api\front\health\vendor\caresupreme\CareSupremeController;
use App\Http\Controllers\Api\front\health\vendor\bajajmyhealth\BjajaMyHealthController;
use App\Http\Controllers\Api\front\health\vendor\bajajmyhealthcareplan9\BjajaMyHealthCarePlan9Controller;
use App\Http\Controllers\Api\front\health\vendor\adityabirla\AdityaBirlaController;
use App\Http\Controllers\Api\front\health\vendor\adityabirla\AdityaBirlaMaxPlusController;
use App\Http\Controllers\Api\front\health\vendor\tataaig\TataController;
use App\Http\Controllers\Api\front\health\UtilityController;

class PlanController
{
    public function HealthQuoteStream(Request $request)
    {
        $value = $request->data;
        $userId = $request->userid;
        $cachecoverage = 'cache_coverage_' . $userId;
        $coverage = GetCache($cachecoverage);
        $var = "Unlimited";
        try {
            if ($coverage != $var && $value['vid'] == getconstant("HEALTH.CARESUPREME.KEY")) {
                $oObj = new CareSupremeController();
                $response = UtilityController::getObjectData($request, $oObj, $value);
                return response()->json($response);
            }
            if ($value['vid'] == getconstant("HEALTH.ULTIMATECARE.KEY")) {
                $oObj = new UltimateController();

                $response = UtilityController::getObjectData($request, $oObj, $value);
                return response()->json($response);
            }
            if ($coverage != $var && $value['vid'] == getconstant("HEALTH.BAJAJ.KEY")) {
                $oObj = new BjajaMyHealthController();
                $response = UtilityController::getObjectData($request, $oObj, $value);
                //dd($response);
                return response()->json($response);
            }
            //   if ($coverage != $var && $value['vid'] == getconstant("HEALTH.BAJAJPLAN9.KEY")) {
            //     $oObj = new BjajaMyHealthCarePlan9Controller();
            //     $response = UtilityController::getObjectData($request, $oObj, $value);
            //     //dd($response);
            //     return response()->json($response);
            // }
            // if ($coverage != $var && $value['vid'] == getconstant("HEALTH.ADITYABIRLA.KEY")) {
            //     $oObj = new AdityaBirlaController();
            //     $response = UtilityController::getObjectData($request, $oObj, $value);
            //     return response()->json($response);
            // }
            // if ($coverage != $var && $value['vid'] == getconstant("HEALTH.ADITYABIRLA_Max_Plus.KEY")) {
            //     $oObj = new AdityaBirlaMaxPlusController();
            //     $response = UtilityController::getObjectData($request, $oObj, $value);
            //     return response()->json($response);
            // }
            // if ($value['vid'] == getconstant("HEALTH.TATAAIG.KEY")) {
            //     $oObj = new TataController();
            //     $response = UtilityController::getObjectData($request, $oObj, $value);
            //     return response()->json($response);
            // }

        } catch (\Exception $e) {
            \Log::info($e->getMessage() . "errorcode:HealthQuoteStream");
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

}