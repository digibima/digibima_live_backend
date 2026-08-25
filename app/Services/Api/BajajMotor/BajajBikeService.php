<?php
namespace App\Services\Api\BajajMotor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Shriram\{Shriram_Pincode, Shriram_planCheckout, Shriram_RTO_Master, Shriram_Prev_insurence, Shriram_Vehicle_Master};
use App\Models\{Master_Vehicle_Data as DataModel, MasterAPI, User, MotorJourney, MasterVendor, VendorMotor, MasterMotor, UserMotorDescription, Vehicle_Info};
use Illuminate\Support\Facades\{Auth, Cache};
use App\Models\Bajaj\{RtoMaster, RtaMaster};
use App\Models\Bajaj\BajajPrevInsurence;
class BajajBikeService
{
    private static $Id = "webservice.pos@digibima.com";
    private static $vehicletypecode = "21";
    private static $password = "Newpas12";
    private static $deptcode = "18";

    public static function genRandomNumber()
    {
        $rand = rand(1111, 9999);
        return $rand;
    }

    public static function generateBikeQuote($userId)
    {
        try {
            //$userId = $request->userid;

            $user = User::find($userId);
            $today = Carbon::today();
            // self::initlize();

            $cachemotortype = 'cache_motortype_' . $userId;
            $oDataModel = DataModel::where('userid', $userId)->first();
            $nPlanType = $oDataModel->bike_plan_type;
            // return [
            //     "plantype" => $nPlanType,
            //     "cachemotortype" => $cachemotortype
            // ];
            $cachebikePolicyexp = 'cache_bikepolicyexp_' . $userId;
            // $PAforUnnamedamount = json_decode($aData->bikeaddonvalue, true);
            // $PAforUnnamedamount = !empty($PAforUnnamedamount) ? $PAforUnnamedamount : "0";
            // //$aAddons = json_decode($aData->bikeaddon, true) ?? [];
            // $aBikeAddon = is_string($aData->bikeaddon)
            //     ? json_decode($aData->bikeaddon, true)
            //     : (array) $aData->bikeaddon;


            if (GetCache($cachemotortype) == "newbike") {
                $regno = 'NEW';
                $policyType = getconstant("MOTOR.BAJAJMOTOR.POLICYTYPE.NB");
                if ($nPlanType == '2') {
                    // $sPolicyType = getconstant("MOTOR.ZUNO.POLICYTYPE.BUNDLED");
                    $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    $PolicyToDate = $today->addYears(5)->subDay()->format('d-M-Y');
                    $sPrePolicyType = getconstant("MOTOR.BAJAJMOTOR.PRODUCTCODETWOWHEELER.NEWBUSSINESS");
                }
            }


            if (GetCache($cachemotortype) == "knowbike") {
                $policyType = getconstant("MOTOR.BAJAJMOTOR.POLICYTYPE.OTHER");
                if ($nPlanType == '1') {
                    $sPrePolicyType = getconstant("MOTOR.BAJAJMOTOR.PRODUCTCODETWOWHEELER.ODONLY");
                    //         $NilDepreciationCoverYN = in_array('101', $aAddons) ? "Y" : "N";
                    //         $RSACover = in_array('102', $aAddons) ? "Y" : "N";
                    //         $DailyExpRemYN = in_array('109', $aAddons) ? "Y" : "N";
                    //         $KeyReplacementYN = in_array('111', $aAddons) ? "Y" : "N";
                    //         $LossOfPersonBelongYN = in_array('107', $aAddons) ? "Y" : "N";
                    //         $Eng_Protector = in_array('104', $aAddons) ? "Y" : "N";
                    //         $Consumables = in_array('103', $aAddons) ? "Y" : "N";
                    //         $InvReturnYN = in_array('106', $aAddons) ? "Y" : "N";
                    //         $AntiTheftYN = in_array('119', $aAddons) ? "1" : "0";
                    //         $LLtoPaidDriverYN = in_array('116', $aAddons) ? "1" : "0";
                    //         $NoEmpCoverLL = in_array('122', $aAddons) ? "1" : "0";
                    //         $SHRIMOTORPROTECTION_YN = in_array('113', $aAddons) ? "Y" : "N";
                    //         $LimitedTPPDYN = in_array('121', $aAddons) ? "1" : "0";
                    //         $VoluntaryExcess = in_array('120', $aAddons) ? "1" : "0";
                    //         $PAPaidDriverConductorCleaner = 1;
                    //         $Geographical = in_array('118', $aAddons) ? 1 : "0";
                    //         $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
                }
                if ($nPlanType == '2') {
                    $sPrePolicyType = getconstant("MOTOR.BAJAJMOTOR.PRODUCTCODETWOWHEELER.COMPREHENSIVE");
                    //         $RSACover = in_array('102', $aAddons) ? "Y" : "N";
                    //         $DailyExpRemYN = in_array('109', $aAddons) ? "Y" : "N";
                    //         $KeyReplacementYN = in_array('111', $aAddons) ? "Y" : "N";
                    //         $LossOfPersonBelongYN = in_array('107', $aAddons) ? "Y" : "N";
                    //         $Eng_Protector = in_array('104', $aAddons) ? "Y" : "N";
                    //         $Consumables = in_array('103', $aAddons) ? "Y" : "N";
                    //         $InvReturnYN = in_array('106', $aAddons) ? "Y" : "N";
                    //         $AntiTheftYN = in_array('119', $aAddons) ? "1" : "0";
                    //         $LLtoPaidDriverYN = in_array('116', $aAddons) ? "1" : "0";
                    //         $NoEmpCoverLL = in_array('122', $aAddons) ? "1" : "0";
                    //         $SHRIMOTORPROTECTION_YN = in_array('113', $aAddons) ? "Y" : "N";
                    //         $LimitedTPPDYN = in_array('121', $aAddons) ? "1" : "0";
                    //         $VoluntaryExcess = in_array('120', $aAddons) ? "1" : "0";
                    //         $PAPaidDriverConductorCleaner = 1;
                    //         $Geographical = in_array('117', $aAddons) ? 1 : "0";
                    //         $Geographical = in_array('118', $aAddons) ? 1 : "0";
                    //         $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
                }
                // if ($nPlanType == '3' && (GetCache($cachebikePolicyexp) == "Expired")) {
                //     //         $sPolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.LIABILITY");
                //     $sPrePolicyType = getconstant("MOTOR.BAJAJMOTOR.PRODUCTCODETWOWHEELER.TPONLY");
                //     //         $PolicyFromDate = $today->format('d-m-Y');
                //     //         $PolicyToDate = $today->addYear()->subDay()->format('d-m-Y');
                //     $PolicyFromDate = $today->format('d-M-Y');
                //     $PolicyToDate = $today->addYear()->subDay()->format('d-M-Y');
                //     //         $LLtoPaidDriverYN = in_array('116', $aAddons) ? "1" : "0";
                //     //         $NoEmpCoverLL = in_array('122', $aAddons) ? "1" : "0";
                //     //         $LimitedTPPDYN = in_array('121', $aAddons) ? "1" : "0";
                //     //         $Geographical = in_array('118', $aAddons) ? 1 : "0";
                //     //         $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
                // }
                // if ($nPlanType == '3' && (GetCache($cachebikePolicyexp) == "Not Expired")) {
                //     //         $sPolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.LIABILITY"); //tp
                //     $sPrePolicyType = getconstant("MOTOR.BAJAJMOTOR.PRODUCTCODETWOWHEELER.TPONLY");
                //     //         $LLtoPaidDriverYN = in_array('116', $aAddons) ? "1" : "0";
                //     //         $NoEmpCoverLL = in_array('122', $aAddons) ? "1" : "0";
                //     //         $LimitedTPPDYN = in_array('121', $aAddons) ? "1" : "0";
                //     //         $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
                //     //         $Geographical = in_array('118', $aAddons) ? 1 : "0";
                // }
            }

            // $sRtocity = getBikeRtocityApi($request, $sRegNumber);
            // if ($sRtocity) {
            //     $sRtocity = $sRtocity->RTOCITY ?? $sRtocity->RTONAME;
            // } else {
            //     $sRtocity = "";
            // }

            $pincode = $user->pincode;
           

            //$oDataModel = DataModel::where('userid', $userId)->first();

            $cacheBikeIdv = RedisGet('bajajbike_idv:' . $userId);
            $bikeidv = !empty($cacheBikeIdv) ? $cacheBikeIdv : "0";
            // $cachebikeidv = 'cache_' . $userId . '_bikeidv';
            // $idv = GetCache($cachebikeidv);
            if (GetCache($cachemotortype) == "newbike") {
                $regno = 'NEW';
                $policyType = getconstant("MOTOR.BAJAJMOTOR.POLICYTYPE.NB");
                $sPrePolicyType = getconstant("MOTOR.BAJAJMOTOR.PRODUCTCODETWOWHEELER.NEWBUSSINESS");
                $aNewBikedata = json_decode($oDataModel->newbike_reg_details, true);
                $year = $aNewBikedata['brandyear'];
                //$today = Carbon::today();
                $PolicyFromDate = $today->addDay()->format('d-M-Y');
                $PolicyToDate = $today->addYears(5)->subDay()->format('d-M-Y');

            }

            if (GetCache($cachemotortype) == 'knowbike') {
                $policyType = getconstant("MOTOR.BAJAJMOTOR.POLICYTYPE.OTHER");
                $aBikedata = json_decode($oDataModel->knowbike_reg_details, true) ?? [];
                $regno = $oDataModel->bikenumber;
                if ($aBikedata['prepolitype'] == 'odonly') {
                    $sPrePolicyType = getconstant("MOTOR.BAJAJMOTOR.PRODUCTCODETWOWHEELER.ODONLY");
                    $bikedate = $aBikedata['bikeregdate'];
                    $year = $aBikedata['brandyear'];
                    $regdate = Carbon::parse($bikedate);
                    $bikeregdata = strtoupper($regdate->format('d-M-Y'));
                    // $sPrevexptoDate = $aBikedata['odfromdate'];
                    $sPrevexpfDate = $aBikedata['odtodate'];
                    // $toDate = Carbon::parse($sPrevexptoDate);
                    $fromDate = Carbon::parse($sPrevexpfDate);
                    // $StartDate = strtoupper($sPrevexptoDate->format('d-M-Y'));
                    // $EndDate = strtoupper($sPrevexpfDate->format('d-M-Y'));
                    $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    $PolicyToDate = $today->addYear()->subDay()->format('d-M-Y');
                    // $firstregdate = $today->format('Y-m-d');

                } else if ($aBikedata['prepolitype'] == 'bundled') {
                    $sPrePolicyType = getconstant("MOTOR.BAJAJMOTOR.PRODUCTCODETWOWHEELER.NEWBUSSINESS");
                    $bikedate = $aBikedata['bikeregdate'];
                    $year = $aBikedata['brandyear'];
                    $regdate = Carbon::parse($bikedate);
                    // $year = Carbon::today()->year;
                    // $sPrevexptoDate = $aBikedata['bdfromdate'];
                    $sPrevexpfDate = $aBikedata['bdtptodate'];
                    // $toDate = Carbon::parse($sPrevexptoDate);
                    $fromDate = Carbon::parse($sPrevexpfDate);
                    // $StartDate = strtoupper($toDate->format('d-M-Y'));
                    $EndDate = strtoupper($fromDate->format('d-M-Y'));

                    $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    $PolicyToDate = $today->addYear()->subDay()->format('d-M-Y');

                } elseif ($aBikedata['prepolitype'] == 'comprehensive') {
                    $sPrePolicyType = getconstant("MOTOR.BAJAJMOTOR.PRODUCTCODETWOWHEELER.COMPREHENSIVE");
                    $bikedate = $aBikedata['bikeregdate'];
                    $year = $aBikedata['brandyear'];
                    // $year = Carbon::today()->year;
                    $regdate = Carbon::parse($bikedate);
                    $bikeregdata = strtoupper($regdate->format('d-M-Y'));
                    // $sPrevexptoDate = $aBikedata['compfromdate'];
                    $sPrevexpfDate = $aBikedata['comptodate'];
                    // $toDate = Carbon::parse($sPrevexptoDate);
                    $fromDate = Carbon::parse($sPrevexpfDate);

                    // $StartDate = strtoupper($toDate->format('d-M-Y'));
                    $EndDate = strtoupper($fromDate->format('d-M-Y'));

                    $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    $PolicyToDate = $today->addYear()->subDay()->format('d-M-Y');
                } elseif ($aBikedata['prepolitype'] == 'tponly') {
                    $sPrePolicyType = getconstant("MOTOR.BAJAJMOTOR.PRODUCTCODETWOWHEELER.TPONLY");
                    $bikedate = $aBikedata['bikeregdate'];
                    $year = $aBikedata['brandyear'];
                    $regdate = Carbon::parse($bikedate);
                    $bikeregdata = strtoupper($regdate->format('d-M-Y'));
                    // $sPrevexptoDate = $aBikedata['tpfromdate'];
                    $sPrevexpfDate = $aBikedata['tptodate'];
                    // $toDate = Carbon::parse($sPrevexptoDate);
                    $fromDate = Carbon::parse($sPrevexpfDate);
                    // $StartDate = strtoupper($toDate->format('d-M-Y'));
                    $EndDate = strtoupper($fromDate->format('d-M-Y'));

                    $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    $PolicyToDate = $today->addYear()->subDay()->format('d-M-Y');
                }
            }


            $regnumber = substr($regno, 0, 4);

            $rto = RtoMaster::where('rtacode', $regnumber)->first();

            $sState = $rto->city;
            $state = $rto->state;
            $getstate = RtaMaster::where('state', $state)->first();
            $zone = $getstate->zone;

            $password = self::$password;
            $Id = self::$Id;
            $vehicletypecode = self::$vehicletypecode;
            $deptcode = self::$deptcode;
            $claim = array_key_exists('bonus-button', $aBikedata) ? $aBikedata['bonus-button'] : '0';

            $cacheaddon = RedisGet('bajajflag_bike:' . $userId);
            $addons = !empty($cacheaddon) ? $cacheaddon : "";

            $post = [
                "userid" => $Id,
                "password" => $password,
                "vehiclecode" => "38185824",
                "city" => $sState,
                "weomotpolicyin" => [
                    "contractid" => "0",
                    "poltype" => $policyType,
                    "product4digitcode" => $sPrePolicyType,
                    "deptcode" => $deptcode,
                    "branchcode" => "1607",
                    "termstartdate" => $PolicyFromDate, //$StartDate,
                    "termenddate" => $PolicyToDate, //$EndDate,
                    "tpfintype" => "0",
                    "hypo" => "", //State Bank of India",
                    "vehicletypecode" => $vehicletypecode,
                    "vehicletype" => "TWO WHEELER",
                    "miscvehtype" => "0",
                    "vehiclemakecode" => "204",
                    "vehiclemake" => "HERO HONDA",
                    "vehiclemodelcode" => "8",
                    "vehiclemodel" => "SPLENDOR",
                    "vehiclesubtypecode" => "18",
                    "vehiclesubtype" => "SPOKE DRUM KICK",
                    "fuel" => "P",
                    "zone" => $zone,
                    "engineno" => "",
                    "chassisno" => "",
                    "registrationno" => $regno, //"MH01", "NEW",
                    "registrationdate" => $bikeregdata ?? "", //"15-Nov-2025",
                    "registrationlocation" => $state,
                    "regilocother" => $state,
                    "carryingcapacity" => "2",
                    "cubiccapacity" => "110",
                    "yearmanf" => $year,
                    "color" => "", //"RED",
                    "vehicleidv" => "0", //$bikeidv ?? "0",
                    "ncb" => $claim ?? "0",
                    "addloading" => "0",
                    "addloadingon" => "0",
                    "spdiscrate" => "0",
                    "elecacctotal" => "0",
                    "nonelecacctotal" => "0",
                    "prvpolicyref" => "",
                    "prvexpirydate" => $EndDate ?? "",
                    "prvinscompany" => "0",
                    "prvncb" => !empty($claim) ? 1 : 0,
                    "prvclaimstatus" => "0",
                    "automembership" => "0",
                    "partnertype" => "P"
                ],
                "accessorieslist" => [
                    [
                        "contractid" => "0",
                        "acccategorycode" => "0",
                        "acctypecode" => "0",
                        "accmake" => "0",
                        "accmodel" => "0",
                        "acciev" => "0",
                        "acccount" => "0"
                    ]
                ],
                "paddoncoverlist" => [
                    [
                        "paramdesc" => "",
                        "paramref" => ""
                    ]
                ],
                "motextracover" => [
                    "geogextn" => "0",
                    "noofpersonspa" => "0",
                    "suminsuredpa" => "0",
                    "suminsuredtotalnamedpa" => "0",
                    "cngvalue" => "0",
                    "noofemployeeslle" => "0",
                    "noofpersonsllo" => "0",
                    "fibreglassvalue" => "0",
                    "sidecarvalue" => "0",
                    "nooftrailers" => "0",
                    "totaltrailervalue" => "0",
                    "voluntaryexcess" => "0",
                    "covernoteno" => "",
                    "covernotedate" => "",
                    "subimdcode" => "",
                    "extrafield1" => "",
                    "extrafield2" => "",
                    "extrafield3" => ""
                ],
                "questlist" => [
                    [
                        "questionref" => "",
                        "contractid" => "",
                        "questionval" => ""
                    ]
                ],
                "detariffobj" => [
                    "vehpurchasetype" => "",
                    "vehpurchasedate" => "",
                    "monthofmfg" => "",
                    "registrationauth" => "",
                    "bodytype" => "",
                    "goodstranstype" => "",
                    "natureofgoods" => "",
                    "othergoodsfrequency" => "",
                    "permittype" => "",
                    "roadtype" => "",
                    "vehdrivenby" => "",
                    "driverexperience" => "",
                    "clmhistcode" => "",
                    "incurredclmexpcode" => "",
                    "driverqualificationcode" => "",
                    "tacmakecode" => "",
                    "extcol1" => "",
                    "extcol2" => "",
                    "extcol3" => "",
                    "extcol4" => "",
                    "extcol5" => "",
                    "extcol6" => "",
                    "extcol7" => "",
                    "extcol8" => "",
                    "extcol9" => "",
                    "extcol10" => $addons, //"DRIVE_ASSURE_SILVER",
                    "extcol11" => "", //"1",
                    "extcol12" => "",
                    "extcol13" => "",
                    "extcol14" => "",
                    "extcol15" => "",
                    "extcol16" => "",
                    "extcol17" => "",
                    "extcol18" => "",
                    "extcol19" => "",
                    "extcol20" => "",
                    "extcol21" => "",
                    "extcol22" => "",
                    "extcol23" => "",
                    "extcol24" => "",
                    "extcol25" => "",
                    "extcol26" => "", //"12345",
                    "extcol27" => "",
                    "extcol28" => "",
                    "extcol29" => "",
                    "extcol30" => "",
                    "extcol31" => "",
                    "extcol32" => "",
                    "extcol33" => "",
                    "extcol34" => "",
                    "extcol35" => "",
                    "extcol36" => "",
                    "extcol37" => "",
                    "extcol38" => "",
                    "extcol39" => "",
                    "extcol40" => ""
                ],
                "transactionid" => "",
                "transactiontype" => "MOTOR_WEBSERVICE",
                "contactno" => ""
            ];
            // return $post;

            $postJson = json_encode($post);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://webservicesint.bajajallianz.com/BjazMotorWebservice/calculatemotorpremiumsig',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $postJson,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                ),
            ));

