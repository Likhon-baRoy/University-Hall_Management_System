<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
                  ->withRouting(
                    web: __DIR__.'/../routes/web.php',
                    commands: __DIR__.'/../routes/console.php',
                    health: '/up',
                  )
                  ->withMiddleware(function (Middleware $middleware) {
                    // Register middlleware
                    $middleware -> alias([
                      'admin' => App\Http\Middleware\Admin\AdminAuthMiddleware::class,
                      'admin.redirect' => App\Http\Middleware\Admin\AdminAuthRedirectMiddleware::class,
                    ]);
                  })
                  ->withExceptions(function (Exceptions $exceptions) {
                    //
                  })->create();
