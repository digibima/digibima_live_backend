<?php


use App\Models\Shriram\{Shriram_RTO_Master};
use Illuminate\Support\Facades\{DB, Auth, Cache};
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\{User, MasterHealthAddon, MasterVendor, Master_Vehicle_Data as DataModel, SearchHistory, Vehicle_Info};
function getMaxId($model)
{
    $ID = User::max('id');
    if ($ID) {
        $genID = $ID + 1;
        return $genID;
    } else {
        $ID = 15;
        return $ID;
    }
}

function addYear($noy)
{
    $currentDate = new DateTime();
    $currentDate->modify('+' . $noy . ' year');
    $currentDate->modify('-1 day');
    return $currentDate->format('d-m-Y');
}

function getconstant($param = " ")
{
    $result = config('constant.' . $param);
    if ($result) {
        return $result;
    } else {
        return $result = [];
    }
}

function getconstantarray($param)
{
    $result = config('constant.' . $param);
    return $result;
}

function generateRandomString($str)
{
    return base64_encode(base64_encode($str . date('Ymd')));
}

function getServerIPv4()
{
    $serverIP = gethostbyname(gethostname());
    return $serverIP;
}

//json_encode
function jenc($data)
{
    return json_encode($data);
}

//json_decode
function jdec($data)
{
    return json_decode($data, true);
}
function encodeMobile($mobile)
{
    return base64_encode(base64_encode($mobile));
}

function getBothPincode()
{
    $user = User::find(Auth::id());
    $commaddress = json_decode($user['communication']);
    $oldpincode = $commaddress->oldpincode;
    $compincode = $commaddress->compincode;
    //$pincode = $user['pincode'];
    $status = "";
    if (($oldpincode == $compincode)) {
        $status = "0";
    } else {
        $status = "1";
    }
    return ['status' => $status, 'ppincode' => $oldpincode, 'cpincode' => $compincode];
}
// function getHealthAddons($vid)
// {
//     $aDBMaster = MasterVendor::where('vid', $vid)->get(['healthaddons']);
//     $aDBMaster = json_decode($aDBMaster[0]->healthaddons, true);
//     $addonIds = array_keys($aDBMaster);
//     $aAddons = MasterHealthAddon::whereIn('key', $addonIds)->pluck('addon', 'key')->toArray();
//     return ['list' => $aAddons ?? [], 'code' => $aDBMaster ?? [], 'keys' => $addonIds];
// }
function getHealthAddons($vid)
{
    $aDBMaster = MasterVendor::where('vid', $vid)->get(['healthaddons']);

    if ($aDBMaster->isEmpty() || empty($aDBMaster[0]->healthaddons)) {
        return ['list' => [], 'code' => [], 'keys' => []];
    }
    $decodedAddons = json_decode($aDBMaster[0]->healthaddons, true);
    if (!is_array($decodedAddons)) {
        return ['list' => [], 'code' => [], 'keys' => []];
    }
    $addonIds = array_keys($decodedAddons);
    $aAddons = MasterHealthAddon::whereIn('key', $addonIds)->pluck('addon', 'key')->toArray();
    return ['list' => $aAddons, 'code' => $decodedAddons, 'keys' => $addonIds];
}


function getRtocity($regno = null)
{
    $aData = DataModel::where('userid', Auth::id())->first();
    $sRegNumber = $regno ?? $aData->carnumber;
    //dd($sRegNumber);
    $sRegno1 = substr($sRegNumber, 0, 2);
    $sRegNo2 = substr($sRegNumber, 2, 2);
    $sRtono = $sRegno1 . '-' . $sRegNo2;
    $rtocity = Shriram_RTO_Master::where('RTOCODE', $sRtono)->first();
    return $rtocity ?? null;
}

