<?php
namespace App\Services\Api\godigit;

use App\Models\Godigit\{Godigit_Vehicle_Master, Godigit_pincode, Godigit_Prev_Ins};
use App\Models\Shriram\{Shriram_Pincode};
use App\Models\{Master_Vehicle_Data as DataModel, User, MotorJourney, Vehicle_Info, MasterMotor};
use App\Services\Api\godigit\GoDigitUtilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use stdClass;

class GoDigitCarService
{
    private static $Username;
    private static $Password;
    private static $allAddon = ['101', '102', '109', '111', '107', '108', '110', '104', '103', '106', '119', '116', '122', '121', '120'];

    public static function initlize()
    {
        self::$Username = '';
        self::$Password = '';
    }

    public static function genRandomNumber()
    {
        $rand = rand(111111, 9999999);
        return $rand;
    }

    public static function AddonHandler($addons = [])
    {
        if (empty($addons) || !is_array($addons)) {
            $addons = [];
        }
        $cleanAddons = array_map('strval', $addons);
        // $hasZeroDep = in_array('101', $cleanAddons); // Depreciation Cover
        // $hasRSA = in_array('102', $cleanAddons); // RSA
        // $hasConsumables = in_array('103', $cleanAddons); // Consumables
        // $hasEngine = in_array('104', $cleanAddons); // Engine Protect
        // $hasTyre = in_array('105', $cleanAddons); // Tyre Protect
        // $hasRTI = in_array('106', $cleanAddons); // Return to Invoice
        // $hasPersonal = in_array('107', $cleanAddons); // Personal Belonging
        // $hasKeyLoss = in_array('111', $cleanAddons); // Key Loss (KEYREPLACE)
        // $needPro = ($hasRSA || $hasPersonal || $hasKeyLoss || $hasZeroDep);
        // $needConsumables = ($hasConsumables || $hasEngine || $hasTyre || $hasRTI);
        // $needZeroDep = ($hasZeroDep || $needConsumables);
        // if ($needConsumables) {
        //     $needPro = true;
        // }
        return [
            // 'ZERODEP' => $needZeroDep,
            // 'RSA' => $needPro,
            // 'PERSONALBELONG' => $needPro,
            // 'KEYREPLACE' => $needPro,
            // 'CONSUMABLES' => $needConsumables,
            // 'ENG_PROTECTOR' => $hasEngine,
            // 'TYRE_PROTECTION' => $hasTyre,
            // 'INVRETURNYN' => $hasRTI,
            'ZERODEP' => in_array('101', $cleanAddons),
            'RSA' => in_array('102', $cleanAddons),
            'PERSONALBELONG' => in_array('107', $cleanAddons),
            'KEYREPLACE' => in_array('111', $cleanAddons),
            'CONSUMABLES' => in_array('103', $cleanAddons),
            'ENG_PROTECTOR' => in_array('104', $cleanAddons),
            'TYRE_PROTECTION' => in_array('105', $cleanAddons),
            'INVRETURNYN' => in_array('106', $cleanAddons),
            'DAILYEXP' => in_array('109', $cleanAddons),
            'EMERGENCYTRANHOTELEXPREM' => in_array('108', $cleanAddons),
            'MULTICARBENEFITYN' => in_array('110', $cleanAddons),
            'PAPAIDDRIVERCONDUCTORCLEANER' => in_array('114', $cleanAddons),
            'PAFORUNNAMEDPASSENGER' => in_array('115', $cleanAddons),
            'LLTOPAIDDRIVERYN' => in_array('116', $cleanAddons),
            'GEOGRAPHICAL_OD' => in_array('117', $cleanAddons),
            'GEOGRAPHICAL_TP' => in_array('118', $cleanAddons),
            'ANTITHEFTYN' => in_array('119', $cleanAddons),
            'VOLUNTARYEXCESS' => in_array('120', $cleanAddons),
            'RIMPROTECTION' => in_array('124', $cleanAddons),
            'TOWINGCOVER' => in_array('126', $cleanAddons),
            'PREFERREDGARAGE' => in_array('132', $cleanAddons),
            'PAYASYOUDRIVE' => in_array('133', $cleanAddons),
            'NOEMPCOVERLL' => false,
            'SHRIMOTORPROTECTION_YN' => false,
            'LIMITEDTPPDYN' => false,
        ];
    }

