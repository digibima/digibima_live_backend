<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\front\health\vendor\bajajmyhealth\BjajaMyHealthController;
use App\Http\Controllers\Api\front\health\vendor\bajajmyhealth\FilterController;
use App\Services\Api\bajajHealth\BajajMyhealthService;
use App\Services\Api\bajajHealth\KycService;
use App\Http\Middleware\Api\JourneyAuth;




Route::any('/bajaj/paymentstatus', function () {
    $service = new KycService();
    $response = $service->PaymentStatus();
    return response()->json([
        'response' => $response
    ]);
});

Route::group(['prefix' => 'bajaj-myhealthcare'], function () {
    Route::controller(BjajaMyHealthController::class)->group(function () {
        Route::middleware(JourneyAuth::class)->group(function () {
            Route::any('/addon', 'addonView');
            Route::any('/plancheckout', 'YearPlanCheckout');
            Route::any('/addaddon', 'addAddOn');
            Route::any('/showaddon', 'showAddon');
            Route::any('/setpremium', 'setPremium');
            Route::any('/proposal', 'proposalView');
            Route::any('/createpolicy', 'createPolicy');
            Route::any('/gettotalpremium', 'getQuotation');
            Route::any('/newpincode', 'NewPincode');
            Route::any('/updatepincodes', 'updatePincode');
            Route::any('/proposalStepOne', 'proposalStepOne');
            Route::any('/proposalStepTwo', 'proposalStepTwo');
            Route::any('/proposalStepThree', 'proposalStepThree');
            Route::any('/proposalStepFour', 'proposalStepFour');
            Route::any('/payment', 'PaymentView');
            Route::any('/savepayment', 'savePayment');
            Route::any('/uploadfile', 'uploadFile');
            Route::any('/validateckycdetails', 'ValidateCKYCdetails');
            Route::any('/verifyuploaddocument', 'verifyDocument');
            Route::any('/thankyou', 'thankYou');
            Route::any('/proposalStepPort','portStep');
            Route::any('/policystatus','policyStatus');
            Route::any('/policypdf','policyPdf');
            Route::any('/updateage', 'Ageupdate');
        });
    });

    // Route::controller(FilterController::class)->group(function () {
    //     Route::middleware(JourneyAuth::class)->group(function () {
    //         Route::any('/policytest', 'createPolicy');
    //     });
    // });
});


