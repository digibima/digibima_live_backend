<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Models\Visitor;
class VisitorsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $ip = $request->ip();
            $key = "visitor:$ip";
            if (!Redis::exists($key)) {
                Redis::setex($key, 86400, 1);
                Visitor::insert([
                    'ip' => $ip,
                    'user_agent' => $request->userAgent(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                Redis::incr('visitor:total');
            }

            return $next($request);
        } catch (\Exception $e) {
            return Err($e);
        }
    }
}