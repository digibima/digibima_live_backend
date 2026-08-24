<?php
namespace App\Services\Api\BajajMotor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Shriram\{Shriram_Pincode, Shriram_planCheckout, Shriram_RTO_Master, Shriram_Prev_insurence, Shriram_Vehicle_Master};
use App\Models\{Master_Vehicle_Data as DataModel, MasterAPI, User, MotorJourney, MasterVendor, VendorMotor, MasterMotor, UserMotorDescription, Vehicle_Info};
use Illuminate\Support\Facades\{Auth, Cache};
use App\Models\Bajaj\RtaMaster;
// use App\Http\Controllers\Api\front\motor\Vendor\shriram\Car\ShriramCarController;

class BajajCarService
{
    private static $Id = "webservice.pos@digibima.com";
    private static $vehicletypecode = "22";
    private static $password = "Newpas12";
    private static $deptcode = "18";


    public static function generatePrivateCarQuote($userId)
    {
        try {

            //$userId = '142';
            // $userId = $request->userid;
            $user = User::find($userId);
            $today = Carbon::today();
            // $today = now();
            // self::initlize();
            // $oModel = null;
            // $claim = null;
            // $claimper = null;
            // $transferOfowner = null;
            // $firstregdate = null;
            // $PolicyFromDate = null;
            // $PolicyToDate = null;
            // $sPrevexptoDate = null;
            // $sPrevexpfDate = null;
            // $sPretpfDate = null;
            // $sPretptoDate = null;
            // $NilDepreciationCoverYN = null;
            // $RSACover = null;
            // $DailyExpRemYN = null;
            // $KeyReplacementYN = null;
            // $LossOfPersonBelongYN = null;
            // $EmergencyTranHotelExpRemYN = null;
            // $MultiCarBenefitYN = null;
            // $Eng_Protector = null;
            // $Consumables = null;
            // $InvReturnYN = null;
            // $LLtoPaidDriverYN = null;
            // $NoEmpCoverLL = null;
            // $SHRIMOTORPROTECTION_YN = null;
            // $LimitedTPPDYN = null;
            // $VoluntaryExcess = null;
            // $AntiTheftYN = null;
            // $Geographical = null;
            // $PAforUnnamedPassenger = null;
            // $PAPaidDriverConductorCleaner = null;
            // $cachepolicyExpiry = 'cache_policyExpiry_' . $userId;
            // //$nTpolicyNo = null;
            // $PAcover = null;
            // $pAcoverReason = null;
            // $VehicleType = null;
            // $under = "";
            // $AuthUser = $user->toArray();
            // $oJourneyData = MotorJourney::where('userid', $userId)->where('is_car', '1')->first();
            $aData = DataModel::where('userid', $userId)->first();
            // $PAforUnnamedamount = json_decode($aData->caraddonvalue, true);
            // $PAforUnnamedamount = !empty($PAforUnnamedamount) ? $PAforUnnamedamount : "0";
            // $aAccessories = json_decode($aData->accessories, true);
            // $dRegDate = $aData->knowcar_reg_details ? json_decode($aData->knowcar_reg_details, true)['carregdate'] : date('d-m-Y');
            // $regDate = \DateTime::createFromFormat('d-m-Y', $dRegDate);
            // $addonAgeLimit = [
            //     "101" => 5,
            //     "103" => 5,
            //     "104" => 5,
            //     "107" => 5,
            //     "108" => 7,
            //     "109" => 5,
            //     "110" => 5,
            //     "111" => 5,
            //     "106" => 1
            // ];
            // //$aAddons = json_decode($aData->caraddon, true) ?? [];
            // $aCarAddon = is_string($aData->caraddon)
            //     ? json_decode($aData->caraddon, true)
            //     : (array) $aData->caraddon;

            // // return[
            // //       "adsdf"=>$aCarAddon
            // // ];


            // $aAddons = !empty($aCarAddon['tpselectedaddon'])
            //     ? $aCarAddon['tpselectedaddon']
            //     : (
            //         !empty($aCarAddon['selectedaddon'])
            //         ? $aCarAddon['selectedaddon']
            //         : (
            //             !empty($aCarAddon['odselectedaddon'])
            //             ? $aCarAddon['odselectedaddon']
            //             : []
            //         )
            //     );
            // $validAddons = [];

            // foreach ($aAddons as $addonId) {
            //     $addonId = (string) $addonId;
            //     if (isset($addonAgeLimit[$addonId])) {
            //         $maxyears = $addonAgeLimit[$addonId];
            //         if (ValidateAddonAge($regDate, $maxyears)) {
            //             $validAddons[] = $addonId; // valid by age
            //         }
            //         // else expired, skip
            //     } else {
            //         $validAddons[] = $addonId; // no age limit, keep it
            //     }
            // }

            // $aAddons = $validAddons;

            // // return [
            // //     "valid" => $aAddons
            // // ];
            // $aResult = [];
            // if (!empty($aAccessories)) {
            //     foreach ($aAccessories as $item) {
            //         $aResult[$item['type']] = $item['amount'];

            //     }
            // }

           
            // return $sState;

            $cachemotortype = 'cache_motortype_' . $userId;

            if (GetCache($cachemotortype) == "newcar") {
                //     $VehicleType = "W";
                //     $sProposalType = getconstant("MOTOR.SHRIRAM.PROPOSALTYPE.FRESHPROPOSAL");
                //     if ($nPlanType == '2') {
                //         $PolicyFromDate = $today->format('d-m-Y');
                //         //$today = Carbon::today();
                //         $PolicyToDate = $today->addYears(3)->subDay()->format('d-m-Y');
                // $sPolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.BUNDLED");

                $regno = 'NEW';
                $policyType = getconstant("MOTOR.BAJAJMOTOR.POLICYTYPE.NB");
                $sPrePolicyType = getconstant("MOTOR.BAJAJMOTOR.PRODUCTCODETWOWHEELER.NEWBUSSINESS");
                $aNewCardata = json_decode($aData->newcar_reg_details, true);
                $year = $aNewCardata['brandyear'];
                
                
                $PolicyFromDate = $today->addDay()->format('d-M-Y');
                $PolicyToDate = $today->addYears(3)->subDay()->format('d-M-Y');


                //         $NilDepreciationCoverYN = in_array('101', $aAddons) ? "Y" : "N";
                //         $RSACover = in_array('102', $aAddons) ? "Y" : "N";
                //         $DailyExpRemYN = in_array('109', $aAddons) ? "Y" : "N";
                //         $KeyReplacementYN = in_array('111', $aAddons) ? "Y" : "N";
                //         $LossOfPersonBelongYN = in_array('107', $aAddons) ? "Y" : "N";
                //         $EmergencyTranHotelExpRemYN = in_array('108', $aAddons) ? "Y" : "N";
                //         $MultiCarBenefitYN = in_array('110', $aAddons) ? "Y" : "N";
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
                //     }
            }


            if (GetCache($cachemotortype) == 'knowcar') {
                $aCardata = json_decode($aData->knowcar_reg_details, true) ?? [];
                $policyType = getconstant("MOTOR.BAJAJMOTOR.POLICYTYPE.OTHER");
                $regno = $aData->carnumber;
                if ($aCardata['prepolitype'] == 'odonly') {
                    $sPrePolicyType = getconstant("MOTOR.BAJAJMOTOR.PRODUCTCODEFOURWHEELER.ODONLY");
                    $cardate = $aCardata['carregdate'];
                    $year = $aCardata['brandyear'];
                    $regdate = Carbon::parse($cardate);
                    $carregdata = strtoupper($regdate->format('d-M-Y'));
                    $sPrevexptoDate = $aCardata['odfromdate'];
                    $sPrevexpfDate = $aCardata['odtodate'];
                    $toDate = Carbon::parse($sPrevexptoDate);
                    $fromDate = Carbon::parse($sPrevexpfDate);
                    $StartDate = strtoupper($sPrevexptoDate->format('d-M-Y'));
                    $EndDate = strtoupper($sPrevexpfDate->format('d-M-Y'));

                    $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    $PolicyToDate = $today->addYear()->subDay()->format('Y-m-d');

                } else if ($aCardata['prepolitype'] == 'bundled') {
                    $sPrePolicyType = getconstant("MOTOR.BAJAJMOTOR.PRODUCTCODEFOURWHEELER.NEWBUSSINESS");
                    $cardate = $aCardata['carregdate'];
                    $year = $aCardata['brandyear'];
                    $regdate = Carbon::parse($cardate);
                    // $year = Carbon::today()->year;
                    $sPrevexptoDate = $aCardata['bdfromdate'];
                    $sPrevexpfDate = $aCardata['bdtptodate'];
                    $toDate = Carbon::parse($sPrevexptoDate);
                    $fromDate = Carbon::parse($sPrevexpfDate);
                    $StartDate = strtoupper($toDate->format('d-M-Y'));
                    $EndDate = strtoupper($fromDate->format('d-M-Y'));

                    $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    $PolicyToDate = $today->addYear()->subDay()->format('Y-m-d');

                } elseif ($aCardata['prepolitype'] == 'comprehensive') {
                    $sPrePolicyType = getconstant("MOTOR.BAJAJMOTOR.PRODUCTCODEFOURWHEELER.COMPREHENSIVE");
                    $cardate = $aCardata['carregdate'];
                    $year = $aCardata['brandyear'];
                    // $year = Carbon::today()->year;
                    $regdate = Carbon::parse($cardate);
                    $carregdata = strtoupper($regdate->format('d-M-Y'));
                    $sPrevexptoDate = $aCardata['compfromdate'];
                    $sPrevexpfDate = $aCardata['comptodate'];
                    $toDate = Carbon::parse($sPrevexptoDate);
                    $fromDate = Carbon::parse($sPrevexpfDate);

                    $StartDate = strtoupper($toDate->format('d-M-Y'));
                    $EndDate = strtoupper($fromDate->format('d-M-Y'));

                    $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    $PolicyToDate = $today->addYear()->subDay()->format('Y-m-d');
                    //dd($PolicyFromDate,$PolicyToDate);

                } elseif ($aCardata['prepolitype'] == 'tponly') {
                    $sPrePolicyType = getconstant("MOTOR.BAJAJMOTOR.PRODUCTCODEFOURWHEELER.TPONLY");
                    $cardate = $aCardata['carregdate'];
                    $year = $aCardata['brandyear'];
                    $regdate = Carbon::parse($cardate);
                    $carregdata = strtoupper($regdate->format('d-M-Y'));
                    // $sPrevexptoDate = $aCardata['tpfromdate'];
                    $sPrevexpfDate = $aCardata['tptodate'];
                    // $toDate = Carbon::parse($sPrevexptoDate);
                    $fromDate = Carbon::parse($sPrevexpfDate);
                    // $StartDate = strtoupper($toDate->format('d-M-Y'));
                    $EndDate = strtoupper($fromDate->format('d-M-Y'));
                    //dd($PolicyFromDate,$PolicyToDate);

                    $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    $PolicyToDate = $today->addYear()->subDay()->format('Y-m-d');
                }

            }
            // $sRegNumber = "";
            // $sRegNo2 = "";
            // $sRegNo3 = "";
            // $sRegNo1 = "";
            // $sRegNo4 = "";
            // if (GetCache($cachemotortype) == 'newcar') {
            //     $sRegNumber = substr(explode('(', $aData->rtocode)[1], 0, -1);
            //     $sRegNo1 = substr(str_replace('-', '', $sRegNumber), 0, 2);//k
            //     $sRegNo2 = substr(str_replace('-', '', $sRegNumber), 2, 2);//k
            // } else {
            //     $sRegNumber = $aData->carnumber;
            //     $sRegNo1 = substr($sRegNumber, 0, 2);//k
            //     $sRegNo2 = substr($sRegNumber, 2, 2);//k
            //     $sRegNo3 = substr($sRegNumber, 4, 2);//k
            //     $sRegNo4 = substr($sRegNumber, 6, 4);//k
            // }
            // $permanentAddress = isset($oJourneyData->permanent_address) && json_decode($oJourneyData->permanent_address, true) ? json_decode($oJourneyData->permanent_address, true) : [];
            // $pincode = $user->pincode;
            // $state = Shriram_Pincode::where('PC_CODE', $pincode)->first();

            // if (!$state) {
            //     return ['status' => '0', 'message' => 'Pincode not available for this service.'];
            // }
            // $sState = $state->STATE;
            // if (GetCache($cachemotortype) == "knowcar") {
            //     $aCardata = json_decode($aData->knowcar_reg_details, true) ?? [];
            //     if ($aCardata['under'] == "company") {
            //         $PAcover = "0";
            //         $pAcoverReason = "PA_TYPE2";
            //     } else {
            //         $PAcover = $aData->pacover;
            //         if ($PAcover == 0) {
            //             $pAcoverReason = json_decode($aData->pacover_reason, true);
            //             // $ncoverReason = array_keys($pAcoverReason)[0];
            //             $ncoverReason = is_array($pAcoverReason) ? (array_keys($pAcoverReason)[0] ?? []) : [];
            //             if ($ncoverReason == '1') {
            //                 $pAcoverReason = "PA_TYPE1";
            //             }
            //             if ($ncoverReason == '2') {
            //                 $pAcoverReason = "PA_TYPE2";
            //             }
            //             if ($ncoverReason == '3') {
            //                 $pAcoverReason = "PA_TYPE4";
            //             } else {
            //                 $pAcoverReason = "";
            //             }
            //         }
            //     }
            //     // $firstregdate=$aCardata['carregdate'];
            //     $firstregdate = array_key_exists('carregdate', $aCardata) ? $aCardata['carregdate'] : '';
           $claim = array_key_exists('bonus-button', $aCardata) ? $aCardata['bonus-button'] : '0';
            //     $transferOfowner = array_key_exists('ownershiptoggle', $aCardata) ? $aCardata['ownershiptoggle'] : '0';
            //     //dd($transferOfowner);
            //     $oModel = Shriram_Vehicle_Master::where('id', $aCardata['model'])->first();
            //     //dd($sModel);
            // }
            // if (GetCache($cachemotortype) == "newcar") {
            //     $aCardata = json_decode($aData->newcar_reg_details, true) ?? [];
            //     if ($aCardata['under'] == "company") {
            //         $PAcover = "0";
            //         $pAcoverReason = "PA_TYPE2";
            //     } else {
            //         $PAcover = $aData->pacover;
            //         if ($PAcover == 0) {
            //             $pAcoverReason = json_decode($aData->pacover_reason, true);
            //             // $ncoverReason = array_keys($pAcoverReason)[0];
            //             $ncoverReason = is_array($pAcoverReason) ? (array_keys($pAcoverReason)[0] ?? []) : [];
            //             if ($ncoverReason == '1') {
            //                 $pAcoverReason = "PA_TYPE1";
            //             }
            //             if ($ncoverReason == '2') {
            //                 $pAcoverReason = "PA_TYPE2";
            //             }
            //             if ($ncoverReason == '3') {
            //                 $pAcoverReason = "PA_TYPE4";
            //             } else {
            //                 $pAcoverReason = "";
            //             }
            //         }
            //     }
            //     $firstregdate = $today->format('d-m-Y');
            //     $oModel = Shriram_Vehicle_Master::where('id', $aCardata['model'])->first();

            // }
            // $sVehicleCode = " ";
            // if ($oModel) {
            //     $sVehicleCode = $oModel->VEHICLE_CODE;

            // }
            // $sProdCode = getconstant("MOTOR.SHRIRAM.PRODUCTTYPE.PRIVATECAR");
            // $sPolicyType = "";
            // $sProposalType = "";
            // $today = now();



            // if (GetCache($cachemotortype) == "newcar") {
            //     //     $VehicleType = "W";
            //     //     $sProposalType = getconstant("MOTOR.SHRIRAM.PROPOSALTYPE.FRESHPROPOSAL");
            //     //     if ($nPlanType == '2') {
            //     //         $PolicyFromDate = $today->format('d-m-Y');
            //     //         //$today = Carbon::today();
            //     //         $PolicyToDate = $today->addYears(3)->subDay()->format('d-m-Y');
            //     // $sPolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.BUNDLED");

            //     $regno = 'NEW';
            //     $policyType = getconstant("MOTOR.BAJAJMOTOR.POLICYTYPE.NB");
            //     $sPrePolicyType = getconstant("MOTOR.BAJAJMOTOR.PRODUCTCODETWOWHEELER.NEWBUSSINESS");
            //     $aNewCardata = json_decode($aCardata->newcar_reg_details, true);
            //     $year = $aNewCardata['brandyear'];
            //     $today = Carbon::today();
            //     $StartDate = $today->format('d-M-Y');
            //     $EndDate = $today->addYears(3)->subDay()->format('d-M-Y');


            //     //         $NilDepreciationCoverYN = in_array('101', $aAddons) ? "Y" : "N";
            //     //         $RSACover = in_array('102', $aAddons) ? "Y" : "N";
            //     //         $DailyExpRemYN = in_array('109', $aAddons) ? "Y" : "N";
            //     //         $KeyReplacementYN = in_array('111', $aAddons) ? "Y" : "N";
            //     //         $LossOfPersonBelongYN = in_array('107', $aAddons) ? "Y" : "N";
            //     //         $EmergencyTranHotelExpRemYN = in_array('108', $aAddons) ? "Y" : "N";
            //     //         $MultiCarBenefitYN = in_array('110', $aAddons) ? "Y" : "N";
            //     //         $Eng_Protector = in_array('104', $aAddons) ? "Y" : "N";
            //     //         $Consumables = in_array('103', $aAddons) ? "Y" : "N";
            //     //         $InvReturnYN = in_array('106', $aAddons) ? "Y" : "N";
            //     //         $AntiTheftYN = in_array('119', $aAddons) ? "1" : "0";
            //     //         $LLtoPaidDriverYN = in_array('116', $aAddons) ? "1" : "0";
            //     //         $NoEmpCoverLL = in_array('122', $aAddons) ? "1" : "0";
            //     //         $SHRIMOTORPROTECTION_YN = in_array('113', $aAddons) ? "Y" : "N";
            //     //         $LimitedTPPDYN = in_array('121', $aAddons) ? "1" : "0";
            //     //         $VoluntaryExcess = in_array('120', $aAddons) ? "1" : "0";
            //     //         $PAPaidDriverConductorCleaner = 1;
            //     //         $Geographical = in_array('117', $aAddons) ? 1 : "0";
            //     //         $Geographical = in_array('118', $aAddons) ? 1 : "0";
            //     //         $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
            //     //     }
            // }

            // if (GetCache($cachemotortype) == "knowcar") {
            //     $VehicleType = "U";
            //     $sProposalType = getconstant("MOTOR.SHRIRAM.PROPOSALTYPE.MARKETRENEWAL");
            //     if ($nPlanType == '1') {
            //         $sPolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.OWNDAMAGE");
            //         $NilDepreciationCoverYN = in_array('101', $aAddons) ? "Y" : "N";
            //         $RSACover = in_array('102', $aAddons) ? "Y" : "N";
            //         $DailyExpRemYN = in_array('109', $aAddons) ? "Y" : "N";
            //         $KeyReplacementYN = in_array('111', $aAddons) ? "Y" : "N";
            //         $LossOfPersonBelongYN = in_array('107', $aAddons) ? "Y" : "N";
            //         $EmergencyTranHotelExpRemYN = in_array('108', $aAddons) ? "Y" : "N";
            //         $MultiCarBenefitYN = in_array('110', $aAddons) ? "Y" : "N";
            //         $Eng_Protector = in_array('104', $aAddons) ? "Y" : "N";
            //         $Consumables = in_array('103', $aAddons) ? "Y" : "N";
            //         $InvReturnYN = in_array('106', $aAddons) ? "Y" : "N";
            //         $AntiTheftYN = in_array('119', $aAddons) ? "1" : "0";
            //         $VoluntaryExcess = in_array('120', $aAddons) ? "1" : "0";
            //         $PAPaidDriverConductorCleaner = 1;
            //         $Geographical = in_array('118', $aAddons) ? 1 : "0";
            //         $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";

            //     }
            //     if ($nPlanType == '2') {
            //         $sPolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.PACKAGE");
            //         $NilDepreciationCoverYN = in_array('101', $aAddons) ? "Y" : "N";
            //         $RSACover = in_array('102', $aAddons) ? "Y" : "N";
            //         $DailyExpRemYN = in_array('109', $aAddons) ? "Y" : "N";
            //         $KeyReplacementYN = in_array('111', $aAddons) ? "Y" : "N";
            //         $LossOfPersonBelongYN = in_array('107', $aAddons) ? "Y" : "N";
            //         $EmergencyTranHotelExpRemYN = in_array('108', $aAddons) ? "Y" : "N";
            //         $MultiCarBenefitYN = in_array('110', $aAddons) ? "Y" : "N";
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

            //     }
            //     if ($nPlanType == '3' && (GetCache($cachepolicyExpiry) == "Not Expired")) {
            //         $sPolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.LIABILITY");
            //         $LLtoPaidDriverYN = in_array('116', $aAddons) ? "1" : "0";
            //         $LimitedTPPDYN = in_array('121', $aAddons) ? "1" : "0";
            //         $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
            //         $NoEmpCoverLL = in_array('122', $aAddons) ? "1" : "0";
            //         $Geographical = in_array('118', $aAddons) ? 1 : "0";
            //     }
            //     if ($nPlanType == '3' && (GetCache($cachepolicyExpiry) == "Expired")) {
            //         $sPolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.LIABILITY");
            //         $PolicyFromDate = $today->format('d-m-Y');
            //         $PolicyToDate = $today->addYear()->subDay()->format('d-m-Y');
            //         $LLtoPaidDriverYN = in_array('116', $aAddons) ? "1" : "0";
            //         $LimitedTPPDYN = in_array('121', $aAddons) ? "1" : "0";
            //         $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
            //         $NoEmpCoverLL = in_array('122', $aAddons) ? "1" : "0";
            //         $Geographical = in_array('118', $aAddons) ? 1 : "0";

            //     }
            // }
            // $today = now();
            // $sRtocity = getRtocityApi($request, $sRegNumber);
            // if ($sRtocity) {
            //     $sRtocity = $sRtocity->RTOCITY ?? $sRtocity->RTONAME;
            // } else {
            //     $sRtocity = "";
            // }

            $cachecaridv = 'cache_' . $userId . '_caridv';
            $idv = GetCache($cachecaridv);

            // $url = 'https://nsecureapi.shriramgi.com/NOVADIGITAL/SVS_Services/PolicyGeneration.svc/RestService/GetQuote';
            // $curl = curl_init();
            // $postJson = json_encode([
            //     "objPolicyEntryETT" => [
            //         "ReferenceNo" => "",
            //         "ProdCode" => $sProdCode,
            //         "PolicyFromDt" => $PolicyFromDate,
            //         "PolicyToDt" => $PolicyToDate,
            //         "PolicyIssueDt" => $PolicyFromDate,
            //         "InsuredPrefix" => "1",
            //         "InsuredName" => $AuthUser['name'],
            //         "PolicyType" => $sPolicyType,
            //         "ProposalType" => $sProposalType,
            //         "VehicleCode" => $sVehicleCode ?? "UL8066",
            //         "EngineNo" => "",
            //         "FirstRegDt" => $firstregdate ?? "",
            //         "VehicleType" => $VehicleType ?? "",
            //         "PAYEAR" => "1",
            //         "ChassisNo" => "",
            //         "RegNo1" => $sRegNo1,
            //         "RegNo2" => $sRegNo2,
            //         "RegNo3" => $sRegNo3,
            //         "RegNo4" => $sRegNo4,
            //         "RTOCode" => $sRegNo1 . "-" . $sRegNo2,
            //         "PreviousPolicyNo" => "",
            //         "PreviousInsurer" => "",
            //         "PreviousPolicyFromDt" => $sPrevexpfDate ?? "",
            //         "PreviousPolicyToDt" => $sPrevexptoDate ?? "",
            //         "PreviousPolicyUWYear" => "",
            //         "PreviousPolicySI" => "",
            //         "TRANSFEROFOWNER" => $transferOfowner ?? "",
            //         "PreviousPolicyClaimYN" => $claim,
            //         "PreviousPolicyNCBPerc" => $claimper,
            //         "PreviousPolicyType" => $sPrePolicyType ?? "",
            //         "PreviousNilDepreciation" => "",
            //         "PAforUnnamedPassengerYN" => $PAforUnnamedPassenger ?? "",
            //         "PAforUnnamedPassengerSI" => ($PAforUnnamedPassenger == "1") ? $PAforUnnamedamount : "",
            //         "ElectricalaccessYN" => array_key_exists('electrical', $aResult) ? "Y" : "N",
            //         "ElectricalaccessSI" => array_key_exists('electrical', $aResult) ? $aResult['electrical'] : '',
            //         "ElectricalaccessRemarks" => "",
            //         "NonElectricalaccessYN" => array_key_exists('non-electrical', $aResult) ? "Y" : "N",
            //         "NonElectricalaccessSI" => array_key_exists('non-electrical', $aResult) ? $aResult['non-electrical'] : '',
            //         "NonElectricalaccessRemarks" => "",
            //         "PAPaidDriverConductorCleanerYN" => "Y",
            //         "PAPaidDriverConductorCleanerSI" => "",
            //         "PAPaidDriverCount" => "1",
            //         "PAPaidConductorCount" => "1",
            //         "PAPaidCleanerCount" => "1",
            //         "PAOwnerDriverExclusion" => $PAcover ?? "",
            //         "PAOwnerDriverExReason" => $pAcoverReason ?? "",
            //         "NomineeNameforPAOwnerDriver" => "Demo",
            //         "NomineeAgeforPAOwnerDriver" => "26",
            //         "NomineeRelationforPAOwnerDriver" => "Son",
            //         "AppointeeNameforPAOwnerDriver" => "",
            //         "AppointeeRelationforPAOwnerDriver" => "",
            //         "LLtoPaidDriverYN" => $LLtoPaidDriverYN ?? "0",
            //         "NoEmpCoverLL" => $NoEmpCoverLL ?? "0",
            //         "Bangladesh" => ($Geographical == 1) ? "1" : "0",
            //         "Bhutan" => ($Geographical == 1) ? "1" : "0",
            //         "SriLanka" => ($Geographical == 1) ? "1" : "0",
            //         "Nepal" => ($Geographical == 1) ? "1" : "0",
            //         "Pakistan" => ($Geographical == 1) ? "1" : "0",
            //         "Maldives" => ($Geographical == 1) ? "1" : "0",
            //         "CNGKitYN" => array_key_exists('cng', $aResult) ? "Y" : "N",
            //         "CNGKitSI" => array_key_exists('cng', $aResult) ? $aResult['cng'] : '',
            //         "InBuiltCNGKitYN" => "0",
            //         "NilDepreciationCoverYN" => $NilDepreciationCoverYN ?? "",
            //         "RSACover" => $RSACover ?? "",
            //         "DailyExpRemYN" => $DailyExpRemYN ?? "",
            //         "KeyReplacementYN" => $KeyReplacementYN ?? "",
            //         "LossOfPersonBelongYN" => $LossOfPersonBelongYN ?? "",
            //         "EmergencyTranHotelExpRemYN" => $EmergencyTranHotelExpRemYN ?? "",
            //         "MultiCarBenefitYN" => $MultiCarBenefitYN ?? "",
            //         "Eng_Protector" => $Eng_Protector ?? "",
            //         "Consumables" => $Consumables ?? "",
            //         "InvReturnYN" => $InvReturnYN ?? "",
            //         "SHRIMOTORPROTECTION_YN" => $SHRIMOTORPROTECTION_YN ?? "",
            //         "LimitedTPPDYN" => $LimitedTPPDYN ?? "0",
            //         "Gender" => $AuthUser['gender'][0] ?? '',
            //         "Address1" => "",
            //         "Address2" => "",
            //         "Address3" => "",
            //         "State" => $sState,
            //         "City" => $sRtocity,
            //         "PinCode" => $AuthUser['pincode'],
            //         "PanNo" => "",
            //         "GSTNo" => "",
            //         "TelephoneNo" => "",
            //         "FaxNo" => "",
            //         "EMailID" => "",
            //         "tpPolFmdt" => $sPretpfDate ?? "",
            //         "tpPolTodt" => $sPretptoDate ?? "",
            //         "tpPolNo" => "",
            //         "tpPolComp" => "",
            //         "tpPolAddr" => "",
            //         "MobileNo" => "",
            //         "DateOfBirth" => "",
            //         "POSAgentName" => "",
            //         "POSAgentPanNo" => "",
            //         "CoverNoteNo" => "",
            //         "CoverNoteDt" => "",
            //         "IDV_of_Vehicle" => $idv ?? "0",
            //         "Colour" => "",
            //         "VehiclePurposeYN" => "",
            //         "DriverAgeYN" => "",
            //         "LimitOwnPremiseYN" => "0",
            //         "VoluntaryExcess" => $VoluntaryExcess ?? "",
            //         "DeTariff" => "",
            //         "PreInspectionReportYN" => "",
            //         "PreInspection" => "",
            //         "BreakIn" => "NO",
            //         "AddonPackage" => "",
            //         "AntiTheftYN" => $AntiTheftYN ?? "",
            //         "HypothecationType" => "",
            //         "HypothecationBankName" => "",
            //         "HypothecationAddress1" => "",
            //         "HypothecationAddress2" => "",
            //         "HypothecationAddress3" => "",
            //         "HypothecationAgreementNo" => "",
            //         "HypothecationCountry" => "",
            //         "HypothecationState" => "",
            //         "HypothecationCity" => "",
            //         "HypothecationPinCode" => "",
            //         "SpecifiedPersonField" => "",
            //         "AadharNo" => "",
            //         "AadharEnrollNo" => ""
            //     ]
            // ]);


            // $today = Carbon::today();
            $StartDate = strtoupper($today->format('d-M-Y'));
            $EndDate = strtoupper($today->addYear()->subDay()->format('d-M-Y'));
            $pincode = $user->pincode;
            $state = RtaMaster::where('pincode', $pincode)->first();

            if (!$state) {
                return ['status' => '0', 'message' => 'Pincode not available for this service.'];
            }
            $sState = $state->city;
            $zone = $state->zone;
            $password = self::$password;
            $Id = self::$Id;
            $vehicletypecode = self::$vehicletypecode;
            $deptcode = self::$deptcode;

            $post = [
                "userid" => $Id,
                "password" => $password,
                "vehiclecode" => "38162408",
                "city" => $sState,
                "weomotpolicyin" => [
                    "contractid" => "0",
                    "poltype" => $policyType, //"1",
                    "product4digitcode" => $sPrePolicyType,
                    "deptcode" => $deptcode,
                    "branchcode" => "1149",
                    "termstartdate" =>$PolicyFromDate, //"17-Nov-2025",
                    "termenddate" =>$PolicyToDate, //"16-Nov-2026",
                    "tpfintype" => "0",
                    "hypo" => "", //"State Bank of India",
                    "vehicletypecode" => $vehicletypecode,
                    "vehicletype" => "PRIVATE CAR",
                    "miscvehtype" => "0",
                    "vehiclemakecode" => "110",
                    "vehiclemake" => "MARUTI",
                    "vehiclemodelcode" => "39",
                    "vehiclemodel" => "SWIFT DZIRE",
                    "vehiclesubtypecode" => "8",
                    "vehiclesubtype" => "LXI",
                    "fuel" => "P",
                    "zone" =>$zone, //"A",
                    "engineno" => "", //"TYHGUYG897657896456",
                    "chassisno" => "", //"LOKIJUHYGT6789876",
                    "registrationno" => $regno ?? "NEW",//"MH02",
                    "registrationdate" =>$carregdata ?? "",
                    "registrationlocation" => $sState,
                    "regilocother" => $sState,
                    "carryingcapacity" => "4",
                    "cubiccapacity" => "1298",
                    "yearmanf" => $year,
                    "color" =>"", //"RED",
                    "vehicleidv" => $idv ?? "0",
                    "ncb" => "0", //$claim ?? "0",
                    "addloading" => "0",
                    "addloadingon" => "0",
                    "spdiscrate" => "0",
                    "elecacctotal" => "0",
                    "nonelecacctotal" => "0",
                    "prvpolicyref" => "",
                    "prvexpirydate" => $EndDate ?? "",
                    "prvinscompany" => "0",
                    "prvncb" => "0",
                    "prvclaimstatus" => "0",
                    "automembership" => "", //"dfdffdf",
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
                    "geogextn" => "",
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
                    "extcol8" => "", //"MCPA",
                    "extcol9" => "",
                    "extcol10" => "",
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
                    "extcol24" => "", //"1",
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
                "transactionid" => "0",
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

            curl_close($curl);
            return $response;
        } catch (\Exception $e) {
            // \Log::info($e->getMessage() . "errorcode:shriram_service_generatePrivateCarQuote");
            return [
                'status' => '0',
                'message' => $e->getMessage() . 'An error occurred while fetching cache data.'
            ];
        }
    }
    public static function FileIntoBase64($filePath)
    {
        $base64 = '';
        $image = '';
        $parts = explode("/", $filePath);
        $extension = explode(".", end($parts));
        if (file_exists($filePath)) {
            $image = file_get_contents($filePath);
            $base64 = base64_encode($image);
        } else {
            throw new \Exception("File not found.");
        }
        return ['extension' => '.' . end($extension), 'based64' => $base64];
    }

    public static function privateCarProposal(Request $request, $today, $nextyear, $aData)
    {
        // try {
        // $userId = $request->userid;
        // $user = User::find($userId);
        // $AuthUser = $user->toArray();
        // $aData = DataModel::where('userid', $userId)->first();
        // $oJourneyData = MotorJourney::where('userid', $userId)->where('is_car', '1')->first();
        // $prevPolicydata = json_decode($oJourneyData->pre_policy_details, true);
        // $LimitedTPPDYN = null;
        // $LLtoPaidDriverYN = null;
        // $AntiTheftYN = null;
        // $NoEmpCoverLL = null;
        // $VoluntaryExcess = null;
        // $Geographical = null;
        // $PAforUnnamedPassenger = null;
        // $VehicleType = null;
        // $HypothData = json_decode($oJourneyData->bank_details, true);
        // $aCardata = json_decode($aData->knowcar_reg_details, true);
        // $aNewCardata = json_decode($aData->newcar_reg_details, true);
        // $aAccessories = json_decode($aData->accessories, true);
        // $cachepolicyExpiry = 'cache_policyExpiry_' . $userId;
        // $aResult = [];
        // if (!empty($aAccessories)) {
        //     foreach ($aAccessories as $item) {
        //         $aResult[$item['type']] = $item['amount'];

        //     }
        // }
        // $pAcoverReason = "";
        // $PAcover = "";
        // $PAforUnnamedamount = json_decode($aData->caraddonvalue, true);
        // $PAforUnnamedamount = !empty($PAforUnnamedamount) ? $PAforUnnamedamount : "0";
        // $nTpolicyNo = null;
        // $sTpInsurer = null;
        // $prevPolicyNo = null;
        // $sRegNumber = $aData->carnumber;
        // $sRegNumber = "";
        // $sRegNo2 = "";
        // $sRegNo3 = "";
        // $sRegNo1 = "";
        // $sRegNo4 = "";
        // $cachemotortype = 'cache_motortype_' . $userId;
        // $cacheunder = 'cache_under_' . $userId;

        // if (GetCache($cachemotortype) == 'newcar') {
        //     $sRegNumber = substr(explode('(', $aData->rtocode)[1], 0, -1);
        //     $sRegNo1 = substr(str_replace('-', '', $sRegNumber), 0, 2);//k
        //     $sRegNo2 = substr(str_replace('-', '', $sRegNumber), 2, 2);//k
        // } else {
        //     $sRegNumber = $aData->carnumber;
        //     $sRegNo1 = substr($sRegNumber, 0, 2);//k
        //     $sRegNo2 = substr($sRegNumber, 2, 2);//k
        //     $sRegNo3 = substr($sRegNumber, 4, 2);//k
        //     $sRegNo4 = substr($sRegNumber, 6, 4);//k
        //     $sRegdate = $aCardata['carregdate'] ?? date('d-m-Y');
        // }
        // $sPrevexptoDate = null;
        // $sPrevexpfDate = null;
        // $sPretpfDate = null;
        // $sPretptoDate = null;
        // $claim = null;
        // $claimper = null;
        // $transferOfowner = null;
        // $gstno = null;
        // $oModel = null;

        // if (GetCache($cachemotortype) == 'knowcar') {
        //     if ($aCardata['prepolitype'] == 'odonly') {
        //         $sPrePolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.OWNDAMAGE");
        //         $sPrevexptoDate = $aCardata['odtodate']; //k
        //         $sPrevexpfDate = $aCardata['odfromdate']; //k
        //         $sPretpfDate = $aCardata['odtpfromdate'];
        //         $sPretptoDate = $aCardata['odtptodate'];
        //         $nTpolicyNo = array_key_exists('tppolicynumber', $prevPolicydata) ? $prevPolicydata['tppolicynumber'] : '';
        //         $sTpInsurer = array_key_exists('tpprevInsurance', $prevPolicydata) ? $prevPolicydata['tpprevInsurance'] : '';
        //         $tpInsurCmpny = Shriram_Prev_insurence::where('id', $sTpInsurer)->first()->insurance;
        //         $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
        //         $PolicyFromDate = $date->addDay()->format('d-m-Y');
        //         $PolicyToDate = $date->addYear()->subDay()->format('d-m-Y');

        //     } else if ($aCardata['prepolitype'] == 'bundled') {

        //         $sPrePolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.BUNDLED");
        //         $sPrevexptoDate = $aCardata['bdtodate']; //k
        //         $sPrevexpfDate = $aCardata['bdfromdate']; //k
        //         $sPretpfDate = $aCardata['bdtpfromdate'];
        //         $sPretptoDate = $aCardata['bdtptodate'];
        //         $nTpolicyNo = array_key_exists('tppolicynumber', $prevPolicydata) ? $prevPolicydata['tppolicynumber'] : '';
        //         $sTpInsurer = array_key_exists('tpprevInsurance', $prevPolicydata) ? $prevPolicydata['tpprevInsurance'] : '';
        //         $tpInsurCmpny = Shriram_Prev_insurence::where('id', $sTpInsurer)->first()->insurance;
        //         $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
        //         $PolicyFromDate = $date->addDay()->format('d-m-Y');
        //         $PolicyToDate = $date->addYear()->subDay()->format('d-m-Y');

        //     } elseif ($aCardata['prepolitype'] == 'comprehensive') {
        //         $sPrePolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.PACKAGE");
        //         $sPrevexptoDate = $aCardata['comptodate']; //k
        //         $sPrevexpfDate = $aCardata['compfromdate'];
        //         $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
        //         $PolicyFromDate = $date->addDay()->format('d-m-Y');
        //         $PolicyToDate = $date->addYear()->subDay()->format('d-m-Y');

        //     } elseif ($aCardata['prepolitype'] == 'tponly') {
        //         $sPrePolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.LIABILITY");
        //         $sPrevexptoDate = $aCardata['tptodate']; //k
        //         $sPrevexpfDate = $aCardata['tpfromdate']; //k
        //         $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
        //         $PolicyFromDate = $date->addDay()->format('d-m-Y');
        //         $PolicyToDate = $date->addYear()->subDay()->format('d-m-Y');
        //     }

        // }

        // if (GetCache($cachemotortype) == 'newcar') {
        //     $oModel = Shriram_Vehicle_Master::where('id', $aNewCardata['model'])->first();

        // } else {
        //     $oModel = Shriram_Vehicle_Master::where('id', $aCardata['model'])->first();
        // }
        // $sVehicleCode = "";
        // if ($oModel) {
        //     $sVehicleCode = $oModel->VEHICLE_CODE;
        // }

        // $oObj = new ShriramCarController();
        // $aDocument = UserMotorDescription::where('userid', $userId)->first();
        // //return $aDocument;
        // $aIdDetails = $aDocument->idnumber ? json_decode($aDocument->idnumber, true) : [];
        // $filePath = json_decode($aDocument->document, true);
        // $insurePhotoB64 = self::FileIntoBase64($filePath['insurephoto']);
        // $identityPhotoB64 = self::FileIntoBase64($filePath['identity']['identityfront']);
        // $addressPhotoB64 = self::FileIntoBase64($filePath['address']['addressfront']);
        // self::initlize();

        // //$url = MasterAPI::where('apicode', '116')->first()->apistring;
        // $url = 'https://nsecureapi.shriramgi.com/NOVADIGITAL/SVS_Services/PolicyGeneration.svc/RestService/GenerateProposal';
        // $aNominee = $oJourneyData->nominee_details ? jdec($oJourneyData->nominee_details) : [];
        // $permanentAddress = json_decode($oJourneyData->permanent_address, true) ?? [];
        // $pincode = $permanentAddress['pincode'];
        // $state = Shriram_Pincode::where('PC_CODE', $pincode)->first();

        // $sState = $state->STATE;

        // $nPlanType = $aData->car_plan_type;

        // $dRegDate = $aData->knowcar_reg_details ? json_decode($aData->knowcar_reg_details, true)['carregdate'] : date('d-m-Y');
        // $regDate = \DateTime::createFromFormat('d-m-Y', $dRegDate);
        // $addonAgeLimit = [
        //     "101" => 5,
        //     "103" => 5,
        //     "104" => 5,
        //     "107" => 5,
        //     "108" => 5,
        //     "109" => 5,
        //     "110" => 5,
        //     "111" => 5,
        //     "106" => 1
        // ];
        // //$aAddons = json_decode($aData->caraddon, true) ?? [];
        // $aCarAddon = is_string($aData->caraddon)
        //     ? json_decode($aData->caraddon, true)
        //     : (array) $aData->caraddon;

        // $aAddons = !empty($aCarAddon['tpselectedaddon'])
        //     ? $aCarAddon['tpselectedaddon']
        //     : (
        //         !empty($aCarAddon['selectedaddon'])
        //         ? $aCarAddon['selectedaddon']
        //         : (
        //             !empty($aCarAddon['odselectedaddon'])
        //             ? $aCarAddon['odselectedaddon']
        //             : []
        //         )
        //     );

        // $validAddons = [];
        // foreach ($aAddons as $addonId) {
        //     if (isset($addonAgeLimit[$addonId])) {
        //         $maxyears = $addonAgeLimit[$addonId];
        //         if (ValidateAddonAge($regDate, $maxyears)) {
        //             $validAddons[] = $addonId;
        //         }
        //     } else {
        //         $validAddons[] = $addonId;
        //     }
        // }
        // //  return[
        // //     "sdff"=> $aAddons,
        // //     "validAddons"=>$validAddons
        // // ];
        // $aAddons = $validAddons;

        // $cachecaridv = 'cache_' . $userId . '_caridv';
        // $nIdv = GetCache($cachecaridv);
        // $randomValue = self::genRandomNumber();
        // $EngineNo = "GDFG59D4GD6546D" . $randomValue;//$aVehicledetails['Enginenumber'];
        // $ChassisNo = "GDF45GDFGD4G56D" . $randomValue;//$aVehicledetails['Chassisnumber'];
        // $sProdCode = getconstant("MOTOR.SHRIRAM.PRODUCTTYPE.PRIVATECAR");
        // $sPolicyType = "";
        // $sProposalType = "";
        // $gstno = null;
        // $today = now();

        // if (GetCache($cachemotortype) == "newcar") {
        //     $VehicleType = "U";
        //     $sProposalType = getconstant("MOTOR.SHRIRAM.PROPOSALTYPE.FRESHPROPOSAL");
        //     $companydetails = json_decode($oJourneyData->company_details, true);
        //     if ($aNewCardata['under'] == "company") {
        //         $PAcover = 0;
        //         $pAcoverReason = "PA_TYPE2";
        //     } else {
        //         $PAcover = $aData->pacover;
        //         if ($PAcover == 0) {
        //             $pAcoverReason = json_decode($aData->pacover_reason, true);
        //             // $ncoverReason = array_keys($pAcoverReason)[0];
        //             $ncoverReason = is_array($pAcoverReason) ? (array_keys($pAcoverReason)[0] ?? []) : [];
        //             if ($ncoverReason == '1') {
        //                 $pAcoverReason = "PA_TYPE1";
        //             }
        //             if ($ncoverReason == '2') {
        //                 $pAcoverReason = "PA_TYPE2";
        //             }
        //             if ($ncoverReason == '3') {
        //                 $pAcoverReason = "PA_TYPE4";
        //             } else {
        //                 $pAcoverReason = "";
        //             }
        //         }
        //     }
        //     if ($nPlanType == '2') {
        //         $sPolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.BUNDLED");
        //         $sRegdate = $today->format('d-m-Y');
        //         $PolicyFromDate = $today->format('d-m-Y');
        //         $PolicyToDate = $today->addYears(3)->subDay()->format('d-m-Y');
        //         $Geographical = in_array('118', $aAddons) ? 1 : "0";
        //         $Geographical = in_array('117', $aAddons) ? 1 : "0";
        //         $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";

        //     }
        // }
        // if (GetCache($cachemotortype) == "knowcar") {
        //     $VehicleType = "U";
        //     $prevPolicyNo = $prevPolicydata['policynumber'];
        //     $prevInsurCmpny = Shriram_Prev_insurence::where('id', $prevPolicydata['prevInsuranceId'])->first()->insurance;
        //     if ($aCardata['under'] == "company") {
        //         $PAcover = 0;
        //         $pAcoverReason = "PA_TYPE2";
        //         $companydetails = json_decode($oJourneyData->company_details, true);
        //         //dd($companydetails);
        //         $gstno = $companydetails['gstnumber'];
        //     } else {
        //         $PAcover = $aData->pacover;
        //         $gstno = null;
        //         if ($PAcover == 0) {
        //             $pAcoverReason = json_decode($aData->pacover_reason, true);
        //             // $ncoverReason = array_keys($pAcoverReason)[0];
        //             $ncoverReason = is_array($pAcoverReason) ? (array_keys($pAcoverReason)[0] ?? []) : [];
        //             if ($ncoverReason == '1') {
        //                 $pAcoverReason = "PA_TYPE1";
        //             }
        //             if ($ncoverReason == '2') {
        //                 $pAcoverReason = "PA_TYPE2";
        //             }
        //             if ($ncoverReason == '3') {
        //                 $pAcoverReason = "PA_TYPE4";
        //             } else {
        //                 $pAcoverReason = "";
        //             }
        //         }
        //     }
        //     $claim = array_key_exists('policytoggle', $aCardata) ? '0' : '1';
        //     $claimper = array_key_exists('bonus-button', $aCardata) ? $aCardata['bonus-button'] : '0';
        //     $transferOfowner = array_key_exists('ownershiptoggle', $aCardata) ? $aCardata['ownershiptoggle'] : '0';
        //     $sProposalType = getconstant("MOTOR.SHRIRAM.PROPOSALTYPE.MARKETRENEWAL");
        //     //dd($aCardata);
        //     if ($nPlanType == '1') {
        //         $sPolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.OWNDAMAGE");
        //         $Geographical = in_array('117', $aAddons) ? 1 : "0";
        //     }
        //     if ($nPlanType == '2') {
        //         $sPolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.PACKAGE");
        //         $Geographical = in_array('117', $aAddons) ? 1 : "0";
        //         $Geographical = in_array('118', $aAddons) ? 1 : "0";
        //         $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
        //     }
        //     if ($nPlanType == '3' && (GetCache($cachepolicyExpiry) == "Not Expired")) {
        //         $sPolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.LIABILITY");
        //         $Geographical = in_array('118', $aAddons) ? 1 : "0";
        //         $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";

        //     }
        //     if ($nPlanType == '3' && (GetCache($cachepolicyExpiry) == "Expired")) {
        //         $sPolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.LIABILITY");
        //         $Geographical = in_array('118', $aAddons) ? 1 : "0";
        //         $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
        //         $PolicyFromDate = $today->format('d-m-Y');
        //         $PolicyToDate = $today->addYear()->subDay()->format('d-m-Y');

        //     }
        // }
        // $sRtocity = getRtocityApi($request, $sRegNumber);
        // if ($sRtocity) {
        //     $sRtocity = !empty($sRtocity->RTOCITY) ? $sRtocity->RTOCITY : $sRtocity->RTONAME;
        // } else {
        //     $sRtocity = "";
        // }

        // $dob = !empty($oJourneyData->dob)
        //     ? $oJourneyData->dob
        //     : (!empty($user->dob)
        //         ? $user->dob
        //         : "29-04-2000");
        // $cacheproductcode = 'cache_productcode_' . $userId;
        // SetCache($cacheproductcode, $sProdCode);
        // //session()->put('productcode', $sProdCode);

        // $cacheproposaltype = 'cache_proposaltype_' . $userId;
        // SetCache($cacheproposaltype, $sProposalType);
        // // session()->put('proposaltype', $sProposalType);
        // $cachepolicytpe = 'cache_policytpe_' . $userId;
        // SetCache($cachepolicytpe, $sPolicyType);

        // $curl = curl_init();


        // $postdata = json_encode([
        //     "objPolicyEntryETT" => [
        //         "ReferenceNo" => "123456",//unique each request
        //         "ProdCode" => $sProdCode,
        //         "PolicyFromDt" => $PolicyFromDate ?? "",//'02-05-2025',
        //         "PolicyToDt" => $PolicyToDate ?? "",//"1-05-2026",
        //         "PolicyIssueDt" => $PolicyFromDate ?? "",//'02-05-2025',
        //         "InsuredPrefix" => "1",//mr/mrs
        //         "InsuredName" => $oJourneyData->name ?? "",//name
        //         "Gender" => $AuthUser['gender'][0] ?? 'M',
        //         "Address1" => $permanentAddress['address1'] ?? "",
        //         "Address2" => $permanentAddress['address2'] ?? "",
        //         "Address3" => $permanentAddress['landmark'] ?? "",
        //         "State" => $sState ?? "",
        //         "City" => $permanentAddress['city'] ?? "",
        //         "PinCode" => $permanentAddress['pincode'] ?? "",//manually 
        //         "GSTNo" => "",//$gstno ?? "",//in  case o=of corporate
        //         "TelephoneNo" => "",
        //         "ProposalType" => $sProposalType ?? "",
        //         "PolicyType" => $sPolicyType ?? "",//"MOT-PLT-009",//$sPolicyType,
        //         "DateOfBirth" => Carbon::createFromFormat('d-m-Y', $dob)->format('Y-m-d'),//$dob,//kyc dob
        //         "MobileNo" => $AuthUser['mobile'] ?? "",
        //         "FaxNo" => "",
        //         "EmailID" => $AuthUser['email'] ?? '',
        //         "POSAgentName" => "NANDITA TIWARI",//credential
        //         "POSAgentPanNo" => "BTYPB4567K",//up same
        //         "CoverNoteNo" => "",
        //         "CoverNoteDt" => "",
        //         "VehicleCode" => $sVehicleCode ?? "UL8066",
        //         "FirstRegDt" => $sRegdate ?? "",
        //         "VehicleType" => $VehicleType,
        //         "EngineNo" => $EngineNo ?? "",
        //         "ChassisNo" => $ChassisNo ?? "",
        //         "RegNo1" => $sRegNo1 ?? "",
        //         "RegNo2" => $sRegNo2 ?? "",
        //         "RegNo3" => $sRegNo3 ?? "",
        //         "RegNo4" => $sRegNo4 ?? "",
        //         "RTOCode" => $sRegNo1 ?? "" . '-' . $sRegNo2 ?? "",
        //         "IDV_of_Vehicle" => $nIdv ?? "",
        //         "Colour" => "",
        //         "VoluntaryExcess" => in_array('120', $aAddons) ? "1" : "0",    //$VoluntaryExcess??"",//skip
        //         "NoEmpCoverLL" => in_array('122', $aAddons) ? "1" : "0", //$NoEmpCoverLL??"",//skip
        //         "NoOfCleaner" => "",//skip
        //         "NoOfDriver" => "0",//skip
        //         "NoOfConductor" => "",//skip
        //         "VehicleMadeinindiaYN" => "N",
        //         "VehiclePurposeYN" => "",
        //         "NFPP_Employees" => "",
        //         "NFPP_OthThanEmp" => "",
        //         "LimitOwnPremiseYN" => "N",
        //         "Bangladesh" => ($Geographical == 1) ? "1" : "0",
        //         "Bhutan" => ($Geographical == 1) ? "1" : "0",
        //         "SriLanka" => ($Geographical == 1) ? "1" : "0",
        //         "Nepal" => ($Geographical == 1) ? "1" : "0",
        //         "Pakistan" => ($Geographical == 1) ? "1" : "0",
        //         "Maldives" => ($Geographical == 1) ? "1" : "0",
        //         "CNGKitYN" => array_key_exists('cng', $aResult) ? "Y" : "N",
        //         "CNGKitSI" => array_key_exists('cng', $aResult) ? $aResult['cng'] : '',
        //         "InBuiltCNGKit" => "0",
        //         "LimitedTPPDYN" => in_array('121', $aAddons) ? "1" : "0",//$LimitedTPPDYN??"",
        //         "DeTariff" => 0,
        //         "IMT23YN" => "",
        //         "BreakIn" => "NO",//----in expired case
        //         "PreInspectionReportYN" => "0",
        //         "PreInspection" => "",
        //         "FitnessCertificateno" => "",
        //         "FitnessValidupto" => "",
        //         "VehPermit" => "",
        //         "PermitNo" => "",
        //         "PAforUnnamedPassengerYN" => $PAforUnnamedPassenger ?? "0",
        //         "PAforUnnamedPassengerSI" => ($PAforUnnamedPassenger == 1) ? $PAforUnnamedamount : "0",
        //         "ElectricalaccessYN" => array_key_exists('electrical', $aResult) ? "Y" : "N",
        //         "ElectricalaccessSI" => array_key_exists('electrical', $aResult) ? $aResult['electrical'] : '',
        //         "ElectricalaccessRemarks" => "",
        //         "NonElectricalaccessYN" => array_key_exists('non-electrical', $aResult) ? "Y" : "N",
        //         "NonElectricalaccessSI" => array_key_exists('non-electrical', $aResult) ? $aResult['non-electrical'] : '',
        //         "NonElectricalaccessRemarks" => "",
        //         "PAPaidDriverConductorCleanerYN" => 0,
        //         "PAPaidDriverConductorCleanerSI" => 0,
        //         "PAPaidDriverCount" => "0",
        //         "PAPaidConductorCount" => "",
        //         "PAPaidCleanerCount" => "",
        //         "NomineeNameforPAOwnerDriver" => $aNominee['nomineename'] ?? "unknown",
        //         "NomineeAgeforPAOwnerDriver" => $aNominee['nomineedob'] ? date('Y') - (int) last(explode('-', $aNominee['nomineedob'])) : '28',
        //         "NomineeRelationforPAOwnerDriver" => $aNominee['nomineerelation'] ?? "BROTHER",
        //         "AppointeeNameforPAOwnerDriver" => $aNominee['appointeename'] ?? "",
        //         "AppointeeRelationforPAOwnerDriver" => $aNominee['appointeerelation'] ?? "",
        //         "LLtoPaidDriverYN" => in_array('116', $aAddons) ? "1" : "0",//$LLtoPaidDriverYN??"",
        //         "AntiTheftYN" => in_array('119', $aAddons) ? "1" : "0", //$AntiTheftYN??"",
        //         "PreviousPolicyNo" => $prevPolicyNo ?? "",    //"12345678",
        //         "PreviousInsurer" => $prevInsurCmpny ?? "",   //"Tata AIG General Insurance Co Ltd",
        //         "PreviousPolicyFromDt" => $sPrevexpfDate ?? "",
        //         "PreviousPolicyToDt" => $sPrevexptoDate ?? "",
        //         "PreviousPolicySI" => "",
        //         "PreviousPolicyClaimYN" => $claim ?? "",
        //         "PreviousPolicyUWYear" => "",
        //         "PreviousPolicyNCBPerc" => $claimper ?? "",
        //         "TRANSFEROFOWNER" => $transferOfowner ?? "",
        //         "PreviousPolicyType" => $sPrePolicyType ?? "",  //"MOT-PLT-009",
        //         "AddonPackage" => "",
        //         "NilDepreciationCoverYN" => in_array('101', $aAddons) ? "Y" : "N",
        //         "PreviousNilDepreciation" => in_array('101', $aAddons) ? "1" : "0",
        //         "HypothecationType" => array_key_exists('bankloantype', $HypothData) ? $HypothData['bankloantype'] : '',
        //         "HypothecationBankName" => "",
        //         "HypothecationAddress1" => "",
        //         "HypothecationAddress2" => "",
        //         "HypothecationAddress3" => "",
        //         "HypothecationAgreementNo" => "",
        //         "HypothecationCountry" => "INDIA",
        //         "HypothecationState" => "",
        //         "HypothecationCity" => "",
        //         "HypothecationPinCode" => "",
        //         "SpecifiedPersonField" => "",
        //         "PAOwnerDriverExclusion" => $PAcover ?? "0",
        //         "PAOwnerDriverExReason" => $pAcoverReason ?? "",
        //         "CPAInsComp" => "",
        //         "CPAPolicyFmDt" => "",
        //         "CPAPolicyNo" => "",
        //         "CPAPolicyToDt" => "",
        //         "CPASumInsured" => "",
        //         "LossOfPersonBelongYN" => in_array('107', $aAddons) ? "Y" : "N",
        //         "SHRIMOTORPROTECTION_YN" => in_array('113', $aAddons) ? "Y" : "N",
        //         "MultiCarBenefitYN" => in_array('110', $aAddons) ? "Y" : "N",
        //         "DepDeductWaiverYN" => in_array('101', $aAddons) ? "Y" : "N",
        //         "DailyExpRemYN" => in_array('109', $aAddons) ? "Y" : "N",
        //         "RSACover" => in_array('102', $aAddons) ? "Y" : "N",
        //         "InvReturnYN" => in_array('106', $aAddons) ? "Y" : "N",
        //         "Eng_Protector" => in_array('104', $aAddons) ? "Y" : "N",
        //         "Consumables" => in_array('103', $aAddons) ? "Y" : "N",
        //         "EmergencyTranHotelExpRemYN" => in_array('108', $aAddons) ? "Y" : "N",
        //         "KeyReplacementYN" => in_array('111', $aAddons) ? "Y" : "N",
        //         "tpPolAddr" => $sRtocity ?? "Jaipur",
        //         "tpPolComp" => $tpInsurCmpny ?? "",
        //         "tpPolFmdt" => $sPretpfDate ?? "",
        //         "tpPolNo" => $nTpolicyNo ?? '',
        //         "tpPolTodt" => $sPretptoDate ?? "",
        //         "CKYC_NO" => "",
        //         "DOB" => $dob ?? "",
        //         "POI_Type" => "PAN",
        //         "POI_ID" => "AVSPV4566D",
        //         "POA_Type" => "PROOF OF POSSESSION OF AADHAR",
        //         "POA_ID" => "5380",
        //         "FatherName" => $aIdDetails['fathername'] ?? '',
        //         "MotherName" => $aIdDetails['fathername'] ?? '',
        //         "MaritalStatus" => "",
        //         "SpouseName" => "",
        //         "ResidentialStatus" => "",
        //         "PHYSICALPOLICY" => $aNominee['physicalpolicy'] ?? "0",
        //         "POI_DocumentFile" => $identityPhotoB64['based64'] ?? "",
        //         "POA_DocumentFile" => $addressPhotoB64['based64'] ?? "",
        //         "Insured_photo" => $insurePhotoB64['based64'] ?? "",
        //         "POI_DocumentExt" => $identityPhotoB64['extension'] ?? "",
        //         "POA_DocumentExt" => $addressPhotoB64['extension'] ?? "",
        //         "Insured_photoExt" => $insurePhotoB64['extension'] ?? "",
        //         "PANorForm60" => "PAN",
        //         "PanNo" => "AVSPV4566D",
        //         "Pan_Form60_Document" => $identityPhotoB64['based64'] ?? "",
        //         "Pan_Form60_Document_Ext" => $identityPhotoB64['extension'] ?? "",
        //         "Pan_Form60_Document_Name" => "1"
        //     ]
        // ]);

        $post = [
            "userid" => "webservice@zoominsurance.com",
            "password" => "newpas12",
            "vehiclecode" => "38161618",
            "city" => "MUMBAI",
            "weomotpolicyin" => [
                "contractid" => "0",
                "poltype" => 3,
                "product4digitcode" => 1801,
                "deptcode" => "18",
                "branchcode" => 1149,
                "termstartdate" => "11-NOV-2025",
                "termenddate" => "10-NOV-2026",
                "tpfintype" => "0",
                "hypo" => "",
                "vehicletypecode" => "22",
                "vehicletype" => "Private Car",
                "miscvehtype" => "0",
                "vehiclemakecode" => "107",
                "vehiclemake" => "HONDA",
                "vehiclemodelcode" => "28",
                "vehiclemodel" => "CITY",
                "vehiclesubtypecode" => "2",
                "vehiclesubtype" => "1.3 EXI",
                "fuel" => "P",
                "zone" => "A",
                "engineno" => "",
                "chassisno" => "",
                "registrationno" => "MH01",
                "registrationdate" => "",
                "registrationlocation" => "MUMBAI",
                "regilocother" => "MUMBAI",
                "carryingcapacity" => "5",
                "cubiccapacity" => "1343",
                "yearmanf" => "2020",
                "color" => "",
                "vehicleidv" => "0",
                "ncb" => "0",
                "addloading" => "0",
                "addloadingon" => "0",
                "spdiscrate" => "0",
                "elecacctotal" => "0",
                "nonelecacctotal" => "0",
                "prvpolicyref" => "RANDOM_POLICY_NUMBER",
                "prvexpirydate" => "",
                "prvinscompany" => "33",
                "prvncb" => "0",
                "prvclaimstatus" => "1",
                "automembership" => "",
                "partnertype" => "p"
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
            "paddoncoverlist" => [],
            "motextracover" => [
                "geogextn" => "0",
                "noofpersonspa" => "5",
                "suminsuredpa" => "0",
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
                "subimdcode" => "0",
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
                "extcol10" => "",
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
                "extcol40" => "AHWPA1451C"
            ],
            "transactionid" => "0",
            "transactiontype" => "MOTOR_WEBSERVICE",
            "contactno" => "9999912123"
        ];



        //return $postdata;
        //dd($postdata);
        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => $url,
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'POST',
        //     CURLOPT_POSTFIELDS => $postdata,
        //     CURLOPT_HTTPHEADER => array(
        //         'Username: ' . self::$Username,
        //         'Password: ' . self::$Password,
        //         'Content-Type: application/json',
        //         'Accept: application/json',
        //         'Cookie: ASP.NET_SessionId=3h5sofst43z4xtc5kse54rjy'
        //     ),
        // ));
        // //die;
        // $response = curl_exec($curl);

        // Logfunction($userId, "shriram", $response, $postdata, "car");
        // \Log::info(['privateCarProposal_shriram' => $response]);
        // curl_close($curl);
        // return $response;
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'status' => false,
        //         'msg' => $e->getMessage()
        //     ]);
        // }

    }
}