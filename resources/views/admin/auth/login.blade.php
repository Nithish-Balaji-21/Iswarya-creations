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

            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1">Password</label>
                <input type="password" name="password" required class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
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
</body>
</html>
