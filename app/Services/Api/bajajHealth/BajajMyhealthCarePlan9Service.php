<?php
//----------------------------------test---------------------------
namespace App\Services\Api\bajajHealth;

use Illuminate\Support\Facades\{Auth, Log};
use Illuminate\Support\Facades\{Cache};
use Illuminate\Support\Facades\Session;
use App\Models\JourneyUsers;
use App\Models\Insure;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\HealthJourney;
use App\Models\CareToken;
use App\Models\Bajaj\BajajPincode;
use Carbon\Carbon;
use App\Http\Controllers\front\health\caresupreme\CareSupremeController;
use App\Http\Controllers\front\health\bajajmyhealth\BjajaMyHealthController;
use DateTime;

class BajajMyhealthCarePlan9Service
{

    private static $securityKey = "kycwsbrkmotr2023";
    private static $iv = "kycwsbrkmotr2023";

    private $Id;
    private $imdCode;
    private $password;
    private $deptcode;
    private $baseurl;
    public static function initlize()
    {
        //dd(getconstant('HEALTH.BAJAJPLAN9.CREDENTIAL.ID'));
        $instance = new self();
        $instance->Id = getconstant('HEALTH.BAJAJPLAN9.CREDENTIAL.ID');
        $instance->imdCode = getconstant('HEALTH.BAJAJPLAN9.CREDENTIAL.IMDCODE');
        $instance->password = getconstant('HEALTH.BAJAJPLAN9.CREDENTIAL.PASSWORD');
        $instance->deptcode = getconstant('HEALTH.BAJAJPLAN9.CREDENTIAL.DEPTCODE');
        $instance->baseurl = "https://api.uat.bajajgeneral.com/";
        return $instance;
    }

    public static function getBasicPlan($params)
    {
        try {
            //dd("kjhkhj");
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
            $memrelation = $data[0]['name'] ?? "";
            $memage = $data[0]['age'] ?? "";
            $memgender = "F";
            //$cachecovertype = 'cache_covertype_' . $userId;
            //$producttype = GetCache($cachecovertype) ?? '1';
            $cachehealthplan = 'cache_healthplan_' . $userId;
            $plantype = GetCache($cachehealthplan) ?? "";
            $member = RedisGet('Member:' . $userId);
            $businesstype = "";
            if ($plantype == "1") {
                $businesstype = "NB";
            } else {
                $businesstype = "PORT";
            }
            $productcode = "8456";
            $today = now()->format('Y-m-d');
            $startdate = now()->format('d-M-Y');
            $enddate = now()->addYear()->subDay()->format('d-M-Y');
            $dob = Carbon::createFromFormat('d-m-Y', $data[0]['dob'])->format('d-M-Y');
            $journeydata = HealthJourney::where('userid', $userId)
                ->where('vid', getconstant("HEALTH.BAJAJPLAN9.KEY"))
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
                    if (strpos($addon, 'addon35') === 0) {
                        $aAddons['addon35'] = substr($addon, 7);
                        continue;
                    }
                    if (strpos($addon, 'globalCover') === 0) {
                        $aAddons['globalCover'] = str_replace(
                            '%',
                            '',
                            str_replace('globalCover', '', $addon)
                        );
                        continue;
                    }
                    if (strpos($addon, 'polcovvolntrycp') === 0) {
                        $aAddons['polcovvolntrycp'] = str_replace(
                            '%',
                            '',
                            str_replace('polcovvolntrycp', '', $addon)
                        );
                        continue;
                    }
                    if (strpos($addon, 'pSuperCumulativeBonusOption') === 0 || strpos($addon, 'superCumulativeBonusOption') === 0) {
                        // Agar frontend se direct "100-200" jaisi value aa rahi hai bina prefix ke
                        $val = str_replace(['pSuperCumulativeBonusOption', 'superCumulativeBonusOption'], '', $addon);
                        $aAddons['pSuperCumulativeBonusOption'] = trim($val);
                        continue;
                    }
                    if (strpos($addon, 'pCumulativeBonusOptions') === 0 || strpos($addon, 'cumulativeBonusOptions') === 0) {
                        $val = str_replace(['pCumulativeBonusOptions', 'cumulativeBonusOptions'], '', $addon);
                        $aAddons['pCumulativeBonusOptions'] = trim($val);
                        continue;
                    }


                    if (strpos($addon, 'polcov51') === 0) {

                        $aAddons['polcov51'] = 'Y';

                        $val = trim(str_replace('polcov51', '', $addon));

                        if (!empty($val)) {
                            $aAddons['polcov52'] = $val;
                        }

                        continue;
                    }

                    if (strpos($addon, 'polcov52') === 0) {

                        $aAddons['polcov52'] =
                            trim(str_replace(
                                'polcov52',
                                '',
                                $addon
                            ));

                        continue;
                    }

                    preg_match('/^([a-zA-Z]+)(\d*)$/', $addon, $matches);
                    $char = $matches[1] ?? '';
                    $num = $matches[2] ?? '';

                    if (
                        in_array($char, [
                            'ped',
                            'phe',
                            'prhe',
                            'sdwp',
                            'addon',
                            'ageShield',
                            'pconsumables',
                            'consumablesPlus',
                            'pDeductable',
                            'costOfPrescribedExternalMedicalAid',
                            'globalCover',
                            'polcovvolntrycp',
                            'healthLimitless',
                            'addonFetalFlourish',
                            'instaShield',
                            'nRInsure',
                            'smartTenure',
                            'stepUpBenefit',
                            'pSuperCumulativeBonusOption',
                            'pCumulativeBonusOptions',
                            'polcov51',
                            'polcov52',
                            'pDoubleSumInsuredBenefit',
                            'pProcedureWiseSubLimit',
                            'asthma',
                            'diabetes',
                            'hypertension',
                            'hyperlipidaemia',
                            'hypothyroidism',
                            'obesity',
                            'noneOfTheAbove',
                            'airAmbulance'
                        ])
                    ) {
                        if ($char === 'addon') {
                            $aAddons[$char . $num] = $num;
                        } else {
                            $aAddons[$char] = $num;
                        }
                    }
                }
            }
            $RR = "";
            if ($coverage >= 3 && $coverage <= 10) {
                $RR = "SPA";
            } else if ($coverage >= 10) {
                $RR = "Actual";
            } else {
                $RR = "SPA";
            }
            $password = $instance->password;
            $Id = $instance->Id;
            $imdCode = $instance->imdCode;
            $deptcode = $instance->deptcode;

