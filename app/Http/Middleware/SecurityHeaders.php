<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Gate;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\{Session, Auth};

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Powered-By', 'Digibima');
        $response->headers->set('Referrer-Policy', 'no-referrer');
        $response->headers->set('Permissions-Policy', 'fullscreen=(self;); camera=(self;); microphone=(self;);');
        $response->headers->set(
            'Content-Security-Policy',
            "default-src 'self'; script-src 'self'; object-src 'none';"
        );
        if (!$request->secure()) {
            return redirect()->secure($request->getRequestUri());
        }

        return $response;
    }
}
