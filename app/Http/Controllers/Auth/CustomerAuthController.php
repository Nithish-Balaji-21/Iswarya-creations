<?php

namespace App\Http\Controllers\Auth;

use App\Enums\OtpPurpose;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Otp\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class CustomerAuthController extends Controller
{
    public function __construct(
        protected OtpService $otpService
    ) {}

    public function showLogin(): View|RedirectResponse
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('account.dashboard');
        }

        return view('auth.login');
    }

    public function loginWithPassword(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'identifier' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $identifier = trim($credentials['identifier']);
        $throttleKey = 'customer-login:'.Str::lower($identifier).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withInput($request->only('identifier', 'remember'))->withErrors([
                'identifier' => "Too many login attempts. Please wait {$seconds} seconds.",
            ]);
        }

        // Determine if identifier is email or phone
        $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL);
        $user = $isEmail
            ? User::where('email', $identifier)->first()
            : User::where('phone', $identifier)
                ->orWhere('phone', '+91'.preg_replace('/[^0-9]/', '', $identifier))
                ->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            RateLimiter::clear($throttleKey);
            Auth::guard('web')->login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->intended(route('account.dashboard'))->with('success', "Welcome back, {$user->name}!");
        }

        RateLimiter::hit($throttleKey, 60);

        return back()->withInput($request->only('identifier', 'remember'))->withErrors([
            'identifier' => 'Invalid login credentials. Please verify your details or use OTP sign-in.',
        ]);
    }

    public function sendOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'identifier' => ['required', 'string'],
            'purpose' => ['required', 'string', 'in:login,register,forgot_password'],
        ]);

        $identifier = trim($validated['identifier']);
        $purpose = OtpPurpose::from($validated['purpose']);

        // Format phone if applicable
        if (! filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $cleanPhone = preg_replace('/[^0-9]/', '', $identifier);
            if (strlen($cleanPhone) === 10) {
                $identifier = '+91'.$cleanPhone;
            }
        }

        $otp = $this->otpService->generate($identifier, $purpose);

        if ($otp === null) {
            return back()->withInput()->withErrors([
                'identifier' => 'Too many OTP requests. Please wait a few minutes before trying again.',
            ]);
        }

        return redirect()->route('auth.otp.verify', [
            'identifier' => $identifier,
            'purpose' => $purpose->value,
        ])->with('success', "A 6-digit verification code has been sent to {$identifier}.");
    }

    public function showVerifyOtp(Request $request): View
    {
        $identifier = $request->query('identifier');
        $purpose = $request->query('purpose', 'login');

        return view('auth.verify-otp', compact('identifier', 'purpose'));
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'identifier' => ['required', 'string'],
            'purpose' => ['required', 'string', 'in:login,register,forgot_password'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $identifier = trim($validated['identifier']);
        $purpose = OtpPurpose::from($validated['purpose']);
        $plainOtp = trim($validated['otp']);

        $valid = $this->otpService->verify($identifier, $purpose, $plainOtp);

        if (! $valid) {
            return back()->withInput()->withErrors([
                'otp' => 'Invalid or expired OTP code. Please try again.',
            ]);
        }

        if ($purpose === OtpPurpose::ForgotPassword) {
            $request->session()->put('password_reset_identifier', $identifier);

            return redirect()->route('auth.password.reset')->with('success', 'OTP verified. Please choose a new password.');
        }

        // For login or register: find or create customer
        $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL);
        $user = $isEmail
            ? User::where('email', $identifier)->first()
            : User::where('phone', $identifier)->first();

        if (! $user) {
            // Auto-provision customer account on first OTP login
            $user = User::create([
                'name' => $isEmail ? explode('@', $identifier)[0] : 'Silk Patron',
                'email' => $isEmail ? $identifier : null,
                'phone' => ! $isEmail ? $identifier : null,
                'role' => UserRole::Customer,
                'email_verified_at' => $isEmail ? now() : null,
                'phone_verified_at' => ! $isEmail ? now() : null,
            ]);
        } else {
            if ($isEmail && ! $user->email_verified_at) {
                $user->update(['email_verified_at' => now()]);
            }
            if (! $isEmail && ! $user->phone_verified_at) {
                $user->update(['phone_verified_at' => now()]);
            }
        }

        Auth::guard('web')->login($user, true);
        $request->session()->regenerate();

        return redirect()->intended(route('account.dashboard'))->with('success', "Signed in successfully. Welcome, {$user->name}!");
    }

    public function showRegister(): View|RedirectResponse
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('account.dashboard');
        }

        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'required_without:phone', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'required_without:email', 'string', 'regex:/^(\+91)?[6-9]\d{9}$/', 'unique:users,phone'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'email.required_without' => 'Please provide either an email or mobile number.',
            'phone.required_without' => 'Please provide either an email or mobile number.',
        ]);

        $phone = $validated['phone'] ?? null;
        if ($phone) {
            $digits = preg_replace('/[^0-9]/', '', $phone);
            if (strlen($digits) === 10) {
                $phone = '+91'.$digits;
            }
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $phone,
            'password' => Hash::make($validated['password']),
            'role' => UserRole::Customer,
        ]);

        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        return redirect()->route('account.dashboard')->with('success', 'Your Iswarya Silks account has been created successfully!');
    }

    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    public function showResetPassword(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('password_reset_identifier')) {
            return redirect()->route('auth.forgot-password')->withErrors(['identifier' => 'Session expired. Please request a new OTP.']);
        }

        $identifier = $request->session()->get('password_reset_identifier');

        return view('auth.reset-password', compact('identifier'));
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $identifier = $request->session()->get('password_reset_identifier');
        if (! $identifier) {
            return redirect()->route('auth.forgot-password')->withErrors(['identifier' => 'Session expired. Please restart password reset.']);
        }

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL);
        $user = $isEmail
            ? User::where('email', $identifier)->first()
            : User::where('phone', $identifier)->first();

        if (! $user) {
            return redirect()->route('login')->withErrors(['identifier' => 'User not found.']);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        $request->session()->forget('password_reset_identifier');

        Auth::guard('web')->login($user);

        return redirect()->route('account.dashboard')->with('success', 'Password updated successfully. You are now logged in.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been signed out.');
    }
}