    public static function generateCarQuote(Request $request, $nPlanType)
    {
        // return ['satus' => false];
        try {
            $userId = $request->userid;
            $user = User::find($userId);
            $sNewToken = GoDigitUtilityService::TokenGenerate();
            $sToken = json_decode($sNewToken, true);
            $sToken = isset($sToken) && isset($sToken['access_token']) ? $sToken['access_token'] : '';
            // dd($sToken);
            $aData = DataModel::where('userid', $userId)->first();
            $aCarAddon = is_string($aData->caraddon)
                ? json_decode($aData->caraddon, true)
                : (array) $aData->caraddon;
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
            $aAddons = self::AddonHandler($aAddons);
            $today = now();
            self::initlize();
            $oModel = null;
            $claim = null;
            $claimper = null;
            $transferOfowner = null;
            $firstregdate = null;
            $PolicyFromDate = null;
            $PolicyToDate = null;
            $sPrevexptoDate = null;
            $sPrevexpfDate = null;
            $sPretpfDate = null;
            $sPretptoDate = null;
            $NilDepreciationCoverYN = $aAddons['ZERODEP'];
            $RSACover = $aAddons['RSA'];
            $DailyExpRemYN = $aAddons['DAILYEXP'];
            $KeyReplacementYN = $aAddons['KEYREPLACE'];
            $LossOfPersonBelongYN = $aAddons['PERSONALBELONG'];
            $EmergencyTranHotelExpRemYN = $aAddons['EMERGENCYTRANHOTELEXPREM'] ?? false;
            $MultiCarBenefitYN = $aAddons['MULTICARBENEFITYN'];
            $Eng_Protector = $aAddons['ENG_PROTECTOR'];
            $Consumables = $aAddons['CONSUMABLES'];
            $InvReturnYN = $aAddons['INVRETURNYN'];
            $rimProtection = $aAddons['RIMPROTECTION'];
            $tyreProtection = $aAddons['TYRE_PROTECTION'];
            $LLtoPaidDriverYN = $aAddons['LLTOPAIDDRIVERYN'];
            $NoEmpCoverLL = $aAddons['NOEMPCOVERLL'];
            $SHRIMOTORPROTECTION_YN = false;
            $LimitedTPPDYN = $aAddons['LIMITEDTPPDYN'];
            $VoluntaryExcess = $aAddons['VOLUNTARYEXCESS'];
            $AntiTheftYN = $aAddons['ANTITHEFTYN'];
            $Geographical = false;
            $PAforUnnamedPassenger = $aAddons['PAFORUNNAMEDPASSENGER'];
            $PAPaidDriverConductorCleaner = $aAddons['PAPAIDDRIVERCONDUCTORCLEANER'];
            $towingCover = $aAddons['TOWINGCOVER'];
            $preferredGarage = $aAddons['PREFERREDGARAGE'];
            $payAsYouDrive = $aAddons['PAYASYOUDRIVE'];
            $cachepolicyExpiry = 'cache_policyExpiry_' . $userId;
            // $nTpolicyNo = null;
            $PAcover = false;
            $pAcoverReason = false;
            $VehicleType = false;
            $policyHolderType = '';
            $under = '';
            $previousInsurerCode = false;
            $AuthUser = $user->toArray();
            $subInsuranceProductCode = '';
            $sinsuranceProductCode = '';
            $isClaimInLastYear = '';
            $previousNoClaimBonus = '';
            $oJourneyData = MotorJourney::where('userid', $userId)->where('is_car', '1')->first();
            $prevoiusPolicyDetails = isset($oJourneyData->pre_policy_details) ? json_decode($oJourneyData->pre_policy_details, true) : null;
            $rValue = self::genRandomNumber();
            $enquiryId = $rValue;
            $cacheEnquiryId = 'cache_enquiryid_' . $userId;
            SetCache($cacheEnquiryId, $enquiryId);
            $aData = DataModel::where('userid', $userId)->first();
            $dRegDate = null;
            $modelSearch = null;
            $sPrePolicyType = null;
            $results = null;
            $claimperString = null;
            $results = [];
            $cachecaridv = 'cache_' . $userId . '_caridv';
            $plantype = $aData->car_plan_type;
            $idv = $plantype == '3' ? 0 : GetCache($cachecaridv);
            $cachemotortype = 'cache_motortype_' . $userId;
            $vahanresponse = GetCache($userId . '_vahandata');
            $vahanresponse = json_decode($vahanresponse, true);
            $vahanregdate = $vahanresponse['registration_date'] ?? null;
            $monthsfromtoday = Carbon::parse($vahanregdate)
                ->diffInMonths(Carbon::parse(today()->format('Y-m-d')));
            $claim = match (true) {
                $monthsfromtoday <= 12 * 1 => 'ZERO',
                $monthsfromtoday <= 12 * 2 && $monthsfromtoday >= 12 * 1 => 'TWENTY',
                $monthsfromtoday <= 12 * 3 && $monthsfromtoday >= 12 * 2 => 'TWENTY',
                $monthsfromtoday <= 12 * 4 && $monthsfromtoday >= 12 * 3 => 'TWENTY_FIVE',
                $monthsfromtoday <= 12 * 5 && $monthsfromtoday >= 12 * 4 => 'THIRTY_FIVE',
                $monthsfromtoday <= 12 * 6 && $monthsfromtoday >= 12 * 5 => 'FORTY_FIVE',
                $monthsfromtoday >= 12 * 6 => 'FIFTY',
                default => 'ZERO',
            };

            if (GetCache($cachemotortype) == 'knowcar') {
                $aCardata = json_decode($aData->knowcar_reg_details, true) ?? [];
                $modelSearch = $aCardata['model'] ?? '';
                $dRegDate = $aData->knowcar_reg_details ? json_decode($aData->knowcar_reg_details, true)['carregdate'] : date('d-m-Y');
            }
            if (GetCache($cachemotortype) == 'newcar') {
                $aCardata = json_decode($aData->newcar_reg_details, true) ?? [];
                $modelSearch = $aCardata['model'] ?? '';
                $dRegDate = date('d-m-Y');
            }

            // dd($modelSearch);
            $regDate = \DateTime::createFromFormat('Y-m-d', $dRegDate);
            $vid = getconstant('MOTOR.GODIGIT.KEY');
            $vdata = getVcode($modelSearch, $vid, 'MOT-PRD-001', 'App\Models\Master\Godigit');
            $oModel = $vdata['status'] && isset($vdata['data']->vcode) ? $vdata['data']->vcode : $vdata['data'];
            // dd(GetCache($cachemotortype),$dRegDate,$modelSearch);
            // dd($modelSearch,$oModel,$vdata,"uuuu");
            if (!$vdata['status']) {
                return response()->json($oModel);
            }
            $addonAgeLimit = [
                '101' => 7,
                '103' => 10,
                '104' => 10,
                '107' => 5,
                '108' => 7,
                '109' => 5,
                '110' => 5,
                '111' => 10,
                '106' => 1
            ];

            $validAddons = [];
            $previousPolicyTypeCodeCode = null;
            if (GetCache($cachemotortype) == 'knowcar') {
                if ($aCardata['prepolitype'] == 'odonly') {
                    $sPrePolicyType = getconstant('MOTOR.GODIGIT.CAR.POLICYTYPE.OWNDAMAGE');
                    $previousPolicyTypeCodeCode = '1OD_0TP';
                    $sPrevexptoDate = \DateTime::createFromFormat('d-m-Y', $aCardata['odtodate'])->format('Y-m-d');  // k
                    $sPrevexpfDate = \DateTime::createFromFormat('d-m-Y', $aCardata['odfromdate'])->format('Y-m-d');  // k
                    $sPretpfDate = \DateTime::createFromFormat('d-m-Y', $aCardata['odtpfromdate'])->format('Y-m-d');
                    $sPretptoDate = \DateTime::createFromFormat('d-m-Y', $aCardata['odtptodate'])->format('Y-m-d');
                    // $nTpolicyNo = array_key_exists('tppolicynumber', $prevPolicydata) ? $prevPolicydata['tppolicynumber'] : '';
                    $date = Carbon::createFromFormat('d-m-Y', $aCardata['odtodate']);
                    $PolicyFromDate = $date->addDay()->format('Y-m-d');
                    $PolicyToDate = $date->addYear()->subDay()->format('Y-m-d');
                } else if ($aCardata['prepolitype'] == 'bundled') {
                    $sPrePolicyType = getconstant('MOTOR.GODIGIT.CAR.POLICYTYPE.BUNDLED');
                    $previousPolicyTypeCodeCode = '1OD_3TP';
                    $sPrevexpfDate = \DateTime::createFromFormat('d-m-Y', $aCardata['bdfromdate'])->format('Y-m-d');  // k
                    $sPrevexptoDate = \DateTime::createFromFormat('d-m-Y', $aCardata['bdtodate'])->format('Y-m-d');  // k
                    $sPretpfDate = \DateTime::createFromFormat('d-m-Y', $aCardata['bdtpfromdate'])->format('Y-m-d');
                    $sPretptoDate = \DateTime::createFromFormat('d-m-Y', $aCardata['bdtptodate'])->format('Y-m-d');
                    // $nTpolicyNo = array_key_exists('tppolicynumber', $prevPolicydata) ? $prevPolicydata['tppolicynumber'] : '';
                    $date = Carbon::createFromFormat('d-m-Y', $aCardata['bdtodate']);
                    $PolicyFromDate = $date->addDay()->format('Y-m-d');
                    $PolicyToDate = $date->addYear()->subDay()->format('Y-m-d');
                } elseif ($aCardata['prepolitype'] == 'comprehensive') {
                    $sPrePolicyType = getconstant('MOTOR.GODIGIT.CAR.POLICYTYPE.PACKAGE');
                    $previousPolicyTypeCodeCode = '1OD_1TP';
                    $sPrevexptoDate = \DateTime::createFromFormat('d-m-Y', $aCardata['comptodate'])->format('Y-m-d');  // k
                    $sPrevexpfDate = \DateTime::createFromFormat('d-m-Y', $aCardata['compfromdate'])->format('Y-m-d');
                    $date = Carbon::createFromFormat('d-m-Y', $aCardata['comptodate']);
                    $PolicyFromDate = $date->addDay()->format('Y-m-d');
                    $PolicyToDate = $date->addYear()->subDay()->format('Y-m-d');
                    // dd($PolicyFromDate,$PolicyToDate);
                } elseif ($aCardata['prepolitype'] == 'tponly') {
                    $sPrePolicyType = getconstant('MOTOR.GODIGIT.CAR.POLICYTYPE.LIABILITY');
                    $previousPolicyTypeCodeCode = '0OD_1TP';
                    $sPrevexptoDate = \DateTime::createFromFormat('d-m-Y', $aCardata['tptodate'])->format('Y-m-d');  // k
                    $sPrevexpfDate = \DateTime::createFromFormat('d-m-Y', $aCardata['tpfromdate'])->format('Y-m-d');  // k
                    $date = Carbon::createFromFormat('d-m-Y', $aCardata['tptodate']);
                    $PolicyFromDate = $date->addDay()->format('Y-m-d');
                    $PolicyToDate = $date->addYear()->subDay()->format('Y-m-d');
                    // dd($PolicyFromDate,$PolicyToDate);
                }

                // return $PolicyFromDate;
            }
            // $sPrevexptoDate = \DateTime::createFromFormat('d-m-Y', $aCardata['bdfromdate'])->format('Y-m-d'); //k
            // $sPrevexpfDate = \DateTime::createFromFormat('d-m-Y', $aCardata['bdtodate'])->format('Y-m-d'); //k
            // dd($sPrevexptoDate,$sPrevexpfDate,$aCardata['prepolitype']);
            // dd($sPrePolicyType, $aCardata['prepolitype']);
            $sRegNumber = '';
            $sRegNo2 = '';
            $sRegNo3 = '';
            $sRegNo1 = '';
            $sRegNo4 = '';
            if (GetCache($cachemotortype) == 'newcar') {
                $RegNumber = substr(explode('(', $aData->rtocode)[1], 0, -1);
                $sRegNo1 = substr(str_replace('-', '', $RegNumber), 0, 2);  // k
                $sRegNo2 = substr(str_replace('-', '', $RegNumber), 2, 2);  // k
                $sRegNo = substr(str_replace('-', '', $RegNumber), 0, 4);
                $sRegNumber = $sRegNo;
            } else {
                $sRegNumber = $aData->carnumber;
                $sRegNo1 = substr($sRegNumber, 0, 2);  // k
                $sRegNo2 = substr($sRegNumber, 2, 2);  // k
                $sRegNo3 = substr($sRegNumber, 4, 2);  // k
                $sRegNo4 = substr($sRegNumber, 6, 4);  // k
            }
            $permanentAddress = isset($oJourneyData->permanent_address) && json_decode($oJourneyData->permanent_address, true) ? json_decode($oJourneyData->permanent_address, true) : [];
            $pincode = $user->pincode;
            $state = Shriram_Pincode::where('PC_CODE', $pincode)->first();

            if (!$state) {
                return ['status' => '0', 'message' => 'Pincode not available for this service.'];
            }
            $sState = $state->STATE;
            if (GetCache($cachemotortype) == 'knowcar') {
                $aCardata = json_decode($aData->knowcar_reg_details, true) ?? [];
                if ($aCardata['under'] == 'company') {
                    $PAcover = '0';
                    $pAcoverReason = 'PA_TYPE2';
                    $policyHolderType = 'company';
                } else {
                    $policyHolderType = 'INDIVIDUAL';
                    $PAcover = $aData->pacover;
                    if ($PAcover == 0) {
                        $pAcoverReason = json_decode($aData->pacover_reason, true);
                        // $ncoverReason = array_keys($pAcoverReason)[0];
                        $ncoverReason = is_array($pAcoverReason) ? (array_keys($pAcoverReason)[0] ?? []) : [];
                        if ($ncoverReason == '1') {
                            $pAcoverReason = 'PA_TYPE1';
                        }
                        if ($ncoverReason == '2') {
                            $pAcoverReason = 'PA_TYPE2';
                        }
                        if ($ncoverReason == '3') {
                            $pAcoverReason = 'PA_TYPE4';
                        } else {
                            $pAcoverReason = '';
                        }
                    }
                }
                $regdate = array_key_exists('carregdate', $aCardata) ? $aCardata['carregdate'] : '';
                $firstregdate = \DateTime::createFromFormat('d-m-Y', $regdate)->format('Y-m-d');
                $claim = array_key_exists('policytoggle', $aCardata) ? '0' : '1';
                $claimper = array_key_exists('bonus-button', $aCardata) ? $aCardata['bonus-button'] : '0';
                $transferOfowner = array_key_exists('ownershiptoggle', $aCardata) ? $aCardata['ownershiptoggle'] : '0';
                $claimMap = [
                    0 => 'ZERO',
                    20 => 'TWENTY',
                    25 => 'TWENTY_FIVE',
                    35 => 'THIRTY_FIVE',
                    45 => 'FORTY_FIVE',
                    50 => 'FIFTY',
                    55 => 'FIFTY_FIVE',
                    65 => 'SIXTY_FIVE',
                ];

                $claimperString = $claimMap[$claimper] ?? null;

                // return $transferOfowner;
            }
            $previousInsurerCode = $prevoiusPolicyDetails['prevInsuranceId'] ?? null;
            $previousInsurerPolicy = $prevoiusPolicyDetails['policynumber'] ?? null;
            $previousTpInsurerCode = $prevoiusPolicyDetails['tpprevInsurance'] ?? null;
            $previousTpInsurerPolicy = $prevoiusPolicyDetails['tppolicynumber'] ?? null;
            $previousInsurer = [];
            $sVehicleCode = ' ';
            $sPolicyType = '';
            $sProposalType = '';
            $today = now();
            $previousPolicyNumber = '';
            $isPreviousInsurerKnown = null;
            if (GetCache($cachemotortype) == 'newcar') {
                $aCardata = json_decode($aData->newcar_reg_details, true) ?? [];
                if ($aCardata['under'] == 'company') {
                    $policyHolderType = 'company';
                    $PAcover = '0';
                    $pAcoverReason = 'PA_TYPE2';
                } else {
                    $policyHolderType = 'INDIVIDUAL';
                    $PAcover = $aData->pacover;
                    if ($PAcover == 0) {
                        $pAcoverReason = json_decode($aData->pacover_reason, true);
                        $ncoverReason = is_array($pAcoverReason) ? (array_keys($pAcoverReason)[0] ?? []) : [];
                        if ($ncoverReason == '1') {
                            $pAcoverReason = 'PA_TYPE1';
                        }
                        if ($ncoverReason == '2') {
                            $pAcoverReason = 'PA_TYPE2';
                        }
                        if ($ncoverReason == '3') {
                            $pAcoverReason = 'PA_TYPE4';
                        } else {
                            $pAcoverReason = '';
                        }
                    }
                }
                $firstregdate = $today->format('Y-m-d');
            }

            if (GetCache($cachemotortype) == 'newcar') {
                $NewVehicleType = true;
                $previousInsurerCode = $previousInsurerCode ?? null;
                $previousNoClaimBonus = 'ZERO';
                $previousPolicyNumber = '';
                $isClaimInLastYear = false;
                $isPreviousInsurerKnown = true;
                $subInsuranceProductCode = getconstant('MOTOR.GODIGIT.PROPOSALTYPE.CARFRESHPROPOSAL');
                if ($nPlanType == '2') {
                    $PolicyFromDate = $today->format('Y-m-d');
                    // $today = Carbon::today();
                    $PolicyToDate = $today->addYears(3)->subDay()->format('Y-m-d');
                    $sinsuranceProductCode = getconstant('MOTOR.GODIGIT.CAR.POLICYTYPE.BUNDLED');
                }
            }
            // dd($sPrevexptoDate);
            if (GetCache($cachemotortype) == 'knowcar') {
                $NewVehicleType = false;

                // $previousPolicyNumber = "12345877";
                $previousNoClaimBonus = $claim;
                $isClaimInLastYear = false;
                $isPreviousInsurerKnown = true;
                $subInsuranceProductCode = getconstant('MOTOR.GODIGIT.PROPOSALTYPE.MARKETRENEWAL');
                if ($nPlanType == '1') {
                    $sinsuranceProductCode = getconstant('MOTOR.GODIGIT.CAR.POLICYTYPE.OWNDAMAGE');
                    $previousInsurer = [
                        'isPreviousInsurerKnown' => true,
                        'previousInsurerCode' => null,
                        'previousPolicyNumber' => null,
                        'previousPolicyExpiryDate' => $sPrevexptoDate ?? '',
                        'isClaimInLastYear' => false,
                        'originalPreviousPolicyType' => $previousPolicyTypeCodeCode,
                        'previousPolicyType' => $previousPolicyTypeCodeCode,
                        'previousNoClaimBonus' => $claim,  // "ZERO",
                        'previousInsuranceCompany' => $previousInsurerCode ?? null,
                        'currentThirdPartyPolicy' => [
                            'isCurrentThirdPartyPolicyActive' => true,
                            'currentThirdPartyPolicyInsurerCode' => $previousTpInsurerCode ?? '',
                            'currentThirdPartyPolicyNumber' => $previousTpInsurerPolicy ?? '',
                            'currentThirdPartyPolicyStartDateTime' => $sPretpfDate ?? '',
                            'currentThirdPartyPolicyExpiryDateTime' => $sPretptoDate ?? ''
                        ]
                    ];
                }
                if ($nPlanType == '2') {
                    $sinsuranceProductCode = getconstant('MOTOR.GODIGIT.CAR.POLICYTYPE.PACKAGE');
                    $previousInsurer = [
                        'previousInsurerCode' => null,
                        'previousPolicyExpiryDate' => null,
                        'isClaimInLastYear' => false,  // $isClaimInLastYear ?? "",
                        'previousNoClaimBonus' => $claim ?? 'ZERO',
                        'previousPolicyNumber' => null,
                        'isPreviousInsurerKnown' => $isPreviousInsurerKnown ?? null,
                        'originalPreviousPolicyType' => $previousPolicyTypeCodeCode,
                        'previousPolicyType' => $previousPolicyTypeCodeCode,
                        'previousInsuranceCompany' => null,
                        'currentThirdPartyPolicy' => [
                            'isCurrentThirdPartyPolicyActive' => true,
                            'currentThirdPartyPolicyInsurerCode' => $previousTpInsurerCode ?? '',
                            'currentThirdPartyPolicyNumber' => $previousTpInsurerPolicy ?? '',
                            'currentThirdPartyPolicyStartDateTime' => $sPretpfDate ?? '',
                            'currentThirdPartyPolicyExpiryDateTime' => $sPretptoDate ?? ''
                        ]
                    ];

                    $PAPaidDriverConductorCleaner = 1;
                    $Geographical = $aAddons['GEOGRAPHICAL_TP'] ? true : false;
                }
                if ($nPlanType == '3' && (GetCache($cachepolicyExpiry) == 'Not Expired')) {
                    // dd($nPlanType);
                    $previousInsurer = [
                        'isPreviousInsurerKnown' => true,
                        'previousInsurerCode' => null,
                        'previousPolicyNumber' => null,
                        'previousPolicyExpiryDate' => $sPrevexptoDate ?? '',
                        'isClaimInLastYear' => false,
                        'originalPreviousPolicyType' => $previousPolicyTypeCodeCode,
                        'previousPolicyType' => $previousPolicyTypeCodeCode,
                        'previousNoClaimBonus' => $claim ?? 'ZERO',  // "ZERO",
                        'previousInsuranceCompany' => $previousInsurerCode ?? null,
                        'currentThirdPartyPolicy' => [
                            'isCurrentThirdPartyPolicyActive' => true,
                            'currentThirdPartyPolicyInsurerCode' => $previousTpInsurerCode ?? '',
                            'currentThirdPartyPolicyNumber' => $previousTpInsurerPolicy ?? '',
                            'currentThirdPartyPolicyStartDateTime' => $sPretpfDate ?? '',
                            'currentThirdPartyPolicyExpiryDateTime' => $sPretptoDate ?? ''
                        ]
                    ];
                    $sinsuranceProductCode = getconstant('MOTOR.GODIGIT.CAR.POLICYTYPE.LIABILITY');
                    $Geographical = $aAddons['GEOGRAPHICAL_TP'] ? true : false;
                }
                if ($nPlanType == '3' && (GetCache($cachepolicyExpiry) == 'Expired')) {
                    // dd($nPlanType);
                    $sinsuranceProductCode = getconstant('MOTOR.GODIGIT.CAR.POLICYTYPE.LIABILITY');
                    $PolicyFromDate = $today->format('Y-m-d');
                    $PolicyToDate = $today->addYear()->subDay()->format('Y-m-d');
                    $Geographical = $aAddons['GEOGRAPHICAL_TP'] ? true : false;
                }
            }
            $today = now();
            $sRtocity = getRtocityApi($request, $sRegNumber);
            if ($sRtocity) {
                $sRtocity = $sRtocity->RTOCITY ?? $sRtocity->RTONAME;
            } else {
                $sRtocity = '';
            }

            $curl = curl_init();

            $data = json_encode([
                'pincode' => $pincode ?? '',
                'previousInsurer' => $previousInsurer ?? '',
                'preInspection' => [
                    'isPreInspectionOpted' => false
                ],
                'contract' => [
                    'policyHolderType' => $policyHolderType ?? '',
                    'insuranceProductCode' => $sinsuranceProductCode ?? '',
                    'endDate' => $PolicyToDate ?? '',
                    'externalPolicyNumber' => null,
                    'isNCBTransfer' => false,
                    'subInsuranceProductCode' => $subInsuranceProductCode ?? '',
                    'coverages' => [
                        'isIMT23' => false,
                        'personalAccident' => [
                            'selection' => false
                        ],
                        'addons' => [
                            'returnToInvoice' => [
                                'selection' => $InvReturnYN ?? false
                            ],
                            'rimProtection' => [
                                'selection' => $rimProtection ?? false
                            ],
                            'consumables' => [
                                'selection' => $Consumables ?? false
                            ],
                            'partsDepreciation' => [
                                'selection' => $NilDepreciationCoverYN ?? false
                            ],
                            'engineProtection' => [
                                'selection' => $Eng_Protector ?? false
                            ],
                            'tyreProtection' => [
                                'selection' => $rimProtection ?? false
                            ],
                            'roadSideAssistance' => [
                                'selection' => $RSACover ?? false
                            ],
                            'keyAndLockProtect' => [
                                'selection' => $KeyReplacementYN ?? false
                            ],
                            'personalBelonging' => [
                                'selection' => $LossOfPersonBelongYN ?? false
                            ],
                            'preferredGarage' => [
                                'selection' => $preferredGarage ?? false
                            ],
                            'payAsYouDrive' => [
                                'selection' => $payAsYouDrive ?? false
                            ],
                            'towingCover' => [
                                'selection' => $towingCover ?? false
                            ]
                        ],
                        'accessories' => [
                            'electrical' => [
                                'selection' => false
                            ],
                            'nonElectrical' => [
                                'selection' => false
                            ],
                            'cng' => [
                                'selection' => false
                            ]
                        ],
                        'voluntaryDeductible' => null,
                        'isGeoExt' => false,
                        'legalLiability' => [
                            'nonFarePaxLL' => [
                                'selection' => false
                            ],
                            'unnamedPaxLL' => [
                                'selection' => false
                            ],
                            'workersCompensationLL' => [
                                'selection' => false
                            ],
                            'paidDriverLL' => [
                                'selection' => $LLtoPaidDriverYN ?? false
                            ],
                            'employeesLL' => [
                                'selection' => false
                            ],
                            'cleanersLL' => [
                                'selection' => false
                            ]
                        ],
                        'thirdPartyLiability' => [
                            'isTPPD' => $LimitedTPPDYN ?? false
                        ],
                        'unnamedPA' => [
                            'unnamedPaidDriver' => [
                                'selection' => false
                            ],
                            'unnamedPax' => [
                                'selection' => false
                            ],
                            'unnamedConductor' => [
                                'selection' => false
                            ],
                            'unnamedHirer' => [
                                'selection' => false
                            ],
                            'unnamedPillionRider' => [
                                'selection' => false
                            ],
                            'unnamedCleaner' => [
                                'selection' => false
                            ]
                        ],
                        'isOverturningExclusionIMT47' => false,
                        'isTheftAndConversionRiskIMT43' => false,
                        'ownDamage' => [
                            'discount' => [
                                'userSpecialDiscountPercent' => null
                            ]
                        ]
                    ],
                    'startDate' => $PolicyFromDate ?? ''
                ],
                'enquiryId' => $enquiryId ?? '122346789',
                'pospInfo' => [
                    'isPOSP' => false
                ],
                'vehicle' => [
                    'isVehicleNew' => $NewVehicleType ?? null,
                    'vehicleMaincode' => $oModel,
                    'licensePlateNumber' => $sRegNumber ?? '',
                    'vehicleIdentificationNumber' => '',
                    'engineNumber' => '',
                    'manufactureDate' => $firstregdate ?? '',
                    'registrationDate' => $firstregdate ?? '',
                    'vehicleIDV' => [
                        'idv' => $idv ?? 0
                    ]
                ]
            ]);
            // dd($data);
            if (GetCache($cachemotortype) == 'newcar') {
                SaveFile($data, 'godigit_quote_newcar_json_request.txt');
            } else {
                SaveFile($data, 'godigit_quote_knowcar_json_request.txt');
            }
            $url = getconstant('MOTOR.GODIGIT.API.QUOTE');
            $integartionid = getconstant('MOTOR.GODIGIT.INTEGRATIONID.QUOTE');
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_HTTPHEADER => array(
                    'integrationId: ' . $integartionid,  // 22789-0100 // // 27632-0100
                    'Authorization: Bearer ' . $sToken,
                    'Content-Type: application/json'
                ),
            ));
            $response = curl_exec($curl);
            if (GetCache($cachemotortype) == 'newcar') {
                SaveFile($response, 'godigit_quote_newcar_json_response.txt');
            } else {
                SaveFile($response, 'godigit_quote_json_response.txt');
            }

