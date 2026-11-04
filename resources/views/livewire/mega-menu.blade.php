<header class="w-full bg-white border-b border-rose-100/60 sticky top-0 z-40 shadow-xs" x-data="{ mobileNav: false, categoryDropdown: false, searchOpen: false }">
    <!-- Announcement Bar -->
    <div class="bg-maroon text-white text-xs py-2 px-4 text-center font-medium tracking-wide">
        <div class="max-w-7xl mx-auto flex items-center justify-center gap-2">
            <svg class="w-3.5 h-3.5 text-gold-light" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            <span>{{ \App\Models\Setting::announcementText() }}</span>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Mobile Menu Trigger -->
            <button type="button" @click="mobileNav = !mobileNav" class="lg:hidden p-2 text-stone-700 hover:text-maroon focus:outline-hidden" aria-label="Toggle navigation">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Brand Logo -->
            <div class="flex-shrink-0 text-center lg:text-left">
                <a href="{{ route('home') }}" class="group flex flex-col items-center lg:items-start">
                    <span class="text-2xl sm:text-3xl font-bold tracking-wider text-maroon font-jost uppercase group-hover:text-maroon-hover transition-colors">
                        ISWARYA SILKS
                    </span>
                    <span class="text-[10px] tracking-widest text-gold uppercase font-medium -mt-1">
                        Peelamedu, Coimbatore • ஐஸ்வர்யா சில்க்ஸ்
                    </span>
                </a>
            </div>

            <!-- Desktop Navigation Links -->
            <nav class="hidden lg:flex items-center space-x-7 text-sm font-medium tracking-wide text-brand-text">
                <a href="{{ route('home') }}" class="hover:text-maroon transition-colors {{ request()->routeIs('home') ? 'text-maroon font-semibold border-b-2 border-maroon pb-1' : '' }}">
                    Home
                </a>

                <a href="{{ route('products.new-arrivals') }}" class="hover:text-maroon transition-colors {{ request()->routeIs('products.new-arrivals') ? 'text-maroon font-semibold border-b-2 border-maroon pb-1' : '' }}">
                    New Arrivals
                </a>

                <!-- Shop By Category (Mega Menu Dropdown) -->
                <div class="relative" @mouseenter="categoryDropdown = true" @mouseleave="categoryDropdown = false">
                    <button type="button" class="flex items-center gap-1 hover:text-maroon transition-colors py-4">
                        <span>Shop By Category</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="categoryDropdown ? 'rotate-180 text-maroon' : 'text-stone-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Mega Dropdown Panel -->
                    <div x-show="categoryDropdown"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2"
                         class="absolute left-1/2 -translate-x-1/2 w-[760px] bg-white rounded-xl shadow-xl border border-rose-100 p-8 grid grid-cols-3 gap-8 z-50"
                         style="display: none;">
                        
                        <!-- Column 1: Weave -->
                        <div>
                            <h4 class="text-xs font-semibold text-maroon uppercase tracking-widest border-b border-rose-100 pb-2 mb-3">
                                By Weave
                            </h4>
                            <ul class="space-y-2 text-xs">
                                @forelse($categoriesByGroup['weave'] as $cat)
                                    @php
                                        $catName = is_array($cat) ? ($cat['name'] ?? null) : ($cat->name ?? null);
                                        $catSlug = is_array($cat) ? ($cat['slug'] ?? null) : ($cat->slug ?? null);
                                        $rawGroup = is_array($cat) ? ($cat['group'] ?? null) : ($cat->group ?? null);
                                        $catGroup = is_object($rawGroup) ? $rawGroup->value : (string) $rawGroup;
                                    @endphp
                                    @if($catName && $catSlug)
                                        <li>
                                            <a href="{{ route('categories.show', ['group' => $catGroup, 'slug' => $catSlug]) }}" class="text-stone-600 hover:text-maroon hover:translate-x-1 transition-all inline-block">
                                                {{ $catName }}
                                            </a>
                                        </li>
                                    @endif
                                @empty
                                    <li class="text-stone-400">Pure Handloom Weaves</li>
                                @endforelse
                            </ul>
                        </div>

                        <!-- Column 2: Fabric -->
                        <div>
                            <h4 class="text-xs font-semibold text-maroon uppercase tracking-widest border-b border-rose-100 pb-2 mb-3">
                                By Fabric
                            </h4>
                            <ul class="space-y-2 text-xs">
                                @forelse($categoriesByGroup['fabric'] as $cat)
                                    @php
                                        $catName = is_array($cat) ? ($cat['name'] ?? null) : ($cat->name ?? null);
                                        $catSlug = is_array($cat) ? ($cat['slug'] ?? null) : ($cat->slug ?? null);
                                        $rawGroup = is_array($cat) ? ($cat['group'] ?? null) : ($cat->group ?? null);
                                        $catGroup = is_object($rawGroup) ? $rawGroup->value : (string) $rawGroup;
                                    @endphp
                                    @if($catName && $catSlug)
                                        <li>
                                            <a href="{{ route('categories.show', ['group' => $catGroup, 'slug' => $catSlug]) }}" class="text-stone-600 hover:text-maroon hover:translate-x-1 transition-all inline-block">
                                                {{ $catName }}
                                            </a>
                                        </li>
                                    @endif
                                @empty
                                    <li class="text-stone-400">Mulberry Silk & Zari</li>
                                @endforelse
                            </ul>
                        </div>

                        <!-- Column 3: Occasion -->
                        <div>
                            <h4 class="text-xs font-semibold text-maroon uppercase tracking-widest border-b border-rose-100 pb-2 mb-3">
                                By Occasion
                            </h4>
                            <ul class="space-y-2 text-xs">
                                @forelse($categoriesByGroup['occasion'] as $cat)
                                    @php
                                        $catName = is_array($cat) ? ($cat['name'] ?? null) : ($cat->name ?? null);
                                        $catSlug = is_array($cat) ? ($cat['slug'] ?? null) : ($cat->slug ?? null);
                                        $rawGroup = is_array($cat) ? ($cat['group'] ?? null) : ($cat->group ?? null);
                                        $catGroup = is_object($rawGroup) ? $rawGroup->value : (string) $rawGroup;
                                    @endphp
                                    @if($catName && $catSlug)
                                        <li>
                                            <a href="{{ route('categories.show', ['group' => $catGroup, 'slug' => $catSlug]) }}" class="text-stone-600 hover:text-maroon hover:translate-x-1 transition-all inline-block">
                                                {{ $catName }}
                                            </a>
                                        </li>
                                    @endif
                                @empty
                                    <li class="text-stone-400">Bridal & Festive</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Kanjeevaram Sarees Direct Link -->
                @php
                    $kanjeevaramFirst = is_iterable($categoriesByGroup['kanjeevaram']) 
                        ? (is_object($categoriesByGroup['kanjeevaram']) && method_exists($categoriesByGroup['kanjeevaram'], 'first') 
                            ? $categoriesByGroup['kanjeevaram']->first() 
                            : ($categoriesByGroup['kanjeevaram'][0] ?? null))
                        : null;
                    $kanjeevaramSlug = is_array($kanjeevaramFirst) ? ($kanjeevaramFirst['slug'] ?? null) : ($kanjeevaramFirst?->slug ?? null);
                    $kanjeevaramUrl = $kanjeevaramSlug 
                        ? route('categories.show', ['group' => 'kanjeevaram', 'slug' => $kanjeevaramSlug]) 
                        : route('products.new-arrivals');
                @endphp
                <a href="{{ $kanjeevaramUrl }}" class="hover:text-maroon transition-colors text-maroon font-medium">
                    Kanjeevaram Sarees
                </a>

                <a href="{{ route('video-shopping') }}" class="hover:text-maroon transition-colors flex items-center gap-1.5 text-stone-700">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span>Video Shopping</span>
                </a>

                <a href="{{ route('pages.about') }}" class="hover:text-maroon transition-colors {{ request()->routeIs('pages.about') ? 'text-maroon font-semibold' : '' }}">
                    About Us
                </a>

                <a href="{{ route('pages.contact') }}" class="hover:text-maroon transition-colors {{ request()->routeIs('pages.contact') ? 'text-maroon font-semibold' : '' }}">
                    Contact
                </a>
            </nav>

            <!-- Header Right Actions: Live Search, Wishlist, Account -->
            <div class="flex items-center space-x-4">
                <!-- Search Trigger / Input -->
                <div class="relative" x-data="{ expanded: false }">
                    <button type="button" @click="expanded = !expanded; if(expanded) $nextTick(() => $refs.searchInput.focus())" class="p-2 text-stone-600 hover:text-maroon transition-colors" title="Search catalog">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>

                    <!-- Expandable Search Popover -->
                    <div x-show="expanded"
                         @click.outside="expanded = false; $wire.clearSearch()"
                         x-transition
                         class="absolute right-0 top-12 w-80 sm:w-96 bg-white rounded-xl shadow-2xl border border-rose-100 p-3 z-50"
                         style="display: none;">
                        <div class="relative">
                            <input type="text"
                                   x-ref="searchInput"
                                   wire:model.live.debounce.300ms="searchQuery"
                                   placeholder="Search pure silk sarees, SKU, colour..."
                                   class="w-full pl-9 pr-8 py-2 text-xs border border-rose-200 rounded-full focus:outline-hidden focus:border-maroon focus:ring-1 focus:ring-maroon">
                            <svg class="w-4 h-4 text-stone-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <button type="button" x-show="$wire.searchQuery.length > 0" @click="$wire.clearSearch()" class="absolute right-2.5 top-2.5 text-stone-400 hover:text-stone-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <!-- Live Autocomplete Results (Top 6) -->
                        @if(!empty($searchResults))
                            <div class="mt-2 divide-y divide-rose-50 max-h-80 overflow-y-auto">
                                @foreach($searchResults as $result)
                                    <a href="{{ $result['url'] }}" class="flex items-center gap-3 p-2 hover:bg-rose-50/50 rounded-lg transition-colors">
                                        <img src="{{ $result['thumbnail'] }}" alt="{{ $result['name'] }}" class="w-12 h-15 object-cover rounded border border-rose-100">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-semibold text-stone-800 truncate">{{ $result['name'] }}</p>
                                            <p class="text-[10px] text-stone-500">SKU: {{ $result['sku'] }}</p>
                                            <p class="text-xs font-semibold text-maroon">{{ $result['price'] }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @elseif(strlen($searchQuery) >= 2)
                            <div class="p-4 text-center text-xs text-stone-500">
                                No pure silk sarees found matching "{{ $searchQuery }}".
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Wishlist Counter -->
                <a href="{{ route('account.wishlist') }}" class="relative p-2 text-stone-600 hover:text-maroon transition-colors" title="My Wishlist">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    @if($wishlistCount > 0)
                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-maroon rounded-full">
                            {{ $wishlistCount }}
                        </span>
                    @endif
                </a>

                <!-- Account Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button type="button" @click="open = !open" class="p-2 text-stone-600 hover:text-maroon transition-colors flex items-center gap-1" title="Account">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </button>

                    <div x-show="open"
                         @click.outside="open = false"
                         x-transition
                         class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-xl border border-rose-100 py-2 z-50 text-xs"
                         style="display: none;">
                        @auth('web')
                            <div class="px-4 py-2 border-b border-rose-50">
                                <p class="font-semibold text-stone-800">{{ auth('web')->user()->name }}</p>
                                <p class="text-[10px] text-stone-500 truncate">{{ auth('web')->user()->email ?? auth('web')->user()->phone }}</p>
                            </div>
                            <a href="{{ route('account.dashboard') }}" class="block px-4 py-2 text-stone-700 hover:bg-rose-50 hover:text-maroon">Dashboard</a>
                            <a href="{{ route('account.wishlist') }}" class="block px-4 py-2 text-stone-700 hover:bg-rose-50 hover:text-maroon">My Wishlist</a>
                            <a href="{{ route('account.enquiries') }}" class="block px-4 py-2 text-stone-700 hover:bg-rose-50 hover:text-maroon">My Enquiries</a>
                            <a href="{{ route('account.profile') }}" class="block px-4 py-2 text-stone-700 hover:bg-rose-50 hover:text-maroon">Profile Settings</a>
                            <div class="border-t border-rose-50 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-rose-700 hover:bg-rose-50">Sign Out</button>
                            </form>
                        @else
                            <div class="px-4 py-2 border-b border-rose-50 text-stone-600">
                                Welcome to Iswarya Silks
                            </div>
                            <a href="{{ route('login') }}" class="block px-4 py-2 text-stone-700 hover:bg-rose-50 hover:text-maroon font-medium">Sign In / OTP</a>
                            <a href="{{ route('register') }}" class="block px-4 py-2 text-stone-700 hover:bg-rose-50 hover:text-maroon">Create Account</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Drawer Navigation -->
    <div x-show="mobileNav"
         x-transition
         class="lg:hidden border-t border-rose-100 bg-brand-warm px-4 py-5 space-y-4"
         style="display: none;">
        <nav class="space-y-2 text-sm font-medium text-stone-800">
            <a href="{{ route('home') }}" class="block py-1 hover:text-maroon">Home</a>
            <a href="{{ route('products.new-arrivals') }}" class="block py-1 hover:text-maroon">New Arrivals</a>
            <div class="py-2 border-y border-rose-200/50">
                <p class="text-xs uppercase font-semibold text-maroon mb-2">Collections</p>
                @foreach($categoriesByGroup['kanjeevaram'] as $cat)
                    @php
                        $catName = is_array($cat) ? ($cat['name'] ?? null) : ($cat->name ?? null);
                        $catSlug = is_array($cat) ? ($cat['slug'] ?? null) : ($cat->slug ?? null);
                        $rawGroup = is_array($cat) ? ($cat['group'] ?? null) : ($cat->group ?? null);
                        $catGroup = is_object($rawGroup) ? $rawGroup->value : (string) $rawGroup;
                    @endphp
                    @if($catName && $catSlug)
                        <a href="{{ route('categories.show', ['group' => $catGroup, 'slug' => $catSlug]) }}" class="block py-1 pl-2 text-xs text-stone-600 hover:text-maroon">{{ $catName }}</a>
                    @endif
                @endforeach
                @foreach($categoriesByGroup['weave']->take(4) as $cat)
                    @php
                        $catName = is_array($cat) ? ($cat['name'] ?? null) : ($cat->name ?? null);
                        $catSlug = is_array($cat) ? ($cat['slug'] ?? null) : ($cat->slug ?? null);
                        $rawGroup = is_array($cat) ? ($cat['group'] ?? null) : ($cat->group ?? null);
                        $catGroup = is_object($rawGroup) ? $rawGroup->value : (string) $rawGroup;
                    @endphp
                    @if($catName && $catSlug)
                        <a href="{{ route('categories.show', ['group' => $catGroup, 'slug' => $catSlug]) }}" class="block py-1 pl-2 text-xs text-stone-600 hover:text-maroon">{{ $catName }}</a>
                    @endif
                @endforeach
            </div>
            <a href="{{ route('video-shopping') }}" class="block py-1 text-emerald-700 font-semibold flex items-center gap-1.5">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Video Shopping</span>
            </a>
            <a href="{{ route('pages.about') }}" class="block py-1 hover:text-maroon">About Us</a>
            <a href="{{ route('pages.contact') }}" class="block py-1 hover:text-maroon">Contact</a>
        </nav>
    </div>
</header>
