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
        // Регистрация alias для вашего middleware
        $middleware->alias([
            'checkAdmin' => \App\Http\Middleware\CheckAdmin::class,
            'checkAccount' => \App\Http\Middleware\CheckAccount::class,
            // Можно добавить другие middleware с alias
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
