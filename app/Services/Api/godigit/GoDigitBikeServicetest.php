<?php
namespace App\Services\Api\godigit;
use App\Services\Api\godigit\GoDigitUtilityService;
use stdClass;
use Carbon\Carbon;
use Illuminate\Support\Facades\{Auth, Cache};
use App\Http\Controllers\Api\front\motor\Vendor\shriram\Car\ShriramCarController;
use App\Http\Controllers\Api\front\motor\Vendor\shriram\Bike\ShriramBikeController;
//use App\Models\Godigit\{};
use App\Models\{Master_Vehicle_Data as DataModel, MasterAPI, User, MotorJourney, MasterVendor, VendorMotor, MasterMotor, UserMotorDescription, Vehicle_Info};

class GoDigitBikeServicetest
{
    private static $Username;
    private static $Password;
    public static function initlize()
    {
        self::$Username = '';
        self::$Password = '';
    }


    public function generateBikeQuotetest()
    {
        //dd("hjkf");
        $sNewToken = GoDigitUtilityService::TokenGenerate();
        $sToken = json_decode($sNewToken, true);
        $sToken = $sToken ? $sToken['access_token'] : '';
        //dd($sToken); 
        $curl = curl_init();
        $data = json_encode([
            "pincode" => "", //MAX LENGTH 20 CHARACTERS(MANDATORY)
            "contract" => [
                "policyHolderType" => "INDIVIDUAL", //MAX LENGTH 20 CHARACTERS
                "insuranceProductCode" => "20201", //Support Values (20203,20103,20101,20202,20301,20302,20201,20102)MANDATORY
                "endDate" => "", //Support format (yyyy-MM-dd)
                "subInsuranceProductCode" => "PB", // MAX LENGTH 20 CHARACTERS (MANDATORY)
                "coverages" => [
                    "addons" => [
                        "personalBelonging" => ["selection" => false],
                        "returnToInvoice" => ["selection" => true],
                        "rimProtection" => ["selection" => false],
                        "consumables" => ["selection" => true],
                        "partsDepreciation" => [
                            "selection" => true,
                            "claimsCovered" => "ONE"
                        ],
                        "engineProtection" => ["selection" => true],
                        "tyreProtection" => ["selection" => false],
                        "roadSideAssistance" => ["selection" => true],
                        "keyAndLockProtect" => ["selection" => false],
                    ],
                    "accessories" => [
                        "electrical" => [
                            "maxAllowed" => 0,
                            "selection" => true,
                            "minAllowed" => 0,
                            "insuredAmount" => 0
                        ],
                        "nonElectrical" => [
                            "maxAllowed" => 0,
                            "selection" => true,
                            "minAllowed" => 0,
                            "insuredAmount" => 0
                        ],
                        "cng" => [
                            "maxAllowed" => 0,
                            "selection" => false,
                            "minAllowed" => 0,
                            "insuredAmount" => 0
                        ]
                    ],
                    "voluntaryDeductible" => 0,
                    "isGeoExt" => false,
                    "legalLiability" => [
                        "nonFarePaxLL" => ["selection" => false, "insuredCount" => 0],
                        "unnamedPaxLL" => ["selection" => false, "insuredCount" => 0],
                        "workersCompensationLL" => ["selection" => false, "insuredCount" => 0],
                        "paidDriverLL" => ["selection" => false, "insuredCount" => 0],
                        "employeesLL" => ["selection" => false, "insuredCount" => 0],
                        "cleanersLL" => ["selection" => false, "insuredCount" => 0],
                    ],
                    "theft" => ["selection" => true],
                    "isTheftAndConversionRiskIMT43" => "",
                    "ownDamage" => [
                        "surcharge" => new stdClass(),
                        "discount" => [
                            "userSpecialDiscountPercent" => ""
                        ]
                    ],
                    "isIMT23" => false,
                    "personalAccident" => [
                        "coverTerm" => null,
                        "selection" => false,
                        "insuredAmount" => null
                    ],
                    "fire" => ["selection" => true],
                    "thirdPartyLiability" => ["isTPPD" => True],
                    "unnamedPA" => [
                        "unnamedPaidDriver" => ["selection" => true, "insuredAmount" => 0, "insuredCount" => 0],
                        "unnamedPax" => ["selection" => true, "insuredAmount" => 0, "insuredCount" => 0],
                        "unnamedConductor" => ["selection" => false, "insuredAmount" => 0, "insuredCount" => 0],
                        "unnamedHirer" => ["selection" => true, "insuredAmount" => 0, "insuredCount" => 0],
                        "unnamedPillionRider" => ["selection" => false, "insuredAmount" => 0, "insuredCount" => 0],
                        "unnamedCleaner" => ["selection" => true, "insuredAmount" => 0, "insuredCount" => 0],
                    ],
                    "isOverturningExclusionIMT47" => false
                ],
                "startDate" => ""
            ],
            "motorQuestions" => [
                "selfInspection" => false,
                "furtherAgreement" => "",
                "financer" => "",
                "whatsappCommunicationConstent" => ""
            ],
            "pathParam" => [
                "applicationId" => ""
            ],
            "pospInfo" => [
                "isPOSP" => false,
                "pospContactNumber" => "",
                "pospName" => "",
                "pospAadhaarNumber" => "",
                "pospLocation" => "",
                "pospUniqueNumber" => "",
                "pospPanNumber" => ""
            ],
            "vehicle" => [
                "isVehicleNew" => true,
                "licensePlateNumber" => "MH01",
                "permitType" => null,
                "registrationAuthority" => "MH01",
                "engineNumber" => "",
                "vehicleIdentificationNumber" => "73478HJDJF98438HD",
                "registrationDate" => "2019-06-04",
                "manufactureDate" => "2019-06-04",
                "vehicleMaincode" => "1211311119",
                "vehicleIDV" => [
                    "minimumIdv" => 0,
                    "defaultIdv" => 0,
                    "maximumIdv" => 0,
                    "idv" => 0
                ],
                "usageType" => ""
            ],
            "persons" => [
                [
                    "identificationDocuments" => [
                        [
                            "expiryDate" => "",
                            "identificationDocumentId" => "",
                            "issuingPlace" => "",
                            "documentType" => "",
                            "documentId" => "",
                            "issueDate" => ""
                        ]
                    ],
                    "lastName" => "",
                    "addresses" => [
                        [
                            "country" => "",
                            "pincode" => "",
                            "city" => "",
                            "streetNumber" => "",
                            "addressType" => "",
                            "flatNumber" => "",
                            "street" => "",
                            "district" => "",
                            "state" => ""
                        ]
                    ],
                    "gender" => "male",
                    "isPolicyHolder" => false,
                    "dateOfBirth" => "",
                    "firstName" => "",
                    "communications" => [
                        [
                            "communicationId" => "",
                            "isPrefferedCommunication" => false,
                            "communicationType" => ""
                        ],
                        [
                            "communicationId" => "",
                            "isPrefferedCommunication" => false,
                            "communicationType" => ""
                        ]
                    ],
                    "isVehicleOwner" => false,
                    "isInsuredPerson" => false,
                    "middleName" => "",
                    "isDriver" => false,
                    "personType" => ""
                ]
            ],
            "previousInsurer" => [
                "previousPolicyExpiryDate" => "2024-06-04",
                "isClaimInLastYear" => true,
                "previousInsurerCode" => "",
                "previousPolicyNumber" => "",
                "currentThirdPartyPolicy" => [
                    "currentThirdPartyPolicyExpiryDateTime" => "",
                    "currentThirdPartyPolicyInsurerCode" => "",
                    "currentThirdPartyPolicyStartDateTime" => "",
                    "currentThirdPartyPolicyNumber" => ""
                ],
                "isPreviousInsurerKnown" => false,
                "previousPolicyType" => "",
                "previousNoClaimBonus" => "ZERO",
                "originalPreviousPolicyType" => ""
            ],
            "preInspection" => [
                "isPreInspectionOpted" => false
            ],
            "kyc" => [
                "addressVerificationDocType" => "",
                "ckycReferenceNumber" => "",
                "addressVerificationDoc" => [],
                "isKYCDone" => false,
                "ckycReferenceDocId" => "",
                "photo" => "",
                "dateOfBirth" => "",
                "idVerificationDocType" => "",
                "idVerificationDoc" => []
            ],
            "dealer" => [
                "dealerName" => "",
                "city" => "",
                "deliveryDate" => ""
            ],
            "nominee" => [
                "firstName" => "",
                "lastName" => "",
                "dateOfBirth" => "",
                "middleName" => "",
                "personType" => "",
                "relation" => ""
            ],
            "enquiryId" => "124334"
        ]);
        //dd($data);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://preprod-oneapi.godigit.com/OneAPI/v1/executor',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'integrationId: 22789-0100',
                'Authorization: Bearer ' . $sToken,
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        dd($response);
        curl_close($curl);
        return $response;
    }

