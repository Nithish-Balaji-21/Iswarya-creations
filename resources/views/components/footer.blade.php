<footer class="bg-stone-950 text-white mt-20 border-t border-stone-800">
    <!-- Top Announcement Strip -->
    <div class="border-b border-stone-800/80 bg-stone-900/40 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6 text-center md:text-left">
            <div class="flex items-center justify-center md:justify-start gap-3">
                <div class="w-10 h-10 rounded-full bg-maroon/20 flex items-center justify-center text-gold border border-gold/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <h4 class="text-sm font-semibold tracking-wide text-white">Silk Mark Certified Pure Silk</h4>
                    <p class="text-xs text-stone-400">100% genuine tested zari and mulberry handlooms.</p>
                </div>
            </div>

            <div class="flex items-center justify-center md:justify-start gap-3">
                <div class="w-10 h-10 rounded-full bg-maroon/20 flex items-center justify-center text-gold border border-gold/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h4 class="text-sm font-semibold tracking-wide text-white">Personalized Video Shopping</h4>
                    <p class="text-xs text-stone-400">Private virtual showroom viewing from Peelamedu.</p>
                </div>
            </div>

            <div class="flex items-center justify-center md:justify-start gap-3">
                <div class="w-10 h-10 rounded-full bg-maroon/20 flex items-center justify-center text-gold border border-gold/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h4 class="text-sm font-semibold tracking-wide text-white">Fresh Daily Weaves</h4>
                    <p class="text-xs text-stone-400">Direct artisan clusters with daily catalog updates.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Footer Links Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
        <!-- Col 1: Brand & Peelamedu Heritage (Span 2) -->
        <div class="lg:col-span-2 space-y-4">
            <div>
                <span class="text-2xl font-bold tracking-wider text-white font-jost uppercase">
                    ISWARYA SILKS
                </span>
                <p class="text-xs text-gold tracking-widest uppercase mt-0.5 font-medium">
                    Peelamedu, Coimbatore • ஐஸ்வர்யா சில்க்ஸ்
                </p>
            </div>
            <p class="text-xs text-stone-400 leading-relaxed max-w-sm">
                Generations of exquisite silk mastery in Coimbatore. Dedicated to preserving authentic Tamil handloom weaving traditions, certified pure zari craftsmanship, and personalized bridal concierge consultations.
            </p>
            <div class="text-xs text-stone-400 space-y-1.5 pt-2">
                <p class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-gold shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>{{ \App\Models\Setting::storeAddress() }}</span>
                </p>
                <p class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gold shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    <span>{{ config('iswarya.contact_phone') }}</span>
                </p>
            </div>
        </div>

        <!-- Col 2: Pure Silk Categories -->
        <div>
            <h4 class="text-xs font-semibold uppercase tracking-widest text-gold mb-4">Pure Silks</h4>
            <ul class="space-y-2 text-xs text-stone-400">
                <li><a href="{{ route('products.new-arrivals') }}" class="hover:text-white transition-colors">New Arrivals</a></li>
                <li><a href="/kanjeevaram/bridal-collection" class="hover:text-white transition-colors">Kanjeevaram Bridals</a></li>
                <li><a href="/weave/traditional-korvai" class="hover:text-white transition-colors">Traditional Korvai</a></li>
                <li><a href="/fabric/soft-silk" class="hover:text-white transition-colors">Soft Silk Sarees</a></li>
                <li><a href="/fabric/organza-tussar" class="hover:text-white transition-colors">Organza & Tussar</a></li>
                <li><a href="/occasion/wedding-reception" class="hover:text-white transition-colors">Muhurtham & Reception</a></li>
            </ul>
        </div>

        <!-- Col 3: Customer Care -->
        <div>
            <h4 class="text-xs font-semibold uppercase tracking-widest text-gold mb-4">Concierge & Care</h4>
            <ul class="space-y-2 text-xs text-stone-400">
                <li><a href="{{ route('video-shopping') }}" class="hover:text-white transition-colors">Video Call Viewing</a></li>
                <li><a href="{{ route('pages.about') }}" class="hover:text-white transition-colors">Our Weaver Story</a></li>
                <li><a href="{{ route('pages.contact') }}" class="hover:text-white transition-colors">Peelamedu Showroom</a></li>
                <li><a href="{{ route('account.wishlist') }}" class="hover:text-white transition-colors">My Saree Wishlist</a></li>
                <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Customer Login</a></li>
                <li><a href="{{ route('admin.login') }}" class="hover:text-white transition-colors text-stone-600">Admin Portal</a></li>
            </ul>
        </div>

        <!-- Col 4: Newsletter Subscription -->
        <div>
            <h4 class="text-xs font-semibold uppercase tracking-widest text-gold mb-4">Exclusive Previews</h4>
            <p class="text-xs text-stone-400 mb-3 leading-relaxed">
                Be the first to view our daily handloom loom cuts and heritage bridal releases.
            </p>

            <form method="POST" action="{{ route('newsletter.subscribe') }}" class="space-y-2">
                @csrf
                <div class="relative">
                    <input type="email" name="email" required placeholder="Enter your email" class="w-full bg-stone-900 border border-stone-800 rounded-lg px-3 py-2 text-xs text-white placeholder-stone-500 focus:outline-hidden focus:border-gold">
                </div>
                <button type="submit" class="btn-pill btn-gold w-full py-2 text-xs font-semibold tracking-wider uppercase">
                    Subscribe
                </button>
            </form>
        </div>
    </div>

    <!-- Bottom Copyright & Trademark Strip -->
    <div class="border-t border-stone-900 py-6 text-center text-xs text-stone-500">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p>&copy; {{ date('Y') }} Iswarya Silks (ஐஸ்வர்யா சில்க்ஸ்). Handloom Silk Saree Manufacturer, Peelamedu, Coimbatore. All rights reserved.</p>
            <p class="text-[11px] text-stone-600">A luxury brand showcase. Direct artisan enquiries conducted via WhatsApp.</p>
        </div>
    </div>
</footer>
