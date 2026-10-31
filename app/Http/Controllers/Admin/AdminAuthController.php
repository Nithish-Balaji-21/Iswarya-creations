<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    /**
     * Show admin login view.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    /**
     * Handle admin login attempt with rate limiting & lockout.
     */
    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = 'admin-login:'.Str::lower($validated['email']).'|'.$request->ip();

        // Check general IP/key rate limiting (10 attempts per minute)
        if (RateLimiter::tooManyAttempts($throttleKey, 10)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withInput($request->only('email', 'remember'))->withErrors([
                'email' => "Too many attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        // Find user by email
        $user = User::where('email', $validated['email'])->first();

        // Check account-level lockout (5 failed attempts -> 15-minute lock)
        if ($user && $user->isLocked()) {
            $remaining = now()->diffInMinutes($user->locked_until, false) + 1;

            return back()->withInput($request->only('email', 'remember'))->withErrors([
                'email' => "Account is temporarily locked due to 5 consecutive failed login attempts. Please try again in {$remaining} minutes.",
            ]);
        }

        $remember = $request->boolean('remember');

        // Verify credentials with admin guard
        if ($user && $user->isAdmin() && Hash::check($validated['password'], $user->password)) {
            RateLimiter::clear($throttleKey);
            $user->resetFailedAttempts();

            Auth::guard('admin')->login($user, $remember);
            $request->session()->regenerate();

            if ($user->must_change_password) {
                return redirect()->route('admin.password.force')->with('info', 'Please set a permanent password for your administrator account.');
            }

            return redirect()->intended(route('admin.dashboard'));
        }

        // On failure: increment attempts
        RateLimiter::hit($throttleKey, 60);

        if ($user && $user->isAdmin()) {
            $user->incrementFailedAttempts(
                max: (int) config('iswarya.max_login_attempts', 5),
                lockMinutes: (int) config('iswarya.lockout_minutes', 15)
            );

            if ($user->isLocked()) {
                return back()->withInput($request->only('email', 'remember'))->withErrors([
                    'email' => 'Maximum login attempts exceeded. Your account has been locked for 15 minutes for security.',
                ]);
            }
        }

        return back()->withInput($request->only('email', 'remember'))->withErrors([
            'email' => 'The provided credentials do not match our records or you do not have administrative access.',
        ]);
    }

    /**
     * Show forced password change view for first-time login.
     */
    public function showChangePasswordForm(): View|RedirectResponse
    {
        $user = Auth::guard('admin')->user();
        if (! $user) {
            return redirect()->route('admin.login');
        }

        return view('admin.auth.change-password', compact('user'));
    }

    /**
     * Process forced password change.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = Auth::guard('admin')->user();
        if (! $user) {
            return redirect()->route('admin.login');
        }

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
            'must_change_password' => false,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Your password has been successfully updated. Welcome to Iswarya Silks Management Portal.');
    }

    /**
     * Admin logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'You have been securely logged out.');
    }
}
