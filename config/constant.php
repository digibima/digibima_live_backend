<?php

use App\Models\User;

$SITE_URL = env('APP_URL', '');
$BASE_URL = $SITE_URL;
// $BASE_URL = $SITE_URL . 'public/';
// $BASE_URL              = 'http://127.0.0.1:80/insurance/public/';
return [
    'SITE_URL' => $SITE_URL,
    'BASE_URL' => $BASE_URL,
    'IMAGE' => $BASE_URL . 'upload/',
    // front
    'FRONT_URL' => $BASE_URL . 'front/',
    'PDFDATA' => [
        'care' => [
            'Company' => 'Care Health Insurance Limited',
            'Address' => 'Vipul Tech Square, Tower C, 3rd Floor, Golf Course Road, Sector-43, Gurugram-122009 (Haryana)',
            'IRDAI' => '148',
            'CIN' => 'U66000DL2007PLC161503',
        ],
    ],
    'CONFIG' => [
        'TESTMOTOR' => 'TEST_MOTOR',
        'TESTHEALTH' => 'TEST_HEALTH',
        'TESTMASTER' => 'TEST_MASTER',
    ],
    'FOOTER' => [
        'FOOTER' => '1',
    ],
    'MONEY' =>
        [
            'Lac' => 'Lac',
            'Thousand' => 'Thousand',
            'CR' => 'CR',
        ],
    'DAY' => [
        'Years' => 'Years',
        'Year' => 'Year',
        'Months' => 'Months',
        'Month' => 'Month',
        'Weeks' => 'Weeks',
        'Week' => 'Week',
        'Days' => 'Days',
        'Day' => 'Day',
    ],
    'MESSAGE' =>
        [
            'ONPAYMENT' => 'Your Policy Created Successfully',
            'ONLOGIN' => 'Last login',
            'ONLOGOUT' => 'Last logout',
            'ONPOLICYFAILURE' => 'Creating policy failed'
        ],
    'CAREDISEASE' => [
        '11' => ' Cancer or Tumor of any kind?',
        '12' => 'Any heart related or circulatory system disorders?',
        '13' => 'Hypertension/High Blood Pressure/Cholesterol disorder?',
        '14' => 'Breathing/Respiratory issues (E.g.TB,Asthma,etc.) ?',
        '15' => 'Endocrine disorders(E.g.Thyroid related disoders,etc)?',
        '16' => 'Diabetes/High blood sugar?',
        '17' => 'Muscles or Nervous system related disorder or Stroke/Epilepsy/ Paralysis of other brain related disorders?',
        '18' => 'Liver/gallbladder or any other Gastro-Intestinal Disease?',
        '19' => 'Kidney failure/Stone/ Dialysis/ Gynaecological/ Prostate disease?',
        '110' => 'Auto-immune or Blood related disorders (Rheumatoid arthritis, Thalessemia,etc.)?',
        '111' => 'Any Congenital Disorder?',
        '112' => 'HIV/ AIDS/ STD?',
        '113' => 'Any other disease/health adversity/injury/ condition/treatment not mentioned above?',
        '114' => 'Has any of the Proposed to be Insured consulted/taken treatment or recommended to take investigations/ medication/ surgery other than for childbirth/ minor injuries?',
        '115' => 'Has any of the Proposed to be Insured been hospitalized or has been under any prolonged treatment for any illness/injury or has undergone surgery other than for childbirth/minor injuries?',
        '21' => 'Has any of the new person(s) to be insured ever filed a claim with their current / previous insurer?',
        '22' => 'Has any proposal for Health Insurance of the new person(s) to be insured, been declined, cancelled or charged a higher premium ?',
        '23' => 'Is any of the person(s) to be insured, already covered under any other health insurance policy of Care Health Insurance ?',
        '24' => 'Already covered',
        '25' => 'Have any of the above mentioned person(s) to be insured been diagnosed / hospitalized for any illness / injury during the last 48 months?',
    ],
    'LIFESTYLE' => [
        '31' => 'Personal habit of smoking/ alcohol/gutkha/ tobacco/paan?',
        // '32' => 'Bidis',
        // '33' => 'Gutkha or Pan',
        // '34' => 'Whisky',
        // '35' => 'Wine',
        // '36' => 'Beer',
        // '37' => 'Any other type of Drugs',
    ],
    'INSURANCE' => [
        'CARE' => '100',
        '100' => 'CARE',
        'SHRIRAM' => '102',
        '200' => 'SHRIRAM'
    ],
    'MODEL' => [
        'BIKE' => 'MOT-PRD-002',
        'CAR' => 'MOT-PRD-001',
        'PCCV' => 'MOT-PRD-005',
        'GCCV' => 'MOT-PRD-003'
    ],
    'HEALTH' => [
        'COVERAGE' => [5, 7, 10, 15, 20, 25, 50, 75, 100, 'UNLIMITED'],
        'TENURE' => [1, 2, 3],
        // 'TENURE' => [1, 2, 3, 4, 5],
        'CARESUPREME' =>
            [
                'KEY' => '100',
                'CREDENTIAL' => [
                    'PAERTNERID' => '1001101',
                    'APPID' => '1001101',
                    'TIMESTAMP' => '1736157011820',
                    'SECURITYKEY' => 'KzZMOHQrUGYwZFJZZThtZkFWWEE1Zm9lT05ZWTZHV2x4bDM5T05nb09LL3diWmlDckdmcFh1ZmJSNW5oVDhkdw==',
                    'AESKEY' => 'z5yK1lw7XYt6YKdP7Pne2Jw3zRkMAziH',
                    'IV' => 'i0kbCAlFTlDXshYV',
                    'APPLICATIONCODE' => 'PARTNERAPP',
                    'SIGNATURE' => 'g0erUaIKutEFSxQtm2zHjJo',
                    'AGENTID' => 'agentId'
                ],
                'POLICYNAME' => 'CARE SUPREME(HEALTH)',
                'RELATIONCODE' => [
                    'SELF' => 'R001',
                    'HUSBAND' => 'SPSE',
                    'WIFE' => 'SPSE',
                    'SON' => 'SONM',
                    'DAUGHTER' => 'UDTR',
                    'FATHER' => 'FATH',
                    'MOTHER' => 'MOTH',
                    'FATHERINLAW' => 'FLAW',
                    'MOTHERINLAW' => 'MLAW'
                ],
                'ADDON' => [
                    'aa' => 'Air Ambulance',
                    'wb' => 'Wellness Benefit',
                    'ncb' => 'Cumulative Bonus Super',
                    'ahc' => 'Annual Health Check-up',
                    'ic' => 'Instant Cover',
                    // 'cs' => 'Claim Shield',
                    'opd' => 'Care OPD',
                    'pwpm' => 'PED Wait Period Modification',
                    'befit' => 'Befit Benefit',
                    'uc' => 'Unlimited Care'
                ],
            ],
        'ULTIMATECARE' => [
            'KEY' => '102',
            'ADDON' => [
                'pp' => 'Premium Payback',
                'uec' => 'Unlimited e-Consultation',
                'wb' => 'Wellness Benefit',
                'gpc' => 'Grace Period Coverage',
                'tm4' => 'Tenure Multiplier 4Year-Ultimate Care',
                'tm5' => 'Tenure Multiplier 5Year-Ultimate Care',
                'tm2' => 'Tenure Multiplier 2Year-Ultimate Care',
                'tm3' => 'Tenure Multiplier 3Year-Ultimate Care',
                'ib' => 'Infinity Bonus',
                'ahc' => 'Annual Health Check-up',
                'cs' => 'Claim Shield',
                'opd' => 'Care OPD',
                'ic' => 'Instant Cover',
                'bfp' => 'Be-Fit Plus',
                'uc' => 'Unlimited Care',
                'opc' => 'Out-Patient Consultations',
                'ped1' => 'PED Wait Period Modification 1year',
                'ped2' => 'PED Wait Period Modification 2year',
                'opdp' => 'OPD-PLUS'
            ],
        ],
        'TATAAIG' => [
            'KEY' => '104',
            'ADDON' => [
                'ic' => 'Instant Cover',
                'cs' => 'Claim Shield',
                'opd' => 'Care OPD',
                'pwpm' => 'PED Wait Period Modification',
                // 'befit' => 'befit'
            ],
        ],
        'BAJAJ' => [
            'KEY' => '106',
            'ADDON' => [
                'addon23' => 'Loss of Income',
                'addon24' => 'Major Illness and Accident Multiplie',
                'addon25' => 'International Cover - Emergency Care only',
                'phe' => 'Post Hospitalization Expenses',
                'prhe' => 'Pre Hospitalization Expenses',
                'sdwp' => 'Specific Disease Waiting Period',
                'RR' => 'Room Rent',
                'ped' => 'PED Wait Period Modification'
            ],
            'CREDENTIAL' => [
                'ID' => 'webservice@digibima.com',
                'IMDCODE' => '10105697',
                'DEPTCODE' => '1401',
                'PASSWORD' => 'Digibima@123',
            ]
        ],
        'ADITYABIRLA' => [
            'KEY' => '108',
            'ADDON' => [
                'IPTT' => 'In-patient Hospitalization',
                'RACV' => 'Road Ambulance Cover (per hospitalization)',
                'MTAT' => 'Modern Procedures/Treatments',
                'HIVC' => 'HIV / AIDS and STD Cover',
                'MITR' => 'Mental Illness Hospitalization',
                'OBTR' => 'Obesity Treatment',
                'PRHM' => 'Pre-Hospitalization Expenses',
                'POHM' => 'Post-Hospitalization Expenses',
                'DCHS' => 'Domiciliary Hospitalization',
                'HCT' => 'Home Health Care',
                'AIPH' => 'AYUSH Treatment',
                'ORDR' => 'Organ Donor Expenses',
                'SUPR' => 'Super Reload',
                'HLTHA' => 'Health AssessmentTM',
                'HLTHRET' => 'HealthReturnsTM',
                'RISW' => 'Reduction in Specific Disease waiting period',
                'RIPW' => [
                    'TITLE' => 'Reduction in Pre-Existing Disease waiting period',
                    'OPTIONS' => [
                        '3TO1' => '3 Years to 1 Year',
                        '3TO2' => '3 Years to 2 Years'
                    ]
                ],
                'WNME' => 'Claim Protect (Non-Medical Expense Waiver)',
                'RRTO' => [
                    'TITLE' => 'Room Rent Type Options',
                    'OPTIONS' => [
                        'SH' => 'Shared Accommodation',
                        'YSY' => 'Single Private Room'
                    ]
                ],
                'PCDED' => [
                    'TITLE' => 'Per Claim Deductible',
                    'OPTIONS' => [
                        '15000' => '15000',
                        '25000' => '25000'
                    ]
                ],
                'PPNDISC' => 'Preferred Provider Network (PPN) Discount -10% co-pay of claim is outside PPN',
                'CIL' => 'Critical Illness cover',
                'PA' => 'Personal Accident Cover AD+PTD+PPD',
                'SUPCRD' => 'Super Credit(increases irrespective of claim)',
                'CHRHSPT' => 'Chronic Care (Day 1 In-patient Hospitalization)',
                'CHMP' => 'Chronic Management Program (OPD)',
                'CANC' => 'Cancer Booster',
                'DECOV' => 'Durable equipment cover',
                'CHRREST' => 'Chronic Care (Day 1 In-patient Hospitalization) Restriction',
                'COMV' => 'Compassionate Visit',
                'SCOP' => 'Second Medical Opinion for listed Major Illness',
                'ANCANC' => 'Annual Screening Package for Cancer Diagnosed Patients',
                'ANHLTH' => 'Annual Health Check up',
                'VACC' => 'Vaccine cover',
                'TELEOPD' => 'Tele – OPD consultation',
                'OPDADON' => 'OPD Add-On',
                'GCEE' => 'Geographical extension',
            ],
            'ADDON_NAME_CODE_MAP' => [
                'In-patient Hospitalization' => 'IPTT',
                'Road Ambulance Cover (per hospitalization)' => 'RACV',
                'Modern Procedures/Treatments' => 'MTAT',
                'HIV / AIDS and STD Cover' => 'HIVC',
                'Mental Illness Hospitalization' => 'MITR',
                'Obesity Treatment' => 'OBTR',
                'Pre-Hospitalization Expenses' => 'PRHM',
                'Post-Hospitalization Expenses' => 'POHM',
                'Domiciliary Hospitalization' => 'DCHS',
                'Home Health Care' => 'HCT',
                'AYUSH Treatment' => 'AIPH',
                'Organ Donor Expenses' => 'ORDR',
                'Super Reload' => 'SUPR',
                'Health AssessmentTM' => 'HLTHA',
                'HealthReturnsTM' => 'HLTHRET',
                'Reduction in Specific Disease waiting period' => '2TO1',
                'Reduction in Pre-Existing Disease waiting period' => [
                    'CODE' => [
                        '3 Years to 1 Year' => '3TO1',
                        '3 Years to 2 Years' => '3TO2',
                    ]
                ],
                'Claim Protect (Non-Medical Expense Waiver)' => 'WNME',
                'Room Rent Type Options' => [
                    'CODE' => [
                        'SH' => 'Shared Accommodation',
                        'YSY' => 'Single Private Room'
                    ]
                ],
                'Per Claim Deductible' => [
                    'CODE' => [
                        '15000' => '15000',
                        '25000' => '25000'
                    ]
                ],
                'Preferred Provider Network (PPN) Discount -10% co-pay of claim is outside PPN' => 'PPNDISC',
                'Critical Illness cover' => 'CIL',
                'Personal Accident Cover AD+PTD+PPD' => 'PA',
                'Super Credit(increases irrespective of claim)' => 'SUPCRD',
                'Chronic Care (Day 1 In-patient Hospitalization)' => 'CHRHSPT',
                'Chronic Management Program (OPD)' => 'CHMP',
                'Cancer Booster' => 'CANC',
                'Durable equipment cover' => 'DECOV',
                'Chronic Care (Day 1 In-patient Hospitalization) Restriction' => 'CHRREST',
                'Compassionate Visit' => 'COMV',
                'Second Medical Opinion for listed Major Illness' => 'SCOP',
                'Annual Screening Package for Cancer Diagnosed Patients' => 'ANCANC',
                'Annual Health Check up' => 'ANHLTH',
                'Vaccine cover' => 'VACC',
                'Tele – OPD consultation' => 'TELEOPD',
                'OPD Add-On' => 'OPDADON',
                'Geographical extension' => 'GCEE',
            ],
            'PLAN_ADDONS' => [
                'MassMarket' => [
                    'RISW',
                    'RIPW',
                    'PCDED',
                    'PPNDISC',
                    'CIL',
                    'CANC',
                    'DECOV',
                    'COMV',
                    'SCOP',
                    'ANCANC',
                    'TELEOPD',
                    'RRTO'
                ],
                'Digital' => [
                    'RISW',
                    'RIPW',
                    'WNME',
                    'ANCANC',
                    'ANHLTH',
                    'CIL',
                    'SUPCRD',
                    'CANC',
                    'DECOV',
                    'COMV',
                    'SCOP',
                    'TELEOPD',
                    'PCDED',
                    'PPNDISC',
                    // 'GCEE',
                    // 'OBTR',
                    // 'RRTO'
                ],
                'MassMarket_Plus' => [
                    'RISW',
                    'RIPW',
                    'PCDED',
                    'PPNDISC',
                    'CIL',
                    'CANC',
                    'COMV',
                    'SCOP',
                    'ANCANC',
                    'TELEOPD',
                    'RRTO'
                ],
                'SAVR' => [
                    'RISW',
                    'RIPW',
                    'PCDED',
                    'PPNDISC',
                    'CIL',
                    'CANC',
                    'DECOV',
                    'COMV',
                    'SCOP',
                    'ANCANC',
                    'TELEOPD',
                    'RRTO'
                ],
                'CHRONIC' => [
                    'RISW',
                    'RIPW',
                    'PCDED',
                    'PPNDISC',
                    'CIL',
                    'CANC',
                    'DECOV',
                    'COMV',
                    'SCOP',
                    'ANCANC',
                    'TELEOPD',
                    'CHRREST',
                    'RRTO'
                ],
            ],
            'RELATIONCODE' => [
                'SELF' => 'SELF',
                'HUSBAND' => 'SPSE',
                'WIFE' => 'SPSE',
                'SON' => 'SONM',
                'DAUGHTER' => 'UDTR',
                'FATHER' => 'FATH',
                'MOTHER' => 'MOTH',
                'FATHERINLAW' => 'FLAW',
                'MOTHERINLAW' => 'MLAW'
            ],
            'CREDENTIAL' => [
                'AESKEY' => 'fdc36aef0618933aebc4508a93245760',
                'AUTHORIZATION' => '06754239a8054cbea3398160fea63cdf',
                'PRODUCT_CODE' => '7200',
                'INTERMEDIARY_CODE' => '2103129',
                'BRANCH_CODE' => '10MHMUM02',
                'SOURCE_NAME' => 'Invictus'
            ]
        ]
    ],
    // --------------------------------------------------------------MOTOR-------------------------------------------------------------------
    'MOTOR' =>
        [
            'PLANTYPE' => ['1' => 'OWN DAMAGE', '2' => 'COMPREHENSIVE', '3' => 'THIRD PARTY', '4' => 'BUNDLED'],
            'BIKEPLANTYPE' => ['1' => 'OWN DAMAGE', '2' => 'COMPREHENSIVE', '3' => 'THIRD PARTY', '4' => 'BUNDLED'],
            'PLANTYPEQUOTE' => ['1' => 'OWN DAMAGE', '2' => 'COMPREHENSIVE', '3' => 'THIRD PARTY'],
            'CAR' => [
                'ADDONS' => [
                    '101' => 'ZERO DEPRECIATION',
                    '102' => 'ROAD SIDE ASSISTANCE',
                    '103' => 'CONSUMABLE',
                    '104' => 'ENGINE PROTECTOR',
                    '105' => 'TYRE SECURE',
                    '106' => 'RETURN TO INVOICE',
                    '107' => 'LOSS OF PERSONAL BELONGINGS',
                    '108' => 'EMERGENCYTRANHOTELEXPREMYN',
                    '109' => 'DAILYEXPREMYN',
                    '110' => 'MULTICARBENEFITYN',
                    '111' => 'KEY REPLACEMENT',
                    '112' => 'NCB PROTECTION',
                    '113' => 'MOTOR PROTECTION',
                    '114' => 'PAPaidDriverConductorCleaner',
                    '115' => 'Unnamed Passenger PA Cover',
                    '116' => 'Legal Liability Coverages For Paid Driver',
                    '117' => 'Geographical Extension',
                    '118' => 'SHRIMOTORPROTECTION',
                    '119' => 'Anti-Theft device',
                    '120' => 'Voluntary Deductible',
                    '121' => 'GR39A-Limit The Third Party Property Damage Cover',
                    '122' => 'Legal Liability To Employees',
                    '127' => '24x7 SPOT ASSISTANCE',
                    '128' => 'fuel adulteration cover',
                    '129' => 'DEFENCE COST COVER',
                    '130' => 'Electric Vehicle/ Hybrid System Protection Cover',
                    '131' => 'Refurbished Part Repair Cover',
                ]
            ],
            'BIKE' => [
                'ADDONS' => [
                    '101' => 'ZERO DEPRECIATION',
                    '102' => 'ROAD SIDE ASSISTANCE',
                    '103' => 'CONSUMABLE',
                    '104' => 'ENGINE PROTECTOR',
                    '106' => 'RETURN TO INVOICE',
                    '107' => 'LOSS OF PERSONAL BELONGINGS',
                    '109' => 'DAILYEXPREMYN',
                    '111' => 'KEY REPLACEMENT',
                    '113' => 'MOTOR PROTECTION',
                    '114' => 'PAPaidDriverConductorCleaner',
                    '115' => 'Unnamed Passenger PA Cover',
                    '116' => 'Legal Liability Coverages For Paid Driver',
                    '117' => 'Geographical Extension',
                    '118' => 'SHRIMOTORPROTECTION',
                    '119' => 'Anti-Theft device',
                    '120' => 'Voluntary Deductible',
                    '121' => 'GR39A-Limit The Third Party Property Damage Cover',
                    '122' => 'Legal Liability To Employees',
                    '127' => '24x7 SPOT ASSISTANCE',
                ]
            ],
            'SHRIRAM' => [
                'KEY' => '101',
                'TITLE' => 'Shriram general',
                'POLICYTYPE' => [
                    'PACKAGE' => 'MOT-PLT-001',
                    'LIABILITY' => 'MOT-PLT-002',
                    'OWNDAMAGE' => 'MOT-PLT-009',
                    'BUNDLED' => 'MOT-PLT-010',
                ],
                'PRODUCTTYPE' => [
                    'TWOWHEELER' => 'MOT-PRD-002',
                    'PRIVATECAR' => 'MOT-PRD-001',
                    'PCCV' => 'MOT-PLT-005',
                    'GCCV' => 'MOT-PLT-003',
                ],
                'MODEL' => [
                    'BIKE' => 'MOT-PRD-002',
                    'CAR' => 'MOT-PRD-001',
                    'PCCV' => 'MOT-PRD-005',
                    'GCCV' => 'MOT-PRD-003'
                ],
                'PROPOSALTYPE' => [
                    'FRESHPROPOSAL' => 'FRESH',
                    'MARKETRENEWAL' => 'RENEWAL OF OTHERS',
                    'MARKET_RENEWAL_WITHOUT_PREVIOUS_INSURANCE_HISTORY' => 'RENEWAL.WO.PRV INS DTL',
                ],
                'API' => [
                    'PRIVATECARQUOTE' => 'https://nsecureapi.shriramgi.com/NOVADIGITAL/SVS_Services/PolicyGeneration.svc/RestService/GetQuote',
                    'PRIVATECARPOLICY' => 'https://nsecureapi.shriramgi.com/NOVADIGITAL/SVS_Services/PolicyGeneration.svc/RestService/GenerateProposal',
                    'PDF' => '',
                    'PAYMENT' => '',
                    'PAYMENTSTATUS' => 'http://novaapiuat.shriramgi.com/UATNovaWS/novaServices/WebAggregator.svc/RestService/getPaymentStatus',
                    'OVDKYC' => ''
                ],
                'CREDENTIAL' => [
                    'USERNAME' => 'DIGIBIMA',
                    // 'USERNAME' => 'NiveshIns',
                    'PASSWORD' => 'shriram@1',
                ]
            ],
            'GODIGIT' => [
                'KEY' => '103',
                'TITLE' => 'Godigit general',
                'BIKE' => [
                    'POLICYTYPE' => [
                        'PACKAGE' => '20201',
                        'LIABILITY' => '20202',
                        'OWNDAMAGE' => '20203',
                        'BUNDLED' => '20201',
                    ],
                ],
                'CAR' => [
                    'POLICYTYPE' => [
                        'PACKAGE' => '20101',
                        'LIABILITY' => '20102',
                        'OWNDAMAGE' => '20103',
                        'BUNDLED' => '20101',
                    ],
                ],
                'PRODUCTTYPE' => [
                    'TWOWHEELER' => 'MOT-PRD-002',
                    'PRIVATECAR' => 'MOT-PRD-001',
                    'PCCV' => 'MOT-PLT-005',
                    'GCCV' => 'MOT-PLT-003',
                ],
                'MODEL' => [
                    'BIKE' => 'MOT-PRD-002',
                    'CAR' => 'MOT-PRD-001',
                    'PCCV' => 'MOT-PRD-005',
                    'GCCV' => 'MOT-PRD-003'
                ],
                'PROPOSALTYPE' => [
                    'BIKEFRESHPROPOSAL' => '51',
                    'CARFRESHPROPOSAL' => '31',
                    'MARKETRENEWAL' => 'PB',
                ],
                'API' => [
                    'QUOTE' => 'https://oneapi.godigit.com/OneAPI/v1/executor',
                    'POLICY' => 'https://oneapi.godigit.com/OneAPI/v1/executor',
                    'POLICYSTATUS' => 'https://oneapi.godigit.com/OneAPI/v1/executor',
                    'PAYMENT' => 'https://oneapi.godigit.com/OneAPI/v1/executor',
                    'TOKEN' => 'https://oneapi.godigit.com/OneAPI/v1/auth',
                    'KYCTOKEN' => 'https://oneapi.godigit.com/OneAPI/v1/auth',
                    'OVDKYC' => 'https://oneapi.godigit.com/OneAPI/v1/executor',
                    'KYCSTATUS' => 'https://oneapi.godigit.com/OneAPI/v1/executor',
                    'PAYMENTSTATUS' => 'https://oneapi.godigit.com/OneAPI/v1/executor',
                    'PDF' => 'https://oneapi.godigit.com/OneAPI/v1/executor',
                    'KYC' => 'https://oneapi.godigit.com/OneAPI/v1/executor',
                    'RETURNURL' => 'https://insurance.digibima.com/motor/car/vendor/godigit/journey',
                    'PAYMENTRETURNURL' => 'https://insurance.digibima.com/motor/car/vendor/godigit/payment/thankyou',
                ],
                'INTEGRATIONID' => [
                    'QUOTE' => '28094-0100',
                    'POLICY' => '28096-0100',
                    'POLICYSTATUS' => '28098-0100',
                    'OVDKYC' => '23553-0100',
                    'KYCSTATUS' => '28098-0100',
                    'PAYMENT' => '28099-0100',
                    'PAYMENTSTATUS' => '22795-0100',
                    'PDF' => '22793-0100',
                    'KYC' => '',
                ],
                'CREDENTIAL' => [
                    'USERNAME' => '67160562',
                    'PASSWORD' => '4IS1AmEmnIEAF9u6i3a',
                    'TOKENUSERNAME' => 'HkMfH+mws05z5uOSCEpeTQ==',
                    'TOKENPASSWORD' => 'lI42Enh/ZY2vXqQYAgbrKa3pRWoTMOyqPwMwexnF3uo=',
                    'AGENTCODE' => '1000295',
                    'IMD' => '1188421'
                ]
            ],
            'ZUNO' => [
                'KEY' => '107',
                'TITLE' => 'Zuno',
                'POLICYTYPE' => [
                    'PACKAGE' => 'Package Policy',
                    'LIABILITY' => 'Liability Only',
                    'OWNDAMAGE' => 'Standard Alone',
                    'BUNDLED' => 'Bundled Insurance',
                ],
                'PRODUCTTYPE' => [
                    'TWOWHEELER' => 'MOT-PRD-002',
                    'PRIVATECAR' => 'MOT-PRD-001',
                    'PCCV' => 'MOT-PLT-005',
                    'GCCV' => 'MOT-PLT-003',
                ],
                'MODEL' => [
                    'BIKE' => 'MOT-PRD-002',
                    'CAR' => 'MOT-PRD-001',
                    'PCCV' => 'MOT-PRD-005',
                    'GCCV' => 'MOT-PRD-003'
                ],
                'PROPOSALTYPE' => [
                    'FRESHPROPOSAL' => 'New',
                    'MARKETRENEWAL' => 'Rollover',
                    'MARKET_RENEWAL_WITHOUT_PREVIOUS_INSURANCE_HISTORY' => 'RENEWAL.WO.PRV INS DTL',
                ],
                'CARCREDENTIAL' => [
                    'USERNAME' => 'ijc3rr25rsu65eha8audvpqeg',
                    'APIKEY' => 'jCe5bdB1ML2Q5vIAPOU8K2hRJvqlLtV23qoHGQlE',
                    'PASSWORD' => '1abh34ras5dmjhekjqec7lblact6dokvde9cu9sfm57qiuet35l7',
                ]
            ],
            'BAJAJMOTOR' => [
                'KEY' => '105',
                'TITLE' => 'Bajaj Motor Web Service',
                'PRODUCTCODETWOWHEELER' => [
                    'COMPREHENSIVE' => '1802',
                    'TPONLY' => '1806',
                    'NEWBUSSINESS' => '1826',
                    'ODONLY' => '1871',
                ],
                'PRODUCTCODEFOURWHEELER' => [
                    'COMPREHENSIVE' => '1801',
                    'TPONLY' => '1805',
                    'NEWBUSSINESS' => '1825',
                    'ODONLY' => '1870',
                ],
                'POLICYTYPE' => [
                    'NB' => '1',
                    'OTHER' => '3',
                    'Renewal' => '2'
                ],
                'ADDON_PACKAGE_NAME_BIKE' => [
                    [
                        'addon_name' => 'DRIVE ASSURE SILVER',
                        'flag' => 'DRIVE_ASSURE_SILVER',
                        'covers' => [
                            '103' => 'Consumable Expenses',
                            '101' => 'Depreciation Shield',
                            '104' => 'Engine Protector',
                        ]
                    ],
                    [
                        'addon_name' => 'DRIVE ASSURE BASIC',
                        'flag' => 'DRIVE_ASSURE_BASIC',
                        'covers' => [
                            '101' => 'Depreciation Shield',
                        ]
                    ],
                    [
                        'addon_name' => 'DRIVE ASSURE SPOT',
                        'flag' => 'DRIVE_ASSURE_SPOT',
                        'covers' => [
                            '127' => '24x7 SPOT ASSISTANCE',
                        ]
                    ]
                ],
                'ADDON_PACKAGE_NAME_CAR' => [
                    [
                        'addon_name' => 'Drive Assure Prime',
                        'flag' => 'DRIVE_ASSURE_PRIME',
                        'covers' => [
                            '127' => '24x7 SPOT ASSISTANCE',
                            '111' => 'KEYS AND LOCKS REPLACEMENT COVER',
                        ]
                    ],
                    [
                        'addon_name' => 'Drive Assure – Economy',
                        'flag' => 'DRIVE_ASSURE_PACK',
                        'covers' => [
                            '127' => '24x7 SPOT ASSISTANCE',
                            '101' => 'Depreciation Shield',
                            '104' => 'Engine Protector',
                        ]
                    ],
                    [
                        'addon_name' => 'Drive Assure Economy Plus',
                        'flag' => 'DRIVE_ASSURE_PACK_PLUS',
                        'covers' => [
                            '127' => '24x7 SPOT ASSISTANCE',
                            '111' => 'KEYS AND LOCKS REPLACEMENT COVER',
                            '107' => 'Personal Baggage Cover',
                            '101' => 'Depreciation Shield',
                            '104' => 'Engine Protector',
                        ]
                    ],
                    [
                        'addon_name' => 'ECO ASSURE',
                        'flag' => 'ECO_ASSURE_REPAIR',
                        'covers' => [
                            '127' => '24x7 SPOT ASSISTANCE',
                            '111' => 'KEYS AND LOCKS REPLACEMENT COVER',
                            '107' => 'Personal Baggage Cover',
                            '103' => 'Consumable Expenses',
                            '102' => 'RODENT DAMAGE COVER',
                            '128' => 'fuel adulteration cover',
                            '129' => 'DEFENCE COST COVER',
                            '130' => 'Electric Vehicle/ Hybrid System Protection Cover',
                            '131' => 'Refurbished Part Repair Cover',
                            '104' => 'Engine Protector',
                        ]
                    ]
                ],
            ]
        ],
];
