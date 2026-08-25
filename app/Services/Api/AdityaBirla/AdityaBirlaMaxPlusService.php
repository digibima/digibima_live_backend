<?php
//----------------------------------test---------------------------
namespace App\Services\Api\AdityaBirla;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\{Auth, Log};
use Illuminate\Support\Facades\{Cache};
use Illuminate\Support\Facades\Session;
use App\Models\JourneyUsers;
use App\Models\Insure;
use App\Http\Controllers\front\health\adityabirla\AdityaBirlaMaxPlusController;
use App\Models\Adityabirla\AdityaBirlaPincode;
use App\Models\Adityabirla\AdityaBirlaNatureofDuty;
use App\Models\Adityabirla\AdityaBirlaOccupation;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\HealthJourney;
use App\Models\CareToken;
use App\Models\Ultimatecare\UltimateInsurerMaster;
use Carbon\Carbon;
use App\Http\Controllers\front\health\caresupreme\CareSupremeController;
use DateTime;


class AdityaBirlaMaxPlusService
{
   public static function normalizeAddons($addonInput, $config)
    {
        $addonMaster = $config['ADDON'];
        $formattedAddons = [];

        $i = 0;
        $values = array_values($addonInput);

        while ($i < count($values)) {

            $key = $values[$i];


            if (isset($addonMaster[$key])) {

                $addonData = $addonMaster[$key];


                if (is_array($addonData) && isset($addonData['OPTIONS'])) {

                    $nextValue = $values[$i + 1] ?? null;

                    if ($nextValue && isset($addonData['OPTIONS'][$nextValue])) {
                        $formattedAddons[$key] = $nextValue;
                        $i += 2; // skip next value
                        continue;
                    } else {
                        $formattedAddons[$key] = array_key_first($addonData['OPTIONS']);
                    }
                } else {
                    $formattedAddons[$key] = true;
                }
            }
            $i++;
        }
        return $formattedAddons;
    }
    public static function buildOptionalCovers($selectedAddons)
    {
        $config = getconstant('HEALTH.ADITYABIRLA_Max_Plus');
        $addonMaster = $config['ADDON'];

        $optionalCovers = [];

        foreach ($selectedAddons as $addonKey => $addonValue) {

            if (!isset($addonMaster[$addonKey]))
                continue;

            $addonData = $addonMaster[$addonKey];

            if (is_string($addonData)) {

                $optionalCovers[] = [
                    "optionalCoverName" => $addonData,
                    "optionalCoverValue" => "1",
                    "CoverPartCode" => $addonKey,
                    "CoverPartValue" => ""
                ];
            } elseif (is_array($addonData) && isset($addonData['OPTIONS'])) {

                $optionalCovers[] = [
                    "optionalCoverName" => $addonData['TITLE'],
                    "optionalCoverValue" => "1",
                    "CoverPartCode" => (string) $addonValue,
                    "CoverPartValue" => (string) $addonValue
                ];
            }
        }

        return $optionalCovers;
    }
    public static function getMemberTypeCode($members)
    {
        $relations = collect($members)
            ->pluck('relation')
            ->filter()   // ✅ removes null/empty
            ->map(fn($r) => strtolower(trim($r)))
            ->map(function ($r) {
                return match ($r) {
                    'self' => 'self',
                    'husband', 'wife' => 'spouse',
                    'son', 'daughter' => 'child',
                    default => $r
                };
            })
            ->toArray();

        $final = [];
        $childCount = 0;

        foreach ($relations as $rel) {

            if ($rel === 'child') {
                $childCount++;
                $final[] = 'kid' . $childCount;
            } else {
                $final[] = $rel;
            }
        }

        // IMPORTANT: fixed ordering (NO SORTING)
        $order = ['self', 'spouse'];
        $sorted = [];

        foreach ($order as $o) {
            foreach ($final as $f) {
                if ($f === $o) {
                    $sorted[] = $f;
                }
            }
        }

        // add kids last
        foreach ($final as $f) {
            if (str_starts_with($f, 'kid')) {
                $sorted[] = $f;
            }
        }

        $key = implode('_', $sorted);

        $map = [
            'self' => 'AH01',
            'self_spouse' => 'AH02',
            'self_kid1' => 'AH03',
            'self_kid2' => 'AH04',
            'self_mother' => 'AH05',
            'self_kid1_kid2' => 'AH06',
            'self_kid1_father' => 'AH07',
            'self_kid1_mother' => 'AH08',
            'self_kid1_father_mother' => 'AH09',
            'self_kid1_kid2_kid3' => 'AH10',
            'self_spouse_kid1' => 'AH14',
            'self_spouse_kid1_kid2' => 'AH19',
            'self_spouse_kid1_father_mother' => 'AH24',
            'self_spouse_kid1_kid2_father_mother' => 'AH31',
            'self_spouse_kid1_kid2_kid3' => 'AH34',
            'self_spouse_kid1_kid2_kid3_father_mother' => 'AH39',
            'self_father_mother' => 'AH42',
            'self_spouse_father_mother' => 'AH43',
        ];

        return $map[$key] ?? 'AH01';
    }
    public static function getRelationCode($relation, $config)
    {
        $relation = strtolower(trim($relation));
        $relation = preg_replace('/\s*\(.*?\)/', '', $relation);

        $map = [
            'self' => 'R001',
            'husband' => 'R002',
            'wife' => 'R002',
            'spouse' => 'R002',

            'son' => 'R003',
            'daughter' => 'R004',

            'father' => 'R005',
            'mother' => 'R006'
        ];

        return $map[$relation] ?? 'R001';
    }
    public static function getBasicPlan($params)
    {
        try {
            $config = getconstant('HEALTH.ADITYABIRLA_Max_Plus');
            $userId = $params['userId'];
            // dd(JourneyUsers::where('proposalid', $userId)->get());
            $coverage = $params['coverage'];

            $tenure = $params['tenure'] ?? 1;
            $pincode = $params['pincode'];
            $addons = $params['addons'] ?? [];

            $user = User::find($userId);
            $journey = HealthJourney::where('userid', $userId)
                ->where('vid', $config['KEY'])
                ->first();
            if (!$journey) {

                $journey = new \stdClass();
                $journey->name = $user->name ?? 'User';
                $journey->dob = $user->dob ?? date('Y-m-d');
                $journey->gender = $user->gender ?? 'male';
                $journey->pincode = $user->pincode;
                $journey->permanent_address = json_encode([]);
                $journey->contact_details = json_encode([]);
            }

            $insureMembers = Insure::where('proposalid', $userId)->get();

            $journeyMembers = JourneyUsers::where('proposalid', $userId)
                ->where('insureid', '!=', 0)
                ->get()
                ->keyBy('insureid');

            // ✅ FINAL MEMBERS ARRAY
            $members = $insureMembers->map(function ($insure) use ($journeyMembers) {

                $journey = $journeyMembers[$insure->id] ?? null;

                return (object) [
                    // ✅ ONLY NAME from journey
                    'name' => $journey->name ?? ucfirst($insure->name ?? 'Self'),

                    // 🔥 EVERYTHING ELSE from INSURE
                    'dob' => $insure->dob,
                    'gender' => $journey->gender ?? 'male', // safe
                    'height' => $journey->height ?? 170,
                    'weight' => $journey->weight ?? 70,

                    // 🔥 CRITICAL (do not touch)
                    'relation' => strtolower(trim($insure->name))
                ];
            });

            $relations = ['self', 'spouse', 'son', 'daughter', 'father', 'mother'];


            $nominee = JourneyUsers::where('proposalid', $userId)
                ->where('insureid', '0')
                ->first();

            if ($members->isEmpty()) {

                $members = collect([
                    (object) [
                        'name' => $user->name ?? 'User',
                        'dob' => $user->dob ?? date('Y-m-d'),
                        'gender' => $user->gender ?? 'male',
                        'relation' => 'self'
                    ]
                ]);
            }
            foreach ($members as $m) {
                if (empty($m->dob)) {
                    $m->dob = $user->dob; // fallback safe
                }
            }
            $pincodeData = AdityaBirlaPincode::where('PINCODE', $pincode)->first();
            $zone = $pincodeData->ZONE ?? 'Z002';
            $sumInsured = $coverage * 100000;
            $roomCategory = $addons['RRTO'] ?? 'UPTOSI';

            $memberArray = [];

            foreach ($members as $index => $m) {
                $relationCode = self::getRelationCode($m->relation, $config);
                $gender = strtoupper($m->gender);
                $genderCode = ($gender == 'MALE') ? 'M' : 'F';
                $salutation = ($gender == 'MALE') ? 'Mr' : 'Ms';

                $memberArray[] = [
                    "MemberNo" => $index + 1,
                    "Salutation" => $salutation,
                    "First_Name" => $m->name ?? '',
                    "Last_Name" => "",
                    "Gender" => $genderCode,
                    "DateOfBirth" => date('d/m/Y', strtotime($m->dob)),

                    "Relation_Code" => $relationCode,

                    "PrimaryMember" => (strtolower($m->relation) === 'self') ? 'Y' : 'N',

                    // ✅ IMPORTANT FIELDS (premium impact)
                    "height" => $m->height ?? 170,
                    "weight" => $m->weight ?? 70,
                    "Marital_Status" => "Single",

                    "productComponents" => [
                        [
                            "productComponentName" => "Conditions",
                            "productComponentValue" => "Non-Chronic"
                        ],
                        [
                            "productComponentName" => "SumInsured",
                            "productComponentValue" => (string) $sumInsured
                        ],
                        [
                            "productComponentName" => "Zone",
                            "productComponentValue" => $zone
                        ],
                        [
                            "productComponentName" => "RoomCategory",
                            "productComponentValue" => $roomCategory
                        ]
                    ],

                    "optionalCovers" => self::buildOptionalCovers($addons),

                    "MemberQuestionDetails" => [],
                    "MemberPED" => []
                ];
            }

            $permanentAddress = json_decode($journey->permanent_address, true) ?? [];
            $contactDetails = json_decode($journey->contact_details, true) ?? [];
            $clientCreation = [
                "salutation" => ($journey->gender == 'male') ? 'Mr' : 'Ms',
                "firstName" => $journey->kyc_name ?? $journey->name ?? '',
                "lastName" => "",
                "dateofBirth" => date('d/m/Y', strtotime($journey->dob)),
                "gender" => ($journey->gender == 'male') ? 'M' : 'F',
                "pinCode" => $user->pincode,
                "maritalStatus" => "Single",
                "nationality" => "Indian",
                "primaryEmailID" => $contactDetails['email'] ?? $user->email ?? '',
                "contactMobileNo" => $contactDetails['mobile'] ?? $user->mobile ?? '',
                "annualIncome" => "1500000",
                "homeAddressLine1" => $permanentAddress['address1'] ?? '',
                "homeAddressLine2" => $permanentAddress['address2'] ?? '',
                "homePinCode" => $permanentAddress['pincode'] ?? $user->pincode,
                "homeArea" => $permanentAddress['city'] ?? '',
                "sameAsHomeAddress" => "1",
                "homeContactMobileNo" => "",
                "mailingAddressLine1" => $permanentAddress['address1'] ?? '',
                "mailingAddressLine2" => $permanentAddress['address2'] ?? '',
                "mailingPinCode" => $permanentAddress['pincode'] ?? $user->pincode,
                "mailingArea" => $permanentAddress['city'] ?? '',
                "bankAccountType" => "02",
                "bankAccountNo" => "1234567890",
                "ifscCode" => "SBIN0008996",
                "GSTRegistrationStatus" => "Consumers",
                "IsEIAavailable" => 0,
                "ApplyEIA" => 0
            ];
            $memberTypeCode = self::getMemberTypeCode($members);

            $sumInsuredType = (count($members) > 1)
                ? "Family Floater"
                : "Individual";
            // dd([
//     'members_count' => count($memberArray),
//     'member_type' => $memberTypeCode,
//     'suminsured_type' => (count($memberArray) > 1) ? "Family Floater" : "Individual",
//     'members' => $memberArray
// ]);
            $payload = [
                "ClientCreation" => $clientCreation,

                "Quotation_Number" => "",
                "IsPayment" => "0",

                "PolicyCreationRequest" => [
                    "Product_Code" => $config['CREDENTIAL']['PRODUCT_CODE'],
                    "Plan_Code" => $params['plan'] ?? "MassMarket_Plus",

                    // ✅ IMPORTANT
                    "SumInsured_Type" => $sumInsuredType,

                    // ✅ NO WRONG VALUE
                    "Member_Type_Code" => $memberTypeCode,

                    "intermediaryCode" => $config['CREDENTIAL']['INTERMEDIARY_CODE'],
                    "Policy_Tanure" => (string) $tenure,
                    "QuoteDate" => now()->format('d/m/Y')
                ],

                "MemObj" => [
                    "Member" => $memberArray
                ],

                "ReceiptCreation" => new \stdClass()
            ];
            // Encrypt and send (your existing curl code)
            $plainText = json_encode($payload, JSON_UNESCAPED_SLASHES);
            SaveFile($plainText, "hf1");
            $key = $config['CREDENTIAL']['AESKEY'];
            $iv = random_bytes(16);
            $encrypted = openssl_encrypt($plainText, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
            $encryptedPayload = base64_encode($iv) . '.' . base64_encode($encrypted);
            $requestBody = json_encode(["EncryptedPayload" => $encryptedPayload]);

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://apimuat.abhicl.in/ABHI_NB/PreEnc/activeOne",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $requestBody,
                CURLOPT_HTTPHEADER => [
                    "Accept: application/json",
                    "Authorization: " . $config['CREDENTIAL']['AUTHORIZATION'],
                    "Content-Type: application/json"
                ],
                CURLOPT_SSL_VERIFYPEER => false
            ]);
            $response = curl_exec($curl);
            curl_close($curl);

            $responseData = json_decode($response, true);
            if (!isset($responseData['EncryptedResponse'])) {
                return ["error" => "Invalid response", "raw" => $response];
            }
            $parts = explode('.', $responseData['EncryptedResponse']);
            $responseIV = base64_decode($parts[0]);
            $encryptedData = base64_decode($parts[1]);
            $decrypted = openssl_decrypt($encryptedData, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $responseIV);
            $final = json_decode($decrypted, true);

            $quoteNo = $final['PolCreationRespons']['quoteNumber'] ?? $final['PolCreationRespons']['quotationNumber'] ?? null;
            if (!empty($quoteNo))
                SetCache('aditya_quote_' . $userId, $quoteNo);

            // ✅ Cache the member array for createPolicy
            Cache::put('aditya_member_array_' . $userId, $memberArray, now()->addHours(24));

            return $final;
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }


