<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsappServive
{
    private const CONFIG = [
        'BASE_URL' => 'https://api.celitix.com',
        'API_KEY' => 'e87f55c326XX',
        'WABA_NUMBER' => '919119173772',
        'TEMPLATE_NAME_1' => 'digibima_portal_login',
        'LANGUAGE_CODE' => 'en',
        'TIMEZONE' => 'Asia/Kolkata',
    ];

    /**
     * Send WhatsApp template message
     */
    public function sendSmsTemplate($mobile, $username = '', $templateName)
    {
        try {
            $url = self::CONFIG['BASE_URL'] . '/wrapper/waba/message';
            $payload = [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => (string) $mobile,
                'type' => 'template',
                'template' => [
                    'name' => $templateName,
                    'language' => [
                        'code' => self::CONFIG['LANGUAGE_CODE'],
                        'policy' => 'deterministic'
                    ],
                    'components' => [
                        [
                            'type' => 'header',
                            'parameters' => [
                                [
                                    'type' => 'image',
                                    'image' => [
                                        'link' => 'http://16.112.152.22:9000/digibimabucket/celetix/digibima_portal_login.jpg'
                                    ],
                                ],
                            ],
                        ],
                        [
                            'type' => 'body',
                            'parameters' => [
                                [
                                    'type' => 'text',
                                    'text' => trim((string) ($username ?? '')),
                                ],
                            ],
                        ],
                    ],
                ],
            ];

            $response = Http::withHeaders([
                'key' => self::CONFIG['API_KEY'],
                'wabaNumber' => self::CONFIG['WABA_NUMBER'],
            ])
                ->timeout(30)
                ->post($url, $payload);

            return [
                'responseCode' => $response->status(),
                'responseData' => $response->json(),
            ];
        } catch (\Throwable $e) {
            return [
                'responseCode' => 500,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function sendSms($mobile, $username = '')
    {
        $template = self::CONFIG['TEMPLATE_NAME_1'];
        // return ['name' => $username, 'mobile' => $mobile];
        return $this->sendSmsTemplate(
            $mobile,
            $username,
            $template,
        );
    }
}
