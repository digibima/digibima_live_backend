<?php
//----------------------------------test---------------------------
namespace App\Services;

use Illuminate\Support\Facades\{Auth, Log};
use Illuminate\Support\Facades\Session;
use App\Models\JourneyUsers;
use App\Models\Insure;
use App\Models\User;
use App\Models\CareToken;
use Carbon\Carbon;
use App\Http\Controllers\front\health\caresupreme\CareSupremeController;
use DateTime;

class CareSupremeService
{
   private static $ProductId = "10001138";//"10001117";
  private static $partnerId = "872788";
  private static $appId = "872788";
  private static $timestamp = "1706613898969";
  private static $securityKey = "dkpBQ0Q3cGVGb1NXVnNsWW1EaERWb0ErQVFyTGFhSytNZCtrVzdzRGtrOW1DWktaTDdwWHRWdVZoYnpyV1JseA==";
  private static $aeskey = "z5yK1lw7XYt6YKdP7Pne2Jw3zRkMAziH";
  private static $iv = "i0kbCAlFTlDXshYV";
  private static $applicationCD = "PARTNERAPP";
  private static $signature = "R+GdzmW7vnvSX5MekKa";
  private static $agentid = "20008325";
  public static function managePartnerToken($data = null)
  {
    //dd($data);
    $bFlag = true;
    $data = json_decode($data, true);
    $status = $data['responseData']['status'];
    if ($status == "fail") {
      $error = $data['responseData']['message'];
      if (isset(explode(' ', strtolower($error))[0]) && explode(' ', strtolower($error))[0] == "session" || explode(' ', strtolower($error))[0] == "token") {
        $bFlag = false;
        $existingToken = CareToken::where('userid', Auth::id())->first();
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

  public static function generatePartnerToken($id = "")
  {
    
    $sSessionID = " ";
    $tokenId = " ";
    //dd($sSessionID,$tokenId);
    try {
      $tokendata = CareToken::where('userid', '=', Auth::id())->first();
      //dd($tokendata);
       
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
          CURLOPT_URL => 'https://apiuat.careinsurance.com/relinterfacerestful/religare/secure/restful/generatePartnerToken',
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
            'timestamp: 1706613898969',
            'applicationCD: ' . self::$applicationCD
          ),
        ));
        // dd($curl);
        $response = curl_exec($curl);
        //dd($response);
        Log::info(['Response' => $response]);
        curl_close($curl);
        if ($response == false) {
          return false;
        }
        $aToken = json_decode($response);
        $existingToken = CareToken::where('userid', Auth::id())->first();
        if ($existingToken) {
          $existingToken->token_number = 1;
          $existingToken->sessionid = json_encode($aToken->partnerTokenGeneratorInputIO->sessionId);
          $existingToken->partner_token = json_encode($aToken->partnerTokenGeneratorInputIO->listOfToken);
          $existingToken->updated_at = now();
          $existingToken->save();
        } else {
          $obj = new CareToken();
          $obj->userid = Auth::id();
          $obj->token_number = 1;
          $obj->sessionid = json_encode($aToken->partnerTokenGeneratorInputIO->sessionId);
          $obj->partner_token = json_encode($aToken->partnerTokenGeneratorInputIO->listOfToken);
          $obj->created_at = now();
          $obj->updated_at = now();
          $obj->save();
        }
        //$aToken = json_decode($response);
        $sSessionID = $aToken->partnerTokenGeneratorInputIO->sessionId;
        $gentokens = $aToken->partnerTokenGeneratorInputIO->listOfToken;
        $tokenKey = $gentokens[0]->tokenKey;
        $tokenValue = $gentokens[0]->tokenValue;
        $tokenId = self::generateTokenID($tokenKey, $tokenValue);
      }
    } catch (\Exception $e) {
      echo '' . $e->getMessage();
    }

