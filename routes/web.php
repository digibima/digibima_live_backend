<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\OTPVerificationController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\KYCController;
use App\Http\Controllers\UserController;
use App\Models\CarePincode;

Route::controller(SystemController::class)->group(function () {
    Route::any('/optimize', 'optimize')->name('optimize');
    Route::any('/mode-down', 'MModeDown')->name('mmodea');
    Route::any('/mode-up', 'MModeUp')->name('mmoded');
    Route::any('/logout', 'Logout')->name('logout');
});
Route::controller(KYCController::class)->group(function () {
    Route::any('/gentoken', 'genToken');
});
Route::any('/test', function () {
    return "live server";
});
Route::any('/stringconvertor', function (Request $request) {
    if ($request->method() === 'GET') {
        return view('front.convertor');
    }

    if ($request->method() === 'POST') {
        $sStr = $request->data;
        $action = $request->action;

        if (empty($sStr)) {
            return ['status' => '0', 'message' => 'Data is empty'];
        }

        if ($action === 'encrypt') {
            try {
                $encrypted = Crypt::encrypt($sStr);
                return ['status' => '1', 'type' => 'encrypt', 'string' => $encrypted];
            } catch (\Exception $e) {
                return ['status' => '0', 'message' => 'Encryption failed', 'error' => $e->getMessage()];
            }
        }

        if ($action === 'decrypt') {
            try {
                $decrypted = Crypt::decrypt($sStr);
                return ['status' => '1', 'type' => 'decrypt', 'string' => $decrypted];
            } catch (\Exception $e) {
                return ['status' => '0', 'message' => 'Decryption failed', 'error' => $e->getMessage()];
            }
        }

        return ['status' => '0', 'message' => 'Invalid action'];
    }
})->name('convertstring');


