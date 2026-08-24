<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;

class SecureCookiesMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        // Ensure XSRF-TOKEN is secure
        Cookie::queue(
            Cookie::make('XSRF-TOKEN', $request->session()->token(), 120, '/', null, true, false, false, 'NONE')
        );

        return $response;
    }
}