            curl_close($curl);

            return $response;
        } catch (\Exception $e) {
            // SaveFile($data, "godigit_quotation_json_request.txt");
            return json_encode(ErrMessage($e));
        }
    }

    public static function getPackage($aAddons)
    {
        $selectedAddons = $aAddons['selectedaddon'] ?? [];
        $package = null;
        // dd($selectedAddons);
        if (count($selectedAddons) < 1) {
            $package = 'Pro';
        }
        if (in_array('102', $selectedAddons) || in_array('107', $selectedAddons) || in_array('111', $selectedAddons)) {
            $package = 'Pro';
        }
        // if ($package === null) {
        //     return null;
        // }

        if (in_array('101', $selectedAddons)) {
            $package = 'D pro';

            if (in_array('103', $selectedAddons)) {
                $package = 'DC pro';
                $hasEngine = in_array('104', $selectedAddons);
                $hasTyre = in_array('105', $selectedAddons);
                $hasRTI = in_array('106', $selectedAddons);

                if ($hasRTI) {
                    if ($hasEngine && $hasTyre) {
                        $package = 'DCET - RTI Pro';
                    } elseif ($hasTyre) {
                        $package = 'DCT - RTI Pro';
                    } elseif ($hasEngine) {
                        $package = 'DCE - RTI Pro';
                    } else {
                        $package = 'DC- RTI Pro';
                    }
                } else {
                    if ($hasEngine && $hasTyre) {
                        $package = 'DCET pro';
                    } elseif ($hasTyre) {
                        $package = 'DCT pro';
                    } elseif ($hasEngine) {
                        $package = 'DCE pro';
                    }
                }
            }
        }

        return $package;
    }

    public static function generateCarProposal($request, $nextyear)
    {
        try {
            $userId = $request->userid;
            $user = User::find($userId);
            $sNewToken = GoDigitUtilityService::TokenGenerate();
            // dd($sNewToken);
            $sToken = json_decode($sNewToken, true);
            $sToken = $sToken ? $sToken['access_token'] : '';
            // return $sToken;
            // $AuthUser = $user->toArray();
            $aData = DataModel::where('userid', $userId)->first();
            $aCarAddon = is_string($aData->caraddon)
                ? json_decode($aData->caraddon, true)
                : (array) $aData->caraddon;
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
            $aAddons = self::AddonHandler($aAddons);
            $oJourneyData = MotorJourney::where('userid', $userId)->where('is_car', '1')->where('vid', getconstant('MOTOR.GODIGIT.KEY'))->first();
            $prevoiusPolicyDetails = isset($oJourneyData->pre_policy_details) ? json_decode($oJourneyData->pre_policy_details, true) : null;
            // dd($prevoiusPolicyDetails);
            $previousInsurerCode = $prevoiusPolicyDetails['prevInsuranceId'] ?? null;
            $previousInsurerPolicy = $prevoiusPolicyDetails['policynumber'] ?? null;
            $previousTpInsurerCode = $prevoiusPolicyDetails['tpprevInsurance'] ?? null;
            $previousTpInsurerPolicy = $prevoiusPolicyDetails['tppolicynumber'] ?? null;

            $prevPolicydata = json_decode($oJourneyData->pre_policy_details, true);
            $name = $oJourneyData['name'];
            $parts = explode(' ', trim($name));
            $firstName = $parts[0] ?? '';
            $lastName = $parts[1] ?? '';
            $NilDepreciationCoverYN = $aAddons['ZERODEP'];
            $RSACover = $aAddons['RSA'];
            $DailyExpRemYN = $aAddons['DAILYEXP'];
            $KeyReplacementYN = $aAddons['KEYREPLACE'];
            $LossOfPersonBelongYN = $aAddons['PERSONALBELONG'];
            $EmergencyTranHotelExpRemYN = $aAddons['EMERGENCYTRANHOTELEXPREM'] ?? false;
            $MultiCarBenefitYN = $aAddons['MULTICARBENEFITYN'];
            $Eng_Protector = $aAddons['ENG_PROTECTOR'];
            $Consumables = $aAddons['CONSUMABLES'];
            $InvReturnYN = $aAddons['INVRETURNYN'];
            $rimProtection = $aAddons['RIMPROTECTION'];
            $tyreProtection = $aAddons['TYRE_PROTECTION'];
            $LLtoPaidDriverYN = $aAddons['LLTOPAIDDRIVERYN'];
            $NoEmpCoverLL = $aAddons['NOEMPCOVERLL'];
            $SHRIMOTORPROTECTION_YN = false;
            $LimitedTPPDYN = $aAddons['LIMITEDTPPDYN'];
            $VoluntaryExcess = $aAddons['VOLUNTARYEXCESS'];
            $AntiTheftYN = $aAddons['ANTITHEFTYN'];
            $Geographical = false;
            $PAforUnnamedPassenger = $aAddons['PAFORUNNAMEDPASSENGER'];
            $PAPaidDriverConductorCleaner = $aAddons['PAPAIDDRIVERCONDUCTORCLEANER'];
            $towingCover = $aAddons['TOWINGCOVER'];
            $preferredGarage = $aAddons['PREFERREDGARAGE'];
            $payAsYouDrive = $aAddons['PAYASYOUDRIVE'];
            $VehicleType = null;
            $HypothData = json_decode($oJourneyData->bank_details, true);
            $aCardata = json_decode($aData->knowcar_reg_details, true);
            $aNewCardata = json_decode($aData->newcar_reg_details, true);
            $aAccessories = json_decode($aData->accessories, true);
            $cachepolicyExpiry = 'cache_policyExpiry_' . $userId;
            $aResult = [];
            $aVehicledetails = json_decode($oJourneyData['vehicle_details'], true);
            // return $aVehicledetails['Enginenumber'];
            if (!empty($aAccessories)) {
                foreach ($aAccessories as $item) {
                    $aResult[$item['type']] = $item['amount'];
                }
            }
            $pAcoverReason = '';
            $PAcover = '';
            // $PAforUnnamedamount = json_decode($aData->caraddonvalue, true);
            // $PAforUnnamedamount = !empty($PAforUnnamedamount) ? $PAforUnnamedamount : "0";
            $nTpolicyNo = null;
            $sTpInsurer = null;
            $prevPolicyNo = null;
            $sRegNumber = $aData->carnumber;
            $sRegNumber = '';
            $sRegNo2 = '';
            $sRegNo3 = '';
            $sRegNo1 = '';
            $sRegNo4 = '';
            $sRegNoauth = '';
            $sPrevexptoDate = null;
            $sPrevexpfDate = null;
            $sPretpfDate = null;
            $sPretptoDate = null;
            $claim = null;
            $claimper = null;
            $transferOfowner = null;
            $gstno = null;
            $oModel = null;
            $firstregdate = null;
            $cachemotortype = 'cache_motortype_' . $userId;
            $cacheunder = 'cache_under_' . $userId;
            $cacheEnquiryId = 'cache_enquiryid_' . $userId;
            $enquiryId = GetCache($cacheEnquiryId);
            // return  $enquiryId;

            $today = now();
            $claimperString = null;
            $vahanresponse = GetCache($userId . '_vahandata');
            $vahanresponse = json_decode($vahanresponse, true);
            $vahanregdate = $vahanresponse['registration_date'] ?? null;
            $monthsfromtoday = Carbon::parse($vahanregdate)
                ->diffInMonths(Carbon::parse(today()->format('Y-m-d')));
            $claim = match (true) {
                $monthsfromtoday <= 12 * 1 => 'ZERO',
                $monthsfromtoday <= 12 * 2 && $monthsfromtoday >= 12 * 1 => 'TWENTY',
                $monthsfromtoday <= 12 * 3 && $monthsfromtoday >= 12 * 2 => 'TWENTY',
                $monthsfromtoday <= 12 * 4 && $monthsfromtoday >= 12 * 3 => 'TWENTY_FIVE',
                $monthsfromtoday <= 12 * 5 && $monthsfromtoday >= 12 * 4 => 'THIRTY_FIVE',
                $monthsfromtoday <= 12 * 6 && $monthsfromtoday >= 12 * 5 => 'FORTY_FIVE',
                $monthsfromtoday >= 12 * 6 => 'FIFTY',
                default => 'ZERO',
            };
            if (GetCache($cachemotortype) == 'knowcar') {
                // $cachemodel = 'cache_model_knowcar' . $userId;
                $aCardata = json_decode($aData->knowcar_reg_details, true) ?? [];
                $modelSearch = $aCardata['model'] ?? '';
                $dRegDate = $aData->knowcar_reg_details ? json_decode($aData->knowcar_reg_details, true)['carregdate'] : date('d-m-Y');
            }

            if (GetCache($cachemotortype) == 'newcar') {
                $aCardata = json_decode($aData->newcar_reg_details, true) ?? [];
                $modelSearch = $aCardata['model'] ?? '';
                $dRegDate = date('d-m-Y');
            }

            $regDate = \DateTime::createFromFormat('Y-m-d', $dRegDate);
            $vid = getconstant('MOTOR.GODIGIT.KEY');
            $vdata = getVcode($modelSearch, $vid, 'MOT-PRD-001', 'App\Models\Master\Godigit');

            $oModel = $vdata['status'] && isset($vdata['data']->vcode) ? $vdata['data']->vcode : $vdata['data'];
            if (!$vdata['status']) {
                return $oModel;
            }

            $addonAgeLimit = [
                '101' => 7,
                '103' => 10,
                '104' => 10,
                '107' => 5,
                '108' => 7,
                '109' => 5,
                '110' => 5,
                '111' => 10,
                '106' => 1
            ];

            $validAddons = [];
            $aNominee = json_decode($oJourneyData->nominee_details, true) ?? [];
            $permanentAddress = json_decode($oJourneyData->permanent_address, true) ?? [];
            $ContactDetails = json_decode($oJourneyData->contact_details, true) ?? [];

            $pincode = $permanentAddress['pincode'] ?? '';

            $state = Godigit_pincode::where('Pincode', $pincode)->first();
            // return $state['Statecode'];
            // dd($state);
            $sState = $state['Statecode'];
            $nPlanType = $aData->car_plan_type;
            $dRegDate = $aData->knowcar_reg_details ? json_decode($aData->knowcar_reg_details, true)['carregdate'] : date('d-m-Y');
            $regDate = \DateTime::createFromFormat('d-m-Y', $dRegDate);
            $cachecaridv = 'cache_' . $userId . '_caridv';
            $idv = GetCache($cachecaridv);
            $randomValue = self::genRandomNumber();
            $EngineNo = $aVehicledetails['Enginenumber'];
            $ChassisNo = $aVehicledetails['Chassisnumber'];
            // return $oJourneyData;
            // $Dob = \DateTime::createFromFormat('d-m-Y', $oJourneyData["dob"])->format('Y-m-d') ?? "";
            $Dob = '';

            if (!empty($oJourneyData['dob'])) {
                $cleanDob = str_replace('/', '-', $oJourneyData['dob']);
                $dateObj = \DateTime::createFromFormat('d-m-Y', $cleanDob);
                if ($dateObj !== false) {
                    $Dob = $dateObj->format('Y-m-d');
                } else {
                    try {
                        $Dob = (new \DateTime($oJourneyData['dob']))->format('Y-m-d');
                    } catch (\Exception $e) {
                        $Dob = null;
                    }
                }
            }

            $NomineeDob = \DateTime::createFromFormat('d-m-Y', $aNominee['nomineedob'])->format('Y-m-d');
            // return $NomineeDob;
            if (GetCache($cachemotortype) == 'newcar') {
                $RegNumber = substr(explode('(', $aData->rtocode)[1], 0, -1);
                $sRegNo1 = substr(str_replace('-', '', $sRegNumber), 0, 2);  // k
                $sRegNo2 = substr(str_replace('-', '', $sRegNumber), 2, 2);  // k
                $sRegNoauth = substr(str_replace('-', '', $RegNumber), 0, 4);
                $sRegNumber = $sRegNoauth;
                // $sRegdate = array_key_exists('carregdate',  $aNewCardata) ? $aNewCardata['carregdate'] : '';
                $firstregdate = $today->format('Y-m-d');
            } else {
                $sRegNumber = $aData->carnumber;
                $sRegNo1 = substr($sRegNumber, 0, 2);  // k
                $sRegNo2 = substr($sRegNumber, 2, 2);  // k
                $sRegNo3 = substr($sRegNumber, 4, 2);  // k
                $sRegNo4 = substr($sRegNumber, 6, 4);  // k
                $sRegNoauth = substr(str_replace('-', '', $sRegNumber), 0, 4);
                $sRegdate = array_key_exists('carregdate', $aCardata) ? $aCardata['carregdate'] : '';
                $firstregdate = \DateTime::createFromFormat('d-m-Y', $sRegdate)->format('Y-m-d');
            }
            // return $firstregdate;

            $previousPolicyTypeCodeCode = null;
            if (GetCache($cachemotortype) == 'knowcar') {
                if ($aCardata['prepolitype'] == 'odonly') {
                    $sPrePolicyType = getconstant('MOTOR.GODIGIT.CAR.POLICYTYPE.OWNDAMAGE');
                    $previousPolicyTypeCodeCode = '1OD_0TP';
                    $sPrevexptoDate = \DateTime::createFromFormat('d-m-Y', $aCardata['odtodate'])->format('Y-m-d');  // k
                    $sPrevexpfDate = \DateTime::createFromFormat('d-m-Y', $aCardata['odfromdate'])->format('Y-m-d');  // k
                    $sPretpfDate = \DateTime::createFromFormat('d-m-Y', $aCardata['odtpfromdate'])->format('Y-m-d');
                    $sPretptoDate = \DateTime::createFromFormat('d-m-Y', $aCardata['odtptodate'])->format('Y-m-d');
                    $nTpolicyNo = array_key_exists('tppolicynumber', $prevPolicydata) ? $prevPolicydata['tppolicynumber'] : '';
                    $sTpInsurer = array_key_exists('tpprevInsurance', $prevPolicydata) ? $prevPolicydata['tpprevInsurance'] : '';
                    // $tpInsurCmpny = Godigit_Prev_Ins::where('id', $sTpInsurer)->first()->no;
                    $date = Carbon::createFromFormat('d-m-Y', $aCardata['odtodate']);
                    $PolicyFromDate = $date->addDay()->format('Y-m-d');
                    $PolicyToDate = $date->addYear()->subDay()->format('Y-m-d');
                } else if ($aCardata['prepolitype'] == 'bundled') {
                    $sPrePolicyType = getconstant('MOTOR.GODIGIT.CAR.POLICYTYPE.BUNDLED');
                    $previousPolicyTypeCodeCode = '1OD_3TP';
                    $sPrevexptoDate = \DateTime::createFromFormat('d-m-Y', $aCardata['bdtodate'])->format('Y-m-d');  // k
                    $sPrevexpfDate = \DateTime::createFromFormat('d-m-Y', $aCardata['bdfromdate'])->format('Y-m-d');  // k
                    $sPretpfDate = \DateTime::createFromFormat('d-m-Y', $aCardata['bdtpfromdate'])->format('Y-m-d');
                    $sPretptoDate = \DateTime::createFromFormat('d-m-Y', $aCardata['bdtptodate'])->format('Y-m-d');
                    $nTpolicyNo = array_key_exists('tppolicynumber', $prevPolicydata) ? $prevPolicydata['tppolicynumber'] : '';
                    $sTpInsurer = array_key_exists('tpprevInsurance', $prevPolicydata) ? $prevPolicydata['tpprevInsurance'] : '';
                    // $tpInsurCmpny = Godigit_Prev_Ins::where('id', $sTpInsurer)->first()->no;
                    $date = Carbon::createFromFormat('d-m-Y', $aCardata['bdtodate']);
                    $PolicyFromDate = $date->addDay()->format('Y-m-d');
                    $PolicyToDate = $date->addYear()->subDay()->format('Y-m-d');
                } elseif ($aCardata['prepolitype'] == 'comprehensive') {
                    $sPrePolicyType = getconstant('MOTOR.GODIGIT.CAR.POLICYTYPE.PACKAGE');
                    $previousPolicyTypeCodeCode = '1OD_1TP';
                    $sPrevexptoDate = \DateTime::createFromFormat('d-m-Y', $aCardata['comptodate'])->format('Y-m-d');  // k
                    $sPrevexpfDate = \DateTime::createFromFormat('d-m-Y', $aCardata['compfromdate'])->format('Y-m-d');
                    $date = Carbon::createFromFormat('d-m-Y', $aCardata['comptodate']);
                    $PolicyFromDate = $date->addDay()->format('Y-m-d');
                    $PolicyToDate = $date->addYear()->subDay()->format('Y-m-d');
                } elseif ($aCardata['prepolitype'] == 'tponly') {
                    $sPrePolicyType = getconstant('MOTOR.GODIGIT.CAR.POLICYTYPE.LIABILITY');
                    $previousPolicyTypeCodeCode = '0OD_1TP';
                    $sPrevexptoDate = \DateTime::createFromFormat('d-m-Y', $aCardata['tptodate'])->format('Y-m-d');  // k
                    $sPrevexpfDate = \DateTime::createFromFormat('d-m-Y', $aCardata['tpfromdate'])->format('Y-m-d');  // k
                    $date = Carbon::createFromFormat('d-m-Y', $aCardata['tptodate']);
                    $PolicyFromDate = $date->addDay()->format('Y-m-d');
                    $PolicyToDate = $date->addYear()->subDay()->format('Y-m-d');
                }
            }
            // return $oJourneyData['nominee_details'];

            // return $EngineNo;
            // $EngineNo = "GDFG59D4GD6546D" . $randomValue;//$aVehicledetails['Enginenumber'];
            // $ChassisNo = "GDF45GDFGD4G56D" . $randomValue;//$aVehicledetails['Chassisnumber'];
            $sPolicyType = '';
            $sProposalType = '';
            $gstno = null;
            $sinsuranceProductCode = null;
            $subInsuranceProductCode = null;
            $NewVehicleType = null;
            $previousNoClaimBonus = null;
            $previousPolicyNumber = '';
            $isClaimInLastYear = null;
            $isPreviousInsurerKnown = null;
            $policyHolderType = null;
            $previousInsurer = [];
            if (GetCache($cachemotortype) == 'newcar') {
                $NewVehicleType = true;
                $previousNoClaimBonus = 'ZERO';
                $isClaimInLastYear = false;
                $isPreviousInsurerKnown = false;
                $subInsuranceProductCode = getconstant('MOTOR.GODIGIT.PROPOSALTYPE.CARFRESHPROPOSAL');
                $companydetails = json_decode($oJourneyData->company_details, true) ?? '';
                if ($aNewCardata['under'] == 'company') {
                    $PAcover = 0;
                    $pAcoverReason = 'PA_TYPE2';
                    $policyHolderType = 'company';
                } else {
                    $PAcover = $aData->pacover;
                    $policyHolderType = 'INDIVIDUAL';
                    if ($PAcover == 0) {
                        $pAcoverReason = json_decode($aData->pacover_reason, true);
                        $ncoverReason = is_array($pAcoverReason) ? (array_keys($pAcoverReason)[0] ?? []) : [];
                        if ($ncoverReason == '1') {
                            $pAcoverReason = 'PA_TYPE1';
                        }
                        if ($ncoverReason == '2') {
                            $pAcoverReason = 'PA_TYPE2';
                        }
                        if ($ncoverReason == '3') {
                            $pAcoverReason = 'PA_TYPE4';
                        } else {
                            $pAcoverReason = '';
                        }
                    }
                }
                if ($nPlanType == '2') {
                    $sinsuranceProductCode = getconstant('MOTOR.GODIGIT.CAR.POLICYTYPE.PACKAGE');
                    $PolicyFromDate = $today->format('Y-m-d');
                    $PolicyToDate = $today->addYears(3)->subDay()->format('Y-m-d');
                }
            }
            if (GetCache($cachemotortype) == 'knowcar') {
                $claim = array_key_exists('policytoggle', $aCardata) ? '0' : '1';
                $claimper = array_key_exists('bonus-button', $aCardata) ? $aCardata['bonus-button'] : '0';
                $transferOfowner = array_key_exists('ownershiptoggle', $aCardata) ? $aCardata['ownershiptoggle'] : '0';

                $claimMap = [
                    0 => 'ZERO',
                    20 => 'TWENTY',
                    25 => 'TWENTY_FIVE',
                    35 => 'THIRTY_FIVE',
                    45 => 'FORTY_FIVE',
                    50 => 'FIFTY',
                    55 => 'FIFTY_FIVE',
                    65 => 'SIXTY_FIVE',
                ];

                $claimperString = $claimMap[$claimper] ?? null;
                $NewVehicleType = false;
                // $prevInsurCmpny = Godigit_Prev_Ins::where('id', $prevPolicydata['prevInsuranceId'])->first()->no;
                // $previousInsurerCode = $prevInsurCmpny;
                // $previousPolicyNumber = "12345877";
                $previousNoClaimBonus = $claim;  // "TWENTY";
                // dd($previousNoClaimBonus,$claim,$aCardata);
                $isClaimInLastYear = false;
                $isPreviousInsurerKnown = true;
                $subInsuranceProductCode = getconstant('MOTOR.GODIGIT.PROPOSALTYPE.MARKETRENEWAL');
                $prevPolicyNo = $prevPolicydata['policynumber'];
                if ($aCardata['under'] == 'company') {
                    $PAcover = 0;
                    $policyHolderType = 'company';
                    $pAcoverReason = 'PA_TYPE2';
                    $companydetails = json_decode($oJourneyData->company_details, true);
                    $gstno = $companydetails['gstnumber'];
                } else {
                    $PAcover = $aData->pacover;
                    $policyHolderType = 'INDIVIDUAL';
                    $gstno = null;
                    if ($PAcover == 0) {
                        $pAcoverReason = json_decode($aData->pacover_reason, true);
                        $ncoverReason = is_array($pAcoverReason) ? (array_keys($pAcoverReason)[0] ?? []) : [];
                        if ($ncoverReason == '1') {
                            $pAcoverReason = 'PA_TYPE1';
                        }
                        if ($ncoverReason == '2') {
                            $pAcoverReason = 'PA_TYPE2';
                        }
                        if ($ncoverReason == '3') {
                            $pAcoverReason = 'PA_TYPE4';
                        } else {
                            $pAcoverReason = '';
                        }
                    }
                }
                if ($nPlanType == '1') {
                    $previousInsurer = [
                        'isPreviousInsurerKnown' => true,
                        'previousInsurerCode' => $previousInsurerCode,  // "108",
                        'previousPolicyNumber' => $prevPolicyNo,  // "134356666",
                        'previousPolicyExpiryDate' => $sPrevexptoDate ?? '',
                        'isClaimInLastYear' => false,
                        'originalPreviousPolicyType' => $previousPolicyTypeCodeCode,
                        'previousPolicyType' => $previousPolicyTypeCodeCode,
                        'previousNoClaimBonus' => $claim ?? 'ZERO',  // "ZERO",
                        'currentThirdPartyPolicy' => [
                            'isCurrentThirdPartyPolicyActive' => true,
                            'currentThirdPartyPolicyInsurerCode' => $previousTpInsurerCode ?? '',
                            'currentThirdPartyPolicyNumber' => $previousTpInsurerPolicy ?? '',
                            'currentThirdPartyPolicyStartDateTime' => $sPretpfDate ?? '',
                            'currentThirdPartyPolicyExpiryDateTime' => $sPretptoDate ?? ''
                        ]
                    ];
                    $sinsuranceProductCode = getconstant('MOTOR.GODIGIT.CAR.POLICYTYPE.OWNDAMAGE');
                }
                if ($nPlanType == '2') {
                    $sinsuranceProductCode = getconstant('MOTOR.GODIGIT.CAR.POLICYTYPE.PACKAGE');
                    $previousInsurer = [
                        'previousInsurerCode' => $previousInsurerCode ?? '',
                        'previousPolicyExpiryDate' => $sPrevexptoDate ?? '',
                        'isClaimInLastYear' => false,  // $isClaimInLastYear ?? "",
                        'previousNoClaimBonus' => $claim ?? 'ZERO',
                        'previousPolicyNumber' => $prevPolicyNo ?? '',
                        'isPreviousInsurerKnown' => $isPreviousInsurerKnown ?? null,
                        'originalPreviousPolicyType' => $previousPolicyTypeCodeCode,  // null,
                        'previousPolicyType' => $previousPolicyTypeCodeCode ?? null,
                        'currentThirdPartyPolicy' => [
                            'isCurrentThirdPartyPolicyActive' => true,
                            'currentThirdPartyPolicyInsurerCode' => $previousTpInsurerCode ?? '',
                            'currentThirdPartyPolicyNumber' => $previousTpInsurerPolicy ?? '',
                            'currentThirdPartyPolicyStartDateTime' => $sPretpfDate ?? '',
                            'currentThirdPartyPolicyExpiryDateTime' => $sPretptoDate ?? ''
                        ]
                    ];
                }
                if ($nPlanType == '3' && (GetCache($cachepolicyExpiry) == 'Not Expired')) {
                    $sinsuranceProductCode = getconstant('MOTOR.GODIGIT.CAR.POLICYTYPE.LIABILITY');
                    $previousInsurer = [
                        'previousInsurerCode' => $previousInsurerCode ?? '',
                        'previousPolicyExpiryDate' => $sPrevexptoDate ?? '',
                        'isClaimInLastYear' => false,  // $isClaimInLastYear ?? "",
                        'previousNoClaimBonus' => $claim ?? 'ZERO',
                        'previousPolicyNumber' => $prevPolicyNo ?? '',
                        'isPreviousInsurerKnown' => $isPreviousInsurerKnown ?? null,
                        'originalPreviousPolicyType' => $previousPolicyTypeCodeCode,  // null,
                        'previousPolicyType' => $previousPolicyTypeCodeCode ?? null,
                        'currentThirdPartyPolicy' => [
                            'isCurrentThirdPartyPolicyActive' => true,
                            'currentThirdPartyPolicyInsurerCode' => $previousTpInsurerCode ?? '',
                            'currentThirdPartyPolicyNumber' => $previousTpInsurerPolicy ?? '',
                            'currentThirdPartyPolicyStartDateTime' => $sPretpfDate ?? '',
                            'currentThirdPartyPolicyExpiryDateTime' => $sPretptoDate ?? ''
                        ]
                    ];
                }
                if ($nPlanType == '3' && (GetCache($cachepolicyExpiry) == 'Expired')) {
                    $sinsuranceProductCode = getconstant('MOTOR.GODIGIT.CAR.POLICYTYPE.LIABILITY');
                    $previousInsurer = [
                        'previousInsurerCode' => $previousInsurerCode ?? '',
                        'previousPolicyExpiryDate' => $sPrevexptoDate ?? '',
                        'isClaimInLastYear' => false,  // $isClaimInLastYear ?? "",
                        'previousNoClaimBonus' => $claim ?? 'ZERO',
                        'previousPolicyNumber' => $prevPolicyNo ?? '',
                        'isPreviousInsurerKnown' => $isPreviousInsurerKnown ?? null,
                        'originalPreviousPolicyType' => $previousPolicyTypeCodeCode,  // null,
                        'previousPolicyType' => $previousPolicyTypeCodeCode ?? null,
                        'currentThirdPartyPolicy' => [
                            'isCurrentThirdPartyPolicyActive' => true,
                            'currentThirdPartyPolicyInsurerCode' => $previousTpInsurerCode ?? '',
                            'currentThirdPartyPolicyNumber' => $previousTpInsurerPolicy ?? '',
                            'currentThirdPartyPolicyStartDateTime' => $sPretpfDate ?? '',
                            'currentThirdPartyPolicyExpiryDateTime' => $sPretptoDate ?? ''
                        ]
                    ];
                    $PolicyFromDate = $today->format('Y-m-d');
                    $PolicyToDate = $today->addYear()->subDay()->format('Y-m-d');
                }
            }
            $sRtocity = getRtocityApi($request, $sRegNumber);
            if ($sRtocity) {
                $sRtocity = !empty($sRtocity->RTOCITY) ? $sRtocity->RTOCITY : $sRtocity->RTONAME;
            } else {
                $sRtocity = '';
            }

            $dob = !empty($oJourneyData->dob)
                ? $oJourneyData->dob
                : (!empty($user->dob)
                    ? $user->dob
                    : '29-04-2000');
            // $CacheIdv = 'godigt_cache_idv' . $userId;
            // $idv = GetCache($CacheIdv);

            $curl = curl_init();
            $data = json_encode([
                'persons' => [
                    [
                        'firstName' => $firstName ?? '',
                        'identificationDocuments' => [],
                        'lastName' => $lastName ?? '',
                        'addresses' => [
                            [
                                'addressType' => 'PRIMARY_RESIDENCE',
                                'flatNumber' => $permanentAddress['address1'] ?? '',  // "12fsdf3",
                                'streetNumber' => (strlen($permanentAddress['address2']) >= 30)
                                    ? substr($permanentAddress['address2'], 0, 30)
                                    : $permanentAddress['address2'],
                                'street' => $permanentAddress['landmark'] ?? '',  // "jhgfdsdfg76543456 ",
                                'district' => $state['District'] ?? '',  // "Jaipur",
                                'city' => $state['City'] ?? '',  // "Jaipur",
                                'country' => 'IN',
                                'pincode' => $pincode ?? '',
                                'state' => $state['Statecode']  // "9"
                            ]
                        ],
                        'communications' => [
                            [
                                'communicationType' => 'MOBILE',
                                'communicationId' => $ContactDetails['contactmobile'] ?? '',  // "9987650999",
                                'isPrefferedCommunication' => true
                            ],
                            [
                                'communicationType' => 'EMAIL',
                                'communicationId' => $ContactDetails['contactemail'] ?? '',  // "ajhgfd@a.com",
                                'isPrefferedCommunication' => true
                            ]
                        ],
                        'isVehicleOwner' => true,
                        'isInsuredPerson' => true,
                        'gender' => ($oJourneyData['gender'] == 'Mr') ? 'MALE' : 'FEMALE',
                        'isPolicyHolder' => true,
                        'dateOfBirth' => $Dob ?? '',  // "1998-07-08",
                        'isDriver' => false,
                        'personType' => 'INDIVIDUAL'
                    ]
                ],
                'pincode' => $pincode ?? '',
                'previousInsurer' => $previousInsurer ?? '',
                'preInspection' => [
                    'isPreInspectionOpted' => false
                ],
                'kyc' => [
                    'ckycReferenceNumber' => '',  // "ASDRR4277S",
                    'isKYCDone' => true,
                    'ckycReferenceDocId' => '',  // "D07",
                    'successReturnURL' => getconstant('MOTOR.GODIGIT.API.RETURNURL'),
                    'failureReturnURL' => getconstant('MOTOR.GODIGIT.API.RETURNURL'),
                    'photo' => 'gfgh',
                    'dateOfBirth' => '',  // "1988-07-08"
                ],
                'contract' => [
                    'policyHolderType' => $policyHolderType,  // "INDIVIDUAL",
                    'insuranceProductCode' => $sinsuranceProductCode,  // "20101",
                    'endDate' => $PolicyToDate,  // "2028-11-10",
                    'externalPolicyNumber' => null,
                    'isNCBTransfer' => true,
                    'subInsuranceProductCode' => $subInsuranceProductCode,  // "31",
                    'coverages' => [
                        'accessories' => [
                            'cng' => [
                                'selection' => false,
                                'insuredAmount' => null
                            ],
                            'electrical' => [
                                'selection' => false,
                                'insuredAmount' => null
                            ],
                            'nonElectrical' => [
                                'selection' => false,
                                'insuredAmount' => null
                            ]
                        ],
                        'addons' => [
                            'returnToInvoice' => [
                                'selection' => $InvReturnYN ?? false
                            ],
                            'rimProtection' => [
                                'selection' => $rimProtection ?? false
                            ],
                            'consumables' => [
                                'selection' => $Consumables ?? false
                            ],
                            'partsDepreciation' => [
                                'selection' => $NilDepreciationCoverYN ?? false
                            ],
                            'engineProtection' => [
                                'selection' => $Eng_Protector ?? false
                            ],
                            'tyreProtection' => [
                                'selection' => $rimProtection ?? false
                            ],
                            'roadSideAssistance' => [
                                'selection' => $RSACover ?? false
                            ],
                            'keyAndLockProtect' => [
                                'selection' => $KeyReplacementYN ?? false
                            ],
                            'personalBelonging' => [
                                'selection' => $LossOfPersonBelongYN ?? false
                            ],
                            'preferredGarage' => [
                                'selection' => $preferredGarage ?? false
                            ],
                            'payAsYouDrive' => [
                                'selection' => $payAsYouDrive ?? false
                            ],
                            'towingCover' => [
                                'selection' => $towingCover ?? false
                            ]
                        ],
                        'voluntaryDeductible' => null,
                        'isGeoExt' => false,
                        'legalLiability' => [
                            'paidDriverLL' => [
                                'selection' => $LLtoPaidDriverYN ?? false
                            ],
                            'employeesLL' => [
                                'selection' => false
                            ],
                            'unnamedPaxLL' => [
                                'selection' => false
                            ],
                            'cleanersLL' => [
                                'selection' => false
                            ],
                            'nonFarePaxLL' => [
                                'selection' => false
                            ],
                            'workersCompensationLL' => [
                                'selection' => false
                            ]
                        ],
                        'unnamedPA' => [
                            'unnamedPax' => [
                                'selection' => false,
                                'insuredAmount' => null
                            ],
                            'unnamedPaidDriver' => [
                                'selection' => false
                            ],
                            'unnamedHirer' => [
                                'selection' => false
                            ],
                            'unnamedPillionRider' => [
                                'selection' => false
                            ],
                            'unnamedCleaner' => [
                                'selection' => false
                            ],
                            'unnamedConductor' => [
                                'selection' => false
                            ]
                        ],
                        'theft' => [
                            'selection' => false
                        ],
                        'isTheftAndConversionRiskIMT43' => false,
                        'ownDamage' => [
                            'discount' => [
                                'userSpecialDiscountPercent' => null
                            ]
                        ],
                        'isIMT23' => false,
                        'personalAccident' => [
                            'coverTerm' => null,
                            'selection' => false,
                            'insuredAmount' => null
                        ],
                        'fire' => [
                            'selection' => false
                        ],
                        'thirdPartyLiability' => [
                            'isTPPD' => $LimitedTPPDYN ?? false
                        ],
                        'isOverturningExclusionIMT47' => false
                    ],
                    'startDate' => $PolicyFromDate ?? '',  // "2025-11-11"
                ],
                'nominee' => [
                    'firstName' => $aNominee['nomineename'] ?? '',  // "dfgh",
                    'lastName' => 'jhgf',
                    'dateOfBirth' => $NomineeDob ?? '',  // "1999-09-09",
                    'middleName' => null,
                    'personType' => 'INDIVIDUAL',
                    'relation' => $aNominee['nomineerelation'] ?? '',  // "BROTHER"
                ],
                'motorQuestions' => [
                    'selfInspection' => false,
                    'furtherAgreement' => null,
                    'financer' => null
                ],
                'enquiryId' => $enquiryId ?? '',
                'pospInfo' => [
                    'isPOSP' => false
                ],
                'vehicle' => [
                    'isVehicleNew' => $NewVehicleType ?? null,
                    'licensePlateNumber' => $sRegNumber ?? '',
                    'registrationAuthority' => $sRegNoauth ?? '',
                    'engineNumber' => $EngineNo ?? '',
                    'vehicleIdentificationNumber' => $ChassisNo ?? '',
                    'registrationDate' => $firstregdate ?? '',
                    'manufactureDate' => $firstregdate ?? '',
                    'vehicleMaincode' => $oModel,
                    'vehicleIDV' => [
                        'idv' => $idv ?? 0
                    ]
                ]
            ], JSON_UNESCAPED_SLASHES);
            // return json_decode($data);
            // dd($data);
            if (GetCache($cachemotortype) == 'newcar') {
                SaveFile($data, 'godigit_policy_newcar_json_request.txt');
            } else {
                SaveFile($data, 'godigit_policy_json_request.txt');
            }
            $url = getconstant('MOTOR.GODIGIT.API.POLICY');
            $integartionid = getconstant('MOTOR.GODIGIT.INTEGRATIONID.POLICY');
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_HTTPHEADER => array(
                    'integrationId: ' . $integartionid,  // 24579-0100 //27634-0100
                    'Authorization: Bearer ' . $sToken,
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);
            // dd($response);
            if (GetCache($cachemotortype) == 'newcar') {
                SaveFile($response, 'godigit_policy_newcar_json_response.txt');
            } else {
                SaveFile($response, 'godigit_policy_json_response.txt');
            }
            curl_close($curl);
            return $response;
        } catch (\Exception $e) {
            return response()->json(ErrMessage($e));
        }
    }

    public static function Payment(Request $request)
    {
        $userId = $request->userid;

        // 🔹 Token generate
        $tokenResponse = GoDigitUtilityService::TokenGenerate();
        $tokenData = json_decode($tokenResponse, true);
        $accessToken = $tokenData['access_token'] ?? '';
        $integartionid = getconstant('MOTOR.GODIGIT.INTEGRATIONID.PAYMENT');
        if (!$accessToken) {
            return response()->json([
                'status' => false,
                'message' => 'Token generation failed',
            ]);
        }

        // 🔹 Application ID from cache
        $cachequoteid = 'cache_godigitAppid_' . $userId;
        $applicationId = GetCache($cachequoteid);

        if (!$applicationId) {
            return response()->json([
                'status' => false,
                'message' => 'Application ID not found in cache'
            ]);
        }

        // 🔹 Dynamic payload
        $payload = [
            'paymentMode' => 'EB',
            'successReturnUrl' => getconstant('MOTOR.GODIGIT.API.PAYMENTRETURNURL'),
            'cancelReturnUrl' => getconstant('MOTOR.GODIGIT.API.PAYMENTRETURNURL'),
            'applicationId' => $applicationId
        ];

        $postfields = json_encode($payload);

        // 🔹 Log request
        SaveFile($postfields, 'godigit_request_paymentlink.txt');

        // 🔹 cURL init
        $curl = curl_init();
        $url = getconstant('MOTOR.GODIGIT.API.PAYMENT');
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,  // better than 0
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_HTTPHEADER => [
                'integrationId: ' . $integartionid,
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json'
            ],
            CURLOPT_SSL_VERIFYPEER => false,  // only for testing
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        // 🔹 Log response
        SaveFile($response, 'godigit_response_paymentlink.txt');

        if ($error) {
            return response()->json([
                'status' => false,
                'message' => 'cURL Error',
                'error' => $error
            ]);
        }

        return [
            json_decode($response, true)
        ];
    }

    public static function PaymentStatus($policyNumber = null)
    {
        $tokenResponse = GoDigitUtilityService::TokenGenerate();
        $tokenData = json_decode($tokenResponse, true);
        $accessToken = $tokenData['access_token'] ?? '';
        $integartionid = getconstant('MOTOR.GODIGIT.INTEGRATIONID.PAYMENTSTATUS');
        if (!$accessToken) {
            return response()->json([
                'status' => false,
                'message' => 'Token generation failed'
            ]);
        }

        // 🔹 Policy Number (request se lo)
        // $policyNumber = $request->policy_number;

        if (!$policyNumber) {
            return response()->json([
                'status' => false,
                'message' => 'Policy number required'
            ]);
        }

        // 🔹 API URL
        $url = getconstant('MOTOR.GODIGIT.API.PAYMENTSTATUS');

        // 🔹 Payload
        $payload = [
            'queryParam' => [
                'policyNumber' => $policyNumber
            ]
        ];

        // 🔹 cURL Call
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'integrationId: ' . $integartionid,
                'Content-Type: application/json',
                'Authorization: Bearer ' . $accessToken,
            ],
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return response()->json([
                'status' => false,
                'message' => curl_error($ch)
            ]);
        }

        curl_close($ch);

        // 🔹 Decode response
        $result = json_decode($response, true);

        return $result;
    }

    public static function PolicyPdf($policyNumber = null)
    {
        // $userId = $request->userid;

        // 🔹 Token generate
        $tokenResponse = GoDigitUtilityService::TokenGenerate();
        $tokenData = json_decode($tokenResponse, true);
        $accessToken = $tokenData['access_token'] ?? '';
        $integartionid = getconstant('MOTOR.GODIGIT.INTEGRATIONID.PDF');
        if (!$accessToken) {
            return response()->json([
                'status' => false,
                'message' => 'Token generation failed'
            ]);
        }

        if (!$policyNumber) {
            return response()->json([
                'status' => false,
                'message' => 'Policy number required'
            ]);
        }

        // 🔹 API URL
        $url = getconstant('MOTOR.GODIGIT.API.PDF');

        // 🔹 Payload
        $payload = [
            'policyId' => $policyNumber
        ];
        SaveFile($payload, 'godigit_pdf_json_request.txt');
        // 🔹 cURL Call
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'integrationId: ' . $integartionid,
                'Content-Type: application/json',
                'Authorization: Bearer ' . $accessToken,
            ],
        ]);

        $response = curl_exec($ch);
        SaveFile($response, 'godigit_pdf_json_response.txt');
        if (curl_errno($ch)) {
            return response()->json([
                'status' => false,
                'message' => curl_error($ch)
            ]);
        }

        curl_close($ch);

        // 🔹 Decode response
        $result = json_decode($response, true);

        return $result;
    }
}
