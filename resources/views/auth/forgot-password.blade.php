@extends('layouts.app')

@section('title', 'Forgot Password – Iswarya Silks')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-rose-100 p-8">
        <div class="text-center mb-8">
            <span class="text-xs uppercase tracking-widest text-gold font-semibold">Account Recovery</span>
            <h1 class="text-2xl font-bold text-stone-900 font-jost mt-1">Reset Your Password</h1>
            <p class="text-xs text-stone-500 mt-1">Enter your registered email address or mobile number to receive a verification OTP.</p>
        </div>

        <form method="POST" action="{{ route('auth.otp.send') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="purpose" value="forgot_password">

            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">Email or Mobile Number</label>
                <input type="text" name="identifier" required placeholder="email@example.com or +91 mobile" class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
            </div>

            <button type="submit" class="btn-pill btn-maroon w-full py-3 text-xs tracking-wider uppercase font-semibold shadow-md">
                Send Reset Code
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-stone-500">
            Remembered your credentials? <a href="{{ route('login') }}" class="text-maroon font-semibold hover:underline">Back to Sign In</a>
        </div>
    </div>
</div>
@endsection