            $response = curl_exec($curl);
            // \Log::info(['generateBikeQuote_BAJAJ' => $response]);
            curl_close($curl);
            return $response;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public static function generateBikeProposal(Request $request)
    {
        try {
            $userId = $request->userid;
            $user = User::find($userId);
            $AuthUser = $user->toArray();
            // $today = now();
            $today = Carbon::today();
            // $AuthUser = User::find($userId)->toArray();
            $aData = DataModel::where('userid', $userId)->first();
            // $oJourneyData = MotorJourney::where('userid', $userId)->where('is_bike', '1')->first();
            $oJourneyData = MotorJourney::where('userid', $userId)
                ->where('is_bike', '1')
                ->where('vid', getconstant("MOTOR.BAJAJMOTOR.KEY"))
                ->first();

            $prevPolicydata = json_decode($oJourneyData->pre_policy_details, true);

            $HypothData = json_decode($oJourneyData->bank_details, true);
            $aBikedata = json_decode($aData->knowbike_reg_details, true);
            $aNewBikedata = json_decode($aData->newbike_reg_details, true);


            $aResult = [];
            if (!empty($aAccessories)) {
                foreach ($aAccessories as $item) {
                    $aResult[$item['type']] = $item['amount'];

                }
            }
            $PAcover = $aData->pacover;
            // if ($PAcover == 0) {
            //     $pAcoverReason = json_decode($aData->pacover_reason, true);
            //     //$ncoverReason = array_keys($pAcoverReason)[0];
            //     $ncoverReason = is_array($pAcoverReason) ? (array_keys($pAcoverReason)[0] ?? []) : [];

            //     if ($ncoverReason == '1') {
            //         $pAcoverReason = "PA_TYPE1";
            //     }
            //     if ($ncoverReason == '2') {
            //         $pAcoverReason = "PA_TYPE2";
            //     }
            //     if ($ncoverReason == '3') {
            //         $pAcoverReason = "PA_TYPE4";
            //     }

            // } else {
            //     $pAcoverReason = "";
            // }


            // //dd(session('motortype'));
            $sRegdate = $aBikedata['bikeregdate'] ?? date('d-m-Y');
            $cachemotortype = 'cache_motortype_' . $userId;

            if (GetCache($cachemotortype) == "newbike") {
                $regno = 'NEW';
                $policyType = getconstant("MOTOR.BAJAJMOTOR.POLICYTYPE.NB");
                $sPrePolicyType = getconstant("MOTOR.BAJAJMOTOR.PRODUCTCODETWOWHEELER.NEWBUSSINESS");
                // $aNewBikedata = json_decode($oDataModel->newbike_reg_details, true);
                $year = $aNewBikedata['brandyear'];
                //$today = Carbon::today();
                $PolicyFromDate = $today->addDay()->format('d-M-Y');
                $PolicyToDate = $today->addYears(5)->subDay()->format('d-M-Y');

            }

            if (GetCache($cachemotortype) == 'knowbike') {
                $policyType = getconstant("MOTOR.BAJAJMOTOR.POLICYTYPE.OTHER");
                // $aBikedata = json_decode($oDataModel->knowbike_reg_details, true) ?? [];
                $regno = $aData->bikenumber;
                if ($aBikedata['prepolitype'] == 'odonly') {
                    $sPrePolicyType = getconstant("MOTOR.BAJAJMOTOR.PRODUCTCODETWOWHEELER.ODONLY");
                    $bikedate = $aBikedata['bikeregdate'];
                    $year = $aBikedata['brandyear'];
                    $regdate = Carbon::parse($bikedate);
                    $bikeregdata = strtoupper($regdate->format('d-M-Y'));
                    $sPrevexpfDate = $aBikedata['odtodate'];
                    $fromDate = Carbon::parse($sPrevexpfDate);

                    $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    $PolicyToDate = $today->addYear()->subDay()->format('d-M-Y');

                    $prevInsuranceId = $prevPolicydata['prevInsuranceId'] ?? null;
                    $policynumber = $prevPolicydata['policynumber'] ?? null;
                    $InsurCmpny = null;
                    if (!empty($prevInsuranceId)) {
                        $InsurCmpny = BajajPrevInsurence::where('id', $prevInsuranceId)->value('company_code');
                    }
                } else if ($aBikedata['prepolitype'] == 'bundled') {
                    $sPrePolicyType = getconstant("MOTOR.BAJAJMOTOR.PRODUCTCODETWOWHEELER.NEWBUSSINESS");
                    $bikedate = $aBikedata['bikeregdate'];
                    $year = $aBikedata['brandyear'];
                    $regdate = Carbon::parse($bikedate);

                    $sPrevexpfDate = $aBikedata['bdtptodate'];

                    $fromDate = Carbon::parse($sPrevexpfDate);

                    $EndDate = strtoupper($fromDate->format('d-M-Y'));

                    $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    $PolicyToDate = $today->addYear()->subDay()->format('d-M-Y');

                    $prevInsuranceId = $prevPolicydata['prevInsuranceId'] ?? null;
                    $policynumber = "";
                    $InsurCmpny = "";

                    // $nTpolicyNo = array_key_exists('tppolicynumber', $prevPolicydata) ? $prevPolicydata['tppolicynumber'] : '';
                    // $sTpInsurer = array_key_exists('tpprevInsurance', $prevPolicydata) ? $prevPolicydata['tpprevInsurance'] : '';
                    // $tpInsurCmpny = BajajPrevInsurence::select('id', $sTpInsurer)->first()->company_name;

                } elseif ($aBikedata['prepolitype'] == 'comprehensive') {
                    $sPrePolicyType = getconstant("MOTOR.BAJAJMOTOR.PRODUCTCODETWOWHEELER.COMPREHENSIVE");
                    $bikedate = $aBikedata['bikeregdate'];
                    $year = $aBikedata['brandyear'];

                    $regdate = Carbon::parse($bikedate);
                    $bikeregdata = strtoupper($regdate->format('d-M-Y'));

                    $sPrevexpfDate = $aBikedata['comptodate'];

                    $fromDate = Carbon::parse($sPrevexpfDate);
                    $EndDate = strtoupper($fromDate->format('d-M-Y'));

                    $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    $PolicyToDate = $today->addYear()->subDay()->format('d-M-Y');

                    $prevInsuranceId = $prevPolicydata['prevInsuranceId'] ?? null;
                    $policynumber = $prevPolicydata['policynumber'] ?? null;
                    $InsurCmpny = null;
                    if (!empty($prevInsuranceId)) {
                        $InsurCmpny = BajajPrevInsurence::where('id', $prevInsuranceId)->value('company_code');
                    }

                } elseif ($aBikedata['prepolitype'] == 'tponly') {
                    $sPrePolicyType = getconstant("MOTOR.BAJAJMOTOR.PRODUCTCODETWOWHEELER.TPONLY");
                    $bikedate = $aBikedata['bikeregdate'];
                    $year = $aBikedata['brandyear'];
                    $regdate = Carbon::parse($bikedate);
                    $bikeregdata = strtoupper($regdate->format('d-M-Y'));
                    $sPrevexpfDate = $aBikedata['tptodate'];
                    $fromDate = Carbon::parse($sPrevexpfDate);
                    $EndDate = strtoupper($fromDate->format('d-M-Y'));

                    $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    $PolicyToDate = $today->addYear()->subDay()->format('d-M-Y');
                    $prevInsuranceId = $prevPolicydata['prevInsuranceId'] ?? null;
                    $policynumber = $prevPolicydata['policynumber'] ?? null;
                    $InsurCmpny = null;
                    if (!empty($prevInsuranceId)) {
                        $InsurCmpny = BajajPrevInsurence::where('id', $prevInsuranceId)->value('company_code');
                    }
                }
            }

            // return $EndDate;


            // //$oVehicleInfo = Vehicle_Info::where('MODEL_DESCRIPTION', $sModel)->first();
            $sRegNumber = "";
            
            $aNominee = $oJourneyData->nominee_details ? jdec($oJourneyData->nominee_details) : [];
            $permanentAddress = json_decode($oJourneyData->permanent_address, true) ?? [];
            $pincode = $permanentAddress['pincode'];


            // $state = RtaMaster::where('pincode', $pincode)->first();
            // $sState = $state->state;
            // $zone = $state->zone;

            $regnumber = substr($regno, 0, 4);

            $rto = RtoMaster::where('rtacode', $regnumber)->first();

            $sState = $rto->city;
            $state = $rto->state;
            $getstate = RtaMaster::where('state', $state)->first();
            $zone = $getstate->zone;

            $nPlanType = $aData->bike_plan_type;

            $cacheBikeIdv = RedisGet('bajajbike_idv:' . $userId);
            $bikeidv = !empty($cacheBikeIdv) ? $cacheBikeIdv : "0";
            $randomValue = self::genRandomNumber();
            $EngineNo = "GDFG59D4GD6546D" . $randomValue;
            $ChassisNo = "GDF45GDFGD4G56D" . $randomValue;


            // $sRtocity = getBikeRtocity($sRegNumber);
            // if ($sRtocity) {
            //     $sRtocity = !empty($sRtocity->RTOCITY) ? $sRtocity->RTOCITY : $sRtocity->RTONAME;
            // } else {
            //     $sRtocity = "";
            // }
            $user = User::find($userId);



            $dob = !empty($oJourneyData->dob)
                ? date("d-M-Y", strtotime($oJourneyData->dob))
                : (!empty($user->dob)
                    ? date("d-M-Y", strtotime($user->dob))
                    : date("d-M-Y", strtotime("2000-04-29")));



            // $StartDate = strtoupper($today->format('d-M-Y'));
            // $EndDate = strtoupper($today->addYear()->subDay()->format('d-M-Y'));
            $password = self::$password;
            $Id = self::$Id;
            $vehicletypecode = self::$vehicletypecode;
            $deptcode = self::$deptcode;

            $cacheTransicationId = RedisGet('transactionid_bike:' . $userId);
            $TransactionId = $cacheTransicationId;

            $cachebajajidv = RedisGet('bajajbike_idv:' . $userId);
            $bikeidv = $cachebajajidv;

            $hypo = array_key_exists('bankloantype', $HypothData) ? $HypothData['bankloantype'] : '';

            $claim = array_key_exists('bonus-button', $aBikedata) ? $aBikedata['bonus-button'] : '0';

            $cacheaddon = RedisGet('bajajflag_bike:' . $userId);
            $addons = !empty($cacheaddon) ? $cacheaddon : "";

            $nameParts = self::splitFullName($AuthUser['name'] ?? "");

            $post = [
                "userid" => $Id,
                "password" => $password,
                "transactionid" => $TransactionId,
                "rcptlist" => [],
                "custdetails" => [
                    "parttempid" => "",
                    "firstname" => $nameParts['firstname'] ?? "",
                    "middlename" => $nameParts['middlename'] ?? "",
                    "surname" => $nameParts['surname'] ?? "",
                    "addline1" => $permanentAddress['address1'] ?? "PLOT NO H5 ADC FLAT NO 9",
                    "addline2" => $permanentAddress['address2'] ?? "UDAY GARDEN RESIDENCY",
                    "addline3" => $permanentAddress['landmark'] ?? "Pune",
                    "addline5" => $sState ?? "MAHARASHTRA",
                    "pincode" => $pincode ?? $permanentAddress['pincode'], //"411001",
                    "email" => $AuthUser['email'] ?? "pankaj@bajajallianz.co.in",
                    "telephone1" => "",
                    "telephone2" => "",
                    "mobile" => $AuthUser['mobile'] ?? "9637384353",
                    "delivaryoption" => "",
                    "poladdline1" => "",
                    "poladdline2" => "",
                    "poladdline3" => "",
                    "poladdline5" => "",
                    "polpincode" => "",
                    "password" => "newpas12",
                    "cptype" => "P",
                    "profession" => "",
                    "dateofbirth" => $dob ?? "12-SEP-1980",
                    "availabletime" => "",
                    "institutionname" => null,
                    "existingyn" => "N",
                    "loggedin" => "",
                    "mobilealerts" => "",
                    "emailalerts" => "",
                    "title" => $oJourneyData->gender ?? "",
                    "partid" => "",
                    "status1" => "",
                    "status2" => "",
                    "status3" => ""
                ],
                "weomotpolicyin" => [
                    "contractid" => "0",
                    "poltype" => $policyType,
                    "product4digitcode" => $sPrePolicyType,
                    "deptcode" => $deptcode,
                    "branchcode" => "1607",
                    "termstartdate" => $PolicyFromDate,
                    "termenddate" => $PolicyToDate,
                    "tpfintype" => !empty($hypo) ? 1 : 0,
                    "hypo" => $hypo ?? "",
                    "vehicletypecode" => "21",
                    "vehicletype" => "TWO WHEELER",
                    "miscvehtype" => "0",
                    "vehiclemakecode" => "205",
                    "vehiclemake" => "HONDA",
                    "vehiclemodelcode" => "1",
                    "vehiclemodel" => "ACTIVA",
                    "vehiclesubtypecode" => "24",
                    "vehiclesubtype" => "3G 110 CC",
                    "fuel" => "P",
                    "zone" => $zone,
                    "engineno" => $EngineNo ?? '',
                    "chassisno" => $ChassisNo ?? '',
                    "registrationno" => $regno ?? '',
                    "registrationdate" => $bikeregdata ?? "",
                    "registrationlocation" => $state,
                    "regilocother" => $state,
                    "carryingcapacity" => "2",
                    "cubiccapacity" => "110",
                    "yearmanf" => $year,
                    "color" => "",
                    "vehicleidv" => $bikeidv,
                    "ncb" => $claim ?? "0",
                    "addloading" => "0",
                    "addloadingon" => "0",
                    "spdiscrate" => "0",
                    "elecacctotal" => "0",
                    "nonelecacctotal" => "0",
                    "prvpolicyref" => $policynumber ?? "",
                    "prvexpirydate" => $EndDate,
                    "prvinscompany" => $InsurCmpny ?? "0",
                    "prvncb" => !empty($claim) ? 1 : 0,
                    "prvclaimstatus" => "0",
                    "automembership" => "0",
                    "partnertype" => "P"
                ],
                "accessorieslist" => [
                    [
                        "contractid" => "0",
                        "acccategorycode" => "0",
                        "acctypecode" => "0",
                        "accmake" => "0",
                        "accmodel" => "",
                        "acciev" => "0",
                        "acccount" => "0"
                    ]
                ],
                "paddoncoverlist" => [
                    [
                        "paramdesc" => null,
                        "paramref" => null
                    ]
                ],
                "motextracover" => [
                    "geogextn" => "0",
                    "noofpersonspa" => null,
                    "suminsuredpa" => null,
                    "suminsuredtotalnamedpa" => null,
                    "cngvalue" => "0",
                    "noofemployeeslle" => "0",
                    "noofpersonsllo" => "0",
                    "fibreglassvalue" => "0",
                    "sidecarvalue" => "0",
                    "nooftrailers" => "0",
                    "totaltrailervalue" => "0",
                    "voluntaryexcess" => "0",
                    "covernoteno" => "",
                    "covernotedate" => "",
                    "subimdcode" => "",
                    "extrafield1" => "",
                    "extrafield2" => "",
                    "extrafield3" => ""
                ],
                "premiumdetails" => [
                    "ncbamt" => "0",
                    "addloadprem" => "0",
                    "totalodpremium" => "0",
                    "totalactpremium" => "0",
                    "totalnetpremium" => "0",
                    "totalpremium" => "0",
                    "netpremium" => "0",
                    "finalpremium" => "0",
                    "spdisc" => "0",
                    "servicetax" => "0",
                    "stampduty" => "0",
                    "collpremium" => "0",
                    "imtout" => "",
                    "totaliev" => "0"
                ],
                "premiumsummerylist" => [
                    [
                        "paramdesc" => "0",
                        "paramref" => "0",
                        "paramtype" => "0",
                        "od" => "0",
                        "act" => "0",
                        "net" => "0"
                    ]
                ],
                "questlist" => [
                    [
                        "questionref" => "",
                        "contractid" => "",
                        "questionval" => ""
                    ]
                ],
                "detariffobj" => [
                    "vehpurchasetype" => "",
                    "vehpurchasedate" => "",
                    "monthofmfg" => "",
                    "registrationauth" => "",
                    "bodytype" => "",
                    "goodstranstype" => "",
                    "natureofgoods" => "",
                    "othergoodsfrequency" => "",
                    "permittype" => "",
                    "roadtype" => "",
                    "vehdrivenby" => "",
                    "driverexperience" => "",
                    "clmhistcode" => "",
                    "incurredclmexpcode" => "",
                    "driverqualificationcode" => "",
                    "tacmakecode" => "",
                    "extcol1" => "",
                    "extcol2" => "",
                    "extcol3" => "",
                    "extcol4" => "",
                    "extcol5" => "",
                    "extcol6" => "",
                    "extcol7" => "",
                    "extcol8" => "",
                    "extcol9" => "",
                    "extcol10" => $addons,
                    "extcol11" => "",
                    "extcol12" => "",
                    "extcol13" => "",
                    "extcol14" => "",
                    "extcol15" => "",
                    "extcol16" => "",
                    "extcol17" => "",
                    "extcol18" => "",
                    "extcol19" => "",
                    "extcol20" => "",
                    "extcol21" => "",
                    "extcol22" => "",
                    "extcol23" => "",
                    "extcol24" => "",
                    "extcol25" => "",
                    "extcol26" => "", //"12345",
                    "extcol27" => "",
                    "extcol28" => "",
                    "extcol29" => "",
                    "extcol30" => "",
                    "extcol31" => "",
                    "extcol32" => "",
                    "extcol33" => "",
                    "extcol34" => "",
                    "extcol35" => "",
                    "extcol36" => "",
                    "extcol37" => "",
                    "extcol38" => "",
                    "extcol39" => "",
                    "extcol40" => ""
                ],
                "potherdetails" => [
                    "imdcode" => "",
                    "covernoteno" => "",
                    "leadno" => "",
                    "ccecode" => "",
                    "runnercode" => "",
                    "extra1" => "",
                    "extra2" => "",
                    "extra3" => "",
                    "extra4" => "",
                    "extra5" => ""
                ],
                "premiumpayerid" => "0",
                "paymentmode" => "CC"
            ];

            // return $post;

            $postdata = json_encode($post);


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://webservicesint.bajajallianz.com/BjazMotorWebservice/issuepolicy',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $postdata,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                ),
            ));

