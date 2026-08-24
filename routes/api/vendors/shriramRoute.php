<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\KYCController;
use App\Http\Controllers\Api\front\motor\{MotorController};
use App\Http\Controllers\Api\front\motor\Vendor\shriram\{JourneyController, ShriramController, BikeController, ShriramKYCController};
use App\Http\Controllers\Api\front\motor\Vendor\shriram\Car\{ShriramCarController};
use App\Http\Controllers\Api\front\motor\Vendor\shriram\Bike\{ShriramBikeController};
use Illuminate\Support\Facades\Auth;
use App\Services\Api\{ShriramService};


// Route::any('/carproposal', function () {
//     $responseProposal = json_decode(
//         ShriramService::privateCarProposal(now(), "", ''),
//         true
//     );
//     dd($responseProposal);
// });
Route::any('/test', function () {
    // $responseProposal = json_decode(
    //     ShriramService::privateCarProposal(now(), "", ''),
    //     true
    // );
    return "testing of live";
});
// Route::any('/bikeproposal', function () {
//     $responseProposal1 = json_decode(
//         ShriramService::generateBikeProposal(now(), "", ''),
//         true
//     );
//     dd($responseProposal1);
// });

Route::group(['prefix' => 'motor-car-shriram'], function () {
    Route::controller(ShriramCarController::class)->group(function () {
        Route::group(['middleware' => 'App\Http\Middleware\Api\JourneyAuth'], function () {
            Route::any('/savestepone', 'stepone');
            Route::any('/acdetails', 'AutoCompleteDetails');
            Route::any('/journey', 'journey');
            Route::any('/uploadfile', 'uploadFile');
            Route::any('/payment', 'Payment');
            Route::any('/thankyou', 'thankYou');
            Route::any('/paymentstatus', 'PaymentStatus');
        });
    });
});

Route::group(['prefix' => 'motor-bike-shriram'], function () {
    Route::controller(ShriramBikeController::class)->group(function () {
        Route::group(['middleware' => 'App\Http\Middleware\Api\JourneyAuth'], function () {
             Route::any('/acdetails', 'AutoCompleteDetails');
            Route::any('/bikecheckout', 'bikeplanCheckout');
            Route::any('/bikestepone', 'bikestepone');
            Route::any('/bikesavesteptwo', 'bikesteptwo');
            Route::any('/bikesavestepthree', 'bikestepthree');
            Route::any('/bike-journey', 'bikeJourney');
            Route::any('/bikeuploadfile', 'bikeuploadFile');
            Route::any('/bike-payment', 'bikePayment');
            Route::any('/thankyou', 'thankYou');
        });
    });
});

