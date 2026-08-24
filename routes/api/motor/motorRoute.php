<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\front\motor\{MotorController};
use App\Http\Middleware\Api\JourneyAuth;
// Route::group(['prefix' => 'motor'], function () {
//     Route::controller(MotorController::class)->group(function () {
//         Route::any('/', function(){
//             return redirect()->route('motor.root.login');
//         })->name('motor.root');
//         Route::any('/motor-login','index')->name('motor.root.login');
//         Route::any('/vehicle-type-select/{id?}', 'login')->name('motor.login');
//         Route::any('/acpincode' . generateRandomString('acpincode'), 'AutoCompletePincode')->name('motor.acpincode');
//         Route::any('/getcity', 'getCity')->name('motor.getcity');
//         Route::any('/getbrand', 'showBrand')->name('car.getbrand');
//         Route::any('/getmodel', 'showModel')->name('car.getmodel');
//         Route::any('/verifyrto', 'verifyRto')->name('motor.verifyrto');
//         Route::any('/bikeverifyrto', 'verifyBikeRto')->name('motor.bikeverifyrto');
//     });
// });


Route::group(['prefix' => 'motor'], function () {
    Route::controller(MotorController::class)->group(function () {
        Route::any('/vehicle-type-select', 'login');
        Route::any('/acpincode', 'AutoCompletePincode');
        Route::middleware(JourneyAuth::class)->group(function () {
            Route::any('/getcity', 'getCity');
            Route::any('/getbrand', 'showBrand');
            Route::any('/getmodel', 'showModel');
            Route::any('/verifyrto', 'verifyRto');
            Route::any('/bikeverifyrto', 'verifyBikeRto');
        });
    });
});
