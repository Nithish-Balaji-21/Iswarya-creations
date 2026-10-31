@extends('layouts.admin')

@section('title', 'Admin Dashboard – Iswarya Silks Management')

@section('content')
<div class="space-y-8">
    <!-- Top Greeting Banner -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-6 border-b border-rose-100 gap-4">
        <div>
            <span class="text-xs uppercase font-bold tracking-widest text-gold">Daily Operations Overview</span>
            <h1 class="text-3xl font-bold text-stone-900 font-jost mt-1">Showroom Operations Center</h1>
            <p class="text-xs text-stone-500 mt-1">Peelamedu, Coimbatore flagship catalog and customer WhatsApp leads.</p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('admin.products.create') }}" class="btn-pill btn-maroon px-6 py-2.5 text-xs font-semibold tracking-wider uppercase flex items-center gap-2 shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Quick Add Saree (< 30s)</span>
            </a>
            <a href="{{ route('admin.enquiries.index') }}" class="btn-pill border border-rose-200 bg-white px-4 py-2 text-xs font-semibold text-stone-700 hover:bg-rose-50">
                View All Enquiries
            </a>
        </div>
    </div>

    <!-- 4 Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Active Sarees -->
        <div class="bg-white p-6 rounded-2xl border border-rose-100 shadow-2xs">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-stone-400">Active Sarees</p>
                    <p class="text-3xl font-bold text-stone-900 font-jost mt-2">{{ $activeSareesCount }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-rose-50 text-maroon flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
            </div>
            <p class="text-[11px] text-stone-500 mt-3 flex items-center gap-1">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Published & available on public storefront</span>
            </p>
        </div>

        <!-- Card 2: New Enquiries -->
        <div class="bg-white p-6 rounded-2xl border border-rose-100 shadow-2xs">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-stone-400">New Enquiries</p>
                    <p class="text-3xl font-bold text-amber-600 font-jost mt-2">{{ $newEnquiriesCount }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.587-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z"/></svg>
                </div>
            </div>
            <p class="text-[11px] text-stone-500 mt-3">Awaiting response from Peelamedu staff</p>
        </div>

        <!-- Card 3: This Month's Enquiries -->
        <div class="bg-white p-6 rounded-2xl border border-rose-100 shadow-2xs">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-stone-400">This Month's Enquiries</p>
                    <p class="text-3xl font-bold text-maroon font-jost mt-2">{{ $thisMonthEnquiriesCount }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-rose-50 text-maroon flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <p class="text-[11px] text-stone-500 mt-3">Total lead taps since {{ now()->startOfMonth()->format('d M') }}</p>
        </div>

        <!-- Card 4: Active Categories -->
        <div class="bg-white p-6 rounded-2xl border border-rose-100 shadow-2xs">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-stone-400">Active Categories</p>
                    <p class="text-3xl font-bold text-stone-900 font-jost mt-2">{{ $categoriesCount }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-gold-light text-gold-dark flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
            </div>
            <p class="text-[11px] text-stone-500 mt-3">Weave, Fabric, Occasion & Kanjeevaram</p>
        </div>
    </div>

    <!-- Recent Enquiries Table -->
    <div class="bg-white rounded-2xl border border-rose-100 shadow-2xs p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-base font-bold text-stone-900 font-jost">Recent WhatsApp Enquiries</h3>
                <p class="text-xs text-stone-500">Live feed of customers enquiring from saree cards and product pages.</p>
            </div>
            <a href="{{ route('admin.enquiries.index') }}" class="text-xs font-semibold text-maroon hover:underline">
                View Full Manager &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-brand-pearl text-stone-600 uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="py-3 px-4">Saree & SKU</th>
                        <th class="py-3 px-4">Price</th>
                        <th class="py-3 px-4">Customer</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Time</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-rose-50">
                    @forelse($recentEnquiries as $enquiry)
                        <tr class="hover:bg-rose-50/20">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-10 bg-brand-pearl rounded overflow-hidden shrink-0">
                                        @if($enquiry->product)
                                            <img src="{{ $enquiry->product->primary_image_url }}" alt="{{ $enquiry->product_name }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-stone-900">{{ $enquiry->product_name }}</p>
                                        <p class="text-[10px] font-mono text-stone-400">SKU: {{ $enquiry->product_sku }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 font-semibold text-maroon font-jost">{{ $enquiry->formatted_price }}</td>
                            <td class="py-3 px-4">
                                <p class="font-semibold text-stone-900">{{ $enquiry->customer_name ?? 'Guest' }}</p>
                                <p class="text-[10px] text-stone-500">{{ $enquiry->customer_phone ?? 'Via WhatsApp' }}</p>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold border {{ $enquiry->status->badgeClasses() }}">
                                    {{ $enquiry->status->label() }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-stone-400">{{ $enquiry->created_at->diffForHumans() }}</td>
                            <td class="py-3 px-4 text-right">
                                @if($enquiry->reply_whats_app_url)
                                    <a href="{{ $enquiry->reply_whats_app_url }}" target="_blank" rel="noopener noreferrer" class="btn-pill btn-whatsapp px-3 py-1 text-[11px] font-semibold">
                                        Reply on WhatsApp
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-xs text-stone-400">
                                No customer enquiries logged yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
