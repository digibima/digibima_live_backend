<?php

namespace App\Http\Controllers\Api\front\motor\Bike;
use App\Http\Controllers\Api\front\motor\Bike\UtilityController;
use App\Http\Controllers\Api\front\motor\Vendor\shriram\Bike\ShriramBikeController;
use App\Http\Controllers\Api\front\motor\Vendor\godigit\Bike\GoDigitBikeController;
use App\Models\Shriram\{Shriram_Pincode, Shriram_planCheckout, Shriram_RTO_Master, Shriram_Vehicle_Master};
use App\Models\{Master_Vehicle_Data as DataModel, User, MasterVendor, VendorMotor, MasterMotor};
use App\Services\Api\{ShriramService};
use Illuminate\Support\Facades\{Auth, Cache};
use Illuminate\Http\Request;
class PlanController
{

    public function BikeQuoteGenerateStream(Request $request)
    {

        $value = $request->data;
        $aCache = [];

        //dd($value);
        try {
            //-----------------------------------SHRIRAM---------------------------------------------
            if ($value['vid'] == getconstant("MOTOR.SHRIRAM.KEY")) {

                $oObj = new ShriramBikeController();
                $response = UtilityController::getObjectData($request, $oObj, $value, $aCache);
                //dd($response);
                // return[
                //      "data"=>$response
                // ];
                return response()->json($response);
            }
           
            // if ($value['vid'] == getconstant("MOTOR.GODIGIT.KEY")) {
            //     $oObj = new GoDigitBikeController();
            //     $response = UtilityController::getObjectData($request, $oObj, $value, $aCache);
            //     return response()->json($response);
            // }
        } catch (\Exception $e) {
            \Log::info($e->getMessage() . " errorcode:CarQuoteGenerateStream");
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
