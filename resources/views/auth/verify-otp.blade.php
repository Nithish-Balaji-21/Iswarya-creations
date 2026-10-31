@extends('layouts.app')

@section('title', 'Verify Passcode – Iswarya Silks')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-rose-100 p-8 text-center">
        <div class="w-12 h-12 bg-rose-100 text-maroon rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        </div>

        <h1 class="text-2xl font-bold text-stone-900 font-jost mb-2">Enter Verification Code</h1>
        <p class="text-xs text-stone-500 mb-6">
            We sent a 6-digit code to <strong class="text-stone-800">{{ $identifier }}</strong>. Valid for 10 minutes.
        </p>

        <form method="POST" action="{{ route('auth.otp.verify.submit') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="identifier" value="{{ $identifier }}">
            <input type="hidden" name="purpose" value="{{ $purpose }}">

            <div>
                <input type="text"
                       name="otp"
                       maxlength="6"
                       required
                       autofocus
                       placeholder="••••••"
                       class="w-48 text-center text-2xl tracking-[0.5em] font-mono py-3 border-2 border-rose-200 rounded-xl focus:outline-hidden focus:border-maroon focus:ring-2 focus:ring-rose-200 mx-auto block">
                @error('otp') <span class="text-rose-600 text-xs block mt-2">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn-pill btn-maroon w-full py-3 text-xs tracking-wider uppercase font-semibold shadow-md">
                Verify & Continue
            </button>
        </form>

        <div class="mt-6 text-xs text-stone-500">
            Didn't receive the passcode?
            <form method="POST" action="{{ route('auth.otp.send') }}" class="inline">
                @csrf
                <input type="hidden" name="identifier" value="{{ $identifier }}">
                <input type="hidden" name="purpose" value="{{ $purpose }}">
                <button type="submit" class="text-maroon font-semibold hover:underline">Resend Code</button>
            </form>
        </div>
    </div>
</div>
@endsection
