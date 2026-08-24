<?php

namespace App\Http\Controllers\Api\front\motor\Bike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Cache};

class UtilityController
{
    public static function getObjectData(Request $request, $oObj, $value, &$aCache)
    {
        $aGetCache = GetCache("user_" . $request->userid . "_bikequote_key") ?? [];
        if ($aGetCache) {
            $aCache = json_decode($aGetCache, true);
        }
        $aData = $oObj->getBikeQuote($request);
        // return[
        //              "avd"=>$aData
        //         ];
        $aData['data']['productName'] = $value['productname'];
        if ($aData['status'] == '1') {
            $aCache[$value['vid']] = $aData['cacheData'];
            SetCache("user_" . $request->userid . "_bikequote_key", json_encode($aCache));
            return [
                'status' => true,
                'userid' => $request->userid,
                'data' => $aData['data'],
                'cache' => GetCache("user_" . $request->userid . "_bikequote_key") ?? null
            ];
        }
        return [
            'status' => false
        ];
    }

    public static function getCacheObjectData(Request $request, $oObj, $value)
    {
        try {
            $aData = $oObj->getCacheBikeQuote($request, $value);

            //dd($aData);
            // return[
            //          "avd"=>$aData
            //     ];
            if ($aData['status'] == '1') {
                return [
                    'status' => '1',
                    'data' => $aData['data'],
                    'addonlist' => $aData['addonlist']
                ];
            }
        } catch (\Exception $e) {
            \Log::info($e->getMessage() . " errorcode:CacheCarQuoteGenerateStream");
            return [
                'status' => '0',
                'message' => $e->getMessage()
            ];
        }
    }


}