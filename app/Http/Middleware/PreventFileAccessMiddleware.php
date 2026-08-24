<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventFileAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        //dd($request->query);
       $urlArray = [''];
        //$urlArray = [];//
        foreach($urlArray as $url)
        {
            if ($request->is($url)) {
                abort(403);
            }
        }
        
        return $next($request);
    }
}
