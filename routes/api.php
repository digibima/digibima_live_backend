<?php
use App\Http\Controllers\Api\{OTPVerificationController as ApiOTPVerificationController, SystemController as ApiSystemController, InsuranceUploadController};
use App\Http\Controllers\Api\QuoteComparisonController;
use App\Http\Middleware\Api\ClickLimiter;
use App\Mail\PDFMail;
use App\Models\{Pincode, CarePincode};
use App\Services\Api\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Route, Storage};
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
// use App\Http\Controllers\Api\MailController;
use App\Http\Controllers\CelitixController;
use App\Http\Middleware\Api\JourneyAuth;

Route::any('/device', function (Request $request) {
    // $deviceHeader = $request->header('X-Device-Id');
    return response()->json('device1');
})->middleware('api');
Route::group([
    'middleware' => [
        'App\Http\Middleware\Cors',
        'App\Http\Middleware\PreventFileAccessMiddleware',
        'App\Http\Middleware\SecurityHeaders',
        'App\Http\Middleware\CacheControl',
        // 'App\Http\Middleware\ContentSecurityPolicy',
        'App\Http\Middleware\ErrorHandlerMiddleware'
    ]
], function () {
    Route::controller(CelitixController::class)->group(function () {
        Route::any('/sendceletixwpmsg', 'SendMsg');
        Route::any('/getceletixwpmsg', 'GetMsg');
        Route::any('/triggerwpmsg', 'ReponseMsg');
    });
    Route::controller(InsuranceUploadController::class)->group(function () {
        Route::any('/upload_poilcy', 'UploadPolicy');
        Route::any('/admin-employee-login', 'Login');
        Route::any('/admin-employee-logout', 'Logout');
    });
    Route::post('/vendors', [InsuranceUploadController::class, 'getVendors']);
    Route::any('/test-minio', function (Request $request) {
        try {
            // return [
            //     'error' => 'yuyuy'
            // ];
            $content = null;
            $path = null;
            if ($request->has('document')) {
                $path = $request->document;
            } else {
                return [
                    'request' => $request->all(),
                ];
            }
            $uploaded = Storage::disk('minio')->putFile(
                'test',
                $path
            );
            return [
                'uploaded' => $uploaded,
                'url' => Storage::disk('minio')->url($uploaded),
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    });
    Route::controller(ImageService::class)->group(function () {
        Route::any('/extract-text', 'ExtractText');
    });
    Route::any('api/pincode', function (Request $request) {
        if (empty($request->pincode) || strlen($request->pincode) < 5) {
            return response()->json(['status' => 0, 'message' => 'Enter valid pincode']);
        }
        $pincodeData = CarePincode::where('pincode', 'like', $request->pincode . '%')->get(['district', 'state', 'pincode']);
        return response()->json($pincodeData);
    })->middleware('api');

    Route::any('/', function (Request $request) {
        return response()->json([
            'status' => 1,
            'message' => 'API works!',
            'data' => '',
        ]);
    });
    Route::post('/compare-quotes', [
        QuoteComparisonController::class,
        'compare'
    ]);

    Route::controller(ApiOTPVerificationController::class)->group(function () {
        // Route::middleware(ClickLimiter::class)->group(function () {
        Route::any('/sendotp', 'sendOTPApi');
        Route::any('/verifyotp', 'verifyOTPApi');
        // });
    });
    Route::any('/mailto', function (Request $request) {
        try {
            $data = [
                'name' => 'John Doe',
                'message' => 'This is a test email from Laravel.'
            ];

            // Mail::to('welldheeraj.100@gmail.com')->send(new PDFMail($data));
            Mail::raw('Test Mail', function ($m) {
                $m->to('welldheeraj.100@gmail.com')->subject('SMTP Test');
            });
            // return "Mail sent successfully!";
            return response()->json([
                'status' => true,
                'message' => 'Mail sent successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    });
    Route::controller(ApiSystemController::class)->group(function () {
        Route::any('/relogin', 'ReLogin');
        Route::any('/logout', 'LogoutToken');
        Route::any('/verifytoken', 'verifyToken');
        Route::any('/acpincode', 'AutoCompletePincodeApi');
    });

    Route::get('/optimize', function () {
        Artisan::call('optimize');
        Artisan::call('route:clear');
        Artisan::call('config:cache');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        return response()->json(['status' => true, 'message' => 'Optimized']);
    });

    include (base_path('routes/api/motor/bikeRoute.php'));
    include (base_path('routes/api/health/healthRoute.php'));
    include (base_path('routes/api/vendors/caresupremeRoute.php'));
    include (base_path('routes/api/vendors/ultimatecareRoute.php'));
    include (base_path('routes/api/vendors/bajajhealthRoute.php'));
    // nclude(base_path('routes/api/vendors/bajajhealthcareplan9Route.php'));
    // include(base_path('routes/api/vendors/zunomotorRoute.php'));
    include (base_path('routes/api/vendors/shriramRoute.php'));
    include (base_path('routes/api/vendors/godigitRoute.php'));

    // include(base_path('routes/api/vendors/bajajmotorRoute.php'));
    // include(base_path('routes/api/vendors/adityabirlaRoute.php'));
    // include(base_path('routes/api/vendors/adityabirlamaxRoute.php'));

    include (base_path('routes/api/motor/motorRoute.php'));
    include (base_path('routes/api/motor/carRoute.php'));
    include (base_path('routes/api/user/userRoute.php'));
    include (base_path('routes/api/admin/adminRoute.php'));
    // include(base_path('routes/api/testRoute.php'));
})->middleware('api');

// Route::controller(MailController::class)->group(function () {
//      Route::any('/pdf', 'TexttoPdf');
//     Route::middleware(JourneyAuth::class)->group(function () {
//         Route::any('/mail/usersend', 'sendMail');
//         Route::any('/upload/pdf', 'Savepdf');
//         // Route::any('/pdf', 'TexttoPdf');
//     });
// });
