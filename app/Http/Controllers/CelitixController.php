<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Http, Log, Mail};
use App\Models\TeleSales;
use App\Mail\NotificationMail;
class CelitixController
{
    // public function SendMsg(Request $request)
    // {
    //     try {
    //         $response = $request->responseData ?? null;
    //         $regno = $request->regNo ?? null;
    //         $mobile = $request->mobile ?? null;
    //         $mobile = "91" . $mobile;
    //         $name = $request->ownerName ?? null;
    //         $rawArray = is_string($response) ? json_decode($response, true) : $response;
    //         $logData = [
    //             'ip' => $request->ip(),
    //             'mobile' => $mobile,
    //             'regno' => $regno,
    //             'messaging_product' => $rawArray['messaging_product'] ?? null,
    //             'input_mobile' => $rawArray['contacts'][0]['input'] ?? null,
    //             'wa_id' => $rawArray['contacts'][0]['wa_id'] ?? null,
    //             'wamid' => $rawArray['messages'][0]['id'] ?? null,
    //             'message_status' => $rawArray['messages'][0]['message_status'] ?? null,
    //             'raw_response' => $rawArray
    //         ];
    //         $isext = TeleSales::where('message_id', $logData['wamid'])->first();
    //         if ($isext) {
    //             $isext->delete();
    //         }
    //         $record = new TeleSales();
    //         $record->mobile = $mobile;
    //         $record->regno = $regno;
    //         $record->message_id = $logData['wamid'];
    //         $record->message = null;
    //         $record->name = $name;
    //         $record->date = now();
    //         $record->save();
    //         \Log::build([
    //             'driver' => 'single',
    //             'path' => storage_path('logs/celetix_responses.log'),
    //         ])->info('Send Msg Response:', $logData);
    //         return response()->json(['success' => true]);
    //     } catch (\Exception $e) {
    //         \Log::build([
    //             'driver' => 'single',
    //             'path' => storage_path('logs/celetix_responses.log'),
    //         ])->info('Exception:', ErrMessage($e));
    //     }
    // }

    // public function ReponseMsg(Request $request)
    // {
    //     try {
    //         //$rawPayload = $request->getContent();
    //         $response = $request->all();
    //         $value = $response['entry'][0]['changes'][0]['value'] ?? [];
    //         $message = $value['messages'][0] ?? [];
    //         $cleanData = [
    //             'sender_name' => $value['contacts'][0]['profile']['name'] ?? null,
    //             'whatsapp_id' => $value['contacts'][0]['wa_id'] ?? null,
    //             'from_mobile' => $message['from'] ?? null,
    //             'message_body' => $message['text']['body'] ?? ($message['button']['text'] ?? null),
    //             'button_payload' => $message['button']['payload'] ?? null,
    //             //'message_id' => $message['id'] ?? null,
    //             'wamid' => $message['context']['id'] ?? null,
    //             'message_type' => $message['type'] ?? null,
    //             'timestamp' => $message['timestamp'] ?? null,
    //             'received_at' => now()->toDateTimeString(),
    //         ];

    //         // if (!isset($cleanData['button_payload'])) {
    //         //     return;
    //         // }
    //         // $mobile = $cleanData['from_mobile'];
    //         // if (!isset($mobile)) {
    //         //     return response()->json(['success' => false, 'message' => 'Mobile number missing'], 400);
    //         // }

    //         // $isext = TeleSales::where('message_id', $cleanData['wamid'])->first();
    //         $name = $isext->name ?? $cleanData['sender_name'];
    //         $messageBody = $isext->message ?? $cleanData['message_body'];
    //         // if ($isext && now()->greaterThanOrEqualTo($isext->date->copy()->addDays(35))) {
    //         //     $isext->delete();
    //         // }

    //         if (isset($cleanData['from_mobile'])) {
    //             \Log::build([
    //                 'driver' => 'single',
    //                 'path' => storage_path('logs/celetix_responses.log'),
    //             ])->info('Clicked Msg Response:', $cleanData);


    //             //=====================mail============================
    //             //$mailto = 'welldheeraj.100@gmail.com'; 
    //             // $mailto = 'review.digibima@gmail.com';
    //             // $subject = "Celetix Whatsapp Response";
    //             // $body = "A client send response from whatsapp.\n" .
    //             //     "Name: {$name}\n" .
    //             //     "Mobile: {$mobile}\n" .
    //             //     "Reg_No: {$isext->regno}";
    //             // Mail::to($mailto)
    //             //     ->send(new NotificationMail($subject, $body));

    //             //============================send to telecaller====================================
    //             $response = Http::get('https://api.digibima.in/api/leads/telesales-employees');
    //             $ids = collect($response->json('data'))->pluck('id')->toArray();
    //             SetCache('avl_staffs', $ids);
    //             $randomUserId = $ids[rand(0, count($ids) - 1)];
    //             $leadsresponse = Http::post('https://api.digibima.in/api/leads', ["client_name" => $name, "user_id" => $randomUserId, "registration_number" => $isext->regno??12345, "client_contact_number" => $mobile??12345]);
    //             return response()->json(['id' => $ids, 'leadsresponse' => $leadsresponse->json()]);
    //             \Log::build([
    //                 'driver' => 'single',
    //                 'path' => storage_path('logs/celetix_responses.log'),
    //             ])->info('Lead Response:', $leadsresponse);


