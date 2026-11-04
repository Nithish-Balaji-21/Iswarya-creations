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

                <div x-data="{ showPassword: false }">
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-xs font-semibold text-stone-700">Password</label>
                        <a href="{{ route('auth.forgot-password') }}" class="text-[11px] text-maroon hover:underline">Forgot Password?</a>
                    </div>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" 
                               name="password" 
                               required 
                               placeholder="Enter your password"
                               class="w-full text-xs px-3.5 py-2.5 pr-10 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
                        <button type="button" 
                                @click="showPassword = !showPassword" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-stone-400 hover:text-maroon transition-colors focus:outline-hidden"
                                :title="showPassword ? 'Hide password' : 'Show password'"
                                :aria-label="showPassword ? 'Hide password' : 'Show password'"
                                tabindex="-1">
                            <!-- Eye icon (Show password) -->
                            <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <!-- Eye-off / Eye-slash icon (Hide password) -->
                            <svg x-show="showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                    @error('password') <span class="text-rose-600 text-[11px] block mt-1">{{ $message }}</span> @enderror
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
