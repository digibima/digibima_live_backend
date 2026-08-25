<?php

namespace App\Http\Controllers\Api\front\motor\Car;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Cache};

class UtilityController
{
    public static function getObjectData($request, $oObj, $value, &$aCache)
    {
        $aData = null;
        $aData = $oObj->getCarQuote($request);
        if ($aData instanceof JsonResponse) {
            $aData = $aData->getData(true);
        }
        //     return [
        //    'asds'=>$aData
        //  ];
        $aData['data']['productName'] = $value['productname'];
        if ((isset($aData['status']) && $aData['status'] == '1') || (isset($aData['status']) && $aData['status'] == true)) {
            $aCache[$value['vid']] = $aData['cacheData'];
            SetCache('user_' . $request->userid . '_carquote_key', json_encode($aCache));
            return [
                'status' => '1',
                'data' => $aData['data'],
                'userid' => $request->userid,
                'cache' => GetCache('user_' . $request->userid . '_carquote_key') ?? null
            ];
        }
        return ['status' => '0', 'value' => $value['vid'] ?? '', 'product' => $value['productname'] ?? '', 'data' => $aData ?? []];
    }

    // public static function getCacheObjectData(Request $request, $oObj, $value)
    // {
    //     try {
    //         $aData = $oObj->getCacheCarQuote($request, $value);
    //         //     return [
    //         //    'asds'=>$aData
    //         //  ];
    //         if ($aData['status'] == '1') {
    //             return [
    //                 'status' => '1',
    //                 'data' => $aData['data'],
    //                 'addonlist' => $aData['addonlist']
    //             ];
    //         }
    //         // return ['status' => '0'];
    //     } catch (\Exception $e) {
    //         \Log::info($e->getMessage() . ' errorcode:CacheCarQuoteGenerateStream');
    //         return [
    //             'status' => '0',
    //             'message' => $e->getMessage()
    //         ];
    //     }
    // }
}
