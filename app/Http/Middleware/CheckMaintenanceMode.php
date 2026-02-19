<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     * - If maintenance mode is OFF: let everyone through.
     * - If maintenance mode is ON:
     *     - Logged-in admins pass through.
     *     - Everyone else is redirected to /maintenance.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->isMaintenanceModeEnabled()) {
            // Always allow these routes through (maintenance page itself + auth routes)
            if ($request->is('maintenance', 'login', 'logout', '_debugbar/*')) {
                return $next($request);
            }

            // Allow logged-in admins to bypass
            if (Auth::check() && Auth::user()->role === 'admin') {
                return $next($request);
            }

            // Redirect everyone else to the maintenance screen
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Service is currently under maintenance. Please contact the administrator.',
                ], 503);
            }

            return redirect('/maintenance');
        }

        return $next($request);
    }

    /**
     * Check whether our custom maintenance lock file exists.
     */
    private function isMaintenanceModeEnabled(): bool
    {
        return file_exists(storage_path('framework/maintenance.lock'));
    }
}
