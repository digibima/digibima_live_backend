<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use App\Models\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class MotorAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //
        // dd(Auth::check());
        //     if (Auth::check()) 
        //     {
        //         return $next($request);
        //     }
        //     return redirect()->route('motor.root');
        // }
        $token = $request->header('Authorization');
        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Authorization token missing'
            ]);
        }
        $isToken = PersonalAccessToken::findToken($token);
        if (!$isToken) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid token'
            ]);
        }
        $request->merge([
            'userid' => $isToken->tokenable_id,
            'istoken' => $isToken,
        ]);

        return $next($request);
    }
}