    public static function startKyc($data)
    {
        try {

            $response = Http::withHeaders([
                "Accept" => "application/json",
                "Authorization" => "06754239a8054cbea3398160fea63cdf",
                "Content-Type" => "application/json"
            ])
                ->withBody(json_encode($data), 'application/json')
                ->post("https://apimuat.abhicl.in/ABHI_MT_KYC/partner/start");

            return $response->json();

        } catch (\Exception $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    public static function getKycResult($transactionId)
    {
        try {

            $response = Http::withHeaders([
                "Accept" => "application/json",
                "Authorization" => "06754239a8054cbea3398160fea63cdf",
                "Content-Type" => "application/json"
            ])->post("https://apimuat.abhicl.in/ABHI_MT_KYC/partner/results", [
                        "transactionId" => $transactionId
                    ]);

            return $response->json();

        } catch (\Exception $e) {
            \Log::error('KYC Result API Error', [
                'error' => $e->getMessage()
            ]);

            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    public static function createPolicy($userId, $paymentData = [])
    {
        try {
            $config = getconstant('HEALTH.ADITYABIRLA_Max_Plus');
            $key = $config['CREDENTIAL']['AESKEY'];
            $auth = $config['CREDENTIAL']['AUTHORIZATION'];

            // Fetch data
            $user = User::find($userId);
            $journey = HealthJourney::where('userid', $userId)
                ->where('vid', $config['KEY'])
                ->first();
            if (!$journey)
                return ['error' => 'Journey data not found'];
            $proposerDetails = json_decode($journey->proposer_details, true) ?? [];

$occupation = $proposerDetails['occupation'] ?? '';

$natureOfDuty = $proposerDetails['nature_of_duty'] ?? '';

            $members = JourneyUsers::where('proposalid', $userId)
                ->where('insureid', '!=', 0)
                ->get();
            $nominee = JourneyUsers::where('proposalid', $userId)
                ->where('insureid', '0')
                ->first();
            if ($members->isEmpty())
                return ['error' => 'No members found. Complete proposalStepTwo first.'];

            $selectedAddons = json_decode($journey->addon, true) ?? [];
            $permanentAddress = json_decode($journey->permanent_address, true) ?? [];
            $contactDetails = json_decode($journey->contact_details, true) ?? [];

            // Plan details – force MassMarket
            $coverage = Cache::get('cache_coverage_' . $userId) ?? 5;
            $tenure = Cache::get('cache_tenure_' . $userId) ?? 1;
            $planCode = "MassMarket_Plus";   // ✅ as per vendor

            $sumInsured = $coverage * 100000;
            $zone = Cache::get('ab_zone_' . $userId) ?? 'Z003';
            $roomCategory = $selectedAddons['RRTO'] ?? 'UPTOSI';

            // ========== BUILD THE MEMBER ARRAY (once) ==========
            $memberArray = [];
            $allQuestionIds = [
                "1362470729092023",
                "1362471229092023",
                "1362471529092023",
                "1362471829092023",
                "1362472129092023",
                "1362472429092023",
                "1362474029092023",
                "1362472729092023",
                "1362474329092023",
                "1362474629092023",
                "1362474929092023",
                "1362475229092023",
                "1362475529092023",
                "1362475829092023",
                "1362476129092023",
                "1362476429092023",
                "1362476729092023",
                "1362477729092023",
            ];
            $diseaseToPedCode = [
                "1362475229092023" => "PE003", // Diabetes
                "1362474329092023" => "PE002", // BP
                "1362474629092023" => "PE009", // Asthma
                "1362474929092023" => "PE004", // Thyroid
                "1362472429092023" => "PE1001", // Heart
                "1362474029092023" => "PE1001", // Heart
                "1362475529092023" => "PE999", // Other
            ];
            foreach ($members as $index => $member) {
                // ✅ RESET ARRAY (IMPORTANT)
                $memberQuestions = [];
                $memberPED = [];

                // ✅ PED MAP BANANA (YAHI DALNA HAI 🔥)
                $pedMap = [];

                if (!empty($member->ped)) {

    $userPed = json_decode($member->ped, true);

    if (is_array($userPed)) {

        foreach ($userPed as $pedData) {

            $questionId = $pedData['question_id'] ?? null;

            if (
                $questionId &&
                strtolower($pedData['answer'] ?? '') === 'yes'
            ) {

                $pedMap[$questionId] = [
                    'answer' => 'yes',
                    'extra' => $pedData['extra'] ?? []
                ];
            }
        }
    }
}

                foreach ($allQuestionIds as $qId) {

                    $ped = $pedMap[$qId] ?? null;

                    if ($ped && strtolower($ped['answer']) === 'yes') {

                        $extra = $ped['extra'] ?? [];

                        $diagnosisDate = !empty($extra['diagnosis_date'])
                            ? date('d/m/Y', strtotime('01/' . $extra['diagnosis_date']))
                            : '';

                        $consultationDate = !empty($extra['consultation_date'])
                            ? date('d/m/Y', strtotime('01/' . $extra['consultation_date']))
                            : '';

                        // ✅ QUESTION ARRAY
                        $memberQuestions[] = [
                            "QuestionCode" => $qId,
                            "Answer" => "1",
                            "Remarks" => "",
                            "exactDiagnosis" => $extra['disease_name'] ?? '',
                            "dateOfDiagnosis" => $diagnosisDate,
                            "lastDateConsultation" => $consultationDate,
                            "detailsOfTreatmentGiven" => $extra['treatment_details'] ?? ''
                        ];

                        // ✅ PED ARRAY (NEW)
                        $pedCode = $diseaseToPedCode[$qId] ?? null;

                        if ($pedCode) {
                            $memberPED[] = [
                                "PedCode" => $pedCode
                            ];
                        }

                    } else {

                        $memberQuestions[] = [
                            "QuestionCode" => $qId,
                            "Answer" => "0",
                            "Remarks" => "",
                            "exactDiagnosis" => "",
                            "dateOfDiagnosis" => "",
                            "lastDateConsultation" => "",
                            "detailsOfTreatmentGiven" => ""
                        ];
                    }
                }

                // $relationCode = $config['RELATIONCODE'][strtolower($member->relation)] ?? 'R001';
                $relation = strtolower(trim($member->relation));
                $relationCode = self::getRelationCode($relation, $config);
                $isPrimary = ($member->relation == 'self') ? 'Y' : 'N';
                $genderUpper = strtoupper($member->gender);
                $genderCode = ($genderUpper == 'MALE') ? 'M' : 'F';
                $salutation = ($genderUpper == 'MALE') ? 'Mr' : 'Ms';

                // Height conversion (feet/inches to cm)
              // Height conversion (feet + inch -> cm)
$feet = (float) ($member->height ?? 0);
$inch = (float) ($member->inch ?? 0);

$heightCm = round((($feet * 12) + $inch) * 2.54);

// Weight
$weight = (float) ($member->weight ?? 70);

                // Optional covers from addons
                $optionalCovers = [];
                foreach ($selectedAddons as $addonKey => $addonValue) {
                    $addonData = $config['ADDON'][$addonKey] ?? null;
                    if (!$addonData)
                        continue;
                    if (is_string($addonData)) {
                        $optionalCovers[] = ["optionalCoverName" => $addonData, "optionalCoverValue" => "1", "CoverPartCode" => $addonKey, "CoverPartValue" => ""];
                    } elseif (is_array($addonData) && isset($addonData['OPTIONS'])) {
                        $selectedOption = is_array($addonValue) ? key($addonValue) : $addonValue;
                        $optionalCovers[] = ["optionalCoverName" => $addonData['TITLE'], "optionalCoverValue" => "1", "CoverPartCode" => $selectedOption, "CoverPartValue" => ""];
                    }
                }

                // Nominee details only for primary member
                $nomineeFirstName = ($isPrimary == 'Y' && $nominee) ? ($nominee->name ?? '') : '';
                $nomineeContact = ($isPrimary == 'Y' && $nominee) ? ($nominee->mobile ?? '9876543210') : '';
                // Map nominee relation code (remove '(nominee)' suffix)
                $nomineeRelationRaw = strtolower(str_replace('(nominee)', '', $nominee->relation ?? ''));
                $nomineeRelationCode = $config['RELATIONCODE'][$nomineeRelationRaw] ?? 'R002';

                $memberArray[] = [
                    "MemberNo" => $index + 1,
                    "Salutation" => $salutation,
                    "First_Name" => $member->name ?? '',
                    "Last_Name" => "",
                    "Gender" => $genderCode,
                    "DateOfBirth" => date('d/m/Y', strtotime($member->dob)),
                    "Relation_Code" => $relationCode,
                    "Marital_Status" => ($member->relation == 'spouse') ? 'Married' : '',

                    // ✅ FIXED PART
                    "height" => $heightCm,
                    "weight" => $weight,
                    "PrimaryMember" => $isPrimary,
                    "optionalCovers" => $optionalCovers,
                    "productComponents" => [
                        ["productComponentName" => "SumInsured", "productComponentValue" => (string) $sumInsured],
                        ["productComponentName" => "Zone", "productComponentValue" => $zone],
                        ["productComponentName" => "Conditions", "productComponentValue" => "Non-Chronic"],
                        ["productComponentName" => "RoomCategory", "productComponentValue" => $roomCategory]
                    ],
                    "occupation" => $occupation,
"NatureOfDuty" => $natureOfDuty,
                    "Designation" => "",
                    "MemberPED" => array_values(array_unique($memberPED, SORT_REGULAR)),
                    "MemberQuestionDetails" => $memberQuestions,
                    "personalHabitDetail" => [],
                    "Nominee_First_Name" => $nomineeFirstName,
                    "Nominee_Last_Name" => "",
                    "Nominee_Contact_Number" => $nomineeContact,
                    "Nominee_Relationship_Code" => "R002"
                ];
            }

            // ========== STEP 1: GENERATE A FRESH QUOTE ==========
            $quotePayload = [
                "ClientCreation" => [
                    "salutation" => ($journey->gender == 'male') ? 'Mr' : 'Ms',
                    "firstName" => $journey->kyc_name ?? $journey->name ?? 'Test',
                    "lastName" => "",
                    "dateofBirth" => date('d/m/Y', strtotime($journey->dob ?? '1990-01-01')),
                    "gender" => ($journey->gender == 'male') ? 'M' : 'F',
                    "pinCode" => $user->pincode,
                    "maritalStatus" => "Single",
                    "nationality" => "Indian",
                    "primaryEmailID" => $user->email ?? 'test@test.com',
                    "contactMobileNo" => $user->mobile ?? '9999999999',
                    "annualIncome" => "500000",
                    "homeAddressLine1" => $permanentAddress['address1'] ?? 'Address',
                    "homePinCode" => $permanentAddress['pincode'] ?? $user->pincode
                ],
                "PolicyCreationRequest" => [
                    "Product_Code" => $config['CREDENTIAL']['PRODUCT_CODE'],
                    "Plan_Code" => $planCode,   // "MassMarket"
                    "SumInsured_Type" => (count($memberArray) > 1) ? "Family Floater" : "Individual",
                    "Member_Type_Code" => "AH01",
                    "intermediaryCode" => $config['CREDENTIAL']['INTERMEDIARY_CODE'],
                    "Policy_Tanure" => (string) $tenure,
                    "QuoteDate" => now()->format('d/m/Y')
                ],
                "MemObj" => ["Member" => $memberArray],
                "ReceiptCreation" => new \stdClass()
            ];

            // Send quote request
            $plainText = json_encode($quotePayload, JSON_UNESCAPED_SLASHES);
            $iv = random_bytes(16);
            $encrypted = openssl_encrypt($plainText, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
            $encryptedPayload = base64_encode($iv) . '.' . base64_encode($encrypted);
            $requestBody = json_encode(["EncryptedPayload" => $encryptedPayload]);

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => "https://apimuat.abhicl.in/ABHI_NB/PreEnc/activeOne",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $requestBody,
                CURLOPT_HTTPHEADER => [
                    "Accept: application/json",
                    "Authorization: " . $auth,
                    "Content-Type: application/json"
                ],
                CURLOPT_SSL_VERIFYPEER => false
            ]);
            $response = curl_exec($ch);
            curl_close($ch);

            $responseData = json_decode($response, true);
            if (!isset($responseData['EncryptedResponse'])) {
                return ['error' => 'Quote generation failed', 'raw' => $response];
            }
            $parts = explode('.', $responseData['EncryptedResponse']);
            $responseIV = base64_decode($parts[0]);
            $encryptedData = base64_decode($parts[1]);
            $decrypted = openssl_decrypt($encryptedData, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $responseIV);
            $quoteResult = json_decode($decrypted, true);
            $quoteNumber = $quoteResult['PolCreationRespons']['quoteNumber'] ?? $quoteResult['PolCreationRespons']['quotationNumber'] ?? null;
            if (!$quoteNumber) {
                Log::error('Quote generation failed', ['response' => $quoteResult]);
                return ['error' => 'Could not generate quote'];
            }
            $memberTypeCode = self::getMemberTypeCode($members);

            $sumInsuredType = (count($members) > 1)
                ? "Family Floater"
                : "Individual";
            // ========== STEP 2: BUILD FINAL POLICY REQUEST (reuse same member array) ==========
            $clientCreation = [
                "salutation" => ($journey->gender == 'male') ? 'Mr' : 'Ms',
                "firstName" => $journey->kyc_name ?? $journey->name ?? '',
                "lastName" => "",
                "dateofBirth" => date('d/m/Y', strtotime($journey->dob)),
                "gender" => ($journey->gender == 'male') ? 'M' : 'F',
                "pinCode" => $user->pincode ?? '',
                "maritalStatus" => "Single",
                "occupation" => "O085",
                "nationality" => "Indian",
                "primaryEmailID" => $contactDetails['email'] ?? $user->email ?? '',
                "contactMobileNo" => $contactDetails['mobile'] ?? $user->mobile ?? '',
                "annualIncome" => "500000",
                "homeAddressLine1" => $permanentAddress['address1'] ?? '',
                "homeAddressLine2" => $permanentAddress['address2'] ?? '',
                "homePinCode" => $permanentAddress['pincode'] ?? $user->pincode,
                "homeArea" => $permanentAddress['city'] ?? '',
                "sameAsHomeAddress" => "1",
                "homeContactMobileNo" => "",
                "mailingAddressLine1" => $permanentAddress['address1'] ?? '',
                "mailingAddressLine2" => $permanentAddress['address2'] ?? '',
                "mailingPinCode" => $permanentAddress['pincode'] ?? $user->pincode,
                "mailingArea" => $permanentAddress['city'] ?? '',
                "bankAccountType" => "02",
                "bankAccountNo" => "1234567890",
                "ifscCode" => "SBIN0008996",
                "GSTRegistrationStatus" => "Consumers",
                "IsEIAavailable" => 0,
                "ApplyEIA" => 0
            ];

            $memberCount = $members->count();
            $sumInsuredType = $sumInsuredType;
            $memberTypeCode = $memberTypeCode;
            // if ($memberCount == 2) $memberTypeCode = 'AH14';
            // elseif ($memberCount == 3) $memberTypeCode = 'AH19';
            // elseif ($memberCount == 4) $memberTypeCode = 'AH24';

            $kycTransactionId = GetCache('ab_txn_' . $userId) ?? '';

            $policyRequest = [
                "Quotation_Number" => $quoteNumber,
                "Product_Code" => $config['CREDENTIAL']['PRODUCT_CODE'],
                "Plan_Code" => $planCode,
                "SumInsured_Type" => $sumInsuredType,
                "Policy_Tanure" => (string) $tenure,
                "Member_Type_Code" => $memberTypeCode,
                "intermediaryCode" => $config['CREDENTIAL']['INTERMEDIARY_CODE'],
                "intermediaryBranchCode" => $config['CREDENTIAL']['BRANCH_CODE'] ?? '',
                "leadID" => "LEAD-" . $userId,
                "BusinessType" => "New Business",
                "Source_Name" => $config['CREDENTIAL']['SOURCE_NAME'] ?? 'Apollo247',
                "IsPayment" => "1",
                "QuoteDate" => now()->format('d/m/Y'),
                "CKYC_Flag" => "N",
                "CKYC_Number" => "",
                "Hyper_verge_OPD_Status" => "Y",
                "Digi_Locker_Verified" => "Y",
                "KYC_Transition_Id" => $kycTransactionId,
                "Portal_PEP" => "N",
                "ifPEP" => "N",
                "ifAML" => "N",
                "ifFaceMatch" => "N",
                "EmployeeDiscount" => "0",
                "Discount_Lieu_Commission" => 0,
                "EMI_Eligible" => "N",
                "EMI_Type" => "",
                "uidNo" => "",
                "panNo" => $journey->pan ?? ''
            ];

            $receiptCreation = [
                "officeLocation" => "Mumbai",
                "modeOfEntry" => "DIRECT",      // ✅ uppercase as per vendor
                "cdAcNo" => "",
                "expiryDate" => "",
                "payerType" => "Proposer",      // ✅ changed from Customer
                "payerCode" => "",
                "paymentBy" => "Customer",
                "paymentByName" => $journey->kyc_name ?? $journey->name ?? '',
                "paymentByRelationship" => "R001",
                "collectionAmount" => (string) ($paymentData['amount'] ?? 0),
                "collectionRcvdDate" => now()->format('d/m/Y'),
                "collectionMode" => "Online Collections",
                "remarks" => "",
                "instrumentNumber" => $paymentData['txn_id'] ?? '',
                "instrumentDate" => now()->format('d/m/Y'),
                "bankName" => "",
                "branchName" => "",
                "bankLocation" => "",
                "micrNo" => "",
                "chequeType" => "",
                "ifscCode" => "",
                "PaymentGatewayName" => "Juspay",
                "TerminalID" => ""
            ];

            $fullPayload = [
                "ClientCreation" => $clientCreation,
                "PolicyCreationRequest" => $policyRequest,
                "MemObj" => ["Member" => $memberArray],   // reuse same member array
                "ReceiptCreation" => $receiptCreation
            ];

            // Encrypt and send final policy request
            $plainText = json_encode($fullPayload, JSON_UNESCAPED_SLASHES);
            SaveFile($plainText, "fullquoteaditya_birla77");
            $iv = random_bytes(16);
            $encrypted = openssl_encrypt($plainText, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
            $encryptedPayload = base64_encode($iv) . '.' . base64_encode($encrypted);
            $requestBody = json_encode(["EncryptedPayload" => $encryptedPayload]);

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://apimuat.abhicl.in/ABHI_NB/PreEnc/activeOne",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $requestBody,
                CURLOPT_HTTPHEADER => [
                    "Accept: application/json",
                    "Authorization: " . $auth,
                    "Content-Type: application/json"
                ],
                CURLOPT_SSL_VERIFYPEER => false
            ]);
            $response = curl_exec($curl);
            curl_close($curl);

            $responseData = json_decode($response, true);
            if (!isset($responseData['EncryptedResponse'])) {
                return ['error' => 'Policy creation API failed', 'raw' => $response];
            }

            $parts = explode('.', $responseData['EncryptedResponse']);
            $responseIV = base64_decode($parts[0]);
            $encryptedData = base64_decode($parts[1]);
            $decrypted = openssl_decrypt($encryptedData, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $responseIV);
            $finalResponse = json_decode($decrypted, true);

            $policyNumber = $finalResponse['PolCreationRespons']['policyNumber'] ?? null;
            $receiptNumber = $finalResponse['ReceiptCreationResponse']['ReceiptNumber'] ?? null;

            if ($policyNumber) {
                $journey->portdata = json_encode([
                    'policy_number' => $policyNumber,
                    'receipt_number' => $receiptNumber,
                    'proposal_number' => $finalResponse['PolCreationRespons']['proposalNumber'] ?? null,
                    'quote_number' => $quoteNumber
                ]);
                $journey->save();
            }

            return [
                'success' => true,
                'policyNumber' => $policyNumber,
                'receiptNumber' => $receiptNumber,
                'fullResponse' => $finalResponse
            ];

        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
    public static function fetchPolicyDocument($policyNumber)
    {
        try {
            $config = getconstant('HEALTH.ADITYABIRLA');

            // Generate unique request IDs
            $uniqueId = uniqid() . '-' . rand(1000, 9999) . '-' . time();
            $currentDateTime = now()->format('d/m/Y H:i:s');

            // Build the request payload
            $payload = [
                "Metadata" => [
                    "Sender" => [
                        "LogicalID" => "SSS",
                        "TaskID" => "Policy",
                        "ReferenceID" => $uniqueId,
                        "CreationDateTime" => $currentDateTime,
                        "TODID" => $uniqueId
                    ]
                ],
                "FetchDocRequest" => [
                    [
                        "CategoryID" => "1009",
                        "DocumentID" => "",
                        "ReferenceID" => "",
                        "FileName" => "",
                        "Description" => "",
                        "DataClassParam" => [
                            [
                                "DocSearchParamId" => "15",
                                "Value" => "PS_04"
                            ],
                            [
                                "DocSearchParamId" => "2",
                                "Value" => $policyNumber
                            ]
                        ]
                    ]
                ],
                "SourceSystemName" => "SSS",
                "SearchOperator" => "AND"
            ];
            SaveFile(
                json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
                "OMNIDOC_REQUEST"
            );
            // Basic Auth credentials
            $username = "esb_ewlns";
            $password = "esb@ebwlns";
            $auth = base64_encode($username . ":" . $password);

            // Log request for debugging
            \Log::info('OmniDocs Request', [
                'policy_number' => $policyNumber,
                'payload' => $payload
            ]);

            // Make API call
            $response = Http::withHeaders([
                "Accept" => "application/json",
                "Content-Type" => "application/json",
                "Authorization" => "Basic " . $auth
            ])->timeout(30)->post("https://apimuat.abhicl.in/ABHI_OmniDocs/enc/OmniDocsFetchDetails", $payload);

            $result = $response->json();
            dd($result);
            // SaveFile($result, "pdf");
            // Log response for debugging
            \Log::info('OmniDocs Response', [
                'policy_number' => $policyNumber,
                'response' => $result
            ]);

            // Check if we got a valid response with byte array
            if (
                isset($result['FetchDocResponse'][0]['ByteArray']) &&
                !empty($result['FetchDocResponse'][0]['ByteArray'])
            ) {

                return [
                    'success' => true,
                    'byteArray' => $result['FetchDocResponse'][0]['ByteArray'],
                    'fileName' => $result['FetchDocResponse'][0]['FileName'] ?? "policy_{$policyNumber}.pdf",
                    'globalId' => $result['FetchDocResponse'][0]['GlobalId'] ?? null,
                    'omniDocImageIndex' => $result['FetchDocResponse'][0]['OmniDocImageIndex'] ?? null
                ];
            }

            // Check for error
            if (
                isset($result['FetchDocResponse'][0]['Error'][0]['ErrorCode']) &&
                $result['FetchDocResponse'][0]['Error'][0]['ErrorCode'] != '0'
            ) {
                return [
                    'success' => false,
                    'error' => $result['FetchDocResponse'][0]['Error'][0]['ErrorMessage'] ?? 'Unknown error'
                ];
            }

            return [
                'success' => false,
                'error' => 'No document found for this policy number',
                'raw_response' => $result
            ];

        } catch (\Exception $e) {
            \Log::error('OmniDocs Fetch Error', [
                'policy_number' => $policyNumber,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Download and decode policy document
     */
    public static function downloadPolicyDocument($policyNumber)
    {
        $result = self::fetchPolicyDocument($policyNumber);

        if (!$result['success']) {
            return [
                'success' => false,
                'error' => $result['error']
            ];
        }

        // Decode the base64 byte array
        $pdfContent = base64_decode($result['byteArray']);

        if (!$pdfContent || strlen($pdfContent) < 100) { // Basic validation
            return [
                'success' => false,
                'error' => 'Failed to decode PDF content or PDF is empty'
            ];
        }

        return [
            'success' => true,
            'content' => $pdfContent,
            'fileName' => $result['fileName'],
            'mimeType' => 'application/pdf'
        ];
    }

    // ====================== JUSPAY PAYMENT METHODS ======================
    public static function initiatePayment($userId, $premium = null)
    {
        try {
            $config = getconstant('HEALTH.ADITYABIRLA_Max_Plus');
            $user = User::find($userId);

            if (!$user) {
                return ['success' => false, 'error' => 'User not found'];
            }

            // ✅ If premium not passed, try to get from cache
            if (!$premium) {
                $premium = Cache::get('cache_premium_' . $userId);
            }

            // ✅ One more fallback - get from journey
            if (!$premium || $premium <= 0) {
                $journey = HealthJourney::where('userid', $userId)
                    ->where('vid', $config['KEY'])
                    ->first();

                if ($journey && $journey->addon) {
                    $addonData = json_decode($journey->addon, true);
                    $premium = $addonData['premium'] ?? $addonData['premium_with_addon'] ?? 0;
                }
            }

            // ✅ Final validation
            if (!$premium || $premium <= 0) {
                return ['success' => false, 'error' => 'Unable to calculate premium amount'];
            }

            // ✅ Log premium before payment
            \Log::info('Juspay Payment Initiation', [
                'user_id' => $userId,
                'premium_amount' => (int) $premium,
                'order_creation_time' => now()->format('Y-m-d H:i:s')
            ]);

            $orderId = 'ORD-' . time() . '-' . $userId;
            $proposalNumber = 'PROP-' . time() . rand(1000, 9999);

            $payload = [
                "order_id" => $orderId,
                "amount" => (int) $premium,  // ✅ Now this will be correct
                "currency" => "INR",
                "customer_id" => (string) $userId,
                "customer_email" => $user->email,
                "customer_phone" => $user->mobile,
                "payment_page_client_id" => "adityabirlahealth",
                "merchant_id" => $config['JUSPAY_MERCHANT_ID'],
                "metadata.JUSPAY:gateway_reference_id" => "Seller_Portal",
                "metadata.webhook_url" => url('/api/adityabirla-health/payment-webhook'),
                "udf1" => "Online Portal",
                "udf2" => "LEADID - " . $userId,
                "udf3" => "PROP - " . $proposalNumber,
                "udf4" => $user->email,
                "udf5" => $user->mobile,
                "udf6" => "JSPSKIP",
                "udf7" => $proposalNumber,
                "return_url" => "https://uat.digibima.com/health/vendors/adityabirlamax/journey",
                "description" => "Health Insurance Premium",
                "action" => "paymentPage"
            ];
            $debugRequest = json_encode([
                'step' => 'JUSPAY REQUEST',
                'url' => $config['JUSPAY_BASE_URL'] . "/session",
                'payload' => $payload
            ], JSON_PRETTY_PRINT);

            SaveFile($debugRequest, "JUSPAY_REQ_" . $orderId);

            // ================= DEBUG =================
            Log::info('PAYMENT DEBUG', [
                'userId' => $userId,
                'premium' => $premium,
                'payload' => $payload
            ]);

            // ================= API CALL =================
            $response = Http::withHeaders([
                "Authorization" => "Basic " . $config['JUSPAY_API_KEY'],
                "Content-Type" => "application/json"
            ])->post($config['JUSPAY_BASE_URL'] . "/session", $payload);

            $result = $response->json();

            // ================= SUCCESS =================
            if (isset($result['status']) && $result['status'] == 'NEW') {

                Cache::put('juspay_order_' . $orderId, [
                    'user_id' => $userId,
                    'amount' => $premium,
                    'proposal_number' => $proposalNumber,
                    'juspay_order_id' => $result['id']
                ], now()->addHours(24));

                return [
                    'success' => true,
                    'status' => $result['status'],
                    'id' => $result['id'],
                    'order_id' => $result['order_id'],
                    'payment_links' => $result['payment_links'],
                    'sdk_payload' => $result['sdk_payload'],
                    'order_expiry' => $result['order_expiry']
                ];
            }

            Log::error('Juspay session creation failed', ['response' => $result]);

            return [
                'success' => false,
                'error' => $result['error'] ?? 'Payment session creation failed'
            ];

        } catch (\Exception $e) {

            Log::error('Juspay initiatePayment exception', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    public static function verifyPayment($orderId)
    {
        try {
            $config = getconstant('HEALTH.ADITYABIRLA_Max_Plus');

            $response = Http::withHeaders([
                "Authorization" => "Basic " . $config['JUSPAY_API_KEY'],
                "Content-Type" => "application/json"
            ])->get($config['JUSPAY_BASE_URL'] . "/orders/" . $orderId);

            $result = $response->json();
            Log::info('JUSPAY RESPONSE', [
                'order_id' => $orderId,
                'response' => $result
            ]);

            // $debugResponse = json_encode([
//     'step' => 'JUSPAY RESPONSE',
//     'order_id' => $orderId,
//     'response' => $result
// ], JSON_PRETTY_PRINT);

            // SaveFile($debugResponse, "JUSPAY_RES_" . $orderId);

            if (!$result || !isset($result['status'])) {
                return [
                    'success' => false,
                    'error' => 'Invalid response from Juspay',
                    'raw' => $result
                ];
            }

            return [
                'success' => true,
                'data' => $result // ✅ FULL response
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}