            $postfields = [
                "password" => $password,
                "userid" => $Id,
                "sourcedtls" => [
                    "username" => "",
                    "departmentcode" => "9906",
                    "productcode" => $productcode,
                    "businesstype" => $businesstype ?? "",
                    "imdcode" => $imdCode ?? "",
                    "subimdcode" => "",
                    "modulename" => "WEBSERVICE"
                ],
                "policydtls" => [
                    "remarks" => "http://uat.digibima.com/health/vendors/bajaj/payment/thankyou",
                    "termstartdate" => $startdate ?? "",
                    "termenddate" => $enddate ?? "",
                    "partnertype" => "P",
                    "businesstype" => $businesstype ?? "",
                    "policyperiod" => $policyperiod ?? "1200",
                    "productcode" => $productcode ?? "",
                    "scrlocationcode" => "9906",
                    "paymentmode" => "",
                    "scrcategory" => $businesstype ?? "",
                    "deptcode" => $deptcode ?? "",
                ],
                "tycpdetails" => [
                    "telephone" => "",
                    "nationality" => "",
                    "email" => "",
                    "surname" => "",
                    "contact1" => "",
                    "dateofbirth" => "",
                    "firstname" => "",
                    "middlename" => "",
                    "sex" => ""
                ],
                "tycpaddrlist" => [
                    [
                        "state" => "",
                        "postcode" => $pincode ?? "400072",
                        "addressline1" => "",
                        "addressline2" => "",
                        "addressline3" => "",
                        "addressline4" => "",
                        "addressline5" => ""
                    ]
                ],
                "channeldtls" => [
                    "subimdcode" => "",
                    "imdcode" => $imdCode ?? ""
                ],
                "previnsdtls" => [
                    "noofclaims" => "0",
                    "previnsname" => "",
                    "previnsaddress" => "",
                    "previnspolicyno" => "",
                    "prevpolicyexpirydate" => ""
                ],

                "hcpdtmemlist" => [
                    [
                        "membername" => "",
                        "memrelation" => $memrelation ?? "",
                        "memdob" => $dob ?? "",
                        "memage" => $memage ?? "",
                        "memgender" => $memgender ?? "",
                        "memheightcm" => "",
                        "memweightkg" => "",
                        "membmi" => "",
                        "memoccupation" => $proposerDetails->occupation ?? "doctor",
                        "memgrossmonthlyincome" => "",
                        "mempreexistdisease" => "0",
                        "memsmkertbco" => "0",
                        "memasthma" => "0",
                        "memcholstrldisordr" => "0",
                        "memheartdisease" => "0",
                        "memhypertension" => "0",
                        "memdiabetes" => "0",
                        "memobesity" => "0",
                        "memaddflag" => "N",
                        "selfcoveredflag" => "Y",
                        "memnomineename" => "",
                        "memnomineerelation" => "",
                        "memprvsi" => "",
                        "roomRent" => isset($aAddons['RR']) ? $aAddons['RR'] : $RR,
                        "pedWaitingPeriodInMonths" => isset($aAddons['ped']) ? ($aAddons['ped'] * 12) : '36',
                        "postHospitalizationExpenses" => isset($aAddons['phe']) ? $aAddons['phe'] : '90',
                        "preHospitalizationExpenses" => isset($aAddons['prhe']) ? $aAddons['prhe'] : '60',
                        "specificDiseaseWaitingPeriod" => isset($aAddons['sdwp']) ? ($aAddons['sdwp'] * 12) : '24',
                        "bahcpdtmemparam91" => null,
                        "bahcpdtmemparam41" => null
                    ]
                ],
                "hcpdtmemcovlist" => [
                    [
                        "membername" => "",
                        "memiptreatsi" => $sI ?? "1000000",
                        "memiptreatplan" => "Plan 9",
                        "memiptreatsubplan" => "MHCP Plan 9",
                        "memaddflag" => "N",
                        "selfcoveredflag" => "Y",
                        "memmatnormsi" => "0",
                        "memmatcesrnsi" => "0",
                        "memcoonbnftsi" => "",
                        "meminschldsi" => ""
                    ]
                ],
                "hcpdtpolcovobj" => [
                    "polcovzone" => $polcovzone,
                    "polcov41" => "NA",
                    "polcovvolntrycp" => $aAddons['polcovvolntrycp'] ?? '',
                    "pDeductible" => isset($aAddons['pDeductable']) ? (int) $aAddons['pDeductable'] : 0,
                    "polcovspcndt" => "NA",
                    "polcovmedcnd" => "N",
                    "polcov91" => "NA",
                    "polcovihtsi" => $sI ?? "1000000",
                    "polcov51" => isset($aAddons['polcov51']) ? 'Y' : 'N',

                    "polcov52" => isset($aAddons['polcov51'])
                        ? ($aAddons['polcov52'] ?? ($totalmembernos == 1 ? 'IndOption1' : 'FltOption1'))
                        : '',
                    "pDoubleSumInsuredBenefit" => isset($aAddons['pDoubleSumInsuredBenefit']) ? '1' : '0',
                    "pProcedureWiseSubLimit" => isset($aAddons['pProcedureWiseSubLimit']) ? '1' : '0',
                    "polcovihtpln" => "",
                    "polcovmatexpnorm" => "0",
                    "polcovmatexpcsrn" => "0",
                    "polcov46" => "0",
                    "pSuperCumulativeBonusOption" => isset($aAddons['pSuperCumulativeBonusOption'])
                        ? str_replace(' upto ', '-', $aAddons['pSuperCumulativeBonusOption'])
                        : "NA",

                    "pCumulativeBonusOptions" => isset($aAddons['pCumulativeBonusOptions'])
                        ? str_replace(['% upto ', ' upto '], '-', $aAddons['pCumulativeBonusOptions'])
                        : "NA",
                ],
                "hcpdtmemcovaddonlist" => [
                    [
                        "addonairambcv" => $aAddons['addon35'] ?? "",
                        "membername" => "",
                        "addon23" => isset($aAddons['addon23']) ? '1' : '0',
                        "addon24" => isset($aAddons['addon24']) ? '1' : '0',
                        "addon25" => isset($aAddons['addon25']) ? '1' : '0',
                        "ageShield" => isset($aAddons['ageShield']) ? '1' : '0',
                        "consumablesPlus" => isset($aAddons['consumablesPlus']) ? '1' : '0',
                        "healthLimitless" => isset($aAddons['healthLimitless']) ? '1' : '0',
                        "addonFetalFlourish" => isset($aAddons['addonFetalFlourish']) ? '1' : '0',
                        "instaShield" => isset($aAddons['instaShield']) ? '1' : '0',
                        "asthma" => isset($aAddons['asthma']) ? 'Yes' : '',
                        "diabetes" => isset($aAddons['diabetes']) ? 'Yes' : '',
                        "hypertension" => isset($aAddons['hypertension']) ? 'Yes' : '',
                        "hyperlipidaemia" => isset($aAddons['hyperlipidaemia']) ? 'Yes' : '',
                        "hypothyroidism" => isset($aAddons['hypothyroidism']) ? 'Yes' : '',
                        "obesity" => isset($aAddons['obesity']) ? 'Yes' : '',
                        "noneOfTheAbove" => isset($aAddons['noneOfTheAbove']) ? 'Yes' : '',
                        "nRInsure" => isset($aAddons['nRInsure']) ? '1' : '0',
                        "stepUpBenefit" => isset($aAddons['stepUpBenefit']) ? '1' : '0',
                        "pConsumables" => isset($aAddons['pConsumables']) ? '1' : '0',
                        //    "pDeductable" => $aAddons['pDeductable'] ?? '',
                        "addon35" => $aAddons['addon35'] ?? '',
                        "globalCover" => $aAddons['globalCover'] ?? '',
                        "smartTenure" => $aAddons['smartTenure'] ?? '',
                        "costOfPrescribedExternalMedicalAid" => isset($aAddons['costOfPrescribedExternalMedicalAid'])
                            ? (string) ($sI * 0.10)
                            : '',

                        "memaddflag" => "N"
                    ]
                ],
                "hcpstagedataobj" => [
                    "busstype" => $businesstype ?? "NB",
                    "productcode" => $productcode,
                    "termstartdate" => $startdate,
                    "termenddate" => $enddate,
                    "selfcoveredflag" => "Y",
                    "membercombo" => $member ?? "1A",
                    "totalmembernos" => $totalmembernos,
                    "partnerpincode" => $pincode
                ]
            ];

            //return $postfields;
            $requestdata = json_encode($postfields);
            // dd($requestdata);
            SaveFile($requestdata, 'bajaj_myhealth_quote_request9.txt');
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.uat.bajajgeneral.com/bagicHws/health/healthpremiumcal',
                // CURLOPT_URL => $instance->baseurl . 'bagicHws/health/healthpremiumcal',
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
            //dd($response);
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
            // dd($data);
            $sI = 100000 * $coverage;
            $time = $params['tenure'] ?? '1';
            $policyperiod = $time * 1200;
            $productcode = "8457";
            $today = now()->format('Y-m-d');
            $startdate = now()->format('d-M-Y');
            $enddate = now()->addYear()->subDay()->format('d-M-Y');
            $dob = Carbon::createFromFormat('d-m-Y', $data[0]['dob'])->format('d-M-Y');

            $totalmembernos = count($data) ?? 1;
            $memrelation = $data[0]['name'] ?? "Self";
            $membercombo = RedisGet('Member:' . $userId);
            $memage = $data[0]['age'] ?? "28";
            $memgender = "F";
            $cachecovertype = 'cache_covertype_' . $userId;
            $producttype = GetCache($cachecovertype) ?? '1';
            $cachehealthplan = 'cache_healthplan_' . $userId;
            $plantype = GetCache($cachehealthplan) ?? '';
            //             dd([
//     'cache_key' => $cachehealthplan,
//     'plantype' => $plantype
// ]);
            $businesstype = "";
            if ($plantype == "1") {
                $businesstype = "NB";
            } else {
                $businesstype = "PORT";
            }

