<?php
use Illuminate\Support\Facades\Route;
use App\Services\Api\BajajMotor\BajajCarService;
use App\Services\Api\BajajMotor\BajajBikeService;
use App\Http\Controllers\Api\front\motor\Vendor\bajaj\Bike\BajajBikeController;
use App\Services\Api\BajajMotor\KycService;
use App\Http\Middleware\Api\JourneyAuth;
use Illuminate\Http\Request;

// Route::any('/bajaj/qutote', function () {
//     $service = new BajajBikeService();
//     $response = $service->generateBikeQuote();
//     return response()->json([
//         'response' => json_decode($response)
//     ]);
//     // return $response;
// });

// Route::any('/bajaj/kyc', function (Request $request) {
//     $service = new KycService();
//     $response = $service->ValidateCKYCdetails($request);
//     return $response;
// });


// Route::any('/bajaj/verifyuploaddocument', function (Request $request) {
//     $service = new KycService();
//     $response = $service->verifyUploadDocument($request);
//     return $response;
// });


Route::group(['prefix' => 'motor-bike-bajaj'], function () {
    Route::controller(BajajBikeController::class)->group(function () {
        Route::group(['middleware' => 'App\Http\Middleware\Api\JourneyAuth'], function () {
             Route::any('/acdetails', 'AutoCompleteDetails');
            Route::any('/bikecheckout', 'bikeplanCheckout');
            Route::any('/savestepone', 'stepone');
            Route::any('/bikesavesteptwo', 'bikesteptwo');
            Route::any('/bikesavestepthree', 'bikestepthree');
            Route::any('/journey', 'journey');
            Route::any('/uploadfile', 'uploadFile');
            Route::any('/bike-payment', 'Payment');
            Route::any('/thankyou', 'thankYou');
        });
    });
});

