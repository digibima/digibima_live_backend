<?php

namespace App\Http\Middleware\Api;
use Illuminate\Support\Facades\Gate;
use Closure;
use Illuminate\Http\Request;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\{Session, Auth};
class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');
        $isToken = PersonalAccessToken::findToken($token);

        if (!$isToken) {
            return response()->json([
                'message' => 'Invalid token.'
            ], 401);
        }

        $user = User::find($isToken->tokenable_id);

        if (!$user || $user->role !== 'admin') {
            return response()->json(['message' => "Access denied. User can't access this page."], 403);
        }
         $request->merge([
            'userid' => $isToken->tokenable_id,
            'istoken' => $isToken,
        ]);

        return $next($request);

        
    }
}

