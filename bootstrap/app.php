<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\LandlordAuth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        then: function () {
            // register tenant subdomain routes
            Route::middleware('web')
                ->group(base_path('routes/tenant.php'));
        },
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // reg landlord auth middleware
        $middleware->alias([
            'landlord.auth' => LandlordAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
