@extends('layouts.admin')

@section('title', 'Admin Dashboard – Iswarya Silks Management')
@section('page_heading', 'Storefront & Inventory Overview')

@section('content')
<div class="space-y-8">
    <!-- Top Greeting Banner -->
    <div class="bg-white rounded-2xl border border-stone-200 p-6 sm:p-8 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-xs uppercase font-bold tracking-widest text-gold-dark">Live Storefront Status: Active</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-stone-900 font-jost mt-1">Showroom Operations Center</h1>
            <p class="text-xs text-stone-500 mt-1">
                Peelamedu, Coimbatore flagship master catalog, categories, and storefront configuration.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.products.create') }}" 
               class="btn-pill btn-maroon px-5 py-2.5 text-xs font-semibold tracking-wider uppercase flex items-center gap-2 shadow-md">
                <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Add New Saree</span>
            </a>
            <a href="{{ route('admin.settings.edit') }}" 
               class="btn-pill border border-stone-300 bg-stone-50 hover:bg-stone-100 text-stone-700 px-4 py-2.5 text-xs font-semibold tracking-wider uppercase flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                <span>Store Settings</span>
            </a>
        </div>
    </div>

    <!-- 4 High-Density Inventory Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1: Active Sarees Live -->
        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-xs hover:border-maroon/30 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-stone-400">Live In Showroom</p>
                    <p class="text-3xl font-bold text-stone-900 font-jost mt-1">{{ $activeSareesCount }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-stone-100 flex items-center justify-between text-[11px]">
                <span class="text-stone-500">Published & available for order</span>
                <a href="{{ route('admin.products.index') }}" class="text-maroon font-semibold hover:underline">View &rarr;</a>
            </div>
        </div>

        <!-- Card 2: Total Sarees in Catalog -->
        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-xs hover:border-maroon/30 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-stone-400">Total Sarees</p>
                    <p class="text-3xl font-bold text-maroon font-jost mt-1">{{ $totalSareesCount }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-rose-50 text-maroon flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-stone-100 flex items-center justify-between text-[11px]">
                <span class="text-stone-500">{{ $draftSareesCount }} unpublished drafts</span>
                <a href="{{ route('admin.products.create') }}" class="text-maroon font-semibold hover:underline">+ Add &rarr;</a>
            </div>
        </div>

        <!-- Card 3: Sold Out Sarees -->
        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-xs hover:border-maroon/30 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-stone-400">Sold Out Sarees</p>
                    <p class="text-3xl font-bold text-amber-600 font-jost mt-1">{{ $soldOutCount }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-stone-100 flex items-center justify-between text-[11px]">
                <span class="text-stone-500">Marked as sold out</span>
                <a href="{{ route('admin.products.index') }}?status=sold_out" class="text-amber-700 font-semibold hover:underline">Manage &rarr;</a>
            </div>
        </div>

        <!-- Card 4: Categories & Banners -->
        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-xs hover:border-maroon/30 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-stone-400">Active Categories</p>
                    <p class="text-3xl font-bold text-stone-900 font-jost mt-1">{{ $categoriesCount }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-stone-100 flex items-center justify-between text-[11px]">
                <span class="text-stone-500">{{ $bannersCount }} homepage hero banners</span>
                <a href="{{ route('admin.categories.index') }}" class="text-purple-700 font-semibold hover:underline">Manage &rarr;</a>
            </div>
        </div>
    </div>

    <!-- Main Content Split: Recent Sarees (Left) & Storefront Configuration (Right) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left: Recently Added Sarees (Span 8) -->
        <div class="lg:col-span-8 bg-white rounded-2xl border border-stone-200 shadow-xs p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                <div>
                    <h3 class="text-base font-bold text-stone-900 font-jost">Recent Saree Additions</h3>
                    <p class="text-xs text-stone-500">Quickly toggle stock availability or edit freshly added sarees.</p>
                </div>
                <a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-maroon hover:underline flex items-center gap-1">
                    <span>View All Catalog</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <!-- Sarees Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-[11px] font-bold uppercase tracking-wider text-stone-400 border-b border-stone-100 pb-2">
                            <th class="py-2.5 font-bold">Saree Details</th>
                            <th class="py-2.5 font-bold">Price</th>
                            <th class="py-2.5 font-bold">Categories</th>
                            <th class="py-2.5 font-bold">Stock Status</th>
                            <th class="py-2.5 font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse($recentSarees as $product)
                            <tr class="hover:bg-stone-50/70 transition-colors">
                                <td class="py-3 pr-3">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $product->primary_image_url }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-12 h-14 object-cover rounded-lg border border-stone-200 shrink-0">
                                        <div class="min-w-0">
                                            <p class="font-bold text-stone-900 truncate max-w-xs">{{ $product->name }}</p>
                                            <span class="inline-block font-mono text-[10px] text-stone-500 bg-stone-100 px-1.5 py-0.5 rounded mt-0.5">
                                                {{ $product->sku }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 font-semibold text-stone-800 whitespace-nowrap">
                                    {{ $product->formatted_price }}
                                </td>
                                <td class="py-3">
                                    <div class="flex flex-wrap gap-1 max-w-xs">
                                        @foreach($product->categories->take(2) as $category)
                                            <span class="inline-block text-[10px] bg-rose-50 text-maroon px-2 py-0.5 rounded-full font-medium">
                                                {{ $category->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="py-3 whitespace-nowrap">
                                    <form method="POST" action="{{ route('admin.products.toggle-availability', $product) }}">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider transition-colors {{ $product->availability === \App\Enums\ProductAvailability::Available ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-rose-100 text-rose-800 hover:bg-rose-200' }}"
                                                title="Click to toggle availability">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $product->availability === \App\Enums\ProductAvailability::Available ? 'bg-emerald-600' : 'bg-rose-600' }}"></span>
                                            <span>{{ $product->availability->label() }}</span>
                                        </button>
                                    </form>
                                </td>
                                <td class="py-3 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.products.edit', $product) }}" 
                                           class="p-1.5 text-stone-600 hover:text-maroon hover:bg-rose-50 rounded-lg transition-colors" 
                                           title="Edit Saree">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <a href="{{ route('products.show', $product->slug) }}" 
                                           target="_blank" 
                                           class="p-1.5 text-stone-400 hover:text-stone-700 hover:bg-stone-100 rounded-lg transition-colors" 
                                           title="View Saree on Website">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-stone-400">
                                    No sarees added yet. Click <a href="{{ route('admin.products.create') }}" class="text-maroon underline font-semibold">Add Saree</a> to create your first listing.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right: Live Website Settings & Quick Actions (Span 4) -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Website Configuration Card -->
            <div class="bg-white rounded-2xl border border-stone-200 shadow-xs p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                    <h3 class="text-base font-bold text-stone-900 font-jost">Website Configuration</h3>
                    <a href="{{ route('admin.settings.edit') }}" class="text-xs font-semibold text-maroon hover:underline">Edit</a>
                </div>

                <!-- Active WhatsApp -->
                <div class="bg-emerald-50/70 border border-emerald-200/80 rounded-xl p-3.5 space-y-1 text-xs">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-800 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 fill-current text-emerald-600" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.587-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z"/></svg>
                        <span>Active WhatsApp Number</span>
                    </span>
                    <p class="font-mono font-bold text-stone-900 text-sm">
                        +{{ $storeSettings['whatsapp_number'] }}
                    </p>
                    <p class="text-[11px] text-stone-500">Receives all saree enquiries from customer cards & product detail pages.</p>
                </div>

                <!-- Showroom Location -->
                <div class="border border-stone-200 rounded-xl p-3.5 space-y-1 text-xs">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-stone-400 block">Showroom Location</span>
                    <p class="text-stone-800 font-medium leading-relaxed">
                        {{ $storeSettings['store_address'] }}
                    </p>
                    @if($storeSettings['google_maps_url'])
                        <a href="{{ $storeSettings['google_maps_url'] }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] text-maroon hover:underline font-semibold mt-1">
                            <span>Test Google Maps Link</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    @endif
                </div>

                <!-- Announcement Bar Preview -->
                <div class="border border-stone-200 rounded-xl p-3.5 space-y-1.5 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-stone-400">Storefront Announcement</span>
                        <span class="text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full {{ $storeSettings['announcement_enabled'] ? 'bg-emerald-100 text-emerald-800' : 'bg-stone-100 text-stone-500' }}">
                            {{ $storeSettings['announcement_enabled'] ? 'Enabled' : 'Disabled' }}
                        </span>
                    </div>
                    <p class="text-stone-700 italic bg-stone-50 p-2 rounded border border-stone-100 text-[11px]">
                        "{{ $storeSettings['announcement_text'] }}"
                    </p>
                </div>

                <a href="{{ route('admin.settings.edit') }}" 
                   class="btn-pill btn-maroon w-full py-2.5 text-xs font-semibold tracking-wider uppercase text-center block shadow-xs">
                    Manage Full Website Settings
                </a>
            </div>

            <!-- Quick Website Shortcuts -->
            <div class="bg-white rounded-2xl border border-stone-200 shadow-xs p-5 space-y-2.5 text-xs font-semibold">
                <span class="text-[10px] font-bold uppercase tracking-wider text-stone-400 block mb-1">Quick Website Shortcuts</span>
                
                <a href="{{ route('admin.products.create') }}" class="flex items-center justify-between p-2.5 hover:bg-stone-50 rounded-xl transition-colors text-stone-800">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-maroon"></span>
                        <span>Add New Saree Listing</span>
                    </span>
                    <span class="text-stone-400">&rarr;</span>
                </a>

                <a href="{{ route('admin.categories.create') }}" class="flex items-center justify-between p-2.5 hover:bg-stone-50 rounded-xl transition-colors text-stone-800">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-gold-dark"></span>
                        <span>Create New Category</span>
                    </span>
                    <span class="text-stone-400">&rarr;</span>
                </a>

                <a href="{{ route('admin.banners.create') }}" class="flex items-center justify-between p-2.5 hover:bg-stone-50 rounded-xl transition-colors text-stone-800">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                        <span>Upload Hero Slider Banner</span>
                    </span>
                    <span class="text-stone-400">&rarr;</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
