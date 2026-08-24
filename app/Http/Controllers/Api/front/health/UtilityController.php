<?php

namespace App\Http\Controllers\Api\front\health;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Cache};


class UtilityController
{
    public static function getObjectData(Request $request, $oObj, $value)
    {
        try {
            $jResponse = $oObj->validatePlanApi($request); 
            if (!$jResponse['status']) {
                $aData = $oObj->getQuotation($request);
                // return response()->json([
                //     'cvc'=>$aData
                // ]);
                if (!empty($aData['data']) && $aData['status'] == '1') {
                    $aData['data']['productname'] = $value['productname'] ?? '';
                    $aData['data']['logo'] = $value['logo'] ?? '';
                    if ($aData['status']) {
                        return ['status' => true, 'data' => $aData['data']];
                    }
                    return ['status' => false];
                }
            }
        } catch (\Exception $e) {
            \Log::info($e->getMessage() . "errorcode:HealthQuoteStream");
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()  . "errorcode:HealthQuoteStream"
            ]);
        }
        //return ['status' => false];
    }
}