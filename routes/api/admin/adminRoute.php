<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OTPVerificationController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\admin\{AdminController, UserManagerController, VendorController};


// Route::group([
//     'prefix' => 'adminpnlx',
//     'middleware' => [
//         \App\Http\Middleware\Api\MaintainanceMiddleware::class,
//         ]
// ], function () {

Route::group(['prefix' => 'adminpnlx',], function () {
    //---------------------------blogRoute----------------added-here-------------------------
    include(base_path('routes/api/admin/blogRoute.php'));

    Route::controller(OTPVerificationController::class)->group(function () {
        Route::group(['middleware' => 'App\Http\Middleware\Api\otpAdminAuth'], function () {
            Route::any('/sendotp', 'sendOTPApi');
            Route::any('/verifyotp', 'verifyOTPApi');
        });
    });
    Route::controller(AdminController::class)->group(function () {
        Route::any('/admin-login', 'Login');
        Route::group(['middleware' => 'App\Http\Middleware\Api\AdminAuth'], function () {
            Route::any('/admin-dashboard', 'Dashboard');
            Route::any('/admin-policy', 'getPolicy');
            Route::any('/manage-plan', 'Manageplan');
            Route::any('/edit-footer', 'EditFooter');
            Route::any('/manage-product', 'Manageproduct');
            Route::any('/getaddon', 'getAddons');
            Route::any('/manage-saveplan', 'ManageSaveplan');
            Route::any('/recycle-bin', 'RecycleBin');
            Route::any('/manage-vendor', 'index');
            Route::any('/add-vendor', 'AddManagevendor');
            Route::any('/update-vendor', 'UpdateManagevendor');
            Route::any('/addnewsave-vendor', 'AddNewSaveVendor');
            Route::any('/edit-vendor', 'editVendor');
            Route::any('/delete-product', 'deleteVendor');
            Route::any('/recent-login', 'RecentLogin');
            Route::group(['middleware' => 'App\Http\Middleware\Api\autoAdminAuth'], function () {
                Route::any('/manage-updateplan', 'ManageUpdateplan');
                Route::any('/manage-deleteplan', 'deletePlan');
            });
        });
    });
    Route::controller(UserManagerController::class)->group(function () {
        Route::group(['middleware' => 'App\Http\Middleware\Api\AdminAuth'], function () {
            Route::any('/manage-user', 'index');
            Route::any('/user-policies', 'getUserPolicies');
        });
    });
    // Route::controller(VendorController::class)->group(function () {
    //     Route::group(['middleware' => 'App\Http\Middleware\Api\AdminAuth'], function () {
    //         Route::any('/manage-vendor', 'index');
    //         Route::any('/add-vendor', 'AddManagevendor');
    //         Route::any('/update-vendor', 'UpdateManagevendor');
    //         Route::any('/addnewsave-vendor', 'AddNewSaveVendor');
    //         Route::any('/edit-vendor', 'editVendor');
    //         Route::any('/delete-product', 'deleteVendor');

    //     });
    // });

});

