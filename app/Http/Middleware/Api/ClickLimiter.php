<?php

namespace App\Http\Middleware\Api;
use Closure;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

class ClickLimiter
{
    public function handle($request, Closure $next)
    {

        $maxAttempts = 10;
        $decaySeconds = 60 * 60;
        $key = sha1($request->ip() . '|' . $request->route()->uri);

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = RateLimiter::availableIn($key);

            return response()->json([
                'status' => false,
                'message' => 'You have reached the click limit. Please try again later.'
            ]);
        }
        RateLimiter::hit($key, $decaySeconds);

        return $next($request);
    }
}
