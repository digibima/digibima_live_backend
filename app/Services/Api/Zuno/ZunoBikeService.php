<?php
namespace App\Services\Api\Zuno;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Zuno\{Zuno_RTO_Master, Zuno_Pincode, Zuno_Prev_Insurer};
use App\Models\{Master_Vehicle_Data as DataModel, MasterAPI, User, MotorJourney, MasterVendor, VendorMotor, MasterMotor, UserMotorDescription, Vehicle_Info};
use Illuminate\Support\Facades\{Auth, Cache};
use App\Services\Api\Zuno\ZunoBikeUtilityService;
use Vtiful\Kernel\Format;
class ZunoBikeService
{
    private static $xapikey = "LrPGqNsTwM12cIH4VEsmB2Ew2i19vBxO1QMfhOfk";
    //private static $Masterxapikey = "Y1B6JKVTVN4IRoho0I3whafGfU9IiNkDa8K6GcKm";

    // private static $password;
    // private static $deptcode;

    // public static function initlize()
    // {
    //     self::$Id = getconstant('HEALTH.BAJAJ.CREDENTIAL.ID');
    //     self::$imdCode = getconstant('HEALTH.BAJAJ.CREDENTIAL.IMDCODE');
    //     self::$password = getconstant('HEALTH.BAJAJ.CREDENTIAL.PASSWORD');
    //     self::$deptcode = getconstant('HEALTH.BAJAJ.CREDENTIAL.DEPTCODE');

    // }


