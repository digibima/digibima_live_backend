<?php
namespace App\Services\Api\BajajMotor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Shriram\{Shriram_Pincode, Shriram_planCheckout, Shriram_RTO_Master, Shriram_Prev_insurence, Shriram_Vehicle_Master};
use App\Models\{Master_Vehicle_Data as DataModel, MasterAPI, User, MotorJourney, MasterVendor, VendorMotor, MasterMotor, UserMotorDescription, Vehicle_Info};
use Illuminate\Support\Facades\{Auth, Cache};
use App\Models\Bajaj\RtaMaster;
class BajajBikeService
{
    private static $Id = "webservice.pos@digibima.com";
    private static $vehicletypecode = "21";
    private static $password = "Newpas12";
    private static $deptcode = "18";

    public static function generateBikeQuote($userId)
    {
        try {
            //$userId = $request->userid;

            $user = User::find($userId);
            $today = Carbon::today();
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
            // $Eng_Protector = null;
            // $Consumables = null;
            // $InvReturnYN = null;
            // $LLtoPaidDriverYN = null;
            // $NoEmpCoverLL = null;
            // $SHRIMOTORPROTECTION_YN = null;
            // $LimitedTPPDYN = null;
            // $VoluntaryExcess = null;
            // $AntiTheftYN = null;
            // $PAforUnnamedPassengerYN = null;
            // $Geographical = null;
            // $VehicleType = null;
            // $AuthUser = $user->toArray();
            // $aData = DataModel::where('userid', $userId)->first();
            // $aBikedata = json_decode($aData->knowbike_reg_details, true) ?? [];
            // $aNewBikedata = json_decode($aData->newbike_reg_details, true) ?? [];
            // $PAcover = null;
            // $pAcoverReason = null;
            // //dd(session('motortype'));
            // $sRegNumber = "";
            // $sRegNo2 = "";
            // $sRegNo3 = "";
            // $sRegNo1 = "";
            // $sRegNo4 = "";
            // $cachemotortype = 'cache_motortype_' . $userId;
            // $cachebikePolicyexp = 'cache_bikepolicyexp_' . $userId;
            // $PAforUnnamedamount = json_decode($aData->bikeaddonvalue, true);
            // $PAforUnnamedamount = !empty($PAforUnnamedamount) ? $PAforUnnamedamount : "0";
            // //$aAddons = json_decode($aData->bikeaddon, true) ?? [];
            // $aBikeAddon = is_string($aData->bikeaddon)
            //     ? json_decode($aData->bikeaddon, true)
            //     : (array) $aData->bikeaddon;

            // // Pick based on which exists
            // $aAddons = !empty($aBikeAddon['tpselectedaddon'])
            //     ? $aBikeAddon['tpselectedaddon']
            //     : (
            //         !empty($aBikeAddon['selectedaddon'])
            //         ? $aBikeAddon['selectedaddon']
            //         : (
            //             !empty($aBikeAddon['odselectedaddon'])
            //             ? $aBikeAddon['odselectedaddon']
            //             : []
            //         )
            //     );
            // $aResult = [];
            // if (!empty($aAccessories)) {
            //     foreach ($aAccessories as $item) {
            //         $aResult[$item['type']] = $item['amount'];

            //     }
            // }
            // // return[
            // //     "asdd" => GetCache($cachebikePolicyexp)
            // // ];
            // if (GetCache($cachemotortype) == 'newbike') {
            //     if ($aNewBikedata['under'] == "company") {
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
            //     $oModel = Shriram_Vehicle_Master::where('id', $aNewBikedata['model'])->first();
            //     $sRegNumber = substr(explode('(', $aData->rtocode)[1], 0, -1);
            //     $sRegNo1 = substr(str_replace('-', '', $sRegNumber), 0, 2);//k
            //     $sRegNo2 = substr(str_replace('-', '', $sRegNumber), 2, 2);//k
            // } else {
            //     if ($aBikedata['under'] == "company") {
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
            //     $oModel = Shriram_Vehicle_Master::where('id', $aBikedata['model'])->first();
            //     $sRegNumber = $aData->bikenumber;
            //     $sRegNo1 = substr($sRegNumber, 0, 2);//k
            //     $sRegNo2 = substr($sRegNumber, 2, 2);//k
            //     $sRegNo3 = substr($sRegNumber, 4, 2);//k
            //     $sRegNo4 = substr($sRegNumber, 6, 4);//k
            // }
            // //dd($sRegNumber);

            // if (GetCache($cachemotortype) == 'knowbike') {
            //     if ($aBikedata['prepolitype'] == 'odonly') {
            //         $sPrePolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.OWNDAMAGE");
            //         $sPrevexptoDate = $aBikedata['odtodate']; //k
            //         $sPrevexpfDate = $aBikedata['odfromdate']; //k
            //         $sPretpfDate = $aBikedata['odtpfromdate'];
            //         $sPretptoDate = $aBikedata['odtptodate'];
            //         //$nTpolicyNo = array_key_exists('tppolicynumber', $prevPolicydata) ? $prevPolicydata['tppolicynumber'] : '';
            //         $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
            //         $PolicyFromDate = $date->addDay()->format('d-m-Y');
            //         $PolicyToDate = $date->addYear()->subDay()->format('d-m-Y');

            //     } else if ($aBikedata['prepolitype'] == 'bundled') {
            //         $sPrePolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.BUNDLED");
            //         $sPrevexptoDate = $aBikedata['bdtodate']; //k
            //         $sPrevexpfDate = $aBikedata['bdfromdate']; //k
            //         $sPretpfDate = $aBikedata['bdtpfromdate'];
            //         $sPretptoDate = $aBikedata['bdtptodate'];
            //         //$nTpolicyNo = array_key_exists('tppolicynumber', $prevPolicydata) ? $prevPolicydata['tppolicynumber'] : '';
            //         $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
            //         $PolicyFromDate = $date->addDay()->format('d-m-Y');
            //         $PolicyToDate = $date->addYear()->subDay()->format('d-m-Y');

            //     } elseif ($aBikedata['prepolitype'] == 'comprehensive') {
            //         $sPrePolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.PACKAGE");
            //         $sPrevexptoDate = $aBikedata['comptodate']; //k
            //         $sPrevexpfDate = $aBikedata['compfromdate'];
            //         $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
            //         $PolicyFromDate = $date->addDay()->format('d-m-Y');
            //         $PolicyToDate = $date->addYear()->subDay()->format('d-m-Y');
            //         //dd($PolicyFromDate,$PolicyToDate);

            //     } elseif ($aBikedata['prepolitype'] == 'tponly') {
            //         $sPrePolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.LIABILITY");
            //         $sPrevexptoDate = $aBikedata['tptodate']; //k
            //         $sPrevexpfDate = $aBikedata['tpfromdate']; //k
            //         $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
            //         $PolicyFromDate = $date->addDay()->format('d-m-Y');
            //         $PolicyToDate = $date->addYear()->subDay()->format('d-m-Y');
            //         //dd($PolicyFromDate,$PolicyToDate);

            //     }

            // }

            // $oJourneyData = MotorJourney::where('userid', $userId)->where('is_bike', '1')->first();

            // $permanentAddress = isset($oJourneyData->permanent_address) && json_decode($oJourneyData->permanent_address, true) ? json_decode($oJourneyData->permanent_address, true) : [];
            // $pincode = $user->pincode;
            // $state = Shriram_Pincode::where('PC_CODE', $pincode)->first();
            // if (!$state) {
            //     return [
            //         'status' => '0',
            //         'message' => 'Pincode not available for this service.'
            //     ];
            // }
            // //dd($pincode,$state);
            // $sState = $state->STATE;
            // if (GetCache($cachemotortype) == "knowbike") {
            //     $firstregdate = array_key_exists('bikeregdate', $aBikedata) ? $aBikedata['bikeregdate'] : '';
            //     $claim = array_key_exists('policytoggle', $aBikedata) ? '1' : '0';
            //     $claimper = array_key_exists('bonus-button', $aBikedata) ? $aBikedata['bonus-button'] : '0';
            //     $transferOfowner = array_key_exists('ownershiptoggle', $aBikedata) ? $aBikedata['ownershiptoggle'] : '0';
            //     $oModel = Shriram_Vehicle_Master::where('id', $aBikedata['model'])->first();
            //     //dd($aBikedata,$firstregdate,$sModel);
            // }
            // if (GetCache($cachemotortype) == "newbike") {
            //     $aNewBikedata = json_decode($aData->newbike_reg_details, true);
            //     $firstregdate = $today->format('d-m-Y');
            //     $oModel = Shriram_Vehicle_Master::where('id', $aNewBikedata['model'])->first();
            // }
            // $sVehicleCode = " ";
            // if ($oModel) {
            //     $sVehicleCode = $oModel->VEHICLE_CODE;

            // }
            // // $url = MasterAPI::where('apicode', '115')->first()->apistring;
            // $sProdCode = getconstant("MOTOR.SHRIRAM.PRODUCTTYPE.TWOWHEELER");
            // $sPolicyType = "";
            // $sProposalType = "";
            // if (GetCache($cachemotortype) == "newbike") {
            //      $VehicleType = "W";
            //     $sProposalType = getconstant("MOTOR.SHRIRAM.PROPOSALTYPE.FRESHPROPOSAL");
            //     if ($nPlanType == '2') {
            //         $sPolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.BUNDLED");
            //         $PolicyFromDate = $today->format('d-m-Y');
            //         //$today = Carbon::today();
            //         $PolicyToDate = $today->addYears(3)->subDay()->format('d-m-Y');
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
            //         $Geographical = in_array('117', $aAddons) ? 1 : "0";
            //         $Geographical = in_array('118', $aAddons) ? 1 : "0";
            //         $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
            //     }
            // }
            // if (GetCache($cachemotortype) == "knowbike") {
            //     $sProposalType = getconstant("MOTOR.SHRIRAM.PROPOSALTYPE.MARKETRENEWAL");
            //     $VehicleType = "U";
            //     if ($nPlanType == '1') {
            //         $sPolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.OWNDAMAGE");
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
            //     }
            //     if ($nPlanType == '2') {
            //         $sPolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.PACKAGE");
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
            //     }
            //     if ($nPlanType == '3' && (GetCache($cachebikePolicyexp) == "Expired")) {
            //         $sPolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.LIABILITY");
            //         $PolicyFromDate = $today->format('d-m-Y');
            //         $PolicyToDate = $today->addYear()->subDay()->format('d-m-Y');
            //         $LLtoPaidDriverYN = in_array('116', $aAddons) ? "1" : "0";
            //         $NoEmpCoverLL = in_array('122', $aAddons) ? "1" : "0";
            //         $LimitedTPPDYN = in_array('121', $aAddons) ? "1" : "0";
            //         $Geographical = in_array('118', $aAddons) ? 1 : "0";
            //         $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
            //     }
            //     if ($nPlanType == '3' && (GetCache($cachebikePolicyexp) == "Not Expired")) {
            //         $sPolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.LIABILITY");
            //         $LLtoPaidDriverYN = in_array('116', $aAddons) ? "1" : "0";
            //         $NoEmpCoverLL = in_array('122', $aAddons) ? "1" : "0";
            //         $LimitedTPPDYN = in_array('121', $aAddons) ? "1" : "0";
            //         $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
            //         $Geographical = in_array('118', $aAddons) ? 1 : "0";
            //     }
            // }


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
            // //  return[
            // //     "asdf"=>GetCache($cachebikePolicyexp),
            // //     "PolicyFromDate"=>$PolicyFromDate,
            // //     "PolicyToDate"=>$PolicyToDate
            // // ];
            // //dd($sProposalType,$nPlanType,$sRegNo4,$sRegNo3, $sRegNo2,$sRegNo1);

            // $jPostField = json_encode([
            //     "objPolicyEntryETT" => [
            //         "ReferenceNo" => "",
            //         "ProdCode" => $sProdCode,
            //         "PolicyFromDt" => $PolicyFromDate,
            //         "PolicyToDt" => $PolicyToDate,
            //         "PolicyIssueDt" => $PolicyFromDate,
            //         "InsuredPrefix" => "1",
            //         "InsuredName" => $AuthUser['name'],
            //         "Gender" => $AuthUser['gender'][0] ?? '',
            //         "Address1" => "",
            //         "Address2" => "",
            //         "Address3" => "",
            //         "State" => $sState,
            //         "City" => $sRtocity,
            //         "PinCode" => $AuthUser['pincode'],
            //         "PanNo" => null,
            //         "GSTNo" => null,
            //         "TelephoneNo" => "",
            //         "ProposalType" => $sProposalType,//"FRESH",
            //         "PolicyType" => $sPolicyType,//"MOT-PLT-010",
            //         "DateOfBirth" => "",
            //         "FaxNo" => "",
            //         "POSAgentName" => "",
            //         "POSAgentPanNo" => "",
            //         "CoverNoteNo" => "",
            //         "CoverNoteDt" => "",
            //         "VehicleCode" => $sVehicleCode ?? 'M_21989',
            //         "FirstRegDt" => $firstregdate ?? "",
            //         "VehicleManufactureYear" => "2016",
            //         "VehicleType" =>$VehicleType??"",
            //         "EngineNo" => "",
            //         "ChassisNo" => "",
            //         "RegNo1" => $sRegNo1,
            //         "RegNo2" => $sRegNo2,
            //         "RegNo3" => $sRegNo3,
            //         "RegNo4" => $sRegNo4,
            //         "RTOCode" => $sRegNo1 . '-' . $sRegNo2,
            //         "IDV_of_Vehicle" => $idv ?? "0",
            //         "Colour" => "",
            //         "VoluntaryExcess" => $VoluntaryExcess ?? "0",
            //         "NoEmpCoverLL" => $NoEmpCoverLL ?? "0",
            //         "NoOfCleaner" => "",
            //         "NoOfDriver" => "0",
            //         "NoOfConductor" => "",
            //         "VehicleMadeinindiaYN" => "",
            //         "VehiclePurposeYN" => "",
            //         "NFPP_Employees" => "",
            //         "NFPP_OthThanEmp" => "",
            //         "LimitOwnPremiseYN" => "",
            //         "PAYEAR" => "1",
            //         "Bangladesh" => ($Geographical == 1) ? "1" : "0",
            //         "Bhutan" => ($Geographical == 1) ? "1" : "0",
            //         "SriLanka" => ($Geographical == 1) ? "1" : "0",
            //         "Nepal" => ($Geographical == 1) ? "1" : "0",
            //         "Pakistan" => ($Geographical == 1) ? "1" : "0",
            //         "Maldives" => ($Geographical == 1) ? "1" : "0",
            //         "CNGKitYN" => array_key_exists('cng', $aResult)?"Y":"N",
            //         "CNGKitSI" => array_key_exists('cng', $aResult) ? $aResult['cng'] : '',
            //         "InBuiltCNGKit" => 0,
            //         "LimitedTPPDYN" => $LimitedTPPDYN ?? "0",
            //         "DeTariff" => 0,
            //         "IMT23YN" => "",
            //         "BreakIn" => "No",
            //         "PreInspectionReportYN" => "0",
            //         "PreInspection" => "",
            //         "FitnessCertificateno" => "",
            //         "FitnessValidupto" => "",
            //         "VehPermit" => "",
            //         "PermitNo" => "",
            //         "PAforUnnamedPassengerYN" => $PAforUnnamedPassengerYN ?? "0",
            //         "PAforUnnamedPassengerSI" => ($PAforUnnamedPassengerYN == 1) ? $PAforUnnamedamount : "",
            //         "ElectricalaccessYN" => array_key_exists('electrical', $aResult)?"Y":"N",
            //         "ElectricalaccessSI" => array_key_exists('electrical', $aResult) ? $aResult['electrical'] : '',
            //         "ElectricalaccessRemarks" => "",
            //         "NonElectricalaccessYN" => array_key_exists('non-electrical', $aResult)?"Y":"N",
            //         "NonElectricalaccessSI" => array_key_exists('non-electrical', $aResult)?$aResult['non-electrical'] : '',
            //         "NonElectricalaccessRemarks" => "",
            //         "PAPaidDriverConductorCleanerYN" => $PAPaidDriverConductorCleaner ?? "1",
            //         "PAPaidDriverConductorCleanerSI" => "0",
            //         "PAPaidDriverCount" => "1",
            //         "PAPaidConductorCount" => "1",
            //         "PAPaidCleanerCount" => "1",
            //         "NomineeNameforPAOwnerDriver" => "",
            //         "NomineeAgeforPAOwnerDriver" => "",
            //         "NomineeRelationforPAOwnerDriver" => "",
            //         "AppointeeNameforPAOwnerDriver" => "",
            //         "AppointeeRelationforPAOwnerDriver" => "",
            //         "LLtoPaidDriverYN" => $LLtoPaidDriverYN ?? "0",
            //         "AntiTheftYN" => $AntiTheftYN ?? "0",
            //         "PreviousPolicyNo" => "",
            //         "PreviousInsurer" => "",
            //         "PreviousPolicyFromDt" => $sPrevexpfDate,
            //         "PreviousPolicyToDt" => $sPrevexptoDate,
            //         "PreviousPolicySI" => "",
            //         "TRANSFEROFOWNER" => $transferOfowner ?? "",
            //         "PreviousPolicyClaimYN" => $claim,
            //         "PreviousPolicyUWYear" => "",
            //         "PreviousPolicyNCBPerc" => $claimper,
            //         "PreviousPolicyType" => $sPrePolicyType ?? "",
            //         "tpPolFmdt" => $sPretpfDate ?? "",
            //         "tpPolTodt" => $sPretptoDate ?? "",
            //         "NilDepreciationCoverYN" => $NilDepreciationCoverYN ?? "0",
            //         "PreviousNilDepreciation" => "0",
            //         "InvReturnYN" => $InvReturnYN ?? "",
            //         "RSACover" => $RSACover ?? "",
            //         "LossOfPersonBelongYN" => $LossOfPersonBelongYN ?? "",
            //         "DailyExpRemYN" => $DailyExpRemYN ?? "",
            //         "KeyReplacementYN" => $KeyReplacementYN ?? "",
            //         "SHRIMOTORPROTECTION_YN" => $SHRIMOTORPROTECTION_YN ?? "",
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
            //         "PAOwnerDriverExclusion" => $PAcover ?? "",
            //         "PAOwnerDriverExReason" => $pAcoverReason ?? "",
            //         "CPAInsComp" => "",
            //         "CPAPolicyFmDt" => "",
            //         "CPAPolicyNo" => "",
            //         "CPAPolicyToDt" => "",
            //         "CPASumInsured" => "",
            //         "Consumables" => $Consumables ?? "",
            //         "Eng_Protector" => $Eng_Protector ?? ""
            //     ]
            // ]);

            $cachemotortype = 'cache_motortype_' . $userId;
            $pincode = $user->pincode;
            $state = RtaMaster::where('pincode', $pincode)->first();
            if (!$state) {
                return [
                    'status' => '0',
                    'message' => 'Pincode not available for this service.'
                ];
            }
            $sState = $state->city;
            $zone = $state->zone;
            $oDataModel = DataModel::where('userid', $userId)->first();
            $cachebikeidv = 'cache_' . $userId . '_bikeidv';
            $idv = GetCache($cachebikeidv);
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
                    $PolicyToDate = $today->addYear()->subDay()->format('Y-m-d');
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
                    $PolicyToDate = $today->addYear()->subDay()->format('Y-m-d');

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
                    $PolicyToDate = $today->addYear()->subDay()->format('Y-m-d');
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
                    $PolicyToDate = $today->addYear()->subDay()->format('Y-m-d');
                }
            }

