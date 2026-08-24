<?php

namespace App\Http\Controllers\Api\front\health;
use Illuminate\Http\Request;
use App\Models\{MasterVendor};

class FilterController
{
    public function planFilter(Request $request)
    {
        $userId = $request->userid;
        $request = json_decode(json_encode($request->data));
        $cachefilter = 'cache_filter_' . $userId;
        SetCache($cachefilter, '1');

        try {
            $coverage = trim(explode(" ", $request->coverage)[0]);
            $coverage = $coverage == 1 ? '100' : $coverage;

            $cachecoverage = 'cache_coverage_' . $userId;
            SetCache($cachecoverage, $coverage);
            $cachetenure = 'cache_tenure_' . $userId;
            $tenure = $request->tenure ?? GetCache($cachetenure);
            SetCache($cachetenure, $tenure);
            $cachehealthplan = 'cache_healthplan_' . $userId;
            $plantype = $request->plantype ?? GetCache($cachehealthplan);
            if ($plantype) {
                SetCache($cachehealthplan, $plantype);
            }


            return response()->json([
                'status' => true,
                'message' => 'Filter applied',
                'coverage' => GetCache($cachecoverage),
                'tenure' => GetCache($cachetenure),
                'plantype'=>$plantype??"",
                'id' => $userId
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage() . 'errorcode:planFilter_caresupereme'
            ]);
        }
    }
}

