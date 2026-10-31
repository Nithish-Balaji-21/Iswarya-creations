<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Portal – Iswarya Silks | Peelamedu, Coimbatore')</title>
    
    <!-- Google Fonts Jost -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <!-- CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-full flex flex-col font-sans bg-brand-warm text-brand-text antialiased">
    <!-- Top Admin Header -->
    <header class="bg-white border-b border-rose-100 shadow-2xs sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
            <div class="flex items-center gap-6">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    <span class="text-xl font-bold tracking-wider text-maroon font-jost uppercase">ISWARYA SILKS</span>
                    <span class="text-[10px] font-bold uppercase tracking-widest bg-rose-100 text-maroon px-2 py-0.5 rounded-full">Admin</span>
                </a>

                <!-- Desktop Admin Nav Links -->
                <nav class="hidden md:flex items-center space-x-5 text-xs font-semibold tracking-wide text-stone-600">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-maroon py-1 transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-maroon font-bold border-b-2 border-maroon' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="hover:text-maroon py-1 transition-colors {{ request()->routeIs('admin.products.*') ? 'text-maroon font-bold border-b-2 border-maroon' : '' }}">
                        Products (Sarees)
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="hover:text-maroon py-1 transition-colors {{ request()->routeIs('admin.categories.*') ? 'text-maroon font-bold border-b-2 border-maroon' : '' }}">
                        Categories
                    </a>
                    <a href="{{ route('admin.enquiries.index') }}" class="hover:text-maroon py-1 transition-colors {{ request()->routeIs('admin.enquiries.*') ? 'text-maroon font-bold border-b-2 border-maroon' : '' }}">
                        Enquiries
                    </a>
                    <a href="{{ route('admin.banners.index') }}" class="hover:text-maroon py-1 transition-colors {{ request()->routeIs('admin.banners.*') ? 'text-maroon font-bold border-b-2 border-maroon' : '' }}">
                        Banners
                    </a>
                    <a href="{{ route('admin.appointments.index') }}" class="hover:text-maroon py-1 transition-colors {{ request()->routeIs('admin.appointments.*') ? 'text-maroon font-bold border-b-2 border-maroon' : '' }}">
                        Video Calls
                    </a>
                    <a href="{{ route('admin.settings.edit') }}" class="hover:text-maroon py-1 transition-colors {{ request()->routeIs('admin.settings.*') ? 'text-maroon font-bold border-b-2 border-maroon' : '' }}">
                        Settings
                    </a>
                </nav>
            </div>

            <!-- Header Right: Quick Add + Storefront Link + Logout -->
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.products.create') }}" class="btn-pill btn-maroon px-4 py-1.5 text-xs font-semibold tracking-wider uppercase flex items-center gap-1.5 shadow-2xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Quick Add</span>
                </a>

                <a href="{{ route('home') }}" target="_blank" class="text-stone-500 hover:text-stone-800 p-2" title="View Public Website">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>

                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="text-xs font-semibold text-rose-700 hover:text-rose-900 px-3 py-1.5 rounded-lg hover:bg-rose-50 transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-emerald-50 border-b border-emerald-200 text-emerald-800 text-xs py-2 px-4 text-center font-semibold">
            {{ session('success') }}
        </div>
    @endif
    @if(session('info'))
        <div class="bg-indigo-50 border-b border-indigo-200 text-indigo-800 text-xs py-2 px-4 text-center font-semibold">
            {{ session('info') }}
        </div>
    @endif
    @if(session('warning') || session('errors'))
        <div class="bg-rose-50 border-b border-rose-200 text-rose-800 text-xs py-2 px-4 text-center font-semibold">
            {{ session('warning') ?? session('errors')->first() }}
        </div>
    @endif

    <!-- Content Area -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        @yield('content')
    </main>

    @livewireScripts
</body>
</html>