            $journeydata = HealthJourney::where('userid', $userId)
                ->where('vid', getconstant("HEALTH.BAJAJPLAN9.KEY"))
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
                    if (strpos($addon, 'addon35') === 0) {
                        $aAddons['addon35'] = substr($addon, 7);
                        continue;
                    }
                    if (strpos($addon, 'pDeductable') === 0) {
                        $aAddons['pDeductable'] = str_replace('pDeductable', '', $addon);
                        continue;
                    }
                    if (strpos($addon, 'globalCover') === 0) {
                        $aAddons['globalCover'] = str_replace(
                            '%',
                            '',
                            str_replace('globalCover', '', $addon)
                        );
                        continue;
                    }
                    if (strpos($addon, 'polcovvolntrycp') === 0) {
                        $aAddons['polcovvolntrycp'] = str_replace(
                            '%',
                            '',
                            str_replace('polcovvolntrycp', '', $addon)
                        );
                        continue;
                    }
                    if (strpos($addon, 'pSuperCumulativeBonusOption') === 0 || strpos($addon, 'superCumulativeBonusOption') === 0) {
                        // Agar frontend se direct "100-200" jaisi value aa rahi hai bina prefix ke
                        $val = str_replace(['pSuperCumulativeBonusOption', 'superCumulativeBonusOption'], '', $addon);
                        $aAddons['pSuperCumulativeBonusOption'] = trim($val);
                        continue;
                    }
                    if (strpos($addon, 'pCumulativeBonusOptions') === 0 || strpos($addon, 'cumulativeBonusOptions') === 0) {
                        $val = str_replace(['pCumulativeBonusOptions', 'cumulativeBonusOptions'], '', $addon);
                        $aAddons['pCumulativeBonusOptions'] = trim($val);
                        continue;
                    }
                    if (strpos($addon, 'polcov51') === 0) {

                        $aAddons['polcov51'] = 'Y';

                        $val = trim(str_replace('polcov51', '', $addon));

                        if (!empty($val)) {
                            $aAddons['polcov52'] = $val;
                        }

                        continue;
                    }


                    if (strpos($addon, 'polcov52') === 0) {

                        $aAddons['polcov52'] =
                            trim(str_replace(
                                'polcov52',
                                '',
                                $addon
                            ));

                        continue;
                    }
                    preg_match('/^([a-zA-Z]+)(\d*)$/', $addon, $matches);
                    $char = $matches[1] ?? '';
                    $num = $matches[2] ?? '';

