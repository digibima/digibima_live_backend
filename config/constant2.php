<?php

use App\Models\User;


$BASE_URL = 'https://test.digibima.com/public/';
$SITE_URL = 'https://test.digibima.com/';
//$BASE_URL              = 'http://127.0.0.1:80/insurance/public/';
return [

    'SITE_URL' => $SITE_URL,
    'BASE_URL' => $BASE_URL,
    'IMAGE' => $BASE_URL . 'upload/',
    //front
    'FRONT_URL' => $BASE_URL . 'front/',

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
    "LIFESTYLE" => [
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
        'COVERAGE' => [5, 7, 10, 15, 25, 50, 100],
        'TENURE' => [1, 2, 3],
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

                'ADDON' => [
                    'aa' => 'Air Ambulance',
                    'wb' => 'Wellness Benefit',
                    'ncb' => 'Cumulative Bonus Super',
                    'ahc' => 'Annual Health Check-up',
                    'ic' => 'Instant Cover',
                    'cs' => 'Claim Shield',
                    'opd' => 'Care OPD',
                    'pwpm' => 'PED Wait Period Modification',
                    'befit' => 'befit'
                ],
            ],

        'ULTIMATECARE' => [
            'KEY' => '102',
            'ADDON' => [
                'aa' => 'Air Ambulance',
                'wb' => 'Wellness Benefit',
                'ncb' => 'Cumulative Bonus Super',
                'ahc' => 'Annual Health Check-up',
                'ic' => 'Instant Cover',
                'cs' => 'Claim Shield',
                'opd' => 'Care OPD',
                'pwpm' => 'PED Wait Period Modification',
                'befit' => 'befit'
            ],
        ]
    ],
    //--------------------------------------------------------------MOTOR-------------------------------------------------------------------
    'MOTOR' =>
        [
            'CAR' => [
                'PLANTYPE' => ['1' => 'OWN DAMAGE', '2' => 'COMPREHENSIVE', '3' => 'THIRD PARTY'],
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
                ]
            ],
            'SHRIRAM' => [
                'KEY' => '101',
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
                'PROPOSALTYPE' => [
                    'FRESHPROPOSAL' => 'FRESH',
                    'MARKETRENEWAL' => 'RENEWAL OF OTHERS',
                    'MARKET_RENEWAL_WITHOUT_PREVIOUS_INSURANCE_HISTORY' => 'RENEWAL.WO.PRV INS DTL',
                ],
                'CREDENTIAL' => [
                    'USERNAME' => 'NiveshIns',
                    'PASSWORD' => 'shriram@1',
                ]
            ]
        ],

    'PINCODE' =>
        [1, 2, 3]
 
];
