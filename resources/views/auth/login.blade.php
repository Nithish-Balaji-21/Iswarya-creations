@extends('layouts.app')

@section('title', 'Sign In – Iswarya Silks | Customer Portal')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-rose-100 p-8" x-data="{ tab: 'password' }">
        <div class="text-center mb-8">
            <span class="text-xs uppercase tracking-widest text-gold font-semibold">Iswarya Silks Patron Portal</span>
            <h1 class="text-2xl sm:text-3xl font-bold text-stone-900 font-jost mt-1">Sign In to Your Account</h1>
            <p class="text-xs text-stone-500 mt-1">Access your saved sarees, custom enquiries, and bridal consultation history.</p>
        </div>

        <!-- Tab Toggle: Password vs OTP -->
        <div class="flex rounded-full bg-brand-pearl p-1 border border-rose-100 mb-6 text-xs">
            <button type="button" 
                    @click="tab = 'password'" 
                    class="flex-1 py-2 rounded-full font-semibold transition-all duration-200"
                    :class="tab === 'password' ? 'bg-white text-maroon shadow-xs' : 'text-stone-500 hover:text-stone-800'">
                Password Sign In
            </button>
            <button type="button" 
                    @click="tab = 'otp'" 
                    class="flex-1 py-2 rounded-full font-semibold transition-all duration-200"
                    :class="tab === 'otp' ? 'bg-white text-maroon shadow-xs' : 'text-stone-500 hover:text-stone-800'">
                One-Time OTP Code
            </button>
        </div>

        <!-- Tab 1: Password Login -->
        <div x-show="tab === 'password'" x-transition>
            <form method="POST" action="{{ route('auth.login.password') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Email or Mobile Number</label>
                    <input type="text" name="identifier" value="{{ old('identifier') }}" required placeholder="email@example.com or +919876543210" class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
                    @error('identifier') <span class="text-rose-600 text-[11px] block mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-xs font-semibold text-stone-700">Password</label>
                        <a href="{{ route('auth.forgot-password') }}" class="text-[11px] text-maroon hover:underline">Forgot Password?</a>
                    </div>
                    <input type="password" name="password" required class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember" class="rounded text-maroon focus:ring-maroon">
                    <label for="remember" class="ml-2 text-xs text-stone-600">Remember this device</label>
                </div>

                <button type="submit" class="btn-pill btn-maroon w-full py-3 text-xs tracking-wider uppercase font-semibold shadow-md">
                    Sign In
                </button>
            </form>
        </div>

        <!-- Tab 2: OTP Login -->
        <div x-show="tab === 'otp'" x-transition style="display: none;">
            <form method="POST" action="{{ route('auth.otp.send') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="purpose" value="login">

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Email or Mobile Number</label>
                    <input type="text" name="identifier" required placeholder="Enter email or +91 mobile" class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
                    <p class="text-[11px] text-stone-400 mt-1">We will send a 6-digit verification code. No password needed.</p>
                </div>

                <button type="submit" class="btn-pill btn-maroon w-full py-3 text-xs tracking-wider uppercase font-semibold shadow-md">
                    Send One-Time Passcode
                </button>
            </form>
        </div>

        <div class="mt-8 pt-6 border-t border-rose-50 text-center text-xs text-stone-500">
            Don't have an account yet? 
            <a href="{{ route('register') }}" class="text-maroon font-semibold hover:underline">Register Now</a>
        </div>
    </div>
</div>
@endsection
