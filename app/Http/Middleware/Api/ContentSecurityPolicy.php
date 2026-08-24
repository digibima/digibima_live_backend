<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Gate;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\{Session, Auth};
class ContentSecurityPolicy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $nonce = base64_encode(random_bytes(16));
        $cspHeader = "default-src 'self'; script-src 'self' 'nonce-$nonce'; style-src 'self' 'nonce-$nonce'; object-src 'none'";
        //$response->headers->set('Content-Security-Policy', $cspHeader);
        $response->headers->set('Content-Security-Policy', " script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'");
        session(['csp_nonce' => $nonce]);
        \Log::info('CSP Middleware applied with nonce: ' . $nonce);
        return $response;
    }
}