    return json_encode(["sSessionID" => $sSessionID, "tokenid" => $tokenId]);
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
        "getCkycEkycInputIO" => [
          "panNum" => $panid,
          "document_type" => "PAN",
          "id_number" => "",
          "consent_purpose" => "this is a consent purpose string pass anything",
          "dob" => $dob
        ]
      ]);

      curl_setopt_array($curl, [
        CURLOPT_URL => 'https://apiuat.careinsurance.com/relinterfacerestful/religare/secure/restful/ckycDownload',
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
      //dd(json_decode($response));
      return $response;
    } catch (\Exception $e) {
      echo '' . $e->getMessage();
    }
  }
  //https://test.digibima.com/generatecbc/2/19473f652597890240dd590b0005a475
  public static function verifyAdhar($adharid, $dob, $name, $gender, $sessionid, $tokenid)
  {
    $curl = curl_init();
    // JSON payload
    try {
      $payload = json_encode([
        "aadharCKYCDetailsIO" => [
          "aadharno" => $adharid,
          "name" => $name,
          "dob" => $dob,
          "gender" => $gender,
        ]
      ]);

      curl_setopt_array($curl, [
        CURLOPT_URL => 'https://apiuat.careinsurance.com/relinterfacerestful/religare/secure/restful/aadharCKYC',
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
      //Log::info(['Care_Abacus_Token_Response'=>$response]);
      curl_close($curl);
      //dd($response);
      return $response;
    } catch (\Exception $e) {
      echo '' . $e->getMessage();
    }
  }

  public static function getBasicPlan( $pincode, $nom = "", $covertype = "", $noc = "", $eldest = "", $coverage, $time = "1", $ped_tenure = "", $aa = "0", $wb = "0", $ncb = "0", $ped = "0", $opd = "0", $cs = '0', $ic = '0', $ahc = '0', $bfb = '0')
  {
    try {
      
      $data = Insure::where('proposalid', Auth::id())->get();
      $nom = $data->count();
      $noc = $data->filter(function ($item) {
        return $item->age < 25 && $item->name == 'Son' || $item->name == 'Daughter';
      })->count();
      $aData = $data->sortByDesc('age')->toArray();
      
      //dd($aData);
      $memberName = [];
      foreach ($data as $key => $member) {
        $memberName[] = strtolower($member->name);
      }
      $covertype = (in_array('self', $memberName) && $nom > 1) ? 'Floater' : 'Individual';
      $authValue = "";
      $authID = self::getBasicPlanToken();
      $authValue = json_decode($authID)->data->accessToken;
      $arr1 = [];
      foreach ($aData as $key=>$row)
      {
        if($key==0)
        {
          continue;
        }
        $arr1["newMem_".$key+1] = $row['age'];
      }
      $arr2 = array(
        "partnerId" => "498",
        "abacusId" => "6008",
        "postedField" => array_merge($arr1,array(
          "field_54" => $pincode,
          "field_9" => $covertype,
          "field_1" => $nom,
          "field_10" => $noc,
          "field_3" => $aData[0]['age'],
          "field_2" => $coverage,
          "field_4" => $time . " Year",
          "outPutField" => "field_8",
          "field_35" => $aa,
          "field_WB" => $wb,
          "field_NCB" => $ncb,
          "field_AHC" => $ahc,
          "field_IC" => $ic,
          "field_CS" => $cs,
          "field_OPD" => $opd,
          "field_43" => $ped,
          "field_BFB" => $bfb,
          // "field_PED"=>"1",
          "field_PED_TENURE" => $ped_tenure ? $ped_tenure . ' Year' : '',
        ))
      );
      //dd($arr2);
      $requestdata = json_encode($arr2
      );
      //dd($requestdata);
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://abacus.careinsurance.com/religare_api/api/web/v1/abacus/partner?formattype=json',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $requestdata,
        CURLOPT_HTTPHEADER => array(
          'Authorization: Bearer' . $authValue,
          'Content-Type: application/json'
        ),
      ));
      $response = curl_exec($curl);
      //dd($response);
      //dd(json_decode($requestdata),json_decode($response));
      Log::info(['Care_Abacus_Response' => $response]);
      curl_close($curl);
      return $response;
    } catch (\Exception $e) {
      echo '' . $e->getMessage();
    }
  }

  public static function cbcConvertor($param)
  {
    $loaclaesKey = "z5yK1lw7XYt6YKdP7Pne2Jw3zRkMAziH";
    $loacliv = "i0kbCAlFTlDXshYV";
    $encrypted = openssl_encrypt(
      $param,
      'aes-256-cbc',
      $loaclaesKey,
      OPENSSL_RAW_DATA,
      $loacliv
    );
    return base64_encode(base64_encode($encrypted));
  }
  public static function creatPolicy($sessionid, $tokenid)
  {
    $user = User::find(Auth::id());
    $commaddress = json_decode($user['communication']);
    $bankAccount = !empty(json_decode($user['bank_details'])->account) ? json_decode($user['bank_details'])->account : "SACBHJHB";
    $bankIFSC = !empty(json_decode($user['bank_details'])->ifsc) ? json_decode($user['bank_details'])->ifsc : "SDVBHJSJDBV";
    $addons = $user['addon'] != null ? json_decode($user['addon']) : [];
    //dd($bankAccount,$bankIFSC);
    //$csaddonCode = "WBS1146,AACS1147,NCBS1145";
    //dd($user);
    $csaddonCode = "";
    $compulsoryaddon = session()->has('compulsoryaddon') ? array_filter(json_decode(session()->get('compulsoryaddon'), true)) : [];
    //dd($compulsoryaddon);
    $namePart = session('kyctype') == 'o' ? explode(' ', $user->name) : explode(' ', $user->kyc_name);
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
      '5' => '621',//621
      '7' => '623',//623
      '10' => '625',//625
      '15' => '627',//627
      '25' => '631',//631
      '50' => '633',//633
      '100' => '637',//637
    ];

    $sumInsuredFlo = [
      '5' => '622',//622
      '7' => '624',//624
      '10' => '626',//626
      '15' => '628',//628
      '25' => '632',//632
      '50' => '034',//634
      '100' => '638',//638
    ];
    $addonCode = [
      'aa' => 'AACS1147',
      'wb' => 'WBS1146',
      'ncb' => 'NCBS1145',
      'ahc' => 'AHCS1144',
      'ic' => 'ICS1149',
      'cs' => 'CS1154',
      'opd' => 'COPD1211',
      '1' => 'PEDWP1Y1155',
      '2' => 'PEDWP2Y1156',
      'befit' => 'BFS1148'
    ];

    //dd($csaddonCode);
    $userData = [
      'dob' => str_replace('-', '/', $user->dob),
      'gender' => strtoupper($user->gender),
      'height' => $user->height,
      'weight' => $user->weight,
      'house' => strtoupper($user->house),
      'city' => strtoupper($user->city),
      'state' => strtoupper($user->state),
      'pincode' => $user->pincode,
      'mobile' => $user->mobile,
      'email' => $user->email,
      'panId' => strtoupper($user->panid),
      'title' => strtoupper($user->mr_mrs),
      'firstName' => isset($namePart[0]) ? $namePart[0] : 'Unknown',
      'lastName' => count($namePart) > 1 ? end($namePart) : 'Unknown',
      'bankAccount' => $bankAccount,
      'bankIFSC' => $bankIFSC,
      'relationCd' => $relationCode[strtoupper($user->proposar)] //strtoupper($user->proposar)
    ];
    //dd($userData);
    //------------------------------------inserting proposar data------------------------------
    $ckycnumber = session()->get('ckycno');
    $insures[] =
      [
        'birthDt' => $userData['dob'],
        'firstName' => $userData['firstName'],
        'genderCd' => $userData['gender'],
        'height' => (30 * $userData['height']),
        'weight' => $userData['weight'],
        'guid' => 'QN6237993',
        "ckycNumber" => session('kyctype') != 'o' ? $ckycnumber : '',
        "ckyc" => session('kyctype') != 'o' ? 'Yes' : 'No',
        "ovdkyc" => session('kyctype') == 'o' ? 'YES' : 'NO',
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
            'addressLine1Lang1' => $commaddress->status == "1" ? $userData['house'] : $commaddress->comhouse,
            'addressLine2Lang1' => $commaddress->status == "1" ? $userData['house'] : $commaddress->comhouse,
            'addressTypeCd' => 'COMMUNICATION',
            'areaCd' => $commaddress->status == "1" ? $userData['city'] : $commaddress->comcity,
            'cityCd' => $commaddress->status == "1" ? $userData['city'] : $commaddress->comcity,
            'pinCode' => $commaddress->status == "1" ? $userData['pincode'] : $commaddress->compincode,
            'stateCd' => $commaddress->status == "1" ? $userData['state'] : $commaddress->comstate,
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
    //------------------------------------end inserting proposar data------------------------------
    //dd($insures);

    $members = JourneyUsers::where('proposalid', Auth::User()->id)->where('insureid', '<>', '0')->get();
    $nominee = JourneyUsers::where('proposalid', Auth::User()->id)->where('insureid', '0')->first();
    
    // return false;
    $csaddonCode = implode(',', array_map(function ($value) use ($addonCode) {
      return $addonCode[$value];
    }, $compulsoryaddon));

    foreach ($addons as $k => $value) {
      if (array_key_exists($value, $addonCode)) {
        $csaddonCode .= "," . $addonCode[$value];
      }
    }
    //dd($csaddonCode);
    $tenure = session('tenure');

    $memberRelation = [];
    foreach ($members as $key => $member) {
      $memberRelation[] = strtolower($member->relation);
    }
    //$covertype = (in_array('self', $memberRelation) && count($members) > 1) ? 'FAMILYFLOATER' : 'INDIVIDUAL';
    $covertype = count($members) > 1 ? 'FAMILYFLOATER' : 'INDIVIDUAL';
    $sumInsured = '';
    if (count($members) > 1 && array_key_exists(session()->get('coverage'), $sumInsuredFlo)) {
      $sumInsured = $sumInsuredFlo[session()->get('coverage')];
    } else {
      $sumInsured = $sumInsuredInd[session()->get('coverage')];
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
            "ckycNumber" => session('kyctype') != 'o' ? $ckycnumber : '',
            "ckyc" => session('kyctype') != 'o' ? 'Yes' : 'No',
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
                'addressLine1Lang1' => $commaddress->status == "1" ? $userData['house'] : $commaddress->comhouse,
                'addressLine2Lang1' => $commaddress->status == "1" ? $userData['house'] : $commaddress->comhouse,
                'addressTypeCd' => 'COMMUNICATION',
                'areaCd' => $commaddress->status == "1" ? $userData['city'] : $commaddress->comcity,
                'cityCd' => $commaddress->status == "1" ? $userData['city'] : $commaddress->comcity,
                'pinCode' => $commaddress->status == "1" ? $userData['pincode'] : $commaddress->compincode,
                'stateCd' => $commaddress->status == "1" ? $userData['state'] : $commaddress->comstate,
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
            //'partyQuestionDOList'=>[],
            'partyQuestionDOList' => [
              [
                "questionSetCd" => "yesNoExist",
                "questionCd" => "pedYesNo",
                "response" => $diseaseCount > 0 ? 'YES' : 'NO'
              ],
              [
                "questionSetCd" => "PEDcancerDetails",
                "questionCd" => "114",
                "response" => array_search(1.1, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDcancerDetails",
                "questionCd" => "cancerExistingSince",
                "response" => array_search(1.1, $did) !== false ? $disease[array_search(1.1, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDcardiacDetails",
                "questionCd" => "143",
                "response" => array_search(1.2, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDcardiacDetails",
                "questionCd" => "cardiacExistingSince",
                "response" => array_search(1.2, $did) !== false ? $disease[array_search(1.2, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDhyperTensionDetails",
                "questionCd" => "207",
                "response" => array_search(1.3, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDhyperTensionDetails",
                "questionCd" => "hyperTensionExistingSince",
                "response" => array_search(1.3, $did) !== false ? $disease[array_search(1.3, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDRespiratoryDetails",
                "questionCd" => "250",
                "response" => array_search(1.4, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDRespiratoryDetails",
                "questionCd" => "respiratoryExistingSince",
                "response" => array_search(1.4, $did) !== false ? $disease[array_search(1.4, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDEndoDetails",
                "questionCd" => "222",
                "response" => array_search(1.5, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDEndoDetails",
                "questionCd" => "EndocriExistingSince",
                "response" => array_search(1.5, $did) !== false ? $disease[array_search(1.5, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDdiabetesDetails",
                "questionCd" => "205",
                "response" => array_search(1.6, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDdiabetesDetails",
                "questionCd" => "diabetesExistingSince",
                "response" => array_search(1.6, $did) !== false ? $disease[array_search(1.6, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDkidneyDetails",
                "questionCd" => "129",
                "response" => array_search(1.9, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDkidneyDetails",
                "questionCd" => "kidneyExistingSince",
                "response" => array_search(1.9, $did) !== false ? $disease[array_search(1.9, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDliverDetails",
                "questionCd" => "232",
                "response" => array_search(1.8, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDliverDetails",
                "questionCd" => "liverExistingSince",
                "response" => array_search(1.8, $did) !== false ? $disease[array_search(1.8, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDparalysisDetails",
                "questionCd" => "164",
                "response" => array_search(1.7, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDparalysisDetails",
                "questionCd" => "paralysisExistingSince",
                "response" => array_search(1.7, $did) !== false ? $disease[array_search(1.7, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDjointpainDetails",
                "questionCd" => "105",
                "response" => array_search('1.10', $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDjointpainDetails",
                "questionCd" => "jointpainExistingSince",
                "response" => array_search('1.10', $did) !== false ? $disease[array_search('1.10', $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDcongenitalDetails",
                "questionCd" => "122",
                "response" => array_search(1.11, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDcongenitalDetails",
                "questionCd" => "congenitalExistingSince",
                "response" => array_search(1.11, $did) !== false ? $disease[array_search(1.11, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDHivaidsDetails",
                "questionCd" => "147",
                "response" => array_search(1.12, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDHivaidsDetails",
                "questionCd" => "hivaidsExistingSince",
                "response" => array_search(1.12, $did) !== false ? $disease[array_search(1.12, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDotherDetails",
                "questionCd" => "210",
                "response" => array_search(1.13, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDotherDetails",
                "questionCd" => "otherExistingSince",
                "response" => array_search(1.13, $did) !== false ? $disease[array_search(1.13, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDotherDetails",
                "questionCd" => "otherDiseasesDescription",
                "response" => array_search(1.13, $did) !== false ? $disease[array_search(1.13, $did)]['des'] : ''
              ],
              [
                "questionSetCd" => "PEDillnessDetails",
                "questionCd" => "502",
                "response" => array_search(1.14, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDillnessDetails",
                "questionCd" => "illnessExistingSince",
                "response" => array_search(1.14, $did) !== false ? $disease[array_search(1.14, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDSurgeryDetails",
                "questionCd" => "503",
                "response" => array_search(1.15, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDSurgeryDetails",
                "questionCd" => "SurgeryExistingSince",
                "response" => array_search(1.15, $did) !== false ? $disease[array_search(1.15, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "HEDHealthHospitalized",
                "questionCd" => "H001",
                "response" => array_search(2.4, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "HEDHealthClaim",
                "questionCd" => "H002",
                "response" => array_search(2.1, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "HEDHealthDeclined",
                "questionCd" => "H003",
                "response" => array_search(2.2, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "HEDHealthCovered",
                "questionCd" => "H004",
                "response" => array_search(2.3, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "HEDCareleafPA",
                "questionCd" => "P010",
                "response" => "NO"
              ],
              [
                "questionSetCd" => "PEDSmokeDetails",
                "questionCd" => "504",
                "response" => array_search(3.1, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDSmokeDetails",
                "questionCd" => "SmokeExistingSince",
                "response" => array_search(3.1, $did) !== false ? $disease[array_search(3.1, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDSmokeDetails",
                "questionCd" => "OtherSmokeDetails",
                "response" => array_search(3.1, $did) !== false ? $disease[array_search(3.1, $did)]['quantity'] : ''
              ]
            ],
            'relationCd' => $relationCode[strtoupper($user->proposar)],
            'roleCd' => 'PRIMARY',
            'titleCd' => $userData['title'],

          ];
        break;
      }
    }
    //dd($insures);
    if (count($members) > 0) {
      $guid = "";
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
          $guid = "QN623799" . ($k + 4);
          $insures[] = [
            'birthDt' => str_replace('-', '/', $member->dob),
            'firstName' => $fname,
            'genderCd' => $member->gender,
            'guid' => $guid,
            "ckycNumber" => $member->relation == 'self' && session('kyctype') != 'o' ? $ckycnumber : '',
            "ckyc" => $member->relation == 'self' && session('kyctype') != 'o' ? 'YES' : 'No',
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
                'pinCode' => $commaddress->status == "1" ? $userData['pincode'] : $commaddress->compincode,
              ],
              [
                'addressLine1Lang1' => '',
                'addressLine2Lang1' => '',
                'addressTypeCd' => 'COMMUNICATION',
                'areaCd' => '',
                'cityCd' => '',
                'pinCode' => $commaddress->status == "1" ? $userData['pincode'] : $commaddress->compincode,
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
                "questionSetCd" => "yesNoExist",
                "questionCd" => "pedYesNo",
                "response" => $diseaseCount > 0 ? 'YES' : 'NO'
              ],
              [
                "questionSetCd" => "PEDcancerDetails",
                "questionCd" => "114",
                "response" => array_search(1.1, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDcancerDetails",
                "questionCd" => "cancerExistingSince",
                "response" => array_search(1.1, $did) !== false ? $disease[array_search(1.1, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDcardiacDetails",
                "questionCd" => "143",
                "response" => array_search(1.2, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDcardiacDetails",
                "questionCd" => "cardiacExistingSince",
                "response" => array_search(1.2, $did) !== false ? $disease[array_search(1.2, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDhyperTensionDetails",
                "questionCd" => "207",
                "response" => array_search(1.3, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDhyperTensionDetails",
                "questionCd" => "hyperTensionExistingSince",
                "response" => array_search(1.3, $did) !== false ? $disease[array_search(1.3, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDRespiratoryDetails",
                "questionCd" => "250",
                "response" => array_search(1.4, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDRespiratoryDetails",
                "questionCd" => "respiratoryExistingSince",
                "response" => array_search(1.4, $did) !== false ? $disease[array_search(1.4, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDEndoDetails",
                "questionCd" => "222",
                "response" => array_search(1.5, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDEndoDetails",
                "questionCd" => "EndocriExistingSince",
                "response" => array_search(1.5, $did) !== false ? $disease[array_search(1.5, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDdiabetesDetails",
                "questionCd" => "205",
                "response" => array_search(1.6, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDdiabetesDetails",
                "questionCd" => "diabetesExistingSince",
                "response" => array_search(1.6, $did) !== false ? $disease[array_search(1.6, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDkidneyDetails",
                "questionCd" => "129",
                "response" => array_search(1.9, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDkidneyDetails",
                "questionCd" => "kidneyExistingSince",
                "response" => array_search(1.9, $did) !== false ? $disease[array_search(1.9, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDliverDetails",
                "questionCd" => "232",
                "response" => array_search(1.8, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDliverDetails",
                "questionCd" => "liverExistingSince",
                "response" => array_search(1.8, $did) !== false ? $disease[array_search(1.8, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDparalysisDetails",
                "questionCd" => "164",
                "response" => array_search(1.7, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDparalysisDetails",
                "questionCd" => "paralysisExistingSince",
                "response" => array_search(1.7, $did) !== false ? $disease[array_search(1.7, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDjointpainDetails",
                "questionCd" => "105",
                "response" => array_search('1.10', $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDjointpainDetails",
                "questionCd" => "jointpainExistingSince",
                "response" => array_search('1.10', $did) !== false ? $disease[array_search('1.10', $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDcongenitalDetails",
                "questionCd" => "122",
                "response" => array_search(1.11, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDcongenitalDetails",
                "questionCd" => "congenitalExistingSince",
                "response" => array_search(1.11, $did) !== false ? $disease[array_search(1.11, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDHivaidsDetails",
                "questionCd" => "147",
                "response" => array_search(1.12, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDHivaidsDetails",
                "questionCd" => "hivaidsExistingSince",
                "response" => array_search(1.12, $did) !== false ? $disease[array_search(1.12, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDotherDetails",
                "questionCd" => "210",
                "response" => array_search(1.13, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDotherDetails",
                "questionCd" => "otherExistingSince",
                "response" => array_search(1.13, $did) !== false ? $disease[array_search(1.13, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDotherDetails",
                "questionCd" => "otherDiseasesDescription",
                "response" => array_search(1.13, $did) !== false ? $disease[array_search(1.13, $did)]['des'] : ''
              ],
              [
                "questionSetCd" => "PEDillnessDetails",
                "questionCd" => "502",
                "response" => array_search(1.14, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDillnessDetails",
                "questionCd" => "illnessExistingSince",
                "response" => array_search(1.14, $did) !== false ? $disease[array_search(1.14, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDSurgeryDetails",
                "questionCd" => "503",
                "response" => array_search(1.15, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDSurgeryDetails",
                "questionCd" => "SurgeryExistingSince",
                "response" => array_search(1.15, $did) !== false ? $disease[array_search(1.15, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "HEDHealthHospitalized",
                "questionCd" => "H001",
                "response" => array_search(2.4, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "HEDHealthClaim",
                "questionCd" => "H002",
                "response" => array_search(2.1, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "HEDHealthDeclined",
                "questionCd" => "H003",
                "response" => array_search(2.2, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "HEDHealthCovered",
                "questionCd" => "H004",
                "response" => array_search(2.3, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "HEDCareleafPA",
                "questionCd" => "P010",
                "response" => "NO"
              ],
              [
                "questionSetCd" => "PEDSmokeDetails",
                "questionCd" => "504",
                "response" => array_search(3.1, $did) !== false ? "YES" : 'NO'
              ],
              [
                "questionSetCd" => "PEDSmokeDetails",
                "questionCd" => "SmokeExistingSince",
                "response" => array_search(3.1, $did) !== false ? $disease[array_search(3.1, $did)]['date'] : ''
              ],
              [
                "questionSetCd" => "PEDSmokeDetails",
                "questionCd" => "OtherSmokeDetails",
                "response" => array_search(3.1, $did) !== false ? $disease[array_search(3.1, $did)]['quantity'] : ''
              ]
            ],
            'relationCd' => $relationCd,
            'roleCd' => 'PRIMARY',
            'titleCd' => $member->gender == "MALE" ? 'MR' : 'MS'
          ];
        }
      }
    }
    $postData = json_encode([
      "intPolicyDataIO" => [
        "policy" => [
          "businessTypeCd" => "NEWBUSINESS",
          "baseProductId" => self::$ProductId,
          "baseAgentId" => self::$agentid,
          "coverType" => $covertype,
          "partyDOList" => $insures,
          "policyAdditionalFieldsDOList" => [
            [
              "field1" => "Partner_NB_DigiBima",
              "field10" => $nominee->name??"",
              "field12" => $nominee->relation?strtoupper(explode('(', $nominee->relation)[0]):"",
              "fieldAgree" => "YES",
              "fieldAlerts" => "YES",
              "fieldTc" => "YES"
            ]
          ],
          "sumInsured" => $sumInsured,
          "term" => $tenure,
          "isPremiumCalculation" => "YES",
          "addOns" => $csaddonCode
        ]
      ]
    ]);

    // return json_decode($postData);
        Log::info(['caresupreme_createpolicy_request' => $postData]);
    //dd($postData);
    // try {
    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => 'https://apiuat.careinsurance.com/relinterfacerestful/religare/secure/restful/createPolicy',
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
    //dd($response);
    Log::info(['Response' => $response]);
    Log::info(['nominee' => $nominee]);
    return $response;
    // } catch (\Exception $e) {
    //   echo '<p>Something is wrong with API. Contact to Administartor</p>';
    // }
  }

  public static function policyStatus($sessionid, $tokenid, $proposal)
  {
    $proposalnum = session()->get('proposalnum');
    $curl = curl_init();
    try {
      $payload = json_encode([
        "intGetPolicyStatusIO" => [
          "proposalNum" => !empty($proposal) ? $proposal : $proposalnum,
        ]
      ]);

      curl_setopt_array($curl, [
        CURLOPT_URL => 'https://apiuat.careinsurance.com/relinterfacerestful/religare/secure/restful/getPolicyStatusV2',
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
          'agentId:'.self::$agentid,, //20689274
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
    //$proposalnum = session()->get('proposalnum');
    $curl = curl_init();
    try {
      $payload = json_encode([
        "intFaveoGetPolicyPDFIO" => [
          "policyNum" => $policy,
          "ltype" => " "
        ]
      ]);
      curl_setopt_array($curl, [
        CURLOPT_URL => 'https://apiuat.careinsurance.com/relinterfacerestful/religare/secure/restful/getPolicyPDFV2',
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
          'agentId:'.self::$agentid, //20689274
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
    //dd("kkk");
    //self::initialize();
    $parts = explode("/", $filePath);
    $filename = end($parts);
    // dd($filename);
    if (isset($filename)) {
      if (file_exists($filePath)) {
        $fileContents = file_get_contents($filePath);
        $base64EncodedFile = base64_encode($fileContents);
        try {
          $payload = json_encode([
            "uploadEkycDocumentIO" => [
              "proposalNumber" => $proposalNum,
              "documentName" => $docType . '_' . $filename,
              "isBase64" => "Y",
              "documentData" => $base64EncodedFile,
              "docType" => $docType
            ]
          ]);
          //dd($payload,$proposalNum,$tokenid);
          $curl = curl_init();
          curl_setopt_array($curl, [
            CURLOPT_URL => 'https://apiuat.careinsurance.com/relinterfacerestful/religare/secure/restful/uploadEkycDocument',
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
          //dd($response);
          Log::info(['Response' => $response]);
          curl_close($curl);
          //self::saveResponse($response, '');
          return $response;
        } catch (\Exception $e) {
          echo '' . $e->getMessage();
        }
        return $base64EncodedFile;
      } else {
        // Handle case where file doesn't exist
        echo "File not found ";
        die;
      }
    } else {
      // Handle case where UserDescription is not found
      throw new Exception("File not found.");
    }
  }
}
