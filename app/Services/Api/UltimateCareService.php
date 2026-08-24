<?php
// ----------------------------------test---------------------------
namespace App\Services\Api;

use App\Models\Ultimatecare\UltimateInsurerMaster;
use App\Models\{User, MasterVendor, HealthJourney, MasterHealthAddon};
use App\Models\Insure;
use App\Models\JourneyUsers;
use App\Models\UltimateToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Log};
use Illuminate\Support\Facades\Session;
// use App\Http\Controllers\front\health\caresupreme\vendor\CareSupremeController;
use App\Http\Controllers\front\health\ultimatecare\vendor\UltimateController;
use Illuminate\Support\Facades\Crypt;
use DateTime;

class UltimateCareService
{
  private static $partnerId = '1001101';  // "872788";
  private static $appId = '1001101';  // "872788";
  private static $timestamp = '1736157011820';  // "1706613898969";

  private static $securityKey = 'KzZMOHQrUGYwZFJZZThtZkFWWEE1Zm9lT05ZWTZHV2x4bDM5T05nb09LL3di
WmlDckdmcFh1ZmJSNW5oVDhkdw==';  // "dkpBQ0Q3cGVGb1NXVnNsWW1EaERWb0ErQVFyTGFhSytNZCtrVzdzRGtrOW1DWktaTDdwWHRWdVZoYnpyV1JseA==";

  private static $aeskey = 'z5yK1lw7XYt6YKdP7Pne2Jw3zRkMAziH';
  private static $agentid = '20689274';  // "20008325";
  private static $iv = 'i0kbCAlFTlDXshYV';
  private static $productId = '10001135';
  private static $applicationCD = 'PARTNERAPP';
  private static $signature = 'g0erUaIKutEFSxQtm2zHjJo';

  private static $rsaPublicKey = <<<EOD
    -----BEGIN PUBLIC KEY-----
    MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuP89hk3z2LZRoMxqZK0k
    Pq1Zdf2xDbobT1EAY3V87I5qZ25Ix0sV3+O8qPbwO3K0Kn9lchxJ2OX0iJrrRXcA
    rB0E+3AVZLgGQqUp7rYbwck9pUvIE6uEfgkEfWvKDp9mX12V1T5JThFP6n1mhK9kq
    DUMMYKEYCONTINUEDLINESHERE1234567890ABCDEFGHIJKL=
    -----END PUBLIC KEY-----
    EOD;

  public static function managePartnerToken($userId, $data = null)
  {
    // dd($session);
    $bFlag = true;
    $data = json_decode($data, true);

    $status = $data['responseData']['status'];
    // return $status;
    if ($status == 'fail') {
      $error = $data['responseData']['message'];
      if (isset(explode(' ', strtolower($error))[0]) && explode(' ', strtolower($error))[0] == 'session' || explode(' ', strtolower($error))[0] == 'token') {
        $bFlag = false;
        $existingToken = UltimateToken::where('userid', $userId)->first();
        if ($existingToken) {
          $existingToken->token_number = null;
          $existingToken->sessionid = null;
          $existingToken->partner_token = null;
          $existingToken->updated_at = now();
          $existingToken->save();
        }
      }
    }
    return $bFlag;
  }

