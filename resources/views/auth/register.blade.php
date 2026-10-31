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
                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Password <span class="text-rose-600">*</span></label>
                    <input type="password" name="password" required class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
                    @error('password') <span class="text-rose-600 text-[11px] block mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Confirm Password <span class="text-rose-600">*</span></label>
                    <input type="password" name="password_confirmation" required class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
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
