@extends('layouts.app')

@section('title', 'New Arrivals – Iswarya Silks | Fresh Loom Cuts')
@section('meta_description', 'Browse the latest handloom pure silk saree arrivals from Iswarya Silks, Peelamedu, Coimbatore. Fresh creations woven by generational master weavers.')

@section('content')
    <section class="bg-brand-pearl border-b border-rose-100/80 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center max-w-2xl">
            <span class="text-xs uppercase tracking-widest text-gold font-semibold block mb-1">
                Fresh Off The Loom • Last 30 Days
            </span>
            <h1 class="text-3xl sm:text-4xl font-bold text-stone-900 font-jost tracking-tight mb-2">
                New Arrivals
            </h1>
            <p class="text-xs sm:text-sm text-stone-600 font-light">
                Our boutique collection updates daily as fresh loom cuts arrive from Coimbatore and Tamil Nadu weaving clusters.
            </p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Sort bar -->
        <div class="flex items-center justify-between pb-6 mb-8 border-b border-rose-100 text-xs">
            <p class="text-stone-500">
                Displaying <span class="font-semibold text-stone-900">{{ $products->total() }}</span> newly released sarees
            </p>

            <form method="GET" action="{{ route('products.new-arrivals') }}" class="flex items-center gap-2">
                <label for="sort-select" class="text-stone-500">Sort By:</label>
                <select id="sort-select" name="sort" onchange="this.form.submit()" class="bg-white border border-rose-200 rounded-lg px-3 py-1.5 text-stone-800 focus:outline-hidden focus:border-maroon">
                    <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest Releases</option>
                    <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="popularity" {{ $sort === 'popularity' ? 'selected' : '' }}>Most Viewed</option>
                </select>
            </form>
        </div>

        @if($products->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <livewire:product-card :product="$product" :key="'new-arr-'.$product->id" />
                @endforeach
            </div>

            <div class="mt-12">
                {{ $products->links() }}
            </div>
        @else
            <div class="bg-white rounded-2xl p-12 text-center border border-rose-100 max-w-md mx-auto">
                <p class="text-sm font-semibold text-stone-700">Check back soon!</p>
                <p class="text-xs text-stone-500 mt-1">New loom releases will be published in the morning.</p>
            </div>
        @endif
    </div>
@endsection
