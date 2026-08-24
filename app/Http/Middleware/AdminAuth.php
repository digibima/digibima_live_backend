<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Gate;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\{Session,Auth};
class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       
        if (!auth()->check()) {
            return redirect()->route('admin.root');
        }
        // if (Gate::allows('is-admin')) {
        //     abort(403, 'Unauthorized');
        // }
        // dd("ghgg");
        return $next($request);
    }
}
