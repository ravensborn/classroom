<?php

use App\Enums\UserRole;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
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
        $middleware->trustProxies('*');
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureRole::class,
        ]);

        RedirectIfAuthenticated::redirectUsing(function ($request) {
            $user = $request->user();

            return match ($user->role) {
                UserRole::Admin => route('admin.dashboard'),
                UserRole::Teacher => route('teacher.dashboard'),
                UserRole::Student => route('student.dashboard'),
            };
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