                    if (
                        in_array($char, [
                            'ped',
                            'phe',
                            'prhe',
                            'sdwp',
                            'addon',
                            'ageShield',
                            'consumables',
                            'consumablesPlus',
                            'pDeductable',
                            'costOfPrescribedExternalMedicalAid',
                            'globalCover',
                            'polcovvolntrycp',
                            'healthLimitless',
                            'addonFetalFlourish',
                            'instaShield',
                            'nRInsure',
                            'smartTenure',
                            'stepUpBenefit',
                            'pSuperCumulativeBonusOption',
                            'pCumulativeBonusOptions',
                            'polcov51',
                            'polcov52',
                            'doubleSumInsuredBenefit',
                            'pProcedureWiseSubLimit',
                            'asthma',
                            'diabetes',
                            'hypertension',
                            'hyperlipidaemia',
                            'hypothyroidism',
                            'obesity',
                            'noneOfTheAbove',
                            'airAmbulance',
                        ])
                    ) {
                        if ($char === 'addon') {
                            $aAddons[$char . $num] = $num;
                        } else {
                            $aAddons[$char] = $num;
                        }
                    }
                }
                // dd($aAddons);
            }
            $RR = "";
            if ($coverage >= 3 && $coverage <= 10) {
                $RR = "SPA";
            } else if ($coverage >= 10) {
                $RR = "Actual";
            } else {
                $RR = "SPA";
            }
            $hcpdtmemlist = [];
            $hcpdtmemcovlist = [];
            $hcpdtmemcovaddonlist = [];
            $relation = "";

            foreach ($data as $member) {
                $memberDob = Carbon::createFromFormat(
                    'd-m-Y',
                    $member['dob']
                )->format('d-M-Y');
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
                    "membername" => "",
                    "memrelation" => $relation ?? "",
                    "memdob" => $memberDob,
                    "memage" => $member['age'],
                    "memgender" => $gender,
                    "memheightcm" => "",
                    "memweightkg" => "",
                    "membmi" => "",
                    "memoccupation" => $proposerDetails->occupation ?? "doctor",
                    "memgrossmonthlyincome" => "",
                    "mempreexistdisease" => "0",
                    "memsmkertbco" => "0",
                    "memasthma" => "0",
                    "memcholstrldisordr" => "0",
                    "memheartdisease" => "0",
                    "memhypertension" => "0",
                    "memdiabetes" => "0",
                    "memobesity" => "0",
                    "memaddflag" => "Y",
                    "selfcoveredflag" => strtolower($member['name']) == "self" ? "Y" : "N",
                    "roomRent" => isset($aAddons['RR']) ? $aAddons['RR'] : $RR,
                    "pedWaitingPeriodInMonths" => isset($aAddons['ped']) ? ($aAddons['ped'] * 12) : '36',
                    "postHospitalizationExpenses" => isset($aAddons['phe']) ? $aAddons['phe'] : '90',
                    "preHospitalizationExpenses" => isset($aAddons['prhe']) ? $aAddons['prhe'] : '60',
                    "specificDiseaseWaitingPeriod" => isset($aAddons['sdwp']) ? ($aAddons['sdwp'] * 12) : '24',
                ];
                $hcpdtmemcovlist[] = [
                    "membername" => "",
                    "memiptreatsi" => $sI ?? "",
                    "memiptreatplan" => "Plan 9",
                    "memiptreatsubplan" => "MHCP Plan 9",
                    "memaddflag" => "Y",
                    "selfcoveredflag" => strtolower($member['name']) == "self" ? "Y" : "N",
                    "memmatnormsi" => "0",
                    "memmatcesrnsi" => "0",
                    "memcoonbnftsi" => "",
                    "meminschldsi" => ""
                ];
                $hasDisease =
                    isset($aAddons['asthma']) ||
                    isset($aAddons['diabetes']) ||
                    isset($aAddons['hypertension']) ||
                    isset($aAddons['hyperlipidaemia']) ||
                    isset($aAddons['hypothyroidism']) ||
                    isset($aAddons['obesity']);
                $hcpdtmemcovaddonlist[] = [
                    "membername" => "",
                    "addonairambcv" => $aAddons['addon35'] ?? "",
                    "addon23" => isset($aAddons['addon23']) ? '1' : '0',
                    "addon24" => isset($aAddons['addon24']) ? '1' : '0',
                    "addon25" => isset($aAddons['addon25']) ? '1' : '0',
                    "ageShield" => isset($aAddons['ageShield']) ? '1' : '0',
                    "consumablesPlus" => isset($aAddons['consumablesPlus']) ? '1' : '0',
                    "healthLimitless" => isset($aAddons['healthLimitless']) ? '1' : '0',
                    "addonFetalFlourish" => isset($aAddons['addonFetalFlourish']) ? '1' : '0',
                    "instaShield" => isset($aAddons['instaShield']) ? '1' : '0',
                    "asthma" => isset($aAddons['asthma']) ? 'yes' : '',
                    "diabetes" => isset($aAddons['diabetes']) ? 'yes' : '',
                    "hypertension" => isset($aAddons['hypertension']) ? 'yes' : '',
                    "hyperlipidaemia" => isset($aAddons['hyperlipidaemia']) ? 'yes' : '',
                    "hypothyroidism" => isset($aAddons['hypothyroidism']) ? 'yes' : '',
                    "obesity" => isset($aAddons['obesity']) ? 'yes' : '',
                    "noneOfTheAbove" => !$hasDisease ? 'Yes' : '',
                    "nRInsure" => isset($aAddons['nRInsure']) ? '1' : '0',
                    "stepUpBenefit" => isset($aAddons['stepUpBenefit']) ? '1' : '0',
                    "consumables" => isset($aAddons['consumables']) ? '1' : '0',



                    "addon35" => $aAddons['addon35'] ?? '',
                    "globalCover" => $aAddons['globalCover'] ?? '',
                    "smartTenure" => $aAddons['smartTenure'] ?? '',
                    "costOfPrescribedExternalMedicalAid" => isset($aAddons['costOfPrescribedExternalMedicalAid'])
                        ? (int) ($sI * 0.10)
                        : '',

                    "memaddflag" => "N"
                ];
            }
            // dd($hcpdtmemcovaddonlist);
            $hcpdtpolcovobj = [
                "polcovzone" => $polcovzone ?? "",
                "polcov41" => "NA",
                "polcovvolntrycp" => $aAddons['polcovvolntrycp'] ?? '',
                "consumables" => isset($aAddons['consumables']) ? 'Y' : 'N',
                // "pDeductable" => $aAddons['pDeductable'] ?? '',
                "polcovspcndt" => "NA",
                "polcovmedcnd" => "N",
                "polcov91" => "NA",
                "polcovihtsi" => $sI ?? "",
                "polcov51" => isset($aAddons['polcov51']) ? 'Y' : 'N',

                "polcov52" => isset($aAddons['polcov51'])
                    ? ($aAddons['polcov52'] ?? ($totalmembernos == 1 ? 'IndOption1' : 'FltOption1'))
                    : '',
                "doubleSumInsuredBenefit" =>
                    isset($aAddons['doubleSumInsuredBenefit']) ? 'Y' : 'N',
                "pProcedureWiseSubLimit" => isset($aAddons['pProcedureWiseSubLimit']) ? '1' : '0',
                "polcovihtpln" => "",
                "polcovmatexpnorm" => "0",
                "polcovmatexpcsrn" => "0",
                "polcov46" => "0",
                "pSuperCumulativeBonusOption" => isset($aAddons['pSuperCumulativeBonusOption'])
                    ? str_replace(' upto ', '-', $aAddons['pSuperCumulativeBonusOption'])
                    : "NA",

                "pCumulativeBonusOptions" => isset($aAddons['pCumulativeBonusOptions'])
                    ? str_replace(['% upto ', ' upto '], '-', $aAddons['pCumulativeBonusOptions'])
                    : "NA",
            ];
            // dd($hcpdtpolcovobj);
            $password = $instance->password;
            $Id = $instance->Id;
            $imdCode = $instance->imdCode;
            $deptcode = $instance->deptcode;
            //return "asdff";
            $postfeilds = [
                "password" => $password,
                "userid" => $Id,
                "sourcedtls" => [
                    "username" => "",
                    "departmentcode" => "9906",
                    "productcode" => $productcode ?? "8457",
                    "businesstype" => $businesstype ?? "",
                    "imdcode" => $imdCode,
                    "subimdcode" => "",
                    "modulename" => "WEBSERVICE"
                ],
                "policydtls" => [
                    "remarks" => "http://uat.digibima.com/health/vendors/bajaj/payment/thankyou",
                    "termstartdate" => $startdate ?? "",
                    "termenddate" => $enddate ?? "",
                    "partnertype" => "P",
                    "businesstype" => $businesstype ?? "",
                    "policyperiod" => $policyperiod ?? "",
                    "productcode" => $productcode ?? "8457",
                    "scrlocationcode" => "9906",
                    "paymentmode" => "CC",
                    "scrcategory" => "NB",
                    "deptcode" => $deptcode
                ],
                "tycpdetails" => [
                    "telephone" => "",
                    "nationality" => "",
                    "email" => "",
                    "surname" => "",
                    "contact1" => "",
                    "dateofbirth" => "",
                    "firstname" => "",
                    "middlename" => "",
                    "sex" => $memgender ?? ""
                ],
                "tycpaddrlist" => [
                    [
                        "state" => "",
                        "postcode" => $pincode ?? "",
                        "addressline1" => "",
                        "addressline2" => "",
                        "addressline3" => "",
                        "addressline4" => "",
                        "addressline5" => ""
                    ]
                ],
                "channeldtls" => [
                    "subimdcode" => "",
                    "imdcode" => $imdCode
                ],
                "previnsdtls" => [
                    "noofclaims" => "0",
                    "previnsname" => "",
                    "previnsaddress" => "",
                    "previnspolicyno" => "",
                    "prevpolicyexpirydate" => ""
                ],
                "hcpdtmemlist" => $hcpdtmemlist,
                "hcpdtmemcovlist" => $hcpdtmemcovlist,
                "hcpdtpolcovobj" => [
                    "polcovzone" => $polcovzone ?? "",
                    "polcov41" => "NA",
                    "polcovvolntrycp" => $aAddons['polcovvolntrycp'] ?? '',
                    "pDeductable" => $aAddons['pDeductable'] ?? '',
                    "consumables" => isset($aAddons['consumables']) ? 'Y' : 'N',
                    "polcovspcndt" => "NA",
                    "polcovmedcnd" => "N",
                    "polcov91" => "NA",
                    "polcovihtsi" => $sI ?? "",
                    "polcov51" => isset($aAddons['polcov51']) ? 'Y' : 'N',

                    "polcov52" => isset($aAddons['polcov51'])
                        ? ($aAddons['polcov52'] ?? ($totalmembernos == 1 ? 'IndOption1' : 'FltOption1'))
                        : '',
                    "doubleSumInsuredBenefit" => isset($aAddons['doubleSumInsuredBenefit']) ? 'Y' : '',
                    "pProcedureWiseSubLimit" => isset($aAddons['pProcedureWiseSubLimit']) ? '1' : '0',
                    "polcovihtpln" => "",
                    "polcovmatexpnorm" => "0",
                    "polcovmatexpcsrn" => "0",
                    "polcov46" => "0",
                    "pSuperCumulativeBonusOption" => isset($aAddons['pSuperCumulativeBonusOption'])
                        ? str_replace(' upto ', '-', $aAddons['pSuperCumulativeBonusOption'])
                        : "NA",

                    "pCumulativeBonusOptions" => isset($aAddons['pCumulativeBonusOptions'])
                        ? str_replace(['% upto ', ' upto '], '-', $aAddons['pCumulativeBonusOptions'])
                        : "NA",
                ],
                "hcpdtmemcovaddonlist" => $hcpdtmemcovaddonlist,
                "hcpstagedataobj" => [
                    "busstype" => $businesstype ?? "",
                    "productcode" => $productcode ?? "8457",
                    "termstartdate" => $startdate ?? "",
                    "termenddate" => $enddate ?? "",
                    "selfcoveredflag" => "Y",
                    "membercombo" => $membercombo ?? "2A",
                    "totalmembernos" => $totalmembernos ?? "2",
                    "partnerpincode" => $pincode ?? ""
                ]
            ];

            //return $postfeilds;
            $requestdata = json_encode($postfeilds);
            SaveFile($requestdata, 'bajaj_myhealth_quote_floater_request9.txt');
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.uat.bajajgeneral.com/bagicHws/health/healthpremiumcal',
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
            // dd([
