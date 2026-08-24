<?php
// ----------------------------------test---------------------------
namespace App\Services\Api;

use App\Http\Controllers\front\health\caresupreme\CareSupremeController;
use App\Models\CareToken;
use App\Models\HealthJourney;
use App\Models\Insure;
use App\Models\JourneyUsers;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Log};
use Illuminate\Support\Facades\{Cache};
use Illuminate\Support\Facades\Session;
use DateTime;

class CareSupremeService
{
  // private static $partnerId = "1001101"; //"872788";
  // private static $partnerId = "872788"; //"872788";
  // //private static $appId = "1001101";//"872788";
  // private static $appId = "872788";//"872788";
  // //private static $timestamp = "1736157011820"; //"1706613898969";
  // private static $timestamp = "1706613898969"; //"1706613898969";
  // //private static $securityKey = "KzZMOHQrUGYwZFJZZThtZkFWWEE1Zm9lT05ZWTZHV2x4bDM5T05nb09LL3diWmlDckdmcFh1ZmJSNW5oVDhkdw==";
  // private static $securityKey = "dkpBQ0Q3cGVGb1NXVnNsWW1EaERWb0ErQVFyTGFhSytNZCtrVzdzRGtrOW1DWktaTDdwWHRWdVZoYnpyV1JseA==";
  // private static $aeskey = "z5yK1lw7XYt6YKdP7Pne2Jw3zRkMAziH";
  // private static $iv = "i0kbCAlFTlDXshYV";
  // private static $applicationCD = "PARTNERAPP";
  // private static $signature = "R+GdzmW7vnvSX5MekKa"; //"R+GdzmW7vnvSX5MekKa";
  // // private static $signature = "g0erUaIKutEFSxQtm2zHjJo"; //"R+GdzmW7vnvSX5MekKa";
  // private static $agentid = "20008325"; //"20008325", //20689274

  private static $partnerId = '1001101';
  private static $appId = '1001101';
  private static $timestamp = '1736157011820';
  private static $securityKey = 'KzZMOHQrUGYwZFJZZThtZkFWWEE1Zm9lT05ZWTZHV2x4bDM5T05nb09LL3diWmlDckdmcFh1ZmJSNW5oVDhkdw==';
  private static $aeskey = 'z5yK1lw7XYt6YKdP7Pne2Jw3zRkMAziH';
  private static $iv = 'i0kbCAlFTlDXshYV';
  private static $applicationCD = 'PARTNERAPP';
  private static $signature = 'g0erUaIKutEFSxQtm2zHjJo';
  private static $agentid = '20689274';

