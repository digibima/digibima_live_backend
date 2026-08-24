<?php
namespace App\Services;
use stdClass;
use Carbon\Carbon;
use Illuminate\Support\Facades\{Auth, Cache};
use App\Http\Controllers\front\motor\Vendor\shriram\Car\ShriramCarController;
use App\Http\Controllers\front\motor\Vendor\shriram\Bike\ShriramBikeController;
use App\Models\Shriram\{Shriram_Pincode, Shriram_planCheckout, Shriram_RTO_Master, Shriram_Prev_insurence, Shriram_Vehicle_Master};
use App\Models\{Master_Vehicle_Data as DataModel, MasterAPI, User, MotorJourney, MasterVendor, VendorMotor, MasterMotor, UserMotorDescription, Vehicle_Info};

class GoDigitBikeService
{
    private static $Username;
    private static $Password;
    public static function initlize()
    {
        self::$Username = '';
        self::$Password = '';
    }
    public static function TokenGenerate()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://preprod-oneapi.godigit.com/OneAPI/digit/generateAuthKey',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
            "username": "35327650",
            "password": "Digit@123$"
        }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function generateBikeQuote()
    {
        $data = json_encode([
            "pincode" => "", //MAX LENGTH 20 CHARACTERS(MANDATORY)
            "contract" => [
                "policyHolderType" => "", //MAX LENGTH 20 CHARACTERS
                "insuranceProductCode" => "", //Support Values (20203,20103,20101,20202,20301,20302,20201,20102)MANDATORY
                "endDate" => "", //Support format (yyyy-MM-dd)
                "subInsuranceProductCode" => "", // MAX LENGTH 20 CHARACTERS (MANDATORY)
                "coverages" => [
                    "addons" => [
                        "personalBelonging" => ["selection" => false],
                        "returnToInvoice" => ["selection" => false],
                        "rimProtection" => ["selection" => false],
                        "consumables" => ["selection" => false],
                        "partsDepreciation" => [
                            "selection" => false,
                            "claimsCovered" => ""
                        ],
                        "engineProtection" => ["selection" => false],
                        "tyreProtection" => ["selection" => false],
                        "roadSideAssistance" => ["selection" => false],
                        "keyAndLockProtect" => ["selection" => false],
                    ],
                    "accessories" => [
                        "electrical" => [
                            "maxAllowed" => 0,
                            "selection" => false,
                            "minAllowed" => 0,
                            "insuredAmount" => 0
                        ],
                        "nonElectrical" => [
                            "maxAllowed" => 0,
                            "selection" => false,
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
                    "voluntaryDeductible" => "",
                    "isGeoExt" => false,
                    "legalLiability" => [
                        "nonFarePaxLL" => ["selection" => false, "insuredCount" => 0],
                        "unnamedPaxLL" => ["selection" => false, "insuredCount" => 0],
                        "workersCompensationLL" => ["selection" => false, "insuredCount" => 0],
                        "paidDriverLL" => ["selection" => false, "insuredCount" => 0],
                        "employeesLL" => ["selection" => false, "insuredCount" => 0],
                        "cleanersLL" => ["selection" => false, "insuredCount" => 0],
                    ],
                    "theft" => ["selection" => false],
                    "isTheftAndConversionRiskIMT43" => "",
                    "ownDamage" => [
                        "surcharge" => new stdClass(),
                        "discount" => [
                            "userSpecialDiscountPercent" => ""
                        ]
                    ],
                    "isIMT23" => false,
                    "personalAccident" => [
                        "coverTerm" => 0,
                        "selection" => false,
                        "insuredAmount" => 0
                    ],
                    "fire" => ["selection" => false],
                    "thirdPartyLiability" => ["isTPPD" => false],
                    "unnamedPA" => [
                        "unnamedPaidDriver" => ["selection" => false, "insuredAmount" => 0, "insuredCount" => 0],
                        "unnamedPax" => ["selection" => false, "insuredAmount" => 0, "insuredCount" => 0],
                        "unnamedConductor" => ["selection" => false, "insuredAmount" => 0, "insuredCount" => 0],
                        "unnamedHirer" => ["selection" => false, "insuredAmount" => 0, "insuredCount" => 0],
                        "unnamedPillionRider" => ["selection" => false, "insuredAmount" => 0, "insuredCount" => 0],
                        "unnamedCleaner" => ["selection" => false, "insuredAmount" => 0, "insuredCount" => 0],
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
                "isVehicleNew" => false,
                "licensePlateNumber" => "",
                "permitType" => "",
                "registrationAuthority" => "",
                "engineNumber" => "",
                "vehicleIdentificationNumber" => "",
                "registrationDate" => "",
                "manufactureDate" => "",
                "vehicleMaincode" => 0,
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
                    "gender" => "",
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
                "previousPolicyExpiryDate" => "",
                "isClaimInLastYear" => false,
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
                "previousNoClaimBonus" => "",
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
            "enquiryId" => ""
        ]);

        $curl = curl_init();

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
                'integrationId: 22871-0100',
                'Content-Type: application/json',
                'Authorization: Bearer <Enter the access_token generated using GenerateAuth API>'
            ),
        ));
        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function generateBikeProposal()
    {
        $data = [
            "persons" => [
                [
                    "firstName" => "Harsh",
                    "identificationDocuments" => [],
                    "lastName" => "Piparwar",
                    "addresses" => [
                        [
                            "addressType" => "PRIMARY_RESIDENCE",
                            "flatNumber" => null,
                            "streetNumber" => null,
                            "street" => "jhgfdsdfg76543456 ",
                            "district" => null,
                            "city" => "Pune",
                            "country" => "IN",
                            "pincode" => 411057,
                            "state" => "27"
                        ]
                    ],
                    "communications" => [
                        [
                            "communicationType" => "MOBILE",
                            "communicationId" => "9987650999",
                            "isPrefferedCommunication" => true
                        ],
                        [
                            "communicationType" => "EMAIL",
                            "communicationId" => "ajhgfd@a.com",
                            "isPrefferedCommunication" => true
                        ]
                    ],
                    "isVehicleOwner" => true,
                    "isInsuredPerson" => true,
                    "gender" => "MALE",
                    "isPolicyHolder" => true,
                    "dateOfBirth" => "1998-07-08",
                    "isDriver" => true,
                    "personType" => "INDIVIDUAL"
                ]
            ],
            "pincode" => 411057,
            "previousInsurer" => [
                "previousPolicyExpiryDate" => "2023-08-18",
                "isClaimInLastYear" => false,
                "previousInsurerCode" => "159",
                "previousPolicyNumber" => "jhgfdwertii",
                "currentThirdPartyPolicy" => null,
                "isPreviousInsurerKnown" => true,
                "previousPolicyType" => null,
                "previousNoClaimBonus" => "ZERO",
                "originalPreviousPolicyType" => null
            ],
            "preInspection" => [
                "isPreInspectionOpted" => false
            ],
            "kyc" => [
                "ckycReferenceNumber" => "ASDRR4277S",
                "isKYCDone" => false,
                "ckycReferenceDocId" => "D07",
                "photo" => "gfgh",
                "dateOfBirth" => "1988-07-08"
            ],
            "contract" => [
                "policyHolderType" => "INDIVIDUAL",
                "insuranceProductCode" => "20101",
                "endDate" => null,
                "externalPolicyNumber" => null,
                "subInsuranceProductCode" => null,
                "coverages" => [
                    "accessories" => [
                        "cng" => [
                            "selection" => false,
                            "insuredAmount" => null
                        ],
                        "electrical" => [
                            "selection" => false,
                            "insuredAmount" => null
                        ],
                        "nonElectrical" => [
                            "selection" => false,
                            "insuredAmount" => null
                        ]
                    ],
                    "addons" => [
                        "partsDepreciation" => ["selection" => true],
                        "roadSideAssistance" => ["selection" => true],
                        "engineProtection" => ["selection" => true],
                        "tyreProtection" => ["selection" => true],
                        "rimProtection" => ["selection" => true],
                        "returnToInvoice" => ["selection" => true],
                        "consumables" => ["selection" => true],
                        "personalBelonging" => ["selection" => true],
                        "keyAndLockProtect" => ["selection" => true]
                    ],
                    "voluntaryDeductible" => null,
                    "isGeoExt" => false,
                    "legalLiability" => [
                        "paidDriverLL" => ["selection" => true],
                        "employeesLL" => ["selection" => true],
                        "unnamedPaxLL" => ["selection" => true],
                        "cleanersLL" => ["selection" => true],
                        "nonFarePaxLL" => ["selection" => true],
                        "workersCompensationLL" => ["selection" => true]
                    ],
                    "unnamedPA" => [
                        "unnamedPax" => [
                            "selection" => true,
                            "insuredAmount" => null
                        ],
                        "unnamedPaidDriver" => ["selection" => true],
                        "unnamedHirer" => ["selection" => true],
                        "unnamedPillionRider" => ["selection" => true],
                        "unnamedCleaner" => ["selection" => true],
                        "unnamedConductor" => ["selection" => true]
                    ],
                    "theft" => ["selection" => false],
                    "isTheftAndConversionRiskIMT43" => false,
                    "ownDamage" => [
                        "discount" => [
                            "userSpecialDiscountPercent" => "0"
                        ]
                    ],
                    "isIMT23" => false,
                    "personalAccident" => [
                        "coverTerm" => null,
                        "selection" => false,
                        "insuredAmount" => null
                    ],
                    "fire" => ["selection" => false],
                    "thirdPartyLiability" => [
                        "isTPPD" => false
                    ],
                    "isOverturningExclusionIMT47" => false
                ],
                "startDate" => null
            ],
            "nominee" => [
                "firstName" => "dfgh",
                "lastName" => "jhgf",
                "dateOfBirth" => "1999-09-09",
                "middleName" => null,
                "personType" => "INDIVIDUAL",
                "relation" => "BROTHER"
            ],
            "motorQuestions" => [
                "selfInspection" => true,
                "furtherAgreement" => null,
                "financer" => null
            ],
            "enquiryId" => "kjhgfdswertyui9876543wsdfghjkjhf",
            "pospInfo" => [
                "isPOSP" => false
            ],
            "vehicle" => [
                "isVehicleNew" => false,
                "licensePlateNumber" => "MH01AW3497",
                "registrationAuthority" => "MH01",
                "engineNumber" => "jhgfdsd765",
                "vehicleIdentificationNumber" => "kjhgfdsrty7654",
                "registrationDate" => "2019-09-09",
                "manufactureDate" => "2019-09-09",
                "vehicleMaincode" => 1110310314,
                "vehicleIDV" => null
            ]
        ];


        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://preprod-oneapi.godigit.com/OneAPI/v1/executor',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
 
    "policyId": "TEXT <MAX LENGTH 50 CHARACTERS> <*MANDATORY*> ",
    "headerParam": {
      "Authorization": "TEXT <MAX LENGTH 30 CHARACTERS> <*MANDATORY*> "
    }
  
}',
            CURLOPT_HTTPHEADER => array(
                'integrationId: 22793-0100',
                'Content-Type: application/json',
                'Authorization: Bearer <Enter the access_token generated using GenerateAuth API>'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }



}