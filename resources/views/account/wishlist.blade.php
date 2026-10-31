@extends('layouts.app')

@section('title', 'My Wishlist – Iswarya Silks')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between pb-6 mb-8 border-b border-rose-100">
        <div>
            <span class="text-xs uppercase tracking-widest text-gold font-semibold">Saved Handlooms</span>
            <h1 class="text-3xl font-bold text-stone-900 font-jost mt-1">My Wishlist</h1>
            <p class="text-xs text-stone-500 mt-1">Direct one-click WhatsApp enquiry per item with snapshot pricing.</p>
        </div>

        <a href="{{ route('account.dashboard') }}" class="btn-pill border border-rose-200 bg-white px-4 py-2 text-xs font-semibold text-stone-700 hover:bg-rose-50">
            Back to Dashboard
        </a>
    </div>

    @if($wishlists->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($wishlists as $item)
                @if($item->product)
                    <div class="relative">
                        <livewire:product-card :product="$item->product" :key="'wish-prod-'.$item->product->id" source="wishlist" />
                        
                        <form method="POST" action="{{ route('account.wishlist.toggle', $item->product->id) }}" class="mt-2 text-center">
                            @csrf
                            <button type="submit" class="text-[11px] text-stone-400 hover:text-rose-600 transition-colors">
                                Remove from Wishlist
                            </button>
                        </form>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="mt-10">
            {{ $wishlists->links() }}
        </div>
    @else
        <div class="bg-white rounded-2xl p-12 text-center border border-rose-100 max-w-md mx-auto">
            <div class="w-16 h-16 bg-rose-50 text-maroon rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-stone-900 font-jost">Your Wishlist is Empty</h3>
            <p class="text-xs text-stone-500 mt-1 mb-6">Explore our curated pure silk collections and tap the heart icon on sarees you cherish.</p>
            <a href="{{ route('products.new-arrivals') }}" class="btn-pill btn-maroon px-6 py-2.5 text-xs font-semibold tracking-wider uppercase">
                Explore Sarees
            </a>
        </div>
    @endif
</div>
@endsection
