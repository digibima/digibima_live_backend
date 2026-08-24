<?php

namespace App\Http\Controllers\Api\front\motor\Car;
use App\Http\Controllers\front\motor\Car\ValidationController;
use App\Http\Controllers\Api\front\motor\Car\UtilityController;
use App\Http\Controllers\Api\front\motor\Vendor\shriram\Car\ShriramCarController;
use App\Http\Controllers\Api\front\motor\Vendor\godigit\Car\GoDigitCarController;
use App\Models\Shriram\{Shriram_Pincode, Shriram_planCheckout, Shriram_RTO_Master, Shriram_Vehicle_Master};
use App\Models\{Master_Vehicle_Data as DataModel, User, MasterVendor, VendorMotor, MasterMotor};
use App\Services\Api\{ShriramService};
use Illuminate\Support\Facades\{Auth, Cache};
use Illuminate\Http\Request;
class PlanController
{
    //-------------------------------------get live data from api----------------------------------
    public function CarQuoteGenerateStream(Request $request)
    {
        $value = $request->data;
        $aCache = [];
        try {
            //-----------------------------------SHRIRAM---------------------------------------------

            if ($value['vid'] == getconstant("MOTOR.SHRIRAM.KEY")) {
                $oObj = new ShriramCarController();
                $response = UtilityController::getObjectData($request, $oObj, $value, $aCache);
                //dd($response);
                //  return [
                //  'data' => $response             
                // ]; 
                return response()->json($response);
            }
        } catch (\Exception $e) {
            \Log::info($e->getMessage() . " errorcode:CarQuoteGenerateStream");
            return [
                'status' => '0',
                'message' => $e->getMessage()
            ];
        }
    }
}
