@extends('layouts.app')

@section('title', 'Iswarya Silks – Peelamedu, Coimbatore | Authentic Handloom Silk Sarees')
@section('meta_description', 'Discover handcrafted pure silk sarees from Iswarya Silks, Peelamedu, Coimbatore (ஐஸ்வர்யா சில்க்ஸ்). Authentic Kanjeevaram, Banarasi, and bridal silk sarees. Direct WhatsApp enquiry.')

@section('content')
    <!-- 1. Hero Carousel (Desktop / Mobile responsive with Alpine.js) -->
    <section class="relative w-full overflow-hidden bg-stone-900" 
             x-data="{ 
                 activeSlide: 0, 
                 totalSlides: {{ $banners->count() > 0 ? $banners->count() : 1 }},
                 autoplay() {
                     setInterval(() => {
                         this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
                     }, 6000);
                 }
             }" 
             x-init="autoplay()">
        
        <div class="relative min-h-[500px] sm:min-h-[600px] lg:min-h-[700px] w-full flex items-center">
            @forelse($banners as $index => $banner)
                <div x-show="activeSlide === {{ $index }}"
                     x-transition:enter="transition ease-out duration-700"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-500"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute inset-0 w-full h-full"
                     style="{{ $index !== 0 ? 'display: none;' : '' }}">
                    
                    <!-- Background Images: Desktop vs Mobile -->
                    <picture>
                        @if($banner->mobile_image)
                            <source media="(max-width: 640px)" srcset="{{ $banner->mobile_image_url }}">
                        @endif
                        <img src="{{ $banner->desktop_image_url }}" alt="{{ $banner->title }}" class="w-full h-full object-cover object-center opacity-70">
                    </picture>

                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-r from-stone-950/85 via-stone-950/50 to-transparent"></div>

                    <!-- Slide Content -->
                    <div class="absolute inset-0 flex items-center">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                            <div class="max-w-xl text-left space-y-4">
                                @if($banner->eyebrow)
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold tracking-widest uppercase bg-gold/20 text-gold-light border border-gold/30 backdrop-blur-xs">
                                        {{ $banner->eyebrow }}
                                    </span>
                                @endif

                                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-bold text-white font-jost tracking-tight leading-tight">
                                    {{ $banner->title }}
                                </h1>

                                @if($banner->subtitle)
                                    <p class="text-sm sm:text-base text-stone-200 leading-relaxed font-light">
                                        {{ $banner->subtitle }}
                                    </p>
                                @endif

                                <div class="pt-2 flex flex-wrap gap-4">
                                    @if($banner->link_url)
                                        <a href="{{ $banner->link_url }}" class="btn-pill btn-maroon px-8 py-3.5 text-xs sm:text-sm tracking-wider uppercase font-semibold shadow-lg">
                                            {{ $banner->button_text ?? 'Explore Collection' }}
                                        </a>
                                    @endif

                                    <a href="{{ route('video-shopping') }}" class="btn-pill px-6 py-3.5 text-xs sm:text-sm tracking-wider uppercase font-semibold bg-white/20 hover:bg-white text-white hover:text-stone-900 backdrop-blur-xs border border-white/40 transition-colors">
                                        Book Video View
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Fallback Banner if no banners seeded yet -->
                <div class="relative w-full h-full py-28 flex items-center bg-gradient-to-r from-stone-950 via-stone-900 to-stone-950">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold tracking-widest uppercase bg-gold/20 text-gold-light border border-gold/30 mb-4">
                            Handloom Heritage • Peelamedu Flagship
                        </span>
                        <h1 class="text-4xl sm:text-6xl font-bold text-white font-jost tracking-tight mb-4">
                            Pure Kanjeevaram & Bridal Silk Sarees
                        </h1>
                        <p class="text-stone-300 max-w-lg mb-8 text-sm sm:text-base font-light">
                            Direct from the loom to your bridal trousseau. Certified mulberry silks hand-spun with pure gold zari.
                        </p>
                        <div class="flex flex-wrap gap-4 justify-center sm:justify-start">
                            <a href="{{ route('products.new-arrivals') }}" class="btn-pill btn-maroon px-8 py-3.5 text-xs uppercase font-semibold tracking-wider">
                                View New Arrivals
                            </a>
                            <a href="{{ route('video-shopping') }}" class="btn-pill btn-gold px-8 py-3.5 text-xs uppercase font-semibold tracking-wider">
                                Video Shopping
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Carousel Indicators -->
        @if($banners->count() > 1)
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-20">
                @foreach($banners as $index => $b)
                    <button type="button"
                            @click="activeSlide = {{ $index }}"
                            class="h-1.5 rounded-full transition-all duration-300"
                            :class="activeSlide === {{ $index }} ? 'w-8 bg-gold' : 'w-2 bg-white/40 hover:bg-white/70'">
                    </button>
                @endforeach
            </div>
        @endif
    </section>

    <!-- 2. Featured Collections Grid (from categories with show_on_homepage) -->
    @if($featuredCategories->count() > 0)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-xs uppercase tracking-widest text-gold font-semibold">Curated Treasures</span>
                <h2 class="text-3xl font-bold text-stone-900 font-jost tracking-tight mt-1">
                    Featured Handloom Collections
                </h2>
                <p class="text-xs sm:text-sm text-stone-500 mt-2 font-normal">
                    Explore master weaver creations classified by weave, certified silk weight, and bridal occasions.
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($featuredCategories as $category)
                    <a href="{{ route('categories.show', ['group' => $category->group->value, 'slug' => $category->slug]) }}" 
                       class="group relative rounded-xl overflow-hidden aspect-[4/5] bg-stone-100 shadow-sm hover:shadow-xl transition-all duration-500 flex flex-col justify-end p-6 border border-rose-100/60">
                        <!-- Cover Image -->
                        @if($category->cover_image)
                            <img src="{{ $category->cover_image_url }}" alt="{{ $category->name }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @else
                            <div class="absolute inset-0 bg-gradient-to-t from-stone-950 via-stone-800/80 to-stone-700 group-hover:scale-105 transition-transform duration-700"></div>
                        @endif

                        <!-- Gradient for Legibility -->
                        <div class="absolute inset-0 bg-gradient-to-t from-stone-950/90 via-stone-950/30 to-transparent"></div>

                        <!-- Collection Content -->
                        <div class="relative z-10 text-white space-y-1">
                            <span class="text-[10px] uppercase tracking-widest text-gold-light font-medium block">
                                {{ $category->group->label() }}
                            </span>
                            <h3 class="text-lg font-bold font-jost group-hover:text-gold-light transition-colors">
                                {{ $category->name }}
                            </h3>
                            <p class="text-[11px] text-stone-300 line-clamp-1 font-light">
                                {{ $category->description ?? 'Explore authentic handwoven creations.' }}
                            </p>
                            <span class="inline-flex items-center text-[11px] font-semibold text-gold pt-2 group-hover:translate-x-1 transition-transform">
                                <span>Explore Collection</span>
                                <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <!-- 3. New Arrivals Carousel with Quick WhatsApp Button -->
    <section class="bg-brand-pearl py-16 sm:py-24 border-y border-rose-100/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between mb-12 gap-4">
                <div>
                    <span class="text-xs uppercase tracking-widest text-gold font-semibold">Fresh Off The Loom</span>
                    <h2 class="text-3xl font-bold text-stone-900 font-jost tracking-tight mt-1">
                        New Arrivals (This Month)
                    </h2>
                    <p class="text-xs sm:text-sm text-stone-500 mt-1">
                        Updated daily as fresh loom cuts arrive at our Peelamedu showroom.
                    </p>
                </div>
                <a href="{{ route('products.new-arrivals') }}" class="btn-pill btn-maroon px-6 py-2.5 text-xs font-semibold tracking-wider uppercase shrink-0">
                    View All New Arrivals
                </a>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($newArrivals as $product)
                    <livewire:product-card :product="$product" :key="'new-arrival-'.$product->id" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- 4. Heritage Section: Peelamedu Flagship & Handloom Certification -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <!-- Text Content -->
            <div class="lg:col-span-6 space-y-6">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold tracking-widest uppercase bg-rose-100 text-maroon border border-rose-200">
                    Peelamedu Flagship Heritage
                </span>
                <h2 class="text-3xl sm:text-4xl font-bold text-stone-900 font-jost tracking-tight leading-snug">
                    Where Coimbatore Handloom Mastery Meets Generational Grace
                </h2>
                <p class="text-sm text-stone-600 leading-relaxed font-normal">
                    Located in the textile heart of <strong>Peelamedu, Coimbatore</strong>, Iswarya Silks (ஐஸ்வர்யா சில்க்ஸ்) stands as a beacon of genuine handloom heritage. Each saree is born from weeks of meticulous labor by master weavers using traditional wooden handlooms, certified mulberry silk, and authentic tested zari.
                </p>
                <div class="grid grid-cols-2 gap-6 pt-2">
                    <div class="border-l-2 border-gold pl-4">
                        <span class="text-2xl font-bold text-maroon font-jost">100%</span>
                        <p class="text-xs text-stone-600 mt-0.5">Pure Handloom Silk with Silk Mark Guarantee</p>
                    </div>
                    <div class="border-l-2 border-gold pl-4">
                        <span class="text-2xl font-bold text-maroon font-jost">Direct</span>
                        <p class="text-xs text-stone-600 mt-0.5">Weaver-to-Patron WhatsApp Concierge</p>
                    </div>
                </div>
                <div class="pt-4 flex gap-4">
                    <a href="{{ route('pages.about') }}" class="btn-pill btn-maroon px-8 py-3.5 text-xs uppercase font-semibold tracking-wider">
                        Our Weaving Story
                    </a>
                    <a href="{{ route('pages.contact') }}" class="btn-pill border border-stone-300 hover:bg-stone-100 px-6 py-3.5 text-xs uppercase font-semibold tracking-wider text-stone-800 transition-colors">
                        Visit Peelamedu Flagship
                    </a>
                </div>
            </div>

            <!-- Heritage Imagery & Badges -->
            <div class="lg:col-span-6 relative">
                <div class="relative rounded-2xl overflow-hidden aspect-[4/3] bg-stone-200 shadow-2xl border-4 border-white">
                    <img src="{{ asset('images/heritage-loom.webp') }}" alt="Traditional Handloom Weaver Iswarya Silks" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-stone-950/70 via-transparent to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6 text-white">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gold-light">Artisanal Loom Cluster</p>
                        <p class="text-sm font-medium mt-1">Master artisan practicing traditional Korvai shuttle interlock technique.</p>
                    </div>
                </div>
                <!-- Silk Mark Floating Badge -->
                <div class="absolute -top-6 -left-6 bg-white p-4 rounded-xl shadow-xl border border-rose-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-amber-50 text-gold flex items-center justify-center font-bold text-lg border border-gold/40">
                        ★
                    </div>
                    <div>
                        <p class="text-xs font-bold text-stone-900">Certified Pure Silk</p>
                        <p class="text-[10px] text-stone-500">Peelamedu, Coimbatore</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. Client Reviews / Testimonials -->
    <section class="bg-brand-pearl py-16 sm:py-24 border-t border-rose-100/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-xl mx-auto mb-12">
                <span class="text-xs uppercase tracking-widest text-gold font-semibold">Bridal Joy</span>
                <h2 class="text-3xl font-bold text-stone-900 font-jost tracking-tight mt-1">
                    Patron Testimonials
                </h2>
                <p class="text-xs sm:text-sm text-stone-500 mt-1">
                    Cherished memories from brides and saree connoisseurs across India and worldwide.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Review 1 -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-rose-100/70 flex flex-col justify-between">
                    <div>
                        <div class="flex text-gold mb-3">
                            ★★★★★
                        </div>
                        <p class="text-xs text-stone-600 leading-relaxed italic mb-4">
                            "The WhatsApp video shopping session made bridal saree shopping from Singapore so effortless. The Peelamedu team demonstrated the drape and zari in natural sunlight. When the saree arrived, the silk lustre exceeded all expectations."
                        </p>
                    </div>
                    <div class="pt-4 border-t border-rose-50 flex items-center justify-between text-xs">
                        <div>
                            <p class="font-semibold text-stone-900">Kavitha Sundaram</p>
                            <p class="text-[11px] text-stone-400">Singapore • Bridal Kanjeevaram</p>
                        </div>
                        <span class="text-[10px] text-emerald-700 font-semibold bg-emerald-50 px-2 py-0.5 rounded-full">Verified Patron</span>
                    </div>
                </div>

                <!-- Review 2 -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-rose-100/70 flex flex-col justify-between">
                    <div>
                        <div class="flex text-gold mb-3">
                            ★★★★★
                        </div>
                        <p class="text-xs text-stone-600 leading-relaxed italic mb-4">
                            "Direct WhatsApp enquiry is so refreshingly transparent. We discussed custom blouse stitching and border sizing directly with the manufacturer in Coimbatore. Truly authentic handloom quality."
                        </p>
                    </div>
                    <div class="pt-4 border-t border-rose-50 flex items-center justify-between text-xs">
                        <div>
                            <p class="font-semibold text-stone-900">Deepa Natarajan</p>
                            <p class="text-[11px] text-stone-400">Chennai • Muhurtham Korvai</p>
                        </div>
                        <span class="text-[10px] text-emerald-700 font-semibold bg-emerald-50 px-2 py-0.5 rounded-full">Verified Patron</span>
                    </div>
                </div>

                <!-- Review 3 -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-rose-100/70 flex flex-col justify-between">
                    <div>
                        <div class="flex text-gold mb-3">
                            ★★★★★
                        </div>
                        <p class="text-xs text-stone-600 leading-relaxed italic mb-4">
                            "Visited their Peelamedu showroom in Coimbatore during my wedding shopping. The collection is updated daily with one-of-a-kind weaver pieces. Worth every rupee."
                        </p>
                    </div>
                    <div class="pt-4 border-t border-rose-50 flex items-center justify-between text-xs">
                        <div>
                            <p class="font-semibold text-stone-900">Aiswarya Rajendran</p>
                            <p class="text-[11px] text-stone-400">Bangalore • Pure Soft Silk</p>
                        </div>
                        <span class="text-[10px] text-emerald-700 font-semibold bg-emerald-50 px-2 py-0.5 rounded-full">Verified Patron</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. Instagram Gallery (Manual / Static Luxury Grid) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
        <div class="text-center mb-10">
            <a href="{{ \App\Models\Setting::instagramUrl() }}" target="_blank" rel="noopener noreferrer" class="inline-block text-xs uppercase tracking-widest text-gold hover:text-maroon font-semibold transition-colors">
                {{ \App\Models\Setting::instagramHandle() }}
            </a>
            <h2 class="text-2xl sm:text-3xl font-bold text-stone-900 font-jost tracking-tight mt-1">
                Follow The Loom on Instagram
            </h2>
            <p class="text-xs text-stone-500 mt-1">
                Behind-the-scenes handloom craftsmanship and bridal portraits from Peelamedu.
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
            @for($i = 1; $i <= 6; $i++)
                <a href="{{ \App\Models\Setting::instagramUrl() }}" target="_blank" rel="noopener noreferrer" class="group relative aspect-square overflow-hidden rounded-lg bg-stone-200">
                    <img src="{{ asset('images/insta-'.$i.'.webp') }}" alt="Iswarya Silks Instagram {{ $i }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-stone-950/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center text-white">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </div>
                </a>
            @endfor
        </div>
    </section>
@endsection
