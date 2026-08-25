<?php
namespace App\Services\Api;

use App\Http\Controllers\Api\front\motor\Vendor\shriram\Bike\ShriramBikeController;
use App\Http\Controllers\Api\front\motor\Vendor\shriram\Car\ShriramCarController;
use App\Models\Shriram\{Shriram_Pincode, Shriram_planCheckout, Shriram_RTO_Master, Shriram_Prev_insurence, Shriram_Vehicle_Master};
use App\Models\{Master_Vehicle_Data as DataModel, MasterAPI, User, MotorJourney, MasterVendor, VendorMotor, MasterMotor, UserMotorDescription, Vehicle_Info, ResponseLog};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Cache};

class ShriramService
{
    private static $Username;
    private static $Password;

    public static function initlize()
    {
        self::$Username = getconstant('MOTOR.SHRIRAM.CREDENTIAL.USERNAME');
        self::$Password = getconstant('MOTOR.SHRIRAM.CREDENTIAL.PASSWORD');
    }

    public static function genRandomNumber()
    {
        $rand = rand(11111, 99999);
        return $rand;
    }

    public static function generateBikeQuote(Request $request, $today, $nextyear = '', $nPlanType = null, $idv = null)
    {
        try {
            $userId = $request->userid;
            $user = User::find($userId);
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
            $NilDepreciationCoverYN = null;
            $RSACover = null;
            $DailyExpRemYN = null;
            $KeyReplacementYN = null;
            $LossOfPersonBelongYN = null;
            $Eng_Protector = null;
            $Consumables = null;
            $InvReturnYN = null;
            $LLtoPaidDriverYN = null;
            $NoEmpCoverLL = null;
            $SHRIMOTORPROTECTION_YN = null;
            $LimitedTPPDYN = null;
            $VoluntaryExcess = null;
            $AntiTheftYN = null;
            $PAforUnnamedPassenger = null;
            $Geographical = null;
            $AuthUser = $user->toArray();
            $aData = DataModel::where('userid', $userId)->first();
            $aBikedata = json_decode($aData->knowbike_reg_details, true) ?? [];
            $aNewBikedata = json_decode($aData->newbike_reg_details, true) ?? [];
            $PAcover = null;
            $pAcoverReason = null;
            $sRegNumber = '';
            $sRegNo2 = '';
            $sRegNo3 = '';
            $sRegNo1 = '';
            $sRegNo4 = '';
            $VehicleType = null;
            $cachemotortype = 'cache_motortype_' . $userId;
            $cachebikePolicyexp = 'cache_bikepolicyexp_' . $userId;
            $PAforUnnamedamount = json_decode($aData->bikeaddonvalue, true);
            $PAforUnnamedamount = !empty($PAforUnnamedamount) ? $PAforUnnamedamount : '0';
            $dRegDate = $aData->knowbike_reg_details ? json_decode($aData->knowbike_reg_details, true)['bikeregdate'] : date('d-m-Y');
            $regDate = \DateTime::createFromFormat('d-m-Y', $dRegDate);
            $addonAgeLimit = [
                '101' => 4,
                '103' => 4,
                '104' => 4,
                '107' => 12,
                '109' => 12,
                '106' => 1
            ];
            $aBikeAddon = is_string($aData->bikeaddon)
                ? json_decode($aData->bikeaddon, true)
                : (array) $aData->bikeaddon;

            $aAddons = !empty($aBikeAddon['tpselectedaddon'])
                ? $aBikeAddon['tpselectedaddon']
                : (
                    !empty($aBikeAddon['selectedaddon'])
                        ? $aBikeAddon['selectedaddon']
                        : (
                            !empty($aBikeAddon['odselectedaddon'])
                                ? $aBikeAddon['odselectedaddon']
                                : []
                        )
                );
            $validAddons = [];

            foreach ($aAddons as $addonId) {
                $addonId = (string) $addonId;
                if (isset($addonAgeLimit[$addonId])) {
                    $maxyears = $addonAgeLimit[$addonId];
                    if (ValidateAddonAge($regDate, $maxyears)) {
                        $validAddons[] = $addonId;
                    }
                } else {
                    $validAddons[] = $addonId;
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
            if (GetCache($cachemotortype) == 'newbike') {
                if ($aNewBikedata['under'] == 'company') {
                    $PAcover = '0';
                    $pAcoverReason = 'PA_TYPE2';
                } else {
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
                $oModel = Shriram_Vehicle_Master::where('id', $aNewBikedata['model'])->first();
                $sRegNumber = substr(explode('(', $aData->rtocode)[1], 0, -1);
                $sRegNo1 = substr(str_replace('-', '', $sRegNumber), 0, 2);  // k
                $sRegNo2 = substr(str_replace('-', '', $sRegNumber), 2, 2);  // k
            } else {
                if ($aBikedata['under'] == 'company') {
                    $PAcover = '0';
                    $pAcoverReason = 'PA_TYPE2';
                } else {
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
                $oModel = Shriram_Vehicle_Master::where('id', $aBikedata['model'])->first();
                $sRegNumber = $aData->bikenumber;
                $sRegNo1 = substr($sRegNumber, 0, 2);  // k
                $sRegNo2 = substr($sRegNumber, 2, 2);  // k
                $sRegNo3 = substr($sRegNumber, 4, 2);  // k
                $sRegNo4 = substr($sRegNumber, 6, 4);  // k
            }
            if (GetCache($cachemotortype) == 'knowbike') {
                if ($aBikedata['prepolitype'] == 'odonly') {
                    $sPrePolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.OWNDAMAGE');
                    $sPrevexptoDate = $aBikedata['odtodate'];  // k
                    $sPrevexpfDate = $aBikedata['odfromdate'];  // k
                    $sPretpfDate = $aBikedata['odtpfromdate'];
                    $sPretptoDate = $aBikedata['odtptodate'];
                    $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
                    $PolicyFromDate = $date->addDay()->format('d-m-Y');
                    $PolicyToDate = $date->addYear()->subDay()->format('d-m-Y');
                } else if ($aBikedata['prepolitype'] == 'bundled') {
                    $sPrePolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.BUNDLED');
                    $sPrevexptoDate = $aBikedata['bdtodate'];  // k
                    $sPrevexpfDate = $aBikedata['bdfromdate'];  // k
                    $sPretpfDate = $aBikedata['bdtpfromdate'];
                    $sPretptoDate = $aBikedata['bdtptodate'];
                    $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
                    $PolicyFromDate = $date->addDay()->format('d-m-Y');
                    $PolicyToDate = $date->addYear()->subDay()->format('d-m-Y');
                } elseif ($aBikedata['prepolitype'] == 'comprehensive') {
                    $sPrePolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.PACKAGE');
                    $sPrevexptoDate = $aBikedata['comptodate'];  // k
                    $sPrevexpfDate = $aBikedata['compfromdate'];
                    $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
                    $PolicyFromDate = $date->addDay()->format('d-m-Y');
                    $PolicyToDate = $date->addYear()->subDay()->format('d-m-Y');
                } elseif ($aBikedata['prepolitype'] == 'tponly') {
                    $sPrePolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.LIABILITY');
                    $sPrevexptoDate = $aBikedata['tptodate'];  // k
                    $sPrevexpfDate = $aBikedata['tpfromdate'];  // k
                    $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
                    $PolicyFromDate = $date->addDay()->format('d-m-Y');
                    $PolicyToDate = $date->addYear()->subDay()->format('d-m-Y');
                }
            }

            $oJourneyData = MotorJourney::where('userid', $userId)->where('is_bike', '1')->first();
            $permanentAddress = isset($oJourneyData->permanent_address) && json_decode($oJourneyData->permanent_address, true) ? json_decode($oJourneyData->permanent_address, true) : [];
            $pincode = $user->pincode;
            $state = Shriram_Pincode::where('PC_CODE', $pincode)->first();
            if (!$state) {
                return [
                    'status' => '0',
                    'message' => 'Pincode not available for this service.'
                ];
            }
            $sState = $state->STATE;
            if (GetCache($cachemotortype) == 'knowbike') {
                $firstregdate = array_key_exists('bikeregdate', $aBikedata) ? $aBikedata['bikeregdate'] : '';
                $claim = array_key_exists('policytoggle', $aBikedata) ? '1' : '0';
                $claimper = array_key_exists('bonus-button', $aBikedata) ? $aBikedata['bonus-button'] : '0';
                $transferOfowner = array_key_exists('ownershiptoggle', $aBikedata) ? $aBikedata['ownershiptoggle'] : '0';
                $oModel = Shriram_Vehicle_Master::where('id', $aBikedata['model'])->first();
            }
            if (GetCache($cachemotortype) == 'newbike') {
                $aNewBikedata = json_decode($aData->newbike_reg_details, true);
                $firstregdate = $today->format('d-m-Y');
                $oModel = Shriram_Vehicle_Master::where('id', $aNewBikedata['model'])->first();
            }
            $sVehicleCode = ' ';
            if ($oModel) {
                $sVehicleCode = $oModel->VEHICLE_CODE;
            }
            $sProdCode = getconstant('MOTOR.SHRIRAM.PRODUCTTYPE.TWOWHEELER');
            $sPolicyType = '';
            $sProposalType = '';
            if (GetCache($cachemotortype) == 'newbike') {
                $VehicleType = 'W';
                $sProposalType = getconstant('MOTOR.SHRIRAM.PROPOSALTYPE.FRESHPROPOSAL');
                if ($nPlanType == '2') {
                    $sPolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.BUNDLED');
                    $PolicyFromDate = $today->format('d-m-Y');
                    $PolicyToDate = $today->addYears(3)->subDay()->format('d-m-Y');
                    $NilDepreciationCoverYN = in_array('101', $aAddons) ? 'Y' : 'N';
                    $RSACover = in_array('102', $aAddons) ? 'Y' : 'N';
                    $DailyExpRemYN = in_array('109', $aAddons) ? 'Y' : 'N';
                    $KeyReplacementYN = in_array('111', $aAddons) ? 'Y' : 'N';
                    $LossOfPersonBelongYN = in_array('107', $aAddons) ? 'Y' : 'N';
                    $Eng_Protector = in_array('104', $aAddons) ? 'Y' : 'N';
                    $Consumables = in_array('103', $aAddons) ? 'Y' : 'N';
                    $InvReturnYN = in_array('106', $aAddons) ? 'Y' : 'N';
                    $AntiTheftYN = in_array('119', $aAddons) ? '1' : '0';
                    $LLtoPaidDriverYN = in_array('116', $aAddons) ? '1' : '0';
                    $NoEmpCoverLL = in_array('122', $aAddons) ? '1' : '0';
                    $SHRIMOTORPROTECTION_YN = in_array('113', $aAddons) ? 'Y' : 'N';
                    $LimitedTPPDYN = in_array('121', $aAddons) ? '1' : '0';
                    $VoluntaryExcess = in_array('120', $aAddons) ? '1' : '0';
                    $PAPaidDriverConductorCleaner = 1;
                    $Geographical = in_array('117', $aAddons) ? 1 : '0';
                    $Geographical = in_array('118', $aAddons) ? 1 : '0';
                    $PAforUnnamedPassenger = in_array('115', $aAddons) ? '1' : '0';
                }
            }
            if (GetCache($cachemotortype) == 'knowbike') {
                $VehicleType = 'U';
                $sProposalType = getconstant('MOTOR.SHRIRAM.PROPOSALTYPE.MARKETRENEWAL');
                if ($nPlanType == '1') {
                    $sPolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.OWNDAMAGE');
                    $NilDepreciationCoverYN = in_array('101', $aAddons) ? 'Y' : 'N';
                    $RSACover = in_array('102', $aAddons) ? 'Y' : 'N';
                    $DailyExpRemYN = in_array('109', $aAddons) ? 'Y' : 'N';
                    $KeyReplacementYN = in_array('111', $aAddons) ? 'Y' : 'N';
                    $LossOfPersonBelongYN = in_array('107', $aAddons) ? 'Y' : 'N';
                    $Eng_Protector = in_array('104', $aAddons) ? 'Y' : 'N';
                    $Consumables = in_array('103', $aAddons) ? 'Y' : 'N';
                    $InvReturnYN = in_array('106', $aAddons) ? 'Y' : 'N';
                    $AntiTheftYN = in_array('119', $aAddons) ? '1' : '0';
                    $LLtoPaidDriverYN = in_array('116', $aAddons) ? '1' : '0';
                    $NoEmpCoverLL = in_array('122', $aAddons) ? '1' : '0';
                    $SHRIMOTORPROTECTION_YN = in_array('113', $aAddons) ? 'Y' : 'N';
                    $LimitedTPPDYN = in_array('121', $aAddons) ? '1' : '0';
                    $VoluntaryExcess = in_array('120', $aAddons) ? '1' : '0';
                    $PAPaidDriverConductorCleaner = 1;
                    $Geographical = in_array('117', $aAddons) ? 1 : '0';
                    $PAforUnnamedPassenger = in_array('115', $aAddons) ? '1' : '0';
                }
                if ($nPlanType == '2') {
                    $sPolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.PACKAGE');
                    $RSACover = in_array('102', $aAddons) ? 'Y' : 'N';
                    $DailyExpRemYN = in_array('109', $aAddons) ? 'Y' : 'N';
                    $KeyReplacementYN = in_array('111', $aAddons) ? 'Y' : 'N';
                    $LossOfPersonBelongYN = in_array('107', $aAddons) ? 'Y' : 'N';
                    $Eng_Protector = in_array('104', $aAddons) ? 'Y' : 'N';
                    $Consumables = in_array('103', $aAddons) ? 'Y' : 'N';
                    $InvReturnYN = in_array('106', $aAddons) ? 'Y' : 'N';
                    $AntiTheftYN = in_array('119', $aAddons) ? '1' : '0';
                    $LLtoPaidDriverYN = in_array('116', $aAddons) ? '1' : '0';
                    $NoEmpCoverLL = in_array('122', $aAddons) ? '1' : '0';
                    $SHRIMOTORPROTECTION_YN = in_array('113', $aAddons) ? 'Y' : 'N';
                    $LimitedTPPDYN = in_array('121', $aAddons) ? '1' : '0';
                    $VoluntaryExcess = in_array('120', $aAddons) ? '1' : '0';
                    $PAPaidDriverConductorCleaner = 1;
                    $Geographical = in_array('117', $aAddons) ? 1 : '0';
                    $Geographical = in_array('118', $aAddons) ? 1 : '0';
                    $PAforUnnamedPassenger = in_array('115', $aAddons) ? '1' : '0';
                }
                if ($nPlanType == '3' && (GetCache($cachebikePolicyexp) == 'Expired')) {
                    $sPolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.LIABILITY');
                    $PolicyFromDate = $today->format('d-m-Y');
                    $PolicyToDate = $today->addYear()->subDay()->format('d-m-Y');
                    $LLtoPaidDriverYN = in_array('116', $aAddons) ? '1' : '0';
                    $NoEmpCoverLL = in_array('122', $aAddons) ? '1' : '0';
                    $LimitedTPPDYN = in_array('121', $aAddons) ? '1' : '0';
                    $Geographical = in_array('118', $aAddons) ? 1 : '0';
                    $PAforUnnamedPassenger = in_array('115', $aAddons) ? '1' : '0';
                }
                if ($nPlanType == '3' && (GetCache($cachebikePolicyexp) == 'Not Expired')) {
                    $sPolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.LIABILITY');
                    $LLtoPaidDriverYN = in_array('116', $aAddons) ? '1' : '0';
                    $NoEmpCoverLL = in_array('122', $aAddons) ? '1' : '0';
                    $LimitedTPPDYN = in_array('121', $aAddons) ? '1' : '0';
                    $PAforUnnamedPassenger = in_array('115', $aAddons) ? '1' : '0';
                    $Geographical = in_array('118', $aAddons) ? 1 : '0';
                }
            }

            $sRtocity = getBikeRtocityApi($request, $sRegNumber);
            if ($sRtocity) {
                $sRtocity = $sRtocity->RTOCITY ?? $sRtocity->RTONAME;
            } else {
                $sRtocity = '';
            }
            $cachebikeidv = 'cache_' . $userId . '_bikeidv';
            $idv = GetCache($cachebikeidv);
            // $url = MasterAPI::where('apicode', '115')->first()->apistring;
            $url = 'https://nsecureapi.shriramgi.com/NOVADIGITAL/SVS_Services/PolicyGeneration.svc/RestService/GetQuote';
            $curl = curl_init();
            $jPostField = json_encode([
                'objPolicyEntryETT' => [
                    'ReferenceNo' => '',
                    'ProdCode' => $sProdCode,
                    'PolicyFromDt' => $PolicyFromDate,
                    'PolicyToDt' => $PolicyToDate,
                    'PolicyIssueDt' => $PolicyFromDate,
                    'InsuredPrefix' => '1',
                    'InsuredName' => $AuthUser['name'],
                    'Gender' => $AuthUser['gender'][0] ?? '',
                    'Address1' => '',
                    'Address2' => '',
                    'Address3' => '',
                    'State' => $sState,
                    'City' => $sRtocity,
                    'PinCode' => $AuthUser['pincode'],
                    'PanNo' => null,
                    'GSTNo' => null,
                    'TelephoneNo' => '',
                    'ProposalType' => $sProposalType,  // "FRESH",
                    'PolicyType' => $sPolicyType,  // "MOT-PLT-010",
                    'DateOfBirth' => '',
                    'FaxNo' => '',
                    'POSAgentName' => '',
                    'POSAgentPanNo' => '',
                    'CoverNoteNo' => '',
                    'CoverNoteDt' => '',
                    'VehicleCode' => $sVehicleCode ?? 'M_21989',
                    'FirstRegDt' => $firstregdate ?? '',
                    'VehicleManufactureYear' => '2016',
                    'VehicleType' => $VehicleType ?? '',
                    'EngineNo' => '',
                    'ChassisNo' => '',
                    'RegNo1' => $sRegNo1,
                    'RegNo2' => $sRegNo2,
                    'RegNo3' => $sRegNo3,
                    'RegNo4' => $sRegNo4,
                    'RTOCode' => $sRegNo1 . '-' . $sRegNo2,
                    'IDV_of_Vehicle' => $idv ?? '0',
                    'Colour' => '',
                    'VoluntaryExcess' => $VoluntaryExcess ?? '0',
                    'NoEmpCoverLL' => $NoEmpCoverLL ?? '0',
                    'NoOfCleaner' => '',
                    'NoOfDriver' => '0',
                    'NoOfConductor' => '',
                    'VehicleMadeinindiaYN' => '',
                    'VehiclePurposeYN' => '',
                    'NFPP_Employees' => '',
                    'NFPP_OthThanEmp' => '',
                    'LimitOwnPremiseYN' => '',
                    'PAYEAR' => '1',
                    'Bangladesh' => ($Geographical == 1) ? '1' : '0',
                    'Bhutan' => ($Geographical == 1) ? '1' : '0',
                    'SriLanka' => ($Geographical == 1) ? '1' : '0',
                    'Nepal' => ($Geographical == 1) ? '1' : '0',
                    'Pakistan' => ($Geographical == 1) ? '1' : '0',
                    'Maldives' => ($Geographical == 1) ? '1' : '0',
                    'CNGKitYN' => array_key_exists('cng', $aResult) ? 'Y' : 'N',
                    'CNGKitSI' => array_key_exists('cng', $aResult) ? $aResult['cng'] : '',
                    'InBuiltCNGKit' => 0,
                    'LimitedTPPDYN' => $LimitedTPPDYN ?? '0',
                    'DeTariff' => 0,
                    'IMT23YN' => '',
                    'BreakIn' => 'No',
                    'PreInspectionReportYN' => '0',
                    'PreInspection' => '',
                    'FitnessCertificateno' => '',
                    'FitnessValidupto' => '',
                    'VehPermit' => '',
                    'PermitNo' => '',
                    'PAforUnnamedPassengerYN' => $PAforUnnamedPassenger ?? '0',
                    'PAforUnnamedPassengerSI' => ($PAforUnnamedPassenger == 1) ? $PAforUnnamedamount : '',
                    'ElectricalaccessYN' => array_key_exists('electrical', $aResult) ? 'Y' : 'N',
                    'ElectricalaccessSI' => array_key_exists('electrical', $aResult) ? $aResult['electrical'] : '',
                    'ElectricalaccessRemarks' => '',
                    'NonElectricalaccessYN' => array_key_exists('non-electrical', $aResult) ? 'Y' : 'N',
                    'NonElectricalaccessSI' => array_key_exists('non-electrical', $aResult) ? $aResult['non-electrical'] : '',
                    'NonElectricalaccessRemarks' => '',
                    'PAPaidDriverConductorCleanerYN' => $PAPaidDriverConductorCleaner ?? '1',
                    'PAPaidDriverConductorCleanerSI' => '0',
                    'PAPaidDriverCount' => '1',
                    'PAPaidConductorCount' => '1',
                    'PAPaidCleanerCount' => '1',
                    'NomineeNameforPAOwnerDriver' => '',
                    'NomineeAgeforPAOwnerDriver' => '',
                    'NomineeRelationforPAOwnerDriver' => '',
                    'AppointeeNameforPAOwnerDriver' => '',
                    'AppointeeRelationforPAOwnerDriver' => '',
                    'LLtoPaidDriverYN' => $LLtoPaidDriverYN ?? '0',
                    'AntiTheftYN' => $AntiTheftYN ?? '0',
                    'PreviousPolicyNo' => '',
                    'PreviousInsurer' => '',
                    'PreviousPolicyFromDt' => $sPrevexpfDate,
                    'PreviousPolicyToDt' => $sPrevexptoDate,
                    'PreviousPolicySI' => '',
                    'TRANSFEROFOWNER' => $transferOfowner ?? '',
                    'PreviousPolicyClaimYN' => $claim,
                    'PreviousPolicyUWYear' => '',
                    'PreviousPolicyNCBPerc' => $claimper,
                    'PreviousPolicyType' => $sPrePolicyType ?? '',
                    'tpPolFmdt' => $sPretpfDate ?? '',
                    'tpPolTodt' => $sPretptoDate ?? '',
                    'NilDepreciationCoverYN' => $NilDepreciationCoverYN ?? '0',
                    'PreviousNilDepreciation' => '0',
                    'InvReturnYN' => $InvReturnYN ?? '',
                    'RSACover' => $RSACover ?? '',
                    'LossOfPersonBelongYN' => $LossOfPersonBelongYN ?? '',
                    'DailyExpRemYN' => $DailyExpRemYN ?? '',
                    'KeyReplacementYN' => $KeyReplacementYN ?? '',
                    'SHRIMOTORPROTECTION_YN' => $SHRIMOTORPROTECTION_YN ?? '',
                    'HypothecationType' => '',
                    'HypothecationBankName' => '',
                    'HypothecationAddress1' => '',
                    'HypothecationAddress2' => '',
                    'HypothecationAddress3' => '',
                    'HypothecationAgreementNo' => '',
                    'HypothecationCountry' => '',
                    'HypothecationState' => '',
                    'HypothecationCity' => '',
                    'HypothecationPinCode' => '',
                    'SpecifiedPersonField' => '',
                    'PAOwnerDriverExclusion' => $PAcover ?? '',
                    'PAOwnerDriverExReason' => $pAcoverReason ?? '',
                    'CPAInsComp' => '',
                    'CPAPolicyFmDt' => '',
                    'CPAPolicyNo' => '',
                    'CPAPolicyToDt' => '',
                    'CPASumInsured' => '',
                    'Consumables' => $Consumables ?? '',
                    'Eng_Protector' => $Eng_Protector ?? ''
                ]
            ]);

            // return [
            //    "asd"=>$jPostField
            // ];
            // dd($jPostField);
            \Log::info(['generateBikeQuote_shriram' => $jPostField]);

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $jPostField,
                CURLOPT_HTTPHEADER => array(
                    'Username: DIGIBIMA',
                    'Password: shriram@1',
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Cookie: ASP.NET_SessionId=pprgtlk4esr1orqh5aptertx'
                ),
            ));
            $response = curl_exec($curl);
            \Log::info(['generateBikeQuote_shriram' => $response]);
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
            $user = User::find($userId);
            $AuthUser = $user->toArray();
            $today = now();
            $AuthUser = User::find($userId)->toArray();
            $aData = DataModel::where('userid', $userId)->first();
            $oJourneyData = MotorJourney::where('userid', $userId)->where('is_bike', '1')->first();
            $prevPolicydata = json_decode($oJourneyData->pre_policy_details, true);
            $HypothData = json_decode($oJourneyData->bank_details, true);
            $aBikedata = json_decode($aData->knowbike_reg_details, true);
            $aNewBikedata = json_decode($aData->newbike_reg_details, true);
            $Geographical = null;
            $PAforUnnamedPassenger = null;
            $sPrevexptoDate = null;
            $sPrevexpfDate = null;
            $sPretpfDate = null;
            $sPretptoDate = null;
            $claim = null;
            $claimper = null;
            $transferOfowner = null;
            $gstno = null;
            $oModel = null;
            $pAcoverReason = '';
            $PAcover = '';
            $nTpolicyNo = null;
            $sTpInsurer = null;
            $prevPolicyNo = null;
            $VehicleType = null;
            $sRegdate = '';
            $cachebikePolicyexp = 'cache_bikepolicyexp_' . $userId;
            $PAforUnnamedamount = json_decode($aData->bikeaddonvalue, true);
            $PAforUnnamedamount = !empty($PAforUnnamedamount) ? $PAforUnnamedamount : '0';
            $aVehicledetails = json_decode($oJourneyData->vehicle_details, true);
            $aResult = [];
            if (!empty($aAccessories)) {
                foreach ($aAccessories as $item) {
                    $aResult[$item['type']] = $item['amount'];
                }
            }
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
                }
            } else {
                $pAcoverReason = '';
            }
            $sRegdate = $aBikedata['bikeregdate'] ?? date('d-m-Y');
            $cachemotortype = 'cache_motortype_' . $userId;
            $cacheunder = 'cache_under_' . $userId;
            if (GetCache($cachemotortype) == 'knowbike') {
                if ($aBikedata['prepolitype'] == 'odonly') {
                    $sPrePolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.OWNDAMAGE');
                    $sPrevexptoDate = $aBikedata['odtodate'];  // k
                    $sPrevexpfDate = $aBikedata['odfromdate'];  // k
                    $sPretpfDate = $aBikedata['odtpfromdate'];
                    $sPretptoDate = $aBikedata['odtptodate'];
                    $nTpolicyNo = array_key_exists('tppolicynumber', $prevPolicydata) ? $prevPolicydata['tppolicynumber'] : '';
                    $sTpInsurer = array_key_exists('tpprevInsurance', $prevPolicydata) ? $prevPolicydata['tpprevInsurance'] : '';
                    $tpInsurCmpny = Shriram_Prev_insurence::where('id', $sTpInsurer)->first()->insurance;
                    $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
                    $PolicyFromDate = $date->addDay()->format('d-m-Y');
                    $PolicyToDate = $date->addYear()->subDay()->format('d-m-Y');
                } else if ($aBikedata['prepolitype'] == 'bundled') {
                    $sPrePolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.BUNDLED');
                    $sPrevexptoDate = $aBikedata['bdtodate'];  // k
                    $sPrevexpfDate = $aBikedata['bdfromdate'];  // k
                    $sPretpfDate = $aBikedata['bdtpfromdate'];
                    $sPretptoDate = $aBikedata['bdtptodate'];
                    $nTpolicyNo = array_key_exists('tppolicynumber', $prevPolicydata) ? $prevPolicydata['tppolicynumber'] : '';
                    $sTpInsurer = array_key_exists('tpprevInsurance', $prevPolicydata) ? $prevPolicydata['tpprevInsurance'] : '';
                    $tpInsurCmpny = Shriram_Prev_insurence::where('id', $sTpInsurer)->first()->insurance;
                    $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
                    $PolicyFromDate = $date->addDay()->format('d-m-Y');
                    $PolicyToDate = $date->addYear()->subDay()->format('d-m-Y');
                } elseif ($aBikedata['prepolitype'] == 'comprehensive') {
                    $sPrePolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.PACKAGE');
                    $sPrevexptoDate = $aBikedata['comptodate'];  // k
                    $sPrevexpfDate = $aBikedata['compfromdate'];
                    $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
                    $PolicyFromDate = $date->addDay()->format('d-m-Y');
                    $PolicyToDate = $date->addYear()->subDay()->format('d-m-Y');
                } elseif ($aBikedata['prepolitype'] == 'tponly') {
                    $sPrePolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.LIABILITY');
                    $sPrevexptoDate = $aBikedata['tptodate'];  // k
                    $sPrevexpfDate = $aBikedata['tpfromdate'];  // k
                    $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
                    $PolicyFromDate = $date->addDay()->format('d-m-Y');
                    $PolicyToDate = $date->addYear()->subDay()->format('d-m-Y');
                }
            }
            $sRegNumber = '';
            $sRegNo2 = '';
            $sRegNo3 = '';
            $sRegNo1 = '';
            $sRegNo4 = '';
            if (GetCache($cachemotortype) == 'newbike') {
                $sRegNumber = substr(explode('(', $aData->rtocode)[1], 0, -1);
                $sRegNo1 = substr(str_replace('-', '', $sRegNumber), 0, 2);  // k
                $sRegNo2 = substr(str_replace('-', '', $sRegNumber), 2, 2);  // k
                $oModel = Shriram_Vehicle_Master::where('id', $aNewBikedata['model'])->first();
            } else {
                $sRegNumber = $aData->bikenumber;
                $sRegNo1 = substr($sRegNumber, 0, 2);  // k
                $sRegNo2 = substr($sRegNumber, 2, 2);  // k
                $sRegNo3 = substr($sRegNumber, 4, 2);  // k
                $sRegNo4 = substr($sRegNumber, 6, 4);  // k
                $oModel = Shriram_Vehicle_Master::where('id', $aBikedata['model'])->first();
            }
            $sVehicleCode = '';
            if ($oModel) {
                $sVehicleCode = $oModel->VEHICLE_CODE;
            }
            $oObj = new ShriramBikeController();
            $aDocument = UserMotorDescription::where('userid', $userId)->first('document');
            $filePath = json_decode($aDocument->document, true);
            $insurePhotoB64 = self::FileIntoBase64($filePath['insurephoto']);
            $identityPhotoB64 = self::FileIntoBase64($filePath['identity']['identityfront']);
            $addressPhotoB64 = self::FileIntoBase64($filePath['address']['addressfront']);
            self::initlize();
            // $url = MasterAPI::where('apicode', '116')->first()->apistring;
            $url = 'https://nsecureapi.shriramgi.com/NOVADIGITAL/SVS_Services/PolicyGeneration.svc/RestService/GenerateProposal';
            $aNominee = $oJourneyData->nominee_details ? jdec($oJourneyData->nominee_details) : [];
            $permanentAddress = json_decode($oJourneyData->permanent_address, true) ?? [];
            $pincode = $permanentAddress['pincode'];
            $state = Shriram_Pincode::where('PC_CODE', $pincode)->first();
            $sState = $state->STATE;
            $nPlanType = $aData->bike_plan_type;
            $dRegDate = $aData->knowbike_reg_details ? json_decode($aData->knowbike_reg_details, true)['bikeregdate'] : date('d-m-Y');
            $regDate = \DateTime::createFromFormat('d-m-Y', $dRegDate);
            $addonAgeLimit = [
                '101' => 4,
                '103' => 4,
                '104' => 4,
                '107' => 12,
                '109' => 12,
                '106' => 1
            ];
            $aBikeAddon = is_string($aData->bikeaddon)
                ? json_decode($aData->bikeaddon, true)
                : (array) $aData->bikeaddon;

            $aAddons = !empty($aBikeAddon['tpselectedaddon'])
                ? $aBikeAddon['tpselectedaddon']
                : (
                    !empty($aBikeAddon['selectedaddon'])
                        ? $aBikeAddon['selectedaddon']
                        : (
                            !empty($aBikeAddon['odselectedaddon'])
                                ? $aBikeAddon['odselectedaddon']
                                : []
                        )
                );
            $validAddons = [];
            foreach ($aAddons as $addonId) {
                $addonId = (string) $addonId;
                if (isset($addonAgeLimit[$addonId])) {
                    $maxyears = $addonAgeLimit[$addonId];
                    if (ValidateAddonAge($regDate, $maxyears)) {
                        $validAddons[] = $addonId;
                    }
                } else {
                    $validAddons[] = $addonId;
                }
            }
            $aAddons = $validAddons;
            $cachebikeidv = 'cache_' . $userId . '_bikeidv';
            $nIdv = GetCache($cachebikeidv);
            $randomValue = self::genRandomNumber();
            $EngineNo = $aVehicledetails['Enginenumber'];  // "GDFG59D4GD6546D" . $randomValue;
            $ChassisNo = $aVehicledetails['Chassisnumber'];  // "GDF45GDFGD4G56D" . $randomValue;
            $sProdCode = getconstant('MOTOR.SHRIRAM.PRODUCTTYPE.TWOWHEELER');
            $sPolicyType = '';
            $sProposalType = '';
            if (GetCache($cachemotortype) == 'newbike') {
                $VehicleType = 'W';
                $sProposalType = getconstant('MOTOR.SHRIRAM.PROPOSALTYPE.FRESHPROPOSAL');
                $companydetails = json_decode($oJourneyData->company_details, true);
                if ($aNewBikedata['under'] == 'company') {
                    $PAcover = 0;
                    $pAcoverReason = 'PA_TYPE2';
                } else {
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
                if ($nPlanType == '2') {
                    $sPolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.BUNDLED');
                    $sRegdate = $today->format('d-m-Y');
                    $PolicyFromDate = $today->format('d-m-Y');
                    $PolicyToDate = $today->addYears(5)->subDay()->format('d-m-Y');
                    $Geographical = in_array('118', $aAddons) ? 1 : '0';
                    $Geographical = in_array('117', $aAddons) ? 1 : '0';
                    $PAforUnnamedPassenger = in_array('115', $aAddons) ? '1' : '0';
                }
            }
            if (GetCache($cachemotortype) == 'knowbike') {
                $VehicleType = 'U';
                $prevPolicyNo = $prevPolicydata['policynumber'];
                $prevInsurCmpny = Shriram_Prev_insurence::where('id', $prevPolicydata['prevInsuranceId'])->first()->insurance;
                if ($aBikedata['under'] == 'company') {
                    $PAcover = 0;
                    $pAcoverReason = 'PA_TYPE2';
                    $companydetails = json_decode($oJourneyData->company_details, true);
                    $gstno = $companydetails['gstnumber'];
                } else {
                    $PAcover = $aData->pacover;
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

                $claim = array_key_exists('policytoggle', $aBikedata) ? '1' : '0';
                $claimper = array_key_exists('bonus-button', $aBikedata) ? $aBikedata['bonus-button'] : '0';
                $transferOfowner = array_key_exists('ownershiptoggle', $aBikedata) ? $aBikedata['ownershiptoggle'] : '0';
                $sProposalType = getconstant('MOTOR.SHRIRAM.PROPOSALTYPE.MARKETRENEWAL');

                if ($nPlanType == '1') {
                    $sPolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.OWNDAMAGE');
                    $Geographical = in_array('117', $aAddons) ? 1 : '0';
                }
                if ($nPlanType == '2') {
                    $sPolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.PACKAGE');
                    $Geographical = in_array('117', $aAddons) ? 1 : '0';
                    $Geographical = in_array('118', $aAddons) ? 1 : '0';
                    $PAforUnnamedPassenger = in_array('115', $aAddons) ? '1' : '0';
                }
                if ($nPlanType == '3' && (GetCache($cachebikePolicyexp) == 'Not Expired')) {
                    $sPolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.LIABILITY');
                    $Geographical = in_array('118', $aAddons) ? 1 : '0';
                    $PAforUnnamedPassenger = in_array('115', $aAddons) ? '1' : '0';
                }
                if ($nPlanType == '3' && (GetCache($cachebikePolicyexp) == 'Expired')) {
                    $sPolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.LIABILITY');
                    $PolicyFromDate = $today->format('d-m-Y');
                    $PolicyToDate = $today->addYear()->subDay()->format('d-m-Y');
                    $Geographical = in_array('118', $aAddons) ? 1 : '0';
                    $PAforUnnamedPassenger = in_array('115', $aAddons) ? '1' : '0';
                }
            }

            $sRtocity = getBikeRtocity($sRegNumber);
            if ($sRtocity) {
                $sRtocity = !empty($sRtocity->RTOCITY) ? $sRtocity->RTOCITY : $sRtocity->RTONAME;
            } else {
                $sRtocity = '';
            }
            $user = User::find($userId);
            $dob = !empty($oJourneyData->dob)
                ? $oJourneyData->dob
                : (!empty($user->dob)
                    ? $user->dob
                    : '29-04-2000');

            $cacheproductcode = 'cache_productcode_' . $userId;
            SetCache($cacheproductcode, $sProdCode);
            $cacheproposaltype = 'cache_proposaltype_' . $userId;
            SetCache($cacheproposaltype, $sProposalType);
            $cachepolicytpe = 'cache_policytpe_' . $userId;
            SetCache($cachepolicytpe, $sPolicyType);
            $cacheplantype = 'cache_plantype_' . $userId;
            SetCache($cacheplantype, $nPlanType);

            $curl = curl_init();
            $randomValue = self::genRandomNumber();
            $postdata = json_encode([
                'objPolicyEntryETT' => [
                    'ReferenceNo' => '123456',
                    'ProdCode' => $sProdCode,
                    'PolicyFromDt' => $PolicyFromDate ?? '',
                    'PolicyToDt' => $PolicyToDate ?? '',
                    'PolicyIssueDt' => $PolicyFromDate ?? '',
                    'InsuredPrefix' => '1',
                    'InsuredName' => $AuthUser['name'] ?? $AuthUser['name'],
                    'Gender' => $AuthUser['gender'][0] ?? 'M',
                    'Address1' => $permanentAddress['address1'] ?? '',
                    'Address2' => $permanentAddress['address2'] ?? '',
                    'Address3' => $permanentAddress['landmark'] ?? '',
                    'State' => $sState ?? '',
                    'City' => $permanentAddress['city'] ?? '',
                    'PinCode' => $pincode ?? $permanentAddress['pincode'],
                    'PanNo' => '',
                    'GSTNo' => $gstno,
                    'TelephoneNo' => '',
                    'ProposalType' => $sProposalType ?? '',
                    'PolicyType' => $sPolicyType ?? '',
                    'DateOfBirth' => Carbon::createFromFormat('d-m-Y', $dob)->format('Y-m-d') ?? '12-08-1998',
                    'MobileNo' => $AuthUser['mobile'] ?? '',
                    'FaxNo' => '',
                    'EmailID' => $AuthUser['email'] ?? '',
                    'POSAgentName' => 'NANDITA TIWARI',
                    'POSAgentPanNo' => 'BTYPB4567K',
                    'CoverNoteNo' => '',
                    'CoverNoteDt' => '',
                    'VehicleCode' => $sVehicleCode ?? 'UL8066',
                    'FirstRegDt' => $sRegdate ?? '',
                    'VehicleType' => $VehicleType ?? '',
                    'EngineNo' => $EngineNo ?? '',
                    'ChassisNo' => $ChassisNo ?? '',
                    'RegNo1' => $sRegNo1 ?? '',
                    'RegNo2' => $sRegNo2 ?? '',
                    'RegNo3' => $sRegNo3 ?? '',
                    'RegNo4' => $sRegNo4 ?? '',
                    'RTOCode' => $sRegNo1 ?? '' . '-' . $sRegNo2 ?? '',
                    'IDV_of_Vehicle' => $nIdv ?? '',
                    'Colour' => '',
                    'VoluntaryExcess' => in_array('120', $aAddons) ? '1' : '0',
                    'NoEmpCoverLL' => in_array('122', $aAddons) ? '1' : '0',
                    'NoOfCleaner' => '',
                    'NoOfDriver' => '0',
                    'NoOfConductor' => '',
                    'VehicleMadeinindiaYN' => 'N',
                    'VehiclePurposeYN' => '',
                    'NFPP_Employees' => '',
                    'NFPP_OthThanEmp' => '',
                    'LimitOwnPremiseYN' => 'N',
                    'Bangladesh' => ($Geographical == 1) ? '1' : '0',
                    'Bhutan' => ($Geographical == 1) ? '1' : '0',
                    'SriLanka' => ($Geographical == 1) ? '1' : '0',
                    'Nepal' => ($Geographical == 1) ? '1' : '0',
                    'Pakistan' => ($Geographical == 1) ? '1' : '0',
                    'Maldives' => ($Geographical == 1) ? '1' : '0',
                    'CNGKitYN' => array_key_exists('cng', $aResult) ? 'Y' : 'N',
                    'CNGKitSI' => array_key_exists('cng', $aResult) ? $aResult['cng'] : '',
                    'InBuiltCNGKit' => '0',
                    'LimitedTPPDYN' => in_array('121', $aAddons) ? '1' : '0',
                    'DeTariff' => 0,
                    'IMT23YN' => '',
                    'BreakIn' => 'NO',
                    'PreInspectionReportYN' => '0',
                    'PreInspection' => '',
                    'FitnessCertificateno' => '',
                    'FitnessValidupto' => '',
                    'VehPermit' => '',
                    'PermitNo' => '',
                    'PAforUnnamedPassengerYN' => $PAforUnnamedPassenger ?? '0',
                    'PAforUnnamedPassengerSI' => ($PAforUnnamedPassenger == 1) ? $PAforUnnamedamount : '0',
                    'ElectricalaccessYN' => array_key_exists('electrical', $aResult) ? 'Y' : 'N',
                    'ElectricalaccessSI' => array_key_exists('electrical', $aResult) ? $aResult['electrical'] : '',
                    'ElectricalaccessRemarks' => '',
                    'NonElectricalaccessYN' => array_key_exists('non-electrical', $aResult) ? 'Y' : 'N',
                    'NonElectricalaccessSI' => array_key_exists('non-electrical', $aResult) ? $aResult['non-electrical'] : '',
                    'NonElectricalaccessRemarks' => '',
                    'PAPaidDriverConductorCleanerYN' => 0,
                    'PAPaidDriverConductorCleanerSI' => 0,
                    'PAPaidDriverCount' => '0',
                    'PAPaidConductorCount' => '',
                    'PAPaidCleanerCount' => '',
                    'NomineeNameforPAOwnerDriver' => $aNominee['nomineename'] ?? 'unknown',
                    'NomineeAgeforPAOwnerDriver' => $aNominee['nomineedob'] ? date('Y') - (int) last(explode('-', $aNominee['nomineedob'])) : '28',
                    'NomineeRelationforPAOwnerDriver' => $aNominee['nomineerelation'] ?? 'BROTHER',
                    'AppointeeNameforPAOwnerDriver' => $aNominee['appointeename'] ?? '',
                    'AppointeeRelationforPAOwnerDriver' => $aNominee['appointeerelation'] ?? '',
                    'LLtoPaidDriverYN' => in_array('116', $aAddons) ? '1' : '0',
                    'AntiTheftYN' => in_array('119', $aAddons) ? '1' : '0',
                    'PreviousPolicyNo' => $prevPolicyNo ?? '',
                    'PreviousInsurer' => $prevInsurCmpny ?? '',
                    'PreviousPolicyFromDt' => $sPrevexpfDate ?? '',
                    'PreviousPolicyToDt' => $sPrevexptoDate ?? '',
                    'PreviousPolicySI' => '',
                    'PreviousPolicyClaimYN' => $claim ?? '',
                    'PreviousPolicyUWYear' => '',
                    'PreviousPolicyNCBPerc' => $claimper ?? '',
                    'TRANSFEROFOWNER' => $transferOfowner ?? '',
                    'PreviousPolicyType' => $sPrePolicyType ?? '',
                    'AddonPackage' => '',
                    'NilDepreciationCoverYN' => in_array('101', $aAddons) ? 'Y' : 'N',
                    'PreviousNilDepreciation' => in_array('101', $aAddons) ? '1' : '0',
                    'HypothecationType' => array_key_exists('bankloantype', $HypothData) ? $HypothData['bankloantype'] : '',
                    'HypothecationBankName' => '',
                    'HypothecationAddress1' => '',
                    'HypothecationAddress2' => '',
                    'HypothecationAddress3' => '',
                    'HypothecationAgreementNo' => '',
                    'HypothecationCountry' => 'INDIA',
                    'HypothecationState' => '',
                    'HypothecationCity' => '',
                    'HypothecationPinCode' => '',
                    'SpecifiedPersonField' => '',
                    'PAOwnerDriverExclusion' => $PAcover ?? '0',
                    'PAOwnerDriverExReason' => $pAcoverReason ?? '',
                    'CPAInsComp' => '',
                    'CPAPolicyFmDt' => '',
                    'CPAPolicyNo' => '',
                    'CPAPolicyToDt' => '',
                    'CPASumInsured' => '',
                    'LossOfPersonBelongYN' => in_array('107', $aAddons) ? 'Y' : 'N',
                    'DailyExpRemYN' => in_array('109', $aAddons) ? 'Y' : 'N',
                    'RSACover' => in_array('102', $aAddons) ? 'Y' : 'N',
                    'InvReturnYN' => in_array('106', $aAddons) ? 'Y' : 'N',
                    'Eng_Protector' => in_array('104', $aAddons) ? 'Y' : 'N',
                    'Consumables' => in_array('103', $aAddons) ? 'Y' : 'N',
                    'KeyReplacementYN' => in_array('111', $aAddons) ? 'Y' : 'N',
                    'SHRIMOTORPROTECTION_YN' => in_array('113', $aAddons) ? 'Y' : 'N',
                    'tpPolAddr' => $sRtocity ?? 'Jaipur',
                    'tpPolComp' => $tpInsurCmpny ?? '',
                    'tpPolFmdt' => $sPretpfDate ?? '',
                    'tpPolNo' => $nTpolicyNo ?? '',
                    'tpPolTodt' => $sPretptoDate ?? '',
                    'CKYC_NO' => '',
                    'DOB' => $dob ?? '',
                    'POI_Type' => 'PAN',
                    'POI_ID' => 'AVSPV4566D',
                    'POA_Type' => 'PROOF OF POSSESSION OF AADHAR',
                    'POA_ID' => '5380',
                    'FatherName' => $aIdDetails['fathername'] ?? '',
                    'MotherName' => '',
                    'MaritalStatus' => '',
                    'SpouseName' => '',
                    'ResidentialStatus' => '',
                    'PHYSICALPOLICY' => $aNominee['physicalpolicy'] ?? '0',
                    'POI_DocumentFile' => $identityPhotoB64['based64'],
                    'POA_DocumentFile' => $addressPhotoB64['based64'],
                    'Insured_photo' => $insurePhotoB64['based64'],
                    'POI_DocumentExt' => $identityPhotoB64['extension'],
                    'POA_DocumentExt' => $addressPhotoB64['extension'],
                    'Insured_photoExt' => $insurePhotoB64['extension'],
                    'PANorForm60' => 'PAN',
                    'PanNo' => 'AVSPV4566D',
                    'Pan_Form60_Document' => $identityPhotoB64['based64'],
                    'Pan_Form60_Document_Ext' => $identityPhotoB64['extension'],
                    'Pan_Form60_Document_Name' => '1'
                ]
            ]);
            // return[
            //     "reee"=>$postdata
            // ];
            // dd($postdata);
            \Log::info(['generateBikeProposal_shriram' => $postdata]);
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $postdata,
                CURLOPT_HTTPHEADER => array(
                    'Username: ' . self::$Username,  // Correct format
                    'Password: ' . self::$Password,  // Correct format
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Cookie: ASP.NET_SessionId=3h5sofst43z4xtc5kse54rjy'
                ),
            ));
            $response = curl_exec($curl);
            Logfunction($userId, 'shriram', $response, $postdata, 'bike');
            \Log::info(['generateBikeProposal_shriram' => $response]);

            curl_close($curl);
            return $response;
        } catch (\Exception $e) {
            return $e->getMessage() . 'errorcode:privateBikeProposal';
        }
    }

