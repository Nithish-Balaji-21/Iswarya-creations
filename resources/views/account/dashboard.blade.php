@extends('layouts.app')

@section('title', 'My Account – Iswarya Silks')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-6 mb-8 border-b border-rose-100 gap-4">
        <div>
            <span class="text-xs uppercase tracking-widest text-gold font-semibold">Customer Portal</span>
            <h1 class="text-3xl font-bold text-stone-900 font-jost mt-1">Vanakkam, {{ $user->name }}!</h1>
            <p class="text-xs text-stone-500 mt-1">{{ $user->email ?? $user->phone }}</p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('account.wishlist') }}" class="btn-pill btn-maroon px-5 py-2 text-xs font-semibold tracking-wider uppercase">
                View My Wishlist ({{ $wishlistCount }})
            </a>
            <a href="{{ route('account.profile') }}" class="btn-pill border border-rose-200 bg-white px-4 py-2 text-xs font-semibold text-stone-700 hover:bg-rose-50">
                Edit Profile
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
        <a href="{{ route('account.wishlist') }}" class="bg-white p-6 rounded-2xl border border-rose-100 shadow-2xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-stone-400 font-semibold uppercase tracking-wider">Saved in Wishlist</p>
                    <p class="text-3xl font-bold text-maroon font-jost mt-2">{{ $wishlistCount }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-rose-50 text-maroon flex items-center justify-center">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                </div>
            </div>
            <p class="text-[11px] text-stone-500 mt-3">Click to open direct WhatsApp enquiries for saved sarees.</p>
        </a>

        <a href="{{ route('account.enquiries') }}" class="bg-white p-6 rounded-2xl border border-rose-100 shadow-2xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-stone-400 font-semibold uppercase tracking-wider">Total Enquiries</p>
                    <p class="text-3xl font-bold text-stone-900 font-jost mt-2">{{ $enquiriesCount }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.587-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z"/></svg>
                </div>
            </div>
            <p class="text-[11px] text-stone-500 mt-3">Snapshots of WhatsApp enquiries sent to Peelamedu.</p>
        </a>

        <a href="{{ route('video-shopping') }}" class="bg-white p-6 rounded-2xl border border-rose-100 shadow-2xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-stone-400 font-semibold uppercase tracking-wider">Video Shopping</p>
                    <p class="text-xl font-bold text-stone-900 font-jost mt-2">Book a View</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-gold-light text-gold-dark flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <p class="text-[11px] text-stone-500 mt-3">One-on-one virtual showroom viewings.</p>
        </a>
    </div>

    <!-- Recent Enquiries -->
    <div class="bg-white rounded-2xl p-6 border border-rose-100 shadow-2xs">
        <h3 class="text-sm font-semibold uppercase tracking-wider text-stone-900 mb-4 border-b border-rose-50 pb-2">
            Recent Enquiries Snapshot
        </h3>

        @if($recentEnquiries->count() > 0)
            <div class="divide-y divide-rose-50 text-xs">
                @foreach($recentEnquiries as $enquiry)
                    <div class="py-3 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-12 bg-brand-pearl rounded overflow-hidden shrink-0">
                                @if($enquiry->product)
                                    <img src="{{ $enquiry->product->primary_image_url }}" alt="{{ $enquiry->product_name }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div>
                                <p class="font-semibold text-stone-900">{{ $enquiry->product_name }}</p>
                                <p class="text-[10px] text-stone-400">SKU: {{ $enquiry->product_sku }} • Enquired {{ $enquiry->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <span class="font-semibold text-maroon">{{ $enquiry->formatted_price }}</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold border {{ $enquiry->status->badgeClasses() }}">
                                {{ $enquiry->status->label() }}
                            </span>
                            @if($enquiry->product)
                                <a href="{{ route('enquiry.redirect', $enquiry->product->id) }}?source=wishlist" class="btn-pill btn-whatsapp px-3 py-1 text-[11px] font-semibold">
                                    Re-Enquire
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-stone-500 py-4 text-center">You have not sent any WhatsApp enquiries yet. Browse our catalog to enquire.</p>
        @endif
    </div>
</div>
@endsection
