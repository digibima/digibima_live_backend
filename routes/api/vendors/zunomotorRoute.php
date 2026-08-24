<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
// use App\Http\Controllers\Api\front\motor\{MotorController};
use App\Http\Controllers\Api\front\motor\Vendor\zuno\Car\{ZunoCarController};
use Illuminate\Support\Facades\Auth;
use App\Services\Api\Zuno\ZunoUtilityService;
use App\Services\Api\Zuno\ZunoCarService;

// Route::any('/zuno/token', function () {
//     $service = new ZunoUtilityService();
//     $response = $service->generateToken();

//     return response()->json([
//         'status' => true,
//         'response' => $response,
//     ]);
// });
// Route::any('/zuno/ovd', function () {
//     $service = new ZunoCarController();
//     $response = $service->uploadFile();

//     return response()->json([
//         'status' => true,
//         'response' => $response,
//     ]);
// });
// Route::any('/zuno/prposal', function () {
//     $service = new ZunoCarService();
//     $response = $service->privateCarProposal();

//     return response()->json([
//         'status' => true,
//         'response' => $response,
//     ]);
// });
Route::any('/zuno/idv', function () {
    $service = new ZunoCarService();
    $response = $service->FindIdv();

    return response()->json([
        'status' => true,
        'response' => $response,
    ]);
});


// Route::any('/bikeproposal', function () {
//     $responseProposal1 = json_decode(
//         ShriramService::generateBikeProposal(now(), "", ''),
//         true
//     );
//     dd($responseProposal1);
// });

Route::group(['prefix' => 'motor-car-zuno'], function () {
    Route::controller(ZunoCarController::class)->group(function () {
        Route::group(['middleware' => 'App\Http\Middleware\Api\JourneyAuth'], function () {
            Route::any('/savestepone', 'stepone');
            Route::any('/acdetails', 'AutoCompleteDetails');
            Route::any('/journey', 'journey');
            Route::any('/uploadfile', 'uploadFile');
            Route::any('/payment', 'Payment');
            Route::any('/thankyou', 'thankYou');
            Route::any('/paymentstatus', 'PaymentStatus');
            Route::any('/update-status', 'updateStatus');
            Route::any('/ekyc', 'Ekyc');
            Route::any('/ovd', 'OVD');
        });
    });
});

// Route::group(['prefix' => 'motor-bike-zuno'], function () {
//     Route::controller(ShriramBikeController::class)->group(function () {
//         Route::group(['middleware' => 'App\Http\Middleware\Api\JourneyAuth'], function () {
//              Route::any('/acdetails', 'AutoCompleteDetails');
//             Route::any('/bikecheckout', 'bikeplanCheckout');
//             Route::any('/bikestepone', 'bikestepone');
//             Route::any('/bikesavesteptwo', 'bikesteptwo');
//             Route::any('/bikesavestepthree', 'bikestepthree');
//             Route::any('/bike-journey', 'bikeJourney');
//             Route::any('/bikeuploadfile', 'bikeuploadFile');
//             Route::any('/bike-payment', 'bikePayment');
//             Route::any('/thankyou', 'thankYou');
//         });
//     });
// });

