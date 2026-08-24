<?php

namespace App\Http\Middleware\Api;

use Closure;
use App\Models\PersonalAccessToken;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JourneyAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');
        if (!$token) {
            return response()->json([
                'status'=>false,
                'message' => 'Authorization token missing'
            ]);
        }
        $isToken = PersonalAccessToken::findToken($token);
        if (!$isToken) {
            return response()->json([
                'status'=>false,
                'message' => 'Invalid token'
            ]);
        }
        $request->merge([
            'userid' => $isToken->tokenable_id,
        ]);

        return $next($request);
    }
}
