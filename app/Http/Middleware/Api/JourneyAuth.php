<?php

namespace App\Http\Middleware\Api;

use App\Models\PersonalAccessToken;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response;
use Closure;

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
                'status' => false,
                'message' => 'Authorization token missing'
            ]);
        }
        $isToken = PersonalAccessToken::findToken($token);
        if (!$isToken) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid token',
                'token' => $token
            ]);
        }
        $request->merge([
            'userid' => $isToken->tokenable_id,
            'istoken' => $isToken,
        ]);

        try {
            $userid = $isToken->tokenable_id;
            $key = "visitor:$userid";
            if (!Redis::exists($key)) {
                Redis::setex($key, 86400, 1);
                Visitor::insert([
                    'userid' => $userid,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                Redis::incr('visitor:total');
            }
            return $next($request);
        } catch (\Exception $e) {
            return Err($e);
        }
        return $next($request);
    }
}
