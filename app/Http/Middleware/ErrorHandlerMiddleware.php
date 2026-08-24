<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ErrorHandlerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            return $next($request);
        } catch (\Exception $e) {
            // Handle specific exceptions
            if ($e instanceof \Illuminate\Database\QueryException) {
                // Handle database query exceptions
                return response()->json(['error' => 'Database error occurred'], 500);
            } elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                // Handle not found errors
                return response()->json(['error' => 'Resource not found'], 404);
            } else {
                // Handle other exceptions
                return response()->json(['error' => 'Something went wrong'], 500);
            }
        }
    }
}