    public static function generatePrivateCarQuote(Request $request, $today, $nextyear = '', $nPlanType = null, $idv = null)
    {
        try {
            $userId = $request->userid;
            $user = User::find($userId);
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
            $NilDepreciationCoverYN = null;
            $RSACover = null;
            $DailyExpRemYN = null;
            $KeyReplacementYN = null;
            $LossOfPersonBelongYN = null;
            $EmergencyTranHotelExpRemYN = null;
            $MultiCarBenefitYN = null;
            $Eng_Protector = null;
            $Consumables = null;
            $InvReturnYN = null;
            $LLtoPaidDriverYN = null;
            $NoEmpCoverLL = null;
            $SHRIMOTORPROTECTION_YN = null;
            $LimitedTPPDYN = null;
            $VoluntaryExcess = null;
            $AntiTheftYN = null;
            $Geographical = null;
            $PAforUnnamedPassenger = null;
            $PAPaidDriverConductorCleaner = null;
            $PreviousPolicyUWYear = null;
            $cachepolicyExpiry = 'cache_policyExpiry_' . $userId;
            $PAcover = null;
            $pAcoverReason = null;
            $VehicleType = null;
            $under = '';
            $AuthUser = $user->toArray();
            $oJourneyData = MotorJourney::where('userid', $userId)->where('is_car', '1')->first();
            $aData = DataModel::where('userid', $userId)->first();
            $PAforUnnamedamount = json_decode($aData->caraddonvalue, true);
            $PAforUnnamedamount = !empty($PAforUnnamedamount) ? $PAforUnnamedamount : '0';
            $aAccessories = json_decode($aData->accessories, true);
            $aCardata = json_decode($aData->knowcar_reg_details, true) ?? [];
            $claim = array_key_exists('policytoggle', $aCardata) ? '0' : '1';
            $claimper = array_key_exists('bonus-button', $aCardata) ? $aCardata['bonus-button'] : '0';
            $transferOfowner = array_key_exists('ownershiptoggle', $aCardata) ? $aCardata['ownershiptoggle'] : '0';
            $dRegDate = $aData->knowcar_reg_details ? json_decode($aData->knowcar_reg_details, true)['carregdate'] : date('d-m-Y');
            $regDate = \DateTime::createFromFormat('d-m-Y', $dRegDate);
            $addonAgeLimit = [
                '101' => 5,
                '103' => 5,
                '104' => 5,
                '107' => 5,
                '108' => 5,
                '109' => 5,
                '110' => 5,
                '111' => 5,
                '106' => 1
            ];
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
            $validAddons = [];

            foreach ($aAddons as $addonId) {
                $addonId = (string) $addonId;
                if (isset($addonAgeLimit[$addonId])) {
                    $maxyears = $addonAgeLimit[$addonId];
                    if (ValidateAddonAge($regDate, $maxyears)) {
                        $validAddons[] = $addonId;
                    }
                } else {
                    $validAddons[] = $addonId;
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
            $cachemotortype = 'cache_motortype_' . $userId;
            if (GetCache($cachemotortype) == 'knowcar') {
                if ($aCardata['prepolitype'] == 'odonly') {
                    $sPrePolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.OWNDAMAGE');
                    $sPrevexptoDate = $aCardata['odtodate'];  // k
                    $sPrevexpfDate = $aCardata['odfromdate'];  // k
                    $sPretpfDate = $aCardata['odtpfromdate'];
                    $sPretptoDate = $aCardata['odtptodate'];
                    $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
                    $PolicyFromDate = $date->addDay()->format('d-m-Y');
                    $PolicyToDate = $date->addYear()->subDay()->format('d-m-Y');
                } else if ($aCardata['prepolitype'] == 'bundled') {
                    $sPrePolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.BUNDLED');
                    $sPrevexptoDate = $aCardata['bdfromdate'];  // k
                    $sPrevexpfDate = $aCardata['bdtodate'];  // k
                    $sPretpfDate = $aCardata['bdtpfromdate'];
                    $sPretptoDate = $aCardata['bdtptodate'];
                    $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
                    $PolicyFromDate = $date->addDay()->format('d-m-Y');
                    $PolicyToDate = $date->addYear()->subDay()->format('d-m-Y');
                } elseif ($aCardata['prepolitype'] == 'comprehensive') {
                    $sPrePolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.PACKAGE');
                    $sPrevexptoDate = $aCardata['comptodate'];  // k
                    $sPrevexpfDate = $aCardata['compfromdate'];
                    $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
                    $PolicyFromDate = $date->addDay()->format('d-m-Y');
                    $PolicyToDate = $date->addYear()->subDay()->format('d-m-Y');
                } elseif ($aCardata['prepolitype'] == 'tponly') {
                    $sPrePolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.LIABILITY');
                    $sPrevexptoDate = $aCardata['tptodate'];
                    $sPrevexpfDate = $aCardata['tpfromdate'];
                    $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
                    $PolicyFromDate = $date->addDay()->format('d-m-Y');
                    $PolicyToDate = $date->addYear()->subDay()->format('d-m-Y');
                }
            }
            $sRegNumber = '';
            $sRegNo2 = '';
            $sRegNo3 = '';
            $sRegNo1 = '';
            $sRegNo4 = '';
            if (GetCache($cachemotortype) == 'newcar') {
                $sRegNumber = substr(explode('(', $aData->rtocode)[1], 0, -1);
                $sRegNo1 = substr(str_replace('-', '', $sRegNumber), 0, 2);
                $sRegNo2 = substr(str_replace('-', '', $sRegNumber), 2, 2);
            } else {
                $sRegNumber = $aData->carnumber;
                $sRegNo1 = substr($sRegNumber, 0, 2);
                $sRegNo2 = substr($sRegNumber, 2, 2);
                $sRegNo3 = substr($sRegNumber, 4, 2);
                $sRegNo4 = substr($sRegNumber, 6, 4);
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
                } else {
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
                $firstregdate = array_key_exists('carregdate', $aCardata) ? $aCardata['carregdate'] : '';
                $oModel = Shriram_Vehicle_Master::where('id', $aCardata['model'])->first();
            }
            if (GetCache($cachemotortype) == 'newcar') {
                $aCardata = json_decode($aData->newcar_reg_details, true) ?? [];
                if ($aCardata['under'] == 'company') {
                    $PAcover = '0';
                    $pAcoverReason = 'PA_TYPE2';
                } else {
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
                $firstregdate = $today->format('d-m-Y');
                $oModel = Shriram_Vehicle_Master::where('id', $aCardata['model'])->first();
            }

            $sVehicleCode = ' ';
            if ($oModel) {
                $sVehicleCode = $oModel->VEHICLE_CODE;
            }
            $sProdCode = getconstant('MOTOR.SHRIRAM.PRODUCTTYPE.PRIVATECAR');
            $sPolicyType = '';
            $sProposalType = '';
            $today = now();

            if (GetCache($cachemotortype) == 'newcar') {
                $VehicleType = 'W';
                $sProposalType = getconstant('MOTOR.SHRIRAM.PROPOSALTYPE.FRESHPROPOSAL');
                if ($nPlanType == '2') {
                    $PolicyFromDate = $today->format('d-m-Y');
                    $PolicyToDate = $today->addYears(3)->subDay()->format('d-m-Y');
                    $sPolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.BUNDLED');
                    $NilDepreciationCoverYN = in_array('101', $aAddons) ? 'Y' : 'N';
                    $RSACover = in_array('102', $aAddons) ? 'Y' : 'N';
                    $DailyExpRemYN = in_array('109', $aAddons) ? 'Y' : 'N';
                    $KeyReplacementYN = in_array('111', $aAddons) ? 'Y' : 'N';
                    $LossOfPersonBelongYN = in_array('107', $aAddons) ? 'Y' : 'N';
                    $EmergencyTranHotelExpRemYN = in_array('108', $aAddons) ? 'Y' : 'N';
                    $MultiCarBenefitYN = in_array('110', $aAddons) ? 'Y' : 'N';
                    $Eng_Protector = in_array('104', $aAddons) ? 'Y' : 'N';
                    $Consumables = in_array('103', $aAddons) ? 'Y' : 'N';
                    $InvReturnYN = in_array('106', $aAddons) ? 'Y' : 'N';
                    $AntiTheftYN = in_array('119', $aAddons) ? '1' : '0';
                    $LLtoPaidDriverYN = in_array('116', $aAddons) ? '1' : '0';
                    $NoEmpCoverLL = in_array('122', $aAddons) ? '1' : '0';
                    $SHRIMOTORPROTECTION_YN = in_array('113', $aAddons) ? 'Y' : 'N';
                    $LimitedTPPDYN = in_array('121', $aAddons) ? '1' : '0';
                    $VoluntaryExcess = in_array('120', $aAddons) ? '1' : '0';
                    $PAPaidDriverConductorCleaner = 1;
                    $Geographical = in_array('117', $aAddons) ? 1 : '0';
                    $Geographical = in_array('118', $aAddons) ? 1 : '0';
                    $PAforUnnamedPassenger = in_array('115', $aAddons) ? '1' : '0';
                }
            }

            if (GetCache($cachemotortype) == 'knowcar') {
                $VehicleType = 'U';
                $sProposalType = getconstant('MOTOR.SHRIRAM.PROPOSALTYPE.MARKETRENEWAL');
                if ($nPlanType == '1') {
                    $sPolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.OWNDAMAGE');
                    $NilDepreciationCoverYN = in_array('101', $aAddons) ? 'Y' : 'N';
                    $RSACover = in_array('102', $aAddons) ? 'Y' : 'N';
                    $DailyExpRemYN = in_array('109', $aAddons) ? 'Y' : 'N';
                    $KeyReplacementYN = in_array('111', $aAddons) ? 'Y' : 'N';
                    $LossOfPersonBelongYN = in_array('107', $aAddons) ? 'Y' : 'N';
                    $EmergencyTranHotelExpRemYN = in_array('108', $aAddons) ? 'Y' : 'N';
                    $MultiCarBenefitYN = in_array('110', $aAddons) ? 'Y' : 'N';
                    $Eng_Protector = in_array('104', $aAddons) ? 'Y' : 'N';
                    $Consumables = in_array('103', $aAddons) ? 'Y' : 'N';
                    $InvReturnYN = in_array('106', $aAddons) ? 'Y' : 'N';
                    $AntiTheftYN = in_array('119', $aAddons) ? '1' : '0';
                    $VoluntaryExcess = in_array('120', $aAddons) ? '1' : '0';
                    $PAPaidDriverConductorCleaner = 1;
                    $Geographical = in_array('117', $aAddons) ? 1 : '0';
                    $PAforUnnamedPassenger = in_array('115', $aAddons) ? '1' : '0';
                    $SHRIMOTORPROTECTION_YN = in_array('113', $aAddons) ? 'Y' : 'N';
                }
                if ($nPlanType == '2') {
                    $sPolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.PACKAGE');
                    $NilDepreciationCoverYN = in_array('101', $aAddons) ? 'Y' : 'N';
                    $RSACover = in_array('102', $aAddons) ? 'Y' : 'N';
                    $DailyExpRemYN = in_array('109', $aAddons) ? 'Y' : 'N';
                    $KeyReplacementYN = in_array('111', $aAddons) ? 'Y' : 'N';
                    $LossOfPersonBelongYN = in_array('107', $aAddons) ? 'Y' : 'N';
                    $EmergencyTranHotelExpRemYN = in_array('108', $aAddons) ? 'Y' : 'N';
                    $MultiCarBenefitYN = in_array('110', $aAddons) ? 'Y' : 'N';
                    $Eng_Protector = in_array('104', $aAddons) ? 'Y' : 'N';
                    $Consumables = in_array('103', $aAddons) ? 'Y' : 'N';
                    $InvReturnYN = in_array('106', $aAddons) ? 'Y' : 'N';
                    $AntiTheftYN = in_array('119', $aAddons) ? '1' : '0';
                    $LLtoPaidDriverYN = in_array('116', $aAddons) ? '1' : '0';
                    $NoEmpCoverLL = in_array('122', $aAddons) ? '1' : '0';
                    $SHRIMOTORPROTECTION_YN = in_array('113', $aAddons) ? 'Y' : 'N';
                    $LimitedTPPDYN = in_array('121', $aAddons) ? '1' : '0';
                    $VoluntaryExcess = in_array('120', $aAddons) ? '1' : '0';
                    $PAPaidDriverConductorCleaner = 1;
                    $Geographical = in_array('117', $aAddons) ? 1 : '0';
                    $Geographical = in_array('118', $aAddons) ? 1 : '0';
                    $PAforUnnamedPassenger = in_array('115', $aAddons) ? '1' : '0';
                }
                if ($nPlanType == '3' && (GetCache($cachepolicyExpiry) == 'Not Expired')) {
                    $sPolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.LIABILITY');
                    $LLtoPaidDriverYN = in_array('116', $aAddons) ? '1' : '0';
                    $LimitedTPPDYN = in_array('121', $aAddons) ? '1' : '0';
                    $PAforUnnamedPassenger = in_array('115', $aAddons) ? '1' : '0';
                    $NoEmpCoverLL = in_array('122', $aAddons) ? '1' : '0';
                    $Geographical = in_array('118', $aAddons) ? 1 : '0';
                }
                if ($nPlanType == '3' && (GetCache($cachepolicyExpiry) == 'Expired')) {
                    $sPolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.LIABILITY');
                    $PolicyFromDate = $today->format('d-m-Y');
                    $PolicyToDate = $today->addYear()->subDay()->format('d-m-Y');
                    $LLtoPaidDriverYN = in_array('116', $aAddons) ? '1' : '0';
                    $LimitedTPPDYN = in_array('121', $aAddons) ? '1' : '0';
                    $PAforUnnamedPassenger = in_array('115', $aAddons) ? '1' : '0';
                    $NoEmpCoverLL = in_array('122', $aAddons) ? '1' : '0';
                    $Geographical = in_array('118', $aAddons) ? 1 : '0';
                }
            }
            $today = now();
            $sRtocity = getRtocityApi($request, $sRegNumber);
            if ($sRtocity) {
                $sRtocity = $sRtocity->RTOCITY ?? $sRtocity->RTONAME;
            } else {
                $sRtocity = '';
            }
            $cachecaridv = 'cache_' . $userId . '_caridv';
            $idv = GetCache($cachecaridv);
            // $url = MasterAPI::where('apicode', '115')->first()->apistring;
            $url = 'https://nsecureapi.shriramgi.com/NOVADIGITAL/SVS_Services/PolicyGeneration.svc/RestService/GetQuote';
            $curl = curl_init();
            $postJson = json_encode([
                'objPolicyEntryETT' => [
                    'ReferenceNo' => '',
                    'ProdCode' => $sProdCode,
                    'PolicyFromDt' => $PolicyFromDate,
                    'PolicyToDt' => $PolicyToDate,
                    'PolicyIssueDt' => $PolicyFromDate,
                    'InsuredPrefix' => '1',
                    'InsuredName' => $AuthUser['name'],
                    'PolicyType' => $sPolicyType,
                    'ProposalType' => $sProposalType,
                    'VehicleCode' => $sVehicleCode ?? 'UL8066',
                    'EngineNo' => '',
                    'FirstRegDt' => $firstregdate ?? '',
                    'VehicleType' => $VehicleType ?? '',
                    'PAYEAR' => '1',
                    'ChassisNo' => '',
                    'RegNo1' => $sRegNo1,
                    'RegNo2' => $sRegNo2,
                    'RegNo3' => $sRegNo3,
                    'RegNo4' => $sRegNo4,
                    'RTOCode' => $sRegNo1 . '-' . $sRegNo2,
                    'PreviousPolicyNo' => '',
                    'PreviousInsurer' => '',
                    'PreviousPolicyFromDt' => $sPrevexpfDate ?? '',
                    'PreviousPolicyToDt' => $sPrevexptoDate ?? '',
                    'PreviousPolicyUWYear' => '',
                    'PreviousPolicySI' => '',
                    'TRANSFEROFOWNER' => $transferOfowner ?? '',
                    'PreviousPolicyClaimYN' => $claim,
                    'PreviousPolicyNCBPerc' => $claimper,
                    'PreviousPolicyType' => $sPrePolicyType ?? '',
                    'PreviousNilDepreciation' => '',
                    'PAforUnnamedPassengerYN' => $PAforUnnamedPassenger ?? '',
                    'PAforUnnamedPassengerSI' => ($PAforUnnamedPassenger == 1) ? $PAforUnnamedamount : '',
                    'ElectricalaccessYN' => array_key_exists('electrical', $aResult) ? 'Y' : 'N',
                    'ElectricalaccessSI' => array_key_exists('electrical', $aResult) ? $aResult['electrical'] : '',
                    'ElectricalaccessRemarks' => '',
                    'NonElectricalaccessYN' => array_key_exists('non-electrical', $aResult) ? 'Y' : 'N',
                    'NonElectricalaccessSI' => array_key_exists('non-electrical', $aResult) ? $aResult['non-electrical'] : '',
                    'NonElectricalaccessRemarks' => '',
                    'PAPaidDriverConductorCleanerYN' => 'Y',
                    'PAPaidDriverConductorCleanerSI' => '',
                    'PAPaidDriverCount' => '1',
                    'PAPaidConductorCount' => '1',
                    'PAPaidCleanerCount' => '1',
                    'PAOwnerDriverExclusion' => $PAcover ?? '',
                    'PAOwnerDriverExReason' => $pAcoverReason ?? '',
                    'NomineeNameforPAOwnerDriver' => 'Demo',
                    'NomineeAgeforPAOwnerDriver' => '26',
                    'NomineeRelationforPAOwnerDriver' => 'Son',
                    'AppointeeNameforPAOwnerDriver' => '',
                    'AppointeeRelationforPAOwnerDriver' => '',
                    'LLtoPaidDriverYN' => $LLtoPaidDriverYN ?? '0',
                    'NoEmpCoverLL' => $NoEmpCoverLL ?? '0',
                    'Bangladesh' => ($Geographical == 1) ? '1' : '0',
                    'Bhutan' => ($Geographical == 1) ? '1' : '0',
                    'SriLanka' => ($Geographical == 1) ? '1' : '0',
                    'Nepal' => ($Geographical == 1) ? '1' : '0',
                    'Pakistan' => ($Geographical == 1) ? '1' : '0',
                    'Maldives' => ($Geographical == 1) ? '1' : '0',
                    'CNGKitYN' => array_key_exists('cng', $aResult) ? 'Y' : 'N',
                    'CNGKitSI' => array_key_exists('cng', $aResult) ? $aResult['cng'] : '',
                    'InBuiltCNGKitYN' => '0',
                    'NilDepreciationCoverYN' => $NilDepreciationCoverYN ?? '',
                    'RSACover' => $RSACover ?? '',
                    'DailyExpRemYN' => $DailyExpRemYN ?? '',
                    'KeyReplacementYN' => $KeyReplacementYN ?? '',
                    'LossOfPersonBelongYN' => $LossOfPersonBelongYN ?? '',
                    'EmergencyTranHotelExpRemYN' => $EmergencyTranHotelExpRemYN ?? '',
                    'MultiCarBenefitYN' => $MultiCarBenefitYN ?? '',
                    'Eng_Protector' => $Eng_Protector ?? '',
                    'Consumables' => $Consumables ?? '',
                    'InvReturnYN' => $InvReturnYN ?? '',
                    'SHRIMOTORPROTECTION_YN' => $SHRIMOTORPROTECTION_YN ?? '',
                    'LimitedTPPDYN' => $LimitedTPPDYN ?? '0',
                    'Gender' => $AuthUser['gender'][0] ?? '',
                    'Address1' => '',
                    'Address2' => '',
                    'Address3' => '',
                    'State' => $sState,
                    'City' => $sRtocity,
                    'PinCode' => $AuthUser['pincode'],
                    'PanNo' => '',
                    'GSTNo' => '',
                    'TelephoneNo' => '',
                    'FaxNo' => '',
                    'EMailID' => '',
                    'tpPolFmdt' => $sPretpfDate ?? '',
                    'tpPolTodt' => $sPretptoDate ?? '',
                    'tpPolNo' => '',
                    'tpPolComp' => '',
                    'tpPolAddr' => '',
                    'MobileNo' => '',
                    'DateOfBirth' => '',
                    'POSAgentName' => '',
                    'POSAgentPanNo' => '',
                    'CoverNoteNo' => '',
                    'CoverNoteDt' => '',
                    'IDV_of_Vehicle' => $idv ?? '0',
                    'Colour' => '',
                    'VehiclePurposeYN' => '',
                    'DriverAgeYN' => '',
                    'LimitOwnPremiseYN' => '0',
                    'VoluntaryExcess' => $VoluntaryExcess ?? '',
                    'DeTariff' => '',
                    'PreInspectionReportYN' => '',
                    'PreInspection' => '',
                    'BreakIn' => 'NO',
                    'AddonPackage' => '',
                    'AntiTheftYN' => $AntiTheftYN ?? '',
                    'HypothecationType' => '',
                    'HypothecationBankName' => '',
                    'HypothecationAddress1' => '',
                    'HypothecationAddress2' => '',
                    'HypothecationAddress3' => '',
                    'HypothecationAgreementNo' => '',
                    'HypothecationCountry' => '',
                    'HypothecationState' => '',
                    'HypothecationCity' => '',
                    'HypothecationPinCode' => '',
                    'SpecifiedPersonField' => '',
                    'AadharNo' => '',
                    'AadharEnrollNo' => ''
                ]
            ]);
            //  return[
            //     "asdf" => $postJson
            //  ];

            // \Log::info([$nPlanType=>$postJson]);
            // \Log::info(['today'=>$today,'plantype'=>$nPlanType]);
            // \Log::info(['Car_Quotation_shriram_request' => $postJson]);
            SaveFile($postJson, 'shriram_car_quote_request.txt');
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $postJson,
                CURLOPT_HTTPHEADER => array(
                    'Username: ' . self::$Username,  // Correct format
                    'Password: ' . self::$Password,  // Correct format
                    'Content-Type: application/json',
                    'Accept: application/json'
                ),
            ));
            $response = curl_exec($curl);
            SaveFile($response, 'shriram_car_quote_response.txt');
            curl_close($curl);
            // \Log::info(['Car_Quotation_shriram_response' => $response]);
            return $response;
        } catch (\Exception $e) {
            // \Log::info($e->getMessage() . "errorcode:shriram_service_generatePrivateCarQuote");
            return ['status' => '0', 'message' => $e->getMessage() . 'An error occurred while fetching cache data.'];
        }
    }

    public static function FileIntoBase64($filePath)
    {
        $base64 = '';
        $image = '';
        $parts = explode('/', $filePath);
        $extension = explode('.', end($parts));
        if (file_exists($filePath)) {
            $image = file_get_contents($filePath);
            $base64 = base64_encode($image);
        } else {
            throw new \Exception('File not found.');
        }
        return ['extension' => '.' . end($extension), 'based64' => $base64];
    }

    public static function privateCarProposal(Request $request, $today, $nextyear, $aData)
    {
        try {
            $userId = $request->userid;
            $user = User::find($userId);
            $AuthUser = $user->toArray();
            $aData = DataModel::where('userid', $userId)->first();
            $oJourneyData = MotorJourney::where('userid', $userId)->where('is_car', '1')->first();
            $prevPolicydata = json_decode($oJourneyData->pre_policy_details, true);
            $VoluntaryExcess = null;
            $PreviousPolicyUWYear = null;
            $Geographical = null;
            $PAforUnnamedPassenger = null;
            $VehicleType = null;
            $HypothData = json_decode($oJourneyData->bank_details, true);
            $aCardata = json_decode($aData->knowcar_reg_details, true);
            $aNewCardata = json_decode($aData->newcar_reg_details, true);
            $aAccessories = json_decode($aData->accessories, true);
            $cachepolicyExpiry = 'cache_policyExpiry_' . $userId;
            $aVehicledetails = json_decode($oJourneyData->vehicle_details, true);
            $aResult = [];
            if (!empty($aAccessories)) {
                foreach ($aAccessories as $item) {
                    $aResult[$item['type']] = $item['amount'];
                }
            }
            $pAcoverReason = '';
            $PAcover = '';
            $PAforUnnamedamount = json_decode($aData->caraddonvalue, true);
            $PAforUnnamedamount = !empty($PAforUnnamedamount) ? $PAforUnnamedamount : '0';
            $nTpolicyNo = null;
            $sTpInsurer = null;
            $prevPolicyNo = null;
            $sRegNumber = $aData->carnumber;
            $sRegNumber = '';
            $sRegNo2 = '';
            $sRegNo3 = '';
            $sRegNo1 = '';
            $sRegNo4 = '';
            $cachemotortype = 'cache_motortype_' . $userId;

            if (GetCache($cachemotortype) == 'newcar') {
                $sRegNumber = substr(explode('(', $aData->rtocode)[1], 0, -1);
                $sRegNo1 = substr(str_replace('-', '', $sRegNumber), 0, 2);  // k
                $sRegNo2 = substr(str_replace('-', '', $sRegNumber), 2, 2);  // k
            } else {
                $sRegNumber = $aData->carnumber;
                $sRegNo1 = substr($sRegNumber, 0, 2);  // k
                $sRegNo2 = substr($sRegNumber, 2, 2);  // k
                $sRegNo3 = substr($sRegNumber, 4, 2);  // k
                $sRegNo4 = substr($sRegNumber, 6, 4);  // k
                $sRegdate = $aCardata['carregdate'] ?? date('d-m-Y');
            }
            $sPrevexptoDate = null;
            $sPrevexpfDate = null;
            $sPretpfDate = null;
            $sPretptoDate = null;
            $claim = null;
            $claimper = null;
            $transferOfowner = null;
            $gstno = null;
            $oModel = null;
            $claim = array_key_exists('policytoggle', $aCardata) ? '0' : '1';
            $claimper = array_key_exists('bonus-button', $aCardata) ? $aCardata['bonus-button'] : '0';
            $transferOfowner = array_key_exists('ownershiptoggle', $aCardata) ? $aCardata['ownershiptoggle'] : '0';

            if (GetCache($cachemotortype) == 'knowcar') {
                if ($aCardata['prepolitype'] == 'odonly') {
                    $sPrePolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.OWNDAMAGE');
                    $sPrevexptoDate = $aCardata['odtodate'];  // k
                    $sPrevexpfDate = $aCardata['odfromdate'];  // k
                    $sPretpfDate = $aCardata['odtpfromdate'];
                    $sPretptoDate = $aCardata['odtptodate'];
                    $nTpolicyNo = array_key_exists('tppolicynumber', $prevPolicydata) ? $prevPolicydata['tppolicynumber'] : '';
                    $sTpInsurer = array_key_exists('tpprevInsurance', $prevPolicydata) ? $prevPolicydata['tpprevInsurance'] : '';
                    $tpInsurCmpny = Shriram_Prev_insurence::where('id', $sTpInsurer)->first()->insurance;
                    $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
                    $PolicyFromDate = $date->addDay()->format('d-m-Y');
                    $PolicyToDate = $date->addYear()->subDay()->format('d-m-Y');
                } else if ($aCardata['prepolitype'] == 'bundled') {
                    $sPrePolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.BUNDLED');
                    $sPrevexptoDate = $aCardata['bdtodate'];  // k
                    $sPrevexpfDate = $aCardata['bdfromdate'];  // k
                    $sPretpfDate = $aCardata['bdtpfromdate'];
                    $sPretptoDate = $aCardata['bdtptodate'];
                    $nTpolicyNo = array_key_exists('tppolicynumber', $prevPolicydata) ? $prevPolicydata['tppolicynumber'] : '';
                    $sTpInsurer = array_key_exists('tpprevInsurance', $prevPolicydata) ? $prevPolicydata['tpprevInsurance'] : '';
                    $tpInsurCmpny = Shriram_Prev_insurence::where('id', $sTpInsurer)->first()->insurance;
                    $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
                    $PolicyFromDate = $date->addDay()->format('d-m-Y');
                    $PolicyToDate = $date->addYear()->subDay()->format('d-m-Y');
                } elseif ($aCardata['prepolitype'] == 'comprehensive') {
                    $sPrePolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.PACKAGE');
                    $sPrevexptoDate = $aCardata['comptodate'];  // k
                    $sPrevexpfDate = $aCardata['compfromdate'];
                    $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
                    $PolicyFromDate = $date->addDay()->format('d-m-Y');
                    $PolicyToDate = $date->addYear()->subDay()->format('d-m-Y');
                } elseif ($aCardata['prepolitype'] == 'tponly') {
                    $sPrePolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.LIABILITY');
                    $sPrevexptoDate = $aCardata['tptodate'];  // k
                    $sPrevexpfDate = $aCardata['tpfromdate'];  // k
                    $date = Carbon::createFromFormat('d-m-Y', $sPrevexptoDate);
                    $PolicyFromDate = $date->addDay()->format('d-m-Y');
                    $PolicyToDate = $date->addYear()->subDay()->format('d-m-Y');
                }
            }

            if (GetCache($cachemotortype) == 'newcar') {
                $oModel = Shriram_Vehicle_Master::where('id', $aNewCardata['model'])->first();
            } else {
                $oModel = Shriram_Vehicle_Master::where('id', $aCardata['model'])->first();
            }
            $sVehicleCode = '';
            if ($oModel) {
                $sVehicleCode = $oModel->VEHICLE_CODE;
            }
            $oObj = new ShriramCarController();
            $aDocument = UserMotorDescription::where('userid', $userId)->first();
            // return $aDocument;

            $aIdDetails = $aDocument->idnumber ? json_decode($aDocument->idnumber, true) : [];

            $filePath = json_decode($aDocument->document, true);
            $insurePhotoB64 = self::FileIntoBase64($filePath['insurephoto']);
            $identityPhotoB64 = self::FileIntoBase64($filePath['identity']['identityfront']);
            $addressPhotoB64 = self::FileIntoBase64($filePath['address']['addressfront']);
            self::initlize();
            // $url = MasterAPI::where('apicode', '116')->first()->apistring;
            $url = 'https://nsecureapi.shriramgi.com/NOVADIGITAL/SVS_Services/PolicyGeneration.svc/RestService/GenerateProposal';
            $aNominee = $oJourneyData->nominee_details ? jdec($oJourneyData->nominee_details) : [];
            $permanentAddress = json_decode($oJourneyData->permanent_address, true) ?? [];
            $pincode = $permanentAddress['pincode'];
            $state = Shriram_Pincode::where('PC_CODE', $pincode)->first();
            $sState = $state->STATE;
            $nPlanType = $aData->car_plan_type;
            $dRegDate = $aData->knowcar_reg_details ? json_decode($aData->knowcar_reg_details, true)['carregdate'] : date('d-m-Y');
            $regDate = \DateTime::createFromFormat('d-m-Y', $dRegDate);
            $addonAgeLimit = [
                '101' => 5,
                '103' => 5,
                '104' => 5,
                '107' => 5,
                '108' => 5,
                '109' => 5,
                '110' => 5,
                '111' => 5,
                '106' => 1
            ];
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
            $validAddons = [];

            foreach ($aAddons as $addonId) {
                $addonId = (string) $addonId;
                if (isset($addonAgeLimit[$addonId])) {
                    $maxyears = $addonAgeLimit[$addonId];
                    if (ValidateAddonAge($regDate, $maxyears)) {
                        $validAddons[] = $addonId;
                    }
                } else {
                    $validAddons[] = $addonId;
                }
            }
            $aAddons = $validAddons;
            // return [
            //     "valid" => $aAddons
            // ];
            $cachecaridv = 'cache_' . $userId . '_caridv';
            $nIdv = GetCache($cachecaridv);
            $randomValue = self::genRandomNumber();
            $EngineNo = $aVehicledetails['Enginenumber'];
            $ChassisNo = $aVehicledetails['Chassisnumber'];
            $sProdCode = getconstant('MOTOR.SHRIRAM.PRODUCTTYPE.PRIVATECAR');
            $sPolicyType = '';
            $sProposalType = '';
            $gstno = null;
            $today = now();
            if (GetCache($cachemotortype) == 'newcar') {
                $VehicleType = 'W';
                $sProposalType = getconstant('MOTOR.SHRIRAM.PROPOSALTYPE.FRESHPROPOSAL');
                $companydetails = json_decode($oJourneyData->company_details, true);
                if ($aNewCardata['under'] == 'company') {
                    $PAcover = 0;
                    $pAcoverReason = 'PA_TYPE2';
                } else {
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
                if ($nPlanType == '2') {
                    $sPolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.BUNDLED');
                    $sRegdate = $today->format('d-m-Y');
                    $PolicyFromDate = $today->format('d-m-Y');
                    $PolicyToDate = $today->addYears(3)->subDay()->format('d-m-Y');
                    $Geographical = in_array('118', $aAddons) ? 1 : '0';
                    $Geographical = in_array('117', $aAddons) ? 1 : '0';
                    $PAforUnnamedPassenger = in_array('115', $aAddons) ? '1' : '0';
                }
            }
            if (GetCache($cachemotortype) == 'knowcar') {
                $VehicleType = 'U';
                $prevPolicyNo = $prevPolicydata['policynumber'];
                $prevInsurCmpny = Shriram_Prev_insurence::where('id', $prevPolicydata['prevInsuranceId'])->first()->insurance;
                if ($aCardata['under'] == 'company') {
                    $PAcover = 0;
                    $pAcoverReason = 'PA_TYPE2';
                    $companydetails = json_decode($oJourneyData->company_details, true);
                    $gstno = $companydetails['gstnumber'];
                } else {
                    $PAcover = $aData->pacover;
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
                $sProposalType = getconstant('MOTOR.SHRIRAM.PROPOSALTYPE.MARKETRENEWAL');
                if ($nPlanType == '1') {
                    $sPolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.OWNDAMAGE');
                    $Geographical = in_array('117', $aAddons) ? 1 : '0';
                }
                if ($nPlanType == '2') {
                    $sPolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.PACKAGE');
                    $Geographical = in_array('117', $aAddons) ? 1 : '0';
                    $Geographical = in_array('118', $aAddons) ? 1 : '0';
                    $PAforUnnamedPassenger = in_array('115', $aAddons) ? '1' : '0';
                }
                if ($nPlanType == '3' && (GetCache($cachepolicyExpiry) == 'Not Expired')) {
                    $sPolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.LIABILITY');
                    $Geographical = in_array('118', $aAddons) ? 1 : '0';
                    $PAforUnnamedPassenger = in_array('115', $aAddons) ? '1' : '0';
                }
                if ($nPlanType == '3' && (GetCache($cachepolicyExpiry) == 'Expired')) {
                    $sPolicyType = getconstant('MOTOR.SHRIRAM.POLICYTYPE.LIABILITY');
                    $Geographical = in_array('118', $aAddons) ? 1 : '0';
                    $PAforUnnamedPassenger = in_array('115', $aAddons) ? '1' : '0';
                    $PolicyFromDate = $today->format('d-m-Y');
                    $PolicyToDate = $today->addYear()->subDay()->format('d-m-Y');
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
            $cacheproductcode = 'cache_productcode_' . $userId;
            SetCache($cacheproductcode, $sProdCode);
            $cacheproposaltype = 'cache_proposaltype_' . $userId;
            SetCache($cacheproposaltype, $sProposalType);
            $cachepolicytpe = 'cache_policytpe_' . $userId;
            SetCache($cachepolicytpe, $sPolicyType);
            $curl = curl_init();
            $postdata = json_encode([
                'objPolicyEntryETT' => [
                    'ReferenceNo' => '123456',  // unique each request
                    'ProdCode' => $sProdCode,
                    'PolicyFromDt' => $PolicyFromDate ?? '',  // '02-05-2025',
                    'PolicyToDt' => $PolicyToDate ?? '',  // "1-05-2026",
                    'PolicyIssueDt' => $PolicyFromDate ?? '',  // '02-05-2025',
                    'InsuredPrefix' => '1',  // mr/mrs
                    'InsuredName' => $oJourneyData->name ?? '',  // name
                    'Gender' => $AuthUser['gender'][0] ?? 'M',
                    'Address1' => $permanentAddress['address1'] ?? '',
                    'Address2' => $permanentAddress['address2'] ?? '',
                    'Address3' => $permanentAddress['landmark'] ?? '',
                    'State' => $sState ?? '',
                    'City' => $permanentAddress['city'] ?? '',
                    'PinCode' => $permanentAddress['pincode'] ?? '',  // manually
                    'GSTNo' => $gstno ?? '',  // in  case o=of corporate
                    'TelephoneNo' => '',
                    'ProposalType' => $sProposalType ?? '',
                    'PolicyType' => $sPolicyType ?? '',  // "MOT-PLT-009",//$sPolicyType,
                    'DateOfBirth' => Carbon::createFromFormat('d-m-Y', $dob)->format('Y-m-d'),  // $dob,//kyc dob
                    'MobileNo' => $AuthUser['mobile'] ?? '',
                    'FaxNo' => '',
                    'EmailID' => $AuthUser['email'] ?? '',
                    'POSAgentName' => 'NANDITA TIWARI',  // credential
                    'POSAgentPanNo' => 'BTYPB4567K',  // up same
                    'CoverNoteNo' => '',
                    'CoverNoteDt' => '',
                    'VehicleCode' => $sVehicleCode ?? 'UL8066',
                    'FirstRegDt' => $sRegdate ?? '',
                    'VehicleType' => $VehicleType ?? '',
                    'EngineNo' => $EngineNo ?? '',
                    'ChassisNo' => $ChassisNo ?? '',
                    'RegNo1' => $sRegNo1 ?? '',
                    'RegNo2' => $sRegNo2 ?? '',
                    'RegNo3' => $sRegNo3 ?? '',
                    'RegNo4' => $sRegNo4 ?? '',
                    'RTOCode' => $sRegNo1 ?? '' . '-' . $sRegNo2 ?? '',
                    'IDV_of_Vehicle' => $nIdv ?? '',
                    'Colour' => '',
                    'VoluntaryExcess' => in_array('120', $aAddons) ? '1' : '0',  // $VoluntaryExcess??"",//skip
                    'NoEmpCoverLL' => in_array('122', $aAddons) ? '1' : '0',  // $NoEmpCoverLL??"",//skip
                    'NoOfCleaner' => '',  // skip
                    'NoOfDriver' => '0',  // skip
                    'NoOfConductor' => '',  // skip
                    'VehicleMadeinindiaYN' => 'N',
                    'VehiclePurposeYN' => '',
                    'NFPP_Employees' => '',
                    'NFPP_OthThanEmp' => '',
                    'LimitOwnPremiseYN' => 'N',
                    'Bangladesh' => ($Geographical == 1) ? '1' : '0',
                    'Bhutan' => ($Geographical == 1) ? '1' : '0',
                    'SriLanka' => ($Geographical == 1) ? '1' : '0',
                    'Nepal' => ($Geographical == 1) ? '1' : '0',
                    'Pakistan' => ($Geographical == 1) ? '1' : '0',
                    'Maldives' => ($Geographical == 1) ? '1' : '0',
                    'CNGKitYN' => array_key_exists('cng', $aResult) ? 'Y' : 'N',
                    'CNGKitSI' => array_key_exists('cng', $aResult) ? $aResult['cng'] : '',
                    'InBuiltCNGKit' => '0',
                    'LimitedTPPDYN' => in_array('121', $aAddons) ? '1' : '0',  // $LimitedTPPDYN??"",
                    'DeTariff' => 0,
                    'IMT23YN' => '',
                    'BreakIn' => 'NO',  // ----in expired case
                    'PreInspectionReportYN' => '0',
                    'PreInspection' => '',
                    'FitnessCertificateno' => '',
                    'FitnessValidupto' => '',
                    'VehPermit' => '',
                    'PermitNo' => '',
                    'PAforUnnamedPassengerYN' => $PAforUnnamedPassenger ?? '0',
                    'PAforUnnamedPassengerSI' => ($PAforUnnamedPassenger == 1) ? $PAforUnnamedamount : '0',
                    'ElectricalaccessYN' => array_key_exists('electrical', $aResult) ? 'Y' : 'N',
                    'ElectricalaccessSI' => array_key_exists('electrical', $aResult) ? $aResult['electrical'] : '',
                    'ElectricalaccessRemarks' => '',
                    'NonElectricalaccessYN' => array_key_exists('non-electrical', $aResult) ? 'Y' : 'N',
                    'NonElectricalaccessSI' => array_key_exists('non-electrical', $aResult) ? $aResult['non-electrical'] : '',
                    'NonElectricalaccessRemarks' => '',
                    'PAPaidDriverConductorCleanerYN' => 0,
                    'PAPaidDriverConductorCleanerSI' => 0,
                    'PAPaidDriverCount' => '0',
                    'PAPaidConductorCount' => '',
                    'PAPaidCleanerCount' => '',
                    'NomineeNameforPAOwnerDriver' => $aNominee['nomineename'] ?? 'unknown',
                    'NomineeAgeforPAOwnerDriver' => $aNominee['nomineedob'] ? date('Y') - (int) last(explode('-', $aNominee['nomineedob'])) : '28',
                    'NomineeRelationforPAOwnerDriver' => $aNominee['nomineerelation'] ?? 'BROTHER',
                    'AppointeeNameforPAOwnerDriver' => $aNominee['appointeename'] ?? '',
                    'AppointeeRelationforPAOwnerDriver' => $aNominee['appointeerelation'] ?? '',
                    'LLtoPaidDriverYN' => in_array('116', $aAddons) ? '1' : '0',  // $LLtoPaidDriverYN??"",
                    'AntiTheftYN' => in_array('119', $aAddons) ? '1' : '0',  // $AntiTheftYN??"",
                    'PreviousPolicyNo' => $prevPolicyNo ?? '',  // "12345678",
                    'PreviousInsurer' => $prevInsurCmpny ?? '',  // "Tata AIG General Insurance Co Ltd",
                    'PreviousPolicyFromDt' => $sPrevexpfDate ?? '',
                    'PreviousPolicyToDt' => $sPrevexptoDate ?? '',
                    'PreviousPolicySI' => '',
                    'PreviousPolicyClaimYN' => $claim ?? '',
                    'PreviousPolicyUWYear' => '',
                    'PreviousPolicyNCBPerc' => $claimper ?? '',
                    'TRANSFEROFOWNER' => $transferOfowner ?? '',
                    'PreviousPolicyType' => $sPrePolicyType ?? '',  // "MOT-PLT-009",
                    'AddonPackage' => '',
                    'NilDepreciationCoverYN' => in_array('101', $aAddons) ? 'Y' : 'N',
                    'PreviousNilDepreciation' => in_array('101', $aAddons) ? '1' : '0',
                    'HypothecationType' => array_key_exists('bankloantype', $HypothData) ? $HypothData['bankloantype'] : '',
                    'HypothecationBankName' => '',
                    'HypothecationAddress1' => '',
                    'HypothecationAddress2' => '',
                    'HypothecationAddress3' => '',
                    'HypothecationAgreementNo' => '',
                    'HypothecationCountry' => 'INDIA',
                    'HypothecationState' => '',
                    'HypothecationCity' => '',
                    'HypothecationPinCode' => '',
                    'SpecifiedPersonField' => '',
                    'PAOwnerDriverExclusion' => $PAcover ?? '0',
                    'PAOwnerDriverExReason' => $pAcoverReason ?? '',  // ($PAcover == "0" || !$PAcover) ? $pAcoverReason : "",
                    'CPAInsComp' => '',
                    'CPAPolicyFmDt' => '',
                    'CPAPolicyNo' => '',
                    'CPAPolicyToDt' => '',
                    'CPASumInsured' => '',
                    'LossOfPersonBelongYN' => in_array('107', $aAddons) ? 'Y' : 'N',
                    'SHRIMOTORPROTECTION_YN' => in_array('113', $aAddons) ? 'Y' : 'N',
                    'MultiCarBenefitYN' => in_array('110', $aAddons) ? 'Y' : 'N',
                    'DepDeductWaiverYN' => in_array('101', $aAddons) ? 'Y' : 'N',
                    'DailyExpRemYN' => in_array('109', $aAddons) ? 'Y' : 'N',
                    'RSACover' => in_array('102', $aAddons) ? 'Y' : 'N',
                    'InvReturnYN' => in_array('106', $aAddons) ? 'Y' : 'N',
                    'Eng_Protector' => in_array('104', $aAddons) ? 'Y' : 'N',
                    'Consumables' => in_array('103', $aAddons) ? 'Y' : 'N',
                    'EmergencyTranHotelExpRemYN' => in_array('108', $aAddons) ? 'Y' : 'N',
                    'KeyReplacementYN' => in_array('111', $aAddons) ? 'Y' : 'N',
                    'tpPolAddr' => $sRtocity ?? 'Jaipur',
                    'tpPolComp' => $tpInsurCmpny ?? '',
                    'tpPolFmdt' => $sPretpfDate ?? '',
                    'tpPolNo' => $nTpolicyNo ?? '',
                    'tpPolTodt' => $sPretptoDate ?? '',
                    'CKYC_NO' => '',
                    'DOB' => $dob ?? '',
                    'POI_Type' => 'PAN',
                    'POI_ID' => 'AVSPV4566D',
                    'POA_Type' => 'PROOF OF POSSESSION OF AADHAR',
                    'POA_ID' => '5380',
                    'FatherName' => $aIdDetails['fathername'] ?? '',
                    'MotherName' => $aIdDetails['fathername'] ?? '',
                    'MaritalStatus' => '',
                    'SpouseName' => '',
                    'ResidentialStatus' => '',
                    'PHYSICALPOLICY' => $aNominee['physicalpolicy'] ?? '0',
                    'POI_DocumentFile' => $identityPhotoB64['based64'] ?? '',
                    'POA_DocumentFile' => $addressPhotoB64['based64'] ?? '',
                    'Insured_photo' => $insurePhotoB64['based64'] ?? '',
                    'POI_DocumentExt' => $identityPhotoB64['extension'] ?? '',
                    'POA_DocumentExt' => $addressPhotoB64['extension'] ?? '',
                    'Insured_photoExt' => $insurePhotoB64['extension'] ?? '',
                    'PANorForm60' => 'PAN',
                    'PanNo' => 'AVSPV4566D',
                    'Pan_Form60_Document' => $identityPhotoB64['based64'] ?? '',
                    'Pan_Form60_Document_Ext' => $identityPhotoB64['extension'] ?? '',
                    'Pan_Form60_Document_Name' => '1'
                ]
            ]);
            //     return[
            //     "asdf" => $postdata
            //  ];
            // return $postdata;
            // dd($postdata);
            \Log::info(['privateCarProposal_shriram_Request_Log' => $postdata]);
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $postdata,
                CURLOPT_HTTPHEADER => array(
                    'Username: ' . self::$Username,  // Correct format
                    'Password: ' . self::$Password,  // Correct format
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Cookie: ASP.NET_SessionId=3h5sofst43z4xtc5kse54rjy'
                ),
            ));
            $response = curl_exec($curl);
            //  return[
            //     "asdf" => $response
            //  ];
            Logfunction($userId, 'shriram', $response, $postdata, 'car');
            \Log::info(['privateCarProposal_shriram_response' => $response]);

            curl_close($curl);
            return $response;
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ]);
            // return $e->getMessage() . 'errorcode:privateCarProposal';
        }
    }

    public static function PVVCProposal()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://novaapiuat.shriramgi.com/UATNOVADIGITAL/SVS_Services/PolicyGeneration.svc/RestService/GeneratePCCVProposal ',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{ 
        "objPolicyEntryETT": { 
            "ReferenceNo": "", 
            "ProdCode": "MOT-PRD-005", 
            "ProposalType": "RENEWAL OF OTHERS", 
            "PolType": "MOT-PLT-001", 
            "PolicyFromDt": "12-Jun-2024",
            "PolicyToDt": "11-Jun-2025",
            "PolicyIssueDt": "03-Jun-2024",
            "InsuredPrefix": "1", 
            "InsuredName": "VIGNESH SCHOOL/INSTITUTION", 
            "Gender": "", 
            "Address2": "Chennai", 
            "Address3": "Chennai", 
            "State": "TN", 
            "City": "Chennai", 
            "PinCode": "600017", 
            "MobileNo": "9087806070", 
            "EmailID": "test@gmail.com", 
            "GSTNo": "", 
            "TelephoneNo": "", 
            "DateOfBirth": "", 
            "FaxNo": "", 
            "VehicleCategory": "CLASS_4C2", 
            "PCCVVehType": "Other Taxi", 
            "VehicleCode": "M_10139", 
            "FirstRegDt": "01-01-2016", 
            "VehicleType": "U", 
            "EngineNo": "4ZR1HFLHJ9787GB74", 
            "ChassisNo": "DNJNUMTWOHFKG569", 
            "RegNo1": "KA", 
            "RegNo2": "02", 
            "RegNo3": "AT", 
            "RegNo4": "1255", 
            "RTOCode": "KA-02", 
            "IDV_of_Vehicle": "", 
            "Colour": "Red", 
            "PreviousInsurer": "Acko General Insurance Ltd", 
            "PreviousPolicyNo": "", 
            "PreviousPolicyFromDt": "12-Jun-2023", 
            "PreviousPolicyToDt": "11-Jun-2024", 
            "PreviousPolicySI": "", 
            "PreviousPolicyClaimYN": "0", 
            "PreviousPolicyUWYear": "", 
            "PreviousPolicyNCBPerc": 50, 
            "PreviousPolicyType": "MOT-PLT-001", 
            "NilDepreciationCoverYN": "N", 
            "PreviousNilDepreciation": 0, 
            "PAOwnerDriverExclusion": "1", 
            "PAOwnerDriverExReason": "PA_TYPE2", 
            "NomineeNameforPAOwnerDriver": "", 
            "NomineeAgeforPAOwnerDriver": "", 
            "NomineeRelationforPAOwnerDriver": "", 
            "AppointeeNameforPAOwnerDriver": "", 
            "AppointeeRelationforPAOwnerDriver": "", 
            "ElectricalaccessYN": "Y", 
            "ElectricalaccessSI": "10000", 
            "ElectricalaccessRemarks": "demo", 
            "NonElectricalaccessYN": "Y", 
            "NonElectricalaccessSI": "10000", 
            "NonElectricalaccessRemarks": "demo", 
            "PAPaidDriverConductorCleanerYN": "", 
            "PAPaidDriverConductorCleanerSI": "", 
            "PAPaidDriverCount": "0", 
            "PAPaidConductorCount": "0", 
            "PAPaidCleanerCount": "0", 
            "SHRIMOTORPROTECTION_YN": "N", 
            "RSACover": "N", 
            "Bangladesh": 0, 
            "Bhutan": 0, 
            "Srilanka": 0, 
            "Nepal": 0, 
            "Pakistan": 0, 
            "Maldives": 0, 
            "CNGKitYN": 0, 
            "CNGKitSI": 0, 
            "InBuiltCNGKit": 0, 
            "NoEmpCoverLL": "0", 
            "NoOfCleaner": "", 
            "NoOfDriver": "0", 
            "NoOfConductor": "", 
            "DeTariff": "", 
            "IMT23YN": "", 
            "BreakIn": "No", 
            "PreInspectionReportYN": "0", 
            "PreInspection": "", 
            "POSAgentName": "", 
            "POSAgentPanNo": "", 
            "CoverNoteNo": "", 
            "CoverNoteDt": "", 
            "VoluntaryExcess": "0", 
            "VehicleMadeinindiaYN": "", 
            "VehiclePurposeYN": "", 
            "NFPP_Employees": "", 
            "NFPP_OthThanEmp": "", 
            "LimitOwnPremiseYN": "", 
            "LimitedTPPDYN": 0, 
            "FitnessCertificateno": "", 
            "FitnessValidupto": "", 
            "VehPermit": "", 
            "PermitNo": "", 
            "PAforUnnamedPassengerYN": "", 
            "PAforUnnamedPassengerSI": "", 
            "LLtoPaidDriverYN": 0, 
            "AntiTheftYN": 0, 
            "HypothecationType": "", 
            "HypothecationBankName": "", 
            "HypothecationAddress1": "", 
            "HypothecationAddress2": "", 
            "HypothecationAddress3": "", 
            "HypothecationAgreementNo": "", 
            "HypothecationCountry": "", 
            "HypothecationState": "", 
            "HypothecationCity": "",  
            "HypothecationPinCode": "", 
            "SpecifiedPersonField": "", 
            "CKYC_NO": "12345678910114", 
    "DOB": "", 
        "PANorForm60": "PAN",
    "PanNo": "AVSPV4566D",
    "Pan_Form60_Document": "base64format",
    "Pan_Form60_Document_Ext": ".pdf",
    "Pan_Form60_Document_Name": "1",
    "POI_Type": "PAN",  
    "POI_ID": "ANNPM6633T", 
    "POA_Type": "PROOF OF POSSESSION OF AADHAR", 
    "POA_ID": "1234", 
    "FatherName": "", 
    "MotherName": "", 
    "MaritalStatus": "", 
    "SpouseName": "", 
    "ResidentialStatus": "", 
    "POI_DocumentFile": "Base64format", 
    "POA_DocumentFile": "Base64format", 
    "Insured_photo": "Base64format", 
    "POI_DocumentExt": ".pdf", 
    "POA_DocumentExt": ".pdf", 
    "Insured_photoExt": ".pdf" 
        } 
    } ',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json',
                'Username: NiveshIns',
                'Password: shriram@1',
                'Cookie: ASP.NET_SessionId=iognzb4k4jhmjhe2kqvqoaw4'
            ),
        ));
        $response = curl_exec($curl);
        \Log::info(['PVVCProposal_Shriram' => $response]);
        curl_close($curl);
        echo $response;
    }

    public static function GCCVProposal()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://novaapiuat.shriramgi.com/UATNOVADIGITAL/SVS_Services/PolicyGeneration.svc/RestService/GenerateGCCVProposal ',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
    "objGCCVProposalEntryETT": {
        "ProdCode": "MOT-PRD-003",
        "PolType": "MOT-PLT-001",
        "PolicyFromDt": "11/11/2022",
        "PolicyToDt": "10/11/2023",
        "PolicyIssueDt": "11/10/2022",
        "InsuredName": "VIGNESH",
        "InsuredPrefix": "3",
        "ProposalType": "RENEWAL OF OTHERS",
        "VehicleCategory": "CLASS_4A1",
        "VehicleCode": "M_21087",
        "GVW": "975",
        "VehicleType": "U",
        "RegNo1": "KA",
        "RegNo2": "06",
        "RegNo3": "A",
        "RegNo4": "9871",
        "FirstRegDt": "17/11/2018",
        "ChassisNo": "hhgfhfggh865734343",
        "EngineNo": "bv768kj98096987999",
        "PreviousInsurer": "National Insurance Company Limited",
        "PreviousNilDepreciation": "0",
        "PreviousPolicyClaimYN": "0",
        "PreviousPolicyFromDt": "11/11/2021",
        "PreviousPolicyToDt": "10/11/2022",
        "PreviousPolicyNCBPerc": "",
        "PreviousPolicyNo": "64654654",
        "PreviousPolicySI": "654754",
        "PreviousPolicyType": "MOT-PLT-001",
        "PreviousPolicyUWYear": "",
        "AadharEnrollNo": "",
        "AadharNo": "954114421457",
        "Address1": "275,RK MUTT ROAD",
        "Address2": "MYLAPORE",
        "Address3": "CHENNAI-600004",
        "City": "CHENNAI",
        "State": "TN",
        "PinCode": "626214",
        "GSTNo": "",
        "Gender": "",
        "EmailID": "vignesh@gmail.com",
        "VehicleAge": "5",
        "VehicleMadeinindiaYN": "Y",
        "VehicleManufactureYear": "2017",
        "AgeOfOwner": "36",
        "AgeOfPaidDriver": "25",
        "AgeOfVehicle": "14",
        "Amount": "742500.00",
        "Amount1": "",
        "AntiTheftYN": "N",
        "AppointeeNameforPAOwnerDriver": "",
        "AppointeeRelationforPAOwnerDriver": "",
        "BodyType": "OPEN WOODEN BODY",
        "BreakIn": "",
        "CC": "",
        "CPAInsComp": "",
        "CPAPolicyFmDt": "",
        "CPAPolicyNo": "",
        "CPAPolicyToDt": "",
        "CPASumInsured": "",
        "CancelOrRefuseRenew": "",
        "CaptiveUseYN": "",
        "ClaimsLodged": "",
        "Colour": "",
        "Consumables": "",
        "CoverLampTyreTubeYN": "",
        "CoverNoteDt": "",
        "CoverNoteNo": "",
        "DE_TARIFFDIS": "",
        "DateOfBirth": "",
        "DateOfPurchaseOfVehAsPerInvOrSaleLetter": "17/11/2017",
        "DeTariff": "",
        "IMT23YN": "",
        "InBuiltCNGKit": "",
        "Nepal": "0",
        "Maldives": "0",
        "Bangladesh": "0",
        "Bhutan": "0",
        "Srilanka": "0",
        "Pakistan": "0",
        "CNGKitSI": "",
        "CNGKitYN": "",
        "LLtoPaidDriverYN": "",
        "NoEmpCoverLL": "",
        "NilDepreciationCoverYN": "1",
        "NoOfCleaner": "",
        "NoOfConductor": "",
        "NoOfCoolies": "",
        "ElectricalaccessYN": "Y",
        "ElectricalaccessSI": "1000",
        "ElectricalaccessRemarks": "",
        "NonElectricalaccessYN": "",
        "NonElectricalaccessSI": "",
        "NonElectricalaccessRemarks": "",
        "PAOwnerDriverExReason": "",
        "PAOwnerDriverExclusion": "",
        "PAPaidCleanerCount": "",
        "PAPaidConductorCount": "",
        "PAPaidDriverConductorCleanerSI": "",
        "PAPaidDriverConductorCleanerYN": "",
        "PAPaidDriverCount": "",
        "PAforUnnamedPassengerSI": "",
        "PAforUnnamedPassengerYN": "",
        "NomineeAgeforPAOwnerDriver": "",
        "NomineeNameforPAOwnerDriver": "",
        "NomineeRelationforPAOwnerDriver": "",
        "SHRIMOTORPROTECTION_YN": "N",
        "GCCVVehType": "",
        "HypothecationAddress1": "",
        "HypothecationAddress2": "",
        "HypothecationAddress3": "",
        "HypothecationAgreementNo": "",
        "HypothecationBankName": "",
        "HypothecationCity": "",
        "HypothecationCountry": "",
        "HypothecationPinCode": "",
        "HypothecationState": "",
        "HypothecationType": "",
        "IDV_of_Vehicle": "",
        "IndemnityToHirerYN": "",
        "LimitOwnPremiseYN": "",
        "LimitedTPPDYN": "",
        "MobileNo": "",
        "NFPPEMP": "",
        "NFPP_OthThanEmp": "",
        "NatureOfGoods": "",
        "NoOfClaims": "",
        "NoOfClaims1": "",
        "NoOfDCCforPA": "",
        "NoOfDriver": "",
        "NoOfTrailers": "",
        "POSAgentName": "",
        "POSAgentPanNo": "",
        "PanNo": "",
        "Permit": "",
        "PreInspection": "",
        "PreInspectionReportYN": "",
        "PuccNo": "",
        "PuccState": "",
        "PuccYN": "Y",
        "RTOCode": "",
        "ReferenceNo": "",
        "SeatingCapacity": "2",
        "SpeedometerReading": "",
        "TDChassisNo": "sfsdfdsf34289076867",
        "TDRegNo": "",
        "TRANSFEROFOWNER": "",
        "TrailerVehicleCode": "",
        "UseofVehisLimitedOwnPremisesYN": "",
        "Validupto": "",
        "VehFitWithTublessTyresYN": "",
        "VehFittedWithFGTankYN": "",
        "VehParkedDuringNight": "",
        "VehPermit": "",
        "VoluntaryExcess": "",
        "CKYC_NO": "",
        "DOB": "",
        "POI_Type": "PAN",
        "POI_ID": "ANNPM6633T",
        "POA_Type": "PROOF OF POSSESSION OF AADHAR",
        "POA_ID": "123478961234",
        "FatherName": "",
        "MotherName": "",
        "MaritalStatus": "",
        "SpouseName": "",
        "ResidentialStatus": "",
        "POI_DocumentFile": "",
        "POA_DocumentFile": "",
        "Insured_photo": "",
        "POI_DocumentExt": "",
        "POA_DocumentExt": "",
        "Insured_photoExt": ""
    }
    }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json',
                'Username: NiveshIns',
                'Password: shriram@1',
                'Cookie: ASP.NET_SessionId=iognzb4k4jhmjhe2kqvqoaw4'
            ),
        ));

        $response = curl_exec($curl);
        \Log::info(['GCCVProposal_shriram' => $response]);
        curl_close($curl);
        echo $response;
    }

    public static function SGIRenewal()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://novaapiuat.shriramgi.com/UATNOVADIGITAL/SVS_Services/PolicyGeneration.svc/ 
            RestService/GetRenewalDetailsResult',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{ 
            "EmailId": "s.vignesh@novactech.in", 
            "MobileNo": "9087806070", 
            "PolicyNumber": "326022/31/21/001681", 
            "VehicleRegno": "UA-06-H-5829" 
        } ',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json',
                'Username: NiveshIns',
                'Password: shriram@1',
                'Cookie: ASP.NET_SessionId=iognzb4k4jhmjhe2kqvqoaw4'
            ),
        ));
        $response = curl_exec($curl);
        \Log::info(['SGIRenewal_shriram' => $response]);
        curl_close($curl);
        echo $response;
    }

    public static function Payment()
    {
        return view('front.motor.car.vendor.shriram.payment');
    }

    public static function PaymentStatus($nProposalNo, $nQuoteId)
    {
        $url = 'https://novaapi.shriramgi.com/NovaWS/novaServices/WebAggregator.svc/RestService/getPaymentStatus';
        // $url = 'http://novaapiuat.shriramgi.com/UATNovaWS/novaServices/WebAggregator.svc/RestService/getPaymentStatus';

        $curl = curl_init();
        $postdata = json_encode([
            'ProposalNo' => $nProposalNo,
            'QuoteID' => $nQuoteId
        ]);
        // dd($postdata);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
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
                'Cookie: ASP.NET_SessionId=mlurt03cufpeh4tciehzhaun'
            ),
        ));
        $response = curl_exec($curl);
        // dd($response);
        curl_close($curl);
        echo $response;
    }
}
