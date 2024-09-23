<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\premiumcoursesmiddleware;
use App\Http\Middleware\Adminmiddleware;
use App\Http\Middleware\genericspecialcoursemiddleware;
use App\Http\Middleware\studentcourseaccessmiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->alias([
            // my-other-middleware
            'premiumuser' => premiumcoursesmiddleware::class,
            'admin' => Adminmiddleware::class,
            'checkspecial' => genericspecialcoursemiddleware::class,
            'checkifenrolled' => studentcourseaccessmiddleware::class,
        ]);

        $middleware->append(RoleMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
