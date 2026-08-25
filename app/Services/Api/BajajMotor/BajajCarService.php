<?php
namespace App\Services\Api\BajajMotor;

use App\Models\Bajaj\{RtoMaster, RtaMaster};
use App\Models\Bajaj\BajajPrevInsurence;
use App\Models\Shriram\{Shriram_Pincode, Shriram_planCheckout, Shriram_RTO_Master, Shriram_Prev_insurence, Shriram_Vehicle_Master};
use App\Models\{Master_Vehicle_Data as DataModel, MasterAPI, User, MotorJourney, MasterVendor, VendorMotor, MasterMotor, UserMotorDescription, Vehicle_Info, DigiPayment};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Cache};
// use App\Http\Controllers\Api\front\motor\Vendor\shriram\Car\ShriramCarController;

class BajajCarService
{
    private static $Id = 'webservice.pos@digibima.com';
    private static $vehicletypecode = '22';
    private static $password = 'Newpas12';
    private static $deptcode = '18';

    public static function genRandomNumber()
    {
        $rand = rand(1111, 9999);
        return $rand;
    }

    public static function generatePrivateCarQuote($userId)
    {
        try {
            // $userId = '142';
            // $userId = $request->userid;
            $user = User::find($userId);
            $today = Carbon::today();

            // $cachepolicyExpiry = 'cache_policyExpiry_' . $userId;

            $oJourneyData = MotorJourney::where('userid', $userId)
                ->where('is_car', '1')
                ->where('vid', getconstant('MOTOR.BAJAJMOTOR.KEY'))
                ->first();

            $aData = DataModel::where('userid', $userId)->first();
            $nPlanType = $aData->car_plan_type;

            $aCardata = json_decode($aData->knowcar_reg_details, true) ?? [];

            $cachebikePolicyexp = 'cache_bikepolicyexp_' . $userId;
            // $PAforUnnamedamount = json_decode($aData->caraddonvalue, true);
            // $PAforUnnamedamount = !empty($PAforUnnamedamount) ? $PAforUnnamedamount : "0";
            // $aAccessories = json_decode($aData->accessories, true);
            // $dRegDate = $aData->knowcar_reg_details ? json_decode($aData->knowcar_reg_details, true)['carregdate'] : date('d-m-Y');
            // $regDate = \DateTime::createFromFormat('d-m-Y', $dRegDate);

            // $aResult = [];
            // if (!empty($aAccessories)) {
            //     foreach ($aAccessories as $item) {
            //         $aResult[$item['type']] = $item['amount'];

            //     }
            // }

            // return $sState;

            $cachemotortype = 'cache_motortype_' . $userId;

            if (GetCache($cachemotortype) == 'newcar') {
                $regno = 'NEW';
                $policyType = getconstant('MOTOR.BAJAJMOTOR.POLICYTYPE.NB');
                $sPrePolicyType = getconstant('MOTOR.BAJAJMOTOR.PRODUCTCODEFOURWHEELER.NEWBUSSINESS');
                $aNewCardata = json_decode($aData->newcar_reg_details, true);
                $year = $aNewCardata['brandyear'];
                $PolicyFromDate = $today->addDay()->format('d-M-Y');
                $PolicyToDate = $today->addYears(3)->subDay()->format('d-M-Y');
            }
            // dd($aCardata);
            if (GetCache($cachemotortype) == 'knowcar') {
                $policyType = getconstant('MOTOR.BAJAJMOTOR.POLICYTYPE.OTHER');
                $regno = $aData->carnumber;
                if ($aCardata['prepolitype'] == 'odonly') {
                    $sPrePolicyType = getconstant('MOTOR.BAJAJMOTOR.PRODUCTCODEFOURWHEELER.ODONLY');
                    $cardate = $aCardata['carregdate'];
                    $year = $aCardata['brandyear'];
                    $regdate = Carbon::parse($cardate);
                    $carregdata = strtoupper($regdate->format('d-M-Y'));
                    // $sPrevexptoDate = $aCardata['odfromdate'];
                    $sPrevexpfDate = $aCardata['odtodate'];
                    $fromDate = Carbon::parse($sPrevexpfDate);
                    $EndDate = strtoupper($fromDate->format('d-M-Y'));

                    $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    $PolicyToDate = $today->addYear()->subDay()->format('d-M-Y');
                } else if ($aCardata['prepolitype'] == 'bundled') {
                    $sPrePolicyType = getconstant('MOTOR.BAJAJMOTOR.PRODUCTCODEFOURWHEELER.NEWBUSSINESS');
                    $cardate = $aCardata['carregdate'];
                    $year = $aCardata['brandyear'];
                    $regdate = Carbon::parse($cardate);
                    // $year = Carbon::today()->year;
                    // $sPrevexptoDate = $aCardata['bdfromdate'];
                    $sPrevexpfDate = $aCardata['bdtptodate'];
                    // $toDate = Carbon::parse($sPrevexptoDate);
                    $fromDate = Carbon::parse($sPrevexpfDate);
                    // $StartDate = strtoupper($toDate->format('d-M-Y'));
                    $EndDate = strtoupper($fromDate->format('d-M-Y'));

                    $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    $PolicyToDate = $today->addYear()->subDay()->format('d-M-Y');
                } elseif ($aCardata['prepolitype'] == 'comprehensive') {
                    $sPrePolicyType = getconstant('MOTOR.BAJAJMOTOR.PRODUCTCODEFOURWHEELER.COMPREHENSIVE');
                    $cardate = $aCardata['carregdate'];
                    $year = $aCardata['brandyear'];
                    // $year = Carbon::today()->year;
                    $regdate = Carbon::parse($cardate);
                    $carregdata = strtoupper($regdate->format('d-M-Y'));
                    // $sPrevexptoDate = $aCardata['compfromdate'];
                    $sPrevexpfDate = $aCardata['comptodate'];
                    // $toDate = Carbon::parse($sPrevexptoDate);
                    $fromDate = Carbon::parse($sPrevexpfDate);

                    // $StartDate = strtoupper($toDate->format('d-M-Y'));
                    $EndDate = strtoupper($fromDate->format('d-M-Y'));

                    $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    $PolicyToDate = $today->addYear()->subDay()->format('d-M-Y');
                    // dd($PolicyFromDate,$PolicyToDate);
                } elseif ($aCardata['prepolitype'] == 'tponly') {
                    $sPrePolicyType = getconstant('MOTOR.BAJAJMOTOR.PRODUCTCODEFOURWHEELER.TPONLY');
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
                    // dd($PolicyFromDate,$PolicyToDate);

                    $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    $PolicyToDate = $today->addYear()->subDay()->format('d-M-Y');
                }
            }

            if (GetCache($cachemotortype) == 'newcar') {
                $regno = 'NEW';
                $policyType = getconstant('MOTOR.BAJAJMOTOR.POLICYTYPE.NB');
                if ($nPlanType == '2') {
                    $sPrePolicyType = getconstant('MOTOR.BAJAJMOTOR.PRODUCTCODEFOURWHEELER.NEWBUSSINESS');
                    $aNewCardata = json_decode($aData->newcar_reg_details, true);
                    $year = $aNewCardata['brandyear'];
                    $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    $PolicyToDate = $today->addYears(3)->subDay()->format('d-M-Y');

                    // $PolicyFromDate = $today->format('d-m-Y');
                    // //$today = Carbon::today();
                    // $PolicyToDate = $today->addYears(3)->subDay()->format('d-m-Y');
                    // $sPolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.BUNDLED");
                    // $NilDepreciationCoverYN = in_array('101', $aAddons) ? "Y" : "N";
                    // $RSACover = in_array('102', $aAddons) ? "Y" : "N";
                    // $DailyExpRemYN = in_array('109', $aAddons) ? "Y" : "N";
                    // $KeyReplacementYN = in_array('111', $aAddons) ? "Y" : "N";
                    // $LossOfPersonBelongYN = in_array('107', $aAddons) ? "Y" : "N";
                    // $EmergencyTranHotelExpRemYN = in_array('108', $aAddons) ? "Y" : "N";
                    // $MultiCarBenefitYN = in_array('110', $aAddons) ? "Y" : "N";
                    // $Eng_Protector = in_array('104', $aAddons) ? "Y" : "N";
                    // $Consumables = in_array('103', $aAddons) ? "Y" : "N";
                    // $InvReturnYN = in_array('106', $aAddons) ? "Y" : "N";
                    // $AntiTheftYN = in_array('119', $aAddons) ? "1" : "0";
                    // $LLtoPaidDriverYN = in_array('116', $aAddons) ? "1" : "0";
                    // $NoEmpCoverLL = in_array('122', $aAddons) ? "1" : "0";
                    // $SHRIMOTORPROTECTION_YN = in_array('113', $aAddons) ? "Y" : "N";
                    // $LimitedTPPDYN = in_array('121', $aAddons) ? "1" : "0";
                    // $VoluntaryExcess = in_array('120', $aAddons) ? "1" : "0";
                    // $PAPaidDriverConductorCleaner = 1;
                    // $Geographical = in_array('117', $aAddons) ? 1 : "0";
                    // $Geographical = in_array('118', $aAddons) ? 1 : "0";
                    // $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
                }
            }

            if (GetCache($cachemotortype) == 'knowcar') {
                $policyType = getconstant('MOTOR.BAJAJMOTOR.POLICYTYPE.OTHER');
                if ($nPlanType == '1') {
                    $sPrePolicyType = getconstant('MOTOR.BAJAJMOTOR.PRODUCTCODEFOURWHEELER.ODONLY');
                    $cardate = $aCardata['carregdate'];
                    $year = $aCardata['brandyear'];
                    $regdate = Carbon::parse($cardate);
                    // $carregdata = strtoupper($regdate->format('d-M-Y'));
                    // // $sPrevexptoDate = $aCardata['odfromdate'];
                    // $sPrevexpfDate = $aCardata['odtodate'];
                    // $fromDate = Carbon::parse($sPrevexpfDate);
                    // $EndDate = strtoupper($fromDate->format('d-M-Y'));

                    // $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    // $PolicyToDate = $today->addYear()->subDay()->format('d-M-Y');
                    // $NilDepreciationCoverYN = in_array('101', $aAddons) ? "Y" : "N";
                    // $RSACover = in_array('102', $aAddons) ? "Y" : "N";
                    // $DailyExpRemYN = in_array('109', $aAddons) ? "Y" : "N";
                    // $KeyReplacementYN = in_array('111', $aAddons) ? "Y" : "N";
                    // $LossOfPersonBelongYN = in_array('107', $aAddons) ? "Y" : "N";
                    // $Eng_Protector = in_array('104', $aAddons) ? "Y" : "N";
                    // $Consumables = in_array('103', $aAddons) ? "Y" : "N";
                    // $InvReturnYN = in_array('106', $aAddons) ? "Y" : "N";
                    // $AntiTheftYN = in_array('119', $aAddons) ? "1" : "0";
                    // $LLtoPaidDriverYN = in_array('116', $aAddons) ? "1" : "0";
                    // $NoEmpCoverLL = in_array('122', $aAddons) ? "1" : "0";
                    // $SHRIMOTORPROTECTION_YN = in_array('113', $aAddons) ? "Y" : "N";
                    // $LimitedTPPDYN = in_array('121', $aAddons) ? "1" : "0";
                    // $VoluntaryExcess = in_array('120', $aAddons) ? "1" : "0";
                    // $PAPaidDriverConductorCleaner = 1;
                    // $Geographical = in_array('118', $aAddons) ? 1 : "0";
                    // $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
                }
                if ($nPlanType == '2') {
                    $sPrePolicyType = getconstant('MOTOR.BAJAJMOTOR.PRODUCTCODEFOURWHEELER.COMPREHENSIVE');
                    $cardate = $aCardata['carregdate'];
                    $year = $aCardata['brandyear'];
                    // $year = Carbon::today()->year;
                    $regdate = Carbon::parse($cardate);
                    // $carregdata = strtoupper($regdate->format('d-M-Y'));
                    // // $sPrevexptoDate = $aCardata['compfromdate'];
                    // $sPrevexpfDate = $aCardata['comptodate'];
                    // // $toDate = Carbon::parse($sPrevexptoDate);
                    // $fromDate = Carbon::parse($sPrevexpfDate);

                    // $StartDate = strtoupper($toDate->format('d-M-Y'));
                    $EndDate = strtoupper($fromDate->format('d-M-Y'));

                    // $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    // $PolicyToDate = $today->addYear()->subDay()->format('d-M-Y');
                    // $RSACover = in_array('102', $aAddons) ? "Y" : "N";
                    // $DailyExpRemYN = in_array('109', $aAddons) ? "Y" : "N";
                    // $KeyReplacementYN = in_array('111', $aAddons) ? "Y" : "N";
                    // $LossOfPersonBelongYN = in_array('107', $aAddons) ? "Y" : "N";
                    // $Eng_Protector = in_array('104', $aAddons) ? "Y" : "N";
                    // $Consumables = in_array('103', $aAddons) ? "Y" : "N";
                    // $InvReturnYN = in_array('106', $aAddons) ? "Y" : "N";
                    // $AntiTheftYN = in_array('119', $aAddons) ? "1" : "0";
                    // $LLtoPaidDriverYN = in_array('116', $aAddons) ? "1" : "0";
                    // $NoEmpCoverLL = in_array('122', $aAddons) ? "1" : "0";
                    // $SHRIMOTORPROTECTION_YN = in_array('113', $aAddons) ? "Y" : "N";
                    // $LimitedTPPDYN = in_array('121', $aAddons) ? "1" : "0";
                    // $VoluntaryExcess = in_array('120', $aAddons) ? "1" : "0";
                    // $PAPaidDriverConductorCleaner = 1;
                    // $Geographical = in_array('117', $aAddons) ? 1 : "0";
                    // $Geographical = in_array('118', $aAddons) ? 1 : "0";
                    // $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
                }
            }

            // return $PolicyFromDate;

            $claim = array_key_exists('bonus-button', $aCardata) ? $aCardata['bonus-button'] : '0';

            $regdata = $aData->carnumber;
            $pincode = $user->pincode;
            $regnumber = substr($regno, 0, 4);

            $rto = RtoMaster::where('rtacode', $regnumber)->first();

            // if (!$state) {
            //     return ['status' => '0', 'message' => 'Pincode not available for this service.'];
            // }

            $cacheaddon = RedisGet('bajajflag_car:' . $userId);
            $addons = !empty($cacheaddon) ? $cacheaddon : '';

            // return $addon;

            $sState = $rto->city;
            $state = $rto->state;
            $getstate = RtaMaster::where('state', $state)->first();
            $zone = $getstate->zone;
            $password = self::$password;
            $Id = self::$Id;
            $vehicletypecode = self::$vehicletypecode;
            $deptcode = self::$deptcode;

            $post = [
                'userid' => $Id,
                'password' => $password,
                'vehiclecode' => '38162408',
                'city' => $sState,
                'weomotpolicyin' => [
                    'contractid' => '0',
                    'poltype' => $policyType,  // "1",
                    'product4digitcode' => $sPrePolicyType,
                    'deptcode' => $deptcode,
                    'branchcode' => '1149',
                    'termstartdate' => $PolicyFromDate,  // "17-Nov-2025",
                    'termenddate' => $PolicyToDate,  // "16-Nov-2026",
                    'tpfintype' => '0',
                    'hypo' => '',  // "State Bank of India",
                    'vehicletypecode' => $vehicletypecode,
                    'vehicletype' => 'PRIVATE CAR',
                    'miscvehtype' => '0',
                    'vehiclemakecode' => '110',
                    'vehiclemake' => 'MARUTI',
                    'vehiclemodelcode' => '39',
                    'vehiclemodel' => 'SWIFT DZIRE',
                    'vehiclesubtypecode' => '8',
                    'vehiclesubtype' => 'LXI',
                    'fuel' => 'P',
                    'zone' => $zone,  // "A",
                    'engineno' => '',  // "TYHGUYG897657896456",
                    'chassisno' => '',  // "LOKIJUHYGT6789876",
                    'registrationno' => $regno ?? 'NEW',  // "MH02",
                    'registrationdate' => $carregdata ?? '',
                    'registrationlocation' => $state,
                    'regilocother' => $state,
                    'carryingcapacity' => '4',
                    'cubiccapacity' => '1298',
                    'yearmanf' => $year,
                    'color' => '',  // "RED",
                    'vehicleidv' => '0',  // $caridv ?? "0",
                    'ncb' => $claim ?? '0',
                    'addloading' => '0',
                    'addloadingon' => '0',
                    'spdiscrate' => '0',
                    'elecacctotal' => '0',
                    'nonelecacctotal' => '0',
                    'prvpolicyref' => '',
                    'prvexpirydate' => $EndDate ?? '',
                    'prvinscompany' => '0',
                    'prvncb' => '0',
                    'prvclaimstatus' => '0',
                    'automembership' => '',  // "dfdffdf",
                    'partnertype' => 'P'
                ],
                'accessorieslist' => [
                    [
                        'contractid' => '0',
                        'acccategorycode' => '0',
                        'acctypecode' => '0',
                        'accmake' => '0',
                        'accmodel' => '0',
                        'acciev' => '0',
                        'acccount' => '0'
                    ]
                ],
                'paddoncoverlist' => [
                    [
                        'paramdesc' => '',
                        'paramref' => ''
                    ]
                ],
                'motextracover' => [
                    'geogextn' => '',
                    'noofpersonspa' => '0',
                    'suminsuredpa' => '0',
                    'suminsuredtotalnamedpa' => '0',
                    'cngvalue' => '0',
                    'noofemployeeslle' => '0',
                    'noofpersonsllo' => '0',
                    'fibreglassvalue' => '0',
                    'sidecarvalue' => '0',
                    'nooftrailers' => '0',
                    'totaltrailervalue' => '0',
                    'voluntaryexcess' => '0',
                    'covernoteno' => '',
                    'covernotedate' => '',
                    'subimdcode' => '',
                    'extrafield1' => '',
                    'extrafield2' => '',
                    'extrafield3' => ''
                ],
                'questlist' => [
                    [
                        'questionref' => '',
                        'contractid' => '',
                        'questionval' => ''
                    ]
                ],
                'detariffobj' => [
                    'vehpurchasetype' => '',
                    'vehpurchasedate' => '',
                    'monthofmfg' => '',
                    'registrationauth' => '',
                    'bodytype' => '',
                    'goodstranstype' => '',
                    'natureofgoods' => '',
                    'othergoodsfrequency' => '',
                    'permittype' => '',
                    'roadtype' => '',
                    'vehdrivenby' => '',
                    'driverexperience' => '',
                    'clmhistcode' => '',
                    'incurredclmexpcode' => '',
                    'driverqualificationcode' => '',
                    'tacmakecode' => '',
                    'extcol1' => '',
                    'extcol2' => '',
                    'extcol3' => '',
                    'extcol4' => '',
                    'extcol5' => '',
                    'extcol6' => '',
                    'extcol7' => '',
                    'extcol8' => '',  // "MCPA",
                    'extcol9' => '',
                    'extcol10' => $addons,
                    'extcol11' => '',
                    'extcol12' => '',
                    'extcol13' => '',
                    'extcol14' => '',
                    'extcol15' => '',
                    'extcol16' => '',
                    'extcol17' => '',
                    'extcol18' => '',
                    'extcol19' => '',
                    'extcol20' => 'http://https://uat.digibima.com/motor/car/vendor/bajaj/payment/thankyou?',
                    'extcol21' => '',
                    'extcol22' => '',
                    'extcol23' => '',
                    'extcol24' => '',  // "1",
                    'extcol25' => '',
                    'extcol26' => '',
                    'extcol27' => '',
                    'extcol28' => '',
                    'extcol29' => '',
                    'extcol30' => '',
                    'extcol31' => '',
                    'extcol32' => '',
                    'extcol33' => '',
                    'extcol34' => '',
                    'extcol35' => '',
                    'extcol36' => '',
                    'extcol37' => '',
                    'extcol38' => '',
                    'extcol39' => '',
                    'extcol40' => ''
                ],
                'transactionid' => '0',
                'transactiontype' => 'MOTOR_WEBSERVICE',
                'contactno' => ''
            ];
            // return $post;

            $postJson = json_encode($post);
            SaveFile($postJson, 'bajaj_motor_quote_request');
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
            SaveFile($response, 'bajaj_motor_quote_response');
            curl_close($curl);
            return $response;
        } catch (\Exception $e) {
            return ErrMessage($e);
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

    public static function privateCarProposal(Request $request)
    {
        try {
            $userId = $request->userid;
            $user = User::find($userId);
            $AuthUser = $user->toArray();
            $today = Carbon::today();
            $aData = DataModel::where('userid', $userId)->first();
            $nPlanType = $aData->car_plan_type;
            // $oJourneyData = MotorJourney::where('userid', $userId)->where('is_car', '1')->first();
            $oJourneyData = MotorJourney::where('userid', $userId)
                ->where('is_car', '1')
                ->where('vid', getconstant('MOTOR.BAJAJMOTOR.KEY'))
                ->first();

            $prevPolicydata = json_decode($oJourneyData->pre_policy_details, true);
            $HypothData = json_decode($oJourneyData->bank_details, true);
            $aCardata = json_decode($aData->knowcar_reg_details, true);
            $aNewCardata = json_decode($aData->newcar_reg_details, true);
            $aAccessories = json_decode($aData->accessories, true);
            // $cachepolicyExpiry = 'cache_policyExpiry_' . $userId;
            // $aResult = [];
            // if (!empty($aAccessories)) {
            //     foreach ($aAccessories as $item) {
            //         $aResult[$item['type']] = $item['amount'];

            //     }
            // }
            // $pAcoverReason = "";
            // $PAcover = "";
            $PAforUnnamedamount = json_decode($aData->caraddonvalue, true);
            $PAforUnnamedamount = !empty($PAforUnnamedamount) ? $PAforUnnamedamount : '0';

            $sRegNumber = $aData->carnumber;
            $cachemotortype = 'cache_motortype_' . $userId;

            if (GetCache($cachemotortype) == 'newcar') {
                $regno = 'NEW';
                $policyType = getconstant('MOTOR.BAJAJMOTOR.POLICYTYPE.NB');
                $sPrePolicyType = getconstant('MOTOR.BAJAJMOTOR.PRODUCTCODEFOURWHEELER.NEWBUSSINESS');
                $year = $aNewCardata['brandyear'];
                $PolicyFromDate = $today->addDay()->format('d-M-Y');
                $PolicyToDate = $today->addYears(3)->subDay()->format('d-M-Y');
            }

            if (GetCache($cachemotortype) == 'knowcar') {
                $policyType = getconstant('MOTOR.BAJAJMOTOR.POLICYTYPE.OTHER');
                $regno = $aData->carnumber;
                if ($aCardata['prepolitype'] == 'odonly') {
                    $sPrePolicyType = getconstant('MOTOR.BAJAJMOTOR.PRODUCTCODEFOURWHEELER.ODONLY');
                    $cardate = $aCardata['carregdate'];
                    $year = $aCardata['brandyear'];
                    $regdate = Carbon::parse($cardate);
                    $carregdata = strtoupper($regdate->format('d-M-Y'));
                    $sPrevexpfDate = $aCardata['odtodate'];
                    $fromDate = Carbon::parse($sPrevexpfDate);
                    $EndDate = strtoupper($fromDate->format('d-M-Y'));

                    $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    $PolicyToDate = $today->addYear()->subDay()->format('d-M-Y');
                    $prevInsuranceId = $prevPolicydata['prevInsuranceId'];
                    $policynumber = $prevPolicydata['policynumber'];
                    $InsurCmpny = BajajPrevInsurence::where('id', $prevInsuranceId)->value('company_code');
                } else if ($aCardata['prepolitype'] == 'bundled') {
                    $sPrePolicyType = getconstant('MOTOR.BAJAJMOTOR.PRODUCTCODEFOURWHEELER.NEWBUSSINESS');
                    $cardate = $aCardata['carregdate'];
                    $year = $aCardata['brandyear'];
                    $regdate = Carbon::parse($cardate);
                    $sPrevexpfDate = $aCardata['bdtptodate'];
                    $fromDate = Carbon::parse($sPrevexpfDate);
                    $EndDate = strtoupper($fromDate->format('d-M-Y'));

                    $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    $PolicyToDate = $today->addYear()->subDay()->format('d-M-Y');
                } elseif ($aCardata['prepolitype'] == 'comprehensive') {
                    $sPrePolicyType = getconstant('MOTOR.BAJAJMOTOR.PRODUCTCODEFOURWHEELER.COMPREHENSIVE');
                    $cardate = $aCardata['carregdate'];
                    $year = $aCardata['brandyear'];
                    $regdate = Carbon::parse($cardate);
                    $carregdata = strtoupper($regdate->format('d-M-Y'));
                    $sPrevexpfDate = $aCardata['comptodate'];
                    $fromDate = Carbon::parse($sPrevexpfDate);
                    $EndDate = strtoupper($fromDate->format('d-M-Y'));

                    $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    $PolicyToDate = $today->addYear()->subDay()->format('d-M-Y');

                    $prevInsuranceId = $prevPolicydata['prevInsuranceId'];
                    $policynumber = $prevPolicydata['policynumber'];
                    $InsurCmpny = BajajPrevInsurence::where('id', $prevInsuranceId)->value('company_code');
                } elseif ($aCardata['prepolitype'] == 'tponly') {
                    $sPrePolicyType = getconstant('MOTOR.BAJAJMOTOR.PRODUCTCODEFOURWHEELER.TPONLY');
                    $cardate = $aCardata['carregdate'];
                    $year = $aCardata['brandyear'];
                    $regdate = Carbon::parse($cardate);
                    $carregdata = strtoupper($regdate->format('d-M-Y'));
                    $sPrevexpfDate = $aCardata['tptodate'];
                    $fromDate = Carbon::parse($sPrevexpfDate);
                    $EndDate = strtoupper($fromDate->format('d-M-Y'));
                    $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    $PolicyToDate = $today->addYear()->subDay()->format('d-M-Y');

                    $prevInsuranceId = $prevPolicydata['prevInsuranceId'];
                    $policynumber = $prevPolicydata['policynumber'];
                    $InsurCmpny = BajajPrevInsurence::where('id', $prevInsuranceId)->value('company_code');
                }
            }

            if (GetCache($cachemotortype) == 'newcar') {
                $regno = 'NEW';
                $policyType = getconstant('MOTOR.BAJAJMOTOR.POLICYTYPE.NB');
                if ($nPlanType == '2') {
                    $sPrePolicyType = getconstant('MOTOR.BAJAJMOTOR.PRODUCTCODEFOURWHEELER.NEWBUSSINESS');
                    $aNewCardata = json_decode($aData->newcar_reg_details, true);
                    $year = $aNewCardata['brandyear'];
                    $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    $PolicyToDate = $today->addYears(3)->subDay()->format('d-M-Y');

                    // $PolicyFromDate = $today->format('d-m-Y');
                    // //$today = Carbon::today();
                    // $PolicyToDate = $today->addYears(3)->subDay()->format('d-m-Y');
                    // $sPolicyType = getconstant("MOTOR.SHRIRAM.POLICYTYPE.BUNDLED");
                    // $NilDepreciationCoverYN = in_array('101', $aAddons) ? "Y" : "N";
                    // $RSACover = in_array('102', $aAddons) ? "Y" : "N";
                    // $DailyExpRemYN = in_array('109', $aAddons) ? "Y" : "N";
                    // $KeyReplacementYN = in_array('111', $aAddons) ? "Y" : "N";
                    // $LossOfPersonBelongYN = in_array('107', $aAddons) ? "Y" : "N";
                    // $EmergencyTranHotelExpRemYN = in_array('108', $aAddons) ? "Y" : "N";
                    // $MultiCarBenefitYN = in_array('110', $aAddons) ? "Y" : "N";
                    // $Eng_Protector = in_array('104', $aAddons) ? "Y" : "N";
                    // $Consumables = in_array('103', $aAddons) ? "Y" : "N";
                    // $InvReturnYN = in_array('106', $aAddons) ? "Y" : "N";
                    // $AntiTheftYN = in_array('119', $aAddons) ? "1" : "0";
                    // $LLtoPaidDriverYN = in_array('116', $aAddons) ? "1" : "0";
                    // $NoEmpCoverLL = in_array('122', $aAddons) ? "1" : "0";
                    // $SHRIMOTORPROTECTION_YN = in_array('113', $aAddons) ? "Y" : "N";
                    // $LimitedTPPDYN = in_array('121', $aAddons) ? "1" : "0";
                    // $VoluntaryExcess = in_array('120', $aAddons) ? "1" : "0";
                    // $PAPaidDriverConductorCleaner = 1;
                    // $Geographical = in_array('117', $aAddons) ? 1 : "0";
                    // $Geographical = in_array('118', $aAddons) ? 1 : "0";
                    // $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
                }
            }

            if (GetCache($cachemotortype) == 'knowcar') {
                $policyType = getconstant('MOTOR.BAJAJMOTOR.POLICYTYPE.OTHER');
                if ($nPlanType == '1') {
                    $sPrePolicyType = getconstant('MOTOR.BAJAJMOTOR.PRODUCTCODEFOURWHEELER.ODONLY');
                    $cardate = $aCardata['carregdate'];
                    $year = $aCardata['brandyear'];
                    $regdate = Carbon::parse($cardate);
                    $carregdata = strtoupper($regdate->format('d-M-Y'));
                    // $sPrevexptoDate = $aCardata['odfromdate'];
                    $sPrevexpfDate = $aCardata['odtodate'];
                    $fromDate = Carbon::parse($sPrevexpfDate);
                    $EndDate = strtoupper($fromDate->format('d-M-Y'));

                    // $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    // $PolicyToDate = $today->addYear()->subDay()->format('d-M-Y');
                    // $NilDepreciationCoverYN = in_array('101', $aAddons) ? "Y" : "N";
                    // $RSACover = in_array('102', $aAddons) ? "Y" : "N";
                    // $DailyExpRemYN = in_array('109', $aAddons) ? "Y" : "N";
                    // $KeyReplacementYN = in_array('111', $aAddons) ? "Y" : "N";
                    // $LossOfPersonBelongYN = in_array('107', $aAddons) ? "Y" : "N";
                    // $Eng_Protector = in_array('104', $aAddons) ? "Y" : "N";
                    // $Consumables = in_array('103', $aAddons) ? "Y" : "N";
                    // $InvReturnYN = in_array('106', $aAddons) ? "Y" : "N";
                    // $AntiTheftYN = in_array('119', $aAddons) ? "1" : "0";
                    // $LLtoPaidDriverYN = in_array('116', $aAddons) ? "1" : "0";
                    // $NoEmpCoverLL = in_array('122', $aAddons) ? "1" : "0";
                    // $SHRIMOTORPROTECTION_YN = in_array('113', $aAddons) ? "Y" : "N";
                    // $LimitedTPPDYN = in_array('121', $aAddons) ? "1" : "0";
                    // $VoluntaryExcess = in_array('120', $aAddons) ? "1" : "0";
                    // $PAPaidDriverConductorCleaner = 1;
                    // $Geographical = in_array('118', $aAddons) ? 1 : "0";
                    // $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
                }
                if ($nPlanType == '2') {
                    $sPrePolicyType = getconstant('MOTOR.BAJAJMOTOR.PRODUCTCODEFOURWHEELER.COMPREHENSIVE');
                    $cardate = $aCardata['carregdate'];
                    $year = $aCardata['brandyear'];
                    // $year = Carbon::today()->year;
                    $regdate = Carbon::parse($cardate);
                    $carregdata = strtoupper($regdate->format('d-M-Y'));
                    // $sPrevexptoDate = $aCardata['compfromdate'];
                    $sPrevexpfDate = $aCardata['comptodate'];
                    // $toDate = Carbon::parse($sPrevexptoDate);
                    $fromDate = Carbon::parse($sPrevexpfDate);

                    // $StartDate = strtoupper($toDate->format('d-M-Y'));
                    $EndDate = strtoupper($fromDate->format('d-M-Y'));

                    // $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    // $PolicyToDate = $today->addYear()->subDay()->format('d-M-Y');
                    // $RSACover = in_array('102', $aAddons) ? "Y" : "N";
                    // $DailyExpRemYN = in_array('109', $aAddons) ? "Y" : "N";
                    // $KeyReplacementYN = in_array('111', $aAddons) ? "Y" : "N";
                    // $LossOfPersonBelongYN = in_array('107', $aAddons) ? "Y" : "N";
                    // $Eng_Protector = in_array('104', $aAddons) ? "Y" : "N";
                    // $Consumables = in_array('103', $aAddons) ? "Y" : "N";
                    // $InvReturnYN = in_array('106', $aAddons) ? "Y" : "N";
                    // $AntiTheftYN = in_array('119', $aAddons) ? "1" : "0";
                    // $LLtoPaidDriverYN = in_array('116', $aAddons) ? "1" : "0";
                    // $NoEmpCoverLL = in_array('122', $aAddons) ? "1" : "0";
                    // $SHRIMOTORPROTECTION_YN = in_array('113', $aAddons) ? "Y" : "N";
                    // $LimitedTPPDYN = in_array('121', $aAddons) ? "1" : "0";
                    // $VoluntaryExcess = in_array('120', $aAddons) ? "1" : "0";
                    // $PAPaidDriverConductorCleaner = 1;
                    // $Geographical = in_array('117', $aAddons) ? 1 : "0";
                    // $Geographical = in_array('118', $aAddons) ? 1 : "0";
                    // $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
                }
            }

            $dob = !empty($oJourneyData->dob)
                ? date('d-M-Y', strtotime($oJourneyData->dob))
                : (!empty($user->dob)
                    ? date('d-M-Y', strtotime($user->dob))
                    : date('d-M-Y', strtotime('2000-04-29')));

            $cacheTransicationId = RedisGet('transactionid_car:' . $userId);
            $TransactionId = $cacheTransicationId;
            $password = self::$password;
            $Id = self::$Id;
            $vehicletypecode = self::$vehicletypecode;
            $deptcode = self::$deptcode;
            $aNominee = $oJourneyData->nominee_details ? jdec($oJourneyData->nominee_details) : [];
            $permanentAddress = json_decode($oJourneyData->permanent_address, true) ?? [];
            $pincode = $permanentAddress['pincode'];

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

            $cacheCarIdv = RedisGet('bajajcar_idv:' . $userId);
            $caridv = !empty($cacheCarIdv) ? $cacheCarIdv : '0';
            $randomValue = self::genRandomNumber();
            $EngineNo = 'GDFG59D4GD6546D' . $randomValue;
            $ChassisNo = 'GDF45GDFGD4G56D' . $randomValue;
            $claim = array_key_exists('bonus-button', $aCardata) ? $aCardata['bonus-button'] : '0';

            $cacheaddon = RedisGet('bajajflag_car:' . $userId);
            $addons = !empty($cacheaddon) ? $cacheaddon : '';

            $nameParts = self::splitFullName($AuthUser['name'] ?? '');

            $post = [
                'userid' => $Id,
                'password' => $password,
                'transactionid' => $TransactionId,
                'rcptlist' => [],
                'custdetails' => [
                    'parttempid' => '',
                    'firstname' => $nameParts['firstname'] ?? '',
                    'middlename' => $nameParts['middlename'] ?? '',
                    'surname' => $nameParts['surname'] ?? '',
                    'addline1' => $permanentAddress['address1'] ?? 'PLOT NO H5 ADC FLAT NO 9',
                    'addline2' => $permanentAddress['address2'] ?? 'UDAY GARDEN RESIDENCY',
                    'addline3' => $permanentAddress['landmark'] ?? 'Pune',
                    'addline5' => $sState ?? 'MAHARASHTRA',
                    'pincode' => $pincode ?? $permanentAddress['pincode'],  // "411001",
                    'email' => $AuthUser['email'] ?? 'pankaj@bajajallianz.co.in',
                    'telephone1' => '',
                    'telephone2' => '',
                    'mobile' => $AuthUser['mobile'] ?? '9637384353',
                    'delivaryoption' => '',
                    'poladdline1' => '',
                    'poladdline2' => '',
                    'poladdline3' => '',
                    'poladdline5' => '',
                    'polpincode' => '',
                    'password' => 'newpas12',
                    'cptype' => 'P',
                    'profession' => '',
                    'dateofbirth' => $dob ?? '12-SEP-1980',
                    'availabletime' => '',
                    'institutionname' => null,
                    'existingyn' => 'N',
                    'loggedin' => '',
                    'mobilealerts' => '',
                    'emailalerts' => '',
                    'title' => $oJourneyData->gender ?? '',
                    'partid' => '',
                    'status1' => '',
                    'status2' => '',
                    'status3' => ''
                ],
                'weomotpolicyin' => [
                    'contractid' => '0',
                    'poltype' => $policyType,  // "1",
                    'product4digitcode' => $sPrePolicyType,  // "1826",
                    'deptcode' => $deptcode,
                    'branchcode' => '1933',
                    'termstartdate' => $PolicyFromDate,
                    'termenddate' => $PolicyToDate,
                    'tpfintype' => !empty($hypo) ? 1 : 0,  // "1",
                    'hypo' => $hypo ?? '',
                    'vehicletypecode' => '22',
                    'vehicletype' => 'Private Car',
                    'miscvehtype' => '0',
                    'vehiclemakecode' => '107',
                    'vehiclemake' => 'HONDA',
                    'vehiclemodelcode' => '28',
                    'vehiclemodel' => 'CITY',
                    'vehiclesubtypecode' => '2',
                    'vehiclesubtype' => '1.3 EXI',
                    'fuel' => 'P',
                    'zone' => $zone,  // "A",
                    'engineno' => $EngineNo ?? '',  // "RJ12FB7889",
                    'chassisno' => $ChassisNo ?? '',  // "RJ12FB56783478889",
                    'registrationno' => $regno ?? '',  // "NEW",
                    'registrationdate' => $carregdata ?? '',  // "09-Nov-2025",
                    'registrationlocation' => $state,
                    'regilocother' => $state,
                    'carryingcapacity' => '5',
                    'cubiccapacity' => '1343',
                    'yearmanf' => $year,  // "2022",
                    'color' => '',
                    'vehicleidv' => $caridv,  // "375803",
                    'ncb' => $claim ?? '0',  // "20",
                    'addloading' => '0',
                    'addloadingon' => '0',
                    'spdiscrate' => '0',
                    'elecacctotal' => '0',
                    'nonelecacctotal' => '0',
                    'prvpolicyref' => $policynumber ?? '23334343434344343',  // "23334343434344343",
                    'prvexpirydate' => $EndDate,  // "26-Aug-2022",
                    'prvinscompany' => $InsurCmpny ?? '',
                    'prvncb' => !empty($claim) ? 1 : 0,
                    'prvclaimstatus' => '0',
                    'automembership' => '0',
                    'partnertype' => 'P'
                ],
                'accessorieslist' => [
                    [
                        'contractid' => '0',
                        'acccategorycode' => '0',
                        'acctypecode' => '0',
                        'accmake' => '',
                        'accmodel' => '',
                        'acciev' => '0',
                        'acccount' => '0'
                    ]
                ],
                'paddoncoverlist' => [
                    [
                        'paramdesc' => null,
                        'paramref' => null
                    ]
                ],
                'motextracover' => [
                    'geogextn' => '0',
                    'noofpersonspa' => null,  // "", //"5",
                    'suminsuredpa' => '0',
                    'suminsuredtotalnamedpa' => null,
                    'cngvalue' => '0',
                    'noofemployeeslle' => '0',
                    'noofpersonsllo' => '0',
                    'fibreglassvalue' => '0',
                    'sidecarvalue' => '0',
                    'nooftrailers' => '0',
                    'totaltrailervalue' => '0',
                    'voluntaryexcess' => '0',
                    'covernoteno' => '',
                    'covernotedate' => '',
                    'subimdcode' => '',
                    'extrafield1' => '',
                    'extrafield2' => '',
                    'extrafield3' => ''
                ],
                'premiumdetails' => [
                    'ncbamt' => '0',
                    'addloadprem' => '0',
                    'totalodpremium' => '0',
                    'totalactpremium' => '0',
                    'totalnetpremium' => '0',
                    'totalpremium' => '0',
                    'netpremium' => '0',
                    'finalpremium' => '0',
                    'spdisc' => '0',
                    'servicetax' => '0',
                    'stampduty' => '0',
                    'collpremium' => '0',
                    'imtout' => '',
                    'totaliev' => '0'
                ],
                'premiumsummerylist' => [
                    [
                        'paramdesc' => '0',
                        'paramref' => '0',
                        'paramtype' => '0',
                        'od' => '0',
                        'act' => '0',
                        'net' => '0'
                    ]
                ],
                'questlist' => [
                    [
                        'questionref' => '',
                        'contractid' => '',
                        'questionval' => ''
                    ]
                ],
                'detariffobj' => [
                    'vehpurchasetype' => '',
                    'vehpurchasedate' => '',
                    'monthofmfg' => '',
                    'registrationauth' => '',
                    'bodytype' => '',
                    'goodstranstype' => '',
                    'natureofgoods' => '',
                    'othergoodsfrequency' => '',
                    'permittype' => '',
                    'roadtype' => '',
                    'vehdrivenby' => '',
                    'driverexperience' => '',
                    'clmhistcode' => '',
                    'incurredclmexpcode' => '',
                    'driverqualificationcode' => '',
                    'tacmakecode' => '',
                    'extcol1' => '',
                    'extcol2' => '',
                    'extcol3' => '',
                    'extcol4' => '',
                    'extcol5' => '',
                    'extcol6' => '',
                    'extcol7' => '',
                    'extcol8' => '',  // "ACPA",
                    'extcol9' => '',
                    'extcol10' => $addons,
                    'extcol11' => '',
                    'extcol12' => '',
                    'extcol13' => '',
                    'extcol14' => '',
                    'extcol15' => '',
                    'extcol16' => '',
                    'extcol17' => '',
                    'extcol18' => '',
                    'extcol19' => '',
                    'extcol20' => 'http://192.168.29.219:3000/motor/car/vendor/bajaj/payment/thankyou?',  // "http=>//beta.ssginsurancebrokers.com/Car_IC/CarProposal/TransactionStatus?",
                    'extcol21' => '',
                    'extcol22' => '',
                    'extcol23' => '',
                    'extcol24' => '',  // "1",
                    'extcol25' => '',  // "1",
                    'extcol26' => '',  // "3142657309616",
                    'extcol27' => '',
                    'extcol28' => '',
                    'extcol29' => '',
                    'extcol30' => '',
                    'extcol31' => '',
                    'extcol32' => '',
                    'extcol33' => '',  // "19AAATA6757C1ZS",
                    'extcol34' => '',  // "710148",
                    'extcol35' => '',
                    'extcol36' => '',
                    'extcol37' => '',
                    'extcol38' => '',  // "~ ~",
                    'extcol39' => '',
                    'extcol40' => ''
                ],
                'potherdetails' => [
                    'imdcode' => '',
                    'covernoteno' => '',
                    'leadno' => '',
                    'ccecode' => '',
                    'runnercode' => '',
                    'extra1' => '',
                    'extra2' => '',
                    'extra3' => '',
                    'extra4' => '',
                    'extra5' => ''
                ],
                'premiumpayerid' => '0',
                'paymentmode' => 'CC'
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

            curl_close($curl);
            return $response;
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }

    public static function TpgeneratePrivateCarQuote($userId)
    {
        try {
            $user = User::find($userId);
            $today = Carbon::today();

            $oJourneyData = MotorJourney::where('userid', $userId)->where('is_car', '1')->where('vid', getconstant('MOTOR.BAJAJMOTOR.KEY'))->first();

            $aData = DataModel::where('userid', $userId)->first();
            $nPlanType = $aData->car_plan_type;
            $cachemotortype = 'cache_motortype_' . $userId;
            $cachepolicyExpiry = 'cache_policyExpiry_' . $userId;

            if (GetCache($cachemotortype) == 'knowcar') {
                $aCardata = json_decode($aData->knowcar_reg_details, true) ?? [];
                $policyType = getconstant('MOTOR.BAJAJMOTOR.POLICYTYPE.OTHER');
                $regno = $aData->carnumber;
                if ($aCardata['prepolitype'] == 'tponly') {
                    $sPrePolicyType = getconstant('MOTOR.BAJAJMOTOR.PRODUCTCODEFOURWHEELER.TPONLY');
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
                    // dd($PolicyFromDate,$PolicyToDate);

                    $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    $PolicyToDate = $today->addYear()->subDay()->format('d-M-Y');
                }
            }

            if (GetCache($cachemotortype) == 'knowcar') {
                $policyType = getconstant('MOTOR.BAJAJMOTOR.POLICYTYPE.OTHER');
                if ($nPlanType == '3' && (GetCache($cachepolicyExpiry) == 'Not Expired')) {
                    $sPrePolicyType = getconstant('MOTOR.BAJAJMOTOR.PRODUCTCODEFOURWHEELER.TPONLY');
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
                    // dd($PolicyFromDate,$PolicyToDate);

                    // $LLtoPaidDriverYN = in_array('116', $aAddons) ? "1" : "0";
                    // $LimitedTPPDYN = in_array('121', $aAddons) ? "1" : "0";
                    // $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
                    // $NoEmpCoverLL = in_array('122', $aAddons) ? "1" : "0";
                    // $Geographical = in_array('118', $aAddons) ? 1 : "0";
                }

                if ($nPlanType == '3' && (GetCache($cachepolicyExpiry) == 'Expired')) {
                    $sPrePolicyType = getconstant('MOTOR.BAJAJMOTOR.PRODUCTCODEFOURWHEELER.TPONLY');
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
                    // dd($PolicyFromDate,$PolicyToDate);

                    // $PolicyFromDate = $today->format('d-m-Y');
                    // $PolicyToDate = $today->addYear()->subDay()->format('d-m-Y');
                    // $LLtoPaidDriverYN = in_array('116', $aAddons) ? "1" : "0";
                    // $LimitedTPPDYN = in_array('121', $aAddons) ? "1" : "0";
                    // $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
                    // $NoEmpCoverLL = in_array('122', $aAddons) ? "1" : "0";
                    // $Geographical = in_array('118', $aAddons) ? 1 : "0";
                }
            }

            $claim = array_key_exists('bonus-button', $aCardata) ? $aCardata['bonus-button'] : '0';

            $regdata = $aData->carnumber;
            $pincode = $user->pincode;
            $regnumber = substr($regno, 0, 4);

            $rto = RtoMaster::where('rtacode', $regnumber)->first();

            // if (!$state) {
            //     return ['status' => '0', 'message' => 'Pincode not available for this service.'];
            // }

            $cacheaddon = RedisGet('bajajflag_car:' . $userId);
            $addons = !empty($cacheaddon) ? $cacheaddon : '';

            $sState = $rto->city;
            $state = $rto->state;
            $getstate = RtaMaster::where('state', $state)->first();
            $zone = $getstate->zone;
            $password = self::$password;
            $Id = self::$Id;
            $vehicletypecode = self::$vehicletypecode;
            $deptcode = self::$deptcode;

            $post = [
                'userid' => 'webservice@digibima.com',
                'password' => 'test',
                'vehiclecode' => '38172719',
                'city' => $sState,
                'weomotpolicyin' => [
                    'contractid' => '0',
                    'poltype' => $policyType,  // "1",
                    'product4digitcode' => $sPrePolicyType,
                    'deptcode' => $deptcode,
                    'branchcode' => '9906',
                    'termstartdate' => $PolicyFromDate,  // "17-Nov-2025",
                    'termenddate' => $PolicyToDate,  // "16-Nov-2026",
                    'tpfintype' => '0',
                    'hypo' => '',  // "State Bank of India",
                    'vehicletypecode' => $vehicletypecode,
                    'vehicletype' => 'PRIVATE CAR',
                    'miscvehtype' => '0',
                    'vehiclemakecode' => '108',
                    'vehiclemake' => 'HYUNDAI',
                    'vehiclemodelcode' => '51',
                    'vehiclemodel' => 'GRAND i10',
                    'vehiclesubtypecode' => '2',
                    'vehiclesubtype' => 'MAGNA 1.2 KAPPA DUAL VTVT 5 SPEED',
                    'fuel' => 'P',
                    'zone' => $zone,  // "A",
                    'engineno' => '',  // "TYHGUYG897657896456",
                    'chassisno' => '',  // "LOKIJUHYGT6789876",
                    'registrationno' => $regno ?? 'NEW',  // "MH02",
                    'registrationdate' => $carregdata ?? '',
                    'registrationlocation' => $state,
                    'regilocother' => $state,
                    'carryingcapacity' => '5',
                    'cubiccapacity' => '1197',
                    'yearmanf' => $year,
                    'color' => '',  // "RED",
                    'vehicleidv' => '0',  // $caridv ?? "0",
                    'ncb' => $claim ?? '0',
                    'addloading' => '0',
                    'addloadingon' => '0',
                    'spdiscrate' => '0',
                    'elecacctotal' => '0',
                    'nonelecacctotal' => '0',
                    'prvpolicyref' => '',
                    'prvexpirydate' => $EndDate ?? '',
                    'prvinscompany' => '0',
                    'prvncb' => '0',
                    'prvclaimstatus' => '0',
                    'automembership' => '',  // "dfdffdf",
                    'partnertype' => 'P'
                ],
                'accessorieslist' => [
                    [
                        'contractid' => '0',
                        'acccategorycode' => '0',
                        'acctypecode' => '0',
                        'accmake' => '0',
                        'accmodel' => '0',
                        'acciev' => '0',
                        'acccount' => '0'
                    ]
                ],
                'paddoncoverlist' => [
                    [
                        'paramdesc' => '',
                        'paramref' => ''
                    ]
                ],
                'motextracover' => [
                    'geogextn' => '',
                    'noofpersonspa' => '0',
                    'suminsuredpa' => '0',
                    'suminsuredtotalnamedpa' => '0',
                    'cngvalue' => '0',
                    'noofemployeeslle' => '0',
                    'noofpersonsllo' => '0',
                    'fibreglassvalue' => '0',
                    'sidecarvalue' => '0',
                    'nooftrailers' => '0',
                    'totaltrailervalue' => '0',
                    'voluntaryexcess' => '0',
                    'covernoteno' => '',
                    'covernotedate' => '',
                    'subimdcode' => '',
                    'extrafield1' => '',
                    'extrafield2' => '',
                    'extrafield3' => ''
                ],
                'questlist' => [
                    [
                        'questionref' => '',
                        'contractid' => '',
                        'questionval' => ''
                    ]
                ],
                'detariffobj' => [
                    'vehpurchasetype' => '',
                    'vehpurchasedate' => '',
                    'monthofmfg' => '',
                    'registrationauth' => '',
                    'bodytype' => '',
                    'goodstranstype' => '',
                    'natureofgoods' => '',
                    'othergoodsfrequency' => '',
                    'permittype' => '',
                    'roadtype' => '',
                    'vehdrivenby' => '',
                    'driverexperience' => '',
                    'clmhistcode' => '',
                    'incurredclmexpcode' => '',
                    'driverqualificationcode' => '',
                    'tacmakecode' => '',
                    'extcol1' => '',
                    'extcol2' => '',
                    'extcol3' => '',
                    'extcol4' => '',
                    'extcol5' => '',
                    'extcol6' => '',
                    'extcol7' => '',
                    'extcol8' => '',  // "MCPA",
                    'extcol9' => '',
                    'extcol10' => $addons,
                    'extcol11' => '',
                    'extcol12' => '',
                    'extcol13' => '',
                    'extcol14' => '',
                    'extcol15' => '',
                    'extcol16' => '',
                    'extcol17' => '',
                    'extcol18' => '',
                    'extcol19' => '',
                    'extcol20' => '',  // "http://192.168.29.219:3000/motor/car/vendor/bajaj/payment/thankyou?",
                    'extcol21' => '',
                    'extcol22' => '',
                    'extcol23' => '',
                    'extcol24' => '',  // "1",
                    'extcol25' => '',
                    'extcol26' => '',
                    'extcol27' => '',
                    'extcol28' => '',
                    'extcol29' => '',
                    'extcol30' => '',
                    'extcol31' => '',
                    'extcol32' => '',
                    'extcol33' => '',
                    'extcol34' => '',
                    'extcol35' => '',
                    'extcol36' => '',
                    'extcol37' => '',
                    'extcol38' => '',
                    'extcol39' => '',
                    'extcol40' => ''
                ],
                'transactionid' => '0',
                'transactiontype' => 'MOTOR_WEBSERVICE',
                'contactno' => ''
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

            curl_close($curl);
            return $response;
        } catch (\Exception $e) {
            return [
                'status' => '0',
                'message' => $e->getMessage() . 'An error occurred while fetching cache data.'
            ];
        }
    }

    public static function TpprivateCarProposal(Request $request)
    {
        try {
            $userId = $request->userid;
            $user = User::find($userId);
            $AuthUser = $user->toArray();

            $today = Carbon::today();
            $aData = DataModel::where('userid', $userId)->first();
            $nPlanType = $aData->car_plan_type;
            $cachemotortype = 'cache_motortype_' . $userId;
            $cachepolicyExpiry = 'cache_policyExpiry_' . $userId;
            $oJourneyData = MotorJourney::where('userid', $userId)->where('is_car', '1')->where('vid', getconstant('MOTOR.BAJAJMOTOR.KEY'))->first();
            $prevPolicydata = json_decode($oJourneyData->pre_policy_details, true);
            $HypothData = json_decode($oJourneyData->bank_details, true);
            $aCardata = json_decode($aData->knowcar_reg_details, true);
            $aNewCardata = json_decode($aData->newcar_reg_details, true);
            $aAccessories = json_decode($aData->accessories, true);

            $PAforUnnamedamount = json_decode($aData->caraddonvalue, true);
            $PAforUnnamedamount = !empty($PAforUnnamedamount) ? $PAforUnnamedamount : '0';

            $sRegNumber = $aData->carnumber;
            $cachemotortype = 'cache_motortype_' . $userId;

            // if (GetCache($cachemotortype) == "newcar") {
            //     $regno = 'NEW';
            //     $policyType = getconstant("MOTOR.BAJAJMOTOR.POLICYTYPE.NB");
            //     $sPrePolicyType = getconstant("MOTOR.BAJAJMOTOR.PRODUCTCODETWOWHEELER.NEWBUSSINESS");
            //     $year = $aNewCardata['brandyear'];
            //     $PolicyFromDate = $today->addDay()->format('d-M-Y');
            //     $PolicyToDate = $today->addYears(3)->subDay()->format('d-M-Y');
            // }

            if (GetCache($cachemotortype) == 'knowcar') {
                $policyType = getconstant('MOTOR.BAJAJMOTOR.POLICYTYPE.OTHER');
                $regno = $aData->carnumber;
                if ($aCardata['prepolitype'] == 'tponly') {
                    $sPrePolicyType = getconstant('MOTOR.BAJAJMOTOR.PRODUCTCODEFOURWHEELER.TPONLY');
                    $cardate = $aCardata['carregdate'];
                    $year = $aCardata['brandyear'];
                    $regdate = Carbon::parse($cardate);
                    $carregdata = strtoupper($regdate->format('d-M-Y'));
                    $sPrevexpfDate = $aCardata['tptodate'];
                    $fromDate = Carbon::parse($sPrevexpfDate);
                    $EndDate = strtoupper($fromDate->format('d-M-Y'));
                    $PolicyFromDate = $today->addDay()->format('d-M-Y');
                    $PolicyToDate = $today->addYear()->subDay()->format('d-M-Y');

                    $prevInsuranceId = $prevPolicydata['prevInsuranceId'];
                    $policynumber = $prevPolicydata['policynumber'];
                    $InsurCmpny = BajajPrevInsurence::where('id', $prevInsuranceId)->value('company_code');
                }
            }

            if (GetCache($cachemotortype) == 'knowcar') {
                $policyType = getconstant('MOTOR.BAJAJMOTOR.POLICYTYPE.OTHER');
                if ($nPlanType == '3' && (GetCache($cachepolicyExpiry) == 'Not Expired')) {
                    $sPrePolicyType = getconstant('MOTOR.BAJAJMOTOR.PRODUCTCODEFOURWHEELER.TPONLY');
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
                    // dd($PolicyFromDate,$PolicyToDate);

                    // $LLtoPaidDriverYN = in_array('116', $aAddons) ? "1" : "0";
                    // $LimitedTPPDYN = in_array('121', $aAddons) ? "1" : "0";
                    // $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
                    // $NoEmpCoverLL = in_array('122', $aAddons) ? "1" : "0";
                    // $Geographical = in_array('118', $aAddons) ? 1 : "0";
                }

                if ($nPlanType == '3' && (GetCache($cachepolicyExpiry) == 'Expired')) {
                    $sPrePolicyType = getconstant('MOTOR.BAJAJMOTOR.PRODUCTCODEFOURWHEELER.TPONLY');
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
                    // dd($PolicyFromDate,$PolicyToDate);

                    // $PolicyFromDate = $today->format('d-m-Y');
                    // $PolicyToDate = $today->addYear()->subDay()->format('d-m-Y');
                    // $LLtoPaidDriverYN = in_array('116', $aAddons) ? "1" : "0";
                    // $LimitedTPPDYN = in_array('121', $aAddons) ? "1" : "0";
                    // $PAforUnnamedPassenger = in_array('115', $aAddons) ? "1" : "0";
                    // $NoEmpCoverLL = in_array('122', $aAddons) ? "1" : "0";
                    // $Geographical = in_array('118', $aAddons) ? 1 : "0";
                }
            }

            $dob = !empty($oJourneyData->dob)
                ? date('d-M-Y', strtotime($oJourneyData->dob))
                : (!empty($user->dob)
                    ? date('d-M-Y', strtotime($user->dob))
                    : date('d-M-Y', strtotime('2000-04-29')));

            $cacheTransicationId = RedisGet('transactionid_car:' . $userId);
            $TransactionId = $cacheTransicationId;
            $password = self::$password;
            $Id = self::$Id;
            $vehicletypecode = self::$vehicletypecode;
            $deptcode = self::$deptcode;
            $aNominee = $oJourneyData->nominee_details ? jdec($oJourneyData->nominee_details) : [];
            $permanentAddress = json_decode($oJourneyData->permanent_address, true) ?? [];
            $pincode = $permanentAddress['pincode'];
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

            $cacheCarIdv = RedisGet('bajajcar_idv:' . $userId);
            $caridv = !empty($cacheCarIdv) ? $cacheCarIdv : '0';
            $randomValue = self::genRandomNumber();
            $EngineNo = 'GDFG59D4GD' . $randomValue;
            $ChassisNo = 'GDF45GDFGD' . $randomValue;
            $claim = array_key_exists('bonus-button', $aCardata) ? $aCardata['bonus-button'] : '0';

            $cacheaddon = RedisGet('bajajflag_car:' . $userId);
            $addons = !empty($cacheaddon) ? $cacheaddon : '';

            $nameParts = self::splitFullName($AuthUser['name'] ?? '');

            $post = [
                'userid' => 'webservice@digibima.com',
                'password' => 'test',
                'transactionid' => $TransactionId,
                'rcptlist' => [],
                'custdetails' => [
                    'parttempid' => '',
                    'firstname' => $nameParts['firstname'] ?? '',
                    'middlename' => $nameParts['middlename'] ?? '',
                    'surname' => $nameParts['surname'] ?? '',
                    'addline1' => $permanentAddress['address1'] ?? '',
                    'addline2' => $permanentAddress['address2'] ?? '',
                    'addline3' => $permanentAddress['landmark'] ?? '',
                    'addline5' => $sState ?? '',
                    'pincode' => $pincode ?? $permanentAddress['pincode'],  // "411001",
                    'email' => $AuthUser['email'] ?? '',
                    'telephone1' => '',
                    'telephone2' => '',
                    'mobile' => $AuthUser['mobile'] ?? '',
                    'delivaryoption' => '',
                    'poladdline1' => '',
                    'poladdline2' => '',
                    'poladdline3' => '',
                    'poladdline5' => '',
                    'polpincode' => '',
                    'password' => '',
                    'cptype' => 'P',
                    'profession' => '',
                    'dateofbirth' => $dob ?? '',
                    'availabletime' => '',
                    'institutionname' => null,
                    'existingyn' => 'Y',
                    'loggedin' => '',
                    'mobilealerts' => '',
                    'emailalerts' => '',
                    'title' => $oJourneyData->gender ?? '',
                    'partid' => '',
                    'status1' => '',
                    'status2' => '',
                    'status3' => ''
                ],
                'weomotpolicyin' => [
                    'contractid' => '0',
                    'poltype' => $policyType,  // "1",
                    'product4digitcode' => $sPrePolicyType,  // "1826",
                    'deptcode' => $deptcode,
                    'branchcode' => '1149',
                    'termstartdate' => $PolicyFromDate,
                    'termenddate' => $PolicyToDate,
                    'tpfintype' => !empty($hypo) ? 1 : 0,  // "1",
                    'hypo' => $hypo ?? '',
                    'vehicletypecode' => '22',
                    'vehicletype' => 'Private Car',
                    'miscvehtype' => '0',
                    'vehiclemakecode' => '108',
                    'vehiclemake' => 'HYUNDAI',
                    'vehiclemodelcode' => '51',
                    'vehiclemodel' => 'GRAND i10',
                    'vehiclesubtypecode' => '2',
                    'vehiclesubtype' => 'MAGNA 1.2 KAPPA DUAL VTVT 5 SPEED',
                    'fuel' => 'P',
                    'zone' => $zone,  // "A",
                    'engineno' => $EngineNo ?? '',  // "RJ12FB7889",
                    'chassisno' => $ChassisNo ?? '',  // "RJ12FB56783478889",
                    'registrationno' => $regno ?? '',  // "NEW",
                    'registrationdate' => $carregdata ?? '',  // "09-Nov-2025",
                    'registrationlocation' => $state,
                    'regilocother' => $state,
                    'carryingcapacity' => '5',
                    'cubiccapacity' => '1197',
                    'yearmanf' => $year,  // "2022",
                    'color' => '',
                    'vehicleidv' => $caridv,  // "375803",
                    'ncb' => $claim ?? '0',  // "20",
                    'addloading' => '0',
                    'addloadingon' => '0',
                    'spdiscrate' => '0',
                    'elecacctotal' => '0',
                    'nonelecacctotal' => '0',
                    'prvpolicyref' => $policynumber,  // "23334343434344343",
                    'prvexpirydate' => $EndDate,  // "26-Aug-2022",
                    'prvinscompany' => $InsurCmpny ?? '',
                    'prvncb' => !empty($claim) ? 1 : 0,
                    'prvclaimstatus' => '0',
                    'automembership' => '0',
                    'partnertype' => 'P'
                ],
                'accessorieslist' => [
                    [
                        'contractid' => '0',
                        'acccategorycode' => '0',
                        'acctypecode' => '0',
                        'accmake' => '',
                        'accmodel' => '',
                        'acciev' => '0',
                        'acccount' => '0'
                    ]
                ],
                'paddoncoverlist' => [
                    [
                        'paramdesc' => null,
                        'paramref' => null
                    ]
                ],
                'motextracover' => [
                    'geogextn' => '0',
                    'noofpersonspa' => null,  // "", //"5",
                    'suminsuredpa' => '0',
                    'suminsuredtotalnamedpa' => null,
                    'cngvalue' => '0',
                    'noofemployeeslle' => '0',
                    'noofpersonsllo' => '0',
                    'fibreglassvalue' => '0',
                    'sidecarvalue' => '0',
                    'nooftrailers' => '0',
                    'totaltrailervalue' => '0',
                    'voluntaryexcess' => '0',
                    'covernoteno' => '',
                    'covernotedate' => '',
                    'subimdcode' => '',
                    'extrafield1' => '',
                    'extrafield2' => '',
                    'extrafield3' => ''
                ],
                'premiumdetails' => [
                    'ncbamt' => '0',
                    'addloadprem' => '0',
                    'totalodpremium' => '0',
                    'totalactpremium' => '0',
                    'totalnetpremium' => '0',
                    'totalpremium' => '0',
                    'netpremium' => '0',
                    'finalpremium' => '0',
                    'spdisc' => '0',
                    'servicetax' => '0',
                    'stampduty' => '0',
                    'collpremium' => '0',
                    'imtout' => '',
                    'totaliev' => '0'
                ],
                'premiumsummerylist' => [
                    [
                        'paramdesc' => '0',
                        'paramref' => '0',
                        'paramtype' => '0',
                        'od' => '0',
                        'act' => '0',
                        'net' => '0'
                    ]
                ],
                'questlist' => [
                    [
                        'questionref' => '',
                        'contractid' => '',
                        'questionval' => ''
                    ]
                ],
                'detariffobj' => [
                    'vehpurchasetype' => '',
                    'vehpurchasedate' => '',
                    'monthofmfg' => '',
                    'registrationauth' => '',
                    'bodytype' => '',
                    'goodstranstype' => '',
                    'natureofgoods' => '',
                    'othergoodsfrequency' => '',
                    'permittype' => '',
                    'roadtype' => '',
                    'vehdrivenby' => '',
                    'driverexperience' => '',
                    'clmhistcode' => '',
                    'incurredclmexpcode' => '',
                    'driverqualificationcode' => '',
                    'tacmakecode' => '',
                    'extcol1' => '',
                    'extcol2' => '',
                    'extcol3' => '',
                    'extcol4' => '',
                    'extcol5' => '',
                    'extcol6' => '',
                    'extcol7' => '',
                    'extcol8' => '',  // "ACPA",
                    'extcol9' => '',
                    'extcol10' => $addons,
                    'extcol11' => '',
                    'extcol12' => '',
                    'extcol13' => '',
                    'extcol14' => '',
                    'extcol15' => '',
                    'extcol16' => '',
                    'extcol17' => '',
                    'extcol18' => '',
                    'extcol19' => '',
                    'extcol20' => 'http://192.168.29.219:3000/motor/car/vendor/bajaj/payment/thankyou?',
                    'extcol22' => '',
                    'extcol23' => '',
                    'extcol24' => '',  // "1",
                    'extcol25' => '',  // "1",
                    'extcol26' => '',  // "3142657309616",
                    'extcol27' => '',
                    'extcol28' => '',
                    'extcol29' => '',
                    'extcol30' => '',
                    'extcol31' => '',
                    'extcol32' => '',
                    'extcol33' => '',  // "19AAATA6757C1ZS",
                    'extcol34' => '',  // "710148",
                    'extcol35' => '',
                    'extcol36' => '',
                    'extcol37' => '',
                    'extcol38' => '',  // "~ ~",
                    'extcol39' => '',
                    'extcol40' => ''
                ],
                'potherdetails' => [
                    'imdcode' => '',
                    'covernoteno' => '',
                    'leadno' => '',
                    'ccecode' => '',
                    'runnercode' => '',
                    'extra1' => '',
                    'extra2' => '',
                    'extra3' => '',
                    'extra4' => '',
                    'extra5' => ''
                ],
                'premiumpayerid' => '0',
                'paymentmode' => 'CC'
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

            curl_close($curl);
            return $response;
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ]);
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

    public static function policypdfdownload(Request $request)
    {
        try {
            $userId = $request->userid;
            $policyno = $request->data['policynumber'];

            $postdata = [
                'userid' => 'webservice.pos@digibima.com',
                'password' => 'newpas12',
                'pdfmode' => 'WS_POLICY_PDF',
                'policynum' => $policyno
            ];

            $data = json_encode($postdata);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://extuat.bajajallianz.com/BjazDownloadPDFWs/policypdfdownload',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);

            $respJson = json_decode($response, true);

            $pdfBase64 = $respJson['fileByteObj'] ?? null;

            if (!$pdfBase64) {
                return response()->json([
                    'status' => false,
                    'message' => 'PDF data not found',
                    'raw' => $respJson
                ]);
            }

            $pdfContent = base64_decode($pdfBase64);
            $path = 'bajaj/policies/' . $policyno . '.pdf';
            $savePath = public_path($path);

            if (!file_exists(dirname($savePath))) {
                mkdir(dirname($savePath), 0777, true);
            }

            file_put_contents($savePath, $pdfContent);

            $digiPayment = new DigiPayment();
            $digiPayment->userid = $userId;
            $digiPayment->vid = getconstant('MOTOR.BAJAJMOTOR.KEY');
            $digiPayment->vehicle_type = 'BAJAJ';
            $digiPayment->policy = $policyno;
            $digiPayment->policy_pdf_path = $path;
            $digiPayment->created_at = now();
            $digiPayment->updated_at = now();
            $digiPayment->save();
            return $digiPayment;
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