    public function generateBikeProposal()
    {
        $sNewToken = GoDigitUtilityService::TokenGenerate();
        $sToken = json_decode($sNewToken, true);
        $sToken = $sToken ? $sToken['access_token'] : '';
        //dd($sToken); 
        $curl = curl_init();
        $data = [
            "motorMotorUsedCarCreateQuote" => [
                "enquiryId" => "6141-577661-39811583-3351",
                "contract" => [
                    "insuranceProductCode" => "20202",
                    "subInsuranceProductCode" => "PB",
                    "startDate" => null,
                    "endDate" => null,
                    "policyHolderType" => "INDIVIDUAL",
                    "externalPolicyNumber" => "PARTNER_POL_NO",
                    "isNCBTransfer" => false,
                    "coverages" => [
                        "voluntaryDeductible" => null,
                        "thirdPartyLiability" => [
                            "isTPPD" => false
                        ],
                        "ownDamage" => [
                            "discount" => [
                                "userSpecialDiscountPercent" => 0,
                                "discounts" => []
                            ],
                            "surcharge" => [
                                "loadings" => []
                            ]
                        ],
                        "personalAccident" => [
                            "selection" => true,
                            "insuredAmount" => null,
                            "coverTerm" => null
                        ],
                        "accessories" => [
                            "cng" => [
                                "selection" => false,
                                "insuredAmount" => 0
                            ],
                            "electrical" => [
                                "selection" => false,
                                "insuredAmount" => 0
                            ],
                            "nonElectrical" => [
                                "selection" => false,
                                "insuredAmount" => 0
                            ]
                        ],
                        "addons" => [
                            "partsDepreciation" => [
                                "claimsCovered" => null,
                                "selection" => false
                            ],
                            "roadSideAssistance" => [
                                "selection" => false
                            ],
                            "engineProtection" => [
                                "selection" => false
                            ],
                            "tyreProtection" => [
                                "selection" => false
                            ],
                            "rimProtection" => [
                                "selection" => false
                            ],
                            "returnToInvoice" => [
                                "selection" => false
                            ],
                            "consumables" => [
                                "selection" => false
                            ],
                            "keyAndLockProtect" => [
                                "selection" => false
                            ],
                            "personalBelonging" => [
                                "selection" => false
                            ]
                        ],
                        "legalLiability" => [
                            "paidDriverLL" => [
                                "selection" => false,
                                "insuredCount" => null
                            ],
                            "employeesLL" => [
                                "selection" => null,
                                "insuredCount" => null
                            ],
                            "unnamedPaxLL" => [
                                "selection" => null,
                                "insuredCount" => null
                            ],
                            "cleanersLL" => [
                                "selection" => null,
                                "insuredCount" => null
                            ],
                            "nonFarePaxLL" => [
                                "selection" => null,
                                "insuredCount" => null
                            ],
                            "workersCompensationLL" => [
                                "selection" => null,
                                "insuredCount" => null
                            ]
                        ],
                        "unnamedPA" => [
                            "unnamedPax" => [
                                "selection" => false,
                                "insuredAmount" => 0,
                                "insuredCount" => null
                            ],
                            "unnamedPaidDriver" => [
                                "selection" => false,
                                "insuredAmount" => 0,
                                "insuredCount" => null
                            ],
                            "unnamedHirer" => [
                                "selection" => null,
                                "insuredAmount" => null,
                                "insuredCount" => null
                            ],
                            "unnamedPillionRider" => [
                                "selection" => null,
                                "insuredAmount" => null,
                                "insuredCount" => null
                            ],
                            "unnamedCleaner" => [
                                "selection" => null,
                                "insuredAmount" => null,
                                "insuredCount" => null
                            ],
                            "unnamedConductor" => [
                                "selection" => null,
                                "insuredAmount" => null,
                                "insuredCount" => null
                            ]
                        ]
                    ]
                ],
                "vehicle" => [
                    "isVehicleNew" => false,
                    "vehicleMaincode" => "1111610602",
                    "licensePlateNumber" => "DL9CW1234",
                    "vehicleIdentificationNumber" => "73478HJDJF98438HD",
                    "engineNumber" => "9843HDHF893498343",
                    "manufactureDate" => "2018-12-05",
                    "registrationDate" => "2018-12-06",
                    "vehicleIDV" => null,
                    "usedVehicle" => [
                        "isUsedVehicle" => true,
                        "saleValue" => "INR 10000000.00",
                        "purchaseDate" => "2018-12-06"
                    ],
                    "motorType" => null
                ],
                "pincode" => "560102",
                "previousInsurer" => [
                    "isPreviousInsurerKnown" => false,
                    "previousInsurerCode" => null,
                    "previousPolicyNumber" => null,
                    "previousPolicyExpiryDate" => "2023-08-02",
                    "isClaimInLastYear" => false,
                    "originalPreviousPolicyType" => null,
                    "previousPolicyType" => null,
                    "previousNoClaimBonus" => "ZERO",
                    "currentThirdPartyPolicy" => [
                        "isCurrentThirdPartyPolicyActive" => null,
                        "currentThirdPartyPolicyInsurerCode" => null,
                        "currentThirdPartyPolicyNumber" => "",
                        "currentThirdPartyPolicyStartDateTime" => "",
                        "currentThirdPartyPolicyExpiryDateTime" => ""
                    ]
                ],
                "pospInfo" => [
                    "isPOSP" => true,
                    "pospName" => "Spinny POSP",
                    "pospUniqueNumber" => "HDFCB23434",
                    "pospLocation" => "",
                    "pospPanNumber" => "AHWPA1451C",
                    "pospAadhaarNumber" => "689505607999",
                    "pospContactNumber" => "9898989823"
                ],
                "motorQuestions" => [
                    "furtherAgreement" => null,
                    "selfInspection" => false,
                    "financer" => null
                ],
                "persons" => [
                    [
                        "personType" => "INDIVIDUAL",
                        "addresses" => [
                            [
                                "addressType" => "PRIMARY_RESIDENCE",
                                "flatNumber" => "",
                                "streetNumber" => "DFDFUIUID",
                                "street" => "738",
                                "district" => "BENGALURU URBAN",
                                "state" => "29",
                                "city" => "BENGALURU",
                                "country" => "IN",
                                "pincode" => "560102"
                            ]
                        ],
                        "communications" => [
                            [
                                "communicationType" => "MOBILE",
                                "communicationId" => "7013848059",
                                "isPrefferedCommunication" => true
                            ],
                            [
                                "communicationType" => "EMAIL",
                                "communicationId" => "s.ruhee@godigit.com",
                                "isPrefferedCommunication" => true
                            ]
                        ],
                        "identificationDocuments" => [
                            [
                                "issuingAuthority" => "Govt of India",
                                "issuingPlace" => "IN",
                                "documentType" => "PAN_CARD",
                                "documentId" => "FAMPB4091G"
                            ]
                        ],
                        "isPolicyHolder" => true,
                        "isVehicleOwner" => true,
                        "firstName" => "krishna ",
                        "middleName" => "",
                        "lastName" => "bhargav",
                        "dateOfBirth" => "1999-07-15",
                        "gender" => "MALE",
                        "isDriver" => true,
                        "isInsuredPerson" => true
                    ]
                ],
                "kyc" => [
                    "isKYCDone" => false,
                    "ckycReferenceDocId" => "D07",
                    "ckycReferenceNumber" => "FAMPB4091G",
                    "dateOfBirth" => "1999-07-15"
                ],
                "nominee" => [
                    "firstName" => "KFKJDJFJK",
                    "middleName" => "",
                    "lastName" => "",
                    "dateOfBirth" => "2000-02-10",
                    "relation" => "SISTER",
                    "personType" => "INDIVIDUAL"
                ],
                "dealer" => [
                    "dealerName" => "",
                    "city" => "",
                    "deliveryDate" => null
                ],
                "identificationDocuments" => []
            ]
        ];

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://preprod-oneapi.godigit.com/OneAPI/v1/transforRequest',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'integrationId=> 20823-0100',
                'Authorization: Bearer ' . $sToken,
                'Content-Type=> application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }
}