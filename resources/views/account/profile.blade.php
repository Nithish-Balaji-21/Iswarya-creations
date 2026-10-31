@extends('layouts.app')

@section('title', 'My Profile – Iswarya Silks')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between pb-6 mb-8 border-b border-rose-100">
        <div>
            <span class="text-xs uppercase tracking-widest text-gold font-semibold">Account Settings</span>
            <h1 class="text-3xl font-bold text-stone-900 font-jost mt-1">Profile Details</h1>
        </div>

        <a href="{{ route('account.dashboard') }}" class="btn-pill border border-rose-200 bg-white px-4 py-2 text-xs font-semibold text-stone-700 hover:bg-rose-50">
            Dashboard
        </a>
    </div>

    <div class="bg-white rounded-2xl p-8 border border-rose-100 shadow-2xs">
        <form method="POST" action="{{ route('account.profile.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">Your Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
                @error('name') <span class="text-rose-600 text-xs block mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
                @error('email') <span class="text-rose-600 text-xs block mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">Mobile / WhatsApp Number</label>
                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+91 98765 43210" class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
                @error('phone') <span class="text-rose-600 text-xs block mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="pt-4 border-t border-rose-100">
                <h4 class="text-xs font-bold uppercase tracking-wider text-stone-800 mb-3">Change Password (Optional)</h4>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-stone-700 mb-1">Current Password</label>
                        <input type="password" name="current_password" class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
                        @error('current_password') <span class="text-rose-600 text-xs block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-stone-700 mb-1">New Password</label>
                            <input type="password" name="new_password" class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
                            @error('new_password') <span class="text-rose-600 text-xs block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-stone-700 mb-1">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-pill btn-maroon px-8 py-3 text-xs tracking-wider uppercase font-semibold shadow-md mt-4">
                Save Changes
            </button>
        </form>
    </div>
</div>
@endsection
