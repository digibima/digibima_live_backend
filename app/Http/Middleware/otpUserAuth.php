<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use  App\Http\Controllers\Front;
use App\Models\User;
use Auth,Mail,Validator;
class otpUserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //dd($request);
        $oResponse = User::where('mobile',encodeMobile($request->mobile))->first();
        if($oResponse && $oResponse->role=="admin")
        {
            return response()->json(['status'=>'0','message'=>"You are admin"]);
        }
        return $next($request);
    }
}
