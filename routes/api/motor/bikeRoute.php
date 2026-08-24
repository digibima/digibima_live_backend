<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
// use App\Http\Controllers\Api\front\motor\Bike\{BikeController, PlanController};
use App\Http\Controllers\Api\front\motor\Bike\{BikeController as ApiBikeController, PlanController as ApiPlanController};
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\Api\JourneyAuth;


Route::group(['prefix' => 'motor-bike'], function () {
    Route::controller(ApiBikeController::class)->group(function () {
        Route::middleware(JourneyAuth::class)->group(function () {
            Route::any('/kn-bike-details', 'KnowBikeDetails');
            Route::any('/kn-bike-step-two', 'KnowBikesteptwo');
            Route::any('/kn-bike-step-three', 'knowbikeStepthree');
            Route::any('/new-bike-details', 'newBikeDetails');
            Route::any('/new-bike-step-two', 'newbikeSteptwo');
            Route::any('/bike-plan', 'bikePlan');
            Route::any('/addaddon', 'addAddon');
            Route::any('/updateidv', 'updateIdv');
            Route::any('/change-plan-type', 'changePlanType');
            Route::any('/pacoverreason', 'paCoverReason');
            Route::any('/bike-journey', 'bikejourney');
        });
    });


    Route::controller(ApiPlanController::class)->group(function () {
        Route::middleware(JourneyAuth::class)->group(function () {
            Route::any('/getbikequote', 'BikeQuoteGenerateStream');
            Route::any('/getcachebikequote', 'CacheBikeQuoteGenerateStream');
        });
    });
});


// Route::group(['prefix' => 'motor-bike'], function () {

//     Route::controller(BikeController::class)->group(function () {

//         Route::any('/kn-bike-details/{id?}', 'KnowBikeDetails')->name('bike.knowbike');

//          Route::any('/kn-bike-detailstwo/{id?}', 'KnowBikeDetailstwo')->name('bike.knowbiketwo');

//         Route::any('/kn-bike-step-two/{id?}', 'knowbikeSteptwo')->name('bike.knowbikesteptwo');
//         Route::any('/new-bike-details/{id?}', 'newBikeDetails')->name('bike.newbike');
//         Route::any('/new-bike-step-two/{id?}', 'newbikeSteptwo')->name('bike.newbikesteptwo');
//         Route::any('/bike-plan/{id?}', 'bikePlan')->name('bike.plans');
//         Route::any('/addaddon', 'addAddon')->name('bike.addaddon');
//         Route::any('/updateidv', 'updateIdv')->name('bike.updateidv');
//         Route::any('/change-plan-type', 'changePlanType')->name('bike.changeplantype');

//          Route::any('/pacoverreason', 'paCoverReason')->name('bike.pacoverreason');

//         Route::any('/bike-journey', 'bikejourney')->name('bike.journey');
//     });

//     Route::controller(PlanController::class)->group(function () {
//         Route::any('/getbikequote', 'BikeQuoteGenerateStream')->name('bike.getbikequote');
//         Route::any('/getcachebikequote', 'CacheBikeQuoteGenerateStream')->name('bike.getcachebikequote');
//     });
// });
