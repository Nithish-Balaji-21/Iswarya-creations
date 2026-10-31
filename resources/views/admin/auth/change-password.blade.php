<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Permanent Password – Iswarya Silks Admin</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex items-center justify-center bg-brand-warm py-12 px-4 sm:px-6 lg:px-8 font-sans">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-rose-100 p-8">
        <div class="text-center mb-6">
            <span class="text-xs uppercase font-bold tracking-widest text-gold">First-Time Security Policy</span>
            <h1 class="text-2xl font-bold text-stone-900 font-jost mt-1">Set Your Permanent Password</h1>
            <p class="text-xs text-stone-500 mt-1">
                Welcome, <strong>{{ $user->name }}</strong>. Because this is your initial login, you must establish a secure permanent password before managing daily inventory.
            </p>
        </div>

        @if($errors->any())
            <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-900 text-xs p-3 rounded-lg">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.password.update') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1">
                    New Password <span class="text-[10px] text-stone-400 font-normal lowercase">(min 8 chars, uppercase, lowercase, number)</span>
                </label>
                <input type="password" name="password" required autofocus class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1">
                    Confirm New Password
                </label>
                <input type="password" name="password_confirmation" required class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
            </div>

            <button type="submit" class="btn-pill btn-maroon w-full py-3 text-xs tracking-wider uppercase font-semibold shadow-md mt-4">
                Update & Continue to Dashboard
            </button>
        </form>
    </div>
</body>
</html>
