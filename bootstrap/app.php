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
        $middleware->redirectGuestsTo(fn (Illuminate\Http\Request $request) => route('login'));
        $middleware->redirectUsersTo(function (Illuminate\Http\Request $request) {
            $user = $request->user();
            if ($user && $user->role === 'admin') {
                return route('admin.dashboard');
            }
            return route('dashboard', ['username' => Illuminate\Support\Str::slug($user->name)]);
        });

        // Maintenance mode: blocks non-admins when storage/framework/down exists
        $middleware->web(append: [
            \App\Http\Middleware\CheckMaintenanceMode::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
