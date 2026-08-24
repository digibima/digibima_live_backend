<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\OTPVerificationController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MailController;
use App\Http\Controllers\Api\front\health\vendor\caresupreme\CareSupremeController;
use App\Http\Controllers\Api\AiPromptController;
// routes/web.php

Route::group(['prefix' => 'userpnlx'], function () {
    Route::controller(UserController::class)->group(function () {
        Route::any('/login', 'Login')->name('login');
        Route::any('/user-inquire', 'Inquire');
        Route::group(['middleware' => 'App\Http\Middleware\Api\JourneyAuth'], function () {
            Route::any('/user-profile', 'index');
            Route::any('/notification-show', 'StatusView');
            Route::any('/user-policy', 'userPolicy');
            Route::post('/user-claim', 'CreateClaim');
            Route::any('/user-setting', 'userSetting');
            Route::any('/user-profile-update', 'userProfileUpdate');
            Route::any('/user-notification', 'status');
            Route::any('/policydownload', 'policyPdf');
        });
    });
});


Route::controller(MailController::class)->group(function () {
    Route::any('/downloadpdfreview', 'Download');
    // Route::any('/pdf', 'TexttoPdf');
     Route::any('/viewpdf', 'Viewpdf');
    Route::group(['middleware' => 'App\Http\Middleware\Api\JourneyAuth'], function () {
        Route::any('/mail/usersend', 'sendMail');
        Route::any('/upload/pdf', 'Savepdf');
        Route::any('/responsepdf', 'sendresponce');
        Route::any('/pdf', 'TexttoPdf');
    });
});
Route::post('/ai-products/upload-pdf', [AiPromptController::class, 'saveProductPdf']);

Route::controller(CareSupremeController::class)->group(function () {
    Route::any('/downloadpolicypdf', 'policyStatus');
});