            $response = curl_exec($curl);
            \Log::info(['generateBikeProposal_bajaj' => $response]);
            curl_close($curl);
            return $response;
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public static function TpgenerateBikeQuote($userId)
    {
        try {
            //$userId = $request->userid;

            $user = User::find($userId);
            $today = Carbon::today();
            // self::initlize();

            $cachemotortype = 'cache_motortype_' . $userId;
            $oDataModel = DataModel::where('userid', $userId)->first();
            $nPlanType = $oDataModel->bike_plan_type;
            // return [
            //     "plantype" => $nPlanType,
            //     "cachemotortype" => $cachemotortype
            // ];
            $cachebikePolicyexp = 'cache_bikepolicyexp_' . $userId;
            // $PAforUnnamedamount = json_decode($aData->bikeaddonvalue, true);
            // $PAforUnnamedamount = !empty($PAforUnnamedamount) ? $PAforUnnamedamount : "0";
            // //$aAddons = json_decode($aData->bikeaddon, true) ?? [];
            // $aBikeAddon = is_string($aData->bikeaddon)
            //     ? json_decode($aData->bikeaddon, true)
            //     : (array) $aData->bikeaddon;


            if (GetCache($cachemotortype) == "newcar") {
                $regno = 'NEW';
                $policyType = getconstant("MOTOR.BAJAJMOTOR.POLICYTYPE.NB");
                if ($nPlanType == '2') {
                    // $sPolicyType = getconstant("MOTOR.ZUNO.POLICYTYPE.BUNDLED");
                    $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    $PolicyToDate = $today->addYears(5)->subDay()->format('d-M-Y');
                    $sPrePolicyType = getconstant("MOTOR.BAJAJMOTOR.PRODUCTCODETWOWHEELER.NEWBUSSINESS");
                }
            }


            if (GetCache($cachemotortype) == "knowbike") {
                //     $sProposalType = getconstant("MOTOR.SHRIRAM.PROPOSALTYPE.MARKETRENEWAL");

                $policyType = getconstant("MOTOR.BAJAJMOTOR.POLICYTYPE.OTHER");

                if ($nPlanType == '3' && (GetCache($cachebikePolicyexp) == "Expired")) {
                    //         $sPolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.LIABILITY");
                    $sPrePolicyType = getconstant("MOTOR.BAJAJMOTOR.PRODUCTCODETWOWHEELER.TPONLY");
                    //         $PolicyFromDate = $today->format('d-m-Y');
                    //         $PolicyToDate = $today->addYear()->subDay()->format('d-m-Y');
                    $PolicyFromDate = $today->format('d-M-Y');
                    $PolicyToDate = $today->addYear()->subDay()->format('d-M-Y');
                    //         $LLtoPaidDriverYN = in_array('116', $aAddons) ? "1" : "0";
                    //         $NoEmpCoverLL = in_array('122', $aAddons) ? "1" : "0";
                    //         $LimitedTPPDYN = in_array('121', $aAddons) ? "1" : "0";
                    //         $Geographical = in_array('118', $aAddons) ? 1 : "0";
                    //         $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
                }
                if ($nPlanType == '3' && (GetCache($cachebikePolicyexp) == "Not Expired")) {
                    //         $sPolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.LIABILITY"); //tp
                    $sPrePolicyType = getconstant("MOTOR.BAJAJMOTOR.PRODUCTCODETWOWHEELER.TPONLY");
                    //         $LLtoPaidDriverYN = in_array('116', $aAddons) ? "1" : "0";
                    //         $NoEmpCoverLL = in_array('122', $aAddons) ? "1" : "0";
                    //         $LimitedTPPDYN = in_array('121', $aAddons) ? "1" : "0";
                    //         $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
                    //         $Geographical = in_array('118', $aAddons) ? 1 : "0";
                }
            }


            // carregdate
            // brandyear
            // compfromdate
            // comptodate

            // $sRtocity = getBikeRtocityApi($request, $sRegNumber);
            // if ($sRtocity) {
            //     $sRtocity = $sRtocity->RTOCITY ?? $sRtocity->RTONAME;
            // } else {
            //     $sRtocity = "";
            // }
            // //$idv = Cache::store('mysql_cache')->get("user_" . $userId . "_bikeidv");
            // $cachebikeidv = 'cache_' . $userId . '_bikeidv';
            // $idv = GetCache($cachebikeidv);
            // $url = MasterAPI::where('apicode', '115')->first()->apistring;
            // $curl = curl_init();

            // //dd($sProposalType,$nPlanType,$sRegNo4,$sRegNo3, $sRegNo2,$sRegNo1);


            $pincode = $user->pincode;
            // $state = RtaMaster::where('pincode', $pincode)->first();
            // if (!$state) {
            //     return [
            //         'status' => '0',
            //         'message' => 'Pincode not available for this service.'
            //     ];
            // }
            // $sState = $state->city;
            // $zone = $state->zone;

            $regnumber = substr($regno, 0, 4);

            $rto = RtoMaster::where('rtacode', $regnumber)->first();


            // if (!$state) {
            //     return ['status' => '0', 'message' => 'Pincode not available for this service.'];
            // }

            // $cacheaddon = RedisGet('bajajflag_car:' . $userId);
            // $addons = !empty($cacheaddon) ? $cacheaddon : "";

            // return $addon;

            $sState = $rto->city;
            $state = $rto->state;
            $getstate = RtaMaster::where('state', $state)->first();
            $zone = $getstate->zone;

            $cacheBikeIdv = RedisGet('bajajbike_idv:' . $userId);
            $bikeidv = !empty($cacheBikeIdv) ? $cacheBikeIdv : "0";

            if (GetCache($cachemotortype) == 'knowbike') {
                $policyType = getconstant("MOTOR.BAJAJMOTOR.POLICYTYPE.OTHER");
                $aBikedata = json_decode($oDataModel->knowbike_reg_details, true) ?? [];
                $regno = $oDataModel->bikenumber;
                if ($aBikedata['prepolitype'] == 'tponly') {
                    $sPrePolicyType = getconstant("MOTOR.BAJAJMOTOR.PRODUCTCODETWOWHEELER.TPONLY");
                    $bikedate = $aBikedata['bikeregdate'];
                    $year = $aBikedata['brandyear'];
                    $regdate = Carbon::parse($bikedate);
                    $bikeregdata = strtoupper($regdate->format('d-M-Y'));
                    $sPrevexpfDate = $aBikedata['tptodate'];
                    $fromDate = Carbon::parse($sPrevexpfDate);
                    $EndDate = strtoupper($fromDate->format('d-M-Y'));
                    $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    $PolicyToDate = $today->addYear()->subDay()->format('d-M-Y');
                }
            }


            $vehicletypecode = self::$vehicletypecode;
            $deptcode = self::$deptcode;
            $claim = array_key_exists('bonus-button', $aBikedata) ? $aBikedata['bonus-button'] : '0';

            $cacheaddon = RedisGet('bajajflag_bike:' . $userId);
            $addons = !empty($cacheaddon) ? $cacheaddon : "";

            $post = [
                "userid" => "webservice@digibima.com",
                "password" => "test",
                "vehiclecode" => "38175792",
                "city" => $sState,
                "weomotpolicyin" => [
                    "contractid" => "0",
                    "poltype" => $policyType,
                    "product4digitcode" => $sPrePolicyType,
                    "deptcode" => $deptcode,
                    "branchcode" => "9906",
                    "termstartdate" => $PolicyFromDate, //$StartDate,
                    "termenddate" => $PolicyToDate, //$EndDate,
                    "tpfintype" => "0",
                    "hypo" => "", //State Bank of India",
                    "vehicletypecode" => $vehicletypecode,
                    "vehicletype" => "TWO WHEELER",
                    "miscvehtype" => "0",
                    "vehiclemakecode" => "205",
                    "vehiclemake" => "HONDA",
                    "vehiclemodelcode" => "1",
                    "vehiclemodel" => "ACTIVA",
                    "vehiclesubtypecode" => "24",
                    "vehiclesubtype" => "3G 110 CC",
                    "fuel" => "P",
                    "zone" => $zone,
                    "engineno" => "",
                    "chassisno" => "",
                    "registrationno" => $regno,
                    "registrationdate" => $bikeregdata ?? "",
                    "registrationlocation" => $state,
                    "regilocother" => $state,
                    "carryingcapacity" => "2",
                    "cubiccapacity" => "110",
                    "yearmanf" => $year,
                    "color" => "",
                    "vehicleidv" => "0",
                    "ncb" => $claim ?? "0",
                    "addloading" => "0",
                    "addloadingon" => "0",
                    "spdiscrate" => "0",
                    "elecacctotal" => "0",
                    "nonelecacctotal" => "0",
                    "prvpolicyref" => "",
                    "prvexpirydate" => $EndDate ?? "",
                    "prvinscompany" => "0",
                    "prvncb" => !empty($claim) ? 1 : 0,
                    "prvclaimstatus" => "0",
                    "automembership" => "0",
                    "partnertype" => "P"
                ],
                "accessorieslist" => [
                    [
                        "contractid" => "0",
                        "acccategorycode" => "0",
                        "acctypecode" => "0",
                        "accmake" => "0",
                        "accmodel" => "0",
                        "acciev" => "0",
                        "acccount" => "0"
                    ]
                ],
                "paddoncoverlist" => [
                    [
                        "paramdesc" => "",
                        "paramref" => ""
                    ]
                ],
                "motextracover" => [
                    "geogextn" => "0",
                    "noofpersonspa" => "0",
                    "suminsuredpa" => "0",
                    "suminsuredtotalnamedpa" => "0",
                    "cngvalue" => "0",
                    "noofemployeeslle" => "0",
                    "noofpersonsllo" => "0",
                    "fibreglassvalue" => "0",
                    "sidecarvalue" => "0",
                    "nooftrailers" => "0",
                    "totaltrailervalue" => "0",
                    "voluntaryexcess" => "0",
                    "covernoteno" => "",
                    "covernotedate" => "",
                    "subimdcode" => "",
                    "extrafield1" => "",
                    "extrafield2" => "",
                    "extrafield3" => ""
                ],
                "questlist" => [
                    [
                        "questionref" => "",
                        "contractid" => "",
                        "questionval" => ""
                    ]
                ],
                "detariffobj" => [
                    "vehpurchasetype" => "",
                    "vehpurchasedate" => "",
                    "monthofmfg" => "",
                    "registrationauth" => "",
                    "bodytype" => "",
                    "goodstranstype" => "",
                    "natureofgoods" => "",
                    "othergoodsfrequency" => "",
                    "permittype" => "",
                    "roadtype" => "",
                    "vehdrivenby" => "",
                    "driverexperience" => "",
                    "clmhistcode" => "",
                    "incurredclmexpcode" => "",
                    "driverqualificationcode" => "",
                    "tacmakecode" => "",
                    "extcol1" => "",
                    "extcol2" => "",
                    "extcol3" => "",
                    "extcol4" => "",
                    "extcol5" => "",
                    "extcol6" => "",
                    "extcol7" => "",
                    "extcol8" => "",
                    "extcol9" => "",
                    "extcol10" => $addons,
                    "extcol11" => "",
                    "extcol12" => "",
                    "extcol13" => "",
                    "extcol14" => "",
                    "extcol15" => "",
                    "extcol16" => "",
                    "extcol17" => "",
                    "extcol18" => "",
                    "extcol19" => "",
                    "extcol20" => "",
                    "extcol21" => "",
                    "extcol22" => "",
                    "extcol23" => "",
                    "extcol24" => "",
                    "extcol25" => "",
                    "extcol26" => "",
                    "extcol27" => "",
                    "extcol28" => "",
                    "extcol29" => "",
                    "extcol30" => "",
                    "extcol31" => "",
                    "extcol32" => "",
                    "extcol33" => "",
                    "extcol34" => "",
                    "extcol35" => "",
                    "extcol36" => "",
                    "extcol37" => "",
                    "extcol38" => "",
                    "extcol39" => "",
                    "extcol40" => ""
                ],
                "transactionid" => "",
                "transactiontype" => "MOTOR_WEBSERVICE",
                "contactno" => ""
            ];
            // return $post;
            $postJson = json_encode($post);
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.bagicpt.bajajallianz.com/ext/motor/aggregatorsrvc/BjazMotorWebservice/calculatemotorpremiumsig',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $postJson,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                ),
            ));
            $response = curl_exec($curl);
            // \Log::info(['generateBikeQuote_BAJAJ' => $response]);
            curl_close($curl);
            return $response;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public static function TpgenerateBikeProposal(Request $request)
    {
        try {
            $userId = $request->userid;
            $user = User::find($userId);
            $AuthUser = $user->toArray();

            $today = Carbon::today();
            // $AuthUser = User::find($userId)->toArray();
            $aData = DataModel::where('userid', $userId)->first();

            $oJourneyData = MotorJourney::where('userid', $userId)
                ->where('is_bike', '1')
                ->where('vid', getconstant("MOTOR.BAJAJMOTOR.KEY"))
                ->first();

            $prevPolicydata = json_decode($oJourneyData->pre_policy_details ?? '{}', true);
            
            $HypothData = json_decode($oJourneyData->bank_details ?? '{}', true);
            $aBikedata = json_decode($aData->knowbike_reg_details ?? '{}', true);

            $aResult = [];
            if (!empty($aAccessories)) {
                foreach ($aAccessories as $item) {
                    $aResult[$item['type']] = $item['amount'];

                }
            }
            $PAcover = $aData->pacover;

            // if ($PAcover == 0) {
            //     $pAcoverReason = json_decode($aData->pacover_reason, true);
            //     //$ncoverReason = array_keys($pAcoverReason)[0];
            //     $ncoverReason = is_array($pAcoverReason) ? (array_keys($pAcoverReason)[0] ?? []) : [];

            //     if ($ncoverReason == '1') {
            //         $pAcoverReason = "PA_TYPE1";
            //     }
            //     if ($ncoverReason == '2') {
            //         $pAcoverReason = "PA_TYPE2";
            //     }
            //     if ($ncoverReason == '3') {
            //         $pAcoverReason = "PA_TYPE4";
            //     }

            // } else {
            //     $pAcoverReason = "";
            // }


            // //dd(session('motortype'));
            // $sRegdate = $aBikedata['bikeregdate'] ?? date('d-m-Y');
            $cachemotortype = 'cache_motortype_' . $userId;


            if (GetCache($cachemotortype) == 'knowbike') {
                $policyType = getconstant("MOTOR.BAJAJMOTOR.POLICYTYPE.OTHER");
                // $aBikedata = json_decode($oDataModel->knowbike_reg_details, true) ?? [];
                $regno = $aData->bikenumber;
                if ($aBikedata['prepolitype'] == 'tponly') {
                    $sPrePolicyType = getconstant("MOTOR.BAJAJMOTOR.PRODUCTCODETWOWHEELER.TPONLY");
                    $bikedate = $aBikedata['bikeregdate'];
                    $year = $aBikedata['brandyear'];
                    $regdate = Carbon::parse($bikedate);
                    $bikeregdata = strtoupper($regdate->format('d-M-Y'));
                    $sPrevexpfDate = $aBikedata['tptodate'];
                    $fromDate = Carbon::parse($sPrevexpfDate);
                    $EndDate = strtoupper($fromDate->format('d-M-Y'));

                    $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    $PolicyToDate = $today->addYear()->subDay()->format('d-M-Y');
                    $prevInsuranceId = $prevPolicydata['prevInsuranceId'] ?? null;

                    $policynumber = $prevPolicydata['policynumber'] ?? null;
                    $InsurCmpny = null;
                    if (!empty($prevInsuranceId)) {
                        $InsurCmpny = BajajPrevInsurence::where('id', $prevInsuranceId)->value('company_code');
                    }
                }
            }

            // return $prevPolicydata['policynumber'];


            // //$oVehicleInfo = Vehicle_Info::where('MODEL_DESCRIPTION', $sModel)->first();
            $sRegNumber = "";
            // $sRegNo2 = "";
            // $sRegNo3 = "";
            // $sRegNo1 = "";
            // $sRegNo4 = "";
            // if (GetCache($cachemotortype) == 'newbike') {
            //     $sRegNumber = substr(explode('(', $aData->rtocode)[1], 0, -1);
            //     $sRegNo1 = substr(str_replace('-', '', $sRegNumber), 0, 2);//k
            //     $sRegNo2 = substr(str_replace('-', '', $sRegNumber), 2, 2);//k
            //     $oModel = Shriram_Vehicle_Master::where('id', $aNewBikedata['model'])->first();

            // } else {
            //     $sRegNumber = $aData->bikenumber;
            //     $sRegNo1 = substr($sRegNumber, 0, 2);//k
            //     $sRegNo2 = substr($sRegNumber, 2, 2);//k
            //     $sRegNo3 = substr($sRegNumber, 4, 2);//k
            //     $sRegNo4 = substr($sRegNumber, 6, 4);//k
            //     $oModel = Shriram_Vehicle_Master::where('id', $aBikedata['model'])->first();

            // }

            // $sVehicleCode = "";
            // if ($oModel) {
            //     $sVehicleCode = $oModel->VEHICLE_CODE;
            // }
            // //dd($sRegNumber);
            // $oObj = new ShriramBikeController();
            // $aDocument = UserMotorDescription::where('userid', $userId)->first('document');
            // $filePath = json_decode($aDocument->document, true);
            // $insurePhotoB64 = self::FileIntoBase64($filePath['insurephoto']);
            // $identityPhotoB64 = self::FileIntoBase64($filePath['identity']['identityfront']);
            // $addressPhotoB64 = self::FileIntoBase64($filePath['address']['addressfront']);
            // self::initlize();
            // $url = MasterAPI::where('apicode', '116')->first()->apistring;


            $aNominee = $oJourneyData->nominee_details ? jdec($oJourneyData->nominee_details) : [];
            $permanentAddress = json_decode($oJourneyData->permanent_address, true) ?? [];
            $pincode = $permanentAddress['pincode'];
            // $state = RtaMaster::where('pincode', $pincode)->first();
            // $sState = $state->state;
            // $zone = $state->zone;


            $regnumber = substr($regno, 0, 4);

            $rto = RtoMaster::where('rtacode', $regnumber)->first();


            // if (!$state) {
            //     return ['status' => '0', 'message' => 'Pincode not available for this service.'];
            // }

           

            $sState = $rto->city;
            $state = $rto->state;
            $getstate = RtaMaster::where('state', $state)->first();
            $zone = $getstate->zone;
            


            $nPlanType = $aData->bike_plan_type;


            $cacheBikeIdv = RedisGet('bajajbike_idv:' . $userId);
            $bikeidv = !empty($cacheBikeIdv) ? $cacheBikeIdv : "0";
            $randomValue = self::genRandomNumber();
            $EngineNo = "GDFG59D4GD" . $randomValue;
            $ChassisNo = "GDF45GDFGD" . $randomValue;


            // $sRtocity = getBikeRtocity($sRegNumber);
            // if ($sRtocity) {
            //     $sRtocity = !empty($sRtocity->RTOCITY) ? $sRtocity->RTOCITY : $sRtocity->RTONAME;
            // } else {
            //     $sRtocity = "";
            // }
            $user = User::find($userId);
            $dob = !empty($oJourneyData->dob)
                ? date("d-M-Y", strtotime($oJourneyData->dob))
                : (!empty($user->dob)
                    ? date("d-M-Y", strtotime($user->dob))
                    : date("d-M-Y", strtotime("2000-04-29")));

            // $StartDate = strtoupper($today->format('d-M-Y'));
            // $EndDate = strtoupper($today->addYear()->subDay()->format('d-M-Y'));
            $password = self::$password;
            $Id = self::$Id;
            $vehicletypecode = self::$vehicletypecode;
            $deptcode = self::$deptcode;

            $cacheTransicationId = RedisGet('transactionid_bike:' . $userId);
            $TransactionId = $cacheTransicationId;

            $cachebajajidv = RedisGet('bajajbike_idv:' . $userId);
            $bikeidv = $cachebajajidv;

            $hypo = array_key_exists('bankloantype', $HypothData) ? $HypothData['bankloantype'] : '';

            $claim = array_key_exists('bonus-button', $aBikedata) ? $aBikedata['bonus-button'] : '0';

            $cacheaddon = RedisGet('bajajflag_bike:' . $userId);
            $addons = !empty($cacheaddon) ? $cacheaddon : "";

            $nameParts = self::splitFullName($AuthUser['name'] ?? "");

            $post = [
                "userid" => "webservice@digibima.com",
                "password" => "test",
                "transactionid" => $TransactionId,
                "rcptlist" => [],
                "custdetails" => [
                    "parttempid" => "",
                    "firstname" => $nameParts['firstname'] ?? "",
                    "middlename" => $nameParts['middlename'] ?? "",
                    "surname" => $nameParts['surname'] ?? "",
                    "addline1" => $permanentAddress['address1'] ?? "PLOT NO H5 ADC FLAT NO 9",
                    "addline2" => $permanentAddress['address2'] ?? "UDAY GARDEN RESIDENCY",
                    "addline3" => $permanentAddress['landmark'] ?? "Pune",
                    "addline5" => $sState ?? "",
                    "pincode" => $pincode ?? $permanentAddress['pincode'], //"411001",
                    "email" => $AuthUser['email'] ?? "pankaj@bajajallianz.co.in",
                    "telephone1" => "",
                    "telephone2" => "",
                    "mobile" => $AuthUser['mobile'] ?? "9637384353",
                    "delivaryoption" => "",
                    "poladdline1" => "",
                    "poladdline2" => "",
                    "poladdline3" => "",
                    "poladdline5" => "",
                    "polpincode" => "",
                    "password" => "newpas12",
                    "cptype" => "P",
                    "profession" => "",
                    "dateofbirth" => $dob ?? "",
                    "availabletime" => "",
                    "institutionname" => null,
                    "existingyn" => "N",
                    "loggedin" => "",
                    "mobilealerts" => "",
                    "emailalerts" => "",
                    "title" => $oJourneyData->gender ?? "",
                    "partid" => "",
                    "status1" => "",
                    "status2" => "",
                    "status3" => ""
                ],
                "weomotpolicyin" => [
                    "contractid" => "0",
                    "poltype" => $policyType ?? '', //"1",
                    "product4digitcode" => $sPrePolicyType, //"1826",
                    "deptcode" => $deptcode,
                    "branchcode" => "1607",
                    "termstartdate" => $PolicyFromDate,
                    "termenddate" => $PolicyToDate,
                    "tpfintype" => !empty($hypo) ? 1 : 0, //"1",
                    "hypo" => $hypo ?? "", //"State Bank of India",
                    "vehicletypecode" => "21",
                    "vehicletype" => "TWO WHEELER",
                    "miscvehtype" => "0",
                    "vehiclemakecode" => "205",
                    "vehiclemake" => "HONDA",
                    "vehiclemodelcode" => "1",
                    "vehiclemodel" => "ACTIVA",
                    "vehiclesubtypecode" => "24",
                    "vehiclesubtype" => "3G 110 CC",
                    "fuel" => "P",
                    "zone" => $zone, //"A",
                    "engineno" => $EngineNo ?? '', //"RJ12FB7889",
                    "chassisno" => $ChassisNo ?? '', //"RJ12FB56783478889",
                    "registrationno" => $regno ?? '', //"NEW",
                    "registrationdate" => $bikeregdata ?? "", //"09-Nov-2025",
                    "registrationlocation" => $state,
                    "regilocother" => $state,
                    "carryingcapacity" => "2",
                    "cubiccapacity" => "110",
                    "yearmanf" => $year, //"2025",
                    "color" => "", //"RED",
                    "vehicleidv" => $bikeidv, //"49090",
                    "ncb" => $claim ?? "0",
                    "addloading" => "0",
                    "addloadingon" => "0",
                    "spdiscrate" => "0",
                    "elecacctotal" => "0",
                    "nonelecacctotal" => "0",
                    "prvpolicyref" => $policynumber ?? "", //"23334343434344343",
                    "prvexpirydate" => $EndDate, //"26-Aug-2022",
                    "prvinscompany" => $InsurCmpny ?? "0",
                    "prvncb" => !empty($claim) ? 1 : 0,
                    "prvclaimstatus" => "0",
                    "automembership" => "0",
                    "partnertype" => "P"
                ],
                "accessorieslist" => [
                    [
                        "contractid" => "0",
                        "acccategorycode" => "0",
                        "acctypecode" => "0",
                        "accmake" => "0",
                        "accmodel" => "",
                        "acciev" => "0",
                        "acccount" => "0"
                    ]
                ],
                "paddoncoverlist" => [
                    [
                        "paramdesc" => null,
                        "paramref" => null
                    ]
                ],
                "motextracover" => [
                    "geogextn" => "0",
                    "noofpersonspa" => null,
                    "suminsuredpa" => null,
                    "suminsuredtotalnamedpa" => null,
                    "cngvalue" => "0",
                    "noofemployeeslle" => "0",
                    "noofpersonsllo" => "0",
                    "fibreglassvalue" => "0",
                    "sidecarvalue" => "0",
                    "nooftrailers" => "0",
                    "totaltrailervalue" => "0",
                    "voluntaryexcess" => "0",
                    "covernoteno" => "",
                    "covernotedate" => "",
                    "subimdcode" => "",
                    "extrafield1" => "",
                    "extrafield2" => "",
                    "extrafield3" => ""
                ],
                "premiumdetails" => [
                    "ncbamt" => "0",
                    "addloadprem" => "0",
                    "totalodpremium" => "0",
                    "totalactpremium" => "0",
                    "totalnetpremium" => "0",
                    "totalpremium" => "0",
                    "netpremium" => "0",
                    "finalpremium" => "0",
                    "spdisc" => "0",
                    "servicetax" => "0",
                    "stampduty" => "0",
                    "collpremium" => "0",
                    "imtout" => "",
                    "totaliev" => "0"
                ],
                "premiumsummerylist" => [
                    [
                        "paramdesc" => "0",
                        "paramref" => "0",
                        "paramtype" => "0",
                        "od" => "0",
                        "act" => "0",
                        "net" => "0"
                    ]
                ],
                "questlist" => [
                    [
                        "questionref" => "",
                        "contractid" => "",
                        "questionval" => ""
                    ]
                ],
                "detariffobj" => [
                    "vehpurchasetype" => "",
                    "vehpurchasedate" => "",
                    "monthofmfg" => "",
                    "registrationauth" => "",
                    "bodytype" => "",
                    "goodstranstype" => "",
                    "natureofgoods" => "",
                    "othergoodsfrequency" => "",
                    "permittype" => "",
                    "roadtype" => "",
                    "vehdrivenby" => "",
                    "driverexperience" => "",
                    "clmhistcode" => "",
                    "incurredclmexpcode" => "",
                    "driverqualificationcode" => "",
                    "tacmakecode" => "",
                    "extcol1" => "",
                    "extcol2" => "",
                    "extcol3" => "",
                    "extcol4" => "",
                    "extcol5" => "",
                    "extcol6" => "",
                    "extcol7" => "",
                    "extcol8" => "",
                    "extcol9" => "",
                    "extcol10" => $addons,
                    "extcol11" => "",
                    "extcol12" => "",
                    "extcol13" => "",
                    "extcol14" => "",
                    "extcol15" => "",
                    "extcol16" => "",
                    "extcol17" => "",
                    "extcol18" => "",
                    "extcol19" => "",
                    "extcol20" => "",
                    "extcol21" => "",
                    "extcol22" => "",
                    "extcol23" => "",
                    "extcol24" => "",
                    "extcol25" => "",
                    "extcol26" => "", //"12345",
                    "extcol27" => "",
                    "extcol28" => "",
                    "extcol29" => "",
                    "extcol30" => "",
                    "extcol31" => "",
                    "extcol32" => "",
                    "extcol33" => "",
                    "extcol34" => "",
                    "extcol35" => "",
                    "extcol36" => "",
                    "extcol37" => "",
                    "extcol38" => "",
                    "extcol39" => "",
                    "extcol40" => ""
                ],
                "potherdetails" => [
                    "imdcode" => "",
                    "covernoteno" => "",
                    "leadno" => "",
                    "ccecode" => "",
                    "runnercode" => "",
                    "extra1" => "",
                    "extra2" => "",
                    "extra3" => "",
                    "extra4" => "",
                    "extra5" => ""
                ],
                "premiumpayerid" => "0",
                "paymentmode" => "CC"
            ];

            // return $post;

            $postdata = json_encode($post);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.bagicpt.bajajallianz.com/ext/motor/aggregatorsrvc/BjazMotorWebservice/issuepolicy',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $postdata,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                ),
            ));
            $response = curl_exec($curl);
            \Log::info(['generateBikeProposal_bajaj' => $response]);
            curl_close($curl);
            return $response;
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public static function splitFullName($fullName)
    {
        $parts = preg_split('/\s+/', trim($fullName));
        $count = count($parts);

        return [
            'firstname' => $parts[0] ?? '',
            'middlename' => $count > 2 ? implode(' ', array_slice($parts, 1, -1)) : '',
            'surname' => $count > 1 ? $parts[$count - 1] : ''
        ];
    }

}