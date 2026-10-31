<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request for admin routes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $guard = Auth::guard('admin');

        if (! $guard->check()) {
            return redirect()->route('admin.login')->with('warning', 'Please sign in to access the administration portal.');
        }

        $user = $guard->user();

        if (! $user->isAdmin()) {
            $guard->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')->withErrors(['email' => 'Access denied. Administrator privileges required.']);
        }

        if ($user->isLocked()) {
            $remaining = now()->diffInMinutes($user->locked_until, false) + 1;
            $guard->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')->withErrors([
                'email' => "Your account is temporarily locked due to multiple failed login attempts. Please try again in {$remaining} minutes.",
            ]);
        }

        // Forced password change on first login
        if ($user->must_change_password && ! $request->routeIs('admin.password.force', 'admin.password.update', 'admin.logout')) {
            return redirect()->route('admin.password.force')->with('info', 'For your security, you must update your temporary password before proceeding.');
        }

        return $next($request);
    }
}
