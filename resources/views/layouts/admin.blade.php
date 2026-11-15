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
<body class="h-full bg-brand-warm font-sans text-stone-900 antialiased" x-data="{ mobileSidebarOpen: false }">
    <div class="min-h-full flex flex-col lg:flex-row">
        <!-- Mobile Sidebar Backdrop -->
        <div x-show="mobileSidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="mobileSidebarOpen = false" 
             class="fixed inset-0 z-40 bg-stone-900/50 backdrop-blur-xs lg:hidden"
             style="display: none;"></div>

        <!-- Sidebar (Desktop Fixed / Mobile Drawer) - On-Brand Luxury Ivory & Maroon Theme -->
        <aside :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed inset-y-0 left-0 z-50 w-72 bg-white text-stone-800 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 flex flex-col justify-between border-r border-rose-100/80 shadow-md lg:shadow-none">
            
            <!-- Sidebar Top: Brand Logo & Navigation -->
            <div class="flex-1 overflow-y-auto py-6 px-4 space-y-6">
                <!-- Brand Header Matching Public Website -->
                <div class="flex items-center justify-between px-2">
                    <a href="{{ route('admin.dashboard') }}" class="group block">
                        <div class="flex items-center gap-2">
                            <span class="text-2xl font-bold tracking-wider text-maroon font-jost uppercase group-hover:text-maroon-hover transition-colors">
                                ISWARYA SILKS
                            </span>
                            <span class="text-[9px] font-bold uppercase tracking-widest bg-rose-100 text-maroon px-2 py-0.5 rounded-full border border-rose-200">
                                Admin
                            </span>
                        </div>
                        <p class="text-[10px] tracking-widest text-gold uppercase mt-0.5 font-medium">
                            Peelamedu • ஐஸ்வர்யா சில்க்ஸ்
                        </p>
                    </a>
                    <button type="button" 
                            @click="mobileSidebarOpen = false" 
                            class="lg:hidden text-stone-400 hover:text-maroon p-1 rounded-lg focus:outline-hidden"
                            aria-label="Close sidebar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="border-t border-rose-100 my-2"></div>

                <!-- Primary Navigation -->
                <div class="space-y-1">
                    <p class="px-3 text-[10px] font-bold uppercase tracking-widest text-maroon/70 mb-2">Storefront Management</p>
                    
                    <!-- 1. Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold tracking-wide transition-all duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-maroon text-white shadow-xs' : 'text-stone-700 hover:bg-rose-50/70 hover:text-maroon' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-gold-light' : 'text-stone-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- 2. Saree Catalog (Products) -->
                    <a href="{{ route('admin.products.index') }}" 
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold tracking-wide transition-all duration-150 {{ request()->routeIs('admin.products.*') ? 'bg-maroon text-white shadow-xs' : 'text-stone-700 hover:bg-rose-50/70 hover:text-maroon' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.products.*') ? 'text-gold-light' : 'text-stone-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span>Saree Catalog</span>
                        </div>
                        <span class="text-[10px] {{ request()->routeIs('admin.products.*') ? 'bg-white/20 text-white' : 'bg-rose-100 text-maroon' }} px-2 py-0.5 rounded-full font-mono font-bold">
                            {{ \App\Models\Product::count() }}
                        </span>
                    </a>

                    <!-- 3. Categories -->
                    <a href="{{ route('admin.categories.index') }}" 
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold tracking-wide transition-all duration-150 {{ request()->routeIs('admin.categories.*') ? 'bg-maroon text-white shadow-xs' : 'text-stone-700 hover:bg-rose-50/70 hover:text-maroon' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.categories.*') ? 'text-gold-light' : 'text-stone-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                            <span>Categories</span>
                        </div>
                        <span class="text-[10px] {{ request()->routeIs('admin.categories.*') ? 'bg-white/20 text-white' : 'bg-rose-100 text-maroon' }} px-2 py-0.5 rounded-full font-mono font-bold">
                            {{ \App\Models\Category::count() }}
                        </span>
                    </a>

                    <!-- 4. Hero Banners -->
                    <a href="{{ route('admin.banners.index') }}" 
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold tracking-wide transition-all duration-150 {{ request()->routeIs('admin.banners.*') ? 'bg-maroon text-white shadow-xs' : 'text-stone-700 hover:bg-rose-50/70 hover:text-maroon' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.banners.*') ? 'text-gold-light' : 'text-stone-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Hero Banners</span>
                        </div>
                        <span class="text-[10px] {{ request()->routeIs('admin.banners.*') ? 'bg-white/20 text-white' : 'bg-rose-100 text-maroon' }} px-2 py-0.5 rounded-full font-mono font-bold">
                            {{ \App\Models\Banner::count() }}
                        </span>
                    </a>
                </div>

                <div class="border-t border-rose-100 my-3"></div>

                <!-- Website Management -->
                <div class="space-y-1">
                    <p class="px-3 text-[10px] font-bold uppercase tracking-widest text-maroon/70 mb-2">Website Settings</p>
                    
                    <!-- 5. Website Settings Hub -->
                    <a href="{{ route('admin.settings.edit') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold tracking-wide transition-all duration-150 {{ request()->routeIs('admin.settings.*') ? 'bg-maroon text-white shadow-xs' : 'text-stone-700 hover:bg-rose-50/70 hover:text-maroon' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('admin.settings.*') ? 'text-gold-light' : 'text-stone-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Storefront Settings</span>
                    </a>
                </div>
            </div>

            <!-- Sidebar Bottom: Live Website & Profile -->
            <div class="p-4 border-t border-rose-100 bg-brand-pearl/60 space-y-3">
                <a href="{{ route('home') }}" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   class="flex items-center justify-between w-full px-3 py-2 bg-white hover:bg-rose-50/70 border border-rose-100 text-stone-800 text-xs font-semibold rounded-xl transition-colors shadow-2xs">
                    <span class="flex items-center gap-2">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span>View Live Storefront</span>
                    </span>
                    <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>

                <div class="flex items-center justify-between pt-1">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-full bg-maroon text-gold-light font-bold text-xs flex items-center justify-center shrink-0 shadow-2xs">
                            IS
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-stone-800 truncate">{{ auth('admin')->user()->name ?? 'Administrator' }}</p>
                            <p class="text-[10px] text-stone-500 truncate">Store Manager</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" 
                                class="text-stone-400 hover:text-rose-600 p-1.5 rounded-lg hover:bg-rose-50 transition-colors" 
                                title="Sign Out">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Workspace Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Top App Bar - Clean Luxury White & Rose -->
            <header class="bg-white border-b border-rose-100/80 sticky top-0 z-30 shadow-2xs">
                <div class="px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
                    <div class="flex items-center gap-3">
                        <button type="button" 
                                @click="mobileSidebarOpen = true" 
                                class="lg:hidden p-2 text-stone-600 hover:text-maroon focus:outline-hidden"
                                aria-label="Open sidebar">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-gold block -mb-0.5">Iswarya Silks Flagship Admin</span>
                            <h2 class="text-base font-bold text-stone-900 font-jost">@yield('page_heading', 'Showroom Control Center')</h2>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 sm:gap-3">
                        <a href="{{ route('admin.products.create') }}" 
                           class="btn-pill btn-maroon px-3.5 sm:px-4 py-2 text-xs font-semibold tracking-wider uppercase flex items-center gap-1.5 shadow-xs hover:shadow-md transition-transform active:scale-95">
                            <svg class="w-4 h-4 text-gold-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span class="hidden sm:inline">Add Saree</span>
                            <span class="sm:hidden">Add</span>
                        </a>

                        <a href="{{ route('home') }}" 
                           target="_blank" 
                           class="inline-flex items-center gap-1.5 px-3 py-2 border border-rose-200 bg-brand-pearl hover:bg-rose-50 text-stone-700 hover:text-maroon text-xs font-semibold rounded-full transition-colors"
                           title="Preview live customer site">
                            <svg class="w-3.5 h-3.5 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            <span class="hidden sm:inline">Live Store</span>
                        </a>
                    </div>
                </div>
            </header>

            <!-- Alerts / Flash Messages -->
            @if(session('success'))
                <div class="bg-emerald-600 text-white text-xs py-2.5 px-4 sm:px-6 font-semibold flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" @click="$el.parentElement.remove()" class="text-white/80 hover:text-white ml-2">&times;</button>
                </div>
            @endif
            @if(session('info'))
                <div class="bg-indigo-600 text-white text-xs py-2.5 px-4 sm:px-6 font-semibold flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('info') }}</span>
                    </div>
                    <button type="button" @click="$el.parentElement.remove()" class="text-white/80 hover:text-white ml-2">&times;</button>
                </div>
            @endif
            @if(session('warning') || session('errors'))
                <div class="bg-rose-600 text-white text-xs py-2.5 px-4 sm:px-6 font-semibold flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>{{ session('warning') ?? session('errors')->first() }}</span>
                    </div>
                    <button type="button" @click="$el.parentElement.remove()" class="text-white/80 hover:text-white ml-2">&times;</button>
                </div>
            @endif

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto">
                @yield('content')
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
