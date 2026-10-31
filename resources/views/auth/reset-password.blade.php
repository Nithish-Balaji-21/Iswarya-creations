@extends('layouts.app')

@section('title', 'Set New Password – Iswarya Silks')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-rose-100 p-8">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-stone-900 font-jost">Choose a New Password</h1>
            <p class="text-xs text-stone-500 mt-1">For account <strong class="text-stone-800">{{ $identifier }}</strong></p>
        </div>

        <form method="POST" action="{{ route('auth.password.reset.submit') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">New Password (Min 8 characters)</label>
                <input type="password" name="password" required class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
                @error('password') <span class="text-rose-600 text-xs block mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">Confirm New Password</label>
                <input type="password" name="password_confirmation" required class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
            </div>

            <button type="submit" class="btn-pill btn-maroon w-full py-3 text-xs tracking-wider uppercase font-semibold shadow-md">
                Update Password & Sign In
            </button>
        </form>
    </div>
</div>
@endsection
