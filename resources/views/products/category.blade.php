@extends('layouts.app')

@section('title', $category->name . ' – Iswarya Silks | Peelamedu, Coimbatore')
@section('meta_description', 'Explore ' . $category->name . ' pure silk sarees from Iswarya Silks, Peelamedu, Coimbatore. Handloom certified with direct WhatsApp manufacturer enquiry.')

@section('content')
    <!-- Category Hero Banner & Breadcrumbs -->
    <section class="relative bg-brand-pearl border-b border-rose-100/80 py-10 sm:py-16 overflow-hidden">
        @if($category->banner_image)
            <img src="{{ $category->banner_image_url }}" alt="{{ $category->name }}" class="absolute inset-0 w-full h-full object-cover opacity-25">
        @endif
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="flex text-xs text-stone-500 mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2">
                    <li><a href="{{ route('home') }}" class="hover:text-maroon">Home</a></li>
                    <li><span class="text-stone-300">/</span></li>
                    <li class="capitalize text-stone-500">{{ $category->group->label() }}</li>
                    <li><span class="text-stone-300">/</span></li>
                    <li class="font-semibold text-stone-900">{{ $category->name }}</li>
                </ol>
            </nav>

            <div class="max-w-2xl">
                <span class="text-xs uppercase tracking-widest text-gold font-semibold block mb-1">
                    {{ $category->group->label() }} Collection
                </span>
                <h1 class="text-3xl sm:text-4xl font-bold text-stone-900 font-jost tracking-tight mb-3">
                    {{ $category->name }}
                </h1>
                @if($category->description)
                    <p class="text-xs sm:text-sm text-stone-600 leading-relaxed font-light">
                        {{ $category->description }}
                    </p>
                @endif
            </div>
        </div>
    </section>

    <!-- Main Catalog Area: Filters + Product Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ mobileFilterOpen: false }">
        <!-- Filter Header Bar -->
        <div class="flex flex-col sm:flex-row items-center justify-between pb-6 mb-8 border-b border-rose-100 gap-4">
            <div class="flex items-center gap-4 w-full sm:w-auto justify-between sm:justify-start">
                <button type="button" 
                        @click="mobileFilterOpen = !mobileFilterOpen"
                        class="lg:hidden btn-pill border border-rose-200 bg-white px-4 py-2 text-xs font-semibold text-stone-700 flex items-center gap-2">
                    <svg class="w-4 h-4 text-maroon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Filters</span>
                </button>

                <p class="text-xs text-stone-500">
                    Showing <span class="font-semibold text-stone-900">{{ $products->total() }}</span> handcrafted sarees
                </p>
            </div>

            <!-- Sort By Dropdown -->
            <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-2 w-full sm:w-auto justify-end">
                <!-- Keep other query parameters when sorting -->
                @foreach(request()->except(['sort', 'page']) as $k => $v)
                    @if(is_array($v))
                        @foreach($v as $subV)
                            <input type="hidden" name="{{ $k }}[]" value="{{ $subV }}">
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endif
                @endforeach

                <label for="sort-select" class="text-xs text-stone-500 whitespace-nowrap">Sort By:</label>
                <select id="sort-select" name="sort" onchange="this.form.submit()" class="text-xs bg-white border border-rose-200 rounded-lg px-3 py-2 text-stone-800 focus:outline-hidden focus:border-maroon">
                    <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest (Default)</option>
                    <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="popularity" {{ $sort === 'popularity' ? 'selected' : '' }}>Popularity</option>
                </select>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Sidebar Filters (Col 3 on Desktop) -->
            <aside class="lg:col-span-3">
                <form method="GET" action="{{ url()->current() }}" id="filter-form">
                    <input type="hidden" name="sort" value="{{ $sort }}">

                    <div class="space-y-6">
                        <!-- Filter Group: Price Range -->
                        <div class="bg-white p-5 rounded-xl border border-rose-100 shadow-2xs">
                            <h4 class="text-xs font-semibold uppercase tracking-wider text-stone-900 border-b border-rose-50 pb-2 mb-3">
                                Price Range (₹)
                            </h4>
                            <div class="grid grid-cols-2 gap-2 mb-3">
                                <div>
                                    <label class="text-[10px] text-stone-400 block mb-1">Min Price</label>
                                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="₹10,000" class="w-full text-xs px-2.5 py-1.5 border border-rose-200 rounded-lg">
                                </div>
                                <div>
                                    <label class="text-[10px] text-stone-400 block mb-1">Max Price</label>
                                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="₹1,50,000" class="w-full text-xs px-2.5 py-1.5 border border-rose-200 rounded-lg">
                                </div>
                            </div>
                            <button type="submit" class="btn-pill btn-maroon w-full py-1.5 text-[11px] font-semibold tracking-wider uppercase">
                                Apply Price
                            </button>
                        </div>

                        <!-- Filter Group: Availability -->
                        <div class="bg-white p-5 rounded-xl border border-rose-100 shadow-2xs">
                            <h4 class="text-xs font-semibold uppercase tracking-wider text-stone-900 border-b border-rose-50 pb-2 mb-3">
                                Availability
                            </h4>
                            <div class="space-y-2 text-xs">
                                <label class="flex items-center gap-2 text-stone-700 cursor-pointer">
                                    <input type="radio" name="availability" value="" {{ !request('availability') ? 'checked' : '' }} onchange="this.form.submit()" class="text-maroon focus:ring-maroon">
                                    <span>All Sarees</span>
                                </label>
                                <label class="flex items-center gap-2 text-stone-700 cursor-pointer">
                                    <input type="radio" name="availability" value="available" {{ request('availability') === 'available' ? 'checked' : '' }} onchange="this.form.submit()" class="text-maroon focus:ring-maroon">
                                    <span>Ready to Dispatch</span>
                                </label>
                                <label class="flex items-center gap-2 text-stone-700 cursor-pointer">
                                    <input type="radio" name="availability" value="on_request" {{ request('availability') === 'on_request' ? 'checked' : '' }} onchange="this.form.submit()" class="text-maroon focus:ring-maroon">
                                    <span>Made to Order</span>
                                </label>
                            </div>
                        </div>

                        <!-- Filter Group: Colours -->
                        @if($availableColours->count() > 0)
                            <div class="bg-white p-5 rounded-xl border border-rose-100 shadow-2xs">
                                <h4 class="text-xs font-semibold uppercase tracking-wider text-stone-900 border-b border-rose-50 pb-2 mb-3">
                                    Shade & Colour
                                </h4>
                                <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1 text-xs">
                                    @foreach($availableColours as $col)
                                        @php
                                            $checked = is_array(request('colour')) ? in_array($col, request('colour')) : request('colour') === $col;
                                        @endphp
                                        <label class="flex items-center justify-between text-stone-700 hover:text-maroon cursor-pointer py-0.5">
                                            <div class="flex items-center gap-2">
                                                <input type="checkbox" name="colour[]" value="{{ $col }}" {{ $checked ? 'checked' : '' }} onchange="this.form.submit()" class="rounded text-maroon focus:ring-maroon">
                                                <span>{{ $col }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Filter Group: Secondary Cross Categories -->
                        @if(isset($crossCategories) && $crossCategories->count() > 0)
                            <div class="bg-white p-5 rounded-xl border border-rose-100 shadow-2xs">
                                <h4 class="text-xs font-semibold uppercase tracking-wider text-stone-900 border-b border-rose-50 pb-2 mb-3">
                                    Filter by Weave / Occasion
                                </h4>
                                <div class="space-y-3 max-h-60 overflow-y-auto pr-1 text-xs">
                                    @foreach($crossCategories as $grpName => $cats)
                                        <div>
                                            <p class="text-[10px] font-bold uppercase tracking-wider text-gold mb-1">{{ $grpName }}</p>
                                            <div class="space-y-1 pl-1">
                                                @foreach($cats as $c)
                                                    @php
                                                        $selectedCat = is_array(request('filter_category')) ? in_array($c->id, request('filter_category')) : request('filter_category') == $c->id;
                                                    @endphp
                                                    <label class="flex items-center gap-2 text-stone-600 hover:text-maroon cursor-pointer">
                                                        <input type="checkbox" name="filter_category[]" value="{{ $c->id }}" {{ $selectedCat ? 'checked' : '' }} onchange="this.form.submit()" class="rounded text-maroon focus:ring-maroon">
                                                        <span class="truncate">{{ $c->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Clear Filters Button -->
                        @if(request()->hasAny(['min_price', 'max_price', 'colour', 'availability', 'filter_category']))
                            <a href="{{ url()->current() }}" class="btn-pill block w-full text-center py-2 text-xs font-semibold text-rose-700 border border-rose-200 hover:bg-rose-50">
                                Reset All Filters
                            </a>
                        @endif
                    </div>
                </form>
            </aside>

            <!-- Product Grid (Col 9 on Desktop) -->
            <main class="lg:col-span-9">
                @if($products->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <livewire:product-card :product="$product" :key="'cat-prod-'.$product->id" />
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-12">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-2xl p-12 text-center border border-rose-100 max-w-lg mx-auto">
                        <div class="w-16 h-16 bg-rose-50 text-maroon rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-stone-900 font-jost">No Sarees Found Matching Filters</h3>
                        <p class="text-xs text-stone-500 mt-1 mb-6">
                            Try broadening your price range or clearing color selections to explore more pieces.
                        </p>
                        <a href="{{ url()->current() }}" class="btn-pill btn-maroon px-6 py-2.5 text-xs font-semibold tracking-wider uppercase">
                            View All {{ $category->name }} Sarees
                        </a>
                    </div>
                @endif
            </main>
        </div>
    </div>
@endsection
