<?php

// bootstrap/app.php
// ─────────────────────────────────────────────────────────────────
//  Laravel 11 Application Bootstrap — Phase 3 Auth Configuration
// ─────────────────────────────────────────────────────────────────

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
 


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        apiPrefix: 'api',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // ── Register custom middleware aliases ─────────────────
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'hotelier.approved' => \App\Http\Middleware\HotelierApproved::class,
        ]);

        // Admin routes will use the 'admin' middleware group defined in app/Http/Kernel.php
        // which includes the custom AdminMiddleware for access control.
        $middleware->alias([
            'hotelier' => \App\Http\Middleware\HotelierMiddleware::class,
            'customer' => \App\Http\Middleware\CustomerMiddleware::class,
            'admin'    => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        // ── Global web middleware (already included by default) ─
        // - EncryptCookies
        // - AddQueuedCookiesToResponse
        // - StartSession
        // - ShareErrorsFromSession
        // - VerifyCsrfToken           ← protects all POST forms
        // - SubstituteBindings
    
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
