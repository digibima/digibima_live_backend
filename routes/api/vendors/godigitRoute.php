<?php

use App\Services\Api\godigit\{GoDigitBikeService, GoDigitCarService};
use App\Http\Controllers\Api\front\motor\Vendor\godigit\Car\{GoDigitCarController};
use App\Http\Controllers\Api\front\motor\Vendor\godigit\Bike\{GoDigitBikeController};
use App\Http\Controllers\Api\front\motor\{MotorController};
use App\Services\Api\godigit\{GoDigitUtilityService};
use App\Http\Middleware\Api\JourneyAuth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;



Route::any('/token', function () {

    $responseProposal = json_decode(
        GoDigitUtilityService::TokenGenerate(),
        true
    );
    return response()->json([
        'response'=>$responseProposal
    ]);
    //dd($responseProposal);
});
Route::any('/ovdkyc', function () {

    $responseProposal = GoDigitUtilityService::OVDkyc();
    return response()->json([
        'response'=>$responseProposal
    ]);
    //dd($responseProposal);
});
Route::any('/policystatus', function () {

    $responseProposal = GoDigitUtilityService::PolicyStatus();
    return response()->json([
        'response'=>$responseProposal
    ]);
    //dd($responseProposal);
});
Route::any('/policypdf', function () {

    $responseProposal = GoDigitUtilityService::PolicyPdf();
    return response()->json([
        'response'=>$responseProposal
    ]);
    //dd($responseProposal);
});
Route::any('/payment', function () {

    $responseProposal = GoDigitCarService::Payment();
    return response()->json([
        'response'=>$responseProposal
    ]);
    //dd($responseProposal);
});
// Route::any('/bikequote', function () {
//     $bikeservice = new GoDigitBikeService();
//     $response = $bikeservice->generateBikeQuote();
//     return response()->json([
//         'response'=>$response
//     ]);
//     //  dd($response);
// });

// Route::any('/usedcarquote', function () {
//     $carservice = new GoDigitCarService();
//     $response = $carservice->generateUsedCarQuote();
//     return[
//         'response'=>$response
//     ];
//     //dd($response);
// });
Route::any('godigit/carproposal', function () {
    $carservice = new GoDigitCarService();
    $response = $carservice->generateCarProposal();
    //dd($response);
    return response()->json([
        'response'=>$response
    ]);
});
Route::any('/bikeproposal', function () {
    $bikeservicePro = new GoDigitBikeService();
    $response = $bikeservicePro->generateBikeProposal();
    return response()->json([
        'response'=>$response
    ]);
    //dd($response);
});

Route::any('/bikeproposaltest', function () {
    $bikeservicetest = new GoDigitBikeServicetest();
    $response = $bikeservicetest->generateBikeQuotetest();
    return response()->json([
        'response'=>$response
    ]);
    //dd($response);
});




Route::group(['prefix' => 'motor-car-godigit'], function () {
    Route::controller(GoDigitCarController::class)->group(function () {
        Route::middleware(JourneyAuth::class)->group(function () {
            Route::any('/quote','getCarQuote');
            Route::any('/savestepone', 'stepone');
            Route::any('/journey', 'journey');
            Route::any('/uploadfile', 'uploadFile');
            Route::any('/car-payment', 'Payment');
            Route::any('/car-thankyou', 'thankYou');
            Route::any('/addonprice', 'getAddonPrice');
            Route::any('/ovdkyc', 'OVDKyc');
            Route::any('/paymentstatus', 'PaymentStatusApi');
            Route::any('/policypdf', 'PolicyPdf');
        });
    });
});


Route::group(['prefix' => 'motor-bike-godigit'], function () {
    Route::controller(GoDigitBikeController::class)->group(function () {
        Route::middleware(JourneyAuth::class)->group(function () {
            Route::any('/savestepone', 'stepone');
            Route::any('/quote', 'getbikequote');
            Route::any('/bike-journey', 'bikeJourney');
            Route::any('/bike-uploadfile', 'bikeuploadFile');
            Route::any('/bike-payment', 'bikePayment');
            Route::any('/bike-thankyou', 'thankYou');
            //Route::any('/paymentstatus', 'PaymentStatus');
        });
    });
});

// Route::group(['prefix' => 'motor-car-godigit', 'middleware' => 'App\Http\Middleware\MaintainanceMiddleware'], function () {
//     Route::controller(GoDigitCarController::class)->group(function () {
//         //Route::group(['middleware' => 'App\Http\Middleware\MotorAuth'], function () {
//         Route::middleware(JourneyAuth::class)->group(function () {
//             Route::any('/savestepone', 'stepone')->name('car.savestepone');
//             Route::any('/journey', 'journey')->name('godigit.journey');
//             Route::any('/caruploadfile', 'uploadFile')->name('godigitcar.uploadfile');
//             Route::any('/car-payment', 'bikePayment')->name('godigitcar.payment');
//             Route::any('/car-thankyou/{id?}', 'thankYou')->name('godigitcar.thankyou');
//             //Route::any('/paymentstatus', 'PaymentStatus');
//         });
//     });
// });



// Route::group(['prefix' => 'motor-bike-godigit', 'middleware' => 'App\Http\Middleware\MaintainanceMiddleware'], function () {
//     Route::controller(GoDigitBikeController::class)->group(function () {
//         Route::middleware(JourneyAuth::class)->group(function () {
//             Route::any('/savestepone', 'stepone')->name('bike.savestepone');
//             Route::any('/quote', 'generateBikeQuote')->name('bike.quote');
//             Route::any('/bike-journey', 'bikeJourney')->name('godigit.bikejourney');
//             Route::any('/bike-uploadfile', 'bikeuploadFile')->name('godigitbike.uploadfile');
//             Route::any('/bike-payment', 'bikePayment')->name('godigitbike.payment');
//             Route::any('/bike-thankyou/{id?}', 'thankYou')->name('godigitbike.thankyou');
//             //Route::any('/paymentstatus', 'PaymentStatus');
//         });
//     });
// });

//Route for services(API)
