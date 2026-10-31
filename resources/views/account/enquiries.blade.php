@extends('layouts.app')

@section('title', 'My Enquiries – Iswarya Silks')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between pb-6 mb-8 border-b border-rose-100">
        <div>
            <span class="text-xs uppercase tracking-widest text-gold font-semibold">Concierge History</span>
            <h1 class="text-3xl font-bold text-stone-900 font-jost mt-1">My Enquiries History</h1>
            <p class="text-xs text-stone-500 mt-1">Record of WhatsApp enquiries sent to our Peelamedu, Coimbatore showroom.</p>
        </div>

        <a href="{{ route('account.dashboard') }}" class="btn-pill border border-rose-200 bg-white px-4 py-2 text-xs font-semibold text-stone-700 hover:bg-rose-50">
            Back to Dashboard
        </a>
    </div>

    @if($enquiries->count() > 0)
        <div class="bg-white rounded-2xl border border-rose-100 shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-brand-pearl text-stone-600 uppercase tracking-wider text-[10px] border-b border-rose-100">
                        <tr>
                            <th class="py-3 px-4">Saree</th>
                            <th class="py-3 px-4">SKU</th>
                            <th class="py-3 px-4">Snapshot Price</th>
                            <th class="py-3 px-4">Enquired At</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-rose-50">
                        @foreach($enquiries as $enquiry)
                            <tr class="hover:bg-rose-50/30 transition-colors">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-12 bg-stone-100 rounded overflow-hidden shrink-0">
                                            @if($enquiry->product)
                                                <img src="{{ $enquiry->product->primary_image_url }}" alt="{{ $enquiry->product_name }}" class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                        <span class="font-semibold text-stone-900">{{ $enquiry->product_name }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-stone-500">{{ $enquiry->product_sku }}</td>
                                <td class="py-3.5 px-4 font-semibold text-maroon">{{ $enquiry->formatted_price }}</td>
                                <td class="py-3.5 px-4 text-stone-400">{{ $enquiry->created_at->format('d M Y, h:i A') }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-semibold border {{ $enquiry->status->badgeClasses() }}">
                                        {{ $enquiry->status->label() }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    @if($enquiry->product)
                                        <a href="{{ route('enquiry.redirect', $enquiry->product->id) }}?source=wishlist" class="btn-pill btn-whatsapp px-3 py-1 text-[11px] font-semibold inline-flex items-center gap-1.5 shadow-2xs">
                                            <span>Chat</span>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8">
            {{ $enquiries->links() }}
        </div>
    @else
        <div class="bg-white rounded-2xl p-12 text-center border border-rose-100 max-w-md mx-auto">
            <h3 class="text-base font-bold text-stone-900 font-jost">No Enquiries Recorded</h3>
            <p class="text-xs text-stone-500 mt-1 mb-4">You have not submitted any enquiries yet.</p>
            <a href="{{ route('products.new-arrivals') }}" class="btn-pill btn-maroon px-6 py-2 text-xs font-semibold tracking-wider uppercase">
                Explore Catalog
            </a>
        </div>
    @endif
</div>
@endsection