    public static function generateBikeQuote(Request $request, $nextyear, $nPlanType)
    {
        try {
            $userId = $request->userid;
            $user = User::find($userId);
            $utilityService = new ZunoBikeUtilityService();
            $anewtoken = $utilityService->BikegenerateToken();
            $stoken = $anewtoken['access_token'];
            //return $stoken;
            $cachecaridv = 'cache_' . $userId . '_caridv';
            $idv = GetCache($cachecaridv);
            $curl = curl_init();
            $postJson = json_encode([
                "commissionContractID" => "1000014234",
                "channelCode" => "002",
                "branch" => "AHMEDABAD",
                "make" => "BAJAJ",
                "model" => "AVENGER 220 CRUISE",
                "variant" => "BS VI",
                "idvCity" => "AHMEDABAD",
                "rtoStateCode" => "06",
                "rtoLocationName" => "GJ-01",
                "rtoZone" => "06",
                "rtoCityOrDistrict" => "Ahmedabad",
                "clusterZone" => "Cluster 3",
                "carZone" => "A",
                "idv" => "112182",
                "registrationDate" => "2024-06-05",
                "previousInsurancePolicy" => "0",
                "policyType" => "Bundled Insurance",
                "subPolicyType" => "",
                "typeOfBusiness" => "New",
                "policyStartDate" => "2024-06-05",
                "policyTenure" => "5",
                "contractTenure" => "5",
                "claimDeclaration" => "",
                "annualMileage" => "",
                "transmissionType" => "",
                "fuelType" => "Petrol",
                "validLicenceNo" => "Y",
                "previousNcb" => "",
                "transferOfNcb" => "N",
                "proofOfNcb" => "NCBRESRV",
                "protectionofNcbValue" => "",
                "breakininsurance" => "NBK",
                "renewalStatus" => "New Policy",
                "dateOfTransaction" => "2024-06-05",
                "fibreGlassFuelTank" => "Yes",
                "overrideAllowableDiscount" => "N",
                "antiTheftDeviceInstalled" => "Yes",
                "automobileAssociationMember" => "Yes",
                "bodystyleDescription" => "COUPE",
                "dateOfFirstPurchaseOrRegistration" => "2024-06-05",
                "dateOfBirth" => "1989-12-12",
                "policyHolderGender" => "Male",
                "policyholderOccupation" => "Medium to High",
                "typeOfGrid" => "Grid1",
                "contractDetails" => [
                    [
                        "contract" => "Own Damage Contract",
                        "coverage" => [
                            "coverage" => "Own Damage Coverage",
                            "deductible" => [
                                "Own Damage Basis Deductible",
                                "Voluntary Deductible"
                            ],
                            "voluntaryDeductible" => "3000",
                            "discount" => [
                                "Auto Mobile Association Discount",
                                "Voluntary Deductible Discount",
                                "Side car Discount",
                                "AntiTheft Discount"
                            ],
                            "subCoverage" => [
                                [
                                    "subCoverage" => "Own Damage Basic",
                                    "limit" => "Own Damage Basic Limit"
                                ],
                                [
                                    "subCoverage" => "Non Electrical Accessories",
                                    "limit" => "Non Electrical Accessories Limit",
                                    "valueOfAccessory" => "2000",
                                    "accessoryDescription" => "qqqqq"
                                ],
                                [
                                    "subCoverage" => "Electrical Electronic Accessories",
                                    "limit" => "Electrical Electronic Accessories Limit",
                                    "valueOfAccessory" => "2000",
                                    "accessoryDescription" => "sss"
                                ],
                                [
                                    "subCoverage" => "CNG LPG Kit Own Damage",
                                    "limit" => "CNG LPG Kit Own Damage Limit",
                                    "ValueofKit" => "2000"
                                ],
                                [
                                    "subCoverage" => "In built CNG LPG Kit Own Damage",
                                    "ValueofKit" => "0"
                                ],
                                [
                                    "subCoverage" => "Side Car",
                                    "limit" => "Side car Limit",
                                    "valueOfAccessory" => "25000"
                                ],
                                [
                                    "subCoverage" => "Value of additional accessories",
                                    "limit" => "Value of additional accessories Limit",
                                    "valueOfAccessory" => "3800"
                                ]
                            ]
                        ]
                    ],
                    [
                        "contract" => "Addon Contract",
                        "coverage" => [
                            "coverage" => "Add On Coverage",
                            "deductible" => "Key Replacement Deductible",
                            "underwriterDiscount" => "0.0",
                            "subCoverage" => [
                                [
                                    "subCoverage" => "Return To Invoice"
                                ],
                                [
                                    "subCoverage" => "Pillion Protect",
                                    "limit" => "Pillion Protect Limit",
                                    "sumInsuredPerPerson" => "50000"
                                ],
                                [
                                    "subCoverage" => "Zero Depreciation"
                                ],
                                [
                                    "subCoverage" => "Consumable Cover"
                                ],
                                [
                                    "subCoverage" => "Emergency Medical Expenses Protect"
                                ],
                                [
                                    "subCoverage" => "Additional Third Party Property Damage Protect",
                                    "limit" => "Additional Third Party Property Damage Protect Limit",
                                    "sumInsuredPerPerson" => "50000"
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
                            "discount" => "Third Party Property Damage Discount",
                            "subCoverage" => [
                                [
                                    "subCoverage" => "Third Party Basic Sub Coverage",
                                    "limit" => "Third Party Property Damage Limit",
                                    "thirdPartyPropertyDamageLimit" => "6000"
                                ],
                                [
                                    "subCoverage" => "Legal Liability to Employees",
                                    "numberofEmployees" => "1"
                                ],
                                [
                                    "subCoverage" => "Legal Liability to Paid Drivers",
                                    "numberofPaidDrivers" => "1"
                                ],
                                [
                                    "subCoverage" => "PA Unnamed Passenger",
                                    "limit" => "PA Unnamed Passenger Limit",
                                    "sumInsuredPerPerson" => "10000"
                                ],
                                [
                                    "subCoverage" => "PA to Paid Driver Cleaner Conductor",
                                    "limit" => "PA to Paid Driver Cleaner Conductor Limit",
                                    "sumInsuredPerPerson" => "10000",
                                    "numberofPaidDrivers" => "1"
                                ],
                                [
                                    "subCoverage" => "CNG LPG Kit Liability"
                                ],
                                [
                                    "subCoverage" => "PA Cover for Named person other than paid driver",
                                    "limit" => "PA Cover for Named person other than paid driver Limit",
                                    "sumInsuredPerPerson" => "10000",
                                    "noofPersons" => "1"
                                ],
                                [
                                    "subCoverage" => "PA Cover for unnamed Hirrer or Pillion Passenger",
                                    "limit" => "PA Cover for unnamed Hirrer or Pillion Passenger Limit",
                                    "sumInsuredPerPerson" => "10000",
                                    "noofPersons" => "1"
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
            //return json_decode($postJson);
            //dd($postJson);
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://devapi.hizuno.com/motor-two-wheeler/rating',
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
            \Log::info($e->getMessage() . "errorcode:zuno_service_generateBikeQuote");
            return ['status' => '0', 'message' => $e->getMessage() . 'An error occurred while fetching cache data.'];
        }
    }
}