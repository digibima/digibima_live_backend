<?php
// ----------------------------------test---------------------------
namespace App\Services\Api\bajajHealth;

use App\Http\Controllers\front\health\bajajmyhealth\BjajaMyHealthController;
use App\Http\Controllers\front\health\caresupreme\CareSupremeController;
use App\Models\Bajaj\BajajPincode;
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

class BajajMyhealthService
{
    private static $securityKey = 'kycwsbrkmotr2023';
    private static $iv = 'kycwsbrkmotr2023';

    private $Id;
    private $imdCode;
    private $password;
    private $deptcode;
    private $baseurl;

    public static function initlize()
    {
        // dd(getconstant('HEALTH.BAJAJ.CREDENTIAL'));
        $instance = new self();
        $instance->Id = getconstant('HEALTH.BAJAJ.CREDENTIAL.ID');
        $instance->imdCode = getconstant('HEALTH.BAJAJ.CREDENTIAL.IMDCODE');
        $instance->password = getconstant('HEALTH.BAJAJ.CREDENTIAL.PASSWORD');
        $instance->deptcode = getconstant('HEALTH.BAJAJ.CREDENTIAL.DEPTCODE');
        $instance->baseurl = 'https://webapi.bajajgeneral.com/';
        return $instance;
    }

    public static function getBasicPlan($params)
    {
        try {
            $userId = $params['userId'];
            $pincode = $params['pincode'];
            $zonedata = BajajPincode::where('Pincode', $pincode)->first();
            $updated_zone = strtolower(trim($zonedata->ZoneCorrected));
            $zone_map = [
                'zone a' => 1,
                'zone b' => 2,
                'zone c' => 3,
            ];
            $polcovzone = $zone_map[$updated_zone] ?? '';
            $instance = self::initlize();
            $coverage = $params['coverage'];
            $sI = 100000 * $coverage;
            $time = $params['tenure'] ?? '1';
            $policyperiod = $time * 1200;
            $data = Insure::where('proposalid', $userId)->get()->toArray();
            $totalmembernos = count($data) ?? 1;
            $memrelation = $data[0]['name'] ?? '';
            $memage = $data[0]['age'] ?? '';
            $memgender = 'F';
            // $cachecovertype = 'cache_covertype_' . $userId;
            // $producttype = GetCache($cachecovertype) ?? '1';
            $cachehealthplan = 'cache_healthplan_' . $userId;
            $plantype = GetCache($cachehealthplan) ?? '';
            $member = RedisGet('Member:' . $userId);
            $businesstype = '';

            if ($plantype == '1') {
                $businesstype = 'NB';
            } else {
                $businesstype = 'PORT';
            }
            // dd($businesstype);
            $productcode = '8456';
            $today = now()->format('Y-m-d');
            $startdate = now()->format('d-M-Y');
            $enddate = now()->addYear()->subDay()->format('d-M-Y');
            $dob = date('d-M-Y', strtotime("-{$data[0]['age']} years", strtotime($today)));
            $journeydata = HealthJourney::where('userid', $userId)
                ->where('vid', getconstant('HEALTH.BAJAJ.KEY'))
                ->first() ?? [];
            $addons = [];
            if (!empty($journeydata) && !empty($journeydata['addon'])) {
                $addons = json_decode($journeydata['addon'], true) ?? [];
            } else {
                $addons = [];
            }
            $aAddons = [];
            if (!empty($addons) && is_array($addons)) {
                foreach ($addons as $addon) {
                    if (!is_string($addon))
                        continue;
                    if (strpos($addon, 'RR') === 0) {
                        $aAddons['RR'] = substr($addon, 2);
                        continue;
                    }
                    preg_match('/^([a-zA-Z]+)(\d*)$/', $addon, $matches);
                    $char = $matches[1] ?? '';
                    $num = $matches[2] ?? '';

                    if (in_array($char, ['ped', 'phe', 'prhe', 'sdwp', 'addon'])) {
                        if ($char === 'addon') {
                            $aAddons[$char . $num] = $num;
                        } else {
                            $aAddons[$char] = $num;
                        }
                    }
                }
            }
            $RR = '';
            if ($coverage >= 3 && $coverage <= 10) {
                $RR = 'SPA';
            } else if ($coverage >= 10) {
                $RR = 'Actual';
            } else {
                $RR = 'SPA';
            }
            $password = $instance->password;
            $Id = $instance->Id;
            $imdCode = $instance->imdCode;
            $deptcode = $instance->deptcode;

            $postfields = [
                'password' => $password,
                'userid' => $Id,
                'sourcedtls' => [
                    'username' => '',
                    'departmentcode' => '9906',
                    'productcode' => $productcode,
                    'businesstype' => $businesstype ?? '',
                    'imdcode' => $imdCode ?? '',
                    'subimdcode' => '',
                    'modulename' => 'WEBSERVICE'
                ],
                'policydtls' => [
                    'remarks' => 'https://insurance.digibima.com/health/vendors/bajaj/payment/thankyou',
                    'termstartdate' => $startdate ?? '',
                    'termenddate' => $enddate ?? '',
                    'partnertype' => 'P',
                    'businesstype' => $businesstype ?? '',
                    'policyperiod' => $policyperiod ?? '1200',
                    'productcode' => $productcode ?? '',
                    'scrlocationcode' => '9906',
                    'paymentmode' => '',
                    'scrcategory' => $businesstype ?? '',
                    'deptcode' => $deptcode ?? '',
                ],
                'tycpdetails' => [
                    'telephone' => '',
                    'nationality' => '',
                    'email' => '',
                    'surname' => '',
                    'contact1' => '',
                    'dateofbirth' => '',
                    'firstname' => '',
                    'middlename' => '',
                    'sex' => ''
                ],
                'tycpaddrlist' => [
                    [
                        'state' => '',
                        'postcode' => $pincode ?? '400072',
                        'addressline1' => '',
                        'addressline2' => '',
                        'addressline3' => '',
                        'addressline4' => '',
                        'addressline5' => ''
                    ]
                ],
                'channeldtls' => [
                    'subimdcode' => '',
                    'imdcode' => $imdCode ?? ''
                ],
                'previnsdtls' => [
                    'noofclaims' => '0',
                    'previnsname' => '',
                    'previnsaddress' => '',
                    'previnspolicyno' => '',
                    'prevpolicyexpirydate' => ''
                ],
                'hcpdtmemlist' => [
                    [
                        'membername' => '',
                        'memrelation' => $memrelation ?? '',
                        'memdob' => $dob ?? '',
                        'memage' => $memage ?? '',
                        'memgender' => $memgender ?? '',
                        'memheightcm' => '',
                        'memweightkg' => '',
                        'membmi' => '',
                        'memoccupation' => $proposerDetails->occupation ?? 'doctor',
                        'memgrossmonthlyincome' => '',
                        'mempreexistdisease' => '0',
                        'memsmkertbco' => '0',
                        'memasthma' => '0',
                        'memcholstrldisordr' => '0',
                        'memheartdisease' => '0',
                        'memhypertension' => '0',
                        'memdiabetes' => '0',
                        'memobesity' => '0',
                        'memaddflag' => 'N',
                        'selfcoveredflag' => 'Y',
                        'memnomineename' => '',
                        'memnomineerelation' => '',
                        'memprvsi' => '',
                        'roomRent' => isset($aAddons['RR']) ? $aAddons['RR'] : $RR,
                        'pedWaitingPeriodInMonths' => isset($aAddons['ped']) ? ($aAddons['ped'] * 12) : '36',
                        'postHospitalizationExpenses' => isset($aAddons['phe']) ? $aAddons['phe'] : '90',
                        'preHospitalizationExpenses' => isset($aAddons['prhe']) ? $aAddons['prhe'] : '60',
                        'specificDiseaseWaitingPeriod' => isset($aAddons['sdwp']) ? ($aAddons['sdwp'] * 12) : '24',
                        'bahcpdtmemparam91' => null,
                        'bahcpdtmemparam41' => null
                    ]
                ],
                'hcpdtmemcovlist' => [
                    [
                        'membername' => '',
                        'memiptreatsi' => $sI ?? '1000000',
                        'memiptreatplan' => 'Plan 1',
                        'memiptreatsubplan' => 'Plan 1',
                        'memaddflag' => 'N',
                        'selfcoveredflag' => 'Y',
                        'memmatnormsi' => '0',
                        'memmatcesrnsi' => '0',
                        'memcoonbnftsi' => '',
                        'meminschldsi' => ''
                    ]
                ],
                'hcpdtpolcovobj' => [
                    'polcovzone' => $polcovzone,
                    'polcov41' => 'NA',
                    'polcovvolntrycp' => '',
                    'polcovspcndt' => 'NA',
                    'polcovmedcnd' => 'N',
                    'polcov91' => 'NA',
                    'polcovihtsi' => $sI ?? '1000000',
                    'polcov51' => '',
                    'polcovihtpln' => '',
                    'polcovmatexpnorm' => '0',
                    'polcovmatexpcsrn' => '0',
                    'polcov46' => '0'
                ],
                'hcpdtmemcovaddonlist' => [
                    [
                        'addonairambcv' => '',
                        'membername' => '',
                        'addon23' => isset($aAddons['addon23']) ? '1' : '0',
                        'addon24' => isset($aAddons['addon24']) ? '1' : '0',
                        'addon25' => isset($aAddons['addon25']) ? '1' : '0',
                        'memaddflag' => 'N'
                    ]
                ],
                'hcpstagedataobj' => [
                    'busstype' => $businesstype ?? 'NB',
                    'productcode' => $productcode,
                    'termstartdate' => $startdate,
                    'termenddate' => $enddate,
                    'selfcoveredflag' => 'Y',
                    'membercombo' => $member ?? '1A',
                    'totalmembernos' => $totalmembernos,
                    'partnerpincode' => $pincode
                ]
            ];
            // return $postfields;
            $requestdata = json_encode($postfields);
            SaveFile($requestdata, 'bajaj_myhealth_quote_request.txt');
            $curl = curl_init();
            curl_setopt_array($curl, [
                // CURLOPT_URL => 'https://htapi.bagicpp.bajajallianz.com/bagicHws/health/healthpremiumcal',
                CURLOPT_URL => $instance->baseurl . 'bagicHws/health/healthpremiumcal',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $requestdata,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'x-api-key : 2O5czaeucN6ROiVrUy7hu1LEyf9rX7ZF959FCKWo'
                ],
            ]);

            $response = curl_exec($curl);
            // dd($response);
            curl_close($curl);
            SaveFile($response, 'bajaj_myhealth_quote_response.txt');
            return $response;
        } catch (\Exception $e) {
            dd(ErrMessage($e));
            return ErrMessage($e);
        }
    }

    public static function getBasicPlanFloater($params)
    {
        try {
            $userId = $params['userId'];
            $pincode = $params['pincode'];
            $zonedata = BajajPincode::where('Pincode', $pincode)->first();
            $updated_zone = strtolower(trim($zonedata->ZoneCorrected));
            $zone_map = [
                'zone a' => 1,
                'zone b' => 2,
                'zone c' => 3,
            ];

            $instance = self::initlize();
            $polcovzone = $zone_map[$updated_zone] ?? '';
            $coverage = $params['coverage'];
            $data = Insure::where('proposalid', $userId)->get()->toArray();
            $sI = 100000 * $coverage;
            $time = $params['tenure'] ?? '1';
            $policyperiod = $time * 1200;
            $productcode = '8457';
            $today = now()->format('Y-m-d');
            $startdate = now()->format('d-M-Y');
            $enddate = now()->addYear()->subDay()->format('d-M-Y');
            $dob = date('d-M-Y', strtotime("-{$data[0]['age']} years", strtotime($today)));
            $totalmembernos = count($data) ?? 1;
            $memrelation = $data[0]['name'] ?? 'Self';
            $membercombo = RedisGet('Member:' . $userId);
            $memage = $data[0]['age'] ?? '28';
            $memgender = 'F';
            $cachecovertype = 'cache_covertype_' . $userId;
            $producttype = GetCache($cachecovertype) ?? '1';
            $cachehealthplan = 'cache_healthplan_' . $userId;
            $plantype = GetCache($cachehealthplan) ?? '';
            $businesstype = '';
            if ($plantype = '1') {
                $businesstype = 'NB';
            } else {
                $businesstype = 'PORT';
            }

            $journeydata = HealthJourney::where('userid', $userId)
                ->where('vid', getconstant('HEALTH.BAJAJ.KEY'))
                ->first() ?? [];
            $addons = [];
            if (!empty($journeydata) && !empty($journeydata['addon'])) {
                $addons = json_decode($journeydata['addon'], true) ?? [];
            } else {
                $addons = [];
            }

            $aAddons = [];
            $relationCode = [
                'SELF' => 'Self',
                'HUSBAND' => 'Spouse',
                'WIFE' => 'Spouse',
                'SON' => 'Son',
                'DAUGHTER' => 'Daughter',
                'FATHER' => 'Father',
                'MOTHER' => 'Mother',
                'FATHERINLAW' => 'Father In Law',
                'MOTHERINLAW' => 'Mother In Law'
            ];
            if (!empty($addons) && is_array($addons)) {
                foreach ($addons as $addon) {
                    if (!is_string($addon))
                        continue;
                    if (strpos($addon, 'RR') === 0) {
                        $aAddons['RR'] = substr($addon, 2);
                        continue;
                    }
                    preg_match('/^([a-zA-Z]+)(\d*)$/', $addon, $matches);
                    $char = $matches[1] ?? '';
                    $num = $matches[2] ?? '';

                    if (in_array($char, ['ped', 'phe', 'prhe', 'sdwp', 'addon'])) {
                        if ($char === 'addon') {
                            $aAddons[$char . $num] = $num;
                        } else {
                            $aAddons[$char] = $num;
                        }
                    }
                }
            }
            $RR = '';
            if ($coverage >= 3 && $coverage <= 10) {
                $RR = 'SPA';
            } else if ($coverage >= 10) {
                $RR = 'Actual';
            } else {
                $RR = 'SPA';
            }
            $hcpdtmemlist = [];
            $hcpdtmemcovlist = [];
            $hcpdtmemcovaddonlist = [];
            $relation = '';

            foreach ($data as $member) {
                $key = strtoupper(str_replace([' ', '-'], '', $member['name']));

                // Relation Code Map

                if (in_array(strtolower($member['name']), ['husband', 'wife'])) {
                    $relation = 'Spouse';
                } else {
                    $relation = $relationCode[$key] ?? ucfirst($member['name']);
                }
                $gender = match (strtolower($member['name'])) {
                    'wife', 'daughter', 'mother', 'grandmother', 'mother-in-law' => 'F',
                    default => 'M',
                };

                $hcpdtmemlist[] = [
                    'membername' => '',
                    'memrelation' => $relation ?? '',
                    'memdob' => $dob,
                    'memage' => $member['age'],
                    'memgender' => $gender,
                    'memheightcm' => '',
                    'memweightkg' => '',
                    'membmi' => '',
                    'memoccupation' => $proposerDetails->occupation ?? 'doctor',
                    'memgrossmonthlyincome' => '',
                    'mempreexistdisease' => '0',
                    'memsmkertbco' => '0',
                    'memasthma' => '0',
                    'memcholstrldisordr' => '0',
                    'memheartdisease' => '0',
                    'memhypertension' => '0',
                    'memdiabetes' => '0',
                    'memobesity' => '0',
                    'memaddflag' => 'Y',
                    'selfcoveredflag' => strtolower($member['name']) == 'self' ? 'Y' : 'N',
                    'roomRent' => isset($aAddons['RR']) ? $aAddons['RR'] : $RR,
                    'pedWaitingPeriodInMonths' => isset($aAddons['ped']) ? ($aAddons['ped'] * 12) : '36',
                    'postHospitalizationExpenses' => isset($aAddons['phe']) ? $aAddons['phe'] : '90',
                    'preHospitalizationExpenses' => isset($aAddons['prhe']) ? $aAddons['prhe'] : '60',
                    'specificDiseaseWaitingPeriod' => isset($aAddons['sdwp']) ? ($aAddons['sdwp'] * 12) : '24',
                ];
                $hcpdtmemcovlist[] = [
                    'membername' => '',
                    'memiptreatsi' => $sI ?? '',
                    'memiptreatplan' => 'Plan 1',
                    'memiptreatsubplan' => 'Plan 1',
                    'memaddflag' => 'Y',
                    'selfcoveredflag' => strtolower($member['name']) == 'self' ? 'Y' : 'N',
                    'memmatnormsi' => '0',
                    'memmatcesrnsi' => '0',
                    'memcoonbnftsi' => '',
                    'meminschldsi' => ''
                ];
                $hcpdtmemcovaddonlist[] = [
                    'membername' => '',
                    'addonairambcv' => '',
                    'addon23' => isset($aAddons['addon23']) ? '1' : '0',
                    'addon24' => isset($aAddons['addon24']) ? '1' : '0',
                    'addon25' => isset($aAddons['addon25']) ? '1' : '0',
                    'memaddflag' => 'Y'
                ];
            }
            $hcpdtpolcovobj = [
                'polcovzone' => $polcovzone ?? '',
                'polcov41' => 'NA',
                'polcovvolntrycp' => '',
                'polcovspcndt' => 'NA',
                'polcovmedcnd' => 'N',
                'polcov91' => 'NA',
                'polcovihtsi' => $sI ?? '',
                'polcov51' => '',
                'polcovihtpln' => '',
                'polcovmatexpnorm' => '0',
                'polcovmatexpcsrn' => '0',
                'polcov46' => '0'
            ];
            $password = $instance->password;
            $Id = $instance->Id;
            $imdCode = $instance->imdCode;
            $deptcode = $instance->deptcode;
            // return "asdff";
            $postfeilds = [
                'password' => $password,
                'userid' => $Id,
                'sourcedtls' => [
                    'username' => '',
                    'departmentcode' => '9906',
                    'productcode' => $productcode ?? '8457',
                    'businesstype' => $businesstype ?? '',
                    'imdcode' => $imdCode,
                    'subimdcode' => '',
                    'modulename' => 'WEBSERVICE'
                ],
                'policydtls' => [
                    'remarks' => 'https://insurance.digibima.com/health/vendors/bajaj/payment/thankyou',
                    'termstartdate' => $startdate ?? '',
                    'termenddate' => $enddate ?? '',
                    'partnertype' => 'P',
                    'businesstype' => $businesstype ?? '',
                    'policyperiod' => $policyperiod ?? '',
                    'productcode' => $productcode ?? '8457',
                    'scrlocationcode' => '9906',
                    'paymentmode' => 'CC',
                    'scrcategory' => 'NB',
                    'deptcode' => $deptcode
                ],
                'tycpdetails' => [
                    'telephone' => '',
                    'nationality' => '',
                    'email' => '',
                    'surname' => '',
                    'contact1' => '',
                    'dateofbirth' => '',
                    'firstname' => '',
                    'middlename' => '',
                    'sex' => $memgender ?? ''
                ],
                'tycpaddrlist' => [
                    [
                        'state' => '',
                        'postcode' => $pincode ?? '',
                        'addressline1' => '',
                        'addressline2' => '',
                        'addressline3' => '',
                        'addressline4' => '',
                        'addressline5' => ''
                    ]
                ],
                'channeldtls' => [
                    'subimdcode' => '',
                    'imdcode' => $imdCode
                ],
                'previnsdtls' => [
                    'noofclaims' => '0',
                    'previnsname' => '',
                    'previnsaddress' => '',
                    'previnspolicyno' => '',
                    'prevpolicyexpirydate' => ''
                ],
                'hcpdtmemlist' => $hcpdtmemlist,
                'hcpdtmemcovlist' => $hcpdtmemcovlist,
                'hcpdtpolcovobj' => [
                    'polcovzone' => $polcovzone ?? '',
                    'polcov41' => 'NA',
                    'polcovvolntrycp' => '',
                    'polcovspcndt' => 'NA',
                    'polcovmedcnd' => 'N',
                    'polcov91' => 'NA',
                    'polcovihtsi' => $sI ?? '',
                    'polcov51' => '',
                    'polcovihtpln' => '',
                    'polcovmatexpnorm' => '0',
                    'polcovmatexpcsrn' => '0',
                    'polcov46' => '0'
                ],
                'hcpdtmemcovaddonlist' => $hcpdtmemcovaddonlist,
                'hcpstagedataobj' => [
                    'busstype' => $businesstype ?? '',
                    'productcode' => $productcode ?? '8457',
                    'termstartdate' => $startdate ?? '',
                    'termenddate' => $enddate ?? '',
                    'selfcoveredflag' => 'Y',
                    'membercombo' => $membercombo ?? '2A',
                    'totalmembernos' => $totalmembernos ?? '2',
                    'partnerpincode' => $pincode ?? ''
                ]
            ];

            // return $postfeilds;
            $requestdata = json_encode($postfeilds);
            SaveFile($requestdata, 'bajaj_myhealth_quote_floater_request.txt');
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $instance->baseurl . 'bagicHws/health/healthpremiumcal',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $requestdata,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json'
                ],
            ]);

            $response = curl_exec($curl);
            SaveFile($response, 'bajaj_myhealth_quote_floater_response.txt');
            curl_close($curl);
            return $response;
        } catch (\Exception $e) {
            return ErrMessage($e);
        }
    }

    public static function cbcConvertor($param)
    {
        $loaclaesKey = 'z5yK1lw7XYt6YKdP7Pne2Jw3zRkMAziH';
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

    public static function creatPolicy(Request $request, $transicationid = null)
    {
        try {
            $userId = $request->userid;
            $user = User::find($userId);
            $instance = self::initlize();
            $user1 = HealthJourney::where('userid', $user)->where('vid', getconstant('HEALTH.BAJAJ.KEY'))->first();
            $TransactionId = $transicationid;
            // dd($TransactionId);
            $pincode = $user->pincode;
            $zonedata = BajajPincode::where('Pincode', $pincode)->first();
            $updated_zone = strtolower(trim($zonedata->ZoneCorrected));
            $zone_map = [
                'zone a' => 1,
                'zone b' => 2,
                'zone c' => 3,
            ];
            $polcovzone = $zone_map[$updated_zone] ?? '';
            $cachecoverage = 'cache_coverage_' . $userId;
            $coverage = GetCache($cachecoverage) ?? '';
            $sI = 100000 * $coverage;
            $cachetenure = 'cache_tenure_' . $userId;
            $tenure = GetCache($cachetenure) ?? '1';
            $policyperiod = $tenure * 1200;
            $data = Insure::where('proposalid', $userId)->get()->toArray();
            $totalmembernos = count($data) ?? 1;
            $memrelation = $data[0]['name'] ?? 'Self';
            $memage = $data[0]['age'] ?? '28';
            $memgender = 'F';
            $cachecovertype = 'cache_covertype_' . $userId;
            $producttype = GetCache($cachecovertype) ?? '1';
            $cachehealthplan = 'cache_healthplan_' . $userId;
            $plantype = GetCache($cachehealthplan) ?? '1';
            $businesstype = '';
            if ($plantype == '1') {
                $businesstype = 'NB';
            } else {
                $businesstype = 'PORT';
            }

            $productcode = '8456';
            $today = now()->format('Y-m-d');
            $startdate = now()->format('d-M-Y');
            $enddate = now()->addYear()->subDay()->format('d-M-Y');
            $dob = date('d-M-Y', strtotime("-{$data[0]['age']} years", strtotime($today)));
            $user1 = HealthJourney::where('userid', $userId)->where('vid', getconstant('HEALTH.BAJAJ.KEY'))->first();
            $addons = json_decode($user1['addon'], true);
            $aAddons = [];
            if (!empty($addons) && is_array($addons)) {
                foreach ($addons as $addon) {
                    if (!is_string($addon))
                        continue;

                    if (strpos($addon, 'RR') === 0) {
                        $aAddons['RR'] = substr($addon, 2);
                        continue;
                    }
                    preg_match('/^([a-zA-Z]+)(\d*)$/', $addon, $matches);
                    $char = $matches[1] ?? '';
                    $num = $matches[2] ?? '';

                    if (in_array($char, ['ped', 'phe', 'prhe', 'sdwp', 'addon'])) {
                        if ($char === 'addon') {
                            $aAddons[$char . $num] = $num;
                        } else {
                            $aAddons[$char] = $num;
                        }
                    }
                }
            }

            $commaddress = json_decode($user1->comunication_address);
            $cachekyctype = 'cache_kyctype_' . $userId;
            GetCache($cachekyctype);
            $namePart = GetCache($cachekyctype) == 'o' ? explode(' ', $user->name) : explode(' ', $user1->kyc_name);
            $relationCode = [
                'SELF' => 'SELF',
                'HUSBAND' => 'SPOUSE',
                'WIFE' => 'SPOUSE',
                'SON' => 'SON',
                'SPOUSE' => 'SPOUSE',
                'DAUGHTER' => 'DAUGHTER',
                'FATHER' => 'FATHER',
                'MOTHER' => 'MOTHER',
                'BROTHER' => 'BROTHER',
                'SISTER' => 'SISTER',
                'FATHERINLAW' => 'Father In Law ',
                'MOTHERINLAW' => 'Mother In Law '
            ];

            $member = RedisGet('Member:' . $userId);
            $contactDetails = json_decode($user1->contact_details);
            $permanentAddress = json_decode($user1->permanent_address);
            $proposerDetails = json_decode($user1->proposer_details);
            $userData = [
                'dob' => $dob ?? '',
                'gender' => (strtoupper($user1->gender) == 'MS') ? 'F' : 'M',
                'height' => $proposerDetails->height ?? '',
                'weight' => $proposerDetails->weight ?? '',
                'house' => strtoupper($permanentAddress->address1) ?? '',
                'colony' => strtoupper($permanentAddress->address2) ?? '',
                'city' => $permanentAddress->city ?? '',
                'state' => $permanentAddress->state ?? '',
                'pincode' => $user->pincode ?? '',
                'mobile' => $contactDetails->contactmobile ?? '',
                'email' => $contactDetails->contactemail ?? $user->email,
                'panId' => strtoupper($user1->pan),
                'title' => strtoupper($user1->gender),
                'firstName' => $user1->name ?? '',
                'lastName' => count($namePart) > 1 ? end($namePart) : 'Unknown',
                'bankIFSC' => $bankIFSC ?? '',
                'relationCd' => $relationCode[strtoupper($user1->proposer)]
            ];

            $members = JourneyUsers::where('proposalid', $user->id)->where('insureid', '<>', '0')->get();
            foreach ($members as $member) {
                $memberdata[] = [
                    'name' => $member->name,
                    'relation' => $member->relation,
                    'dob' => $member->dob,
                    'age' => $member->age,
                    'ped' => $member->ped,
                ];
            }
            $ped = json_decode($memberdata[0]['ped'], true);
            // dd($ped);
            $nominee = JourneyUsers::where('proposalid', $user->id)->where('insureid', '0')->first();
            $relation = $nominee->relation;
            $nomineerelation = trim(strtok($relation, '('));
            $nomineerelation = $relationCode[strtoupper($nomineerelation)];
            // dd($nomineerelation);
            $portReason = '';
            $prevdetails = [];
            if ($businesstype == 'PORT') {
                $portReason = 'Product is not suitable';
                $prevdetails = [
                    'noofclaims' => '0',
                    'previnsname' => 'Acko General Insurance Co.Ltd.',
                    'previnsaddress' => 'adsdfgfg',
                    'previnspolicyno' => '12123435456',
                    'prevpolicyexpirydate' => '25-Nov-2025',
                    'sumInsuredInPreviousPolicy' => 500000
                ];
            } else {
                $portReason = '';
                $prevdetails = [
                    'noofclaims' => '0',
                    'previnsname' => '',
                    'previnsaddress' => '',
                    'previnspolicyno' => '',
                    'prevpolicyexpirydate' => '',
                ];
            }

            $password = $instance->password;
            $Id = $instance->Id;
            $imdCode = $instance->imdCode;
            $deptcode = $instance->deptcode;
            // dd($proposerDetails->occupation);
            $postData = json_encode(
                [
                    'password' => $password ?? '',
                    'userid' => $Id ?? '',
                    'sourcedtls' => [
                        'username' => $userData['firstName'] ?? '',
                        'departmentcode' => '9906',
                        'productcode' => $productcode ?? '',
                        'businesstype' => $businesstype ?? '',
                        'imdcode' => $imdCode ?? '',
                        'subimdcode' => '',
                        'modulename' => 'WEBSERVICE'
                    ],
                    'policydtls' => [
                        'remarks' => 'https://insurance.digibima.com/health/vendors/bajaj/payment/thankyou',
                        'termstartdate' => $startdate ?? '',
                        'termenddate' => $enddate ?? '',
                        'partnertype' => 'P',
                        'businesstype' => $businesstype ?? '',
                        'policyperiod' => $policyperiod ?? '',
                        'productcode' => $productcode ?? '',
                        'scrlocationcode' => '9906',
                        'paymentmode' => 'CC',
                        'scrcategory' => $businesstype ?? ''
                    ],
                    'tycpdetails' => [
                        'telephone' => $userData['mobile'] ?? '',
                        'nationality' => 'Indian',
                        'email' => $userData['email'] ?? '',
                        'surname' => 'Todakar',
                        'contact1' => $userData['mobile'] ?? '',
                        'dateofbirth' => $dob ?? '',
                        'firstname' => $userData['firstName'] ?? '',
                        'middlename' => '',
                        'sex' => $userData['gender'] ?? ''
                    ],
                    'tycpaddrlist' => [
                        [
                            'state' => $userData['state'] ?? '',
                            'postcode' => $userData['pincode'] ?? '',
                            'addressline1' => $userData['house'] ?? '',
                            'addressline2' => $userData['colony'] ?? '',
                            'addressline3' => $userData['city'] ?? '',
                            'addressline4' => '',
                            'addressline5' => 'INDIA'
                        ]
                    ],
                    'channeldtls' => [
                        'subimdcode' => '',
                        'imdcode' => $imdCode ?? ''
                    ],
                    'previnsdtls' => $prevdetails,
                    'hcpdtmemlist' => [
                        [
                            'membername' => $userData['firstName'] ?? '',
                            'memrelation' => $relationCode[strtoupper($userData['relationCd'])] ?? '',
                            'memdob' => $dob ?? '',
                            'memage' => $memage ?? '',
                            'memgender' => $userData['gender'] ?? '',
                            'memheightcm' => $userData['height'] ?? '',
                            'memweightkg' => $userData['weight'] ?? '',
                            'membmi' => '',
                            'memberGenetic' => '0',
                            'memoccupation' => $proposerDetails->occupation ?? '101',
                            'memgrossmonthlyincome' => $proposerDetails->monthlyincom,  // "20000",//
                            'mempreexistdisease' => isset($ped[0]['data']['mempreexistdisease'])
                                ? $ped[0]['data']['mempreexistdisease']
                                : '0',
                            'memsmkertbco' => isset($ped[0]['data']['memsmkertbco'])
                                ? $ped[0]['data']['memsmkertbco']
                                : '0',
                            'memasthma' => isset($ped[0]['data']['memasthma'])
                                ? $ped[0]['data']['memasthma']
                                : '0',
                            'memcholstrldisordr' => isset($ped[0]['data']['memcholstrldisordr'])
                                ? $ped[0]['data']['memcholstrldisordr']
                                : '0',
                            'memheartdisease' => isset($ped[0]['data']['memheartdisease'])
                                ? $ped[0]['data']['memheartdisease']
                                : '0',
                            'memhypertension' => isset($ped[0]['data']['memhypertension'])
                                ? $ped[0]['data']['memhypertension']
                                : '0',
                            'memdiabetes' => isset($ped[0]['data']['memdiabetes'])
                                ? $ped[0]['data']['memdiabetes']
                                : '0',
                            'memobesity' => '0',
                            'memaddflag' => 'N',
                            'selfcoveredflag' => 'Y',
                            'memnomineename' => $nominee['name'] ?? '',
                            'memnomineerelation' => ucfirst($nomineerelation) ?? '',
                            'memprvsi' => '',
                            'memnomineeage' => '',
                            'roomRent' => isset($aAddons['RR']) ? $aAddons['RR'] : '',
                            'pedWaitingPeriodInMonths' => isset($aAddons['ped']) ? ($aAddons['ped'] * 12) : '',
                            'postHospitalizationExpenses' => isset($aAddons['phe']) ? $aAddons['phe'] : '',
                            'preHospitalizationExpenses' => isset($aAddons['prhe']) ? $aAddons['prhe'] : '',
                            'specificDiseaseWaitingPeriod' => isset($aAddons['sdwp']) ? ($aAddons['sdwp'] * 12) : '',
                            'bahcpdtmemparam91' => null,
                            'bahcpdtmemparam41' => null
                        ]
                    ],
                    'hcpdtmemcovlist' => [
                        [
                            'membername' => $userData['firstName'] ?? '',
                            'memiptreatsi' => $sI ?? '',
                            'memiptreatplan' => 'Plan 1',
                            'memiptreatsubplan' => 'Plan 1',
                            'memaddflag' => 'N',
                            'selfcoveredflag' => 'Y',
                            'memmatnormsi' => '0',
                            'memmatcesrnsi' => '0',
                            'memcoonbnftsi' => '',
                            'meminschldsi' => ''
                        ]
                    ],
                    'hcpdtpolcovobj' => [
                        'polcovzone' => $polcovzone ?? '',
                        'polcov41' => 'NA',
                        'polcovvolntrycp' => '',
                        'polcovspcndt' => 'NA',
                        'polcovmedcnd' => 'N',
                        'polcov91' => 'NA',
                        'polcovihtsi' => $sI ?? '',
                        'polcov51' => '',
                        'polcovihtpln' => '',
                        'polcovmatexpnorm' => '0',
                        'polcovmatexpcsrn' => '0',
                        'polcov46' => '0',
                        // "reasonForPorting" => $portReason,
                        // "otherReasonForPorting" =>""
                    ],
                    'hcpdtmemcovaddonlist' => [
                        [
                            'addonairambcv' => '',
                            'membername' => $userData['firstName'] ?? '',
                            'addon23' => isset($aAddons['addon23']) ? '1' : '0',
                            'addon24' => isset($aAddons['addon24']) ? '1' : '0',
                            'addon25' => isset($aAddons['addon25']) ? '1' : '0',
                            'memaddflag' => 'N'
                        ]
                    ],
                    'hcpstagedataobj' => [
                        'busstype' => $businesstype ?? '',
                        'productcode' => $productcode ?? '',
                        'termstartdate' => $startdate ?? '',
                        'termenddate' => $enddate ?? '',
                        'selfcoveredflag' => 'Y',
                        'membercombo' => '1A',
                        'totalmembernos' => $totalmembernos ?? '',
                        'partnerpincode' => $pincode ?? ''
                    ],
                    'transactionid' => $TransactionId ?? ''
                ]
            );
            // return $postData;
            // dd($postData);
            SaveFile($postData, 'bajaj_indv_policy_request.txt');
            $curl = curl_init();
            curl_setopt_array($curl, [
                // CURLOPT_URL => 'https://htapi.bagicpp.bajajallianz.com/bagicHws/health/healthissuepolicy',
                CURLOPT_URL => $instance->baseurl . 'bagicHws/health/healthissuepolicy',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $postData,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'x-api-key: 2O5czaeucN6ROiVrUy7hu1LEyf9rX7ZF959FCKWo'
                ],
            ]);
            $response = curl_exec($curl);
            // dd($response);
            SaveFile($response, 'bajaj_indv_policy_response.txt');
            // Log::info(['Response' => $response]);
            return $response;
        } catch (\Exception $e) {
            return ErrMessage($e);
        }
    }

    public static function creatPolicyFloater(Request $request, $transicationid = null)
    {
        try {
            $userId = $request->userid;
            $user = User::find($userId);
            $instance = self::initlize();
            $user1 = HealthJourney::where('userid', $user)->where('vid', getconstant('HEALTH.BAJAJ.KEY'))->first();
            $TransactionId = $transicationid;
            $pincode = $user->pincode;
            $zonedata = BajajPincode::where('Pincode', $pincode)->first();
            $updated_zone = strtolower(trim($zonedata->ZoneCorrected));
            $zone_map = [
                'zone a' => 1,
                'zone b' => 2,
                'zone c' => 3,
            ];
            $polcovzone = $zone_map[$updated_zone] ?? '';
            $cachecoverage = 'cache_coverage_' . $userId;
            $coverage = GetCache($cachecoverage) ?? '';
            $sI = 100000 * $coverage;
            $cachetenure = 'cache_tenure_' . $userId;
            $tenure = GetCache($cachetenure) ?? '1';
            $policyperiod = $tenure * 1200;
            $data = Insure::where('proposalid', $userId)->get()->toArray();
            $totalmembernos = count($data) ?? 1;
            $memrelation = $data[0]['name'] ?? 'Self';
            $memage = $data[0]['age'] ?? '28';
            $memgender = 'F';
            $cachecovertype = 'cache_covertype_' . $userId;
            $producttype = GetCache($cachecovertype) ?? '1';
            $cachehealthplan = 'cache_healthplan_' . $userId;
            $plantype = GetCache($cachehealthplan) ?? '1';
            $businesstype = '';
            if ($plantype = '1') {
                $businesstype = 'NB';
            } else {
                $businesstype = 'PORT';
            }
            $productcode = '8457';
            $today = now()->format('Y-m-d');
            $startdate = $startdate = now()->format('d-M-Y');
            $enddate = now()->addYear()->subDay()->format('d-M-Y');
            $dob = date('d-M-Y', strtotime("-{$data[0]['age']} years", strtotime($today)));
            $user1 = HealthJourney::where('userid', $userId)->where('vid', getconstant('HEALTH.BAJAJ.KEY'))->first();
            $addons = json_decode($user1['addon'], true);
            $aAddons = [];
            if (!empty($addons) && is_array($addons)) {
                foreach ($addons as $addon) {
                    if (!is_string($addon))
                        continue;

                    if (strpos($addon, 'RR') === 0) {
                        $aAddons['RR'] = substr($addon, 2);
                        continue;
                    }
                    preg_match('/^([a-zA-Z]+)(\d*)$/', $addon, $matches);
                    $char = $matches[1] ?? '';
                    $num = $matches[2] ?? '';

                    if (in_array($char, ['ped', 'phe', 'prhe', 'sdwp', 'addon'])) {
                        if ($char === 'addon') {
                            $aAddons[$char . $num] = $num;
                        } else {
                            $aAddons[$char] = $num;
                        }
                    }
                }
            }
            $commaddress = json_decode($user1->comunication_address);
            $cachekyctype = 'cache_kyctype_' . $userId;
            GetCache($cachekyctype);
            $namePart = GetCache($cachekyctype) == 'o' ? explode(' ', $user->name) : explode(' ', $user1->kyc_name);
            $relationCode = [
                'SELF' => 'SELF',
                'HUSBAND' => 'SPOUSE',
                'WIFE' => 'SPOUSE',
                'SPOUSE' => 'SPOUSE',
                'SON' => 'SON',
                'DAUGHTER' => 'DAUGHTER',
                'FATHER' => 'FATHER',
                'MOTHER' => 'MOTHER',
                'BROTHER' => 'BROTHER',
                'SISTER' => 'SISTER',
                'FATHERINLAW' => 'Father In Law ',
                'MOTHERINLAW' => 'Mother In Law '
            ];
            $contactDetails = json_decode($user1->contact_details);
            $permanentAddress = json_decode($user1->permanent_address);
            $proposerDetails = json_decode($user1->proposer_details);
            $userData = [
                'dob' => $dob ?? '',
                'gender' => (strtoupper($user1->gender) == 'MS') ? 'F' : 'M',
                'height' => $proposerDetails->height ?? '',
                'weight' => $proposerDetails->weight ?? '',
                'house' => strtoupper($permanentAddress->address1) ?? '',
                'colony' => strtoupper($permanentAddress->address2) ?? '',
                'city' => $permanentAddress->city ?? '',
                'state' => $permanentAddress->state ?? '',
                'pincode' => $user->pincode ?? '',
                'mobile' => $contactDetails->contactmobile ?? '',
                'email' => $contactDetails->contactemail ?? $user->email,
                'panId' => strtoupper($user1->pan),
                'title' => strtoupper($user1->gender),
                'firstName' => $user1->name ?? '',
                'lastName' => count($namePart) > 1 ? end($namePart) : 'Unknown',
                'bankIFSC' => $bankIFSC ?? '',
                'relationCd' => $relationCode[strtoupper($user1->proposer)]
            ];
            $data = JourneyUsers::where('proposalid', $user->id)->where('insureid', '<>', '0')->get();
            foreach ($data as $member) {
                $memberdata[] = [
                    'name' => $member->name,
                    'relation' => $member->relation,
                    'dob' => $member->dob,
                    'age' => $member->age,
                    'ped' => $member->ped,
                    'height' => $member->height,
                    'weight' => $member->weight
                ];
            }

            $ped = json_decode($memberdata[0]['ped'], true);  // decode the string
            // $mempreexistdisease = isset($ped[0]['data']['mempreexistdisease'])
            //     ? $ped[0]['data']['mempreexistdisease']
            //     : '';
            // return $mempreexistdisease;
            $membercount = RedisGet('Member:' . $userId);
            $nominee = JourneyUsers::where('proposalid', $user->id)->where('insureid', '0')->first();
            $relation = $nominee['relation'];
            $nomineerelation = trim(strtok($relation, '('));
            $hcpdtmemlist = [];
            $hcpdtmemcovlist = [];
            $hcpdtmemcovaddonlist = [];
            $relation = '';
            $Memgender = '';
            foreach ($data as $member) {
                if (in_array(strtolower($member->relation), ['husband', 'wife'])) {
                    $relation = 'Spouse';
                    $Memgender = (strtolower($member->relation) == 'wife') ? 'F' : 'M';
                } else if (in_array(strtolower($member->relation), ['self'])) {
                    $Memgender = (strtolower($user->gender) == 'female') ? 'F' : 'M';
                    $relation = 'Self';
                } else {
                    $relation = ucfirst($member->relation);
                    $Memgender = match (strtolower($member->relation)) {
                        'wife', 'daughter', 'mother', 'grandmother', 'mother-in-law' => 'F',
                        default => 'M',
                    };
                }

                $hcpdtmemlist[] = [
                    'membername' => $member['name'] ?? '',
                    'memrelation' => $relation ?? '',
                    'memdob' => $member['dob'] ? date('d-M-Y', strtotime($member['dob'])) : '',
                    'memage' => $member['age'],
                    'memgender' => $Memgender,
                    'memheightcm' => $member['height'] ?? '',
                    'memweightkg' => $member['weight'] ?? '',
                    'membmi' => '',
                    'memoccupation' => $proposerDetails->occupation ?? '101',  // $proposerDetails->occupation??"Salaried",
                    'memgrossmonthlyincome' => $proposerDetails->monthlyincom,
                    'memberGenetic' => '0',
                    'mempreexistdisease' => '0',
                    'memsmkertbco' => '0',
                    'memasthma' => '0',
                    'memcholstrldisordr' => '0',
                    'memheartdisease' => '0',
                    'memhypertension' => '0',
                    'memdiabetes' => '0',
                    'memobesity' => '0',
                    'memaddflag' => 'Y',
                    'memnomineename' => 'Asd Asd',
                    'memnomineerelation' => ucfirst($nomineerelation) ?? '',
                    'memprvsi' => '',
                    'selfcoveredflag' => in_array(strtolower($member->relation), ['self']) ? 'Y' : 'N',
                    'roomRent' => isset($aAddons['RR']) ? $aAddons['RR'] : '',
                    'pedWaitingPeriodInMonths' => isset($aAddons['ped']) ? ($aAddons['ped'] * 12) : '',
                    'postHospitalizationExpenses' => isset($aAddons['phe']) ? $aAddons['phe'] : '',
                    'preHospitalizationExpenses' => isset($aAddons['prhe']) ? $aAddons['prhe'] : '',
                    'specificDiseaseWaitingPeriod' => isset($aAddons['sdwp']) ? ($aAddons['sdwp'] * 12) : '',
                ];
                $hcpdtmemcovlist[] = [
                    'membername' => $member['name'] ?? '',
                    'memiptreatsi' => $sI ?? '',
                    'memiptreatplan' => 'Plan 1',
                    'memiptreatsubplan' => 'Plan 1',
                    'memaddflag' => 'Y',
                    'selfcoveredflag' => in_array(strtolower($member->relation), ['self']) ? 'Y' : 'N',
                    'memmatnormsi' => '0',
                    'memmatcesrnsi' => '0',
                    'memcoonbnftsi' => '',
                    'meminschldsi' => ''
                ];

                $hcpdtmemcovaddonlist[] = [
                    'membername' => $member['name'] ?? '',
                    'addonairambcv' => '',
                    'addon23' => isset($aAddons['addon23']) ? '1' : '0',
                    'addon24' => isset($aAddons['addon24']) ? '1' : '0',
                    'addon25' => isset($aAddons['addon25']) ? '1' : '0',
                    'memaddflag' => 'Y'
                ];
            }

            $password = $instance->password;
            $Id = $instance->Id;
            $imdCode = $instance->imdCode;
            $deptcode = $instance->deptcode;

            $postData = json_encode(
                [
                    'password' => $password ?? 'Newpas12',
                    'userid' => $Id ?? 'webservice@digibima.com',
                    'sourcedtls' => [
                        'username' => $userData['firstName'] ?? '',
                        'departmentcode' => '9906',
                        'productcode' => $productcode ?? '8457',
                        'businesstype' => $businesstype ?? '',
                        'imdcode' => $imdCode,
                        'subimdcode' => '',
                        'modulename' => 'WEBSERVICE'
                    ],
                    'policydtls' => [
                        'remarks' => 'https://insurance.digibima.com/health/vendors/bajaj/payment/thankyou',
                        'termstartdate' => $startdate ?? '',
                        'termenddate' => $enddate ?? '',
                        'partnertype' => 'P',
                        'businesstype' => $businesstype ?? '',
                        'policyperiod' => $policyperiod ?? '',
                        'productcode' => $productcode ?? '8457',
                        'scrlocationcode' => '9906',
                        'paymentmode' => 'CC',
                        'scrcategory' => 'NB',
                        'deptcode' => $deptcode ?? '84'
                    ],
                    'tycpdetails' => [
                        'telephone' => $userData['mobile'] ?? '',
                        'nationality' => 'Indian',
                        'email' => $userData['email'] ?? '',
                        'surname' => 'Todkar',
                        'contact1' => $userData['mobile'] ?? '',
                        'dateofbirth' => $dob ?? '',
                        'firstname' => $userData['firstName'] ?? '',
                        'middlename' => '',
                        'sex' => $userData['gender'] ?? ''
                    ],
                    'tycpaddrlist' => [
                        [
                            'state' => $userData['state'] ?? '',
                            'postcode' => $userData['pincode'] ?? '',
                            'addressline1' => $userData['city'] ?? '',
                            'addressline2' => $userData['colony'] ?? '',
                            'addressline3' => $userData['house'] ?? '',
                            'addressline4' => '',
                            'addressline5' => 'INDIA'
                        ]
                    ],
                    'channeldtls' => [
                        'subimdcode' => '',
                        'imdcode' => $imdCode ?? '10105697'
                    ],
                    'previnsdtls' => [
                        'noofclaims' => '0',
                        'previnsname' => '',
                        'previnsaddress' => '',
                        'previnspolicyno' => '',
                        'prevpolicyexpirydate' => ''
                    ],
                    'hcpdtmemlist' => $hcpdtmemlist,
                    'hcpdtmemcovlist' => $hcpdtmemcovlist,
                    'hcpdtpolcovobj' => [
                        'polcovzone' => $polcovzone ?? '1',
                        'polcov41' => 'NA',
                        'polcovvolntrycp' => '',
                        'polcovspcndt' => 'NA',
                        'polcovmedcnd' => 'N',
                        'polcov91' => 'NA',
                        'polcovihtsi' => $sI ?? '',
                        'polcov51' => '',
                        'polcovihtpln' => '',
                        'polcovmatexpnorm' => '0',
                        'polcovmatexpcsrn' => '0',
                        'polcov46' => '0'
                    ],
                    'hcpdtmemcovaddonlist' => $hcpdtmemcovaddonlist,
                    'hcpstagedataobj' => [
                        'busstype' => $businesstype ?? 'NB',
                        'productcode' => $productcode ?? '8457',
                        'termstartdate' => $startdate ?? '',
                        'termenddate' => $enddate ?? '',
                        'selfcoveredflag' => 'Y',
                        'membercombo' => $membercount ?? '2A',
                        'totalmembernos' => $totalmembernos ?? '2',
                        'partnerpincode' => $pincode ?? ''
                    ],
                    'transactionid' => $TransactionId ?? ''
                ]
            );
            // return $postData;
            // dd($postData);
            SaveFile($postData, 'bajaj_floater_policy_request.txt');
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $instance->baseurl . 'bagicHws/health/healthissuepolicy',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $postData,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json'
                ],
            ]);
            $response = curl_exec($curl);
            SaveFile($response, 'bajaj_floater_policy_response.txt');

            Log::info(['Response' => $response]);
            return $response;
        } catch (\Exception $e) {
            return ErrMessage($e);
        }
    }

    public static function policyStatus($userId, $transactionId)
    {
        $instance = self::initlize();
        $id = $instance->Id;
        $password = $instance->password;
        try {
            $payload = json_encode([
                'intGetPolicyStatusIO' => [
                    'userid' => $id,
                    'password' => $password,
                    'transaction_id' => $transactionId,
                    'payment_transobj' => [
                        'stringval1' => 'Y',
                        'stringval2' => '6188359',
                        'stringval3' => 'HEALTH_WS',
                        'stringval6' => 'HDFC',
                        'stringval20' => '1000.0',
                    ]
                ]
            ]);
            // return $payload;
            SaveFile($payload, 'bajajstatusrequest.txt');
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $instance->baseurl . 'bagicHws/health/getpgtransstatus',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'x-api-key: 2O5czaeucN6ROiVrUy7hu1LEyf9rX7ZF959FCKWo'
                ],
            ]);
            $response = curl_exec($curl);
            SaveFile($response, 'bajajstatusresponse.txt');
            Log::info(['bajaj_policystatus_Response' => $response]);
            curl_close($curl);
            return $response;
        } catch (\Exception $e) {
            return ErrMessage($e);
        }
    }

    public static function policyPdf($policynum)
    {
        $curl = curl_init();
        try {
            $instance = self::initlize();
            $payload = json_encode([
                [
                    'userid' => $instance->Id,
                    'password' => $instance->password,
                    'pdfmode' => 'health',
                    'policynum' => $policynum
                ]
            ]);
            SaveFile($payload, 'bajajpolicypdfrequest.txt');
            curl_setopt_array($curl, [
                CURLOPT_URL => $instance->baseurl . 'ext/common/commoncs/BjazDownloadPDFWs/policypdfdownload',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'x-api-key: 2O5czaeucN6ROiVrUy7hu1LEyf9rX7ZF959FCKWo'
                ],
            ]);
            $response = curl_exec($curl);
            SaveFile($payload, 'bajajpolicypdfresponse.txt');
            curl_close($curl);
            Log::info(['bajaj_policypdf_Response' => $response]);
            return $response;
        } catch (\Exception $e) {
            return ErrMessage($e);
        }
    }
}
