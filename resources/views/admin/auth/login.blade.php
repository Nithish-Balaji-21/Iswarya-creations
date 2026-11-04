<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Sign In – Iswarya Silks</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-full flex items-center justify-center bg-brand-warm py-12 px-4 sm:px-6 lg:px-8 font-sans">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-rose-100 p-8 sm:p-10">
        <!-- Brand Header -->
        <div class="text-center mb-8">
            <span class="text-2xl font-bold tracking-wider text-maroon font-jost uppercase">ISWARYA SILKS</span>
            <p class="text-xs text-gold tracking-widest uppercase mt-0.5 font-medium">Administration Portal • Peelamedu</p>
            <h1 class="text-lg font-bold text-stone-900 mt-4">Authorized Personnel Access</h1>
            <p class="text-xs text-stone-500 mt-1">Sign in to manage catalog, daily stock, and customer enquiries.</p>
        </div>

        @if(session('warning'))
            <div class="mb-4 bg-amber-50 border border-amber-200 text-amber-900 text-xs p-3 rounded-lg">
                {{ session('warning') }}
            </div>
        @endif
        @if(session('success'))
            <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs p-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-900 text-xs p-3 rounded-lg">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1">Admin Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
            </div>

            <div x-data="{ showPassword: false }">
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1">Password</label>
                <div class="relative">
                    <input :type="showPassword ? 'text' : 'password'" 
                           name="password" 
                           required 
                           placeholder="Enter admin password"
                           class="w-full text-xs px-3.5 py-2.5 pr-10 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
                    <button type="button" 
                            @click="showPassword = !showPassword" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-stone-400 hover:text-maroon transition-colors focus:outline-hidden"
                            :title="showPassword ? 'Hide password' : 'Show password'"
                            :aria-label="showPassword ? 'Hide password' : 'Show password'"
                            tabindex="-1">
                        <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg x-show="showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center text-stone-600">
                    <input type="checkbox" name="remember" class="rounded text-maroon focus:ring-maroon">
                    <span class="ml-2">Keep me signed in</span>
                </label>
            </div>

            <button type="submit" class="btn-pill btn-maroon w-full py-3 text-xs tracking-wider uppercase font-semibold shadow-md mt-4">
                Verify & Enter Portal
            </button>
        </form>

        <div class="mt-8 pt-4 border-t border-rose-50 text-center text-[11px] text-stone-400">
            Protected by 5-attempt brute force security lockout & IP throttling.
        </div>
    </div>
    @livewireScripts
</body>
</html>
