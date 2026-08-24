<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\OTPVerificationController;
use Illuminate\Http\Request;
use App\Models\{User, MasterHealth, MasterVendor};
use App\Http\Controllers\UserController;
use App\Http\Controllers\front\motor\Car\{PlanController as Car};
use App\Http\Controllers\front\motor\Bike\{PlanController as Bike};
use App\Http\Controllers\front\health\vendor\caresupreme\CareSupremeController as Care;
use App\Http\Controllers\front\health\PlanController as plan;
use App\Http\Controllers\front\health\vendor\ultimatecare\UltimateController;
// routes/web.php
Route::any('/testcarquote', [Car::class, 'TestQuotation']);
Route::any('/testbikequote', [Bike::class, 'TestBikeQuote']);

// Route::any('/genpin', function () {
//     $data = json_encode(['101' => '1512', '102' => '1511', '104' => '1515', '111' => '1517', '113' => '1514', '114' => '1516', '109' => '1502', '108' => '1503', '110' => '1507', '115' => '1508']);
//     dd($data);
// });
Route::any('/testquote', [plan::class, 'HealthQuote']);
Route::any('/testaddon', [UltimateController::class, 'showAddon']);
//Route::any('/carequote', [Care::class,'TestBikeQuote']);
Route::any('/addontest', function () {
  $aDBMaster = MasterVendor::where('vid', getconstant('HEALTH.ULTIMATECARE.KEY'))->get(['healthaddons']);
  $aDBMaster = json_decode($aDBMaster[0]->healthaddons, true);
  $addonIds = array_keys($aDBMaster);
  $aAddons = MasterHealth::whereIn('key', $addonIds)->pluck('addon', 'key');
  dd($aDBMaster, $aAddons);
});

Route::any('/godigit-auth', function (Request $request) {
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://preprod-oneapi.godigit.com/OneAPI/digit/generateAuthKey',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => '{
    "username": "35327650",
    "password": "Digit@123$"
}',
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json'
    ),
  ));
  $response = curl_exec($curl);
  curl_close($curl);
  echo $response;
  dd($response);

});