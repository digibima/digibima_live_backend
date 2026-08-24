<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\front\motor\{MotorController};
use App\Http\Controllers\Api\front\motor\Car\{CarController, PlanController};



// Route::group(['prefix' => 'motor-car'], function () {
//     Route::controller(CarController::class)->group(function () {
//         Route::group(['middleware' => 'App\Http\Middleware\MotorAuth'], function () {
//             Route::any('/kn-car-details/{id?}', 'KnowCarDetails')->name('car.knowcar');

//             Route::any('/kn-car-detailstwo/{id?}', 'KnowCarDetailstwo')->name('car.knowcartwo');

//             Route::any('/new-car/{id?}', 'NewCarDetails')->name('car.newcar');
//             Route::any('/knowcarsteptwo/{id?}', 'knowcarSteptwo')->name('car.knowcarsteptwo');
//             Route::any('/newcarsteptwo/{id?}', 'newcarSteptwo')->name('car.newcarsteptwo');
//             Route::any('/plans/{id?}', 'carPlan')->name('car.plans');
//             Route::any('/addaddon', 'addAddon')->name('car.addaddon');
//             Route::any('/accessories', 'addAccessories')->name('car.addaccessories');
//             Route::any('/updateidv', 'updateIdv')->name('car.updateidv');
//             Route::any('/change-plan-type', 'changePlanType')->name('car.changeplantype');
//             Route::any('/pacoverreason', 'paCoverReason')->name('car.pacoverreason');
//             Route::any('/removeaddon', 'removeAddon')->name('car.removeaddon');


//             // Route::any('/getcarquote', 'CarQuoteGenerateStream')->name('car.getcarquote');
//         });

//         Route::controller(PlanController::class)->group(function () {
//             Route::any('/getcarquote', 'CarQuoteGenerateStream')->name('car.getcarquote');
//             Route::any('/getcachecarquote', 'CacheCarQuoteGenerateStream')->name('car.getcachecarquote');
//             //Route::any('/getcachecarquote', 'testCarQuoteGenerateStream')->name('car.getcachecarquote');
//         });
//     });
// });


Route::group(['prefix' => 'motor-car'], function () {
    Route::controller(CarController::class)->group(function () {
        Route::group(['middleware' => 'App\Http\Middleware\Api\JourneyAuth'], function () {
            Route::any('/kn-car-details', 'KnowCarDetails');
            Route::any('/kn-car-steptwo', 'KnowCarsteptwo');
            Route::any('/knowcarstepthree', 'knowcarStepthree');
            Route::any('/new-car-details', 'NewCarDetails');
            Route::any('/new-car-detailstwo', 'newcarSteptwo');
            Route::any('/plans', 'carPlan');
            Route::any('/addaddon', 'addAddon');
            Route::any('/accessories', 'addAccessories');
            Route::any('/updateidv', 'updateIdv');
            Route::any('/change-plan-type', 'changePlanType');
            Route::any('/pacoverreason', 'paCoverReason');
            Route::any('/removeaddon', 'removeAddon');
        });
    });

    Route::controller(PlanController::class)->group(function () {
        Route::group(['middleware' => 'App\Http\Middleware\Api\JourneyAuth'], function () {
            Route::any('/getcarquote', 'CarQuoteGenerateStream');
            Route::any('/getcachecarquote', 'CacheCarQuoteGenerateStream');
        });
    });
});