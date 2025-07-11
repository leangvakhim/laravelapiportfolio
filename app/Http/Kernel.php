<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
    ];

    protected $middlewareGroups = [
        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    protected $routeMiddleware = [
        // 'auth' => \App\Http\Middleware\Authenticate::class,
        'jwt.cookie' => \App\Http\Middleware\JwtCookieAuth::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        // Add other route middleware as needed
    ];
}
