<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function () {
        return [
            \Illuminate\Session\Middleware\StartSession::class,
            \App\Http\Middleware\SetLocale::class,
        ];
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();


  