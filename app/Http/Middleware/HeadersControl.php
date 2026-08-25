<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HeadersControl
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        /* ================= CACHE CONTROL ================= */
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        /* ================= SECURITY HEADERS ================= */

        // Prevent Clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Prevent MIME sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // XSS Protection (older browsers)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions Policy (disable risky features)
        $response->headers->set('Permissions-Policy', 'geolocation=(), camera=(), microphone=()');

        // HSTS (only enable if HTTPS is used)
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        /* ================= CONTENT SECURITY POLICY ================= */
        $csp = "
            default-src 'self' https:;
    script-src 'self' 'unsafe-inline' 'unsafe-eval' https:;
    style-src 'self' 'unsafe-inline' https:;
    img-src 'self' data: https:;
    font-src 'self' data: https:;
    connect-src 'self' https:;
    frame-ancestors 'self';
    base-uri 'self';
    form-action 'self';
    object-src 'none';
        ";
        $response->headers->set('Content-Security-Policy', preg_replace('/\s+/', ' ', trim($csp)));

        /* ================= EXTRA SECURITY ================= */

        // Hide server info
        $response->headers->set('Server', 'SecureServer');
        $response->headers->set('X-Powered-By', 'SecureApp');

        return $response;
    }
}