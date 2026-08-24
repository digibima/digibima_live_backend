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

class BajajHealthGuardService
{

    private static $Id = "webservice@digibima.com";
    private static $imdCode = "88507001";
    private static $password = "Newpas12";
    private static $deptcode = "84";

    private static $securityKey = "kycwsbrkmotr2023";

    private static $iv = "kycwsbrkmotr2023";
    //   private static $applicationCD = "PARTNERAPP";
//   private static $signature = "R+GdzmW7vnvSX5MekKa"; //"R+GdzmW7vnvSX5MekKa";



    public static function getBasicPlan($params)
    {
        try {
            $userId = $params['userId'];
            //return $params;
            $pincode = $params['pincode'];
            $zonedata = BajajPincode::where('Pincode', $pincode)->first();
            $updated_zone = strtolower(trim($zonedata->ZoneCorrected));
            $zone_map = [
                'zone a' => 1,
                'zone b' => 2,
                'zone c' => 3,
            ];
            $polcovzone = $zone_map[$updated_zone] ?? '';

            $coverage = $params['coverage'];
            $sI = 100000 * $coverage;
            $time = $params['tenure'] ?? '1';
            $policyperiod = $time * 1200;
            $data = Insure::where('proposalid', $userId)->get()->toArray();
            //$polcovzone = 1;
            $totalmembernos = count($data) ?? 1;
            $memrelation = $data[0]['name'] ?? "";
            $memage = $data[0]['age'] ?? "";
            $memgender = "F";
            $cachecovertype = 'cache_covertype_' . $userId;
            $producttype = GetCache($cachecovertype) ?? '1';
            $cachehealthplan = 'cache_healthplan_' . $userId;
            $plantype = GetCache($cachehealthplan) ?? '1';
            $member = RedisGet('Member:' . $userId);
            $businesstype = "";
            if ($plantype = "1") {
                $businesstype = "NB";
            } else {
                $businesstype = "PORT";
            }
            $productcode = "8450";
            $today = now()->format('Y-m-d');
            $startdate = now()->format('d-M-Y');
            $enddate = now()->addYear()->subDay()->format('d-M-Y');
            $dob = date('d-M-Y', strtotime("-{$data[0]['age']} years", strtotime($today)));
            $journeydata = HealthJourney::where('userid', $userId)
                ->where('vid', getconstant("HEALTH.BAJAJ.KEY"))
                ->first() ?? [];
            //return $journeydata;
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
            $RR = "";
            if ($coverage >= 3 && $coverage <= 10) {
                $RR = "SPA";
            } else if ($coverage >= 10) {
                $RR = "Actual";
            } else {
                $RR = "SPA";
            }

            $password = self::$password;
            $Id = self::$Id;
            $imdCode = self::$imdCode;
            $deptcode = self::$deptcode;

            $postfields =
                [
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
                        "termstartdate" => $startdate ?? "",
                        "termenddate" => $enddate ?? "",
                        "partnertype" => "P",
                        "businesstype" => $businesstype ?? "",
                        "deptcode" => $deptcode,
                        "policyperiod" => $policyperiod ?? "1200",
                        "productcode" => $productcode,
                        "scrlocationcode" => "1101",
                        "remarks" => "http://uat.digibima.com/health/vendors/bajaj/payment/thankyou",
                        "paymentmode" => "",
                        "scrcategory" => $businesstype ?? ""

                    ],
                    "previnsdtls" => [
                        "previnsname" => "",
                        "previnsaddress" => "",
                        "previnspolicyno" => "",
                        "prevpolicyexpirydate" => "",
                        "noofclaims" => "0"
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
                            "memoccupation" => "",
                            "memgrossmonthlyincome" => "",
                            "memnomineename" => "",
                            "memnomineerelation" => "",
                            "mempreexistdisease" => "0",
                            "memspecialcondition" => "NA",
                            "memsmkertbco" => "0",
                            "memasthma" => "0",
                            "memcholstrldisordr" => "0",
                            "memheartdisease" => "0",
                            "memhypertension" => "0",
                            "memdiabetes" => "0",
                            "memobesity" => "0",
                            "memaddflag" => "N"
                        ]
                    ],

                    "hcpdtmemcovlist" => [
                        [
                            "membername" => "",
                            "memcbper" => "0",
                            "memcbamnt" => "0",
                            "memiptreatsi" => $sI ?? "1000000",
                            "memiptreatplan" => "Plan 1",
                            "memcoonbnftsi" => "0",
                            "meminschldsi" => "0",
                            "memaddflag" => "N"
                        ]
                    ],

                    "hcpdtpolcovobj" => [
                        "polcovzone" => $polcovzone,
                        "polcovvolntrycp" => "",
                        "polcovspcndt" => "",
                        "polcovmedcnd" => "",
                        "polcovihtsi" => $sI ?? "1000000",
                        "polcovihtpln" => null,
                        "polcovmatexpnorm" => "",
                        "polcovmatexpcsrn" => "",
                        "polcov46" => ""
                    ],
                    "hcpdtmemcovaddonlist" => [
                        [
                            "membername" => "SARATH MELEMANAKKATT",
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


            $requestdata = json_encode($postfields);
            //return $requestdata;
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.bagicuat.bajajallianz.com/bagicHws/health/healthpremiumcal',
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
            Log::info(['BajajHealthGuard_Response' => $response]);
            curl_close($curl);
            return $response;

        } catch (\Exception $e) {
            return $e->getMessage();
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
            $polcovzone = $zone_map[$updated_zone] ?? '';
            $coverage = $params['coverage'];
            $data = Insure::where('proposalid', $userId)->get()->toArray();
            $sI = 100000 * $coverage;
            $time = $params['tenure'] ?? '1';
            $policyperiod = $time * 1200;
            $productcode = "8451";
            $today = now()->format('Y-m-d');
            $startdate = now()->format('d-M-Y');
            $enddate = now()->addYear()->subDay()->format('d-M-Y');
            $dob = date('d-M-Y', strtotime("-{$data[0]['age']} years", strtotime($today)));
            $totalmembernos = count($data) ?? 1;
            $memrelation = $data[0]['name'] ?? "Self";
            $membercombo = RedisGet('Member:' . $userId);
            //return $membercombo;
            $memage = $data[0]['age'] ?? "28";
            $memgender = "F";
            $cachecovertype = 'cache_covertype_' . $userId;
            $producttype = GetCache($cachecovertype) ?? '1';
            $cachehealthplan = 'cache_healthplan_' . $userId;
            $plantype = GetCache($cachehealthplan) ?? '1';
            $businesstype = "";
            if ($plantype = "1") {
                $businesstype = "NB";
            } else {
                $businesstype = "PORT";
            }

            $journeydata = HealthJourney::where('userid', $userId)
                ->where('vid', getconstant("HEALTH.BAJAJ.KEY"))
                ->first();
            $addons = json_decode($journeydata['addon'], true) ?? [];
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
            $RR = "";
            if ($coverage >= 3 && $coverage <= 10) {
                $RR = "SPA";
            } else if ($coverage >= 10) {
                $RR = "Actual";
            } else {
                $RR = "SPA";
            }
            //return $aAddons;
            $hcpdtmemlist = [];
            $hcpdtmemcovlist = [];
            $hcpdtmemcovaddonlist = [];
            $relation = "";
            foreach ($data as $member) {

                if (in_array(strtolower($member['name']), ['husband', 'wife'])) {
                    $relation = 'Spouse';
                } else {
                    $relation = ucfirst($member['name']);
                }
                $gender = match (strtolower($member['name'])) {
                    'wife', 'daughter', 'mother', 'grandmother', 'mother-in-law' => 'F',
                    default => 'M',
                };
                $hcpdtmemlist[] = [
                    "membername" => "",
                    "memrelation" => $relation ?? "",
                    "memdob" => $dob,
                    "memage" => $member['age'],
                    "memgender" => $gender,
                    "memheightcm" => "",
                    "memweightkg" => "",
                    "membmi" => "",
                    "memoccupation" => "",
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

                // Coverage list
                $hcpdtmemcovlist[] = [
                    "membername" => "",
                    "memiptreatsi" => $sI ?? "",
                    "memiptreatplan" => "Plan 1",
                    "memiptreatsubplan" => "Plan 1",
                    "memaddflag" => "Y",
                    "selfcoveredflag" => strtolower($member['name']) == "self" ? "Y" : "N",
                    "memmatnormsi" => "0",
                    "memmatcesrnsi" => "0",
                    "memcoonbnftsi" => "",
                    "meminschldsi" => ""
                ];

                $hcpdtmemcovaddonlist[] = [
                    "membername" => "",
                    "addonairambcv" => "",
                    "addon23" => isset($aAddons['addon23']) ? $aAddons['addon23'] : '0',
                    "addon24" => isset($aAddons['addon24']) ? $aAddons['addon24'] : '0',
                    "addon25" => isset($aAddons['addon25']) ? $aAddons['addon25'] : '0',
                    "memaddflag" => "Y"
                ];
            }
            $hcpdtpolcovobj = [
                "polcovzone" => "1",
                "polcov41" => "NA",
                "polcovvolntrycp" => "",
                "polcovspcndt" => "NA",
                "polcovmedcnd" => "N",
                "polcov91" => "NA",
                "polcovihtsi" => $sI ?? "",
                "polcov51" => "",
                "polcovihtpln" => "",
                "polcovmatexpnorm" => "0",
                "polcovmatexpcsrn" => "0",
                "polcov46" => "0"
            ];

            $result = [
                "hcpdtmemlist" => $hcpdtmemlist,
                "hcpdtmemcovlist" => $hcpdtmemcovlist,
                "hcpdtpolcovobj" => $hcpdtpolcovobj,
                "hcpdtmemcovaddonlist" => $hcpdtmemcovaddonlist,
            ];
            //return $result;
            $password = self::$password;
            $Id = self::$Id;
            $imdCode = self::$imdCode;
            $deptcode = self::$deptcode;

            // $postfeilds = [
            //     "password" => $password,
            //     "userid" => $Id,
            //     "sourcedtls" => [
            //         "username" => "",
            //         "departmentcode" => "1101",
            //         "productcode" => $productcode ?? "8450",
            //         "businesstype" => $businesstype ?? "",
            //         "imdcode" => $imdCode,
            //         "subimdcode" => "",
            //         "modulename" => "WEBSERVICE"
            //     ],
            //     "policydtls" => [
            //         "remarks" => "https://kyc.uat.coverfox.com/covid-plan/order/ee13e40b249c4205a82acd1e2ac85082/response/",
            //         "termstartdate" => $startdate ?? "",
            //         "termenddate" => $enddate ?? "",
            //         "partnertype" => "P",
            //         "businesstype" => $businesstype ?? "",
            //         "policyperiod" => $policyperiod ?? "",
            //         "productcode" => $productcode ?? "8457",
            //         "scrlocationcode" => "9906",
            //         "paymentmode" => "CC",
            //         "scrcategory" => "NB",
            //         "deptcode" => $deptcode
            //     ],
            //     "tycpdetails" => [
            //         "telephone" => "",
            //         "nationality" => "",
            //         "email" => "",
            //         "surname" => "",
            //         "contact1" => "",
            //         "dateofbirth" => "",
            //         "firstname" => "",
            //         "middlename" => "",
            //         "sex" => $memgender ?? ""
            //     ],
            //     "tycpaddrlist" => [
            //         [
            //             "state" => "",
            //             "postcode" => $pincode ?? "",
            //             "addressline1" => "",
            //             "addressline2" => "",
            //             "addressline3" => "",
            //             "addressline4" => "",
            //             "addressline5" => ""
            //         ]
            //     ],
            //     "channeldtls" => [
            //         "subimdcode" => "",
            //         "imdcode" => $imdCode
            //     ],
            //     "previnsdtls" => [
            //         "noofclaims" => "0",
            //         "previnsname" => "",
            //         "previnsaddress" => "",
            //         "previnspolicyno" => "",
            //         "prevpolicyexpirydate" => ""
            //     ],

            //     "hcpdtmemlist" => $hcpdtmemlist,
            //     "hcpdtmemcovlist" => $hcpdtmemcovlist,

            //     "hcpdtpolcovobj" => [
            //         "polcovzone" => $polcovzone ?? "",
            //         "polcov41" => "NA",
            //         "polcovvolntrycp" => "",
            //         "polcovspcndt" => "NA",
            //         "polcovmedcnd" => "N",
            //         "polcov91" => "NA",
            //         "polcovihtsi" => $sI ?? "",
            //         "polcov51" => "",
            //         "polcovihtpln" => "",
            //         "polcovmatexpnorm" => "0",
            //         "polcovmatexpcsrn" => "0",
            //         "polcov46" => "0"
            //     ],
            //     "hcpdtmemcovaddonlist" => $hcpdtmemcovaddonlist,

            //     "hcpstagedataobj" => [
            //         "busstype" => $businesstype ?? "",
            //         "productcode" => $productcode ?? "8457",
            //         "termstartdate" => $startdate ?? "",
            //         "termenddate" => $enddate ?? "",
            //         "selfcoveredflag" => "Y",
            //         "membercombo" => $membercombo ?? "2A",
            //         "totalmembernos" => $totalmembernos ?? "2",
            //         "partnerpincode" => $pincode ?? ""
            //     ]
            // ];



            $postfeilds = [
                "password" => $password,
                "userid" => $Id,
                "sourcedtls" => [
                    "username" => "",
                    "departmentcode" => "1101",
                    "productcode" => $productcode ?? "8450",
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
                    "policyperiod" => $policyperiod ?? "1200",
                    "productcode" => $productcode,
                    "scrlocationcode" => "1101",
                    "paymentmode" => "",
                    "scrcategory" => $businesstype ?? ""
                ],

                "tycpdetails" => [
                    "telephone" => "",
                    "email" => "",
                    "surname" => "",
                    "contact1" => "",
                    "dateofbirth" => "",
                    "firstname" => "",
                    "middlename" => "",
                    "sex" => ""
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
                        "memoccupation" => "",
                        "memgrossmonthlyincome" => "",
                        "memnomineename" => "",
                        "memnomineerelation" => "",
                        "mempreexistdisease" => "0",
                        "memspecialcondition" => "NA",
                        "memsmkertbco" => "0",
                        "memasthma" => "0",
                        "memcholstrldisordr" => "0",
                        "memheartdisease" => "0",
                        "memhypertension" => "0",
                        "memdiabetes" => "0",
                        "memobesity" => "0",
                        "memaddflag" => "N",
                        "memprvsi" => ""
                    ],
                    [
                        "membername" => "",
                        "memrelation" => "",
                        "memdob" => "",
                        "memage" => "",
                        "memgender" => "",
                        "memheightcm" => "",
                        "memweightkg" => "",
                        "membmi" => "",
                        "memoccupation" => "",
                        "memgrossmonthlyincome" => "0",
                        "mempreexistdisease" => "0",
                        "memspecialcondition" => "NA",
                        "memsmkertbco" => "0",
                        "memasthma" => "0",
                        "memcholstrldisordr" => "0",
                        "memheartdisease" => "0",
                        "memhypertension" => "0",
                        "memdiabetes" => "0",
                        "memobesity" => "0",
                        "memaddflag" => "Y",
                        "memnomineename" => "",
                        "memnomineerelation" => "",
                        "memprvsi" => ""
                    ]
                ],
                "hcpdtmemcovlist" => [
                    [
                        "membername" => "",
                        "memcbper" => "0",
                        "memcbamnt" => "0",
                        "memiptreatsi" => $sI ?? "1000000",
                        "memiptreatplan" => "Plan 1",
                        "memcoonbnftsi" => "0",
                        "meminschldsi" => "0",
                        "memaddflag" => "Y",
                        "prembasecover" => ""
                    ],

                    [
                        "membername" => "",
                        "memcbper" => "0",
                        "memcbamnt" => "0",
                        "memiptreatsi" => $sI ?? "1000000",
                        "memiptreatplan" => "Plan 1",
                        "memcoonbnftsi" => "0",
                        "meminschldsi" => "0",
                        "memaddflag" => "Y",
                        "prembasecover" => ""
                    ]
                ],
                "hcpdtpolcovobj" => [
                    "polcovzone" => "1",
                    "polcov46" => "0",
                    "polcov51" => "",
                    "polcov52" => "",
                    "polcovvolntrycp" => "",
                    "polcovspcndt" => "NA",
                    "polcovmedcnd" => "N",
                    "polcovihtsi" => $sI ?? "1000000"
                ],
                "hcpdtmemcovaddonlist" => [
                    [
                        "addonnme" => "0",
                        "addonroomwaiver" => "0",
                        "membername" => "",
                        "memaddflag" => "Y"
                    ],
                    [
                        "addonnme" => "0",
                        "addonroomwaiver" => "0",
                        "membername" => "",
                        "memaddflag" => "Y"
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

            //return $postfeilds;
            $requestdata = json_encode($postfeilds);


            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.bagicuat.bajajallianz.com/bagicHws/health/healthpremiumcal',
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
            Log::info(['BajajHealthGuard_Response' => $response]);
            curl_close($curl);
            return $response;

        } catch (\Exception $e) {
            return $e->getMessage();
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
            $user1 = HealthJourney::where('userid', $user)->where('vid', getconstant("HEALTH.BAJAJ.KEY"))->first();
            //$cacheTransicationId = 'bajajcache_' . getconstant("HEALTH.BAJAJ.KEY") . '_proposalnum_' . $userId;
            //$TransactionId = GetCache($cacheTransicationId) ?? "";
            $TransactionId = RedisGet('transactionid:' . $userId) ?? "";
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
            ;
            $tenure = GetCache($cachetenure) ?? '1';
            $policyperiod = $tenure * 1200;
            $data = Insure::where('proposalid', $userId)->get()->toArray();
            //$polcovzone = 1;
            $totalmembernos = count($data) ?? 1;
            $memrelation = $data[0]['name'] ?? "Self";
            $memage = $data[0]['age'] ?? "28";
            $memgender = "F";
            $cachecovertype = 'cache_covertype_' . $userId;
            $producttype = GetCache($cachecovertype) ?? '1';
            $cachehealthplan = 'cache_healthplan_' . $userId;
            $plantype = GetCache($cachehealthplan) ?? '1';
            $businesstype = "";
            if ($plantype = "1") {
                $businesstype = "NB";
            } else {
                $businesstype = "PORT";
            }
            $productcode = "8456";
            $today = now()->format('Y-m-d');
            $startdate = $startdate = now()->format('d-M-Y');
            $enddate = now()->addYear()->subDay()->format('d-M-Y');
            $dob = date('d-M-Y', strtotime("-{$data[0]['age']} years", strtotime($today)));
            $user1 = HealthJourney::where('userid', $userId)->where('vid', getconstant("HEALTH.BAJAJ.KEY"))->first();
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
            // $insures = [];
            // $covertype = '';
            $relationCode = [
                'SELF' => 'Self',
                'HUSBAND' => 'Spouse',
                'WIFE' => 'Spouse',
                'SON' => 'Son',
                'DAUGHTER' => 'Daughter',
                'FATHER' => 'Father',
                'MOTHER' => 'Mother',
                'FATHERINLAW' => 'Father In Law ',
                'MOTHERINLAW' => 'Mother In Law '
            ];

            $member = RedisGet('Member:' . $userId);
            $contactDetails = json_decode($user1->contact_details);
            $permanentAddress = json_decode($user1->permanent_address);
            // $commaddress = json_decode($user1->comunication_address);
            $proposerDetails = json_decode($user1->proposer_details);
            // //dd($csaddonCode);
            $userData = [
                'dob' => $dob ?? "",//str_replace('-', '/', $user1->dob),
                'gender' => (strtoupper($user1->gender) == 'MS') ? 'F' : 'M',
                'height' => $proposerDetails->height ?? "",
                'weight' => $proposerDetails->weight ?? "",
                'house' => strtoupper($permanentAddress->address1) ?? "",//k
                'colony' => strtoupper($permanentAddress->address2) ?? "",//k
                'city' => $permanentAddress->city ?? "",//k
                'state' => $permanentAddress->state ?? "",//k
                'pincode' => $user->pincode ?? "",
                'mobile' => $contactDetails->contactmobile ?? "",
                'email' => $contactDetails->contactemail ?? $user->email,
                'panId' => strtoupper($user1->pan),
                'title' => strtoupper($user1->gender),
                'firstName' => $user1->name ?? "", //isset($namePart[0]) ? $namePart[0] : 'Unknown',
                'lastName' => count($namePart) > 1 ? end($namePart) : 'Unknown',
                //'bankAccount' => $bankAccount ?? "",
                'bankIFSC' => $bankIFSC ?? "",
                'relationCd' => $relationCode[strtoupper($user1->proposer)] //strtoupper($user->proposar)
            ];
            //return $userData;



            $members = JourneyUsers::where('proposalid', $user->id)->where('insureid', '<>', '0')->get();
            //return $members->toArray();
            foreach ($members as $member) {
                $memberdata[] = [
                    'name' => $member->name,
                    'relation' => $member->relation,
                    'dob' => $member->dob,
                    'age' => $member->age,
                    'ped' => $member->ped,
                ];
            }

            $ped = json_decode($memberdata[0]['ped'], true); // decode the string
            // $mempreexistdisease = isset($ped[0]['data']['mempreexistdisease'])
            //     ? $ped[0]['data']['mempreexistdisease']
            //     : '';
            // return $mempreexistdisease;

            $nominee = JourneyUsers::where('proposalid', $user->id)->where('insureid', '0')->first();
            //return $nominee;
            $relation = $nominee['relation'];
            $nomineerelation = trim(strtok($relation, '('));
            //return $nomineerelation;

            $password = self::$password;
            $Id = self::$Id;
            $imdCode = self::$imdCode;
            $deptcode = self::$deptcode;

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
                    "previnsdtls" => [
                        "noofclaims" => "0",
                        "previnsname" => "",
                        "previnsaddress" => "",
                        "previnspolicyno" => "",
                        "prevpolicyexpirydate" => ""
                    ],
                    "hcpdtmemlist" => [
                        [
                            "membername" => $userData['firstName'] ?? "",
                            "memrelation" => $userData['relationCd'] ?? "",
                            "memdob" => $dob ?? "",
                            "memage" => $memage ?? "",
                            "memgender" => $userData['gender'] ?? "",
                            "memheightcm" => $userData['height'] ?? "",
                            "memweightkg" => $userData['weight'] ?? "",
                            "membmi" => "20.177555148009702",
                            "memoccupation" => "104",
                            "memgrossmonthlyincome" => "20000",
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
                            "specificDiseaseWaitingPeriod" => isset($aAddons['sdwp']) ? ($aAddons['sdwp'] * 12) : '',
                            "bahcpdtmemparam91" => null,
                            "bahcpdtmemparam41" => null
                        ]
                    ],
                    "hcpdtmemcovlist" => [
                        [
                            "membername" => $userData['firstName'] ?? "",
                            "memiptreatsi" => $sI ?? "",
                            "memiptreatplan" => "Plan 1",
                            "memiptreatsubplan" => "Plan 1",
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
                        "polcovvolntrycp" => "null",
                        "polcovspcndt" => "NA",
                        "polcovmedcnd" => "N",
                        "polcov91" => "NA",
                        "polcovihtsi" => $sI ?? "",
                        "polcov51" => "",
                        "polcovihtpln" => "",
                        "polcovmatexpnorm" => "0",
                        "polcovmatexpcsrn" => "0",
                        "polcov46" => "0"
                    ],
                    "hcpdtmemcovaddonlist" => [
                        [
                            "addonairambcv" => "",
                            "membername" => $userData['firstName'] ?? "",
                            "addon23" => isset($aAddons['addon23']) ? '1' : '0',
                            "addon24" => isset($aAddons['addon24']) ? '1' : '0',
                            "addon25" => isset($aAddons['addon25']) ? '1' : '0',
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
            // try {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.bagicuat.bajajallianz.com/bagicHws/health/healthissuepolicy',
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
            //dd($response);

            Log::info(['Response' => $response]);
            return $response;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public static function creatPolicyFloater(Request $request, $transicationid = null)
    {
        try {
            $userId = $request->userid;
            $user = User::find($userId);
            //return $user;
            $user1 = HealthJourney::where('userid', $user)->where('vid', getconstant("HEALTH.BAJAJ.KEY"))->first();
            //$cacheTransicationId = 'bajajcache_' . getconstant("HEALTH.BAJAJ.KEY") . '_proposalnum_' . $userId;
            //$TransactionId = GetCache($cacheTransicationId) ?? "";
            $TransactionId = RedisGet('transactionid:' . $userId) ?? "";
            // $startdate = "09-Nov-2025";
            // $enddate = "08-Nov-2026";
            // $dob = "05-Dec-1994";
            // $polcovzone = 1;
            // $totalmembernos = 1;
            // $memrelation = "Self";
            // $memage = "28";
            // $memgender = "F";
            //$productcode = "8456";
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
            ;
            $tenure = GetCache($cachetenure) ?? '1';
            $policyperiod = $tenure * 1200;
            $data = Insure::where('proposalid', $userId)->get()->toArray();

            //$polcovzone = 1;
            $totalmembernos = count($data) ?? 1;
            $memrelation = $data[0]['name'] ?? "Self";
            $memage = $data[0]['age'] ?? "28";
            $memgender = "F";
            $cachecovertype = 'cache_covertype_' . $userId;
            $producttype = GetCache($cachecovertype) ?? '1';
            $cachehealthplan = 'cache_healthplan_' . $userId;
            $plantype = GetCache($cachehealthplan) ?? '1';
            $businesstype = "";
            if ($plantype = "1") {
                $businesstype = "NB";
            } else {
                $businesstype = "PORT";
            }
            $productcode = "8457";
            $today = now()->format('Y-m-d');
            $startdate = $startdate = now()->format('d-M-Y');
            $enddate = now()->addYear()->subDay()->format('d-M-Y');
            $dob = date('d-M-Y', strtotime("-{$data[0]['age']} years", strtotime($today)));
            $user1 = HealthJourney::where('userid', $userId)->where('vid', getconstant("HEALTH.BAJAJ.KEY"))->first();
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

            //    return response()->json([
            //             'status' => true,
            //             'data' => $TransactionId
            //         ]);

            $commaddress = json_decode($user1->comunication_address);

            //$bankAccount = !empty(json_decode($user1['bank_details'])->account) ? json_decode($user1['bank_details'])->account : "SACBHJHB";

            //$bankIFSC = !empty(json_decode($user1['bank_details'])->ifsc) ? json_decode($user1['bank_details'])->ifsc : "SDVBHJSJDBV";

            //  $addons = (is_object($user1) && isset($user1->addon) && $user1->addon !== null)
            //      ? json_decode($user1->addon, true) : [];

            // $csaddonCode = "";
            // $cachecompulsory = 'cache_compulsoryaddon_' . $userId;
            // $cachedValue = GetCache($cachecompulsory);

            // $compulsoryaddon = $cachedValue ?? [];
            // // $compulsoryaddon = $cachedValue ? $cachedValue : [];

            $cachekyctype = 'cache_kyctype_' . $userId;
            GetCache($cachekyctype);
            $namePart = GetCache($cachekyctype) == 'o' ? explode(' ', $user->name) : explode(' ', $user1->kyc_name);
            // $insures = [];
            // $covertype = '';
            $relationCode = [
                'SELF' => 'Self',
                'HUSBAND' => 'Spouse',
                'WIFE' => 'Spouse',
                'SON' => 'Son',
                'DAUGHTER' => 'Daughter',
                'FATHER' => 'Father',
                'MOTHER' => 'Mother',
                'FATHERINLAW' => 'Father In Law ',
                'MOTHERINLAW' => 'Mother In Law '
            ];



            // $sumInsuredInd = [
            //     '5' => '621',//621
            //     '7' => '623',//623
            //     '10' => '625',//625
            //     '15' => '627',//627
            //     '25' => '631',//631
            //     '50' => '633',//633
            //     '100' => '637',//637
            // ];

            // $sumInsuredFlo = [
            //     '5' => '622',//622
            //     '7' => '624',//624
            //     '10' => '626',//626
            //     '15' => '628',//628
            //     '25' => '632',//632
            //     '50' => '034',//634
            //     '100' => '638',//638
            // ];
            // $addonCode = [];
            $contactDetails = json_decode($user1->contact_details);
            $permanentAddress = json_decode($user1->permanent_address);
            // $commaddress = json_decode($user1->comunication_address);
            $proposerDetails = json_decode($user1->proposer_details);
            //return $proposerDetails;
            // //dd($csaddonCode);
            //return $user->gender;
            $userData = [
                'dob' => $dob ?? "",//str_replace('-', '/', $user1->dob),
                'gender' => (strtoupper($user1->gender) == 'MS') ? 'F' : 'M',
                'height' => $proposerDetails->height ?? "",
                'weight' => $proposerDetails->weight ?? "",
                'house' => strtoupper($permanentAddress->address1) ?? "",//k
                'colony' => strtoupper($permanentAddress->address2) ?? "",//k
                'city' => $permanentAddress->city ?? "",//k
                'state' => $permanentAddress->state ?? "",//k
                'pincode' => $user->pincode ?? "",
                'mobile' => $contactDetails->contactmobile ?? "",
                'email' => $contactDetails->contactemail ?? $user->email,
                'panId' => strtoupper($user1->pan),
                'title' => strtoupper($user1->gender),
                'firstName' => $user1->name ?? "", //isset($namePart[0]) ? $namePart[0] : 'Unknown',
                'lastName' => count($namePart) > 1 ? end($namePart) : 'Unknown',
                //'bankAccount' => $bankAccount ?? "",
                'bankIFSC' => $bankIFSC ?? "",
                'relationCd' => $relationCode[strtoupper($user1->proposer)] //strtoupper($user->proposar)
            ];
            //return $userData;

            // //dd($userData);
            // //------------------------------------inserting proposar data------------------------------
            // //$ckycnumber = session()->get('ckycno');
            // $cacheckycno = 'cache_ckycno_' . $userId;
            // $ckycnumber = GetCache($cacheckycno);
            // $cacheckyctype = 'cache_kyctype_' . $userId;

            // //------------------------------------end inserting proposar data------------------------------
            // //dd($insures);

            $data = JourneyUsers::where('proposalid', $user->id)->where('insureid', '<>', '0')->get();
            //return $members->toArray();
            //return $data;
            foreach ($data as $member) {
                $memberdata[] = [
                    'name' => $member->name,
                    'relation' => $member->relation,
                    'dob' => $member->dob,
                    'age' => $member->age,
                    'ped' => $member->ped,
                ];
            }

            $ped = json_decode($memberdata[0]['ped'], true); // decode the string
            // $mempreexistdisease = isset($ped[0]['data']['mempreexistdisease'])
            //     ? $ped[0]['data']['mempreexistdisease']
            //     : '';
            // return $mempreexistdisease;
            $membercount = RedisGet('Member:' . $userId);
            $nominee = JourneyUsers::where('proposalid', $user->id)->where('insureid', '0')->first();
            //return $nominee;
            $relation = $nominee['relation'];
            $nomineerelation = trim(strtok($relation, '('));

            $hcpdtmemlist = [];
            $hcpdtmemcovlist = [];
            $hcpdtmemcovaddonlist = [];
            $relation = "";
            $Memgender = "";
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
                    "membername" => $member['name'] ?? "",
                    "memrelation" => $relation ?? "",
                    "memdob" => $dob,
                    "memage" => $member['age'],
                    "memgender" => $Memgender,
                    "memheightcm" => "",
                    "memweightkg" => "",
                    "membmi" => "",
                    "memoccupation" => "",
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
                    "memnomineename" => "Asd Asd",
                    "memnomineerelation" => $nomineerelation ?? "",
                    "memprvsi" => "",
                    "selfcoveredflag" => in_array(strtolower($member->relation), ['self']) ? "Y" : "N",
                    "roomRent" => isset($aAddons['RR']) ? $aAddons['RR'] : '',
                    "pedWaitingPeriodInMonths" => isset($aAddons['ped']) ? ($aAddons['ped'] * 12) : '',
                    "postHospitalizationExpenses" => isset($aAddons['phe']) ? $aAddons['phe'] : '',
                    "preHospitalizationExpenses" => isset($aAddons['prhe']) ? $aAddons['prhe'] : '',
                    "specificDiseaseWaitingPeriod" => isset($aAddons['sdwp']) ? ($aAddons['sdwp'] * 12) : '',
                ];

                // Coverage list
                $hcpdtmemcovlist[] = [
                    "membername" => $member['name'] ?? "",
                    "memiptreatsi" => $sI ?? "",
                    "memiptreatplan" => "Plan 1",
                    "memiptreatsubplan" => "Plan 1",
                    "memaddflag" => "Y",
                    "selfcoveredflag" => in_array(strtolower($member->relation), ['self']) ? "Y" : "N",
                    "memmatnormsi" => "0",
                    "memmatcesrnsi" => "0",
                    "memcoonbnftsi" => "",
                    "meminschldsi" => ""
                ];

                $hcpdtmemcovaddonlist[] = [
                    "membername" => $member['name'] ?? "",
                    "addonairambcv" => "",
                    "addon23" => isset($aAddons['addon23']) ? '1' : '0',
                    "addon24" => isset($aAddons['addon24']) ? '1' : '0',
                    "addon25" => isset($aAddons['addon25']) ? '1' : '0',
                    "memaddflag" => "Y"
                ];
            }
            $result = [
                "hcpdtmemlist" => $hcpdtmemlist,
                "hcpdtmemcovlist" => $hcpdtmemcovlist,
                "hcpdtmemcovaddonlist" => $hcpdtmemcovaddonlist,
            ];

            //return response()->json($result);

            //return $nomineerelation;

            // $csaddonCode = implode(',', array_map(function ($value) use ($addonCode) {
            //     return $addonCode[$value];
            // }, $compulsoryaddon));

            // foreach ($addons as $k => $value) {
            //     if (array_key_exists($value, $addonCode)) {
            //         $csaddonCode .= "," . $addonCode[$value];
            //     }
            // }
            // //dd($csaddonCode);
            // // $tenure = session('tenure');


            // $memberRelation = [];
            // foreach ($members as $key => $member) {
            //     $memberRelation[] = strtolower($member->relation);
            // }
            // //$covertype = (in_array('self', $memberRelation) && count($members) > 1) ? 'FAMILYFLOATER' : 'INDIVIDUAL';
            // //$covertype = count($members) > 1 ? 'FAMILYFLOATER' : 'INDIVIDUAL';

            // $cachecovertype = 'cache_covertype_' . $userId;
            // $covertype = GetCache($cachecovertype);
            // $normalized = strtoupper(trim($covertype));
            // if ($normalized == 'FLOATER') {
            //     $covertype = 'FAMILYFLOATER';
            // }
            // if ($normalized == 'INDIVIDUAL') {
            //     $covertype = 'INDIVIDUAL';
            // }
            // //return $covertype;

            // $sumInsured = '';

            // if (($covertype == 'FAMILYFLOATER') && array_key_exists(GetCache($cachecoverage), $sumInsuredFlo)) {
            //     $sumInsured = $sumInsuredFlo[GetCache($cachecoverage)];
            // } else {
            //     $sumInsured = $sumInsuredInd[GetCache($cachecoverage)];
            //}

            $password = self::$password;
            $Id = self::$Id;
            $imdCode = self::$imdCode;
            $deptcode = self::$deptcode;

            // $postData = json_encode(
            //     [
            //         "password" => $password ?? "Newpas12",
            //         "userid" => $Id ?? "webservice@digibima.com",
            //         "sourcedtls" => [
            //             "username" => $userData['firstName'] ?? "",
            //             "departmentcode" => "9906",
            //             "productcode" => $productcode ?? "8457",
            //             "businesstype" => $businesstype ?? "",
            //             "imdcode" => $imdCode,
            //             "subimdcode" => "",
            //             "modulename" => "WEBSERVICE"
            //         ],
            //         "policydtls" => [
            //             "remarks" => "https://kyc.uat.coverfox.com/covid-plan/order/ee13e40b249c4205a82acd1e2ac85082/response/",
            //             "termstartdate" => $startdate ?? "",
            //             "termenddate" => $enddate ?? "",
            //             "partnertype" => "P",
            //             "businesstype" => $businesstype ?? "",
            //             "policyperiod" => $policyperiod ?? "",
            //             "productcode" => $productcode ?? "8457",
            //             "scrlocationcode" => "9906",
            //             "paymentmode" => "CC",
            //             "scrcategory" => "NB",
            //             "deptcode" => $deptcode ?? "84"
            //         ],
            //         "tycpdetails" => [
            //             "telephone" => $userData['mobile'] ?? "",
            //             "nationality" => "Indian",
            //             "email" => $userData['email'] ?? "",
            //             "surname" => "Todkar",
            //             "contact1" => $userData['mobile'] ?? "",
            //             "dateofbirth" => $dob ?? "",
            //             "firstname" => $userData['firstName'] ?? "",
            //             "middlename" => "",
            //             "sex" => $userData['gender'] ?? ""
            //         ],
            //         "tycpaddrlist" => [
            //             [
            //                 "state" => $userData['state'] ?? "",
            //                 "postcode" => $userData['pincode'] ?? "",
            //                 "addressline1" => $userData['city'] ?? "",
            //                 "addressline2" => $userData['colony'] ?? "",
            //                 "addressline3" => $userData['house'] ?? "",
            //                 "addressline4" => "",
            //                 "addressline5" => "INDIA"
            //             ]
            //         ],
            //         "channeldtls" => [
            //             "subimdcode" => "",
            //             "imdcode" => $imdCode ?? "10105697"
            //         ],
            //         "previnsdtls" => [
            //             "noofclaims" => "0",
            //             "previnsname" => "",
            //             "previnsaddress" => "",
            //             "previnspolicyno" => "",
            //             "prevpolicyexpirydate" => ""
            //         ],
            //         "hcpdtmemlist" => $hcpdtmemlist,
            //         // [
            //         //     [
            //         //         "membername" => "Pooja Todkar",
            //         //         "memrelation" => "Self",
            //         //         "memdob" => $dob ?? "",
            //         //         "memage" => $memage ?? "",
            //         //         "memgender" => $memgender ?? "F",
            //         //         "memheightcm" => "165.1",
            //         //         "memweightkg" => "55",
            //         //         "membmi" => "20.177555148009702",
            //         //         "memoccupation" => "104",
            //         //         "memgrossmonthlyincome" => "20000",
            //         //         "mempreexistdisease" => "0",
            //         //         "memsmkertbco" => "0",
            //         //         "memasthma" => "0",
            //         //         "memcholstrldisordr" => "0",
            //         //         "memheartdisease" => "0",
            //         //         "memhypertension" => "0",
            //         //         "memdiabetes" => "0",
            //         //         "memobesity" => "0",
            //         //         "memaddflag" => "Y",
            //         //         "selfcoveredflag" => "Y",
            //         //         "memnomineename" => "Asd Asd",
            //         //         "memnomineerelation" => $nomineerelation ?? "",
            //         //         "memprvsi" => "",
            //         //         "roomRent" => "Actual",
            //         //         "pedWaitingPeriodInMonths" => "36",
            //         //         "postHospitalizationExpenses" => "90",
            //         //         "preHospitalizationExpenses" => "60",
            //         //         "specificDiseaseWaitingPeriod" => "24",
            //         //         "bahcpdtmemparam91" => null,
            //         //         "bahcpdtmemparam41" => null
            //         //     ]
            //         // ],
            //         "hcpdtmemcovlist" => $hcpdtmemcovlist,
            //         // [
            //         //     [
            //         //         "membername" => "Pooja Todkar",
            //         //         "memiptreatsi" => $sI ?? "",
            //         //         "memiptreatplan" => "Plan 1",
            //         //         "memiptreatsubplan" => "Plan 1",
            //         //         "memaddflag" => "N",
            //         //         "selfcoveredflag" => "Y",
            //         //         "memmatnormsi" => "0",
            //         //         "memmatcesrnsi" => "0",
            //         //         "memcoonbnftsi" => "",
            //         //         "meminschldsi" => ""
            //         //     ]

            //         // ],
            //         "hcpdtpolcovobj" => [
            //             "polcovzone" => $polcovzone ?? "1",
            //             "polcov41" => "NA",
            //             "polcovvolntrycp" => "",
            //             "polcovspcndt" => "NA",
            //             "polcovmedcnd" => "N",
            //             "polcov91" => "NA",
            //             "polcovihtsi" => $sI ?? "",
            //             "polcov51" => "",
            //             "polcovihtpln" => "",
            //             "polcovmatexpnorm" => "0",
            //             "polcovmatexpcsrn" => "0",
            //             "polcov46" => "0"
            //         ],
            //         "hcpdtmemcovaddonlist" => $hcpdtmemcovaddonlist,
            //         // [
            //         //     [
            //         //         "addonairambcv" => "",
            //         //         "membername" => "Pooja Todkar",
            //         //         "addon23" => "0",
            //         //         "addon24" => "0",
            //         //         "addon25" => "0",
            //         //         "memaddflag" => "Y"
            //         //     ]

            //         // ],
            //         "hcpstagedataobj" => [
            //             "busstype" => $businesstype ?? "NB",
            //             "productcode" => $productcode ?? "8457",
            //             "termstartdate" => $startdate ?? "",
            //             "termenddate" => $enddate ?? "",
            //             "selfcoveredflag" => "Y",
            //             "membercombo" => $membercount ?? "2A",
            //             "totalmembernos" => $totalmembernos ?? "2",
            //             "partnerpincode" => $pincode ?? ""
            //         ],
            //         "transactionid" => $TransactionId ?? ""
            //     ]
            // );

            $postData = json_encode([
                [
                    "password" => $password ?? "Newpas12",
                    "userid" => $Id ?? "webservice@digibima.com",
                    "sourcedtls" => [
                        "username" => $userData['firstName'] ?? "",
                        "departmentcode" => "1101",
                        "productcode" => $productcode ?? "8451",
                        "businesstype" => $businesstype ?? "",
                        "imdcode" => $imdCode,
                        "subimdcode" => "",
                        "modulename" => "WEBSERVICE"
                    ],

                    "policydtls" => [
                        "remarks" => "https://kyc.uat.coverfox.com/covid-plan/order/ee13e40b249c4205a82acd1e2ac85082/response/",
                        "termstartdate" => $startdate ?? "",
                        "termenddate" => $enddate ?? "",
                        "partnertype" => "P",
                        "businesstype" => $businesstype ?? "",
                        "policyperiod" => $policyperiod ?? "",
                        "productcode" => $productcode ?? "8451",
                        "scrlocationcode" => "1101",
                        "paymentmode" => "CC",
                        "scrcategory" => "NB",
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

                    "hcpdtmemlist" => $hcpdtmemlist,
                    
                   "hcpdtmemcovlist" => $hcpdtmemcovlist,

                    "hcpdtpolcovobj" => [
                        "polcovzone" => $polcovzone ?? "1",
                        "polcov41" => "NA",
                        "polcovvolntrycp" => "",
                        "polcovspcndt" => "NA",
                        "polcovmedcnd" => "N",
                        "polcov91" => "NA",
                        "polcovihtsi" => $sI ?? "",
                        "polcov51" => "",
                        "polcovihtpln" => "",
                        "polcovmatexpnorm" => "0",
                        "polcovmatexpcsrn" => "0",
                        "polcov46" => "0"
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
            ]);
            // return $postData;
            //dd($postData);
            // try {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.bagicuat.bajajallianz.com/bagicHws/health/healthissuepolicy',
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
            //dd($response);

            Log::info(['Response' => $response]);
            return $response;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public static function policyStatus(Request $request, $proposal)
    {
        $userId = $request->userid;
        //$cacheproposalnum = 'cache_' . getconstant("HEALTH.CARESUPREME.KEY") . '_proposalnum_' . $userId;
        //$proposalnum = GetCache($cacheproposalnum);
        $transactionId = "";
        $curl = curl_init();
        try {
            $payload = json_encode([
                "intGetPolicyStatusIO" => [
                    "userid" => "test_health_b2b",
                    "password" => "test",
                    "transaction_id" => "11-8450-0000001669-00",
                    "payment_transobj" => [
                        "stringval1" => "Y",
                        "stringval2" => "6188359",
                        "stringval3" => "HEALTH_WS",
                        "stringval6" => "HDFC",
                        "stringval20" => "1000.0",
                    ]
                ]

            ]);

            curl_setopt_array($curl, [
                //CURLOPT_URL => 'https://api.careinsurance.com/relinterfacerestful/religare/secure/restful/getPolicyStatusV2',
                CURLOPT_URL => 'https://api.bagicuat.bajajallianz.com/bagicHws/health/getpgtransstatus',
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
                    'agentId: ' . self::$agentid, //20008325', //20689274
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
                [

                    "userid" => "test_health_b2b",
                    "password" => "test",
                    "pdfmode" => "health",
                    "policynum" => "12-8429-0000000589-00"
                ]


            ]);
            curl_setopt_array($curl, [
                //CURLOPT_URL => 'https://api.careinsurance.com/relinterfacerestful/religare/secure/restful/getPolicyPDFV2',
                CURLOPT_URL => 'https://api.bagicuat.bajajallianz.com/ext/common/commoncs/BjazDownloadPDFWs/policypdfdownload',
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
                    'agentId: ' . self::$agentid, //20008325', //20689274
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


}