            $password = self::$password;
            $Id = self::$Id;
            $vehicletypecode = self::$vehicletypecode;
            $deptcode = self::$deptcode;
            $claim = array_key_exists('bonus-button', $aBikedata) ? $aBikedata['bonus-button'] : '0';

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
                    "termstartdate" =>$PolicyFromDate, //$StartDate,
                    "termenddate" =>$PolicyToDate, //$EndDate,
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
                    "registrationlocation" => $sState,
                    "regilocother" => $sState,
                    "carryingcapacity" => "2",
                    "cubiccapacity" => "110",
                    "yearmanf" => $year,
                    "color" =>"", //"RED",
                    "vehicleidv" =>$idv ?? "0",
                    "ncb" =>"0",//$claim ?? "0",
                    "addloading" => "0",
                    "addloadingon" => "0",
                    "spdiscrate" => "0",
                    "elecacctotal" => "0",
                    "nonelecacctotal" => "0",
                    "prvpolicyref" => "",
                    "prvexpirydate" =>$EndDate ?? "",
                    "prvinscompany" => "0",
                    "prvncb" => "0",
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
                    "extcol10" => "DRIVE_ASSURE_SILVER",
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
            //return $post;

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

    public static function generateBikeProposal(Request $request, $today, $nextyear, $aData)
    {
        try {
            $userId = $request->userid;
            // $user = User::find($userId);
            // $AuthUser = $user->toArray();
            // $today = now();
            // $AuthUser = User::find($userId)->toArray();
            // $aData = DataModel::where('userid', $userId)->first();
            // $oJourneyData = MotorJourney::where('userid', $userId)->where('is_bike', '1')->first();
            // $prevPolicydata = json_decode($oJourneyData->pre_policy_details, true);
            // $HypothData = json_decode($oJourneyData->bank_details, true);
            // $aBikedata = json_decode($aData->knowbike_reg_details, true);
            // $aNewBikedata = json_decode($aData->newbike_reg_details, true);
            // $Geographical = null;
            // $PAforUnnamedPassenger = null;
            // $sPrevexptoDate = null;
            // $sPrevexpfDate = null;
            // $sPretpfDate = null;
            // $sPretptoDate = null;
            // $claim = null;
            // $claimper = null;
            // $transferOfowner = null;
            // $gstno = null;
            // $oModel = null;
            // $pAcoverReason = "";
            // $PAcover = "";
            // $nTpolicyNo = null;
            // $sTpInsurer = null;
            // $prevPolicyNo = null;
            // $sRegdate = "";
            // $VehicleType = null;
            // $cachebikePolicyexp = 'cache_bikepolicyexp_' . $userId;
            // //$sPrevexptoDate = $aBikedata['comptodate']; //k
            // $PAforUnnamedamount = json_decode($aData->caraddonvalue, true);
            // $PAforUnnamedamount = !empty($PAforUnnamedamount) ? $PAforUnnamedamount : "0";

            // $aResult = [];
            // if (!empty($aAccessories)) {
            //     foreach ($aAccessories as $item) {
            //         $aResult[$item['type']] = $item['amount'];

            //     }
            // }
            // $PAcover = $aData->pacover;
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
            // $cachemotortype = 'cache_motortype_' . $userId;
            // $cacheunder = 'cache_under_' . $userId;
            // if (GetCache($cachemotortype) == 'knowbike') {
            //     if ($aBikedata['prepolitype'] == 'odonly') {
            //         $sPrePolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.OWNDAMAGE");
            //         $sPrevexptoDate = $aBikedata['odtodate']; //k
            //         $sPrevexpfDate = $aBikedata['odfromdate']; //k
            //         $sPretpfDate = $aBikedata['odtpfromdate'];
            //         $sPretptoDate = $aBikedata['odtptodate'];
            //         $nTpolicyNo = array_key_exists('tppolicynumber', $prevPolicydata) ? $prevPolicydata['tppolicynumber'] : '';
            //         $sTpInsurer = array_key_exists('tpprevInsurance', $prevPolicydata) ? $prevPolicydata['tpprevInsurance'] : '';
            //         $tpInsurCmpny = Shriram_Prev_insurence::where('id', $sTpInsurer)->first()->insurance;
            //         $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
            //         $PolicyFromDate = $date->addDay()->format('d-m-Y');
            //         $PolicyToDate = $date->addYear()->subDay()->format('d-m-Y');

            //     } else if ($aBikedata['prepolitype'] == 'bundled') {

            //         $sPrePolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.BUNDLED");
            //         $sPrevexptoDate = $aBikedata['bdtodate']; //k
            //         $sPrevexpfDate = $aBikedata['bdfromdate']; //k
            //         $sPretpfDate = $aBikedata['bdtpfromdate'];
            //         $sPretptoDate = $aBikedata['bdtptodate'];
            //         $nTpolicyNo = array_key_exists('tppolicynumber', $prevPolicydata) ? $prevPolicydata['tppolicynumber'] : '';
            //         $sTpInsurer = array_key_exists('tpprevInsurance', $prevPolicydata) ? $prevPolicydata['tpprevInsurance'] : '';
            //         $tpInsurCmpny = Shriram_Prev_insurence::where('id', $sTpInsurer)->first()->insurance;
            //         $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
            //         $PolicyFromDate = $date->addDay()->format('d-m-Y');
            //         $PolicyToDate = $date->addYear()->subDay()->format('d-m-Y');

            //     } elseif ($aBikedata['prepolitype'] == 'comprehensive') {
            //         $sPrePolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.PACKAGE");
            //         $sPrevexptoDate = $aBikedata['comptodate']; //k
            //         $sPrevexpfDate = $aBikedata['compfromdate'];
            //         $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
            //         $PolicyFromDate = $date->addDay()->format('d-m-Y');
            //         $PolicyToDate = $date->addYear()->subDay()->format('d-m-Y');
            //         //dd($sPrevexptoDate);
            //     } elseif ($aBikedata['prepolitype'] == 'tponly') {
            //         //dd($aBikedata);
            //         $sPrePolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.LIABILITY");
            //         $sPrevexptoDate = $aBikedata['tptodate']; //k
            //         $sPrevexpfDate = $aBikedata['tpfromdate']; //k
            //         $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
            //         $PolicyFromDate = $date->addDay()->format('d-m-Y');
            //         $PolicyToDate = $date->addYear()->subDay()->format('d-m-Y');
            //     }
            // }
            // //$oVehicleInfo = Vehicle_Info::where('MODEL_DESCRIPTION', $sModel)->first();
            // $sRegNumber = "";
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
            // $aNominee = $oJourneyData->nominee_details ? jdec($oJourneyData->nominee_details) : [];
            // $permanentAddress = json_decode($oJourneyData->permanent_address, true) ?? [];
            // $pincode = $permanentAddress['pincode'];
            // $state = Shriram_Pincode::where('PC_CODE', $pincode)->first();
            // $sState = $state->STATE;
            // $nPlanType = $aData->bike_plan_type;
            // $aBikeAddon = is_string($aData->bikeaddon)
            //     ? json_decode($aData->bikeaddon, true)
            //     : (array) $aData->bikeaddon;

            // // Pick based on which exists
            // $aAddons = !empty($aBikeAddon['tpselectedaddon'])
            //     ? $aBikeAddon['tpselectedaddon']
            //     : (
            //         !empty($aBikeAddon['selectedaddon'])
            //         ? $aBikeAddon['selectedaddon']
            //         : (
            //             !empty($aBikeAddon['odselectedaddon'])
            //             ? $aBikeAddon['odselectedaddon']
            //             : []
            //         )
            //     );
            // //$aAddons = json_decode($aData->bikeaddon, true) ?? [];
            // //$aCacheData = json_decode(Cache::store('mysql_cache')->get("user_" . $userId . "_bikequote_key"), true) ?? [];
            // //dd($aCacheData,$aAddons);
            // //$aGetData = $oObj->getCacheBikeQuote($request, $aCacheData[getconstant("MOTOR.SHRIRAM.KEY")][$nPlanType]);
            // //$nIdv = $aGetData['data']['idv'];
            // $cachebikeidv = 'cache_' . $userId . '_bikeidv';
            // $nIdv = GetCache($cachebikeidv);
            // $randomValue = self::genRandomNumber();
            // $EngineNo = "GDFG59D4GD6546D" . $randomValue;//$aVehicledetails['Enginenumber'];
            // $ChassisNo = "GDF45GDFGD4G56D" . $randomValue;//$aVehicledetails['Chassisnumber'];
            // $sProdCode = getconstant("MOTOR.SHRIRAM.PRODUCTTYPE.TWOWHEELER");
            // $sPolicyType = "";
            // $sProposalType = "";

            // if (GetCache($cachemotortype) == "newbike") {
            //     $VehicleType = "W";
            //     $sProposalType = getconstant("MOTOR.SHRIRAM.PROPOSALTYPE.FRESHPROPOSAL");
            //     $companydetails = json_decode($oJourneyData->company_details, true);
            //     if ($aNewBikedata['under'] == "company") {
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
            //         $PolicyToDate = $today->addYears(5)->subDay()->format('d-m-Y');
            //         $Geographical = in_array('118', $aAddons) ? 1 : "0";
            //         $Geographical = in_array('117', $aAddons) ? 1 : "0";
            //         $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";

            //     }
            // }
            // if (GetCache($cachemotortype) == "knowbike") {
            //     $VehicleType = "U";
            //     $prevPolicyNo = $prevPolicydata['policynumber'];
            //     $prevInsurCmpny = Shriram_Prev_insurence::where('id', $prevPolicydata['prevInsuranceId'])->first()->insurance;
            //     if ($aBikedata['under'] == "company") {
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

            //     $claim = array_key_exists('policytoggle', $aBikedata) ? '1' : '0';
            //     $claimper = array_key_exists('bonus-button', $aBikedata) ? $aBikedata['bonus-button'] : '0';
            //     $transferOfowner = array_key_exists('ownershiptoggle', $aBikedata) ? $aBikedata['ownershiptoggle'] : '0';
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
            //     if ($nPlanType == '3' && (GetCache($cachebikePolicyexp) == "Not Expired")) {
            //         $sPolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.LIABILITY");
            //         $Geographical = in_array('118', $aAddons) ? 1 : "0";
            //         $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";

            //     }
            //     if ($nPlanType == '3' && (GetCache($cachebikePolicyexp) == "Expired")) {
            //         $sPolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.LIABILITY");
            //         $PolicyFromDate = $today->format('d-m-Y');
            //         $PolicyToDate = $today->addYear()->subDay()->format('d-m-Y');
            //         $Geographical = in_array('118', $aAddons) ? 1 : "0";
            //         $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";

            //     }

            // }

            // $sRtocity = getBikeRtocity($sRegNumber);
            // if ($sRtocity) {
            //     $sRtocity = !empty($sRtocity->RTOCITY) ? $sRtocity->RTOCITY : $sRtocity->RTONAME;
            // } else {
            //     $sRtocity = "";
            // }
            // $user = User::find($userId);
            // $dob = !empty($oJourneyData->dob)
            //     ? $oJourneyData->dob
            //     : (!empty($user->dob)
            //         ? $user->dob
            //         : "29-04-2000");


            // // session()->put('productcode', $sProdCode);
            // // session()->put('proposaltype', $sProposalType);
            // // session()->put('policytpe', $sPolicyType);
            // // session()->put('plantype', $nPlanType);

            // $cacheproductcode = 'cache_productcode_' . $userId;
            // SetCache($cacheproductcode, $sProdCode);
            // $cacheproposaltype = 'cache_proposaltype_' . $userId;
            // SetCache($cacheproposaltype, $sProposalType);
            // $cachepolicytpe = 'cache_policytpe_' . $userId;
            // SetCache($cachepolicytpe, $sPolicyType);
            // $cacheplantype = 'cache_plantype_' . $userId;
            // SetCache($cacheplantype, $nPlanType);

            // $curl = curl_init();
            // $randomValue = self::genRandomNumber();
            // //    return[
            // //     "policy"=>GetCache($cachebikePolicyexp),
            // //     'PolicyFromDate'=>$PolicyFromDate,
            // //     'todate' => $PolicyToDate
            // //  ];
            // //dd($PolicyFromDate,$PolicyToDate);
            // $postdata = json_encode([
            //     "objPolicyEntryETT" => [
            //         "ReferenceNo" => "123456",
            //         "ProdCode" => $sProdCode,
            //         "PolicyFromDt" => $PolicyFromDate ?? "",
            //         "PolicyToDt" => $PolicyToDate ?? "",
            //         "PolicyIssueDt" => $PolicyFromDate ?? "",
            //         "InsuredPrefix" => "1",
            //         "InsuredName" => $AuthUser['name'] ?? $AuthUser['name'],
            //         "Gender" => $AuthUser['gender'][0] ?? 'M',
            //         "Address1" => $permanentAddress['address1'] ?? "",
            //         "Address2" => $permanentAddress['address2'] ?? "",
            //         "Address3" => $permanentAddress['landmark'] ?? "",
            //         "State" => $sState ?? "",
            //         "City" => $permanentAddress['city'] ?? "",
            //         "PinCode" => $pincode ?? $permanentAddress['pincode'],
            //         "PanNo" => "",
            //         "GSTNo" => null,
            //         "TelephoneNo" => "",
            //         "ProposalType" => $sProposalType ?? "",
            //         "PolicyType" => $sPolicyType ?? "",//$sPolicyType,
            //         "DateOfBirth" => Carbon::createFromFormat('d-m-Y', $dob)->format('Y-m-d') ?? "12-08-1998",
            //         "MobileNo" => $AuthUser['mobile'] ?? "",
            //         "FaxNo" => "",
            //         "EmailID" => $AuthUser['email'] ?? '',
            //         "POSAgentName" => "NANDITA TIWARI",
            //         "POSAgentPanNo" => "BTYPB4567K",
            //         "CoverNoteNo" => "",
            //         "CoverNoteDt" => "",
            //         "VehicleCode" => $sVehicleCode ?? "UL8066",
            //         "FirstRegDt" => $sRegdate ?? "",
            //         "VehicleType" => $VehicleType ??"",
            //         "EngineNo" => $EngineNo ?? "",
            //         "ChassisNo" => $ChassisNo ?? "",
            //         "RegNo1" => $sRegNo1 ?? "",
            //         "RegNo2" => $sRegNo2 ?? "",
            //         "RegNo3" => $sRegNo3 ?? "",
            //         "RegNo4" => $sRegNo4 ?? "",
            //         "RTOCode" => $sRegNo1 ?? "" . '-' . $sRegNo2 ?? "",
            //         "IDV_of_Vehicle" => $nIdv ?? "",
            //         "Colour" => "",
            //         "VoluntaryExcess" => in_array('120', $aAddons) ? "1" : "0",
            //         "NoEmpCoverLL" => in_array('122', $aAddons) ? "1" : "0",
            //         "NoOfCleaner" => "",
            //         "NoOfDriver" => "0",
            //         "NoOfConductor" => "",
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
            //         "CNGKitYN" => array_key_exists('cng', $aResult) ? "Y":"N",
            //         "CNGKitSI" => array_key_exists('cng', $aResult) ? $aResult['cng'] : '',
            //         "InBuiltCNGKit" => "0",
            //         "LimitedTPPDYN" => in_array('121', $aAddons) ? "1" : "0",
            //         "DeTariff" => 0,
            //         "IMT23YN" => "",
            //         "BreakIn" => "NO",
            //         "PreInspectionReportYN" => "0",
            //         "PreInspection" => "",
            //         "FitnessCertificateno" => "",
            //         "FitnessValidupto" => "",
            //         "VehPermit" => "",
            //         "PermitNo" => "",
            //         "PAforUnnamedPassengerYN" => $PAforUnnamedPassenger ?? "0",
            //         "PAforUnnamedPassengerSI" => ($PAforUnnamedPassenger == 1) ? $PAforUnnamedamount : "0",
            //         "ElectricalaccessYN" => array_key_exists('electrical', $aResult)?"Y":"N",
            //         "ElectricalaccessSI" => array_key_exists('electrical', $aResult) ? $aResult['electrical'] : '',
            //         "ElectricalaccessRemarks" => "",
            //         "NonElectricalaccessYN" => array_key_exists('non-electrical', $aResult)?"Y":"N",
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
            //         "LLtoPaidDriverYN" => in_array('116', $aAddons) ? "1" : "0",
            //         "AntiTheftYN" => in_array('119', $aAddons) ? "1" : "0",
            //         "PreviousPolicyNo" => $prevPolicyNo ?? "",
            //         "PreviousInsurer" => $prevInsurCmpny ?? "",
            //         "PreviousPolicyFromDt" => $sPrevexpfDate ?? "",
            //         "PreviousPolicyToDt" => $sPrevexptoDate ?? "",
            //         "PreviousPolicySI" => "",
            //         "PreviousPolicyClaimYN" => $claim ?? "",
            //         "PreviousPolicyUWYear" => "",
            //         "PreviousPolicyNCBPerc" => $claimper ?? "",
            //         "TRANSFEROFOWNER" => $transferOfowner ?? "",
            //         "PreviousPolicyType" => $sPrePolicyType ?? "",
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
            //         "DailyExpRemYN" => in_array('109', $aAddons) ? "Y" : "N",
            //         "RSACover" => in_array('102', $aAddons) ? "Y" : "N",
            //         "InvReturnYN" => in_array('106', $aAddons) ? "Y" : "N",
            //         "Eng_Protector" => in_array('104', $aAddons) ? "Y" : "N",
            //         "Consumables" => in_array('103', $aAddons) ? "Y" : "N",
            //         "KeyReplacementYN" => in_array('111', $aAddons) ? "Y" : "N",
            //         "SHRIMOTORPROTECTION_YN" => in_array('113', $aAddons) ? "Y" : "N",
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
            //         "MotherName" => "",
            //         "MaritalStatus" => "",
            //         "SpouseName" => "",
            //         "ResidentialStatus" => "",
            //         "PHYSICALPOLICY" => $aNominee['physicalpolicy'] ?? "0",
            //         "POI_DocumentFile" => $identityPhotoB64['based64'],
            //         "POA_DocumentFile" => $addressPhotoB64['based64'],
            //         "Insured_photo" => $insurePhotoB64['based64'],
            //         "POI_DocumentExt" => $identityPhotoB64['extension'],
            //         "POA_DocumentExt" => $addressPhotoB64['extension'],
            //         "Insured_photoExt" => $insurePhotoB64['extension'],
            //         "PANorForm60" => "PAN",
            //         "PanNo" => "AVSPV4566D",
            //         "Pan_Form60_Document" => $identityPhotoB64['based64'],
            //         "Pan_Form60_Document_Ext" => $identityPhotoB64['extension'],
            //         "Pan_Form60_Document_Name" => "1"
            //     ]
            // ]);


            $today = Carbon::today();
            $StartDate = strtoupper($today->format('d-M-Y'));
            $EndDate = strtoupper($today->addYear()->subDay()->format('d-M-Y'));
            $password = self::$password;
            $Id = self::$Id;
            $vehicletypecode = self::$vehicletypecode;
            $deptcode = self::$deptcode;
            $cacheTransicationId = RedisGet('transactionid:' . $userId);
            $TransactionId = $cacheTransicationId;

            $post = [
                "userid" => $Id,
                "password" => $password,
                "transactionid" => $TransactionId,
                "rcptlist" => [],
                "custdetails" => [
                    "parttempid" => "",
                    "firstname" => "ABCDEFG",
                    "middlename" => "PANKAJ",
                    "surname" => "JAIN",
                    "addline1" => "PLOT NO H5 ADC FLAT NO 9",
                    "addline2" => "UDAY GARDEN RESIDENCY",
                    "addline3" => "Pune",
                    "addline5" => "MAHARASHTRA",
                    "pincode" => "411001",
                    "email" => "pankaj@bajajallianz.co.in",
                    "telephone1" => "9637384353",
                    "telephone2" => "9896569658",
                    "mobile" => "9637384353",
                    "delivaryoption" => "",
                    "poladdline1" => "PLOT NO H5 ADC FLAT NO 9",
                    "poladdline2" => "UDAY GARDEN RESIDENCY",
                    "poladdline3" => "Pune",
                    "poladdline5" => "MAHARASHTRA",
                    "polpincode" => "411001",
                    "password" => "newpas12",
                    "cptype" => "P",
                    "profession" => "",
                    "dateofbirth" => "12-SEP-1980",
                    "availabletime" => "",
                    "institutionname" => null,
                    "existingyn" => "N",
                    "loggedin" => "",
                    "mobilealerts" => "",
                    "emailalerts" => "",
                    "title" => "Mrs",
                    "partid" => "",
                    "status1" => "",
                    "status2" => "",
                    "status3" => ""
                ],
                "weomotpolicyin" => [
                    "contractid" => "0",
                    "poltype" => "1",
                    "product4digitcode" => "1826",
                    "deptcode" => $deptcode,
                    "branchcode" => "1607",
                    "termstartdate" => $StartDate,
                    "termenddate" => $EndDate,
                    "tpfintype" => "1",
                    "hypo" => "State Bank of India",
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
                    "zone" => "A",
                    "engineno" => "RJ12FB7889",
                    "chassisno" => "RJ12FB56783478889",
                    "registrationno" => "NEW",
                    "registrationdate" => "09-Nov-2025",
                    "registrationlocation" => "Pune",
                    "regilocother" => "Pune",
                    "carryingcapacity" => "2",
                    "cubiccapacity" => "110",
                    "yearmanf" => "2025",
                    "color" => "RED",
                    "vehicleidv" => "49090",
                    "ncb" => "0",
                    "addloading" => "0",
                    "addloadingon" => "0",
                    "spdiscrate" => "0",
                    "elecacctotal" => "0",
                    "nonelecacctotal" => "0",
                    "prvpolicyref" => "",
                    "prvexpirydate" => "",
                    "prvinscompany" => "0",
                    "prvncb" => "0",
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
                    "extcol10" => "DRIVE_ASSURE_SILVER",
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
                    "extcol25" => "1",
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
                "paymentmode" => "AGENT_FLOAT"
            ];

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

}