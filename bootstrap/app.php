<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Trust proxies for proper HTTPS handling on Render
        $middleware->trustProxies(at: '*');
        
        $middleware->validateCsrfTokens(except: [
            'client/*',
            'login/contractor',
        ]);
        
        // Register custom middleware aliases
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'verified.contractor' => \App\Http\Middleware\EnsureContractorVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            if ($request->is('client/*') || $request->is('dashboard/client*')) {
                return redirect()->route('login.client')->with('error', 'Your session has expired. Please log in again.');
            }
            if ($request->is('contractor/*') || $request->is('dashboard/contractor*')) {
                return redirect()->route('login.contractor')->with('error', 'Your session has expired. Please log in again.');
            }
            if ($request->is('admin/*')) {
                return redirect()->route('login.admin')->with('error', 'Your session has expired. Please log in again.');
            }
            
            return redirect()->route('login.contractor')->with('error', 'Your session has expired. Please log in again.');
        });
    })->create();