  public static function managePartnerToken(Request $request, $data = null)
  {
    $bFlag = true;
    $userId = $request->userid;
    $data = json_decode($data, true);
    $status = $data['responseData']['status'];
    if ($status == 'fail') {
      $error = $data['responseData']['message'];
      if (
        isset(explode(' ', strtolower($error))[0]) &&
        explode(' ', strtolower($error))[0] == 'session' ||
        explode(' ', strtolower($error))[0] == 'token'
      ) {
        $bFlag = false;
        $existingToken = CareToken::where('userid', $userId)->first();
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

  public static function getTokenOnly()
  {
    // "securityKey"   : "KzZMOHQrUGYwZFJZZThtZkFWWEE1Zm9lT05ZWTZHV2x4bDM5T05nb09LL3diWmlDckdmcFh1ZmJSNW5oVDhkdw=="
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.careinsurance.com/relinterfacerestful/religare/secure/restful/generatePartnerToken',
      // CURLOPT_URL => 'https://apiuat.careinsurance.com/relinterfacerestful/religare/secure/restful/generatePartnerToken',
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
                "securityKey"   : "KzZMOHQrUGYwZFJZZThtZkFWWEE1Zm9lT05ZWTZHV2x4bDM5T05nb09LL3diWmlDckdmcFh1ZmJSNW5oVDhkdw=="
            }
        }',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'appId: ' . self::$appId,
        'signature: ' . self::$signature,
        'timestamp: 1706613898969 ',  //  1736157011820
        'applicationCD: ' . self::$applicationCD
      ),
    ));
    // dd($curl);
    $response = curl_exec($curl);
    return $response;
  }

  public static function generatePartnerToken(Request $request, $id = '')
  {
    $sSessionID = ' ';
    $tokenId = ' ';
    try {
      $userId = $request->userid;
      $tokendata = CareToken::where('userid', '=', $userId)->first();

      if ($tokendata != null && $tokendata->partner_token != '0' && $tokendata->partner_token != null && $tokendata->token_number <= 23 && $tokendata->updated_at->diffInHours(Carbon::now()) <= 2) {
        if ($tokendata) {
          $tokendata->token_number = ($tokendata->token_number) + 1;
          $tokendata->updated_at = now();
          $tokendata->save();
        }
        $sSessionID = json_decode($tokendata->sessionid);
        $aToken = json_decode($tokendata->partner_token);
        $tokenKey = $aToken[$tokendata->token_number]->tokenKey;
        $tokenValue = $aToken[$tokendata->token_number]->tokenValue;
        $tokenId = self::generateTokenID($tokenKey, $tokenValue);
      } else {
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.careinsurance.com/relinterfacerestful/religare/secure/restful/generatePartnerToken',
          // CURLOPT_URL => 'https://apiuat.careinsurance.com/relinterfacerestful/religare/secure/restful/generatePartnerToken',
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
                "securityKey"   : "KzZMOHQrUGYwZFJZZThtZkFWWEE1Zm9lT05ZWTZHV2x4bDM5T05nb09LL3diWmlDckdmcFh1ZmJSNW5oVDhkdw=="
            }
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'appId: ' . self::$appId,
            'signature: ' . self::$signature,
            'timestamp: 1706613898969 ',  //  1736157011820
            'applicationCD: ' . self::$applicationCD
          ),
        ));
        // dd($curl);
        $response = curl_exec($curl);
        // dd($response);
        Log::info(['Response' => $response]);
        curl_close($curl);
        if ($response == false) {
          return false;
        }
        $aToken = json_decode($response);
        $existingToken = CareToken::where('userid', $userId)->first();
        if ($existingToken) {
          $existingToken->token_number = 1;
          $existingToken->sessionid = json_encode($aToken->partnerTokenGeneratorInputIO->sessionId);
          $existingToken->partner_token = json_encode($aToken->partnerTokenGeneratorInputIO->listOfToken);
          $existingToken->updated_at = now();
          $existingToken->save();
        } else {
          $obj = new CareToken();
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
    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage()
      ]);
      // echo '' . $e->getMessage();
    }

    return json_encode([
      'sSessionID' => $sSessionID,
      'tokenid' => $tokenId
    ]);
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
        // CURLOPT_URL => 'https://apiuat.careinsurance.com/relinterfacerestful/religare/secure/restful/ckycDownload',
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
      return response()->json([
        'status' => false,
        'error' => $e->getMessage()
      ]);
      // echo '' . $e->getMessage();
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
        // CURLOPT_URL => 'https://apiuat.careinsurance.com/relinterfacerestful/religare/secure/restful/aadharCKYC',
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
      $userId = $params['userId'];
      $pincode = $params['pincode'];
      $nom = $params['nom'] ?? '';
      $covertype = $params['covertype'] ?? '';
      $noc = $params['noc'] ?? '';
      $eldest = $params['eldest'] ?? '';
      $coverage = $params['coverage'];
      $time = $params['tenure'] ?? '1';
      $ped_tenure = $params['ped_tenure'] ?? '';
      $aa = $params['aa'] ?? '0';
      $wb = $params['wb'] ?? '0';
      $ncb = $params['ncb'] ?? '0';
      $ped = $params['ped'] ?? '0';
      $opd = $params['opd'] ?? '0';
      $cs = $params['cs'] ?? '0';
      $ic = $params['ic'] ?? '0';
      $ahc = $params['ahc'] ?? '0';
      $bfb = $params['bfb'] ?? '0';
      $uc = $params['uc'] ?? '0';
      // $csp = $params['csp'] ?? '0';
      // $cbb = $params['cbb'] ?? '0';
      // $icp = $params['icp'] ?? '0';
      // $opdp = $params['opdp'] ?? '0';
      $icval = $params['icval'] ?? '';
      $ncbval = $params['ncbval'] ?? '';
      $csval = $params['csval'] ?? '';
      $opdval = $params['opdval'] ?? '';
      $data = Insure::where('proposalid', $userId)->get();

      $nom = $data->count();
      $noc = $data->filter(function ($item) {
        return ($item->age < 25) && ($item->name == 'Son' || $item->name == 'Daughter');
      })->count();
      // $aData = $data->sortByDesc('age')->toArray();
      $aData = $data->sortByDesc('age')->values();
      $memberName = [];
      foreach ($data as $member) {
        $memberName[] = strtolower($member->name);
      }

      $covertype = (in_array('self', $memberName) && $nom > 1) ? 'Floater' : 'Individual';

      $authID = self::getBasicPlanToken();

      $authValue = json_decode($authID)->data->accessToken ?? '';

      $arr1 = [];
      // foreach ($aData as $key => $row) {
      //   if ($key == 0)
      //     continue;
      //   $arr1["newMem_" . ($key + 1)] = $row['age'];
      // }
      foreach ($aData->slice(1)->values() as $index => $row) {
        $arr1['newMem_' . ($index + 2)] = $row->age;  // if using Eloquent models
        // $arr1["newMem_" . ($index + 1)] = $row['age']; // if using array
      }

      $arr2 = [
        'partnerId' => '498',
        'abacusId' => '6008',
        'postedField' => array_merge(
          $arr1,
          [
            'field_54' => $pincode,
            'field_9' => $covertype,
            'field_1' => $nom,
            'field_10' => $noc,
            'field_3' => $aData[0]['age'] ?? '',
            'field_2' => $coverage,
            'field_4' => $time . ' Year',
            'outPutField' => 'field_8',
            'field_35' => $aa,
            'field_WB' => $wb,
            'field_NCB' => $ncb,
            'field_NCB_Value' => $ncbval,
            'field_AHC' => $ahc,
            'field_IC' => $ic,
            'field_IC_Value' => $icval,
            'field_CS' => $cs,
            'field_CS_Value' => $csval,
            'field_OPD' => $opd,
            'field_OPD_Value' => $opdval,
            'field_43' => $ped,
            'field_BFB' => $bfb,
            'field_UC' => $uc,
            'field_PED_TENURE' => $ped_tenure ? $ped_tenure . ' Year' : '',
          ]
        ),
      ];

      // "field_NCB_Value" => $ncb == 1 ? ($cbb == 1 ? "CB Booster" : "Cumulative Bonus Super") : '',
      $requestdata = json_encode($arr2);
      // return response()->json([
      //   'req' => $requestdata
      // ]);
      // return $requestdata;
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
          'Authorization: Bearer ' . $authValue,
          'Content-Type: application/json',
        ],
      ]);

      $response = curl_exec($curl);
      Log::info(['Care_Abacus_Response' => $response]);
      curl_close($curl);

      return $response;
    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }

  public static function cbcConvertor($param)
  {
    $loaclaesKey = self::$aeskey;
    $loacliv = self::$iv;
    $encrypted = openssl_encrypt(
      $param,
      'aes-256-cbc',
      $loaclaesKey,
      OPENSSL_RAW_DATA,
      $loacliv
    );
    return base64_encode(base64_encode($encrypted));
  }

  public static function creatPolicy(Request $request, $sessionid, $tokenid)
  {
    try {
      $userId = $request->userid;
      $user = User::find($userId);

      $user1 = HealthJourney::where('userid', $userId)->where('vid', getconstant('HEALTH.CARESUPREME.KEY'))->first();

      // $user1 = HealthJourney::where('userid', $userId)->first();
      // $commaddress = json_decode($user['communication']);
      $commaddress = json_decode($user1->comunication_address);

      $bankAccount = !empty(json_decode($user1['bank_details'])->account) ? json_decode($user1['bank_details'])->account : 'SACBHJHB';

      $bankIFSC = !empty(json_decode($user1['bank_details'])->ifsc) ? json_decode($user1['bank_details'])->ifsc : 'SDVBHJSJDBV';

      $addons = (is_array($user1) && isset($user1['addon']) && $user1['addon'] !== null)
        ? json_decode($user1['addon'], true)
        : [];

      $csaddonCode = '';
      $cachecompulsory = 'cache_compulsoryaddon_' . $userId;
      $cachedValue = GetCache($cachecompulsory);

      $compulsoryaddon = $cachedValue ?? [];
      // $compulsoryaddon = $cachedValue ?$cachedValue : [];

      $cachekyctype = 'cache_kyctype_' . $userId;
      GetCache($cachekyctype);
      $namePart = GetCache($cachekyctype) == 'o' ? explode(' ', $user->name) : explode(' ', $user1->kyc_name);

      $insures = [];
      $covertype = '';
      $relationCode = [
        'SELF' => 'SELF',
        'HUSBAND' => 'SPSE',
        'WIFE' => 'SPSE',
        'SON' => 'SONM',
        'DAUGHTER' => 'UDTR',
        'FATHER' => 'FATH',
        'MOTHER' => 'MOTH',
        'FATHERINLAW' => 'FLAW',
        'MOTHERINLAW' => 'MLAW'
      ];

      $sumInsuredInd = [
        '5' => '621',  // 621
        '7' => '623',  // 623
        '10' => '625',  // 625
        '15' => '627',  // 627
        '25' => '631',  // 631
        '50' => '633',  // 633
        '100' => '637',  // 637
      ];

      $sumInsuredFlo = [
        '5' => '622',  // 622
        '7' => '624',  // 624
        '10' => '626',  // 626
        '15' => '628',  // 628
        '25' => '632',  // 632
        '50' => '034',  // 634
        '100' => '638',  // 638
      ];
      $addonCode = [
        'aa' => 'AACS1147',  // AACS1147
        'wb' => 'WBS1146',  // WBS1146
        'ncb' => 'NCBS1145',  // NCBS1145
        'ahc' => 'AHCS1144',  // AHCS1144
        'ic' => 'ICS1149',  // ICS1149
        'cs' => 'CS1154',  // CS1154
        'opd' => 'COPD1211',  // COPD1211
        '1' => 'PEDWP1Y1155',  // PEDWP1Y1155
        '2' => 'PEDWP2Y1156',  // PEDWP2Y1156
        'befit' => 'BFS1148',  // BFS1148
        'uc' => '1232',  // 1232
        'csp' => '1231',  // Claim Shield Plus
        'cbb' => '1234',  // CB Booster
        'icp' => '1235',  // Instant Cover Plus
        'opdp' => 'COPDN1223',  // OPD-PLUS
      ];

      $contactDetails = json_decode($user1->contact_details);
      $permanentAddress = json_decode($user1->permanent_address);
      // $commaddress = json_decode($user1->comunication_address);
      $proposerDetails = json_decode($user1->proposer_details);
      // dd($csaddonCode);
      $userData = [
        'dob' => str_replace('-', '/', $user1->dob),
        'gender' => strtoupper($user->gender),
        'height' => $proposerDetails->height,
        'weight' => $proposerDetails->weight,
        'house' => strtoupper($permanentAddress->address1),  // k
        'colony' => strtoupper($permanentAddress->address2),  // k
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

      // dd($userData);
      // ------------------------------------inserting proposar data------------------------------
      // $ckycnumber = session()->get('ckycno');
      $cacheckycno = 'cache_ckycno_' . $userId;
      $ckycnumber = GetCache($cacheckycno);

      $cacheckyctype = 'cache_kyctype_' . $userId;

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
              'addressLine1Lang1' => '1206',
              'addressLine2Lang1' => $userData['house'],
              'addressTypeCd' => 'PERMANENT',
              'areaCd' => $userData['city'],
              'cityCd' => $userData['city'],
              'stateCd' => $userData['state'],
              'pinCode' => $userData['pincode']
            ],
            [
              'addressLine1Lang1' => $commaddress->sameAddress == '1' ? $userData['house'] : $commaddress->commcurrenthouse,
              'addressLine2Lang1' => $commaddress->sameAddress == '1' ? $userData['house'] : $commaddress->commcurrenthouse,
              'addressTypeCd' => 'COMMUNICATION',
              'areaCd' => $commaddress->sameAddress == '1' ? $userData['city'] : $commaddress->commcurrentcity,
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
          'partyQuestionDOList' => []
        ];

      // ------------------------------------end inserting proposar data------------------------------
      // dd($insures);

      $members = JourneyUsers::where('proposalid', $user->id)->where('insureid', '<>', '0')->get();

      $nominee = JourneyUsers::where('proposalid', $user->id)->where('insureid', '0')->first();

      $csaddonCode = implode(',', array_map(function ($value) use ($addonCode) {
        return $addonCode[$value];
      }, $compulsoryaddon));

      foreach ($addons as $k => $value) {
        if (array_key_exists($value, $addonCode)) {
          $csaddonCode .= ',' . $addonCode[$value];
        }
      }
      // dd($csaddonCode);
      // $tenure = session('tenure');
      $cachetenure = 'cache_tenure_' . $userId;
      $tenure = GetCache($cachetenure);

      $memberRelation = [];
      foreach ($members as $key => $member) {
        $memberRelation[] = strtolower($member->relation);
      }
      // $covertype = (in_array('self', $memberRelation) && count($members) > 1) ? 'FAMILYFLOATER' : 'INDIVIDUAL';
      $covertype = count($members) > 1 ? 'FAMILYFLOATER' : 'INDIVIDUAL';
      $sumInsured = '';
      $cachecoverage = 'cache_coverage_' . $userId;
      GetCache($cachecoverage);
      if (count($members) > 1 && array_key_exists(GetCache($cachecoverage), $sumInsuredFlo)) {
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
                  'addressLine1Lang1' => '1206',
                  'addressLine2Lang1' => $userData['house'],
                  'addressTypeCd' => 'PERMANENT',
                  'areaCd' => $userData['city'],
                  'cityCd' => $userData['city'],
                  'stateCd' => $userData['state'],
                  'pinCode' => $userData['pincode']
                ],
                [
                  'addressLine1Lang1' => $commaddress->sameAddress == '1' ? $userData['house'] : $commaddress->commcurrenthouse,
                  'addressLine2Lang1' => $commaddress->sameAddress == '1' ? $userData['house'] : $commaddress->commcurrenthouse,
                  'addressTypeCd' => 'COMMUNICATION',
                  'areaCd' => $commaddress->sameAddress == '1' ? $userData['city'] : $commaddress->commcurrentcity,
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
              // 'partyQuestionDOList'=>[],
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
              'titleCd' => $member->gender == 'MALE' ? 'MR' : 'MS'
            ];
          }
        }
      }
      $postData = json_encode([
        'intPolicyDataIO' => [
          'policy' => [
            'businessTypeCd' => 'NEWBUSINESS',
            'baseProductId' => '10001117',
            'baseAgentId' => self::$agentid,  // "20008325", //20689274
            'coverType' => $covertype,
            'partyDOList' => $insures,
            'policyAdditionalFieldsDOList' => [
              [
                'field1' => 'Partner_NB_DigiBima',
                'field10' => $nominee->name,
                'field12' => strtoupper(explode('(', $nominee->relation)[0]),
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

      Log::info(['caresupreme_createpolicy_request' => $postData]);
      // Log::info(['nominee' => $nominee]);
      // return $postData;
      // dd($postData);
      // try {
      $curl = curl_init();
      curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.careinsurance.com/relinterfacerestful/religare/secure/restful/createPolicy',
        // CURLOPT_URL => 'https://apiuat.careinsurance.com/relinterfacerestful/religare/secure/restful/createPolicy',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => [
          'appId: ' . self::$appId,
          'signature:  ' . self::$signature,
          'timestamp: ' . self::$timestamp,
          'sessionId: ' . $sessionid,
          'tokenId: ' . $tokenid,
          'applicationCD: ' . self::$applicationCD,
          'Content-Type: application/json'
        ],
        CURLOPT_POSTFIELDS => $postData
      ]);
      $response = curl_exec($curl);
      // dd($response);
      Log::info(['caresupreme_createpolicy_Response' => $response]);
      return $response;
    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }

  public static function policyStatus(Request $request, $sessionid, $tokenid, $proposal)
  {
    // $proposalnum = session()->get('proposalnum');
    $userId = $request->userid;
    $cacheproposalnum = 'cache_proposalnum_' . $userId;
    $proposalnum = GetCache($cacheproposalnum) ?? '1';
    $curl = curl_init();
    try {
      $payload = json_encode([
        'intGetPolicyStatusIO' => [
          'proposalNum' => !empty($proposal) ? $proposal : $proposalnum,
        ]
      ]);

      curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.careinsurance.com/relinterfacerestful/religare/secure/restful/getPolicyStatusV2',
        // CURLOPT_URL => 'https://apiuat.careinsurance.com/relinterfacerestful/religare/secure/restful/getPolicyStatusV2',
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
          'agentId: ' . self::$agentid,  // 20008325', //20689274
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
  }

  public static function policyPdf($sessionid, $tokenid, $policy)
  {
    // $proposalnum = session()->get('proposalnum');
    $curl = curl_init();
    try {
      $payload = json_encode([
        'intFaveoGetPolicyPDFIO' => [
          'policyNum' => $policy,
          'ltype' => ' '
        ]
      ]);
      curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.careinsurance.com/relinterfacerestful/religare/secure/restful/getPolicyPDFV2',
        // CURLOPT_URL => 'https://apiuat.careinsurance.com/relinterfacerestful/religare/secure/restful/getPolicyPDFV2',
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
          'agentId: ' . self::$agentid,  // 20008325', //20689274
          'applicationCD: ' . self::$applicationCD,
          'Content-Type: application/json'
        ],
      ]);
      $response = curl_exec($curl);
      curl_close($curl);
      Log::info(['Response' => $response]);
      return $response;
    } catch (\Exception $e) {
      echo '' . $e->getMessage();
    }
  }

  public static function verifyUploadDocument($filePath, $docType, $proposalNum, $sessionid, $tokenid)
  {
    // dd("kkk");
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
          // dd($payload,$proposalNum,$tokenid);
          $curl = curl_init();
          curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.careinsurance.com/relinterfacerestful/religare/secure/restful/uploadEkycDocument',
            // CURLOPT_URL => 'https://apiuat.careinsurance.com/relinterfacerestful/religare/secure/restful/uploadEkycDocument',
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
          // dd($response);
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
}
