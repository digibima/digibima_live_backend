<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\OTPVerificationController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\UserController;
// routes/web.php
Route::group(['prefix' => 'userpnlx', 'middleware' => 'App\Http\Middleware\Api\MaintainanceMiddleware'], function () {
    Route::controller(UserController::class)->group(function () {
        Route::any('/login', 'Login')->name('login');
        Route::group(['middleware' => 'App\Http\Middleware\Api\JourneyAuth'], function () {
            Route::any('/user-profile', 'index');
            Route::any('/user-policy', 'userPolicy');
            Route::get('/user-claim', 'userClaim');
            Route::post('/user-claim', 'CreateClaim');
            Route::any('/user-setting', 'userSetting');
            Route::any('/user-profile-update', 'userProfileUpdate');
           
        });
    });
});