//     'request' => $hcpdtpolcovobj,
//     'response' => json_decode($response, true)
// ]);

        } catch (\Exception $e) {
            return ErrMessage($e);
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
    public static function creatPolicy(Request $request, $transicationid = null)
    {
        try {
            $userId = $request->userid;
            $user = User::find($userId);
            $instance = self::initlize();
            $user1 = HealthJourney::where('userid', $user)->where('vid', getconstant("HEALTH.BAJAJPLAN9.KEY"))->first();
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
            $coverage = GetCache($cachecoverage) ?? "";
            $sI = 100000 * $coverage;
            $cachetenure = 'cache_tenure_' . $userId;
            $tenure = GetCache($cachetenure) ?? '1';
            $policyperiod = $tenure * 1200;
            $data = Insure::where('proposalid', $userId)->get()->toArray();
            $totalmembernos = count($data) ?? 1;
            $memrelation = $data[0]['name'] ?? "Self";
            $memage = $data[0]['age'] ?? "28";
            $memgender = "F";
            $cachecovertype = 'cache_covertype_' . $userId;
            $producttype = GetCache($cachecovertype) ?? '1';
            $cachehealthplan = 'cache_healthplan_' . $userId;
            $plantype = GetCache($cachehealthplan) ?? '1';
            $businesstype = "";
            if ($plantype == "1") {
                $businesstype = "NB";
            } else {
                $businesstype = "PORT";
            }


            $productcode = "8456";
            $today = now()->format('Y-m-d');
            $startdate = now()->format('d-M-Y');
            $enddate = now()->addYear()->subDay()->format('d-M-Y');
            $dob = date('d-M-Y', strtotime("-{$data[0]['age']} years", strtotime($today)));
            $user1 = HealthJourney::where('userid', $userId)->where('vid', getconstant("HEALTH.BAJAJPLAN9.KEY"))->first();
            $cacheAddon = 'cache_addons_' . $userId;

            $addons = GetCache($cacheAddon);

            if (is_string($addons)) {
                $addons = json_decode($addons, true);
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
                    if (strpos($addon, 'addon35') === 0) {
                        $aAddons['addon35'] = substr($addon, 7);
                        continue;
                    }
                    if (strpos($addon, 'pDeductable') === 0) {
                        $aAddons['pDeductable'] = str_replace('pDeductable', '', $addon);
                        continue;
                    }
                    if (strpos($addon, 'globalCover') === 0) {
                        $aAddons['globalCover'] = str_replace(
                            '%',
                            '',
                            str_replace('globalCover', '', $addon)
                        );
                        continue;
                    }
                    if (strpos($addon, 'polcovvolntrycp') === 0) {
                        $aAddons['polcovvolntrycp'] = str_replace(
                            '%',
                            '',
                            str_replace('polcovvolntrycp', '', $addon)
                        );
                        continue;
                    }
                    if (strpos($addon, 'pSuperCumulativeBonusOption') === 0 || strpos($addon, 'superCumulativeBonusOption') === 0) {
                        // Agar frontend se direct "100-200" jaisi value aa rahi hai bina prefix ke
                        $val = str_replace(['pSuperCumulativeBonusOption', 'superCumulativeBonusOption'], '', $addon);
                        $aAddons['pSuperCumulativeBonusOption'] = trim($val);
                        continue;
                    }
                    if (strpos($addon, 'pCumulativeBonusOptions') === 0 || strpos($addon, 'cumulativeBonusOptions') === 0) {
                        $val = str_replace(['pCumulativeBonusOptions', 'cumulativeBonusOptions'], '', $addon);
                        $aAddons['pCumulativeBonusOptions'] = trim($val);
                        continue;
                    }
                    if (strpos($addon, 'polcov51') === 0) {

                        $aAddons['polcov51'] = 'Y';

                        $val = trim(str_replace('polcov51', '', $addon));

                        if (!empty($val)) {
                            $aAddons['polcov52'] = $val;
                        }

                        continue;
                    }


                    if (strpos($addon, 'polcov52') === 0) {

                        $aAddons['polcov52'] =
                            trim(str_replace(
                                'polcov52',
                                '',
                                $addon
                            ));

                        continue;
                    }
                    preg_match('/^([a-zA-Z]+)(\d*)$/', $addon, $matches);
                    $char = $matches[1] ?? '';
                    $num = $matches[2] ?? '';

                    if (
                        in_array($char, [
                            'ped',
                            'phe',
                            'prhe',
                            'sdwp',
                            'addon',
                            'ageShield',
                            'consumables',
                            'consumablesPlus',
                            'pDeductable',
                            'costOfPrescribedExternalMedicalAid',
                            'globalCover',
                            'polcovvolntrycp',
                            'healthLimitless',
                            'addonFetalFlourish',
                            'instaShield',
                            'nRInsure',
                            'smartTenure',
                            'stepUpBenefit',
                            'pSuperCumulativeBonusOption',
                            'pCumulativeBonusOptions',
                            'polcov51',
                            'polcov52',
                            'doubleSumInsuredBenefit',
                            'pProcedureWiseSubLimit',
                            'asthma',
                            'diabetes',
                            'hypertension',
                            'hyperlipidaemia',
                            'hypothyroidism',
                            'obesity',
                            'noneOfTheAbove',
                            'airAmbulance',
                        ])
                    ) {
                        if ($char === 'addon') {
                            $aAddons[$char . $num] = $num;
                        } else {
                            $aAddons[$char] = $num;
                        }
                    }
                }
                // dd($aAddons);
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
                'dob' => $dob ?? "",
                'gender' => (strtoupper($user1->gender) == 'MS') ? 'F' : 'M',
                'height' => $proposerDetails->height ?? "",
                'weight' => $proposerDetails->weight ?? "",
                'house' => strtoupper($permanentAddress->address1) ?? "",
                'colony' => strtoupper($permanentAddress->address2) ?? "",
                'city' => $permanentAddress->city ?? "",
                'state' => $permanentAddress->state ?? "",
                'pincode' => $user->pincode ?? "",
                'mobile' => $contactDetails->contactmobile ?? "",
                'email' => $contactDetails->contactemail ?? $user->email,
                'panId' => strtoupper($user1->pan),
                'title' => strtoupper($user1->gender),
                'firstName' => $user1->name ?? "",
                'lastName' => count($namePart) > 1 ? end($namePart) : 'Unknown',
                'bankIFSC' => $bankIFSC ?? "",
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
            //dd($nomineerelation);
            $portReason = "";
            $prevdetails = [];
            if ($businesstype == "PORT") {
                $portReason = "Product is not suitable";
                $prevdetails = [
                    "noofclaims" => "0",
                    "previnsname" => "Acko General Insurance Co.Ltd.",
                    "previnsaddress" => "adsdfgfg",
                    "previnspolicyno" => "12123435456",
                    "prevpolicyexpirydate" => "25-Nov-2025",
                    "sumInsuredInPreviousPolicy" => 500000
                ];
            } else {
                $portReason = "";
                $prevdetails = [
                    "noofclaims" => "0",
                    "previnsname" => "",
                    "previnsaddress" => "",
                    "previnspolicyno" => "",
                    "prevpolicyexpirydate" => "",
                ];
            }

            $password = $instance->password;
            $Id = $instance->Id;
            $imdCode = $instance->imdCode;
            $deptcode = $instance->deptcode;

            $postData = json_encode(
                [
                    "password" => $password ?? "",
                    "userid" => $Id ?? "",
                    "sourcedtls" => [
                        "username" => $userData['firstName'] ?? "",
                        "departmentcode" => "9906",
                        "productcode" => $productcode ?? "",
                        "businesstype" => $businesstype ?? "",
                        "imdcode" => $imdCode ?? "",
                        "subimdcode" => "",
                        "modulename" => "WEBSERVICE"
                    ],
                    "policydtls" => [
                        "remarks" => "http://uat.digibima.com/health/vendors/bajaj/payment/thankyou",
                        "termstartdate" => $startdate ?? "",
                        "termenddate" => $enddate ?? "",
                        "partnertype" => "P",
                        "businesstype" => $businesstype ?? "",
                        "policyperiod" => $policyperiod ?? "",
                        "productcode" => $productcode ?? "",
                        "scrlocationcode" => "9906",
                        "paymentmode" => "CC",
                        "scrcategory" => $businesstype ?? ""
                    ],
                    "tycpdetails" => [
                        "telephone" => $userData['mobile'] ?? "",
                        "nationality" => "Indian",
                        "email" => $userData['email'] ?? "",
                        "surname" => "Todakar",
                        "contact1" => $userData['mobile'] ?? "",
                        "dateofbirth" => $dob ?? "",
                        "firstname" => $userData['firstName'] ?? "",
                        "middlename" => "",
                        "sex" => $userData['gender'] ?? ""
                    ],
                    "tycpaddrlist" => [
                        [
                            "state" => $userData['state'] ?? "",
                            "postcode" => $userData['pincode'] ?? "",
                            "addressline1" => $userData['house'] ?? "",
                            "addressline2" => $userData['colony'] ?? "",
                            "addressline3" => $userData['city'] ?? "",
                            "addressline4" => "",
                            "addressline5" => "INDIA"
                        ]
                    ],
                    "channeldtls" => [
                        "subimdcode" => "",
                        "imdcode" => $imdCode ?? ""
                    ],
                    "previnsdtls" => $prevdetails,
                    "hcpdtmemlist" => [
                        [
                            "membername" => $userData['firstName'] ?? "",
                            "memrelation" => $relationCode[strtoupper($userData['relationCd'])] ?? "",
                            "memdob" => $dob ?? "",
                            "memage" => $memage ?? "",
                            "memgender" => $userData['gender'] ?? "",
                            "memheightcm" => $userData['height'] ?? "",
                            "memweightkg" => $userData['weight'] ?? "",
                            "membmi" => "",
                            "memberGenetic" => "0",
                            "memoccupation" => $proposerDetails->occupation, //"104", // 
                            "memgrossmonthlyincome" => $proposerDetails->monthlyincom,//"20000",//
                            "mempreexistdisease" => isset($ped[0]['data']['mempreexistdisease'])
                                ? $ped[0]['data']['mempreexistdisease']
                                : '0',
                            "memsmkertbco" => isset($ped[0]['data']['memsmkertbco'])
                                ? $ped[0]['data']['memsmkertbco']
                                : '0',
                            "memasthma" => isset($ped[0]['data']['memasthma'])
                                ? $ped[0]['data']['memasthma']
                                : '0',
                            "memcholstrldisordr" => isset($ped[0]['data']['memcholstrldisordr'])
                                ? $ped[0]['data']['memcholstrldisordr']
                                : '0',
                            "memheartdisease" => isset($ped[0]['data']['memheartdisease'])
                                ? $ped[0]['data']['memheartdisease']
                                : '0',
                            "memhypertension" => isset($ped[0]['data']['memhypertension'])
                                ? $ped[0]['data']['memhypertension']
                                : '0',
                            "memdiabetes" => isset($ped[0]['data']['memdiabetes'])
                                ? $ped[0]['data']['memdiabetes']
                                : '0',
                            "memobesity" => "0",
                            "memaddflag" => "N",
                            "selfcoveredflag" => "Y",
                            "memnomineename" => $nominee['name'] ?? "",
                            "memnomineerelation" => $nomineerelation ?? "",
                            "memprvsi" => "",
                            "memnomineeage" => "",
                            "roomRent" => isset($aAddons['RR']) ? $aAddons['RR'] : '',
                            "pedWaitingPeriodInMonths" => isset($aAddons['ped']) ? ($aAddons['ped'] * 12) : '',
                            "postHospitalizationExpenses" => isset($aAddons['phe']) ? $aAddons['phe'] : '',
                            "preHospitalizationExpenses" => isset($aAddons['prhe']) ? $aAddons['prhe'] : '',
                            "specificDiseaseWaitingPeriod" => isset($aAddons['sdwp'])
                                ? ($aAddons['sdwp'] * 12)
                                : '36',
                            "bahcpdtmemparam91" => null,
                            "bahcpdtmemparam41" => null
                        ]
                    ],
                    "hcpdtmemcovlist" => [
                        [
                            "membername" => $userData['firstName'] ?? "",
                            "memiptreatsi" => $sI ?? "",
                            "memiptreatplan" => "Plan 9",
                            "memiptreatsubplan" => "MHCP Plan 9",
                            "memaddflag" => "N",
                            "selfcoveredflag" => "Y",
                            "memmatnormsi" => "0",
                            "memmatcesrnsi" => "0",
                            "memcoonbnftsi" => "",
                            "meminschldsi" => ""
                        ]
                    ],
                    "hcpdtpolcovobj" => [
                        "polcovzone" => $polcovzone ?? "",
                        "polcov41" => "NA",
                        "polcovvolntrycp" => $aAddons['polcovvolntrycp'] ?? '',
                        "polcovspcndt" => "NA",
                        "polcovmedcnd" => "N",
                        "polcov91" => "NA",
                        "polcovihtsi" => $sI ?? "",
                        "polcov51" => isset($aAddons['polcov51']) ? 'Y' : 'N',

                        "polcov52" => isset($aAddons['polcov51'])
                            ? ($aAddons['polcov52'] ?? 'IndOption1')
                            : '',
                        "polcovihtpln" => "",
                        "polcovmatexpnorm" => "0",
                        "polcovmatexpcsrn" => "0",
                        "polcov46" => "0",
                        "pDeductible" => isset($aAddons['pDeductable'])
                            ? (int) $aAddons['pDeductable']
                            : 0,

                        "pDoubleSumInsuredBenefit" =>
                            isset($aAddons['pDoubleSumInsuredBenefit']) ? '1' : '0',

                        "pProcedureWiseSubLimit" =>
                            isset($aAddons['pProcedureWiseSubLimit']) ? '1' : '0',
                        //"reasonForPorting" => $portReason,
                        //"otherReasonForPorting" =>""


                    ],
                    "hcpdtmemcovaddonlist" => [
                        [
                            "membername" => $userData['firstName'] ?? "",
                            "addon23" => isset($aAddons['addon23']) ? '1' : '0',
                            "addon24" => isset($aAddons['addon24']) ? '1' : '0',
                            "addon25" => isset($aAddons['addon25']) ? '1' : '0',
                            "addonairambcv" => $aAddons['addon35'] ?? "",

                            "ageShield" => isset($aAddons['ageShield']) ? '1' : '0',
                            "consumablesPlus" => isset($aAddons['consumablesPlus']) ? '1' : '0',
                            "healthLimitless" => isset($aAddons['healthLimitless']) ? '1' : '0',
                            "addonFetalFlourish" => isset($aAddons['addonFetalFlourish']) ? '1' : '0',
                            "instaShield" => isset($aAddons['instaShield']) ? '1' : '0',

                            "asthma" => isset($aAddons['asthma']) ? 'Yes' : '',
                            "diabetes" => isset($aAddons['diabetes']) ? 'Yes' : '',
                            "hypertension" => isset($aAddons['hypertension']) ? 'Yes' : '',
                            "hyperlipidaemia" => isset($aAddons['hyperlipidaemia']) ? 'Yes' : '',
                            "hypothyroidism" => isset($aAddons['hypothyroidism']) ? 'Yes' : '',
                            "obesity" => isset($aAddons['obesity']) ? 'Yes' : '',
                            "noneOfTheAbove" => isset($aAddons['noneOfTheAbove']) ? 'Yes' : '',

                            "nRInsure" => isset($aAddons['nRInsure']) ? '1' : '0',
                            "stepUpBenefit" => isset($aAddons['stepUpBenefit']) ? '1' : '0',
                            "consumables" => isset($aAddons['consumables']) ? '1' : '0',

                            "addon35" => $aAddons['addon35'] ?? '',
                            "globalCover" => $aAddons['globalCover'] ?? '',
                            "smartTenure" => $aAddons['smartTenure'] ?? '',

                            "costOfPrescribedExternalMedicalAid" =>
                                isset($aAddons['costOfPrescribedExternalMedicalAid'])
                                ? (string) ($sI * 0.10)
                                : '',
                            "memaddflag" => "N"
                        ]
                    ],
                    "hcpstagedataobj" => [
                        "busstype" => $businesstype ?? "",
                        "productcode" => $productcode ?? "",
                        "termstartdate" => $startdate ?? "",
                        "termenddate" => $enddate ?? "",
                        "selfcoveredflag" => "Y",
                        "membercombo" => "1A",
                        "totalmembernos" => $totalmembernos ?? "",
                        "partnerpincode" => $pincode ?? ""
                    ],
                    "transactionid" => $TransactionId ?? ""
                ]
            );
            //return $postData;
            //dd($postData);
            SaveFile($postData, 'bajaj_indv_policy_request.txt');
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.uat.bajajgeneral.com/bagicHws/health/healthissuepolicy',
                // CURLOPT_URL => $instance->baseurl . 'bagicHws/health/healthissuepolicy',
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

    public static function creatPolicyFloater(Request $request, $transicationid = null)
    {
        try {
            $userId = $request->userid;
            $user = User::find($userId);
            $instance = self::initlize();
            $user1 = HealthJourney::where('userid', $user)->where('vid', getconstant("HEALTH.BAJAJPLAN9.KEY"))->first();
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
            $coverage = GetCache($cachecoverage) ?? "";
            $sI = 100000 * $coverage;
            $cachetenure = 'cache_tenure_' . $userId;
            $tenure = GetCache($cachetenure) ?? '1';
            $policyperiod = $tenure * 1200;
            $data = Insure::where('proposalid', $userId)->get()->toArray();
            $totalmembernos = count($data) ?? 1;
            $memrelation = $data[0]['name'] ?? "Self";
            $memage = $data[0]['age'] ?? "28";
            $memgender = "F";
            $cachecovertype = 'cache_covertype_' . $userId;
            $producttype = GetCache($cachecovertype) ?? '1';
            $cachehealthplan = 'cache_healthplan_' . $userId;
            $plantype = GetCache($cachehealthplan) ?? '1';
            $businesstype = "";
            if ($plantype == "1") {
                $businesstype = "NB";
            } else {
                $businesstype = "PORT";
            }
            $productcode = "8457";
            $today = now()->format('Y-m-d');
            $startdate = $startdate = now()->format('d-M-Y');
            $enddate = now()->addYear()->subDay()->format('d-M-Y');
            $dob = date('d-M-Y', strtotime("-{$data[0]['age']} years", strtotime($today)));
            $user1 = HealthJourney::where('userid', $userId)->where('vid', getconstant("HEALTH.BAJAJPLAN9.KEY"))->first();
            $cacheAddon = 'cache_addons_' . $userId;

            $addons = GetCache($cacheAddon);

            if (is_string($addons)) {
                $addons = json_decode($addons, true);
            }

            $aAddons = [];
            $RR = "";

            if ($coverage >= 3 && $coverage <= 10) {
                $RR = "SPA";
            } else if ($coverage >= 10) {
                $RR = "Actual";
            } else {
                $RR = "SPA";
            }
            if (!empty($addons) && is_array($addons)) {
                foreach ($addons as $addon) {
                    if (!is_string($addon))
                        continue;
                    if (strpos($addon, 'RR') === 0) {
                        $aAddons['RR'] = substr($addon, 2);
                        continue;
                    }
                    if (strpos($addon, 'addon35') === 0) {
                        $aAddons['addon35'] = substr($addon, 7);
                        continue;
                    }
                    if (strpos($addon, 'pDeductable') === 0) {
                        $aAddons['pDeductable'] = str_replace('pDeductable', '', $addon);
                        continue;
                    }
                    if (strpos($addon, 'globalCover') === 0) {
                        $aAddons['globalCover'] = str_replace(
                            '%',
                            '',
                            str_replace('globalCover', '', $addon)
                        );
                        continue;
                    }
                    if (strpos($addon, 'polcovvolntrycp') === 0) {
                        $aAddons['polcovvolntrycp'] = str_replace(
                            '%',
                            '',
                            str_replace('polcovvolntrycp', '', $addon)
                        );
                        continue;
                    }
                    if (strpos($addon, 'pSuperCumulativeBonusOption') === 0 || strpos($addon, 'superCumulativeBonusOption') === 0) {
                        // Agar frontend se direct "100-200" jaisi value aa rahi hai bina prefix ke
                        $val = str_replace(['pSuperCumulativeBonusOption', 'superCumulativeBonusOption'], '', $addon);
                        $aAddons['pSuperCumulativeBonusOption'] = trim($val);
                        continue;
                    }
                    if (strpos($addon, 'pCumulativeBonusOptions') === 0 || strpos($addon, 'cumulativeBonusOptions') === 0) {
                        $val = str_replace(['pCumulativeBonusOptions', 'cumulativeBonusOptions'], '', $addon);
                        $aAddons['pCumulativeBonusOptions'] = trim($val);
                        continue;
                    }
                    if (strpos($addon, 'polcov51') === 0) {

                        $aAddons['polcov51'] = 'Y';

                        $val = trim(str_replace('polcov51', '', $addon));

                        if (!empty($val)) {
                            $aAddons['polcov52'] = $val;
                        }

                        continue;
                    }


                    if (strpos($addon, 'polcov52') === 0) {

                        $aAddons['polcov52'] =
                            trim(str_replace(
                                'polcov52',
                                '',
                                $addon
                            ));

                        continue;
                    }
                    preg_match('/^([a-zA-Z]+)(\d*)$/', $addon, $matches);
                    $char = $matches[1] ?? '';
                    $num = $matches[2] ?? '';

                    if (
                        in_array($char, [
                            'ped',
                            'phe',
                            'prhe',
                            'sdwp',
                            'addon',
                            'ageShield',
                            'consumables',
                            'consumablesPlus',
                            'pDeductable',
                            'costOfPrescribedExternalMedicalAid',
                            'globalCover',
                            'polcovvolntrycp',
                            'healthLimitless',
                            'addonFetalFlourish',
                            'instaShield',
                            'nRInsure',
                            'smartTenure',
                            'stepUpBenefit',
                            'pSuperCumulativeBonusOption',
                            'pCumulativeBonusOptions',
                            'polcov51',
                            'polcov52',
                            'doubleSumInsuredBenefit',
                            'pProcedureWiseSubLimit',
                            'asthma',
                            'diabetes',
                            'hypertension',
                            'hyperlipidaemia',
                            'hypothyroidism',
                            'obesity',
                            'noneOfTheAbove',
                            'airAmbulance',
                        ])
                    ) {
                        if ($char === 'addon') {
                            $aAddons[$char . $num] = $num;
                        } else {
                            $aAddons[$char] = $num;
                        }
                    }
                }
                // dd($aAddons);
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
                'dob' => $dob ?? "",
                'gender' => (strtoupper($user1->gender) == 'MS') ? 'F' : 'M',
                'height' => $proposerDetails->height ?? "",
                'weight' => $proposerDetails->weight ?? "",
                'house' => strtoupper($permanentAddress->address1) ?? "",
                'colony' => strtoupper($permanentAddress->address2) ?? "",
                'city' => $permanentAddress->city ?? "",
                'state' => $permanentAddress->state ?? "",
                'pincode' => $user->pincode ?? "",
                'mobile' => $contactDetails->contactmobile ?? "",
                'email' => $contactDetails->contactemail ?? $user->email,
                'panId' => strtoupper($user1->pan),
                'title' => strtoupper($user1->gender),
                'firstName' => $user1->name ?? "",
                'lastName' => count($namePart) > 1 ? end($namePart) : 'Unknown',
                'bankIFSC' => $bankIFSC ?? "",
                'relationCd' => $relationCode[strtoupper($user1->proposer)]
            ];
            $data = JourneyUsers::where('proposalid', $user->id)->where('insureid', '<>', '0')->get();
            foreach ($data as $member) {
                $hasDisease =
                    isset($aAddons['asthma']) ||
                    isset($aAddons['diabetes']) ||
                    isset($aAddons['hypertension']) ||
                    isset($aAddons['hyperlipidaemia']) ||
                    isset($aAddons['hypothyroidism']) ||
                    isset($aAddons['obesity']);
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

            $ped = json_decode($memberdata[0]['ped'], true); // decode the string
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
            $relation = "";
            $Memgender = "";
            foreach ($data as $member) {
                $key = strtoupper(str_replace([' ', '-'], '', $member['relation']));

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

                $relation = $relationCode[$key] ?? ucfirst($member['relation']);

                $Memgender = match (strtolower($member['relation'])) {
                    'wife', 'daughter', 'mother', 'grandmother', 'mother-in-law' => 'F',
                    default => 'M',
                };

                $hcpdtmemlist[] = [
                    "membername" => $member['name'] ?? "",
                    "memrelation" => $relation ?? "",
                    "memdob" => $member['dob'] ? date('d-M-Y', strtotime($member['dob'])) : "",
                    "memage" => $member['age'],
                    "memgender" => $Memgender,
                    "memheightcm" => $member['height'] ?? "",
                    "memweightkg" => $member['weight'] ?? "",
                    "membmi" => "",
                    "memoccupation" => $proposerDetails->occupation,
                    "memgrossmonthlyincome" => $proposerDetails->monthlyincom,
                    "memberGenetic" => "0",
                    "mempreexistdisease" => "0",
                    "memsmkertbco" => "0",
                    "memasthma" => "0",
                    "memcholstrldisordr" => "0",
                    "memheartdisease" => "0",
                    "memhypertension" => "0",
                    "memdiabetes" => "0",
                    "memobesity" => "0",
                    "memaddflag" => "Y",
                    "memnomineename" => "Asd Asd",
                    "memnomineerelation" => $nomineerelation ?? "",
                    "memprvsi" => "",
                    "selfcoveredflag" => strtolower($member->relation) == "self" ? "Y" : "N",
                    "roomRent" => isset($aAddons['RR']) ? $aAddons['RR'] : $RR,
                    "pedWaitingPeriodInMonths" => isset($aAddons['ped']) ? ($aAddons['ped'] * 12) : '36',
                    "postHospitalizationExpenses" => isset($aAddons['phe']) ? $aAddons['phe'] : '90',
                    "preHospitalizationExpenses" => isset($aAddons['prhe']) ? $aAddons['prhe'] : '60',
                    "specificDiseaseWaitingPeriod" => isset($aAddons['sdwp'])
                        ? ($aAddons['sdwp'] * 12)
                        : '36',
                ];
                $hcpdtmemcovlist[] = [
                    "membername" => $member['name'] ?? "",
                    "memiptreatsi" => $sI ?? "",
                    "memiptreatplan" => "Plan 9",
                    "memiptreatsubplan" => "MHCP Plan 9",
                    "memaddflag" => "Y",
                    "selfcoveredflag" => strtolower($member->relation) == "self" ? "Y" : "N",
                    "memmatnormsi" => "0",
                    "memmatcesrnsi" => "0",
                    "memcoonbnftsi" => "",
                    "meminschldsi" => ""
                ];

                $hcpdtmemcovaddonlist[] = [
                    "membername" => $member['name'] ?? "",
                    "addon23" => isset($aAddons['addon23']) ? '1' : '0',
                    "addon24" => isset($aAddons['addon24']) ? '1' : '0',
                    "addon25" => isset($aAddons['addon25']) ? '1' : '0',
                    "addonairambcv" => $aAddons['addon35'] ?? "",

                    "ageShield" => isset($aAddons['ageShield']) ? '1' : '0',
                    "consumablesPlus" => isset($aAddons['consumablesPlus']) ? '1' : '0',
                    "healthLimitless" => isset($aAddons['healthLimitless']) ? '1' : '0',
                    "addonFetalFlourish" => isset($aAddons['addonFetalFlourish']) ? '1' : '0',
                    "instaShield" => isset($aAddons['instaShield']) ? '1' : '0',

                    "asthma" => isset($aAddons['asthma']) ? 'Yes' : '',
                    "diabetes" => isset($aAddons['diabetes']) ? 'Yes' : '',
                    "hypertension" => isset($aAddons['hypertension']) ? 'Yes' : '',
                    "hyperlipidaemia" => isset($aAddons['hyperlipidaemia']) ? 'Yes' : '',
                    "hypothyroidism" => isset($aAddons['hypothyroidism']) ? 'Yes' : '',
                    "obesity" => isset($aAddons['obesity']) ? 'Yes' : '',
                    "noneOfTheAbove" => !$hasDisease ? 'Yes' : '',

                    "nRInsure" => isset($aAddons['nRInsure']) ? '1' : '0',
                    "stepUpBenefit" => isset($aAddons['stepUpBenefit']) ? '1' : '0',
                    "consumables" => isset($aAddons['consumables']) ? '1' : '0',

                    "addon35" => $aAddons['addon35'] ?? '',
                    "globalCover" => $aAddons['globalCover'] ?? '',
                    "smartTenure" => $aAddons['smartTenure'] ?? '',

                    "costOfPrescribedExternalMedicalAid" =>
                        isset($aAddons['costOfPrescribedExternalMedicalAid'])
                        ? (string) ($sI * 0.10)
                        : '',
                    "memaddflag" => "N"
                ];
            }

            $password = $instance->password;
            $Id = $instance->Id;
            $imdCode = $instance->imdCode;
            $deptcode = $instance->deptcode;

            $postData = json_encode(
                [
                    "password" => $password ?? "Newpas12",
                    "userid" => $Id ?? "webservice@digibima.com",
                    "sourcedtls" => [
                        "username" => $userData['firstName'] ?? "",
                        "departmentcode" => "9906",
                        "productcode" => $productcode ?? "8457",
                        "businesstype" => $businesstype ?? "",
                        "imdcode" => $imdCode,
                        "subimdcode" => "",
                        "modulename" => "WEBSERVICE"
                    ],
                    "policydtls" => [
                        "remarks" => "http://uat.digibima.com/health/vendors/bajaj/payment/thankyou",
                        "termstartdate" => $startdate ?? "",
                        "termenddate" => $enddate ?? "",
                        "partnertype" => "P",
                        "businesstype" => $businesstype ?? "",
                        "policyperiod" => $policyperiod ?? "",
                        "productcode" => $productcode ?? "8457",
                        "scrlocationcode" => "9906",
                        "paymentmode" => "CC",
                        "scrcategory" => "NB",
                        "deptcode" => $deptcode ?? "84"
                    ],
                    "tycpdetails" => [
                        "telephone" => $userData['mobile'] ?? "",
                        "nationality" => "Indian",
                        "email" => $userData['email'] ?? "",
                        "surname" => "Todkar",
                        "contact1" => $userData['mobile'] ?? "",
                        "dateofbirth" => $dob ?? "",
                        "firstname" => $userData['firstName'] ?? "",
                        "middlename" => "",
                        "sex" => $userData['gender'] ?? ""
                    ],
                    "tycpaddrlist" => [
                        [
                            "state" => $userData['state'] ?? "",
                            "postcode" => $userData['pincode'] ?? "",
                            "addressline1" => $userData['city'] ?? "",
                            "addressline2" => $userData['colony'] ?? "",
                            "addressline3" => $userData['house'] ?? "",
                            "addressline4" => "",
                            "addressline5" => "INDIA"
                        ]
                    ],
                    "channeldtls" => [
                        "subimdcode" => "",
                        "imdcode" => $imdCode ?? "10105697"
                    ],
                    "previnsdtls" => [
                        "noofclaims" => "0",
                        "previnsname" => "",
                        "previnsaddress" => "",
                        "previnspolicyno" => "",
                        "prevpolicyexpirydate" => ""
                    ],
                    "hcpdtmemlist" => $hcpdtmemlist,
                    "hcpdtmemcovlist" => $hcpdtmemcovlist,
                    "hcpdtpolcovobj" => [
                        "polcovzone" => $polcovzone ?? "",
                        "polcov41" => "NA",
                        "polcovvolntrycp" => "",
                        "pDeductable" => $aAddons['pDeductable'] ?? "",
                        "consumables" => isset($aAddons['consumables']) ? 'Y' : 'N',

                        "polcovspcndt" => "NA",
                        "polcovmedcnd" => "N",
                        "polcov91" => "NA",
                        "polcovihtsi" => $sI ?? "",

                        "polcov51" => isset($aAddons['polcov51']) ? 'Y' : 'N',

                        "polcov52" => isset($aAddons['polcov51'])
                            ? ($aAddons['polcov52'] ?? ($totalmembernos == 1 ? 'IndOption1' : 'FltOption1'))
                            : '',

                        "doubleSumInsuredBenefit" => isset($aAddons['doubleSumInsuredBenefit']) ? 'Y' : '',
                        "pProcedureWiseSubLimit" => isset($aAddons['pProcedureWiseSubLimit']) ? '1' : '0',

                        "polcovihtpln" => "",
                        "polcovmatexpnorm" => "0",
                        "polcovmatexpcsrn" => "0",
                        "polcov46" => "0",

                        "pSuperCumulativeBonusOption" => isset($aAddons['pSuperCumulativeBonusOption'])
                            ? str_replace(' upto ', '-', $aAddons['pSuperCumulativeBonusOption'])
                            : "NA",

                        "pCumulativeBonusOptions" => isset($aAddons['pCumulativeBonusOptions'])
                            ? str_replace(['% upto ', ' upto '], '-', $aAddons['pCumulativeBonusOptions'])
                            : "NA",
                    ],
                    "hcpdtmemcovaddonlist" => $hcpdtmemcovaddonlist,
                    "hcpstagedataobj" => [
                        "busstype" => $businesstype ?? "NB",
                        "productcode" => $productcode ?? "8457",
                        "termstartdate" => $startdate ?? "",
                        "termenddate" => $enddate ?? "",
                        "selfcoveredflag" => "Y",
                        "membercombo" => $membercount ?? "2A",
                        "totalmembernos" => $totalmembernos ?? "2",
                        "partnerpincode" => $pincode ?? ""
                    ],
                    "transactionid" => $TransactionId ?? ""
                ]
            );
            //return $postData;
            //dd($postData);
            SaveFile($postData, 'bajaj_floater_policy_request_fullquote9.txt');
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.uat.bajajgeneral.com/bagicHws/health/healthissuepolicy',
                // CURLOPT_URL => $instance->baseurl . 'bagicHws/health/healthissuepolicy',
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
                "intGetPolicyStatusIO" => [
                    "userid" => $id,
                    "password" => $password,
                    "transaction_id" => $transactionId,
                    "payment_transobj" => [
                        "stringval1" => "Y",
                        "stringval2" => "6188359",
                        "stringval3" => "HEALTH_WS",
                        "stringval6" => "HDFC",
                        "stringval20" => "1000.0",
                    ]
                ]
            ]);
            //return $payload;
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
                    "userid" => $instance->Id,
                    "password" => $instance->password,
                    "pdfmode" => "health",
                    "policynum" => $policynum
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
