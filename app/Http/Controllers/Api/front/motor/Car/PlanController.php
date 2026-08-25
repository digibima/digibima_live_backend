<?php

namespace App\Http\Controllers\Api\front\motor\Car;

use App\Http\Controllers\Api\front\motor\Car\UtilityController;
use App\Http\Controllers\Api\front\motor\Vendor\godigit\Car\GoDigitCarController;
use App\Http\Controllers\Api\front\motor\Vendor\shriram\Car\ShriramCarController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Cache};

class PlanController
{
    // -------------------------------------get live data from api----------------------------------
    public function CarQuoteGenerateStream(Request $request)
    {
        $value = $request->data;
        $aCache = [];
        try {
            // -----------------------------------SHRIRAM---------------------------------------------

            if ($value['vid'] == getconstant('MOTOR.SHRIRAM.KEY')) {
                $oObj = new ShriramCarController();
                $response = UtilityController::getObjectData($request, $oObj, $value, $aCache);
                // dd($response);
                //  return [
                //  'data' => $response
                // ];
                return response()->json($response);
            }
            // ---------------------------GODIGIT----------------------------------------
            elseif ($value['vid'] == getconstant('MOTOR.GODIGIT.KEY')) {
                $oObj = new GoDigitCarController();
                $response = UtilityController::getObjectData($request, $oObj, $value, $aCache);
                // dd($response);
                //  return [
                //  'data' => $response
                // ];
                return response()->json($response);
            } else {
                return response()->json(['status' => false]);
            }
        } catch (\Exception $e) {
            \Log::info($e->getMessage() . ' errorcode:CarQuoteGenerateStream');
            return [
                'status' => '0',
                'message' => $e->getMessage()
            ];
        }
    }
}