  public static function generatePartnerToken($userId, $id = '')
  {
    // return $userId;
    try {
      $sSessionID = ' ';
      $tokenId = ' ';
      // dd($sSessionID,$tokenId);
      // try {
      $tokendata = UltimateToken::where('userid', '=', $userId)->first();
      // if (
      //   $tokendata != null &&
      //   $tokendata->partner_token != '0' &&
      //   $tokendata->partner_token != null &&
      //   (
      //     $tokendata->token_number <= 23 ||
      //     $tokendata->updated_at->diffInMinutes(Carbon::now()) >= 14
      //   )
      // ) {
      if (
        $tokendata != null &&
        $tokendata->partner_token != '0' &&
        $tokendata->partner_token != null &&
        $tokendata->token_number < 23 &&  // must be less
        $tokendata->updated_at->diffInMinutes(Carbon::now()) >= 13
      ) {
        // dd($aToken);
        if ($tokendata) {
          $tokendata->token_number = ($tokendata->token_number) + 1;
          // $tokendata->updated_at = now();
          $tokendata->save();
        }
        // dd($tokendata);
        $sSessionID = json_decode($tokendata->sessionid);
        $aToken = json_decode($tokendata->partner_token);
        $tokenKey = $aToken[$tokendata->token_number]->tokenKey;
        $tokenValue = $aToken[$tokendata->token_number]->tokenValue;
        $tokenId = self::generateTokenID($tokenKey, $tokenValue);
      } else {
        $curl = curl_init();
        curl_setopt_array($curl, array(
          // CURLOPT_URL => 'https://api.religarehealthinsurance.com/relinterfacerestful/religare/secure/restful/generatePartnerToken',
          CURLOPT_URL => 'https://api.careinsurance.com/relinterfacerestful/religare/secure/restful/generatePartnerToken',
          // https://api.careinsurance.com/relinterfacerestful/religare/secure/restful/generatePartnerToken //k
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => '{
            "partnerTokenGeneratorInputIO": {
                "partnerId"     : ' . self::$partnerId . ',
                "securityKey"   : "dkpBQ0Q3cGVGb1NXVnNsWW1EaERWb0ErQVFyTGFhSytNZCtrVzdzRGtrOW1DWktaTDdwWHRWdVZoYnpyV1JseA=="
            }
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'appId: ' . self::$appId,
            'signature: ' . self::$signature,
            'timestamp:' . self::$timestamp,
            'applicationCD: ' . self::$applicationCD
          ),
        ));
        // dd($curl);
        $response = curl_exec($curl);
        // return $response;
        dd($response);
        // dd($response);
        // Log::info(['Response' => $response]);
        curl_close($curl);
        if ($response == false) {
          return false;
        }
        $aToken = json_decode($response);
        // dd($aToken);
        $existingToken = UltimateToken::where('userid', $userId)->first();
        if ($existingToken) {
          $existingToken->token_number = 1;
          $existingToken->sessionid = json_encode($aToken->partnerTokenGeneratorInputIO->sessionId);
          $existingToken->partner_token = json_encode($aToken->partnerTokenGeneratorInputIO->listOfToken);
          // $existingToken->updated_at = now();
          $existingToken->save();
        } else {
          $obj = new UltimateToken();
          $obj->userid = $userId;
          $obj->token_number = 1;
          $obj->sessionid = json_encode($aToken->partnerTokenGeneratorInputIO->sessionId);
          $obj->partner_token = json_encode($aToken->partnerTokenGeneratorInputIO->listOfToken);
          $obj->created_at = now();
          $obj->updated_at = now();
          $obj->save();
        }
        // $aToken = json_decode($response);
        $sSessionID = $aToken->partnerTokenGeneratorInputIO->sessionId;
        $gentokens = $aToken->partnerTokenGeneratorInputIO->listOfToken;
        $tokenKey = $gentokens[0]->tokenKey;
        $tokenValue = $gentokens[0]->tokenValue;
        $tokenId = self::generateTokenID($tokenKey, $tokenValue);
      }
      // } catch (\Exception $e) {
      //   echo '' . $e->getMessage();
      // }
      return json_encode(['sSessionID' => $sSessionID, 'tokenid' => $tokenId]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => true,
        'error' => $e->getMessage(),
      ]);
    }
  }

  public static function generateTokenID($tokenKey, $tokenValue)
  {
    $aesKey = self::$aeskey;
    $iv = self::$iv;
    $data = $tokenKey . '|' . $tokenValue;
    $encrypted = openssl_encrypt(
      $data,
      'aes-256-cbc',
      $aesKey,
      OPENSSL_RAW_DATA,
      $iv
    );
    return base64_encode(base64_encode($encrypted));
  }

  public static function verifyPAN($panid, $dob, $sessionid, $tokenid)
  {
    $curl = curl_init();

    // JSON payload
    try {
      $payload = json_encode([
        'getCkycEkycInputIO' => [
          'panNum' => $panid,
          'document_type' => 'PAN',
          'id_number' => '',
          'consent_purpose' => 'this is a consent purpose string pass anything',
          'dob' => $dob
        ]
      ]);

      curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.careinsurance.com/relinterfacerestful/religare/secure/restful/ckycDownload',
        // CURLOPT_URL => 'https://api.religarehealthinsurance.com/relinterfacerestful/religare/secure/restful/ckycDownload',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
          'appId: ' . self::$appId,
          'signature:  ' . self::$signature,
          'timestamp: ' . self::$timestamp,
          'sessionId: ' . $sessionid,
          'tokenId: ' . $tokenid,
          'applicationCD: ' . self::$applicationCD,
          'Content-Type: application/json'
        ],
      ]);

      $response = curl_exec($curl);
      Log::info(['Response' => $response]);
      curl_close($curl);
      // dd(json_decode($response));
      return $response;
    } catch (\Exception $e) {
      echo '' . $e->getMessage();
    }
  }

  // https://test.digibima.com/generatecbc/2/19473f652597890240dd590b0005a475
  public static function verifyAdhar($adharid, $dob, $name, $gender, $sessionid, $tokenid)
  {
    $curl = curl_init();
    // JSON payload
    try {
      $payload = json_encode([
        'aadharCKYCDetailsIO' => [
          'aadharno' => $adharid,
          'name' => $name,
          'dob' => $dob,
          'gender' => $gender,
        ]
      ]);

      curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.careinsurance.com/relinterfacerestful/religare/secure/restful/aadharCKYC',
        // CURLOPT_URL => 'https://api.religarehealthinsurance.com/relinterfacerestful/religare/secure/restful/aadharCKYC',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
          'appId: ' . self::$appId,
          'signature:  ' . self::$signature,
          'timestamp: ' . self::$timestamp,
          'sessionId: ' . $sessionid,
          'tokenId: ' . $tokenid,
          'applicationCD: ' . self::$applicationCD,
          'Content-Type: application/json'
        ],
      ]);
      $response = curl_exec($curl);
      Log::info(['Response' => $response]);
      if (curl_errno($curl)) {
        $error_msg = curl_error($curl);
        curl_close($curl);
        return 'cURL error: ' . $error_msg;
      }
    } catch (\Exception $e) {
      echo '' . $e->getMessage();
    }
    curl_close($curl);
    return $response;
  }

  public static function getBasicPlanToken()
  {
    try {
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://abacus.careinsurance.com/religare_api/api/web/v1/auth/access-token?formattype=json',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => ' {
            "api_key":"_OoMPcpCX1ZLYcWi1h2hb5WIoYrJF4hz",
            "auth_secret":"SVeacjpK{SNd"
        }',
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json'
        ),
      ));
      $response = curl_exec($curl);
      // Log::info(['Care_Abacus_Token_Response'=>$response]);
      curl_close($curl);
      // dd($response);
      return $response;
    } catch (\Exception $e) {
      echo '' . $e->getMessage();
    }
  }

  public static function getBasicPlan($params)
  {
    try {
      // return $params;
      $userId = $params['userId'];
      $pincode = $params['pincode'];
      $coverage = strtoupper($params['coverage']);
      $time = $params['tenure'] ?? '1';
      $ped_tenure = $params['ped_tenure'] ?? '';
      $wb = $params['wb'] ?? '0';
      $gpc = $params['gpc'] ?? '0';
      $ib = $params['ib'] ?? '0';
      $ahc = $params['ahc'] ?? '0';
      $cs = $params['cs'] ?? '0';
      $opd = $params['opd'] ?? '0';
      $bfp = $params['bfp'] ?? '0';
      $pp = $params['pp'] ?? '0';
      $ic = $params['ic'] ?? '0';
      $uc = $params['uc'] ?? '0';
      $opc = $params['opc'] ?? '0';
      $uec = $params['uec'] ?? '0';
      $ped = $params['ped'] ?? '0';
      $tm = $params['tm'] ?? '0';

      $userId = $params['userId'];
      $data = Insure::where('proposalid', $userId)->get();
      $nom = $data->count();
      $noc = $data->filter(function ($item) {
        return $item->age < 25 && $item->name == 'Son' || $item->name == 'Daughter';
      })->count();
      // $aData = $data->sortByDesc('age')->toArray();
      $aData = $data->sortByDesc('age')->values();

      // dd($aData);
      $memberName = [];
      foreach ($data as $key => $member) {
        $memberName[] = strtolower($member->name);
      }
      // $covertype = (in_array('self', $memberName) && $nom > 1) ? 'Floater' : 'Individual';
      $cachecovertype = 'cache_covertype_' . $userId;
      $covertypecache = GetCache($cachecovertype);
      $covertypecache = $covertypecache == 'Multi Individual' ? 'Individual' : $covertypecache;
      if (empty($covertypecache)) {
        $covertype = (in_array('self', $memberName) && $nom > 1) ? 'Floater' : 'Individual';
      } else {
        $covertype = $covertypecache;
      }

      $authValue = '';
      $authID = self::getBasicPlanToken();
      $authValue = json_decode($authID)->data->accessToken;

      $cachehealthplan = 'cache_healthplan_' . $userId;
      $plantype = GetCache($cachehealthplan) ?? '1';
      $business_type = $plantype == '2' ? 'PORT' : 'NB';

      $cacheporttenure = 'cache_porttenure_' . $userId;
      $porttenure = GetCache($cacheporttenure) ?? 1;

      $opdArray = Session::get('user_addons.' . $userId, []);
      $opdAmounts = !empty($opdArray) ? (string) $opdArray[0] : '0';
      // return $opdAmounts;

      $arr1 = [];

      foreach ($aData->slice(1)->values() as $index => $row) {
        $arr1['newMem_' . ($index + 2)] = $row->age;
      }

      $arr2 = [
        'partnerId' => '498',
        'abacusId' => '6643',
        'postedField' => array_merge(
          $arr1,
          [
            'field_54' => $pincode,
            'field_75' => $business_type,
            'field_1' => $nom,
            'field_10' => $noc,
            'field_9' => $covertype,
            'field_3' => $aData[0]['age'] ?? '',
            'field_2' => $coverage,
            'customerType' => 'New',
            'field_4' => $time . ' Year',
            'outPutField' => 'field_8',
            'field_WB' => $wb,
            'field_EC' => $uec,
            'field_GP' => $gpc,
            'field_TB' => $tm,
            'field_NCB' => $ib,
            'field_CS' => $cs,
            'field_RP' => $pp,
            'field_IC' => $ic,
            'field_AHC' => $ahc,
            'field_OPD' => $opd,
            'field_43' => $ped,
            'field_UC' => $uc,
            'field_BFB' => $bfp,
            'field_OPD_SI' => ($opd == 1) ? $opdAmounts : '0',
            'field_PORT_TENURE' => ($business_type == 'PORT') ? $porttenure : '0',
            'field_PED_TENURE' => $ped_tenure ? $ped_tenure . ' Year' : '',
          ]
        ),
      ];
      // return $arr2;
      $requestdata = json_encode($arr2);
      SaveFile($requestdata, 'caresuperemerequest.txt');

      $curl = curl_init();
      curl_setopt_array($curl, [
        CURLOPT_URL => 'https://abacus.careinsurance.com/religare_api/api/web/v1/abacus/partner?formattype=json',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $requestdata,
        CURLOPT_HTTPHEADER => [
          'Authorization: Bearer' . $authValue,
          'Content-Type: application/json'
        ],
      ]);
      $response = curl_exec($curl);
      SaveFile($response, 'caresupereme.txt');
      Log::info(['Ultimate_Abacus_Response' => $response]);
      curl_close($curl);
      return $response;
    } catch (\Exception $e) {
      return response()->json([
        $e->getMessage()
      ]);
    }
  }

  public static function cbcConvertor($param)
  {
    $loaclaesKey = self::$aeskey;
    $loacliv = 'i0kbCAlFTlDXshYV';
    $encrypted = openssl_encrypt(
      $param,
      'aes-256-cbc',
      $loaclaesKey,
      OPENSSL_RAW_DATA,
      $loacliv
    );
    return base64_encode(base64_encode($encrypted));
  }

  public static function creatPolicy($userId, $sessionid, $tokenid)
  {
    try {
      // SaveFile("ghyji");
      // $userId = $request->userid;
      $user = User::find($userId);

      $user1 = HealthJourney::where('userid', $userId)->where('vid', getconstant('HEALTH.ULTIMATECARE.KEY'))->first();
      // return $user1['addon'];

      $commaddress = json_decode($user1->comunication_address);

      $bankAccount = !empty(json_decode($user1['bank_details'])->account) ? json_decode($user1['bank_details'])->account : 'SACBHJHB';

      $bankIFSC = !empty(json_decode($user1['bank_details'])->ifsc) ? json_decode($user1['bank_details'])->ifsc : 'SDVBHJSJDBV';

      $addons = (is_object($user1) && isset($user1->addon) && $user1->addon !== null)
        ? json_decode($user1->addon, true)
        : [];
      // return $addons;
      $csaddonCode = '';
      $cachecompulsory = 'cache_' . getconstant('HEALTH.ULTIMATECARE.KEY') . '_compulsoryaddon_' . $userId;
      $cachedValue = GetCache($cachecompulsory);

      $cachecompulsory = 'cache_' . getconstant('HEALTH.ULTIMATECARE.KEY') . '_compulsoryaddon_' . $userId;
      $cachedValue = GetCache($cachecompulsory);
      $compulsoryaddon = $cachedValue ? json_decode($cachedValue, true) : [];

      $cachekyctype = 'cache_' . getconstant('HEALTH.ULTIMATECARE.KEY') . '_kyctype_' . $userId;
      // GetCache($cachekyctype);

      $namePart = GetCache($cachekyctype) == 'o' ? explode(' ', $user->name) : explode(' ', $user1->kyc_name);

      $insures = [];
      $covertype = '';

      $relationCode = [
        'SELF' => 'SELF',
        'HUSBAND' => 'SPSE',
        'WIFE' => 'SPSE',
        'SON' => 'SONM',
        'DAUGHTER' => 'DAUG',
        'FATHER' => 'FATH',
        'MOTHER' => 'MOTH',
        'FATHERINLAW' => 'FLAW',
        'MOTHERINLAW' => 'MLAW',
        'GRANDMOTHER' => 'GMOT',
        'GRANDFATHER' => 'GFAT'
      ];

      $sumInsuredInd = [
        '5' => '221',
        '7' => '223',
        '10' => '225',
        '15' => '227',
        '20' => '229',
        '25' => '231',
        '50' => '233',
        '75' => '235',
        '100' => '237',
        'UNLIMITED' => '239',
      ];

      $sumInsuredFlo = [
        '5' => '222',
        '7' => '224',
        '10' => '226',
        '15' => '228',
        '20' => '230',
        '25' => '232',
        '50' => '234',
        '75' => '236',
        '100' => '238',
        'UNLIMITED' => '240',
      ];

      $opdValue = 'opd5000';
      $addonCode = [
        'pp' => '1514',
        'uec' => '1511',
        'wb' => '1515',
        'gpc' => '1517',
        'tm2' => '1518',
        'tm3' => '1519',
        'tm4' => '1520',
        'tm5' => '1521',
        'ib' => '1501',
        'ahc' => '1516',
        'cs' => '1502',
        'ic' => '1503',
        'bfp' => '1504',
        'uc' => '1524',
        '1' => '1507',
        '2' => '1508',
      ];

      $opdMap = [
        'opd5000' => '1525-5000',
        'opd500' => '1512,1513',
        'opdplus' => '1565'
      ];

      if (isset($opdMap[$opdValue])) {
        $addonCode['opd'] = $opdMap[$opdValue];
      }

      // return $addonCode;

      $contactDetails = json_decode($user1->contact_details);
      $permanentAddress = json_decode($user1->permanent_address);
      // $commaddress = json_decode($user1->comunication_address);
      $proposerDetails = json_decode($user1->proposer_details);
      // dd($csaddonCode);
      $userData = [
        'dob' => str_replace('-', '/', $user1->dob),
        'gender' => (strtoupper($user1->gender) == 'MS') ? 'FEMALE' : 'MALE',
        'height' => $proposerDetails->height,
        'weight' => $proposerDetails->weight,
        'house' => strtoupper($permanentAddress->address1),  // k
        'colony' => strtoupper($permanentAddress->address2),  // k
        'landmark' => strtoupper($permanentAddress->landmark),
        'city' => $permanentAddress->city,  // k
        'state' => $permanentAddress->state,  // k
        'pincode' => $user->pincode,
        'mobile' => $contactDetails->contactmobile,
        'email' => $contactDetails->contactemail ?? $user->email,
        'panId' => strtoupper($user1->pan),
        'title' => strtoupper($user1->gender),
        'firstName' => isset($namePart[0]) ? $namePart[0] : 'Unknown',
        'lastName' => count($namePart) > 1 ? end($namePart) : 'Unknown',
        'bankAccount' => $bankAccount ?? '',
        'bankIFSC' => $bankIFSC ?? '',
        'relationCd' => $relationCode[strtoupper($user1->proposer)]  // strtoupper($user->proposar)
      ];

      // return $userData;
      // dd($userData);
      // ------------------------------------inserting proposar data------------------------------
      // $ckycnumber = session()->get('ckycno');
      // $cacheckycno = 'cache_' . getconstant("HEALTH.ULTIMATECARE.KEY") . '_ckycno_' . $userId;
      // $ckycnumber = GetCache($cacheckycno);

      // $cacheckycno = 'cache_ckycno_' . $userId;
      $cacheckycno = 'cache_' . getconstant('HEALTH.ULTIMATECARE.KEY') . '_ckycno_' . $userId;
      $ckycnumber = GetCache($cacheckycno);

      $cacheckyctype = 'cache_' . getconstant('HEALTH.ULTIMATECARE.KEY') . '_kyctype_' . $userId;

      $insures[] =
        [
          'birthDt' => $userData['dob'],
          'firstName' => $userData['firstName'],
          'genderCd' => $userData['gender'],
          'height' => (30 * $userData['height']),
          'weight' => $userData['weight'],
          'guid' => 'QN6237993',
          'ckycNumber' => GetCache($cacheckyctype) != 'o' ? $ckycnumber : '',
          'ckyc' => GetCache($cacheckyctype) != 'o' ? 'Yes' : 'No',
          'ovdkyc' => GetCache($cacheckyctype) == 'o' ? 'YES' : 'NO',
          'lastName' => $userData['lastName'],
          'partyBankDetails' => [
            'bankAccountNumber' => self::cbcConvertor($userData['bankAccount']),
            'bankIFSCCode' => self::cbcConvertor($userData['bankIFSC']),
          ],
          'partyAddressDOList' => [
            [
              'addressLine1Lang1' => $userData['house'],
              'addressLine2Lang1' => $userData['colony'],
              'addressTypeCd' => 'PERMANENT',
              'areaCd' => $userData['landmark'],
              'cityCd' => $userData['city'],
              'stateCd' => $userData['state'],
              'pinCode' => $userData['pincode']
            ],
            [
              'addressLine1Lang1' => $commaddress->sameAddress == '1' ? $userData['house'] : $commaddress->commcurrenthouse,
              'addressLine2Lang1' => $commaddress->sameAddress == '1' ? $userData['colony'] : $commaddress->commcurrenthouse,
              'addressTypeCd' => 'COMMUNICATION',
              'areaCd' => $commaddress->sameAddress == '1' ? $userData['landmark'] : $commaddress->commcurrentcity,
              'cityCd' => $commaddress->sameAddress == '1' ? $userData['city'] : $commaddress->commcurrentcity,
              'pinCode' => $commaddress->sameAddress == '1' ? $userData['pincode'] : $commaddress->commcurrentpincode,
              'stateCd' => $commaddress->sameAddress == '1' ? $userData['state'] : $commaddress->commcurrentstate,
            ]
          ],
          'partyContactDOList' => [
            [
              'contactNum' => $userData['mobile'],
              'contactTypeCd' => 'MOBILE',
              'stdCode' => '+91'
            ]
          ],
          'partyEmailDOList' => [
            [
              'emailAddress' => $userData['email'],
              'emailTypeCd' => 'PERSONAL'
            ]
          ],
          'partyIdentityDOList' => [
            [
              'identityTypeCd' => 'PAN',
              'identityNum' => $userData['panId']
            ]
          ],
          'relationCd' => $userData['relationCd'],
          'roleCd' => 'PROPOSER',
          'titleCd' => $userData['title'],
          // "prevInsurerClientId"=> "09580",
          'partyQuestionDOList' => []
        ];

      // ------------------------------------end inserting proposar data------------------------------
      // dd($insures);

      $members = JourneyUsers::where('proposalid', $user->id)->where('insureid', '<>', '0')->get();

      $nominee = JourneyUsers::where('proposalid', $user->id)->where('insureid', '0')->first();

      // $user1 = HealthJourney::where('userid', $userId)
      //   ->where('vid', getconstant("HEALTH.ULTIMATECARE.KEY"))
      //   ->first();
      $cachecoverage = 'cache_coverage_' . $userId;
      $cachetenure = 'cache_tenure_' . $userId;
      $tenure = GetCache($cachetenure);

      $csaddonCodeArray = array_map(function ($value) use ($addonCode, $tenure) {
        $key = ($value === 'tm') ? 'tm' . $tenure : $value;
        return $addonCode[$key] ?? null;
      }, $compulsoryaddon);

      $csaddonCodeArray = array_filter($csaddonCodeArray);

      foreach ($addons as $value) {
        $key = ($value === 'tm') ? 'tm' . $tenure : $value;
        $code = null;

        if (array_key_exists($key, $addonCode)) {
          $code = $addonCode[$key];
        } elseif (array_key_exists($key, $opdMap)) {
          $code = $opdMap[$key];
        }

        if ($code && !in_array($code, $csaddonCodeArray)) {
          $csaddonCodeArray[] = $code;
        }
      }

      // 1. Pehle array ko string mein badal kar default value set karein
      $csaddonCode = implode(',', $csaddonCodeArray);

      if (strtolower(GetCache($cachecoverage)) == 'unlimited') {
        $Aaddon = ['1524', '1512', '1513', '1512,1513'];
        $filteredArray = array_diff($csaddonCodeArray, $Aaddon);
        $csaddonCode = implode(',', array_values($filteredArray));
      }
      // return $csaddonCode;
      $memberRelation = [];
      foreach ($members as $key => $member) {
        $memberRelation[] = strtolower($member->relation);
      }
      // $covertype = (in_array('self', $memberRelation) && count($members) > 1) ? 'FAMILYFLOATER' : 'INDIVIDUAL';
      // $covertype = count($members) > 1 ? 'FAMILYFLOATER' : 'INDIVIDUAL';

      $cachecovertype = 'cache_covertype_' . $userId;

      $covertype = GetCache($cachecovertype);
      $covertype = $covertype == 'Multi Individual' ? 'INDIVIDUAL' : $covertype;

      $normalized = strtoupper(trim($covertype));
      if ($normalized == 'FLOATER') {
        $covertype = 'FAMILYFLOATER';
      }

      if ($normalized == 'INDIVIDUAL') {
        $covertype = 'INDIVIDUAL';
      }

      // return $covertype;
      $sumInsured = '';

      // GetCache($cachecoverage);
      if (($covertype == 'FAMILYFLOATER') && array_key_exists(strtoupper(GetCache($cachecoverage)), $sumInsuredFlo)) {
        $sumInsured = $sumInsuredFlo[strtoupper(GetCache($cachecoverage))];
      } else {
        $sumInsured = $sumInsuredInd[strtoupper(GetCache($cachecoverage))];
      }

      // return $sumInsured;

      foreach ($members as $key => $member) {
        if ($member->relation == 'self') {
          $disease = !empty($member->ped) ? json_decode($member->ped, true) : [];
          $diseaseCount = count($disease);
          $did = [];
          foreach ($disease as $key => $value) {
            $did[] = $value['did'];
          }
          $insures[] =
            [
              'birthDt' => str_replace('-', '/', $member->dob),
              'firstName' => $userData['firstName'],
              'genderCd' => $userData['gender'],
              'height' => (30 * $userData['height']),
              'weight' => $userData['weight'],
              'guid' => 'QN6237993',
              'ckycNumber' => GetCache($cacheckyctype) != 'o' ? $ckycnumber : '',
              'ckyc' => GetCache($cacheckyctype) != 'o' ? 'Yes' : 'No',
              'lastName' => $userData['lastName'],
              'partyBankDetails' => [
                'bankAccountNumber' => self::cbcConvertor($userData['bankAccount']),
                'bankIFSCCode' => self::cbcConvertor($userData['bankIFSC']),
              ],
              'partyAddressDOList' => [
                [
                  'addressLine1Lang1' => $userData['house'],
                  'addressLine2Lang1' => $userData['colony'],
                  'addressTypeCd' => 'PERMANENT',
                  'areaCd' => $userData['landmark'],
                  'cityCd' => $userData['city'],
                  'stateCd' => $userData['state'],
                  'pinCode' => $userData['pincode']
                ],
                [
                  'addressLine1Lang1' => $commaddress->sameAddress == '1' ? $userData['house'] : $commaddress->commcurrenthouse,
                  'addressLine2Lang1' => $commaddress->sameAddress == '1' ? $userData['colony'] : $commaddress->commcurrenthouse,
                  'addressTypeCd' => 'COMMUNICATION',
                  'areaCd' => $commaddress->sameAddress == '1' ? $userData['landmark'] : $commaddress->commcurrentcity,
                  'cityCd' => $commaddress->sameAddress == '1' ? $userData['city'] : $commaddress->commcurrentcity,
                  'pinCode' => $commaddress->sameAddress == '1' ? $userData['pincode'] : $commaddress->commcurrentpincode,
                  'stateCd' => $commaddress->sameAddress == '1' ? $userData['state'] : $commaddress->commcurrentstate,
                ]
              ],
              'partyContactDOList' => [
                [
                  'contactNum' => $userData['mobile'],
                  'contactTypeCd' => 'MOBILE',
                  'stdCode' => '+91'
                ]
              ],
              'partyEmailDOList' => [
                [
                  'emailAddress' => $userData['email'],
                  'emailTypeCd' => 'PERSONAL'
                ]
              ],
              'partyIdentityDOList' => [
                [
                  'identityTypeCd' => 'PAN',
                  'identityNum' => $userData['panId']
                ]
              ],
              'partyQuestionDOList' => [
                [
                  'questionSetCd' => 'yesNoExist',
                  'questionCd' => 'pedYesNo',
                  'response' => $diseaseCount > 0 ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDcancerDetails',
                  'questionCd' => '114',
                  'response' => array_search(1.1, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDcancerDetails',
                  'questionCd' => 'cancerExistingSince',
                  'response' => array_search(1.1, $did) !== false ? $disease[array_search(1.1, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDcardiacDetails',
                  'questionCd' => '143',
                  'response' => array_search(1.2, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDcardiacDetails',
                  'questionCd' => 'cardiacExistingSince',
                  'response' => array_search(1.2, $did) !== false ? $disease[array_search(1.2, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDhyperTensionDetails',
                  'questionCd' => '207',
                  'response' => array_search(1.3, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDhyperTensionDetails',
                  'questionCd' => 'hyperTensionExistingSince',
                  'response' => array_search(1.3, $did) !== false ? $disease[array_search(1.3, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDRespiratoryDetails',
                  'questionCd' => '250',
                  'response' => array_search(1.4, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDRespiratoryDetails',
                  'questionCd' => 'respiratoryExistingSince',
                  'response' => array_search(1.4, $did) !== false ? $disease[array_search(1.4, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDEndoDetails',
                  'questionCd' => '222',
                  'response' => array_search(1.5, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDEndoDetails',
                  'questionCd' => 'EndocriExistingSince',
                  'response' => array_search(1.5, $did) !== false ? $disease[array_search(1.5, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDdiabetesDetails',
                  'questionCd' => '205',
                  'response' => array_search(1.6, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDdiabetesDetails',
                  'questionCd' => 'diabetesExistingSince',
                  'response' => array_search(1.6, $did) !== false ? $disease[array_search(1.6, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDkidneyDetails',
                  'questionCd' => '129',
                  'response' => array_search(1.9, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDkidneyDetails',
                  'questionCd' => 'kidneyExistingSince',
                  'response' => array_search(1.9, $did) !== false ? $disease[array_search(1.9, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDliverDetails',
                  'questionCd' => '232',
                  'response' => array_search(1.8, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDliverDetails',
                  'questionCd' => 'liverExistingSince',
                  'response' => array_search(1.8, $did) !== false ? $disease[array_search(1.8, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDparalysisDetails',
                  'questionCd' => '164',
                  'response' => array_search(1.7, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDparalysisDetails',
                  'questionCd' => 'paralysisExistingSince',
                  'response' => array_search(1.7, $did) !== false ? $disease[array_search(1.7, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDjointpainDetails',
                  'questionCd' => '105',
                  'response' => array_search('1.10', $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDjointpainDetails',
                  'questionCd' => 'jointpainExistingSince',
                  'response' => array_search('1.10', $did) !== false ? $disease[array_search('1.10', $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDcongenitalDetails',
                  'questionCd' => '122',
                  'response' => array_search(1.11, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDcongenitalDetails',
                  'questionCd' => 'congenitalExistingSince',
                  'response' => array_search(1.11, $did) !== false ? $disease[array_search(1.11, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDHivaidsDetails',
                  'questionCd' => '147',
                  'response' => array_search(1.12, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDHivaidsDetails',
                  'questionCd' => 'hivaidsExistingSince',
                  'response' => array_search(1.12, $did) !== false ? $disease[array_search(1.12, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDotherDetails',
                  'questionCd' => '210',
                  'response' => array_search(1.13, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDotherDetails',
                  'questionCd' => 'otherExistingSince',
                  'response' => array_search(1.13, $did) !== false ? $disease[array_search(1.13, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDotherDetails',
                  'questionCd' => 'otherDiseasesDescription',
                  'response' => array_search(1.13, $did) !== false ? $disease[array_search(1.13, $did)]['des'] : ''
                ],
                [
                  'questionSetCd' => 'PEDillnessDetails',
                  'questionCd' => '502',
                  'response' => array_search(1.14, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDillnessDetails',
                  'questionCd' => 'illnessExistingSince',
                  'response' => array_search(1.14, $did) !== false ? $disease[array_search(1.14, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDSurgeryDetails',
                  'questionCd' => '503',
                  'response' => array_search(1.15, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDSurgeryDetails',
                  'questionCd' => 'SurgeryExistingSince',
                  'response' => array_search(1.15, $did) !== false ? $disease[array_search(1.15, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'HEDHealthHospitalized',
                  'questionCd' => 'H001',
                  'response' => array_search(2.4, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'HEDHealthClaim',
                  'questionCd' => 'H002',
                  'response' => array_search(2.1, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'HEDHealthDeclined',
                  'questionCd' => 'H003',
                  'response' => array_search(2.2, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'HEDHealthCovered',
                  'questionCd' => 'H004',
                  'response' => array_search(2.3, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'HEDCareleafPA',
                  'questionCd' => 'P010',
                  'response' => 'NO'
                ],
                [
                  'questionSetCd' => 'PEDSmokeDetails',
                  'questionCd' => '504',
                  'response' => array_search(3.1, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDSmokeDetails',
                  'questionCd' => 'SmokeExistingSince',
                  'response' => array_search(3.1, $did) !== false ? $disease[array_search(3.1, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDSmokeDetails',
                  'questionCd' => 'OtherSmokeDetails',
                  'response' => array_search(3.1, $did) !== false ? $disease[array_search(3.1, $did)]['quantity'] : ''
                ]
              ],
              'relationCd' => $relationCode[strtoupper($user1->proposer)],
              'roleCd' => 'PRIMARY',
              'titleCd' => $userData['title'],
              // "prevInsurerClientId"=> "09580",
            ];
          break;
        }
      }
      // return $coverage;

      // dd($insures);
      if (count($members) > 0) {
        $guid = '';
        foreach ($members as $k => $member) {
          $disease = !empty($member->ped) ? json_decode($member->ped, true) : [];
          $diseaseCount = count($disease);
          $did = [];
          foreach ($disease as $key => $value) {
            $did[] = $value['did'];
          }
          if ($member->insureid != '0' && $member->relation != 'self') {
            $namePart = explode(' ', $member->name);
            $fname = isset($namePart[0]) ? $namePart[0] : 'Unknown';
            $lname = count($namePart) > 1 ? end($namePart) : 'akka';
            $relationCd = '';
            if (strtoupper($member->relation) == array_key_exists(strtoupper($member->relation), $relationCode)) {
              $relationCd = $relationCode[strtoupper($member->relation)];
            }
            $guid = 'QN623799' . ($k + 4);
            $insures[] = [
              'birthDt' => str_replace('-', '/', $member->dob),
              'firstName' => $fname,
              'genderCd' => $member->gender,
              'guid' => $guid,
              'ckycNumber' => $member->relation == 'self' && GetCache($cacheckyctype) != 'o' ? $ckycnumber : '',
              'ckyc' => $member->relation == 'self' && GetCache($cacheckyctype) != 'o' ? 'YES' : 'No',
              'height' => (30 * $member->height),
              'weight' => $member->weight,
              'lastName' => $lname,
              'partyBankDetails' => [
                'bankAccountNumber' => self::cbcConvertor($userData['bankAccount']),
                'bankIFSCCode' => self::cbcConvertor($userData['bankIFSC']),
              ],
              'partyAddressDOList' => [
                [
                  'addressLine1Lang1' => '',
                  'addressLine2Lang1' => '',
                  'addressTypeCd' => 'PERMANENT',
                  'areaCd' => '',
                  'cityCd' => '',
                  'stateCd' => '',
                  'pinCode' => $commaddress->sameAddress == '1' ? $userData['pincode'] : $commaddress->commcurrentpincode,
                ],
                [
                  'addressLine1Lang1' => '',
                  'addressLine2Lang1' => '',
                  'addressTypeCd' => 'COMMUNICATION',
                  'areaCd' => '',
                  'cityCd' => '',
                  'pinCode' => $commaddress->sameAddress == '1' ? $userData['pincode'] : $commaddress->commcurrentpincode,
                  'stateCd' => ''
                ]
              ],
              'partyContactDOList' => [
                [
                  'contactNum' => $userData['mobile'],
                  'contactTypeCd' => 'MOBILE',
                  'stdCode' => '+91'
                ]
              ],
              'partyEmailDOList' => [
                [
                  'emailAddress' => $userData['email'],
                  'emailTypeCd' => 'PERSONAL'
                ]
              ],
              'partyIdentityDOList' => [
                [
                  'identityTypeCd' => 'PAN',
                  'identityNum' => $userData['panId']
                ]
              ],
              'partyQuestionDOList' => [
                [
                  'questionSetCd' => 'yesNoExist',
                  'questionCd' => 'pedYesNo',
                  'response' => $diseaseCount > 0 ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDcancerDetails',
                  'questionCd' => '114',
                  'response' => array_search(1.1, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDcancerDetails',
                  'questionCd' => 'cancerExistingSince',
                  'response' => array_search(1.1, $did) !== false ? $disease[array_search(1.1, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDcardiacDetails',
                  'questionCd' => '143',
                  'response' => array_search(1.2, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDcardiacDetails',
                  'questionCd' => 'cardiacExistingSince',
                  'response' => array_search(1.2, $did) !== false ? $disease[array_search(1.2, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDhyperTensionDetails',
                  'questionCd' => '207',
                  'response' => array_search(1.3, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDhyperTensionDetails',
                  'questionCd' => 'hyperTensionExistingSince',
                  'response' => array_search(1.3, $did) !== false ? $disease[array_search(1.3, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDRespiratoryDetails',
                  'questionCd' => '250',
                  'response' => array_search(1.4, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDRespiratoryDetails',
                  'questionCd' => 'respiratoryExistingSince',
                  'response' => array_search(1.4, $did) !== false ? $disease[array_search(1.4, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDEndoDetails',
                  'questionCd' => '222',
                  'response' => array_search(1.5, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDEndoDetails',
                  'questionCd' => 'EndocriExistingSince',
                  'response' => array_search(1.5, $did) !== false ? $disease[array_search(1.5, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDdiabetesDetails',
                  'questionCd' => '205',
                  'response' => array_search(1.6, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDdiabetesDetails',
                  'questionCd' => 'diabetesExistingSince',
                  'response' => array_search(1.6, $did) !== false ? $disease[array_search(1.6, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDkidneyDetails',
                  'questionCd' => '129',
                  'response' => array_search(1.9, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDkidneyDetails',
                  'questionCd' => 'kidneyExistingSince',
                  'response' => array_search(1.9, $did) !== false ? $disease[array_search(1.9, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDliverDetails',
                  'questionCd' => '232',
                  'response' => array_search(1.8, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDliverDetails',
                  'questionCd' => 'liverExistingSince',
                  'response' => array_search(1.8, $did) !== false ? $disease[array_search(1.8, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDparalysisDetails',
                  'questionCd' => '164',
                  'response' => array_search(1.7, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDparalysisDetails',
                  'questionCd' => 'paralysisExistingSince',
                  'response' => array_search(1.7, $did) !== false ? $disease[array_search(1.7, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDjointpainDetails',
                  'questionCd' => '105',
                  'response' => array_search('1.10', $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDjointpainDetails',
                  'questionCd' => 'jointpainExistingSince',
                  'response' => array_search('1.10', $did) !== false ? $disease[array_search('1.10', $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDcongenitalDetails',
                  'questionCd' => '122',
                  'response' => array_search(1.11, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDcongenitalDetails',
                  'questionCd' => 'congenitalExistingSince',
                  'response' => array_search(1.11, $did) !== false ? $disease[array_search(1.11, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDHivaidsDetails',
                  'questionCd' => '147',
                  'response' => array_search(1.12, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDHivaidsDetails',
                  'questionCd' => 'hivaidsExistingSince',
                  'response' => array_search(1.12, $did) !== false ? $disease[array_search(1.12, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDotherDetails',
                  'questionCd' => '210',
                  'response' => array_search(1.13, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDotherDetails',
                  'questionCd' => 'otherExistingSince',
                  'response' => array_search(1.13, $did) !== false ? $disease[array_search(1.13, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDotherDetails',
                  'questionCd' => 'otherDiseasesDescription',
                  'response' => array_search(1.13, $did) !== false ? $disease[array_search(1.13, $did)]['des'] : ''
                ],
                [
                  'questionSetCd' => 'PEDillnessDetails',
                  'questionCd' => '502',
                  'response' => array_search(1.14, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDillnessDetails',
                  'questionCd' => 'illnessExistingSince',
                  'response' => array_search(1.14, $did) !== false ? $disease[array_search(1.14, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDSurgeryDetails',
                  'questionCd' => '503',
                  'response' => array_search(1.15, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDSurgeryDetails',
                  'questionCd' => 'SurgeryExistingSince',
                  'response' => array_search(1.15, $did) !== false ? $disease[array_search(1.15, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'HEDHealthHospitalized',
                  'questionCd' => 'H001',
                  'response' => array_search(2.4, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'HEDHealthClaim',
                  'questionCd' => 'H002',
                  'response' => array_search(2.1, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'HEDHealthDeclined',
                  'questionCd' => 'H003',
                  'response' => array_search(2.2, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'HEDHealthCovered',
                  'questionCd' => 'H004',
                  'response' => array_search(2.3, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'HEDCareleafPA',
                  'questionCd' => 'P010',
                  'response' => 'NO'
                ],
                [
                  'questionSetCd' => 'PEDSmokeDetails',
                  'questionCd' => '504',
                  'response' => array_search(3.1, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDSmokeDetails',
                  'questionCd' => 'SmokeExistingSince',
                  'response' => array_search(3.1, $did) !== false ? $disease[array_search(3.1, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDSmokeDetails',
                  'questionCd' => 'OtherSmokeDetails',
                  'response' => array_search(3.1, $did) !== false ? $disease[array_search(3.1, $did)]['quantity'] : ''
                ]
              ],
              'relationCd' => $relationCd,
              'roleCd' => 'PRIMARY',
              'titleCd' => $member->gender == 'MALE' ? 'MR' : 'MS',
              // "prevInsurerClientId"=> "09580",
            ];
          }
        }
      }

      $cachehealthplan = 'cache_healthplan_' . $userId;
      $plantype = GetCache($cachehealthplan) ?? '1';
      $business_type = $plantype == '2' ? 'PORT' : 'NB';
      $normalized = strtoupper(trim($business_type));
      // $formattedDob = date('Y-m-d', strtotime($nominee->dob));
      $formattedDob = str_replace('-', '/', $nominee->dob);

      if ($normalized == 'NB') {
        $business_type = 'NEWBUSINESS';
      }
      // return $business_type;
      $postData = json_encode([
        'intPolicyDataIO' => [
          'policy' => [
            'businessTypeCd' => $business_type,  // "NEWBUSINESS",
            // "businessTypeCd" => "ROLLOVER",
            'baseProductId' => self::$productId,
            'baseAgentId' => self::$agentid,
            'coverType' => $covertype,
            'partyDOList' => $insures,
            'policyAdditionalFieldsDOList' => [
              [
                'field1' => 'Partner_NB_DigiBima',
                'field10' => $nominee->name,
                'field12' => strtoupper(explode('(', $nominee->relation)[0]),
                'field9' => (strtoupper($nominee->gender) == 'MALE') ? 'MR' : 'MS',
                'field17' => $formattedDob,
                'fieldAgree' => 'YES',
                'fieldAlerts' => 'YES',
                'fieldTc' => 'YES'
              ]
            ],
            'sumInsured' => $sumInsured,
            'term' => $tenure,
            'isPremiumCalculation' => 'YES',
            'addOns' => $csaddonCode
          ]
        ]
      ]);
      SaveFile($postData, 'ultimate_care_request');
      // return $postData;
      Log::info(['ultimate_request' => $postData]);
      $curl = curl_init();
      curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.careinsurance.com/relinterfacerestful/religare/secure/restful/createPolicy',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_HTTPHEADER => [
          'appId: ' . self::$appId,
          'signature:  ' . self::$signature,
          'timestamp: ' . self::$timestamp,
          'sessionId: ' . $sessionid,
          'tokenId: ' . $tokenid,
          'applicationCD: ' . self::$applicationCD,
          'Content-Type: application/json'
        ],
      ]);
      $response = curl_exec($curl);
      SaveFile($response, 'ultimate_care_response');
      Log::info(['ultimate_Response' => $response]);
      return $response;
    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }

  public static function policyStatus($userId, $sessionid, $tokenid, $proposal)
  {
    // $proposalnum = session()->get('proposalnum');

    $cacheproposalnum = 'cache_' . getconstant('HEALTH.ULTIMATECARE.KEY') . '_proposalnum_' . $userId;
    $proposalnum = GetCache($cacheproposalnum);
    $curl = curl_init();
    try {
      $payload = json_encode([
        'intGetPolicyStatusIO' => [
          'proposalNum' => !empty($proposal) ? $proposal : $proposalnum,
        ]
      ]);

      curl_setopt_array($curl, [
        // CURLOPT_URL => 'https://api.religarehealthinsurance.com/relinterfacerestful/religare/secure/restful/getPolicyStatusV2',
        CURLOPT_URL => 'https://api.careinsurance.com/relinterfacerestful/religare/secure/restful/getPolicyStatusV2',  // k
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
          'appId: ' . self::$appId,
          'signature:  ' . self::$signature,
          'timestamp: ' . self::$timestamp,
          'sessionId: ' . $sessionid,
          'tokenId: ' . $tokenid,
          'agentId: ' . self::$agentid,  // 20689274
          'applicationCD: ' . self::$applicationCD,
          'Content-Type: application/json'
        ],
      ]);
      $response = curl_exec($curl);

      // Log::info(['Response' => $response]);
      curl_close($curl);
      return $response;
    } catch (\Exception $e) {
      echo '' . $e->getMessage();
    }
  }

  public static function policyPdf($sessionid, $tokenid, $policy)
  {
    $curl = curl_init();
    try {
      $payload = json_encode([
        'intFaveoGetPolicyPDFIO' => [
          'policyNum' => $policy,
          'ltype' => 'POLSCHD'
        ]
      ]);
      // return json_decode($payload);
      curl_setopt_array($curl, [
        // CURLOPT_URL => 'https://api.careinsurance.com/relinterfacerestful/religare/secure/restful/getPolicyPDFV2',
        CURLOPT_URL => 'https://api.careinsurance.com/relinterfacerestful/religare/secure/restful/getPolicyPDFV2',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
          'appId: ' . self::$appId,
          'signature:  ' . self::$signature,
          'timestamp: ' . self::$timestamp,
          'sessionId: ' . $sessionid,
          'tokenId: ' . $tokenid,
          'agentId: ' . self::$agentid,
          'applicationCD: ' . self::$applicationCD,
          'Content-Type: application/json'
        ],
      ]);
      $response = curl_exec($curl);
      curl_close($curl);
      // Log::info(['Response' => $response]);
      return $response;
    } catch (\Exception $e) {
      echo '' . $e->getMessage();
    }
  }

  public static function verifyUploadDocument($filePath, $docType, $proposalNum, $sessionid, $tokenid)
  {
    // self::initialize();
    $parts = explode('/', $filePath);
    $filename = end($parts);
    // dd($filename);
    if (isset($filename)) {
      if (file_exists($filePath)) {
        $fileContents = file_get_contents($filePath);
        $base64EncodedFile = base64_encode($fileContents);
        try {
          $payload = json_encode([
            'uploadEkycDocumentIO' => [
              'proposalNumber' => $proposalNum,
              'documentName' => $docType . '_' . $filename,
              'isBase64' => 'Y',
              'documentData' => $base64EncodedFile,
              'docType' => $docType
            ]
          ]);
          // dd($payload);
          $curl = curl_init();
          curl_setopt_array($curl, [
            // CURLOPT_URL => 'https://api.religarehealthinsurance.com/relinterfacerestful/religare/secure/restful/uploadEkycDocument',
            CURLOPT_URL => 'https://api.careinsurance.com/relinterfacerestful/religare/secure/restful/uploadEkycDocument',  // k
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
              'appId: ' . self::$appId,
              'signature:  ' . self::$signature,
              'timestamp: ' . self::$timestamp,
              'sessionId: ' . $sessionid,
              'tokenId: ' . $tokenid,
              'applicationCD: ' . self::$applicationCD,
              'Content-Type: application/json'
            ],
          ]);

          $response = curl_exec($curl);
          Log::info(['Response' => $response]);
          curl_close($curl);
          // self::saveResponse($response, '');
          return $response;
        } catch (\Exception $e) {
          echo '' . $e->getMessage();
        }
        return $base64EncodedFile;
      } else {
        // Handle case where file doesn't exist
        echo 'File not found ';
        die;
      }
    } else {
      // Handle case where UserDescription is not found
      throw new Exception('File not found.');
    }
  }

  public static function creatpolicyport($userId, $sessionid, $tokenid)
  {
    try {
      $user = User::find($userId);
      $user1 = HealthJourney::where('userid', $userId)->where('vid', getconstant('HEALTH.ULTIMATECARE.KEY'))->first();
      // return $user1['addon'];
      $dataport = json_decode($user1->portdata, true);

      $commaddress = json_decode($user1->comunication_address);

      $bankAccount = !empty(json_decode($user1['bank_details'])->account) ? json_decode($user1['bank_details'])->account : 'SACBHJHB';

      $bankIFSC = !empty(json_decode($user1['bank_details'])->ifsc) ? json_decode($user1['bank_details'])->ifsc : 'SDVBHJSJDBV';

      // return $bankIFSC;
      // $addons = (is_array($user1) && isset($user1['addon']) && $user1['addon'] !== null)
      //   ? json_decode($user1['addon'], true) : [];
      $addons = (is_object($user1) && isset($user1->addon) && $user1->addon !== null)
        ? json_decode($user1->addon, true)
        : [];
      // return $addons;
      $csaddonCode = '';
      $cachecompulsory = 'cache_' . getconstant('HEALTH.ULTIMATECARE.KEY') . '_compulsoryaddon_' . $userId;
      $cachedValue = GetCache($cachecompulsory);

      $cachecompulsory = 'cache_' . getconstant('HEALTH.ULTIMATECARE.KEY') . '_compulsoryaddon_' . $userId;
      $cachedValue = GetCache($cachecompulsory);
      $compulsoryaddon = $cachedValue ? json_decode($cachedValue, true) : [];

      $cachekyctype = 'cache_' . getconstant('HEALTH.ULTIMATECARE.KEY') . '_kyctype_' . $userId;
      GetCache($cachekyctype);
      $namePart = GetCache($cachekyctype) == 'o' ? explode(' ', $user->name) : explode(' ', $user1->kyc_name);

      $insures = [];
      $covertype = '';
      $relationCode = [
        'SELF' => 'SELF',
        'HUSBAND' => 'SPSE',
        'WIFE' => 'SPSE',
        'SON' => 'SONM',
        'DAUGHTER' => 'DAUG',
        'FATHER' => 'FATH',
        'MOTHER' => 'MOTH',
        'FATHERINLAW' => 'FLAW',
        'MOTHERINLAW' => 'MLAW',
        'GRANDMOTHER' => 'GMOT',
        'GRANDFATHER' => 'GFAT'
      ];

      $sumInsuredInd = [
        '5' => '221',
        '7' => '223',
        '10' => '225',
        '15' => '227',
        '20' => '229',
        '25' => '231',
        '50' => '233',
        '75' => '235',
        '100' => '237',
        'UNLIMITED' => '239',
      ];

      $sumInsuredFlo = [
        '5' => '222',
        '7' => '224',
        '10' => '226',
        '15' => '228',
        '20' => '230',
        '25' => '232',
        '50' => '234',
        '75' => '236',
        '100' => '238',
        'UNLIMITED' => '240',
      ];

      $opdValue = 'opd5000';
      $addonCode = [
        'pp' => '1514',
        'uec' => '1511',
        'wb' => '1515',
        'gpc' => '1517',
        'tm2' => '1518',
        'tm3' => '1519',
        'tm4' => '1520',
        'tm5' => '1521',
        'ib' => '1501',
        'ahc' => '1516',
        'cs' => '1502',
        'ic' => '1503',
        'bfp' => '1504',
        'uc' => '1524',
        '1' => '1507',
        '2' => '1508',
      ];

      $opdMap = [
        'opd5000' => '1525-5000',
        'opd500' => '1512,1513',
        'opdplus' => '1565'
      ];

      if (isset($opdMap[$opdValue])) {
        $addonCode['opd'] = $opdMap[$opdValue];
      }

      $contactDetails = json_decode($user1->contact_details);
      $permanentAddress = json_decode($user1->permanent_address);
      // $commaddress = json_decode($user1->comunication_address);
      $proposerDetails = json_decode($user1->proposer_details);
      // dd($csaddonCode);
      $userData = [
        'dob' => str_replace('-', '/', $user1->dob),
        'gender' => (strtoupper($user1->gender) == 'MS') ? 'FEMALE' : 'MALE',
        'height' => $proposerDetails->height,
        'weight' => $proposerDetails->weight,
        'house' => strtoupper($permanentAddress->address1),  // k
        'colony' => strtoupper($permanentAddress->address2),  // k
        'landmark' => strtoupper($permanentAddress->landmark),  // k
        'city' => $permanentAddress->city,  // k
        'state' => $permanentAddress->state,  // k
        'pincode' => $user->pincode,
        'mobile' => $contactDetails->contactmobile,
        'email' => $contactDetails->contactemail ?? $user->email,
        'panId' => strtoupper($user1->pan),
        'title' => strtoupper($user1->gender),
        'firstName' => isset($namePart[0]) ? $namePart[0] : 'Unknown',
        'lastName' => count($namePart) > 1 ? end($namePart) : 'Unknown',
        'bankAccount' => $bankAccount ?? '',
        'bankIFSC' => $bankIFSC ?? '',
        'relationCd' => $relationCode[strtoupper($user1->proposer)]  // strtoupper($user->proposar)
      ];
      // $cacheckycno = 'cache_ckycno_' . $userId;
      $cacheckycno = 'cache_' . getconstant('HEALTH.ULTIMATECARE.KEY') . '_ckycno_' . $userId;
      $ckycnumber = GetCache($cacheckycno);
      $cacheckyctype = 'cache_' . getconstant('HEALTH.ULTIMATECARE.KEY') . '_kyctype_' . $userId;
      $insures[] =
        [
          'birthDt' => $userData['dob'],
          'firstName' => $userData['firstName'],
          'genderCd' => $userData['gender'],
          'height' => (30 * $userData['height']),
          'weight' => $userData['weight'],
          'guid' => 'QN6237993',
          'ckycNumber' => GetCache($cacheckyctype) != 'o' ? $ckycnumber : '',
          'ckyc' => GetCache($cacheckyctype) != 'o' ? 'Yes' : 'No',
          'ovdkyc' => GetCache($cacheckyctype) == 'o' ? 'YES' : 'NO',
          'lastName' => $userData['lastName'],
          'partyBankDetails' => [
            'bankAccountNumber' => self::cbcConvertor($userData['bankAccount']),
            'bankIFSCCode' => self::cbcConvertor($userData['bankIFSC']),
          ],
          'partyAddressDOList' => [
            [
              'addressLine1Lang1' => $userData['house'],
              'addressLine2Lang1' => $userData['colony'],
              'addressTypeCd' => 'PERMANENT',
              'areaCd' => $userData['landmark'],
              'cityCd' => $userData['city'],
              'stateCd' => $userData['state'],
              'pinCode' => $userData['pincode']
            ],
            [
              'addressLine1Lang1' => $commaddress->sameAddress == '1' ? $userData['house'] : $commaddress->commcurrenthouse,
              'addressLine2Lang1' => $commaddress->sameAddress == '1' ? $userData['colony'] : $commaddress->commcurrenthouse,
              'addressTypeCd' => 'COMMUNICATION',
              'areaCd' => $commaddress->sameAddress == '1' ? $userData['landmark'] : $commaddress->commcurrentcity,
              'cityCd' => $commaddress->sameAddress == '1' ? $userData['city'] : $commaddress->commcurrentcity,
              'pinCode' => $commaddress->sameAddress == '1' ? $userData['pincode'] : $commaddress->commcurrentpincode,
              'stateCd' => $commaddress->sameAddress == '1' ? $userData['state'] : $commaddress->commcurrentstate,
            ]
          ],
          'partyContactDOList' => [
            [
              'contactNum' => $userData['mobile'],
              'contactTypeCd' => 'MOBILE',
              'stdCode' => '+91'
            ]
          ],
          'partyEmailDOList' => [
            [
              'emailAddress' => $userData['email'],
              'emailTypeCd' => 'PERSONAL'
            ]
          ],
          'partyIdentityDOList' => [
            [
              'identityTypeCd' => 'PAN',
              'identityNum' => $userData['panId']
            ]
          ],
          'relationCd' => $userData['relationCd'],
          'roleCd' => 'PROPOSER',
          'titleCd' => $userData['title'],
          'prevInsurerClientId' => $dataport['policynumber'] ?? '',  // "09580",
          'partyQuestionDOList' => []
        ];
      // ------------------------------------end inserting proposar data------------------------------
      // dd($insures);

      $members = JourneyUsers::where('proposalid', $user->id)->where('insureid', '<>', '0')->get();

      $nominee = JourneyUsers::where('proposalid', $user->id)->where('insureid', '0')->first();

      $cachecoverage = 'cache_coverage_' . $userId;
      $cachetenure = 'cache_tenure_' . $userId;
      $tenure = GetCache($cachetenure);

      $csaddonCodeArray = array_map(function ($value) use ($addonCode, $tenure) {
        $key = ($value === 'tm') ? 'tm' . $tenure : $value;
        return $addonCode[$key] ?? null;
      }, $compulsoryaddon);

      $csaddonCodeArray = array_filter($csaddonCodeArray);

      foreach ($addons as $value) {
        $key = ($value === 'tm') ? 'tm' . $tenure : $value;
        $code = null;

        if (array_key_exists($key, $addonCode)) {
          $code = $addonCode[$key];
        } elseif (array_key_exists($key, $opdMap)) {
          $code = $opdMap[$key];
        }

        if ($code && !in_array($code, $csaddonCodeArray)) {
          $csaddonCodeArray[] = $code;
        }
      }
      $csaddonCode = implode(',', $csaddonCodeArray);
      if (strtolower(GetCache($cachecoverage)) == 'unlimited') {
        $Aaddon = ['1524', '1512', '1513', '1512,1513'];
        foreach ($csaddonCodeArray as $index => $value) {
          if (in_array($value, $Aaddon)) {
            unset($csaddonCodeArray[$index]);
          }
        }
        $csaddonCode = implode(',', array_values($csaddonCodeArray));
      }
      $memberRelation = [];
      foreach ($members as $key => $member) {
        $memberRelation[] = strtolower($member->relation);
      }
      // $covertype = count($members) > 1 ? 'FAMILYFLOATER' : 'INDIVIDUAL';
      $cachecovertype = 'cache_covertype_' . $userId;
      $covertype = GetCache($cachecovertype);
      $covertype = $covertype == 'Multi Individual' ? 'INDIVIDUAL' : $covertype;
      $normalized = strtoupper(trim($covertype));
      if ($normalized == 'FLOATER') {
        $covertype = 'FAMILYFLOATER';
        $expcovertype = 'FLOATER';
      }
      if ($normalized == 'INDIVIDUAL') {
        $covertype = 'INDIVIDUAL';
        $expcovertype = 'INDIVIDUAL';
      }

      $sumInsured = '';

      if (($covertype == 'FAMILYFLOATER') && array_key_exists(GetCache($cachecoverage), $sumInsuredFlo)) {
        $sumInsured = $sumInsuredFlo[GetCache($cachecoverage)];
      } else {
        $sumInsured = $sumInsuredInd[GetCache($cachecoverage)];
      }
      foreach ($members as $key => $member) {
        if ($member->relation == 'self') {
          $disease = !empty($member->ped) ? json_decode($member->ped, true) : [];
          $diseaseCount = count($disease);
          $did = [];
          foreach ($disease as $key => $value) {
            $did[] = $value['did'];
          }
          $insures[] =
            [
              'birthDt' => str_replace('-', '/', $member->dob),
              'firstName' => $userData['firstName'],
              'genderCd' => $userData['gender'],
              'height' => (30 * $userData['height']),
              'weight' => $userData['weight'],
              'guid' => 'QN6237993',
              'ckycNumber' => GetCache($cacheckyctype) != 'o' ? $ckycnumber : '',
              'ckyc' => GetCache($cacheckyctype) != 'o' ? 'Yes' : 'No',
              'lastName' => $userData['lastName'],
              'partyBankDetails' => [
                'bankAccountNumber' => self::cbcConvertor($userData['bankAccount']),
                'bankIFSCCode' => self::cbcConvertor($userData['bankIFSC']),
              ],
              'partyAddressDOList' => [
                [
                  'addressLine1Lang1' => $userData['house'],
                  'addressLine2Lang1' => $userData['colony'],
                  'addressTypeCd' => 'PERMANENT',
                  'areaCd' => $userData['landmark'],
                  'cityCd' => $userData['city'],
                  'stateCd' => $userData['state'],
                  'pinCode' => $userData['pincode']
                ],
                [
                  'addressLine1Lang1' => $commaddress->sameAddress == '1' ? $userData['house'] : $commaddress->commcurrenthouse,
                  'addressLine2Lang1' => $commaddress->sameAddress == '1' ? $userData['colony'] : $commaddress->commcurrenthouse,
                  'addressTypeCd' => 'COMMUNICATION',
                  'areaCd' => $commaddress->sameAddress == '1' ? $userData['landmark'] : $commaddress->commcurrentcity,
                  'cityCd' => $commaddress->sameAddress == '1' ? $userData['city'] : $commaddress->commcurrentcity,
                  'pinCode' => $commaddress->sameAddress == '1' ? $userData['pincode'] : $commaddress->commcurrentpincode,
                  'stateCd' => $commaddress->sameAddress == '1' ? $userData['state'] : $commaddress->commcurrentstate,
                ]
              ],
              'partyContactDOList' => [
                [
                  'contactNum' => $userData['mobile'],
                  'contactTypeCd' => 'MOBILE',
                  'stdCode' => '+91'
                ]
              ],
              'partyEmailDOList' => [
                [
                  'emailAddress' => $userData['email'],
                  'emailTypeCd' => 'PERSONAL'
                ]
              ],
              'partyIdentityDOList' => [
                [
                  'identityTypeCd' => 'PAN',
                  'identityNum' => $userData['panId']
                ]
              ],
              'partyQuestionDOList' => [
                [
                  'questionSetCd' => 'yesNoExist',
                  'questionCd' => 'pedYesNo',
                  'response' => $diseaseCount > 0 ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDcancerDetails',
                  'questionCd' => '114',
                  'response' => array_search(1.1, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDcancerDetails',
                  'questionCd' => 'cancerExistingSince',
                  'response' => array_search(1.1, $did) !== false ? $disease[array_search(1.1, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDcardiacDetails',
                  'questionCd' => '143',
                  'response' => array_search(1.2, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDcardiacDetails',
                  'questionCd' => 'cardiacExistingSince',
                  'response' => array_search(1.2, $did) !== false ? $disease[array_search(1.2, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDhyperTensionDetails',
                  'questionCd' => '207',
                  'response' => array_search(1.3, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDhyperTensionDetails',
                  'questionCd' => 'hyperTensionExistingSince',
                  'response' => array_search(1.3, $did) !== false ? $disease[array_search(1.3, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDRespiratoryDetails',
                  'questionCd' => '250',
                  'response' => array_search(1.4, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDRespiratoryDetails',
                  'questionCd' => 'respiratoryExistingSince',
                  'response' => array_search(1.4, $did) !== false ? $disease[array_search(1.4, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDEndoDetails',
                  'questionCd' => '222',
                  'response' => array_search(1.5, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDEndoDetails',
                  'questionCd' => 'EndocriExistingSince',
                  'response' => array_search(1.5, $did) !== false ? $disease[array_search(1.5, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDdiabetesDetails',
                  'questionCd' => '205',
                  'response' => array_search(1.6, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDdiabetesDetails',
                  'questionCd' => 'diabetesExistingSince',
                  'response' => array_search(1.6, $did) !== false ? $disease[array_search(1.6, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDkidneyDetails',
                  'questionCd' => '129',
                  'response' => array_search(1.9, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDkidneyDetails',
                  'questionCd' => 'kidneyExistingSince',
                  'response' => array_search(1.9, $did) !== false ? $disease[array_search(1.9, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDliverDetails',
                  'questionCd' => '232',
                  'response' => array_search(1.8, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDliverDetails',
                  'questionCd' => 'liverExistingSince',
                  'response' => array_search(1.8, $did) !== false ? $disease[array_search(1.8, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDparalysisDetails',
                  'questionCd' => '164',
                  'response' => array_search(1.7, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDparalysisDetails',
                  'questionCd' => 'paralysisExistingSince',
                  'response' => array_search(1.7, $did) !== false ? $disease[array_search(1.7, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDjointpainDetails',
                  'questionCd' => '105',
                  'response' => array_search('1.10', $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDjointpainDetails',
                  'questionCd' => 'jointpainExistingSince',
                  'response' => array_search('1.10', $did) !== false ? $disease[array_search('1.10', $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDcongenitalDetails',
                  'questionCd' => '122',
                  'response' => array_search(1.11, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDcongenitalDetails',
                  'questionCd' => 'congenitalExistingSince',
                  'response' => array_search(1.11, $did) !== false ? $disease[array_search(1.11, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDHivaidsDetails',
                  'questionCd' => '147',
                  'response' => array_search(1.12, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDHivaidsDetails',
                  'questionCd' => 'hivaidsExistingSince',
                  'response' => array_search(1.12, $did) !== false ? $disease[array_search(1.12, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDotherDetails',
                  'questionCd' => '210',
                  'response' => array_search(1.13, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDotherDetails',
                  'questionCd' => 'otherExistingSince',
                  'response' => array_search(1.13, $did) !== false ? $disease[array_search(1.13, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDotherDetails',
                  'questionCd' => 'otherDiseasesDescription',
                  'response' => array_search(1.13, $did) !== false ? $disease[array_search(1.13, $did)]['des'] : ''
                ],
                [
                  'questionSetCd' => 'PEDillnessDetails',
                  'questionCd' => '502',
                  'response' => array_search(1.14, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDillnessDetails',
                  'questionCd' => 'illnessExistingSince',
                  'response' => array_search(1.14, $did) !== false ? $disease[array_search(1.14, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDSurgeryDetails',
                  'questionCd' => '503',
                  'response' => array_search(1.15, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDSurgeryDetails',
                  'questionCd' => 'SurgeryExistingSince',
                  'response' => array_search(1.15, $did) !== false ? $disease[array_search(1.15, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'HEDHealthHospitalized',
                  'questionCd' => 'H001',
                  'response' => array_search(2.4, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'HEDHealthClaim',
                  'questionCd' => 'H002',
                  'response' => array_search(2.1, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'HEDHealthDeclined',
                  'questionCd' => 'H003',
                  'response' => array_search(2.2, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'HEDHealthCovered',
                  'questionCd' => 'H004',
                  'response' => array_search(2.3, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'HEDCareleafPA',
                  'questionCd' => 'P010',
                  'response' => 'NO'
                ],
                [
                  'questionSetCd' => 'PEDSmokeDetails',
                  'questionCd' => '504',
                  'response' => array_search(3.1, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDSmokeDetails',
                  'questionCd' => 'SmokeExistingSince',
                  'response' => array_search(3.1, $did) !== false ? $disease[array_search(3.1, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDSmokeDetails',
                  'questionCd' => 'OtherSmokeDetails',
                  'response' => array_search(3.1, $did) !== false ? $disease[array_search(3.1, $did)]['quantity'] : ''
                ]
              ],
              'relationCd' => $relationCode[strtoupper($user1->proposer)],
              'roleCd' => 'PRIMARY',
              'titleCd' => $userData['title'],
              'prevInsurerClientId' => $dataport['policynumber'] ?? '',  // "09580",
            ];
          break;
        }
      }

      // dd($insures);
      if (count($members) > 0) {
        $guid = '';
        foreach ($members as $k => $member) {
          $disease = !empty($member->ped) ? json_decode($member->ped, true) : [];
          $diseaseCount = count($disease);
          $did = [];
          foreach ($disease as $key => $value) {
            $did[] = $value['did'];
          }
          if ($member->insureid != '0' && $member->relation != 'self') {
            $namePart = explode(' ', $member->name);
            $fname = isset($namePart[0]) ? $namePart[0] : 'Unknown';
            $lname = count($namePart) > 1 ? end($namePart) : 'akka';
            $relationCd = '';
            if (strtoupper($member->relation) == array_key_exists(strtoupper($member->relation), $relationCode)) {
              $relationCd = $relationCode[strtoupper($member->relation)];
            }
            $guid = 'QN623799' . ($k + 4);
            $insures[] = [
              'birthDt' => str_replace('-', '/', $member->dob),
              'firstName' => $fname,
              'genderCd' => $member->gender,
              'guid' => $guid,
              'ckycNumber' => $member->relation == 'self' && GetCache($cacheckyctype) != 'o' ? $ckycnumber : '',
              'ckyc' => $member->relation == 'self' && GetCache($cacheckyctype) != 'o' ? 'YES' : 'No',
              'height' => (30 * $member->height),
              'weight' => $member->weight,
              'lastName' => $lname,
              'partyBankDetails' => [
                'bankAccountNumber' => self::cbcConvertor($userData['bankAccount']),
                'bankIFSCCode' => self::cbcConvertor($userData['bankIFSC']),
              ],
              'partyAddressDOList' => [
                [
                  'addressLine1Lang1' => '',
                  'addressLine2Lang1' => '',
                  'addressTypeCd' => 'PERMANENT',
                  'areaCd' => '',
                  'cityCd' => '',
                  'stateCd' => '',
                  'pinCode' => $commaddress->sameAddress == '1' ? $userData['pincode'] : $commaddress->commcurrentpincode,
                ],
                [
                  'addressLine1Lang1' => '',
                  'addressLine2Lang1' => '',
                  'addressTypeCd' => 'COMMUNICATION',
                  'areaCd' => '',
                  'cityCd' => '',
                  'pinCode' => $commaddress->sameAddress == '1' ? $userData['pincode'] : $commaddress->commcurrentpincode,
                  'stateCd' => ''
                ]
              ],
              'partyContactDOList' => [
                [
                  'contactNum' => $userData['mobile'],
                  'contactTypeCd' => 'MOBILE',
                  'stdCode' => '+91'
                ]
              ],
              'partyEmailDOList' => [
                [
                  'emailAddress' => $userData['email'],
                  'emailTypeCd' => 'PERSONAL'
                ]
              ],
              'partyIdentityDOList' => [
                [
                  'identityTypeCd' => 'PAN',
                  'identityNum' => $userData['panId']
                ]
              ],
              'partyQuestionDOList' => [
                [
                  'questionSetCd' => 'yesNoExist',
                  'questionCd' => 'pedYesNo',
                  'response' => $diseaseCount > 0 ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDcancerDetails',
                  'questionCd' => '114',
                  'response' => array_search(1.1, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDcancerDetails',
                  'questionCd' => 'cancerExistingSince',
                  'response' => array_search(1.1, $did) !== false ? $disease[array_search(1.1, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDcardiacDetails',
                  'questionCd' => '143',
                  'response' => array_search(1.2, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDcardiacDetails',
                  'questionCd' => 'cardiacExistingSince',
                  'response' => array_search(1.2, $did) !== false ? $disease[array_search(1.2, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDhyperTensionDetails',
                  'questionCd' => '207',
                  'response' => array_search(1.3, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDhyperTensionDetails',
                  'questionCd' => 'hyperTensionExistingSince',
                  'response' => array_search(1.3, $did) !== false ? $disease[array_search(1.3, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDRespiratoryDetails',
                  'questionCd' => '250',
                  'response' => array_search(1.4, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDRespiratoryDetails',
                  'questionCd' => 'respiratoryExistingSince',
                  'response' => array_search(1.4, $did) !== false ? $disease[array_search(1.4, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDEndoDetails',
                  'questionCd' => '222',
                  'response' => array_search(1.5, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDEndoDetails',
                  'questionCd' => 'EndocriExistingSince',
                  'response' => array_search(1.5, $did) !== false ? $disease[array_search(1.5, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDdiabetesDetails',
                  'questionCd' => '205',
                  'response' => array_search(1.6, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDdiabetesDetails',
                  'questionCd' => 'diabetesExistingSince',
                  'response' => array_search(1.6, $did) !== false ? $disease[array_search(1.6, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDkidneyDetails',
                  'questionCd' => '129',
                  'response' => array_search(1.9, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDkidneyDetails',
                  'questionCd' => 'kidneyExistingSince',
                  'response' => array_search(1.9, $did) !== false ? $disease[array_search(1.9, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDliverDetails',
                  'questionCd' => '232',
                  'response' => array_search(1.8, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDliverDetails',
                  'questionCd' => 'liverExistingSince',
                  'response' => array_search(1.8, $did) !== false ? $disease[array_search(1.8, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDparalysisDetails',
                  'questionCd' => '164',
                  'response' => array_search(1.7, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDparalysisDetails',
                  'questionCd' => 'paralysisExistingSince',
                  'response' => array_search(1.7, $did) !== false ? $disease[array_search(1.7, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDjointpainDetails',
                  'questionCd' => '105',
                  'response' => array_search('1.10', $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDjointpainDetails',
                  'questionCd' => 'jointpainExistingSince',
                  'response' => array_search('1.10', $did) !== false ? $disease[array_search('1.10', $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDcongenitalDetails',
                  'questionCd' => '122',
                  'response' => array_search(1.11, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDcongenitalDetails',
                  'questionCd' => 'congenitalExistingSince',
                  'response' => array_search(1.11, $did) !== false ? $disease[array_search(1.11, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDHivaidsDetails',
                  'questionCd' => '147',
                  'response' => array_search(1.12, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDHivaidsDetails',
                  'questionCd' => 'hivaidsExistingSince',
                  'response' => array_search(1.12, $did) !== false ? $disease[array_search(1.12, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDotherDetails',
                  'questionCd' => '210',
                  'response' => array_search(1.13, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDotherDetails',
                  'questionCd' => 'otherExistingSince',
                  'response' => array_search(1.13, $did) !== false ? $disease[array_search(1.13, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDotherDetails',
                  'questionCd' => 'otherDiseasesDescription',
                  'response' => array_search(1.13, $did) !== false ? $disease[array_search(1.13, $did)]['des'] : ''
                ],
                [
                  'questionSetCd' => 'PEDillnessDetails',
                  'questionCd' => '502',
                  'response' => array_search(1.14, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDillnessDetails',
                  'questionCd' => 'illnessExistingSince',
                  'response' => array_search(1.14, $did) !== false ? $disease[array_search(1.14, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDSurgeryDetails',
                  'questionCd' => '503',
                  'response' => array_search(1.15, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDSurgeryDetails',
                  'questionCd' => 'SurgeryExistingSince',
                  'response' => array_search(1.15, $did) !== false ? $disease[array_search(1.15, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'HEDHealthHospitalized',
                  'questionCd' => 'H001',
                  'response' => array_search(2.4, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'HEDHealthClaim',
                  'questionCd' => 'H002',
                  'response' => array_search(2.1, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'HEDHealthDeclined',
                  'questionCd' => 'H003',
                  'response' => array_search(2.2, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'HEDHealthCovered',
                  'questionCd' => 'H004',
                  'response' => array_search(2.3, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'HEDCareleafPA',
                  'questionCd' => 'P010',
                  'response' => 'NO'
                ],
                [
                  'questionSetCd' => 'PEDSmokeDetails',
                  'questionCd' => '504',
                  'response' => array_search(3.1, $did) !== false ? 'YES' : 'NO'
                ],
                [
                  'questionSetCd' => 'PEDSmokeDetails',
                  'questionCd' => 'SmokeExistingSince',
                  'response' => array_search(3.1, $did) !== false ? $disease[array_search(3.1, $did)]['date'] : ''
                ],
                [
                  'questionSetCd' => 'PEDSmokeDetails',
                  'questionCd' => 'OtherSmokeDetails',
                  'response' => array_search(3.1, $did) !== false ? $disease[array_search(3.1, $did)]['quantity'] : ''
                ]
              ],
              'relationCd' => $relationCd,
              'roleCd' => 'PRIMARY',
              'titleCd' => $member->gender == 'MALE' ? 'MR' : 'MS',
              'prevInsurerClientId' => $dataport['policynumber'] ?? '',  // "09580",
            ];
          }
        }
      }

      // $data = $journeydata->portdata;

      $insuredId = $dataport['insurername'];
      $insured = UltimateInsurerMaster::where('id', $insuredId)->value('lookupdatacd');

      foreach ($members as $k => $member) {
        $clientId = $dataport['policynumber'];
        if ($member->relation == 'self') {
          $portdata[] = [
            'guid' => 'NO',
            'expPolicyCumulativeBonus' => '0',  // $dataport['prepolicycumbonus'] ?? "0",
            'expPolicySA' => $dataport['prepolicysuminsured'] ?? '1000000',
            'clientId' => $clientId ?? '',
            'memberAttachmentDt' => str_replace('-', '/', $member->attachmentdate) ?? '',  // "22/11/2024",
            'memberPresentOnPolicy' => 'YES',
            'expPolicyInsurerName' => $insured,
            'expPolicyNum' => $dataport['policynumber'] ?? '',
            'expPolicyStartDt' => str_replace('-', '/', $dataport['prepolicyexpirydate']) ?? '',
            'expPolicyEndDt' => str_replace('-', '/', $dataport['policyenddate']) ?? '',
            'expPolicyTypeCd' => strtoupper($dataport['prepolicytype']) ?? '',
            'firstExpPolicyNum' => $dataport['policynumber'] ?? '',
            'firstExpPolicyStartDt' => str_replace('-', '/', $dataport['firstpolicystartdate']) ?? '',
            'portabilityReason' => 'PC'
          ];
        }
      }

      if (count($members) > 0) {
        foreach ($members as $k => $member) {
          if ($member->insureid != '0' && $member->relation != 'self') {
            $relationCd = '';
            if (strtoupper($member->relation) == array_key_exists(strtoupper($member->relation), $relationCode)) {
              $relationCd = $relationCode[strtoupper($member->relation)];
            }
            $guid = $k++;
            $clientId = self::incrementPolicyNumber($clientId);

            $portdata[] = [
              'guid' => $guid,
              'expPolicyCumulativeBonus' => $dataport['prepolicycumbonus'] ?? '0',
              'expPolicySA' => $dataport['prepolicysuminsured'] ?? '1000000',
              'clientId' => $clientId ?? '',  // $clientId,
              'memberAttachmentDt' => str_replace('-', '/', $member->attachmentdate) ?? '',
              'memberPresentOnPolicy' => 'YES',
              'expPolicyInsurerName' => $insured ?? '',
              'expPolicyNum' => $dataport['policynumber'] ?? '',
              'expPolicyStartDt' => str_replace('-', '/', $dataport['prepolicyexpirydate']),
              'expPolicyEndDt' => str_replace('-', '/', $dataport['policyenddate']) ?? '',
              'expPolicyTypeCd' => strtoupper($dataport['prepolicytype']) ?? '',
              'firstExpPolicyNum' => $dataport['firstpolicynumber'] ?? '',
              'firstExpPolicyStartDt' => str_replace('-', '/', $dataport['firstpolicystartdate']) ?? '',
              'portabilityReason' => 'PC'
            ];
            $clientId++;
          }
        }
      }

      // return $portdata;

      $today = Carbon::now()->format('d/m/Y');
      $formattedDob = str_replace('-', '/', $nominee->dob);
      //  $clientId = rand(100000, 999999);
      $post = [
        'intPolicyDataIO' => [
          'policy' => [
            'businessTypeCd' => 'ROLLOVER',
            'baseProductId' => self::$productId,
            'baseAgentId' => self::$agentid,
            'coverType' => $covertype,
            'partyDOList' => $insures,
            'policyAdditionalFieldsDOList' => [
              [
                'field1' => 'Partner_NB_DigiBima',
                'field10' => $nominee->name,
                'field12' => strtoupper(explode('(', $nominee->relation)[0]),
                'field9' => (strtoupper($nominee->gender) == 'MALE') ? 'MR' : 'MS',
                'field17' => $formattedDob,
                'fieldAgree' => 'YES',
                'fieldAlerts' => 'YES',
                'fieldTc' => 'YES'
              ]
            ],
            'sumInsured' => $sumInsured,
            'term' => $tenure,
            'isPremiumCalculation' => 'YES',
            'addOns' => $csaddonCode,
            'policyPortabilityDO' => [
              'expPolicyBusinessTypeCd' => 'RET',
              'portabilityFromOtherInsurer' => '01',
              'sameAsExpPolicyFl' => 'NO',
              'expPolicyTerm' => '2',
              'periodLapsed' => '1',
              'expDtForExtendedPolicy' => 'NO',
              'isClaimedFl' => 'NO',
              'guid' => '1',
              'expPolicyPlanName' => $dataport['prepolicyplanname'],
              'expProductId' => $dataport['prepolicyplanname'],
              'receivedDt' => $today,
              'sentDt' => $today
            ],
            'policyPortabilityMemberDOList' => $portdata,
            // "policyPortabilityMemberDOList" =>
            //   [
            //     [
            //       "guid" => "NO",
            //       "expPolicyCumulativeBonus" => "0",
            //       "expPolicySA" => "1000000",
            //       "clientId" => $dataport['policynumber'],
            //       "memberAttachmentDt" => "22/11/2024",
            //       "memberPresentOnPolicy" => "YES",
            //       "expPolicyInsurerName" => "1008",
            //       "expPolicyNum" => "POLICY12345",
            //       "expPolicyStartDt" => "20/11/2024",
            //       "expPolicyEndDt" => "20/11/2025",
            //       "expPolicyTypeCd" => "INDIVIDUAL",
            //       "firstExpPolicyNum" => "POLICY12345",
            //       "firstExpPolicyStartDt" => "22/11/2024",
            //       "portabilityReason" => "PC"
            //     ]
            //   ],
            // "policyPortabilityMemberClaimDOList" => [
            //   [
            //     "amountPaid" => "50000",
            //     "claimReason" => "X1132",
            //     "clientId" => "09579",
            //     "paymentDt" => "11/05/2021",
            //     "visitDt" => "11/05/2021",
            //     "claimOutstandingFl" => "NO",
            //     "previousClaimNum" => "306",
            //     "hospitalId" => "000001"
            //   ]
            // ]
          ]
        ]
      ];
      // return $post;

      $postData = json_encode($post);

      // dd($postData);
      // try {
      $curl = curl_init();
      curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.careinsurance.com/relinterfacerestful/religare/secure/restful/createPolicyPortability',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_HTTPHEADER => [
          'appId: ' . self::$appId,
          'signature:  ' . self::$signature,
          'timestamp: ' . self::$timestamp,
          'sessionId: ' . $sessionid,
          'tokenId: ' . $tokenid,
          'applicationCD: ' . self::$applicationCD,
          'Content-Type: application/json'
        ],
      ]);
      $response = curl_exec($curl);

      // $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
      // $headers = substr($response, 0, $header_size);
      // $body = substr($response, $header_size);
      // dd($response);
      // return $headers;
      Log::info(['Response' => $response]);
      return $response;
      // return json_decode($response, true);
    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }

  public static function incrementPolicyNumber($policyNumber)
  {
    if (preg_match('/(\d+)$/', $policyNumber, $matches)) {
      $number = $matches[1];
      $length = strlen($number);

      $incremented = str_pad($number + 1, $length, '0', STR_PAD_LEFT);

      return preg_replace('/\d+$/', $incremented, $policyNumber);
    }

    return $policyNumber;  // if no number found
  }

  public static function portpropsal()
  {
    $token = json_decode(self::generatePartnerToken('147', ''));

    // dd($token);
    $sessionid = $token->sSessionID;
    $tokenid = $token->tokenid;
    return ['session' => $sessionid, 'tokenid' => $tokenid];
  }

  public static function checkPanVerify($userId, $panid, $dob, $mobile, $gender, $name)
  {
    try {
      $mobileNumber = $mobile;
      // $mobileNumber = '9199901992';
      $partnerCode = '20008325';

      $aesKey = self::$aeskey;
      $iv = self::$iv;

      $referenceId = substr(bin2hex(random_bytes(8)), 0, 14);
      $cachereference = 'cache_' . $userId;
      SetCache($cachereference, $referenceId);
      // return $referenceId;

      $publicKeyBase64 = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAvVbPGoHUov0/T17BdHR3LlRkVyprNhdy8eu8HH6bo166ka9QibPmoRYS2PqpbYt3nh5uR66aDC0xVhwOcljdhrZaOOu0CVWSBH1v6lSOPnR44X1Irfmwdwkvj8mRuh0lDm5E3tLG7Ng8MYa0DssX92ZnRixuVWelHdNnB48Hr3puHGfT76ptjB9l4ljuq90hf1Mfnjx3TN9ceg6exFzKjGRV7e0YViTOjmPOUoAdSzdbYLZErHamgTCVuH72NfaXxx3Hxf0euHNV36uM9jgLZAgryftkOsnzJDif0aQ+yXJsMNsgrBbmq5rDQ9Kv5t9ODGi+MA8yJze/rrBEOWmccwIDAQAB';

      $requestData = [
        'mobileNumber' => $mobileNumber,
        'referenceId' => $referenceId,  // "d1c6371484d056",
        'dob' => $dob,
        'idNumber' => $panid,
        'idType' => 'pan',
        'consent' => 'yes',
        'gender' => $gender,
        'name' => $name
      ];
      // $requestData = [
      //   "mobileNumber" => $mobileNumber,
      //   "referenceId" => substr(bin2hex(random_bytes(8)), 0, 14),
      //   "dob" => "28/06/1998",
      //   "idNumber" => "JBSPK8786H",
      //   "idType" => "pan",
      //   "consent" => "yes",
      //   "gender" => "MALE",
      //   "name" => "Dheeraj Kumar"
      // ];

      $requestJson = json_encode($requestData, JSON_UNESCAPED_SLASHES);

      $encryptedRequest = openssl_encrypt(
        $requestJson,
        'AES-256-CBC',
        $aesKey,
        OPENSSL_RAW_DATA,
        $iv
      );

      if ($encryptedRequest === false) {
        throw new Exception('AES encryption failed');
      }

      $encryptedRequestBase64 = base64_encode($encryptedRequest);

      $secretKeyString = $aesKey . '|' . $iv;

      $publicKeyPem =
        "-----BEGIN PUBLIC KEY-----\n"
        . chunk_split($publicKeyBase64, 64, "\n")
        . '-----END PUBLIC KEY-----';

      if (
        !openssl_public_encrypt(
          $secretKeyString,
          $encryptedSecretKey,
          $publicKeyPem,
          OPENSSL_PKCS1_PADDING
        )
      ) {
        throw new Exception('RSA encryption failed');
      }
      $encryptedSecretKeyBase64 = base64_encode($encryptedSecretKey);

      $cleanJson = preg_replace('/\s+/', '', $requestJson);
      $checksum = hash_hmac('sha256', $cleanJson, $iv);

      // $appId = 'appId_' . hash_hmac('sha256', $mobileNumber, $iv);

      $appIdHash = hash_hmac('sha256', $mobileNumber, $secretKeyString);
      $appId = '872788_' . $appIdHash;

      $payload = json_encode([
        'secretKey' => $encryptedSecretKeyBase64,
        'requestData' => $encryptedRequestBase64
      ]);

      $headers = [
        'Content-Type: application/json',
        "appId: {$appId}",
        "checksum: {$checksum}",
        "partnerCode: {$partnerCode}"
      ];

      // return [
      //   'headers' => $headers,
      //   'payload' => $payload
      // ];

      $curl = curl_init();
      curl_setopt_array($curl, [
        CURLOPT_URL => 'https://ix.careinsurance.com/connect-service/kyc/v3/otp/generate',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => $headers
      ]);

      $response = curl_exec($curl);

      if (curl_errno($curl)) {
        throw new Exception(curl_error($curl));
      }

      curl_close($curl);
      // return $response;
      $responseArray = json_decode($response, true);

      // Decrypt only responseData
      if (isset($responseArray['responseData'])) {
        $responseArray['responseData'] = self::decryptResponseData(
          $responseArray['responseData'],
          $aesKey,
          $iv
        );
      }

      return $responseArray;
    } catch (Exception $e) {
      return [
        'status' => false,
        'error' => $e->getMessage()
      ];
    }
  }

  public static function verifyOtp($userId, $mobile, $otp)
  {
    try {
      $aesKey = self::$aeskey;
      $iv = self::$iv;

      $mobileNumber = $mobile;
      $partnerCode = '20008325';
      $cachereference = 'cache_' . $userId;
      $referenceId = getCache($cachereference);

      $requestData = [
        'otp' => $otp,
        'mobileNumber' => $mobileNumber,
        'referenceId' => $referenceId,
      ];

      $requestJson = json_encode($requestData, JSON_UNESCAPED_SLASHES);

      $encryptedRequest = openssl_encrypt(
        $requestJson,
        'AES-256-CBC',
        $aesKey,
        OPENSSL_RAW_DATA,
        $iv
      );

      if ($encryptedRequest === false) {
        throw new Exception('AES encryption failed');
      }

      $requestDataBase64 = base64_encode($encryptedRequest);

      $secretKeyString = $aesKey . '|' . $iv;

      $publicKeyBase64 = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAvVbPGoHUov0/T17BdHR3LlRkVyprNhdy8eu8HH6bo166ka9QibPmoRYS2PqpbYt3nh5uR66aDC0xVhwOcljdhrZaOOu0CVWSBH1v6lSOPnR44X1Irfmwdwkvj8mRuh0lDm5E3tLG7Ng8MYa0DssX92ZnRixuVWelHdNnB48Hr3puHGfT76ptjB9l4ljuq90hf1Mfnjx3TN9ceg6exFzKjGRV7e0YViTOjmPOUoAdSzdbYLZErHamgTCVuH72NfaXxx3Hxf0euHNV36uM9jgLZAgryftkOsnzJDif0aQ+yXJsMNsgrBbmq5rDQ9Kv5t9ODGi+MA8yJze/rrBEOWmccwIDAQAB';

      $publicKeyPem =
        "-----BEGIN PUBLIC KEY-----\n"
        . chunk_split($publicKeyBase64, 64, "\n")
        . '-----END PUBLIC KEY-----';

      if (
        !openssl_public_encrypt(
          $secretKeyString,
          $rsaEncrypted,
          $publicKeyPem,
          OPENSSL_PKCS1_PADDING
        )
      ) {
        throw new Exception('RSA encryption failed');
      }

      $secretKeyBase64 = base64_encode($rsaEncrypted);

      $cleanJson = preg_replace('/\s+/', '', $requestJson);
      $checksum = hash_hmac('sha256', $cleanJson, $iv);

      $appIdHash = hash_hmac('sha256', $mobileNumber, $secretKeyString);
      $appId = '872788_' . $appIdHash;

      $payload = json_encode([
        'secretKey' => $secretKeyBase64,
        'requestData' => $requestDataBase64
      ]);

      $headers = [
        'Content-Type: application/json',
        "appId: {$appId}",
        "checksum: {$checksum}",
        "partnerCode: {$partnerCode}"
      ];

      $curl = curl_init();
      curl_setopt_array($curl, [
        CURLOPT_URL => 'https://ix.careinsurance.com/connect-service/kyc/v3/otp/verify',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 30,
      ]);

      $response = curl_exec($curl);

      if (curl_errno($curl)) {
        throw new Exception(curl_error($curl));
      }

      curl_close($curl);
      $responseArray = json_decode($response, true);

      if (isset($responseArray['responseData'])) {
        $responseArray['responseData'] = self::decryptResponseData(
          $responseArray['responseData'],
          $aesKey,
          $iv
        );
      }

      return $responseArray;
      // return $response;
    } catch (Exception $e) {
      return [
        'status' => false,
        'error' => $e->getMessage()
      ];
    }
  }

  public static function decryptResponseData($responseData, $aesKey, $iv)
  {
    return json_decode(
      openssl_decrypt(
        base64_decode($responseData),
        'AES-256-CBC',
        $aesKey,
        OPENSSL_RAW_DATA,
        $iv
      ),
      true
    );
  }

  public static function verifyUploadDocumentPort($filePath, $docType, $proposalNum, $sessionid, $tokenid)
  {
    // self::initialize();
    $parts = explode('/', $filePath);
    $filename = end($parts);
    // dd($filename);
    if (isset($filename)) {
      if (file_exists($filePath)) {
        $fileContents = file_get_contents($filePath);
        $base64EncodedFile = base64_encode($fileContents);
        try {
          $payload = json_encode([
            'potabilityDocument' => [
              'proposalNo' => $proposalNum,
              'branchCode_branchName' => 'NEWFAVEO',
              'product' => 'Ultimate Care',
              'businessType' => 'ROLLOVER',
              'documentName' => $docType . '_' . $filename,
              'isBase64' => 'Y',
              'documentData' => $base64EncodedFile
            ]
          ]);

          // return $payload;

          $curl = curl_init();
          curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.careinsurance.com/relinterfacerestful/religare/secure/restful/uploadPortabilityDocument',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
              'appId: ' . self::$appId,
              'signature:  ' . self::$signature,
              'timestamp: ' . self::$timestamp,
              'sessionId: ' . $sessionid,
              'tokenId: ' . $tokenid,
              'applicationCD: ' . self::$applicationCD,
              'Content-Type: application/json'
            ],
          ]);

          $response = curl_exec($curl);

          Log::info(['Response' => $response]);
          curl_close($curl);
          return $response;
        } catch (\Exception $e) {
          echo '' . $e->getMessage();
        }
        return $base64EncodedFile;
      } else {
        echo 'File not found ';
        die;
      }
    } else {
      throw new Exception('File not found.');
    }
  }
}
