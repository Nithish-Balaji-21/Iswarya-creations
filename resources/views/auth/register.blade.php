@extends('layouts.app')

@section('title', 'Create Account – Iswarya Silks | Customer Portal')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-rose-100 p-8">
        <div class="text-center mb-8">
            <span class="text-xs uppercase tracking-widest text-gold font-semibold">Join The Patron Circle</span>
            <h1 class="text-2xl sm:text-3xl font-bold text-stone-900 font-jost mt-1">Create Your Account</h1>
            <p class="text-xs text-stone-500 mt-1">Save curated sarees to your wishlist and enjoy express WhatsApp enquiry snapshots.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">Full Name <span class="text-rose-600">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Ananya Meenakshi" class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
                @error('name') <span class="text-rose-600 text-[11px] block mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">Email Address (Optional if phone provided)</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="ananya@example.com" class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
                @error('email') <span class="text-rose-600 text-[11px] block mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">Mobile / WhatsApp Number (Optional if email provided)</label>
                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+91 98765 43210" class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
                @error('phone') <span class="text-rose-600 text-[11px] block mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div x-data="{ showPass: false }">
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Password <span class="text-rose-600">*</span></label>
                    <div class="relative">
                        <input :type="showPass ? 'text' : 'password'" 
                               name="password" 
                               required 
                               placeholder="Minimum 8 characters"
                               class="w-full text-xs px-3.5 py-2.5 pr-10 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
                        <button type="button" 
                                @click="showPass = !showPass" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-stone-400 hover:text-maroon transition-colors focus:outline-hidden"
                                :title="showPass ? 'Hide password' : 'Show password'"
                                tabindex="-1">
                            <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                    @error('password') <span class="text-rose-600 text-[11px] block mt-1">{{ $message }}</span> @enderror
                </div>

                <div x-data="{ showConfirm: false }">
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Confirm Password <span class="text-rose-600">*</span></label>
                    <div class="relative">
                        <input :type="showConfirm ? 'text' : 'password'" 
                               name="password_confirmation" 
                               required 
                               placeholder="Re-enter password"
                               class="w-full text-xs px-3.5 py-2.5 pr-10 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
                        <button type="button" 
                                @click="showConfirm = !showConfirm" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-stone-400 hover:text-maroon transition-colors focus:outline-hidden"
                                :title="showConfirm ? 'Hide password' : 'Show password'"
                                tabindex="-1">
                            <svg x-show="!showConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-pill btn-maroon w-full py-3 text-xs tracking-wider uppercase font-semibold shadow-md mt-2">
                Register Account
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-rose-50 text-center text-xs text-stone-500">
            Already registered? 
            <a href="{{ route('login') }}" class="text-maroon font-semibold hover:underline">Sign In Here</a>
        </div>
    </div>
</div>
@endsection
