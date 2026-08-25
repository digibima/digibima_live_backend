<?php
namespace App\Services\Api\Zuno;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Zuno\{Zuno_RTO_Master, Zuno_Pincode, Zuno_Prev_Insurer};
use App\Models\{Master_Vehicle_Data as DataModel, MasterAPI, User, MotorJourney, MasterVendor, VendorMotor, MasterMotor, UserMotorDescription, Vehicle_Info};
use Illuminate\Support\Facades\{Auth, Cache};
use App\Services\Api\Zuno\ZunoUtilityService;
use Vtiful\Kernel\Format;
class ZunoCarService
{

    private static $xapikey = "Bb0FQ95zah3o8rlAwX2es9vKOcW5u9k61avBxGrP";
    private static $Masterxapikey = "Y1B6JKVTVN4IRoho0I3whafGfU9IiNkDa8K6GcKm";

    // private static $password;
    // private static $deptcode;

    // public static function initlize()
    // {
    //     self::$Id = getconstant('HEALTH.BAJAJ.CREDENTIAL.ID');
    //     self::$imdCode = getconstant('HEALTH.BAJAJ.CREDENTIAL.IMDCODE');
    //     self::$password = getconstant('HEALTH.BAJAJ.CREDENTIAL.PASSWORD');
    //     self::$deptcode = getconstant('HEALTH.BAJAJ.CREDENTIAL.DEPTCODE');

    // }