function getBikeRtocity($regno = null)
{
    $aData = DataModel::where('userid', Auth::id())->first();
    $sRegNumber = $regno ?? $aData->bikenumber;
    //dd($sRegNumber);
    $sRegno1 = substr($sRegNumber, 0, 2);
    $sRegNo2 = substr($sRegNumber, 2, 2);
    $sRtono = $sRegno1 . '-' . $sRegNo2;
    $rtocity = Shriram_RTO_Master::where('RTOCODE', $sRtono)->first();
    return $rtocity ?? null;
}

function setPaCover()
{
    $dataModelQuery = DataModel::where('userid', Auth::id());
    $dataModelQuery
        ->update([
            'pacover' => '1'
        ]);
}

function saveHistory($aData)
{
    SearchHistory::insert(['userid' => $aData['userid'], 'vid' => $aData['vid'], 'plan' => $aData['plan'], 'type' => $aData['type'], 'type2' => $aData['type2'], 'created_at' => now(), 'updated_at' => now()]);
    return true;
}

function SetCache($key, $value)
{

    // Cache::store('mysql_cache')->put($key, $value);
    Cache::put($key, $value, now()->addDays(1));
}
function GetCache($key)
{
    return Cache::get($key);
}

function DeleteCache($key)
{
    return Cache::forget($key);
}

function RedisSet($key, $value)
{
    SetCache($key, $value);
    // $ttlInSeconds = 86400;
    // Redis::setex($key, $ttlInSeconds, $value);
}
function RedisGet($key)
{
    // $data = Redis::get($key);
    $data = GetCache($key);

    if ($data) {
        return json_decode($data, true);
    }

    return null;
}

function RedisDel($key)
{
    Redis::del($key);
}

function RedisRange($key, $start = 0, $end = -1)
{
    return Redis::lrange($key, $start, $end);
}

function RedisPush($key, $value)
{
    if (is_array($value)) {
        foreach ($value as $v) {
            Redis::rpush($key, $v);
        }
    } else {
        Redis::rpush($key, $value);
    }
}
function SaveFile($response, $filename = 'response.txt')
{
    $path = public_path('files');

    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
    $file = $path . '/' . $filename;

    file_put_contents($file, $response);
}
function ErrMessage(\Exception $e, $customMsg = "")
{
    return [
        'status' => false,
        'message' => $customMsg,
        'error' => $e->getMessage(),
        'line' => $e->getLine(),
        'file' => last(explode('/', $e->getFile())),
    ];
}
function Err(\Exception $e, $customMsg = "")
{
    return ErrMessage($e, $customMsg);
}
function GetMinCoverage($coveragelist, $currentCache)
{
    sort($coveragelist);
    $smallerValue = null;
    $nVal = null;
    foreach ($coveragelist as $val) {
        $nVal = $val;
        if ($val > $currentCache) {
            break;
        }
        $smallerValue = $val;
    }
    if (!in_array($currentCache, $coveragelist)) {
        $smallerValue = $nVal;
    }
    return $smallerValue ?? $currentCache;
}

function getVcode($modelSearch, $vid, $pcode = "MOT-PRD-001", $model = "App\Models\Vehicle_Info")
{
    try {
        if (empty($modelSearch)) {
            return [
                'status' => false,
                'message' => 'Search term cannot be empty.',
                'data' => null
            ];
        }


        $cleanSearch = strtoupper(trim($modelSearch));
        $cleanSearch = preg_replace('/\s+/', ' ', $cleanSearch);


        $results = $model::where('MODEL_DESCRIPTION', 'LIKE', '%' . $cleanSearch . '%')
            ->where('VID', $vid)
            ->where('PRODUCT_CODE', $pcode)
            ->selectRaw('*, VCODE AS vcode')
            ->first();

        if ($results) {
            return [
                'status' => true,
                'message' => 'Vehicle found successfully.',
                'data' => $results
            ];
        }

        return [
            'status' => false,
            'message' => 'Vehicle not available.',
            'data' => [
                'vehicle' => $cleanSearch,
                'error' => 'No matching record found in master data.'
            ]
        ];

    } catch (\Exception $e) {
        return function_exists('ErrMessage') ? ErrMessage($e) : ['status' => false, 'message' => $e->getMessage()];
    }
}