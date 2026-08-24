<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\KYCController;
use App\Http\Controllers\Api\OTPVerificationController;
use App\Http\Controllers\Api\front\health\vendor\caresupreme\FilterController;
use App\Http\Controllers\Api\front\health\vendor\caresupreme\{CareSupremeController as ApiCareSupremeController};
use App\Http\Middleware\Api\JourneyAuth;
Route::group(['prefix' => 'health-caresupereme'], function () {
  Route::controller(ApiCareSupremeController::class)->group(function () {
    Route::any('/generatecbc', 'generateCBC');
    // Route::any('/carepolicystatus', 'policyStatus');
    Route::any('/downloadpolicy', 'policyPdf');
    Route::any('/generateCBC', 'generateCBC');
    Route::middleware(JourneyAuth::class)->group(function () {
      Route::any('/addon', 'addonView');
      Route::any('/addaddon', 'addAddOn');
      Route::any('/plancheckout', 'YearPlanCheckout');
      Route::any('/showaddon', 'showAddon');
      Route::any('/setpremium', 'setPremium');
      Route::any('/newpincode', 'NewPincode');
      Route::any('/proposal', 'proposalView');
      Route::any('/createpolicy', 'createPolicy');
      Route::any('/gettotalpremium', 'getBasicPlan');
      // Route::any('/updatepincode', 'updatePincode');
      Route::any('/proposalStepOne', 'proposalStepOne');
      Route::any('/proposalStepTwo', 'proposalStepTwo');
      Route::any('/proposalStepThree', 'proposalStepThree');
      Route::any('/proposalStepFour', 'proposalStepFour');
      Route::any('/carepayment', 'PaymentView');
      Route::any('/savepayment', 'savePayment');
      Route::any('/thankyou', 'thankYou');
      Route::any('/uploadfile', 'uploadFile');
      Route::any('/careillnesses', 'saveInsure');
      Route::any('/carepolicystatus', 'policyStatus');
      Route::any('/downloadpolicy', 'policyPdf');
    });
  });

  Route::controller(FilterController::class)->group(function () {
    Route::middleware(JourneyAuth::class)->group(function () {
      Route::any('/filterPlan', 'planFilter')->name('filterplan');
    });
  });

  Route::controller(KYCController::class)->group(function () {
    Route::middleware(JourneyAuth::class)->group(function () {
      Route::any('/verifypan', 'verifyPAN');
      Route::any('/verifyadhar', 'verifyAdhar');
      // Route::any('/acdetails', 'AutoCompleteDetails');
      Route::any('/basicplan', 'basicPlan');
    });
  });
});

// Route::controller(CareSupremeController::class)->group(function () {
//     Route::any('/generatecbc/{tokenKey?}/{tokenValue?}', 'generateCBC');
// });

// Route::group(['prefix' => 'health-caresupereme'], function () {
//     Route::controller(CareSupremeController::class)->group(function () {
//         Route::group(['middleware' => 'App\Http\Middleware\JourneyAuth'], function () {
//             Route::any('/addon/{id?}', 'addonView')->name('addon');
//             Route::any('/addaddon', 'addAddOn')->name('addaddon');
//             Route::any('/showaddon', 'showAddon')->name('showaddon');
//             Route::any('/setpremium', 'setPremium')->name('setpremium');
//             Route::any('/proposal', 'proposalView')->name('proposal');
//             Route::any('/createpolicy', 'createPolicy')->name('createpolicy');
//             Route::any('/gettotalpremium', 'getBasicPlan')->name('gettotalpremium');
//             Route::any('/updatepincode', 'updatePincode')->name('updatepincode');
//             Route::any('/' . generateRandomString('proposalStepOne'), 'proposalStepOne')->name('proposalStepOne');
//             Route::any('/' . generateRandomString('proposalStepTwo'), 'proposalStepTwo')->name('proposalStepTwo');
//             Route::any('/' . generateRandomString('proposalStepThree'), 'proposalStepThree')->name('proposalStepThree');
//             Route::any('/' . generateRandomString('proposalStepFour'), 'proposalStepFour')->name('proposalStepFour');
//             Route::any('/carepayment', 'PaymentView')->name('carepayment');
//             Route::any('/savepayment/{id?}', 'savePayment')->name('savepayment');
//             // Route::any('/getpincode', 'getPincode')->name('getpincode');
//             //Route::any('/ageupdate', 'ageUpdate')->name('ageupdate');
//             //Route::any('/uploaddocument', 'getPincode')->name('getpincode');
//             Route::any('/uploadfile', 'uploadFile')->name('uploadfile');
//             Route::any('/careillnesses/{id?}', 'saveInsure')->name('care.illnesses');
//             // Route::any('/carepolicystatus/{proposal?}', 'policyStatus')->name('carepolicystatus');
//             // Route::any('/downloadpolicy/{policy?}', 'policyPdf')->name('downloadpolicy');
//         });
//     });
//     Route::controller(CareSupremeController::class)->group(function () {
//         Route::any('/carepolicystatus/{proposal?}', 'policyStatus')->name('carepolicystatus');
//         Route::any('/downloadpolicy/{policy?}', 'policyPdf')->name('downloadpolicy');
//         Route::any('/thankyou/{policy?}', 'thankYou')->name('thankyou');
//         Route::any('/generateCBC/{tokenKey?}/{tokenValue?}', 'generateCBC')->name('generatecbc');
//     });
//     Route::controller(KYCController::class)->group(function () {
//         Route::any('/verifypan/{panno?}/{dob?}', 'verifyPAN')->name('verifyPAN');
//         Route::any('/verifyadhar/{gender?}/{name?}/{adhar?}/{dob?}', 'verifyAdhar')->name('verifyAdhar');
//         Route::any('/acpincode' . generateRandomString('acpincode'), 'AutoCompletePincode')->name('acpincode');
//         Route::any('/acdetails' . generateRandomString('acdetails'), 'AutoCompleteDetails')->name('acdetails');
//         Route::any('/basicplan', 'basicPlan')->name('basicplan');
//     });
//     Route::controller(FilterController::class)->group(function () {
//         Route::any('/filterPlan/{slug?}', 'planFilter')->name('filterplan');
//     });
//});
