<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaintainanceMiddleware
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
        //dd('uu');
       //$urlArray = ['motor','motor/*','motor-*'];
        $urlArray = [];
        foreach($urlArray as $url)
        {
            if ($request->is($url)) {
                echo  "<h1 style=\"color:red\">Under Maintenance....</h1>";die;
            }
        }
        
        return $next($request);
    }
}