    public static function generatePrivateCarQuote(Request $request, $nextyear, $nPlanType)
    {
        try {
            //SaveFile("ttttt","zuno.txt");
            $userId = $request->userid;
            $user = User::find($userId);
            $utilityService = new ZunoUtilityService();
            $anewtoken = $utilityService->generateToken();
            $stoken = $anewtoken['access_token'];
            $today = now();
            $oModel = null;
            $claim = null;
            $claimper = null;
            $transferOfowner = null;
            $firstregdate = null;
            $PolicyFromDate = null;
            $sPrevexptoDate = null;
            $sPretpfDate = null;
            $sPretptoDate = null;
            $RSACover = null;
            $Eng_Protector = null;
            $Consumables = null;
            $cachepolicyExpiry = 'cache_policyExpiry_' . $userId;
            //$nTpolicyNo = null;
            $PAcover = null;
            $pAcoverReason = null;
            $contractDetails = [];
            $under = "";
            $AuthUser = $user->toArray();
            $oJourneyData = MotorJourney::where('userid', $userId)->where('is_car', '1')->first();
            $aData = DataModel::where('userid', $userId)->first();
            $aPAforUnnamedamount = json_decode($aData->caraddonvalue, true);
            $PAforUnnamedamount = !empty($aPAforUnnamedamount) ? $aPAforUnnamedamount : "0";
            $aAccessories = json_decode($aData->accessories, true);
            $dRegDate = $aData->knowcar_reg_details ? json_decode($aData->knowcar_reg_details, true)['carregdate'] : date('d-m-Y');
            $cachemotortype = 'cache_motortype_' . $userId;
            $regDate = \DateTime::createFromFormat('Y-m-d', $dRegDate);
            $validregDate = \DateTime::createFromFormat('d-m-Y', $dRegDate);
            //return GetCache($cachemotortype);
            // if (GetCache($cachemotortype) == 'knowcar') {
            //     //$cachemodel = 'cache_model_knowcar' . $userId;
            //     $aCardata = json_decode($aData->knowcar_reg_details, true) ?? [];
            //     $modelSearch = $aCardata['model'] ?? "";
            //     $dRegDate = $aData->knowcar_reg_details ? json_decode($aData->knowcar_reg_details, true)['carregdate'] : date('d-m-Y');
            // }
            // if (GetCache($cachemotortype) == 'newcar') {
            //     $aCardata = json_decode($aData->newcar_reg_details, true) ?? [];
            //     $modelSearch = $aCardata['model'] ?? "";
            //     $dRegDate = $aData->newcar_reg_details ? json_decode($aData->newcar_reg_details, true)['carregdate'] : date('d-m-Y');
            // }
            // $regDate = \DateTime::createFromFormat('Y-m-d', $dRegDate);
            // $vid = getconstant("MOTOR.GODIGIT.KEY");
            // $vdata = getVcode($modelSearch, $vid);
            // //dd($vdata,$vdata['data']->vcode, $modelSearch, $vid);
            // $oModel = $vdata['status'] && isset($vdata['data']->vcode) ? $vdata['data']->vcode : $vdata['data'];

            // if (!$vdata['status']) {
            //     dd($oModel);
            //     return response()->json($oModel);
            // }
            $addonAgeLimit = [
                "101" => 7,
                "103" => 10,
                "104" => 10,
                "107" => 5,
                "108" => 7,
                "109" => 5,
                "110" => 5,
                "111" => 10,
                "106" => 1
            ];
            //$aAddons = json_decode($aData->caraddon, true) ?? [];
            $aCarAddon = is_string($aData->caraddon)
                ? json_decode($aData->caraddon, true)
                : (array) $aData->caraddon;

            // return[
            //       "adsdf"=>$aCarAddon
            // ];


            $aAddons = !empty($aCarAddon['tpselectedaddon'])
                ? $aCarAddon['tpselectedaddon']
                : (
                    !empty($aCarAddon['selectedaddon'])
                    ? $aCarAddon['selectedaddon']
                    : (
                        !empty($aCarAddon['odselectedaddon'])
                        ? $aCarAddon['odselectedaddon']
                        : []
                    )
                );
            $validAddons = [];

            foreach ($aAddons as $addonId) {
                $addonId = (string) $addonId;
                if (isset($addonAgeLimit[$addonId])) {
                    $maxyears = $addonAgeLimit[$addonId];
                    if (ValidateAddonAge($validregDate, $maxyears)) {
                        $validAddons[] = $addonId;
                    }
                    // else expired, skip
                } else {
                    $validAddons[] = $addonId; // no age limit, keep it
                }
            }

            $aAddons = $validAddons;

            // return [
            //     "valid" => $aAddons
            // ];
            $aResult = [];
            if (!empty($aAccessories)) {
                foreach ($aAccessories as $item) {
                    $aResult[$item['type']] = $item['amount'];

                }
            }
            $aCardata = json_decode($aData->knowcar_reg_details, true) ?? [];
            //return $aCardata;

            if (GetCache($cachemotortype) == "knowcar") {
                if ($aCardata['prepolitype'] == 'odonly') {
                    $sPrePolicyType = getconstant("MOTOR.ZUNO.POLICYTYPE.OWNDAMAGE");
                    $sPrevexptoDate = $aCardata['odtodate']; //k
                    $newdate = \DateTime::createFromFormat('d-m-Y', $aCardata['odtodate']);
                    $PrevexptoDate = $newdate ? $newdate->format('Y-m-d') : null;
                    $sPrevexpfDate = \DateTime::createFromFormat('d-m-Y', $aCardata['odfromdate']); //k
                    $sPretpfDate = \DateTime::createFromFormat('d-m-Y', $aCardata['odtpfromdate']);
                    $sPretptoDate = \DateTime::createFromFormat('d-m-Y', $aCardata['odtptodate']);
                    //$nTpolicyNo = array_key_exists('tppolicynumber', $prevPolicydata) ? $prevPolicydata['tppolicynumber'] : '';
                    $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
                    $PolicyFromDate = $date->addDay()->format('Y-m-d');
                    $PolicyToDate = $date->addYear()->subDay()->format('Y-m-d');
                } else if ($aCardata['prepolitype'] == 'bundled') {
                    $sPrePolicyType = getconstant("MOTOR.ZUNO.POLICYTYPE.BUNDLED");
                    $sPrevexptoDate = $aCardata['bdtodate']; //k
                    $newdate = \DateTime::createFromFormat('d-m-Y', $aCardata['bdtodate']);
                    $PrevexptoDate = $newdate ? $newdate->format('Y-m-d') : null;
                    $sPrevexpfDate = $aCardata['bdfromdate']; //k
                    $sPretpfDate = $aCardata['bdtpfromdate'];
                    $sPretptoDate = $aCardata['bdtptodate'];
                    //$nTpolicyNo = array_key_exists('tppolicynumber', $prevPolicydata) ? $prevPolicydata['tppolicynumber'] : '';
                    $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
                    $PolicyFromDate = $date->addDay()->format('Y-m-d');
                    $PolicyToDate = $date->addYear()->subDay()->format('Y-m-d');

                } elseif ($aCardata['prepolitype'] == 'comprehensive') {
                    $sPrePolicyType = getconstant("MOTOR.ZUNO.POLICYTYPE.PACKAGE");
                    $sPrevexptoDate = $aCardata['comptodate']; //k
                    $newdate = \DateTime::createFromFormat('d-m-Y', $aCardata['comptodate']);
                    $PrevexptoDate = $newdate ? $newdate->format('Y-m-d') : null;
                    $sPrevexpfDate = $aCardata['compfromdate'];
                    $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
                    $PolicyFromDate = $date->addDay()->format('Y-m-d');
                    $PolicyToDate = $date->addYear()->subDay()->format('Y-m-d');
                    //dd($PolicyFromDate,$PolicyToDate);

                } elseif ($aCardata['prepolitype'] == 'tponly') {
                    $sPrePolicyType = getconstant("MOTOR.ZUNO.POLICYTYPE.LIABILITY");
                    $sPrevexptoDate = $aCardata['tptodate']; //k
                    $newdate = \DateTime::createFromFormat('d-m-Y', $aCardata['tptodate']);
                    $PrevexptoDate = $newdate ? $newdate->format('Y-m-d') : null;
                    $sPrevexpfDate = $aCardata['tpfromdate']; //k
                    $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
                    $PolicyFromDate = $date->addDay()->format('Y-m-d');
                    $PolicyToDate = $date->addYear()->subDay()->format('Y-m-d');
                    //dd($PolicyFromDate,$PolicyToDate);
                }

            }
            $sRegNumber = "";
            $sRegNo2 = "";
            $sRegNo3 = "";
            $sRegNo1 = "";
            $sRegNo4 = "";

            if (GetCache($cachemotortype) == 'newcar') {
                $sRegNumber = substr(explode('(', $aData->rtocode)[1], 0, -1);
                $sRegNo1 = substr(str_replace('-', '', $sRegNumber), 0, 2);//k
                $sRegNo2 = substr(str_replace('-', '', $sRegNumber), 2, 2);//k
            } else {
                $sRegNumber = $aData->carnumber;
                $sRegNo1 = substr($sRegNumber, 0, 2);//k
                $sRegNo2 = substr($sRegNumber, 2, 2);//k
                $sRegNo3 = substr($sRegNumber, 4, 2);//k
                $sRegNo4 = substr($sRegNumber, 6, 4);//k
            }
            $sRtocode = $sRegNo1 . "-" . $sRegNo2;
            //return $sRtocode;
            $sRtodetails = Zuno_RTO_Master::where('LOCATION_CD', $sRtocode)->first();
            //return $sRtodetails;
            $make = 'MARUTI';
            $model = 'CIAZ';
            $variant = 'SMART HYBRID ALPHA';
            $idvcity = $sRtodetails->IDVCITY_CD;
            $aVehicleDetails = [
                'user' => $user,
                'make' => $make,
                'model' => $model,
                'variant' => $variant,
                'idvcity' => $idvcity,
            ];
            $regDt = Carbon::parse($dRegDate)->format('Y-m-d');
            $vehicleAge = $dRegDate ? Carbon::parse($dRegDate)->diffInYears(Carbon::now()) : 0;
            $IdvDetails = json_decode(self::FindIdv($aVehicleDetails), true);
            $ageToKey = [
                0 => 'upto6Months',
                1 => 'upto1Year',
                2 => 'upto2Year',
                3 => 'upto3Year',
                4 => 'upto4Year',
                5 => 'upto5Year',
                6 => 'upto6Year',
                7 => 'upto7Year',
                8 => 'upto8Year',
                9 => 'upto9Year',
                10 => 'upto10Year',
            ];

            $selectedKey = $ageToKey[$vehicleAge] ?? 'upto10Year';
            $idvAmount = $IdvDetails['idvAmount'][$selectedKey] ?? "0";
            //return $idvAmount;
            $cache_idv = 'cache_zunocaridv' . $userId;
            $cacheshowroompr = 'cache_exshworoomprice' . $userId;
            SetCache($cacheshowroompr, $IdvDetails['exShowroomPrice']);
            SetCache($cache_idv, $idvAmount);
            //return GetCache($cache_idv);
            $permanentAddress = isset($oJourneyData->permanent_address) && json_decode($oJourneyData->permanent_address, true) ? json_decode($oJourneyData->permanent_address, true) : [];
            $pincode = $user->pincode;
            $state = Zuno_Pincode::where('Postcode', $pincode)->first();
            // return $pincode;
            // if (!$state) {
            //     return ['status' => '0', 'message' => 'Pincode not available for this service.'];
            // }
            //$sState = $state->STATE;
            if (GetCache($cachemotortype) == "knowcar") {
                $aCardata = json_decode($aData->knowcar_reg_details, true) ?? [];
                if ($aCardata['under'] == "company") {
                    $PAcover = "0";
                    $pAcoverReason = "PA_TYPE2";
                } else {
                    $PAcover = $aData->pacover;
                    if ($PAcover == 0) {
                        $pAcoverReason = json_decode($aData->pacover_reason, true);
                        $ncoverReason = is_array($pAcoverReason) ? (array_keys($pAcoverReason)[0] ?? []) : [];
                        if ($ncoverReason == '1') {
                            $pAcoverReason = "PA_TYPE1";
                        }
                        if ($ncoverReason == '2') {
                            $pAcoverReason = "PA_TYPE2";
                        }
                        if ($ncoverReason == '3') {
                            $pAcoverReason = "PA_TYPE4";
                        } else {
                            $pAcoverReason = "";
                        }
                    }
                }
                if (!empty($aCardata['carregdate'])) {
                    $formats = ['Y-m-d', 'd-m-Y', 'd/m/Y', 'Y/m/d'];
                    foreach ($formats as $format) {
                        $date = \DateTime::createFromFormat($format, $aCardata['carregdate']);
                        if ($date) {
                            $firstregdate = $date->format('Y-m-d');
                            break;
                        }
                    }
                }
                $claim = array_key_exists('policytoggle', $aCardata) ? '0' : '1';
                $claimper = array_key_exists('bonus-button', $aCardata) ? $aCardata['bonus-button'] : "";
                $transferOfowner = array_key_exists('ownershiptoggle', $aCardata) ? $aCardata['ownershiptoggle'] : '0';
            }

            if (GetCache($cachemotortype) == "newcar") {
                $aCardata = json_decode($aData->newcar_reg_details, true) ?? [];
                if ($aCardata['under'] == "company") {
                    $PAcover = "0";
                    $pAcoverReason = "PA_TYPE2";
                } else {
                    $PAcover = $aData->pacover;
                    if ($PAcover == 0) {
                        $pAcoverReason = json_decode($aData->pacover_reason, true);
                        $ncoverReason = is_array($pAcoverReason) ? (array_keys($pAcoverReason)[0] ?? []) : [];
                        if ($ncoverReason == '1') {
                            $pAcoverReason = "PA_TYPE1";
                        }
                        if ($ncoverReason == '2') {
                            $pAcoverReason = "PA_TYPE2";
                        }
                        if ($ncoverReason == '3') {
                            $pAcoverReason = "PA_TYPE4";
                        } else {
                            $pAcoverReason = "";
                        }
                    }
                }
                $firstregdate = $today->format('Y-m-d');
            }
            // $sVehicleCode = " ";
            // if ($oModel) {
            //     $sVehicleCode = $oModel->VEHICLE_CODE;

            // }
            $sProdCode = getconstant("MOTOR.ZUNO.PRODUCTTYPE.PRIVATECAR");
            $sPolicyType = "";
            $sProposalType = "";
            $ntenure = "";
            $today = now();
            $caTenure = null;
            if (GetCache($cachemotortype) == "newcar") {
                $VehicleType = "W";
                $caTenure = "3";
                $sProposalType = getconstant("MOTOR.ZUNO.PROPOSALTYPE.FRESHPROPOSAL");
                if ($nPlanType == '2') {
                    $PolicyFromDate = $today->format('Y-m-d');
                    $ntenure = '3';
                    $PolicyToDate = $today->addYears(3)->subDay()->format('Y-m-d');
                    $sPolicyType = getconstant("MOTOR.ZUNO.POLICYTYPE.BUNDLED");
                    $NilDepreciationCover = in_array('101', $aAddons) ? "Zero Depreciation" : "";
                    $RSACover = in_array('102', $aAddons) ? "Basic Road Assistance" : "";
                    $DailyExpRem = in_array('109', $aAddons) ? "Y" : "N";
                    $KeyReplacement = in_array('111', $aAddons) ? "Key Replacement" : "";
                    $LossOfPersonBelong = in_array('107', $aAddons) ? "Loss of Personal Belongings" : "";
                    $EmergencyTranHotelExpRemYN = in_array('108', $aAddons) ? "Y" : "N";
                    $MultiCarBenefitYN = in_array('110', $aAddons) ? "Y" : "N";
                    $Eng_Protector = in_array('104', $aAddons) ? "Engine Protect" : "";
                    $Consumables = in_array('103', $aAddons) ? "Consumable Cover" : "";
                    $InvReturn = in_array('106', $aAddons) ? "Return To Invoice" : "";
                    $AntiTheftYN = in_array('119', $aAddons) ? "1" : "0";
                    $LLtoPaidDriverYN = in_array('116', $aAddons) ? "1" : "0";
                    $NoEmpCoverLL = in_array('122', $aAddons) ? "1" : "0";
                    $SHRIMOTORPROTECTION_YN = in_array('113', $aAddons) ? "Y" : "N";
                    $LimitedTPPDYN = in_array('121', $aAddons) ? "1" : "0";
                    $VoluntaryExcess = in_array('120', $aAddons) ? "1" : "0";
                    $PAPaidDriverConductorCleaner = 1;
                    $Geographical = in_array('117', $aAddons) ? 1 : "0";
                    $Geographical = in_array('118', $aAddons) ? 1 : "0";
                    $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";

                    $contractDetails = [
                        [
                            "contract" => "Own Damage Contract",
                            "coverage" => [
                                "coverage" => "Own Damage Coverage",
                                //"deductible"=> "Own Damage Basis Deductible",
                                "voluntaryCoPay" => "Rs. 2500",
                                "discount" => [
                                    "Voluntary Deductible Discount",
                                    "AntiTheft Discount",
                                    "No Claim Bonus Discount",
                                    //"Handicapped Discount",
                                    //"Vintage Car Discount",
                                    "Pay As You Drive Discount",
                                    //"Preferred Garage Discount",
                                    //"Auto Mobile Association Discount"
                                ],
                                "subCoverage" => [
                                    [
                                        "subCoverage" => "Own Damage Basic",
                                        "limit" => "Own Damage Basic Limit"
                                    ]
                                ]
                            ]
                        ],
                        [
                            "contract" => "Addon Contract",
                            "coverage" => [
                                "coverage" => "Add On Coverage",
                                //"deductible"=> "Key Replacement Deductible",
                                "subCoverage" => [
                                    [
                                        "subCoverage" => $InvReturn ?? ""
                                    ],
                                    [
                                        "subCoverage" => $KeyReplacement ?? ""
                                    ],
                                    [
                                        "subCoverage" => "Tyre Safeguard"
                                    ],
                                    [
                                        "subCoverage" => $NilDepreciationCover ?? ""
                                    ],
                                    [
                                        "subCoverage" => $Eng_Protector ?? ""
                                    ],
                                    [
                                        "subCoverage" => $Consumables ?? ""
                                    ],
                                    [
                                        "subCoverage" => $RSACover ?? ""
                                    ],
                                    [
                                        "subCoverage" => $LossOfPersonBelong ?? ""
                                    ]
                                ]
                            ]
                        ],
                        [
                            "contract" => "PA Compulsary Contract",
                            "coverage" => [
                                "coverage" => "PA Owner Driver Coverage",
                                "subCoverage" => [
                                    "subCoverage" => "PA Owner Driver",
                                    "limit" => "PA Owner Driver Limit",
                                    "sumInsuredperperson" => "1500000"
                                ]
                            ]
                        ],
                        [
                            "contract" => "Third Party Multiyear Contract",
                            "coverage" => [
                                "coverage" => "Legal Liability to Third Party Coverage",
                                "deductible" => "TP Deductible",
                                "discount" => [],
                                "subCoverage" => [
                                    [
                                        "subCoverage" => "Third Party Basic Sub Coverage",
                                        "limit" => "Third Party Property Damage Limit",
                                        "thirdPartyPropertyDamageLimit" => "750000"
                                    ],
                                    [
                                        "subCoverage" => "Legal Liability to Paid Drivers",
                                        "numberofPaidDrivers" => "1"
                                    ],
                                    [
                                        "subCoverage" => "PA Unnamed Passenger",
                                        "limit" => "PA Unnamed Passenger Limit",
                                        "sumInsuredperperson" => "10000"
                                    ]
                                ]
                            ]
                        ]
                    ];

                }
            }
            if (GetCache($cachemotortype) == "knowcar") {
                //return GetCache($cachemotortype);
                $VehicleType = "U";
                $ntenure = '1';
                $caTenure = '1';
                $sProposalType = getconstant("MOTOR.ZUNO.PROPOSALTYPE.MARKETRENEWAL");
                if ($nPlanType == '1') {
                    $sPolicyType = getconstant("MOTOR.ZUNO.POLICYTYPE.OWNDAMAGE");
                    $NilDepreciationCover = in_array('101', $aAddons) ? "Zero Depreciation" : "";
                    $RSACover = in_array('102', $aAddons) ? "Basic Road Assistance" : "";
                    $DailyExpRem = in_array('109', $aAddons) ? "Y" : "N";
                    $KeyReplacement = in_array('111', $aAddons) ? "Key Replacement" : "";
                    $LossOfPersonBelong = in_array('107', $aAddons) ? "Loss of Personal Belongings" : "";
                    //$EmergencyTranHotelExpRemYN = in_array('108', $aAddons) ? "Y" : "N";
                    //$MultiCarBenefitYN = in_array('110', $aAddons) ? "Y" : "N";
                    $Eng_Protector = in_array('104', $aAddons) ? "Engine Protect" : "";
                    $Consumables = in_array('103', $aAddons) ? "Consumable Cover" : "";
                    $InvReturn = in_array('106', $aAddons) ? "Return To Invoice" : "";
                    $AntiTheftYN = in_array('119', $aAddons) ? "1" : "0";
                    $VoluntaryExcess = in_array('120', $aAddons) ? "1" : "0";
                    $PAPaidDriverConductorCleaner = 1;
                    $Geographical = in_array('118', $aAddons) ? 1 : "0";
                    $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";

                    $contractDetails = [
                        [
                            "contract" => "Own Damage Contract",
                            "coverage" => [
                                "coverage" => "Own Damage Coverage",
                                "deductible" => "Own Damage Basis Deductible",
                                "discount" => [
                                    "Voluntary Deductible Discount",
                                    "AntiTheft Discount",
                                    "No Claim Bonus Discount",
                                    //"Handicapped Discount",
                                    //"Vintage Car Discount",
                                    "Pay As You Drive Discount"
                                    //"Preferred Garage Discount"
                                    //"Auto Mobile Association Discount"
                                ],
                                "subCoverage" => [
                                    [
                                        "subCoverage" => "Own Damage Basic",
                                        "limit" => "Own Damage Basic Limit"
                                    ]
                                    // [
                                    //     "subCoverage"=> "Non Electrical Accessories",
                                    //     "limit"=> "Non Electrical Accessories Limit",
                                    //     "valueOfAccessory"=> "10000.0",
                                    //     "accessoryDescription"=> "Guard"
                                    // ],
                                    // [
                                    //     "subCoverage"=> "Electrical Electronic Accessories",
                                    //     "limit"=> "Electrical Electronic Accessories Limit",
                                    //     "valueOfAccessory"=> "10000.0",
                                    //     "accessoryDescription"=> "Headlights"
                                    // ],
                                    // [
                                    //     "subCoverage"=> "CNG LPG Kit Own Damage",
                                    //     "limit"=> "CNG LPG Kit Own Damage Limit",
                                    //     "valueOfKit"=> "10000.0",
                                    //     "accessoryDescription"=> "CNG"
                                    // ]
                                ]
                            ]
                        ],
                        [
                            "contract" => "Addon Contract",
                            "coverage" => [
                                "coverage" => "Add On Coverage",
                                //"deductible"=> "Preferred Garage Deductible",
                                //"preferredGarageDeductibleAmount"=> "Rs. 10000",
                                //"underwriterDiscount"=> "0.0",
                                "subCoverage" => [
                                    [
                                        "subCoverage" => $InvReturn ?? ""
                                    ],
                                    [
                                        "subCoverage" => $KeyReplacement ?? ""
                                    ],
                                    // [
                                    //     "subCoverage" => "Protection of NCB"
                                    // ],
                                    [
                                        "subCoverage" => $NilDepreciationCover ?? ""
                                    ],
                                    [
                                        "subCoverage" => $Eng_Protector ?? ""
                                    ],
                                    [
                                        "subCoverage" => $Consumables ?? ""
                                    ],
                                    // [
                                    //     "subCoverage" => "Waiver of Policy"
                                    // ],
                                    [
                                        "subCoverage" => $RSACover ?? ""
                                    ],
                                    [
                                        "subCoverage" => $LossOfPersonBelong ?? ""
                                    ]
                                    // [
                                    //     "subCoverage" => "Pay As You Drive"
                                    // ]
                                    // [
                                    //     "subCoverage"=> "Preferred Garage Discount"
                                    // ]
                                ]
                            ]
                        ]
                    ];

                }
                if ($nPlanType == '2') {
                    $sPolicyType = getconstant("MOTOR.ZUNO.POLICYTYPE.PACKAGE");
                    $NilDepreciationCover = in_array('101', $aAddons) ? "Zero Depreciation" : "";
                    $RSACover = in_array('102', $aAddons) ? "Basic Road Assistance" : "";
                    $DailyExpRem = in_array('109', $aAddons) ? "Y" : "N";
                    $KeyReplacement = in_array('111', $aAddons) ? "Key Replacement" : "";
                    $LossOfPersonBelong = in_array('107', $aAddons) ? "Loss of Personal Belongings" : "";
                    $EmergencyTranHotelExpRemYN = in_array('108', $aAddons) ? "Y" : "N";
                    $MultiCarBenefitYN = in_array('110', $aAddons) ? "Y" : "N";
                    $Eng_Protector = in_array('104', $aAddons) ? "Engine Protect" : "";
                    $Consumables = in_array('103', $aAddons) ? "Consumable Cover" : "";
                    $InvReturn = in_array('106', $aAddons) ? "Return To Invoice" : "";
                    $AntiTheftYN = in_array('119', $aAddons) ? "1" : "0";
                    $LLtoPaidDriverYN = in_array('116', $aAddons) ? "1" : "0";
                    $NoEmpCoverLL = in_array('122', $aAddons) ? "1" : "0";
                    $SHRIMOTORPROTECTION_YN = in_array('113', $aAddons) ? "Y" : "N";
                    $LimitedTPPDYN = in_array('121', $aAddons) ? "1" : "0";
                    $VoluntaryExcess = in_array('120', $aAddons) ? "1" : "0";
                    $PAPaidDriverConductorCleaner = 1;
                    $Geographical = in_array('117', $aAddons) ? 1 : "0";
                    $Geographical = in_array('118', $aAddons) ? 1 : "0";
                    $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";

                    $contractDetails =
                        [
                            [
                                "contract" => "Own Damage Contract",
                                "coverage" => [
                                    "coverage" => "Own Damage Coverage",
                                    "voluntaryCoPay" => "Rs. 2500",
                                    "discount" => [
                                        "Voluntary Deductible Discount",
                                        "AntiTheft Discount",
                                        "No Claim Bonus Discount",
                                        "Pay As You Drive Discount"
                                    ],
                                    "subCoverage" => [
                                        [
                                            "subCoverage" => "Own Damage Basic"
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "contract" => "Addon Contract",
                                "coverage" => [
                                    "coverage" => "Add On Coverage",
                                    "subCoverage" => [
                                        [
                                            "subCoverage" => $InvReturn ?? ""
                                        ],
                                        [
                                            "subCoverage" => $KeyReplacement ?? ""
                                        ],
                                        [
                                            "subCoverage" => "Tyre Safeguard"
                                        ],
                                        [
                                            "subCoverage" => $NilDepreciationCover ?? ""
                                        ],
                                        [
                                            "subCoverage" => $Eng_Protector ?? ""
                                        ],
                                        [
                                            "subCoverage" => $Consumables ?? ""
                                        ],
                                        [
                                            "subCoverage" => $RSACover ?? ""
                                        ],
                                        [
                                            "subCoverage" => $LossOfPersonBelong ?? ""
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "contract" => "PA Compulsary Contract",
                                "coverage" => [
                                    "coverage" => "PA Owner Driver Coverage",
                                    "subCoverage" => [
                                        "subCoverage" => "PA Owner Driver",
                                        "limit" => "PA Owner Driver Limit",
                                        "sumInsuredperperson" => "1500000"
                                    ]
                                ]
                            ],
                            [
                                "contract" => "Third Party Multiyear Contract",
                                "coverage" => [
                                    "coverage" => "Legal Liability to Third Party Coverage",
                                    "deductible" => "TP Deductible",
                                    "discount" => [],
                                    "subCoverage" => [
                                        [
                                            "subCoverage" => "Third Party Basic Sub Coverage",
                                            "limit" => "Third Party Property Damage Limit",
                                            "thirdPartyPropertyDamageLimit" => "750000"
                                        ],
                                        [
                                            "subCoverage" => "Legal Liability to Paid Drivers",
                                            "numberofPaidDrivers" => "1"
                                        ],
                                        [
                                            "subCoverage" => "PA Unnamed Passenger",
                                            "limit" => "PA Unnamed Passenger Limit",
                                            "sumInsuredperperson" => "10000"
                                        ]
                                    ]
                                ]
                            ]
                        ];

                }
                if ($nPlanType == '3' && (GetCache($cachepolicyExpiry) == "Not Expired")) {
                    $sPolicyType = getconstant("MOTOR.ZUNO.POLICYTYPE.LIABILITY");
                    $LLtoPaidDriverYN = in_array('116', $aAddons) ? "1" : "0";
                    $LimitedTPPDYN = in_array('121', $aAddons) ? "1" : "0";
                    $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
                    $NoEmpCoverLL = in_array('122', $aAddons) ? "1" : "0";
                    $Geographical = in_array('118', $aAddons) ? 1 : "0";
                    $contractDetails = [
                        [
                            "contract" => "PA Compulsary Contract",
                            "coverage" => [
                                "coverage" => "PA Owner Driver Coverage",
                                "subCoverage" => [
                                    "subCoverage" => "PA Owner Driver",
                                    "limit" => "PA Owner Driver Limit",
                                    "sumInsuredperperson" => "1500000"
                                ]
                            ]
                        ],
                        [
                            "contract" => "Third Party Multiyear Contract",
                            "coverage" => [
                                "coverage" => "Legal Liability to Third Party Coverage",
                                "deductible" => "TP Deductible",
                                //"discount"=> "Third Party Property Damage Discount",
                                "subCoverage" => [
                                    [
                                        "subCoverage" => "Third Party Basic Sub Coverage",
                                        "limit" => "Third Party Property Damage Limit",
                                        "thirdPartyPropertyDamageLimit" => "750000"
                                    ],
                                    // [
                                    //     "subCoverage"=> "CNG LPG Kit Liability"
                                    // ],
                                    // [
                                    //     "subCoverage"=> "Legal Liability to Employees",
                                    //     "numberofEmployees"=> "1"
                                    // ],
                                    [
                                        "subCoverage" => "Legal Liability to Paid Drivers",
                                        "numberofPaidDrivers" => "1"
                                    ],
                                    [
                                        "subCoverage" => "PA Unnamed Passenger",
                                        "limit" => "PA Unnamed Passenger Limit",
                                        "sumInsuredperperson" => "10000"
                                    ]
                                    // [
                                    //     "subCoverage"=> "PA to Paid Driver Cleaner Conductor",
                                    //     "limit"=> "PA to Paid Driver Cleaner Conductor Limit",
                                    //     "sumInsuredperperson"=> "10000",
                                    //     "numberofPaidDrivers"=> "1"
                                    // ]
                                ]
                            ]
                        ]
                    ];


                }
                if ($nPlanType == '3' && (GetCache($cachepolicyExpiry) == "Expired")) {
                    $sPolicyType = getconstant("MOTOR.ZUNO.POLICYTYPE.LIABILITY");
                    $PolicyFromDate = $today->format('Y-m-d');
                    $PolicyToDate = $today->addYear()->subDay()->format('Y-m-d');
                    $LLtoPaidDriverYN = in_array('116', $aAddons) ? "1" : "0";
                    $LimitedTPPDYN = in_array('121', $aAddons) ? "1" : "0";
                    $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
                    $NoEmpCoverLL = in_array('122', $aAddons) ? "1" : "0";
                    $Geographical = in_array('118', $aAddons) ? 1 : "0";

                    $contractDetails = [
                        [
                            "contract" => "PA Compulsary Contract",
                            "coverage" => [
                                "coverage" => "PA Owner Driver Coverage",
                                "subCoverage" => [
                                    "subCoverage" => "PA Owner Driver",
                                    "limit" => "PA Owner Driver Limit",
                                    "sumInsuredperperson" => "1500000"
                                ]
                            ]
                        ],
                        [
                            "contract" => "Third Party Multiyear Contract",
                            "coverage" => [
                                "coverage" => "Legal Liability to Third Party Coverage",
                                "deductible" => "TP Deductible",
                                //"discount"=> "Third Party Property Damage Discount",
                                "subCoverage" => [
                                    [
                                        "subCoverage" => "Third Party Basic Sub Coverage",
                                        "limit" => "Third Party Property Damage Limit",
                                        "thirdPartyPropertyDamageLimit" => "750000"
                                    ],
                                    // [
                                    //     "subCoverage"=> "CNG LPG Kit Liability"
                                    // ],
                                    // [
                                    //     "subCoverage"=> "Legal Liability to Employees",
                                    //     "numberofEmployees"=> "1"
                                    // ],
                                    [
                                        "subCoverage" => "Legal Liability to Paid Drivers",
                                        "numberofPaidDrivers" => "1"
                                    ],
                                    [
                                        "subCoverage" => "PA Unnamed Passenger",
                                        "limit" => "PA Unnamed Passenger Limit",
                                        "sumInsuredperperson" => "10000"
                                    ]
                                    // [
                                    //     "subCoverage"=> "PA to Paid Driver Cleaner Conductor",
                                    //     "limit"=> "PA to Paid Driver Cleaner Conductor Limit",
                                    //     "sumInsuredperperson"=> "10000",
                                    //     "numberofPaidDrivers"=> "1"
                                    // ]
                                ]
                            ]
                        ]
                    ];
                }
            }
            $today = now();
            $sRtocity = getRtocityApi($request, $sRegNumber);
            if ($sRtocity) {
                $sRtocity = $sRtocity->RTOCITY ?? $sRtocity->RTONAME;
            } else {
                $sRtocity = "";
            }


            $ncbPercnt = ["0", "20", "25", "35", "45", "50"];
            $index = array_search($claimper, $ncbPercnt);
            if ($index !== false && isset($ncbPercnt[$index + 1])) {
                $ncbValue = $ncbPercnt[$index + 1];
            } else {
                $ncbValue = $claimper; // or default value
            }
            //    return [
            //         'claim' => $claim,
            //         'claimper' => $claimper,
            //         'transferOfowner' => $transferOfowner,
            //         'ncbvalue' => $ncbValue
            //     ];
            //return $sPolicyType;
            //$cachepolicyExpiry = 'cache_policyExpiry_' . $userId;
            // return [
            //     'PAforUnnamedPassenger' => $PAforUnnamedPassenger,
            //     'PAforUnnamedamount' => $PAforUnnamedamount
            // ];
            //$cacheplantype = 'cache_plantype' . $userId;
            // $plantype=(Getcache($cacheplantype) == 3)?1:0;
            $cachecaridv = 'cache_' . $userId . '_caridv';
            $idv = GetCache($cachecaridv);
            $curl = curl_init();
            $postJson = json_encode([
                "commissionContractId" => "1000342686",
                "channelCode" => "002",
                "branch" => "",
                "make" => $make,
                "model" => $model,
                "variant" => $variant,
                "licencedCarryingCapacity" => "5",
                "idvcity" => $idvcity ?? "DELHI NCR",
                "rtoStateCode" => $sRtodetails->POST_CODE ?? "",
                "rtoLocationName" => $sRtodetails->LOCATION_CD ?? "",
                "clusterZone" => $sRtodetails->CLUSTER_VL ?? "Cluster 5",
                "carZone" => $sRtodetails->PVT_CAR ?? "A",
                "rtoZone" => $sRtodetails->POST_CODE ?? "",
                "rtoCityOrDistrict" => $sRtodetails->DISTRICT_CD ?? "Delhi North West=> As",
                "idv" => $idvAmount ?? "1060000",
                "registrationDate" => $firstregdate ?? "2023-08-22",
                "previousInsurancePolicy" => ($sProposalType == "New") ? "0" : "1",
                "previousPolicyExpiryDate" => $PrevexptoDate ?? "",
                "typeOfBusiness" => $sProposalType ?? "",
                "renewalStatus" => "New Policy",
                "policyType" => $sPolicyType ?? "",
                "policyStartDate" => $PolicyFromDate ?? "",
                "policyTenure" => $ntenure ?? "",
                "caTenure" => $caTenure ?? "1",
                "claimDeclaration" => [],
                "previousNcb" => (GetCache($cachemotortype) == "newcar") ? "" : $claimper,
                "annualMileage" => "10000",
                "fuelType" => "HYBRID",
                "transmissionType" => "",
                "dateOfTransaction" => "",
                "subPolicyType" => [],
                "validLicenceNo" => "Y",
                "transferOfNcb" => (GetCache($cachemotortype) == "newcar") ? "" : "No",
                "transferOfNcbPercentage" => (GetCache($cachemotortype) == "newcar") ? "" : $ncbValue,
                "proofProvidedForNcb" => (GetCache($cachemotortype) == "newcar") ? "" : "NCB Reserving Letter",
                "protectionofNcbValue" => (GetCache($cachemotortype) == "newcar") ? "" : $ncbValue,
                "breakinInsurance" => "No Break",
                "contractTenure" => (GetCache($cachemotortype) == "newcar") ? "3.0" : "1.0",
                "fibreGlassFuelTank" => "N",
                "antiTheftDeviceInstalled" => "Y",
                "automobileAssociationMember" => "N",
                "bodystyleDescription" => "",
                "dateOfFirstPurchaseOrRegistration" => $firstregdate ?? "2023-08-22",
                "dateOfBirth" => "",
                "policyHolderGender" => "",
                "policyholderOccupation" => "Medium to High",
                "typeOfGrid" => "Grid 1",
                "payAsYouDrive" => "Yes",
                "currentOdometerReading" => (GetCache($cachemotortype) == "newcar") ? "0" : "5000",
                "avgYearUsage" => "5000",
                "insuredNoOfKms" => "Upto 5000",
                "TPInsurerName" => "",
                "TPPolicyNumber" => "",
                "TPPolicyStartDate" => $sPretpfDate ?? "",
                "TPPolicyEndDate" => $sPretptoDate ?? "",
                "contractDetails" => $contractDetails
            ]);
            //return json_decode($postJson);
            //dd($postJson);
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://devapi.hizuno.com/motor/quote',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $postJson,
                CURLOPT_HTTPHEADER => array(
                    'Authorization:' . $stoken,
                    'x-api-key:' . self::$xapikey,
                    'Content-Type:application/json'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            // \Log::info(['Car_Quotation_shriram' => $response]);
            return $response;
        } catch (\Exception $e) {
            \Log::info($e->getMessage() . "errorcode:zuno_service_generatePrivateCarQuote");
            return ['status' => '0', 'message' => $e->getMessage() . 'An error occurred while fetching cache data.'];
        }
    }
    // public static function FileIntoBase64($filePath)
    // {
    //     $base64 = '';
    //     $image = '';
    //     $parts = explode("/", $filePath);
    //     $extension = explode(".", end($parts));
    //     if (file_exists($filePath)) {
    //         $image = file_get_contents($filePath);
    //         $base64 = base64_encode($image);
    //     } else {
    //         throw new \Exception("File not found.");
    //     }
    //     return ['extension' => '.' . end($extension), 'based64' => $base64];
    // }

    public static function genRandomNumber()
    {
        $rand = rand(11111, 99999);
        return $rand;
    }

    public static function privateCarProposal(Request $request, $today, $nextyear, $aData)
    {
        // SaveFile("jhjhjh");
        // dd("erre");
        try {
            $userId = $request->userid;
            $user = User::find($userId);
            $utilityService = new ZunoUtilityService();
            $newtoken = $utilityService->generateToken();
            $token = $newtoken['access_token'];
            $aData = DataModel::where('userid', $userId)->first();
            $oJourneyData = MotorJourney::where('userid', $userId)->where('is_car', '1')->first();
            $prevPolicydata = json_decode($oJourneyData->pre_policy_details, true);
            $aVehicledetails = json_decode($oJourneyData['vehicle_details'], true);
            //return $aVehicledetails['Enginenumber'];
            $LimitedTPPDYN = null;
            $LLtoPaidDriverYN = null;
            $AntiTheftYN = null;
            $NoEmpCoverLL = null;
            $VoluntaryExcess = null;
            $Geographical = null;
            $PAforUnnamedPassenger = null;
            $VehicleType = null;
            $firstregdate = null;
            $NilDepreciationCover = null;
            $RSACover = null;
            $KeyReplacement = null;
            $LossOfPersonBelong = null;
            $Eng_Protector = null;
            $InvReturn = null;
            $Consumables = null;
            $HypothData = json_decode($oJourneyData->bank_details, true);
            $aCardata = json_decode($aData->knowcar_reg_details, true);
            $aNewCardata = json_decode($aData->newcar_reg_details, true);
            $aAccessories = json_decode($aData->accessories, true);
            $cachepolicyExpiry = 'cache_policyExpiry_' . $userId;
            $cacheallowable = 'cache_' . $userId . 'allowablediscount';
            //return GetCache($cacheallowable);
            $aResult = [];
            if (!empty($aAccessories)) {
                foreach ($aAccessories as $item) {
                    $aResult[$item['type']] = $item['amount'];

                }
            }
            $pAcoverReason = "";
            $PAcover = "";
            $PAforUnnamedamount = json_decode($aData->caraddonvalue, true);
            $PAforUnnamedamount = !empty($PAforUnnamedamount) ? $PAforUnnamedamount : "0";
            $nTpolicyNo = null;
            $sTpInsurer = null;
            $prevPolicyNo = null;
            $sRegNumber = $aData->carnumber;
            $sRegNumber = "";
            $sRegNo2 = "";
            $sRegNo3 = "";
            $sRegNo1 = "";
            $sRegNo4 = "";
            $cachemotortype = 'cache_motortype_' . $userId;
            $cacheunder = 'cache_under_' . $userId;
            $sRegdate = null;
            $sRegNumber = "";
            $sRegNo2 = "";
            $sRegNo3 = "";
            $sRegNo1 = "";
            $sRegNo4 = "";
            $sManuYear = "";
            $firstregdate = "";
            $today = now();
            if (GetCache($cachemotortype) == 'newcar') {
                $sRegNumber = "NEW";
                $RegNumber = substr(explode('(', $aData->rtocode)[1], 0, -1);
                $sRegNo1 = substr(str_replace('-', '', $RegNumber), 0, 2);//k
                $sRegNo2 = substr(str_replace('-', '', $RegNumber), 2, 2);//k
                $firstregdate = $today->format('Y-m-d');
            } else {
                $sRegNumber = $aData->carnumber;
                $sRegNo1 = substr($sRegNumber, 0, 2);//k
                $sRegNo2 = substr($sRegNumber, 2, 2);//k
                $sRegNo3 = substr($sRegNumber, 4, 2);//k
                $sRegNo4 = substr($sRegNumber, 6, 4);//k
                $sRegdate = $aCardata['carregdate'] ?? date('Y-m-d');

                if (!empty($aCardata['carregdate'])) {
                    $formats = ['Y-m-d', 'd-m-Y', 'd/m/Y', 'Y/m/d'];
                    foreach ($formats as $format) {
                        $date = \DateTime::createFromFormat($format, $aCardata['carregdate']);
                        if ($date) {
                            $firstregdate = $date->format('Y-m-d');
                            break;
                        }
                    }
                }
            }
            $sRtocode = $sRegNo1 . "-" . $sRegNo2;
            //return $sRtocode;
            $cacheshowroompr = 'cache_exshworoomprice' . $userId;
            $sRtodetails = Zuno_RTO_Master::where('LOCATION_CD', $sRtocode)->first();
            $nExshowroompr = GetCache($cacheshowroompr);
            $sPrevexptoDate = null;
            $sPrevexpfDate = null;
            $sPretpfDate = null;
            $sPretptoDate = null;
            $claim = null;
            $claimper = null;
            $transferOfowner = null;
            $gstno = null;
            $oModel = null;


            if (GetCache($cachemotortype) == 'knowcar') {
                if ($aCardata['prepolitype'] == 'odonly') {
                    $sPrePolicyType = getconstant("MOTOR.ZUNO.POLICYTYPE.OWNDAMAGE");
                    $sPrevexptoDate = Carbon::parse($aCardata['odtodate'])->format('Y-m-d'); //k
                    $sPrevexpfDate = Carbon::parse($aCardata['odfromdate'])->format('Y-m-d'); //k
                    $sPretpfDate = Carbon::parse($aCardata['odtpfromdate'])->format('Y-m-d');
                    $sPretptoDate = Carbon::parse($aCardata['odtptodate'])->format('Y-m-d');
                    $nTpolicyNo = array_key_exists('tppolicynumber', $prevPolicydata) ? $prevPolicydata['tppolicynumber'] : '';
                    $sTpInsurer = array_key_exists('tpprevInsurance', $prevPolicydata) ? $prevPolicydata['tpprevInsurance'] : '';
                    $tpInsurCmpny = Zuno_Prev_Insurer::where('id', $sTpInsurer)->first()->Company_Name;
                    $date = Carbon::createFromFormat('d-m-Y', $aCardata['odtodate']);
                    $PolicyFromDate = $date->addDay()->format('Y-m-d');
                    $PolicyToDate = $date->addYear()->subDay()->format('Y-m-d');

                } else if ($aCardata['prepolitype'] == 'bundled') {

                    $sPrePolicyType = getconstant("MOTOR.ZUNO.POLICYTYPE.BUNDLED");
                    $sPrevexptoDate = Carbon::parse($aCardata['bdtodate'])->format('Y-m-d'); //k
                    $sPrevexpfDate = Carbon::parse($aCardata['bdfromdate'])->format('Y-m-d'); //k
                    $sPretpfDate = Carbon::parse($aCardata['bdtpfromdate'])->format('Y-m-d');
                    $sPretptoDate = Carbon::parse($aCardata['bdtptodate'])->format('Y-m-d');
                    $nTpolicyNo = array_key_exists('tppolicynumber', $prevPolicydata) ? $prevPolicydata['tppolicynumber'] : '';
                    $sTpInsurer = array_key_exists('tpprevInsurance', $prevPolicydata) ? $prevPolicydata['tpprevInsurance'] : '';
                    $tpInsurCmpny = Zuno_Prev_Insurer::where('id', $sTpInsurer)->first()->Company_Name;
                    $date = Carbon::createFromFormat('d-m-Y', $aCardata['bdtodate']);
                    $PolicyFromDate = $date->addDay()->format('Y-m-d');
                    $PolicyToDate = $date->addYear()->subDay()->format('Y-m-d');

                } elseif ($aCardata['prepolitype'] == 'comprehensive') {
                    $sPrePolicyType = getconstant("MOTOR.ZUNO.POLICYTYPE.PACKAGE");
                    $sPrevexptoDate = Carbon::parse($aCardata['comptodate'])->format('Y-m-d'); //k
                    $sPrevexpfDate = Carbon::parse($aCardata['compfromdate'])->format('Y-m-d');
                    $date = Carbon::createFromFormat('d-m-Y', $aCardata['comptodate']);
                    $PolicyFromDate = $date->addDay()->format('Y-m-d');
                    $PolicyToDate = $date->addYear()->subDay()->format('Y-m-d');

                } elseif ($aCardata['prepolitype'] == 'tponly') {
                    $sPrePolicyType = getconstant("MOTOR.ZUNO.POLICYTYPE.LIABILITY");
                    $sPrevexptoDate = Carbon::parse($aCardata['tptodate'])->format('Y-m-d'); //k
                    $sPrevexpfDate = Carbon::parse($aCardata['tpfromdate'])->format('Y-m-d'); //k
                    $date = Carbon::createFromFormat('d-m-Y', $aCardata['tptodate']);
                    $PolicyFromDate = $date->addDay()->format('Y-m-d');
                    $PolicyToDate = $date->addYear()->subDay()->format('Y-m-d');
                }

            }

            // ];
            // if (GetCache($cachemotortype) == 'newcar') {
            //     // $oModel = Shriram_Vehicle_Master::where('id', $aNewCardata['model'])->first();

            // } else {
            //     // $oModel = Shriram_Vehicle_Master::where('id', $aCardata['model'])->first();
            // }
            $sVehicleCode = "";
            // if ($oModel) {
            //     $sVehicleCode = $oModel->VEHICLE_CODE;
            // }

            $aDocument = UserMotorDescription::where('userid', $userId)->first();
            $aIdDetails = $aDocument->idnumber ? json_decode($aDocument->idnumber, true) : [];
            $aNominee = $oJourneyData->nominee_details ? json_decode($oJourneyData->nominee_details) : [];
            $dNomineeDob = $dNomineeDob = Carbon::parse($aNominee->nomineedob)->format('Y-m-d');
            $dNomineeage = Carbon::parse($dNomineeDob)->age;
            $permanentAddress = json_decode($oJourneyData->permanent_address, true) ?? [];
            $pincode = $permanentAddress['pincode'];
            // $state = Zuno_Pincode::where('Postcode', $pincode)->first();

            // $sState = $state->STATE;

            $nPlanType = $aData->car_plan_type;
            $dRegDate = $aData->knowcar_reg_details ? json_decode($aData->knowcar_reg_details, true)['carregdate'] : date('Y-m-d');
            $regDate = \DateTime::createFromFormat('Y-m-d', $dRegDate);
            $validregDate = \DateTime::createFromFormat('d-m-Y', $dRegDate);
            $addonAgeLimit = [
                "101" => 5,
                "103" => 5,
                "104" => 5,
                "107" => 5,
                "108" => 5,
                "109" => 5,
                "110" => 5,
                "111" => 5,
                "106" => 1
            ];
            //$aAddons = json_decode($aData->caraddon, true) ?? [];
            $aCarAddon = is_string($aData->caraddon)
                ? json_decode($aData->caraddon, true)
                : (array) $aData->caraddon;

            // return[
            //       "adsdf"=>$aCarAddon
            // ];


            $aAddons = !empty($aCarAddon['tpselectedaddon'])
                ? $aCarAddon['tpselectedaddon']
                : (
                    !empty($aCarAddon['selectedaddon'])
                    ? $aCarAddon['selectedaddon']
                    : (
                        !empty($aCarAddon['odselectedaddon'])
                        ? $aCarAddon['odselectedaddon']
                        : []
                    )
                );

            $validAddons = [];
            foreach ($aAddons as $addonId) {
                if (isset($addonAgeLimit[$addonId])) {
                    $maxyears = $addonAgeLimit[$addonId];
                    if (ValidateAddonAge($validregDate, $maxyears)) {
                        $validAddons[] = $addonId;
                    }
                } else {
                    $validAddons[] = $addonId;
                }
            }
            $aAddons = $validAddons;
            //return $aAddons;
            $cachezunocaridv = 'zuno' . $userId . '_caridv';
            $nIdv = GetCache($cachezunocaridv);
            $randomValue = self::genRandomNumber();
            $EngineNo = $aVehicledetails['Enginenumber'];//"GDFG59D4GD6546D" . $randomValue;//
            $ChassisNo = $aVehicledetails['Chassisnumber'];//"GDF45GDFGD4G56D" . $randomValue;
            $sProdCode = getconstant("MOTOR.ZUNO.PRODUCTTYPE.PRIVATECAR");
            $sPolicyType = "";
            $sProposalType = "";
            $mainApplicantField = "";
            $policyTenure = "";
            $gstno = null;
            $today = now();
            $contractDetails = [];
            $typeOfBusiness = "";
            if (GetCache($cachemotortype) == "newcar") {
                $VehicleType = "N";
                $policyTenure = 3;
                $typeOfBusiness = "New";
                $sProposalType = getconstant("MOTOR.ZUNO.PROPOSALTYPE.FRESHPROPOSAL");
                $sManuYear = $aNewCardata ? $aNewCardata['brandyear'] : "";
                $companydetails = json_decode($oJourneyData->company_details, true);
                $NilDepreciationCover = in_array('101', $aAddons) ? "Zero Depreciation" : "";
                $RSACover = in_array('102', $aAddons) ? "Basic Road Assistance" : "";
                $DailyExpRem = in_array('109', $aAddons) ? "Y" : "N";
                $KeyReplacement = in_array('111', $aAddons) ? "Key Replacement" : "";
                $LossOfPersonBelong = in_array('107', $aAddons) ? "Loss of Personal Belongings" : "";
                $EmergencyTranHotelExpRemYN = in_array('108', $aAddons) ? "Y" : "N";
                $MultiCarBenefitYN = in_array('110', $aAddons) ? "Y" : "N";
                $Eng_Protector = in_array('104', $aAddons) ? "Engine Protect" : "";
                $Consumables = in_array('103', $aAddons) ? "Consumable Cover" : "";
                $InvReturn = in_array('106', $aAddons) ? "Return To Invoice" : "";
                $AntiTheftYN = in_array('119', $aAddons) ? "1" : "0";
                $LLtoPaidDriverYN = in_array('116', $aAddons) ? "1" : "0";
                $NoEmpCoverLL = in_array('122', $aAddons) ? "1" : "0";
                $SHRIMOTORPROTECTION_YN = in_array('113', $aAddons) ? "Y" : "N";
                $LimitedTPPDYN = in_array('121', $aAddons) ? "1" : "0";
                $VoluntaryExcess = in_array('120', $aAddons) ? "1" : "0";
                $PAPaidDriverConductorCleaner = 1;
                $Geographical = in_array('117', $aAddons) ? 1 : "0";
                $Geographical = in_array('118', $aAddons) ? 1 : "0";
                $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";

                $contractDetails = [
                    [
                        "contract" => "Own Damage Contract",
                        "coverage" => [
                            "coverage" => "Own Damage Coverage",
                            "deductible" => "Own Damage Basis Deductible",
                            "voluntaryCoPay" => "Rs. 2500",
                            "discount" => [
                                "Voluntary Deductible Discount",
                                "AntiTheft Discount",
                                "No Claim Bonus Discount",
                                // "Handicapped Discount",
                                //"Vintage Car Discount",
                                "Pay As You Drive Discount"
                            ],
                            "subCoverage" => [
                                [
                                    "subCoverage" => "Own Damage Basic",
                                    "limit" => "Own Damage Basic Limit",
                                    "idvValue" => $nIdv ?? "",
                                ]
                                // [
                                //     "subCoverage"=> "Non Electrical Accessories",
                                //     "limit"=> "Non Electrical Accessories Limit",
                                //     "valueOfAccessory"=> "10000.0",
                                //     "accessoryDescription"=> "Guard"
                                // ],
                                // [
                                //     "subCoverage"=> "Electrical Electronic Accessories",
                                //     "limit"=> "Electrical Electronic Accessories Limit",
                                //     "valueOfAccessory"=> "10000.0",
                                //     "accessoryDescription"=> "Headlights"
                                // ]
                            ]
                        ]
                    ],
                    [
                        "contract" => "Addon Contract",
                        "coverage" => [
                            "coverage" => "Add On Coverage",
                            // "deductible"=> "Preferred Garage Deductible",
                            // "preferredGarageDeductibleAmount"=> "Rs. 10000",
                            //"underwriterDiscount"=> "0.0",
                            "subCoverage" => [
                                [
                                    "subCoverage" => $LossOfPersonBelong ?? ""
                                ],
                                [
                                    "subCoverage" => $RSACover ?? ""
                                ],
                                [
                                    "subCoverage" => "Tyre Safeguard"
                                ],
                                [
                                    "subCoverage" => $Consumables ?? ""
                                ],
                                [
                                    "subCoverage" => $Eng_Protector ?? ""
                                ],
                                [
                                    "subCoverage" => $NilDepreciationCover ?? ""
                                ],
                                [
                                    "subCoverage" => $KeyReplacement ?? ""
                                ],
                                [
                                    "subCoverage" => $InvReturn ?? ""
                                ]
                            ]
                        ]
                    ],
                    [
                        "contract" => "PA Compulsary Contract",
                        "coverage" => [
                            "coverage" => "PA Owner Driver Coverage",
                            "subCoverage" => [
                                "subCoverage" => "PA Owner Driver",
                                "limit" => "PA Owner Driver Limit",
                                "sumInsuredPerPerson" => "1500000"
                            ]
                        ]
                    ],
                    [
                        "contract" => "Third Party Multiyear Contract",
                        "coverage" => [
                            "coverage" => "Legal Liability to Third Party Coverage",
                            "deductible" => "TP Deductible",
                            "discount" => "",
                            "subCoverage" => [
                                [
                                    "subCoverage" => "Third Party Basic Sub Coverage",
                                    "limit" => "Third Party Property Damage Limit",
                                    "thirdPartyPropertyDamageLimit" => "750000"
                                ],
                                // [
                                //     "subCoverage"=> "Legal Liability to Employees",
                                //     "numberOfEmployees"=> "1"
                                // ],
                                [
                                    "subCoverage" => "Legal Liability to Paid Drivers",
                                    "numberOfPaidDrivers" => "1"
                                ],
                                [
                                    "subCoverage" => "PA Unnamed Passenger",
                                    "limit" => "PA Unnamed Passenger Limit",
                                    "sumInsuredPerPerson" => "10000"
                                ]
                                // [
                                //     "subCoverage"=> "PA to Paid Driver Cleaner Conductor",
                                //     "limit"=> "PA to Paid Driver Cleaner Conductor Limit",
                                //     "sumInsuredPerPerson"=> "10000",
                                //     "numberOfPaidDrivers"=> "1"
                                // ]
                            ]
                        ]
                    ]
                ];

                if ($aNewCardata['under'] == "company") {
                    $mainApplicantField = "2";
                    $PAcover = 0;
                    $pAcoverReason = "PA_TYPE2";
                } else {
                    $PAcover = $aData->pacover;
                    $mainApplicantField = "1";
                    if ($PAcover == 0) {
                        $pAcoverReason = json_decode($aData->pacover_reason, true);
                        // $ncoverReason = array_keys($pAcoverReason)[0];
                        $ncoverReason = is_array($pAcoverReason) ? (array_keys($pAcoverReason)[0] ?? []) : [];
                        if ($ncoverReason == '1') {
                            $pAcoverReason = "PA_TYPE1";
                        }
                        if ($ncoverReason == '2') {
                            $pAcoverReason = "PA_TYPE2";
                        }
                        if ($ncoverReason == '3') {
                            $pAcoverReason = "PA_TYPE4";
                        } else {
                            $pAcoverReason = "";
                        }
                    }
                }
                if ($nPlanType == '2') {
                    $sPolicyType = getconstant("MOTOR.ZUNO.POLICYTYPE.BUNDLED");
                    $sRegdate = $today->format('Y-m-d');
                    $PolicyFromDate = $today->format('Y-m-d');
                    $PolicyToDate = $today->addYears(3)->subDay()->format('Y-m-d');
                    $Geographical = in_array('118', $aAddons) ? 1 : "0";
                    $Geographical = in_array('117', $aAddons) ? 1 : "0";
                    $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
                }
            }
            if (GetCache($cachemotortype) == "knowcar") {
                $VehicleType = "U";
                $typeOfBusiness = "Rollover";
                $policyTenure = 1;
                $sManuYear = $aData->knowcar_reg_details ? json_decode($aData->knowcar_reg_details, true)['brandyear'] : "";
                $prevPolicyNo = $prevPolicydata['policynumber'];
                $prevInsurCmpny = Zuno_Prev_Insurer::where('id', $prevPolicydata['prevInsuranceId'])->first()->Company_Name;
                //return $prevInsurCmpny;
                if ($aCardata['under'] == "company") {
                    $mainApplicantField = "2";
                    $PAcover = 0;
                    $pAcoverReason = "PA_TYPE2";
                    $companydetails = json_decode($oJourneyData->company_details, true);
                    //dd($companydetails);
                    $gstno = $companydetails['gstnumber'];
                } else {
                    $PAcover = $aData->pacover;
                    $mainApplicantField = "1";
                    $gstno = null;
                    if ($PAcover == 0) {
                        $pAcoverReason = json_decode($aData->pacover_reason, true);
                        // $ncoverReason = array_keys($pAcoverReason)[0];
                        $ncoverReason = is_array($pAcoverReason) ? (array_keys($pAcoverReason)[0] ?? []) : [];
                        if ($ncoverReason == '1') {
                            $pAcoverReason = "PA_TYPE1";
                        }
                        if ($ncoverReason == '2') {
                            $pAcoverReason = "PA_TYPE2";
                        }
                        if ($ncoverReason == '3') {
                            $pAcoverReason = "PA_TYPE4";
                        } else {
                            $pAcoverReason = "";
                        }
                    }
                }
                $claim = array_key_exists('policytoggle', $aCardata) ? '1' : '0';
                $claimper = array_key_exists('bonus-button', $aCardata) ? $aCardata['bonus-button'] : '0';
                $transferOfowner = array_key_exists('ownershiptoggle', $aCardata) ? $aCardata['ownershiptoggle'] : '0';
                $sProposalType = getconstant("MOTOR.ZUNO.PROPOSALTYPE.MARKETRENEWAL");
                //dd($aCardata);
                if ($nPlanType == '1') {
                    $sPolicyType = getconstant("MOTOR.ZUNO.POLICYTYPE.OWNDAMAGE");
                    $Geographical = in_array('117', $aAddons) ? 1 : "0";
                    $NilDepreciationCover = in_array('101', $aAddons) ? "Zero Depreciation" : "";
                    $RSACover = in_array('102', $aAddons) ? "Basic Road Assistance" : "";
                    $DailyExpRem = in_array('109', $aAddons) ? "Y" : "N";
                    $KeyReplacement = in_array('111', $aAddons) ? "Key Replacement" : "";
                    $LossOfPersonBelong = in_array('107', $aAddons) ? "Loss of Personal Belongings" : "";
                    $EmergencyTranHotelExpRemYN = in_array('108', $aAddons) ? "Y" : "N";
                    $MultiCarBenefitYN = in_array('110', $aAddons) ? "Y" : "N";
                    $Eng_Protector = in_array('104', $aAddons) ? "Engine Protect" : "";
                    $Consumables = in_array('103', $aAddons) ? "Consumable Cover" : "";
                    $InvReturn = in_array('106', $aAddons) ? "Return To Invoice" : "";
                    $AntiTheftYN = in_array('119', $aAddons) ? "1" : "0";

                    $contractDetails = [
                        [
                            "contract" => "Own Damage Contract",
                            "coverage" => [
                                "coverage" => "Own Damage Coverage",
                                "deductible" => "Own Damage Basis Deductible",
                                "voluntaryCoPay" => "Rs. 2500",
                                "discount" => [
                                    "Voluntary Deductible Discount",
                                    "AntiTheft Discount",
                                    //"No Claim Bonus Discount",
                                    //"Handicapped Discount",
                                    //"Vintage Car Discount",
                                    //"Pay As You Drive Discount",
                                    // "Preferred Garage Discount",
                                    // "Auto Mobile Association Discount"
                                ],
                                "subCoverage" => [
                                    [
                                        "subCoverage" => "Own Damage Basic",
                                        "limit" => "Own Damage Basic Limit",
                                        "idvValue" => $nIdv
                                    ]
                                    // [
                                    //     "subCoverage"=> "Non Electrical Accessories",
                                    //     "limit"=> "Non Electrical Accessories Limit",
                                    //     "valueOfAccessory"=> "10000.0",
                                    //     "accessoryDescription"=> "Guard"
                                    // ],
                                    // [
                                    //     "subCoverage"=> "Electrical Electronic Accessories",
                                    //     "limit"=> "Electrical Electronic Accessories Limit",
                                    //     "valueOfAccessory"=> "10000.0",
                                    //     "accessoryDescription"=> "Headlights"
                                    // ],
                                    // [
                                    //     "subCoverage"=> "CNG LPG Kit Own Damage",
                                    //     "limit"=> "CNG LPG Kit Own Damage Limit",
                                    //     "valueOfKit"=> "10000.0",
                                    //     "accessoryDescription"=> "CNG"
                                    // ]
                                ]
                            ]
                        ],
                        [
                            "contract" => "Addon Contract",
                            "coverage" => [
                                "coverage" => "Add On Coverage",
                                //"deductible"=> "Preferred Garage Deductible",
                                //"preferredGarageDeductibleAmount"=> "Rs. 10000",
                                //"underwriterDiscount"=> "0.0",
                                "subCoverage" => [
                                    [
                                        "subCoverage" => $InvReturn ?? ""
                                    ],
                                    [
                                        "subCoverage" => $KeyReplacement ?? ""
                                    ],
                                    // [
                                    //     "subCoverage" => "Protection of NCB"
                                    // ],
                                    [
                                        "subCoverage" => $NilDepreciationCover ?? ""
                                    ],
                                    [
                                        "subCoverage" => $Eng_Protector ?? ""
                                    ],
                                    [
                                        "subCoverage" => $Consumables ?? ""
                                    ],
                                    // [
                                    //     "subCoverage" => "Waiver of Policy"
                                    // ],
                                    [
                                        "subCoverage" => $RSACover ?? ""
                                    ],
                                    [
                                        "subCoverage" => $LossOfPersonBelong ?? ""
                                    ]
                                    // [
                                    //     "subCoverage" => "Pay As You Drive"
                                    // ]
                                    // [
                                    //     "subCoverage"=> "Preferred Garage Discount"
                                    // ]
                                ]
                            ]
                        ]
                    ];

                }
                if ($nPlanType == '2') {
                    $sPolicyType = getconstant("MOTOR.ZUNO.POLICYTYPE.PACKAGE");
                    $NilDepreciationCover = in_array('101', $aAddons) ? "Zero Depreciation" : "";
                    $RSACover = in_array('102', $aAddons) ? "Basic Road Assistance" : "";
                    $DailyExpRem = in_array('109', $aAddons) ? "Y" : "N";
                    $KeyReplacement = in_array('111', $aAddons) ? "Key Replacement" : "";
                    $LossOfPersonBelong = in_array('107', $aAddons) ? "Loss of Personal Belongings" : "";
                    $EmergencyTranHotelExpRemYN = in_array('108', $aAddons) ? "Y" : "N";
                    $MultiCarBenefitYN = in_array('110', $aAddons) ? "Y" : "N";
                    $Eng_Protector = in_array('104', $aAddons) ? "Engine Protect" : "";
                    $Consumables = in_array('103', $aAddons) ? "Consumable Cover" : "";
                    $InvReturn = in_array('106', $aAddons) ? "Return To Invoice" : "";
                    $AntiTheftYN = in_array('119', $aAddons) ? "1" : "0";
                    $LLtoPaidDriverYN = in_array('116', $aAddons) ? "1" : "0";
                    $NoEmpCoverLL = in_array('122', $aAddons) ? "1" : "0";
                    $SHRIMOTORPROTECTION_YN = in_array('113', $aAddons) ? "Y" : "N";
                    $LimitedTPPDYN = in_array('121', $aAddons) ? "1" : "0";
                    $VoluntaryExcess = in_array('120', $aAddons) ? "1" : "0";
                    $PAPaidDriverConductorCleaner = 1;
                    $Geographical = in_array('117', $aAddons) ? 1 : "0";
                    $Geographical = in_array('118', $aAddons) ? 1 : "0";
                    $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
                    $Geographical = in_array('117', $aAddons) ? 1 : "0";
                    $Geographical = in_array('118', $aAddons) ? 1 : "0";
                    $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
                    $contractDetails = [
                        [
                            "contract" => "Own Damage Contract",
                            "coverage" => [
                                "coverage" => "Own Damage Coverage",
                                "voluntaryCoPay" => "Rs. 2500",
                                "deductible" => "Own Damage Basis Deductible",

                                "discount" => [
                                    "Voluntary Deductible Discount",
                                    "AntiTheft Discount",
                                    "No Claim Bonus Discount",
                                    //"Handicapped Discount",
                                    // "Vintage Car Discount",
                                    "Pay As You Drive Discount"
                                ],
                                "subCoverage" => [
                                    [
                                        "subCoverage" => "Own Damage Basic",
                                        "limit" => "Own Damage Basic Limit",
                                        "idvValue" => $nIdv ?? "",
                                    ]
                                    // [
                                    //     "subCoverage"=> "Non Electrical Accessories",
                                    //     "limit"=> "Non Electrical Accessories Limit",
                                    //     "valueOfAccessory"=> "10000.0",
                                    //     "accessoryDescription"=> "Guard"
                                    // ],
                                    // [
                                    //     "subCoverage"=> "Electrical Electronic Accessories",
                                    //     "limit"=> "Electrical Electronic Accessories Limit",
                                    //     "valueOfAccessory"=> "10000.0",
                                    //     "accessoryDescription"=> "Headlights"
                                    // ]
                                ]
                            ]
                        ],
                        [
                            "contract" => "Addon Contract",
                            "coverage" => [
                                "coverage" => "Add On Coverage",
                                // "deductible"=> "Preferred Garage Deductible",
                                // "preferredGarageDeductibleAmount"=> "Rs. 10000",
                                //"underwriterDiscount"=> "0.0",
                                "subCoverage" => [
                                    [
                                        "subCoverage" => $LossOfPersonBelong ?? ""
                                    ],
                                    [
                                        "subCoverage" => $RSACover ?? ""
                                    ],
                                    [
                                        "subCoverage" => "Tyre Safeguard"
                                    ],
                                    [
                                        "subCoverage" => $Consumables ?? ""
                                    ],
                                    [
                                        "subCoverage" => $Eng_Protector ?? ""
                                    ],
                                    [
                                        "subCoverage" => $NilDepreciationCover ?? ""
                                    ],
                                    [
                                        "subCoverage" => $KeyReplacement ?? ""
                                    ],
                                    [
                                        "subCoverage" => $InvReturn ?? ""
                                    ]
                                ]
                            ]
                        ],
                        [
                            "contract" => "PA Compulsary Contract",
                            "coverage" => [
                                "coverage" => "PA Owner Driver Coverage",
                                "subCoverage" => [
                                    "subCoverage" => "PA Owner Driver",
                                    "limit" => "PA Owner Driver Limit",
                                    "sumInsuredPerPerson" => "1500000"
                                ]
                            ]
                        ],
                        [
                            "contract" => "Third Party Multiyear Contract",
                            "coverage" => [
                                "coverage" => "Legal Liability to Third Party Coverage",
                                "deductible" => "TP Deductible",
                                "discount" => "",
                                "subCoverage" => [
                                    [
                                        "subCoverage" => "Third Party Basic Sub Coverage",
                                        "limit" => "Third Party Property Damage Limit",
                                        "thirdPartyPropertyDamageLimit" => "750000"
                                    ],
                                    // [
                                    //     "subCoverage"=> "Legal Liability to Employees",
                                    //     "numberOfEmployees"=> "1"
                                    // ],
                                    [
                                        "subCoverage" => "Legal Liability to Paid Drivers",
                                        "numberOfPaidDrivers" => "1"
                                    ],
                                    [
                                        "subCoverage" => "PA Unnamed Passenger",
                                        "limit" => "PA Unnamed Passenger Limit",
                                        "sumInsuredPerPerson" => "10000"
                                    ]
                                    // [
                                    //     "subCoverage"=> "PA to Paid Driver Cleaner Conductor",
                                    //     "limit"=> "PA to Paid Driver Cleaner Conductor Limit",
                                    //     "sumInsuredPerPerson"=> "10000",
                                    //     "numberOfPaidDrivers"=> "1"
                                    // ]
                                ]
                            ]
                        ]
                    ];
                }
                if ($nPlanType == '3' && (GetCache($cachepolicyExpiry) == "Not Expired")) {
                    $sPolicyType = getconstant("MOTOR.ZUNO.POLICYTYPE.LIABILITY");
                    $Geographical = in_array('118', $aAddons) ? 1 : "0";
                    $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
                    $contractDetails =
                        [
                            [
                                "contract" => "PA Compulsary Contract",
                                "coverage" => [
                                    "coverage" => "PA Owner Driver Coverage",
                                    "subCoverage" => [
                                        "subCoverage" => "PA Owner Driver",
                                        "limit" => "PA Owner Driver Limit",
                                        "sumInsuredPerPerson" => "1500000"
                                    ]
                                ]
                            ],
                            [
                                "contract" => "Third Party Multiyear Contract",
                                "coverage" => [
                                    "coverage" => "Legal Liability to Third Party Coverage",
                                    "deductible" => "TP Deductible",
                                    //"discount"=> "Third Party Property Damage Discount",
                                    "subCoverage" => [
                                        [
                                            "subCoverage" => "Third Party Basic Sub Coverage",
                                            "limit" => "Third Party Property Damage Limit",
                                            "thirdPartyPropertyDamageLimit" => "750000"
                                        ],
                                        // [
                                        //     "subCoverage"=> "Legal Liability to Employees",
                                        //     "numberOfEmployees"=> "1"
                                        // ],
                                        [
                                            "subCoverage" => "Legal Liability to Paid Drivers",
                                            "numberOfPaidDrivers" => "1"
                                        ],
                                        [
                                            "subCoverage" => "PA Unnamed Passenger",
                                            "limit" => "PA Unnamed Passenger Limit",
                                            "sumInsuredPerPerson" => "10000"
                                        ]
                                        // [
                                        //     "subCoverage"=> "CNG LPG Kit Liability"
                                        // ],
                                        // [
                                        //     "subCoverage"=> "PA to Paid Driver Cleaner Conductor",
                                        //     "limit"=> "PA to Paid Driver Cleaner Conductor Limit",
                                        //     "sumInsuredPerPerson"=> "10000",
                                        //     "numberOfPaidDrivers"=> "1"
                                        // ]
                                    ]
                                ]
                            ]
                        ];
                }
            }
            $regDate = Carbon::parse($sRegdate)->format('Y-m-d');
            $vehicleAge = $sRegdate ? Carbon::parse($sRegdate)->diffInYears(Carbon::now()) : "0";
            $sRtocity = getRtocityApi($request, $sRegNumber);
            if ($sRtocity) {
                $sRtocity = !empty($sRtocity->RTOCITY) ? $sRtocity->RTOCITY : $sRtocity->RTONAME;
            } else {
                $sRtocity = "";
            }

            $dob = !empty($oJourneyData->dob)
                ? $oJourneyData->dob
                : (!empty($user->dob)
                    ? $user->dob
                    : "29-04-2000");

            $cacheproductcode = 'cache_productcode_' . $userId;
            SetCache($cacheproductcode, $sProdCode);
            $cacheproposaltype = 'cache_proposaltype_' . $userId;
            SetCache($cacheproposaltype, $sProposalType);
            $cachepolicytpe = 'cache_policytpe_' . $userId;
            SetCache($cachepolicytpe, $sPolicyType);

            $ncbPercnt = ["0", "20", "25", "35", "45", "50"];
            $index = array_search($claimper, $ncbPercnt);
            if ($index !== false && isset($ncbPercnt[$index + 1])) {
                $ncbValue = $ncbPercnt[$index + 1];
            } else {
                $ncbValue = $claimper; // or default value
            }

            $curl = curl_init();

            $postdata = [
                "commissionContractId" => "1000342686",
                "branch" => "",
                "agentEmail" => "",
                "saleManagerCode" => "",
                "saleManagerName" => "",
                "mainApplicantField" => $mainApplicantField ?? "",
                "typeOfBusiness" => $typeOfBusiness,
                "policyType" => $sPolicyType ?? "Bundled Insurance",
                "policyStartDate" => $PolicyFromDate ?? "",
                "policyStartTime" => "120100",
                "policyEndDay" => $PolicyToDate ?? "",
                "policyEndTime" => "235900",
                "previousInsurancePolicy" => (GetCache($cachemotortype) == "newcar") ? "0" : "1",
                "previousInsuranceCompanyName" => (GetCache($cachemotortype) == "newcar") ? "" : "ICICI Lombard General Insurance Co. Ltd",
                "previousInsuranceCompanyAddress" => (GetCache($cachemotortype) == "newcar") ? "" : "Mumbai",
                "dateOfFirstPurchaseOrRegistration" => $firstregdate ?? "",
                "previousPolicyStartDate" => $sPrevexpfDate ?? "",
                "previousPolicyEndDate" => $sPrevexptoDate ?? "",
                "previousPolicyNo" => (GetCache($cachemotortype) == "newcar") ? "" : "34245243DFS",
                "natureOfLoss" => "NA",
                "policyTenure" => $policyTenure ?? "3",
                "make" => "MARUTI",
                "model" => "CIAZ",
                "variant" => "SMART HYBRID ALPHA",
                "licencedCarryingCapacity" => "5",
                "idvCity" => $sRtodetails->IDVCITY_CD ?? "", //"JAIPUR",
                "cubicCapacity" => "1462",
                "licencedSeatingCapacity" => "5",
                "validLicenceNo" => "Y",
                "fuelType" => "HYBRID",
                "newOrUsed" => ($VehicleType == "U") ? "U" : "New",
                "yearOfManufacture" => $sManuYear ?? "",
                "registrationDate" => $firstregdate ?? "",
                "vehicleAge" => $vehicleAge ?? "0",
                "engineeNumber" => $EngineNo ?? "GDFG59D4GD6546D99134",
                "chassisNumber" => $ChassisNo ?? "GDF45GDFGD4G56D99134",
                "fibreGlassFuelTank" => "N",
                "bodystyleDescription" => "HATCHBACK",
                "bodyType" => "",
                "transmissionType" => "Gear",
                "validDrivingLicense" => "Y",
                "handicapped" => "N",
                "certifiedVintageCar" => "N",
                "automobileAssociationMember" => "N",
                "antiTheftDeviceInstalled" => "Y",
                "typeOfDeviceInstalled" => "Burglary Alarm",
                "automobileAssociationMembershipNumber" => "",
                "automobileAssociationMembershipExpiryDate" => "",
                "stateCode" => $sRtodetails->POST_CODE ?? "", //"14",
                "districtCode" => $sRegNo2 ?? "06", //"01",
                "vehicleSeriesNumber" => $sRegNo3 ?? "",
                "registrationNumber" => $sRegNo4 ?? "",
                "vehicleRegistrationNumber" => $sRegNumber ?? "",
                "rtoState" => $sRegNo1 ?? "", //"14",
                "rtoLocationName" => $sRtodetails->LOCATION_CD ?? "", //"RJ-14",
                "rtoCityOrDistrict" => $sRtodetails->DISTRICT_CD ?? "",
                "clusterZone" => $sRtodetails->CLUSTER_VL ?? "Cluster 3",
                "carZone" => $sRtodetails->PVT_CAR ?? "", //"B",
                "rtoZone" => $sRtodetails->POST_CODE ?? "",//"14",
                "protectionofNcbValue" => (GetCache($cachemotortype) == "newcar") ? "" : $claimper,
                "transferOfNcb" => (GetCache($cachemotortype) == "newcar") ? "N" : "No",
                "transferOfNcbPercentage" => (GetCache($cachemotortype) == "newcar") ? "" : $claimper,
                "proofDocumentDate" => (GetCache($cachemotortype) == "newcar") ? "" : "2021-12-16",
                "proofProvidedForNcb" => (GetCache($cachemotortype) == "newcar") ? "" : "NCB Declaration",
                "applicableNcb" => (GetCache($cachemotortype) == "newcar") ? "" : $ncbValue,
                "exshowroomPrice" => $nExshowroompr ?? "",
                "originalIdvValue" => $nIdv ?? "",
                "requiredDiscountOrLoadingPercentage" => GetCache($cacheallowable) ? GetCache($cacheallowable) : "0",//"-65",
                "financeType" => [],
                "financierName" => [],
                "branchNameAndAddress" => [],
                "salutation" => $oJourneyData->gender ?? "",
                "firstName" => $oJourneyData->name ?? "",
                "lastName" => "asdf",
                "gender" => ($oJourneyData->gender == "Mr") ? "Male" : "Female",
                "policyHolderGender" => ($oJourneyData->gender == "Mr") ? "Male" : "Female",
                "maritalStatus" => "",
                // "dateOfBirth" => Carbon::parse($dob)->format('Y-m-d') ?? "1996-06-13",
                "dateOfBirth" => $dob ?? "1996-06-13",
                "currentAddressLine1" => $permanentAddress['address1'] ?? "asdf asdf",
                "currentAddressLine2" => $permanentAddress['address2'] ?? "asddf asdf",
                "currentCountry" => "IN",
                "pincode" => $pincode ?? "",
                "currentCity" => $permanentAddress['city'] ?? "",
                "mobileNumber" => "7905927283",
                "emailId" => "test@sddf.com",
                "occupation" => "Salaried",
                "nomineeName" => $aNominee->nomineename ?? "",
                "relationshipWithApplicant" => $aNominee->nomineerelation ?? "",
                "isNomineeMinor" => ($dNomineeage < 18) ? "Y" : "N",
                "nomineeAge" => $dNomineeage ?? "25",
                "nomineeDob" => $dNomineeDob ?? "1996-06-13",
                "overrideAllowableDiscount" => GetCache($cacheallowable) ? "Y" : "N",
                "renewalstatus" => "New Policy",
                "annualmileageofthecar" => "10000",
                "breakininsurance" => "No Break",
                "typeofGrid" => "Grid 1",
                "staffCode" => "",
                "payHowYouDrive" => "No",
                "payAsYouDrive" => "Yes",
                "currentOdometerReading" => (GetCache($cachemotortype) == "newcar") ? "0" : "5000",
                "avgYearUsage" => "5000",
                "insuredNoOfKms" => "Upto 5000",
                "driverDetails" => [
                    "nameofDriver" => "asdf",
                    "dateofBirth" => "1997-12-22",
                    "genderoftheDriver" => "Male",
                    "ageofDriver" => "22.0",
                    "relationshipwithProposer" => "Brother",
                    "drivingExperienceinyears" => "1",
                    "middleName" => "",
                    "lastName" => "asdf"
                ],
                "contractDetails" => $contractDetails
            ];

            if ($nPlanType == 1) {
                $postdata = array_merge($postdata, [
                    "TPPolicyNumber" => $nTpolicyNo ?? "",
                    "TPPolicyStartDate" => $sPretpfDate ?? "",
                    "TPPolicyEndDate" => $sPretptoDate ?? "",
                ]);
            }
            $postData = json_encode($postdata);
            // SaveFile($postData, 'zunorequest.txt');

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://devapi.hizuno.com/motor/fullQuote',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $postData,
                CURLOPT_HTTPHEADER => array(
                    'Authorization:' . $token,
                    'x-api-key:' . self::$xapikey,
                    'Content-Type:application/json'
                ),
            ));

            $response = curl_exec($curl);
            //SaveFile($response, "zunoresponse.txt");
            //curl_close($curl);
            return $response ?? null;
            // Logfunction($userId, "zuno", $response, $postdata, "car");
            //\Log::info(['privateCarProposal_zuno' => $response]);


        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ]);
        }

    }

    // public static function Payment()
    // {
    //     return view('front.motor.car.vendor.shriram.payment');
    // }
    // public static function PaymentStatus($nProposalNo, $nQuoteId)
    // {
    //     //$url = 'https://novaapi.shriramgi.com/NovaWS/novaServices/WebAggregator.svc/RestService/getPaymentStatus';
    //     $url = 'http://novaapiuat.shriramgi.com/UATNovaWS/novaServices/WebAggregator.svc/RestService/getPaymentStatus';

    //     $curl = curl_init();
    //     $postdata = json_encode([
    //         "ProposalNo" => $nProposalNo,
    //         "QuoteID" => $nQuoteId
    //     ]);
    //     //dd($postdata);
    //     curl_setopt_array($curl, array(
    //         CURLOPT_URL => $url,
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => '',
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 0,
    //         CURLOPT_FOLLOWLOCATION => true,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => 'POST',
    //         CURLOPT_POSTFIELDS => $postdata,
    //         CURLOPT_HTTPHEADER => array(
    //             'Content-Type: application/json',
    //             'Cookie: ASP.NET_SessionId=mlurt03cufpeh4tciehzhaun'
    //         ),
    //     ));
    //     $response = curl_exec($curl);
    //     //dd($response);
    //     curl_close($curl);
    //     echo $response;
    // }

    public static function FindIdv($VehicleDetails)
    {
        // $userId = $request->userid;
        $user = $VehicleDetails['user'];
        //return $user;
        $utilityService = new ZunoUtilityService();
        $anewtoken = $utilityService->generateToken();
        $stoken = $anewtoken['access_token'];

        // $make = 'MARUTI';
        // $model = 'CIAZ';
        // $variant = 'SMART HYBRID ALPHA';
        // $idvcity = 'JAIPUR';

        $query = http_build_query([
            'make' => $VehicleDetails['make'],
            'model' => $VehicleDetails['model'],
            'variant' => $VehicleDetails['variant'],
            'idvcity' => $VehicleDetails['idvcity'],
        ]);

        $url = "https://devapi.hizuno.com/car/idv?$query";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization:' . $stoken,
                'x-api-key:' . self::$Masterxapikey
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // \Log::info(['Car_Quotation_shriram' => $response]);
        return $response;

    }
}
