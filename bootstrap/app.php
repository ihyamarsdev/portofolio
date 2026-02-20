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
    ->withMiddleware(function (Middleware $middleware): void {
        // SECURITY: In production, specify trusted proxy IPs instead of '*'
        // For cloud deployments (AWS, Cloudflare, etc.), use:
        // $middleware->trustProxies(at: '*'); // Only when using proper proxy headers
        // Or specify IPs:
        // $middleware->trustProxies(at: '192.168.1.1', '10.0.0.1');
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
