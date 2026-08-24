<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'motor-bike-shriram/thankyou',
        'motor-car-shriram/thankyou',
        'api/*',
        'api/bike-thankyou',
        'api/bike-thankyou/*',
        'https://test.digibima.com/api/bike-thankyou'
    ];
}
