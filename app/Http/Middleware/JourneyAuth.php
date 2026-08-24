<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Models\PersonalAccessToken;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\{Auth,Session,Gate};
class JourneyAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //dd(Auth::check());

        // $token = $request->header('Authorization');
        // $isToken = PersonalAccessToken::findToken($token);
        // $userId = $isToken->tokenable_id ;

        if(Auth::check())
        {
            return $next($request);
        }
        return redirect()->route('root');
    }
}
