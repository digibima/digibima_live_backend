<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KYCController;
use App\Http\Controllers\Api\front\health\vendor\ultimatecare\{UltimateController, KYCController as ApiKycConroller};
use App\Http\Controllers\Api\front\health\vendor\caresupreme\CareSupremeController;
use App\Http\Controllers\Api\front\health\vendor\caresupreme\FilterController;
use App\Services\Api\UltimateCareService;
use App\Services\Api\OvdCareService;
use App\Http\Middleware\Api\JourneyAuth;

// Route::any('/ultimate/verifyOtp', function () {
//     $service = new UltimateCareService();
//     $response = $service->verifyOtp();
//     return response()->json([
//         'response' => $response
//     ]);
// });

Route::any('/ovdrequest', function () {
    $service = new OvdCareService();
    $response = $service->TokenGeneration();
    return $response;
});

Route::any('/ovduplode', function () {
    $service = new OvdCareService();
    $response = $service->UploadDocument();
    return $response;
    // return response()->json([
    //     'response'=>$response
    // ]);
});


Route::group(['prefix' => 'health-ultimatecare'], function () {
    Route::controller(UltimateController::class)->group(function () {
        Route::middleware(JourneyAuth::class)->group(function () {
            // Route::any('/getQuotation', 'getQuotation');
            Route::any('/addon', 'addonView');
            Route::any('/plancheckout', 'YearPlanCheckout');
            Route::any('/addaddon', 'addAddOn');
            Route::any('/showaddon', 'showAddon');
            Route::any('/setpremium', 'setPremium');
            Route::any('/proposal', 'proposalView');
            Route::any('/createpolicy', 'createPolicy');
            Route::any('/gettotalpremium', 'getBasicPlan');
            Route::any('/newpincode', 'NewPincode');
            Route::any('/commpincode', 'CommPincode');
            Route::any('/updatepincodes', 'updatePincode');
            Route::any('/proposalStepOne', 'proposalStepOne');
            Route::any('/proposalStepTwo', 'proposalStepTwo');
            Route::any('/proposalStepThree', 'proposalStepThree');
            Route::any('/proposalStepPort', 'proposalStepFour');
            Route::any('/payment', 'PaymentView');
            Route::any('/savepayment', 'savePayment');
            Route::any('/uploadfile', 'uploadFile');
            Route::any('/downloadpolicy', 'policyPdf');
            Route::any('/updateage', 'Ageupdate');
        });
    });
    Route::controller(UltimateController::class)->group(function () {
        Route::middleware(JourneyAuth::class)->group(function () {
            Route::any('/policystatus', 'policyStatus');
            Route::any('/downloadpolicy', 'policyPdf');
            Route::any('/thankyou', 'thankYou');
            Route::any('/generateCBC', 'generateCBC');
        });
    });

    Route::controller(ApiKycConroller::class)->group(function () {
        Route::middleware(JourneyAuth::class)->group(function () {
            Route::any('/verifypan', 'verifyPAN');
            Route::any('/verifyotp', 'verifyOTP');
        });
    });


    Route::controller(KYCController::class)->group(function () {
        Route::middleware(JourneyAuth::class)->group(function () {
            Route::any('/verifyadhar', 'verifyAdhar');
            Route::any('/basicplan', 'basicPlan');
        });
    });

});


// Route::group(['prefix' => 'health-ultimatecare'], function () {
//     Route::controller(UltimateController::class)->group(function () {
//         Route::group(['middleware' => 'App\Http\Middleware\JourneyAuth'], function () {
//             Route::any('/addon/{id?}', 'addonView')->name('ultimate.addon');
//             Route::any('/addaddon', 'addAddOn')->name('ultimate.addaddon');
//             Route::any('/showaddon', 'showAddon')->name('ultimate.showaddon');
//             Route::any('/setpremium', 'setPremium')->name('ultimate.setpremium');
//             Route::any('/proposal', 'proposalView')->name('ultimate.proposal');
//             Route::any('/createpolicy', 'createPolicy')->name('ultimate.createpolicy');
//             Route::any('/gettotalpremium', 'getBasicPlan')->name('ultimate.gettotalpremium');
//             Route::any('/updatepincodes', 'updatePincode')->name('ultimate.updatepincode');
//             Route::any('/proposalStepOne', 'proposalStepOne')->name('ultimate.proposalStepOne');
//             Route::any('/proposalStepTwo', 'proposalStepTwo')->name('ultimate.proposalStepTwo');
//             Route::any('/proposalStepThree', 'proposalStepThree')->name('ultimate.proposalStepThree');
//             Route::any('/proposalStepFour', 'proposalStepFour')->name('ultimate.proposalStepFour');
//             Route::any('/payment', 'PaymentView')->name('ultimate.payment');
//             Route::any('/savepayment/{id?}', 'savePayment')->name('ultimate.savepayment');
//             // Route::any('/getpincode', 'getPincode')->name('getpincode');
//             //Route::any('/ageupdate', 'ageUpdate')->name('ageupdate');
//             //Route::any('/uploaddocument', 'getPincode')->name('getpincode');
//             Route::any('/uploadfile', 'uploadFile')->name('ultimate.uploadfile');
//             // Route::any('/carepolicystatus/{proposal?}', 'policyStatus')->name('carepolicystatus');
//             // Route::any('/downloadpolicy/{policy?}', 'policyPdf')->name('downloadpolicy');
//         });
//     });
//     Route::controller(CareSupremeController::class)->group(function () {
//         Route::any('/carepolicystatus/{proposal?}', 'policyStatus')->name('ultimate.policystatus');
//         Route::any('/downloadpolicy/{policy?}', 'policyPdf')->name('ultimate.downloadpolicy');
//         Route::any('/thankyou/{policy?}', 'thankYou')->name('ultimate.thankyou');
//         Route::any('/generateCBC/{tokenKey?}/{tokenValue?}', 'generateCBC')->name('ultimate.generatecbc');
//     });
//     Route::any('/thankyou', function () {
//         return 'xyz';
//     })->name('xyz');
//     Route::controller(KYCController::class)->group(function () {
//         Route::any('/verifypan/{panno?}/{dob?}', 'verifyPAN')->name('ultimate.verifyPAN');
//         Route::any('/verifyadhar/{gender?}/{name?}/{adhar?}/{dob?}', 'verifyAdhar')->name('ultimate.verifyAdhar');
//         Route::any('/acpincode' . generateRandomString('acpincode'), 'AutoCompletePincode')->name('ultimate.acpincode');
//         Route::any('/acdetails' . generateRandomString('acdetails'), 'AutoCompleteDetails')->name('ultimate.acdetails');
//         Route::any('/basicplan', 'basicPlan')->name('ultimate.basicplan');
//     });
//     Route::controller(FilterController::class)->group(function () {
//         Route::any('/filterPlan/{slug?}', 'planFilter')->name('ultimate.filterplan');
//     });

//     // AutoCompletePincodeApi
// });

