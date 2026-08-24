<?php

use App\Http\Controllers\Api\front\health\{FilterController as ApiFilterController};
use App\Http\Controllers\Api\front\health\{HealthController as ApiHealthController};
use App\Http\Controllers\Api\front\health\{PlanController as ApiPlanController};
use App\Http\Controllers\Api\{SystemController as ApiSystemController};
use App\Http\Controllers\Api\KYCController;
use App\Http\Controllers\front\health\vendor\caresupreme\CareSupremeController;
use App\Http\Controllers\front\health\{HealthController, PlanController, FilterController};
use App\Http\Controllers\OTPVerificationController;
use App\Http\Middleware\Api\JourneyAuth;
use Illuminate\Support\Facades\{Auth, Validator};
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register web routes for your application. These
 * | routes are loaded by the RouteServiceProvider and all of them will
 * | be assigned to the "web" middleware group. Make something great!
 * |
 */

Route::controller(ApiHealthController::class)->group(function () {
    Route::any('/insureview', 'Login');
    Route::any('/getuserinfo', 'UserInfo');
    // Route::any('/getuserinfo', 'UserInfo1');
    Route::middleware(JourneyAuth::class)->group(function () {
        Route::any('/plantype', 'savePort');
        Route::any('/getplantype', 'getPort');
        Route::any('/illnesses', 'saveInsure');
        Route::any('/saveillnesses', 'savePED');
        Route::any('/quoteview', 'QuoteView');
        Route::any('/getinsureinfo', 'getInsureInfo');
        Route::any('/ageupdate', 'ageUpdate');
        Route::any('/updatepincode', 'updatePincode');
    });
});

Route::controller(ApiFilterController::class)->group(function () {
    Route::middleware(JourneyAuth::class)->group(function () {
        Route::any('/filterplan', 'planFilter');
    });
});

Route::controller(ApiPlanController::class)->group(function () {
    Route::middleware(JourneyAuth::class)->group(function () {
        Route::any('/health-quotation-generate', 'HealthQuoteStream');
    });
});

Route::controller(KYCController::class)->group(function () {
    Route::middleware(JourneyAuth::class)->group(function () {
        Route::any('/acdetails', 'AutoCompleteDetails');
    });
});

// Route::controller(HealthController::class)->group(function () {
//     Route::any('/generatecbc/{tokenKey?}/{tokenValue?}', 'generateCBC');
// });
// Route::group(['prefix' => 'health-test'], function () {
//     Route::controller(HealthController::class)->group(function () {
//         Route::any('/', 'index')->name('health.root');
//         Route::any('/insureview/{id?}', 'Login')->name('insureview');
//         Route::group(['middleware' => 'App\Http\Middleware\JourneyAuth'], function () {
//             Route::any('/illnesses/{id?}', 'saveInsure')->name('illnesses');
//             Route::any('/saveillnesses/{id?}', 'savePED')->name('saveillnesses');
//             Route::any('/quoteview/{id?}', 'QuoteView')->name('plans');
//             Route::any('/getpincode', 'getPincode')->name('getpincode');
//             Route::any('/ageupdate', 'ageUpdate')->name('ageupdate');
//             Route::any('/updatepincode', 'updatePincode')->name('health.updatepincode');
//         });
//     });

//     Route::controller(PlanController::class)->group(function () {
//         Route::group(['middleware' => 'App\Http\Middleware\JourneyAuth'], function () {
//             Route::any('/health-quotation-generate', 'HealthQuoteStream')->name('health.quote');
//         });
//     });
//     Route::controller(FilterController::class)->group(function () {
//         Route::any('/filterPlan/{slug?}', 'planFilter')->name('health.filterplan');
//     });
// });
