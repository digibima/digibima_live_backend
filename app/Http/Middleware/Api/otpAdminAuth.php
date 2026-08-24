<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Front;
use App\Models\User;
use Auth, Mail, Validator;
class otpAdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //dd($request);
        $arequest = $request->data;
        $oResponse = User::where('mobile', encodeMobile($arequest['mobile']))->first();

        if (!$oResponse || $oResponse->role != "admin") {
            return response()->json([
                'status' => '0',
                'message' => "User doesn't exist as admin"
            ]);
        }
        return $next($request);
    }
}
