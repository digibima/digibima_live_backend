<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Cors
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // // Handle preflight request
        // if ($request->isMethod('OPTIONS')) {
        //     return response()->json('OK', 200, [
        //         'Access-Control-Allow-Origin' => '*',
        //         'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
        //         'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With',
        //     ]);
        // }

        $response = $next($request);

        // Add CORS headers to all responses
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');

        return $response;
    }
}
