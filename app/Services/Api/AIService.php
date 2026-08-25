<?php
namespace App\Services\Api;

class AIService
{
    public function GeminiAI($prompt)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $prompt
                            ]
                        ]
                    ]
                ]
            ]),
            CURLOPT_HTTPHEADER => [
                'content-type: application/json',
                'x-goog-api-key: AIzaSyCpTPrKoyG6XJb_Q9iUOmtvhUTX7Lzkcl4'
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo 'cURL Error #:' . $err;
        } else {
            echo $response;
        }
    }

    public function OpenAI($prompt)
    {
        $env = env('OPEN_API');
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.openai.com/v1/responses',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                'model' => 'gpt-5.4',
                'input' => [
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'input_text',
                                'text' => $prompt
                            ]
                        ]
                    ]
                ]
            ]),
            CURLOPT_HTTPHEADER => [
                "authorization: {$env}",
                'content-type: application/json'
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo 'cURL Error #:' . $err;
        } else {
            echo $response;
        }
    }

    public function OllamaAI($prompt)
    {
        $response = \Http::post('http://127.0.0.1:11434/api/generate', [
            'model' => 'qwen2.5:1.5b',
            'prompt' => $prompt,
            'stream' => false,
            'options' => [
                'temperature' => 0,
                'num_predict' => 200
            ]
        ]);
        return $response->json();
    }
}