    //             //=======================Save to gsheet======================================
    //             // $webAppUrl = "https://script.google.com/macros/s/AKfycbyGu3tT3VO2SB2phZMz7-fhAwGDCeWiWAhDSWQ9zYC29xJYs9kvrQyt6Qmsx0nNCOQYNQ/exec";
    //             // try {
    //             //     $httpResponse = Http::withHeaders([
    //             //         'allow_redirects' => true,
    //             //     ])->asJson()->post($webAppUrl, [
    //             //                 'name' => $name,
    //             //                 'mobile' => $mobile,
    //             //                 'message' => $messageBody
    //             //             ]);

    //             //     if ($httpResponse->successful()) {
    //             //         $result = $httpResponse->json();
    //             //         return response()->json($result);
    //             //     }

    //             //     return response()->json([
    //             //         'success' => false,
    //             //         'error' => 'Failed to connect with Google Sheets API.'
    //             //     ], 500);

    //             // } catch (\Exception $e) {
    //             //     return response()->json([
    //             //         'success' => false,
    //             //         'error' => $e->getMessage()
    //             //     ], 500);
    //             // }

    //             return response()->json(['status' => true]);
    //         }

    //         // return response()->json([
    //         //     'success' => false,
    //         //     'error' => 'Failed to insert in Sheets.'
    //         // ], 500);

    //     } catch (\Exception $e) {
    //         \Log::build([
    //             'driver' => 'single',
    //             'path' => storage_path('logs/celetix_responses.log'),
    //         ])->info('Exception:', ErrMessage($e));

    //         return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    //     }
    // }
    // public function GetMsg(Request $request)
    // {
    //     //return "getceletixwpmsg";
    //     // $request->validate([
    //     //     'name' => 'required|string',
    //     //     'mobile' => 'required|string',
    //     //     'message' => 'required|string',
    //     // ]);
    //     $name = "dheeraj";//$request->input('name');
    //     $mobile = "9199901992";//$request->input('mobile');
    //     $message = "hello";//$request->input('message');
    //     $senderid = "123";//$request->input('message');
    //     $msgid = "321";//$request->input('message');
    //     //$message = "hello";//$request->input('message');
    //     $webAppUrl = "";
    //     //$webAppUrl = "https://script.google.com/macros/s/AKfycbx55K4u-lFwA5cGy8VzQqJDC8GVbMi9ttjVXa52TvSURo7Xib1nF7suYs76xba7WeOQ/exec";

    //     try {
    //         $response = Http::withHeaders([
    //             'allow_redirects' => true,
    //         ])->asJson()->post($webAppUrl, [
    //                     'name' => $name,
    //                     'mobile' => $mobile,
    //                     'message' => $message,
    //                     'senderid' => $senderid,
    //                     'msgid' => $msgid,
    //                 ]);

    //         if ($response->successful()) {
    //             $result = $response->json();
    //             return response()->json($result);
    //         }

    //         return response()->json([
    //             'success' => false,
    //             'error' => 'Failed to connect with Google Sheets API.'
    //         ], 500);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }
    // public function sendWhatsapp(Request $request)
    // {
    //     $mobile = "919799968746";//$request->mobile;
    //     $ownerName = "tanya";//rim($request->ownerName);
    //     $regNo = "abcd";//$request->regNo;
    //     $templateName = "fieldsales_reminder1";//$request->templateName;

    //     $url = "https://api.celitix.com/wrapper/waba/message";

    //     $payload = [
    //         "messaging_product" => "whatsapp",
    //         "recipient_type" => "individual",
    //         "to" => $mobile,
    //         "type" => "template",
    //         "template" => [
    //             "name" => $templateName,
    //             "language" => [
    //                 "code" => "en"
    //             ],
    //             "components" => [
    //                 [
    //                     "type" => "header",
    //                     "parameters" => [
    //                         [
    //                             "type" => "image",
    //                             "image" => [
    //                                 "link" => "https://m.cltx.in/upload/image/27105f03-1d17-447a-b383-5865d5ab5095.jpg"
    //                             ]
    //                         ]
    //                     ]
    //                 ],
    //                 [
    //                     "type" => "body",
    //                     "parameters" => [
    //                         [
    //                             "type" => "text",
    //                             "text" => $ownerName
    //                         ],
    //                         [
    //                             "type" => "text",
    //                             "text" => $regNo
    //                         ]
    //                     ]
    //                 ]
    //             ]
    //         ]
    //     ];
    //     // dd($payload);
    //     $response = Http::withHeaders([
    //         'key' => 'e87f55c326XX',
    //         'wabaNumber' => '919119173772'
    //     ])->post($url, $payload);

    //     return response()->json([
    //         'status' => $response->successful(),
    //         'responseCode' => $response->status(),
    //         'response' => $response->json(),
    //     ]);
    // }
}
