@extends('layouts.admin')

@section('title', 'Manage Saree Inventory – Iswarya Silks Admin')

@section('content')
<div class="space-y-6">
    <!-- Top Bar with Quick Add CTA -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-6 border-b border-rose-100 gap-4">
        <div>
            <span class="text-xs uppercase font-bold tracking-widest text-gold">Daily Stock & Catalog</span>
            <h1 class="text-3xl font-bold text-stone-900 font-jost mt-1">Saree Inventory</h1>
            <p class="text-xs text-stone-500 mt-1">Inline one-tap toggles for instant daily stock updates.</p>
        </div>

        <a href="{{ route('admin.products.create') }}" class="btn-pill btn-maroon px-6 py-2.5 text-xs font-semibold tracking-wider uppercase flex items-center gap-2 shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Quick Add Saree (< 30s)</span>
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-5 rounded-2xl border border-rose-100 shadow-2xs">
        <form method="GET" action="{{ route('admin.products.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 text-xs">
            <!-- Search -->
            <div class="lg:col-span-2">
                <label class="block font-semibold text-stone-600 mb-1">Search SKU, Name, Colour</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="e.g. IC-0001 or Kanjeevaram..." class="w-full px-3 py-2 border border-rose-200 rounded-lg">
            </div>

            <!-- Category -->
            <div>
                <label class="block font-semibold text-stone-600 mb-1">Category</label>
                <select name="category_id" class="w-full px-3 py-2 border border-rose-200 rounded-lg bg-white">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }} ({{ $cat->group->value }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Availability -->
            <div>
                <label class="block font-semibold text-stone-600 mb-1">Availability</label>
                <select name="availability" class="w-full px-3 py-2 border border-rose-200 rounded-lg bg-white">
                    <option value="">All Stock States</option>
                    @foreach(\App\Enums\ProductAvailability::cases() as $av)
                        <option value="{{ $av->value }}" {{ request('availability') === $av->value ? 'selected' : '' }}>
                            {{ $av->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="btn-pill btn-maroon w-full py-2 font-semibold">
                    Filter
                </button>
                @if(request()->hasAny(['q', 'category_id', 'status', 'availability']))
                    <a href="{{ route('admin.products.index') }}" class="btn-pill border border-stone-300 hover:bg-stone-100 px-3 py-2 text-stone-600 text-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Products Table with Inline Toggles -->
    <div class="bg-white rounded-2xl border border-rose-100 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-brand-pearl text-stone-600 uppercase tracking-wider text-[10px] border-b border-rose-100">
                    <tr>
                        <th class="py-3 px-4">Saree</th>
                        <th class="py-3 px-4">SKU</th>
                        <th class="py-3 px-4">Price</th>
                        <th class="py-3 px-4">Categories</th>
                        <th class="py-3 px-4">Stock Availability (One-Tap)</th>
                        <th class="py-3 px-4">Catalog Status (One-Tap)</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-rose-50">
                    @forelse($products as $product)
                        <tr class="hover:bg-rose-50/20 transition-colors">
                            <!-- Saree Thumbnail & Name -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-15 bg-brand-pearl rounded overflow-hidden shrink-0 border border-rose-100">
                                        <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="font-bold text-stone-900 hover:text-maroon line-clamp-1">
                                            {{ $product->name }}
                                        </a>
                                        <p class="text-[10px] text-stone-500 mt-0.5">
                                            {{ $product->colour ?? 'Standard' }} • {{ $product->images->count() }} photos • {{ $product->view_count }} views
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <!-- SKU -->
                            <td class="py-3.5 px-4 font-mono font-bold text-stone-700">
                                {{ $product->sku }}
                            </td>

                            <!-- Price -->
                            <td class="py-3.5 px-4">
                                @if($product->price === null)
                                    <span class="text-gold font-semibold uppercase text-[10px]">On Request</span>
                                @elseif($product->has_discount)
                                    <p class="font-bold text-maroon font-jost text-sm">{{ $product->formatted_discount_price }}</p>
                                    <p class="text-[10px] text-stone-400 line-through">{{ $product->formatted_price }}</p>
                                @else
                                    <p class="font-bold text-stone-900 font-jost text-sm">{{ $product->formatted_price }}</p>
                                @endif
                            </td>

                            <!-- Categories -->
                            <td class="py-3.5 px-4">
                                <div class="flex flex-wrap gap-1 max-w-xs">
                                    @forelse($product->categories as $cat)
                                        <span class="px-2 py-0.5 rounded text-[10px] bg-rose-50 text-maroon border border-rose-100 font-medium">
                                            {{ $cat->name }}
                                        </span>
                                    @empty
                                        <span class="text-stone-400 text-[10px]">Uncategorized</span>
                                    @endforelse
                                </div>
                            </td>

                            <!-- INLINE ONE-TAP TOGGLE: Available <-> Sold Out -->
                            <td class="py-3.5 px-4">
                                <form method="POST" action="{{ route('admin.products.toggle-availability', $product->id) }}">
                                    @csrf
                                    <button type="submit" 
                                            class="btn-pill px-3 py-1 text-[11px] font-semibold border transition-all duration-200 {{ $product->is_sold_out ? 'bg-stone-900 text-white border-stone-900 hover:bg-stone-800' : 'bg-emerald-50 text-emerald-800 border-emerald-300 hover:bg-emerald-100' }}"
                                            title="Click to toggle availability">
                                        {{ $product->availability->label() }}
                                    </button>
                                </form>
                            </td>

                            <!-- INLINE ONE-TAP TOGGLE: Publish <-> Draft -->
                            <td class="py-3.5 px-4">
                                <form method="POST" action="{{ route('admin.products.toggle-status', $product->id) }}">
                                    @csrf
                                    <button type="submit" 
                                            class="btn-pill px-3 py-1 text-[11px] font-semibold border transition-all duration-200 {{ $product->status === \App\Enums\ProductStatus::Published ? 'bg-maroon text-white border-maroon hover:bg-maroon-hover' : 'bg-stone-100 text-stone-600 border-stone-300 hover:bg-stone-200' }}"
                                            title="Click to toggle Draft / Published">
                                        {{ $product->status->label() }}
                                    </button>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="p-1.5 text-stone-600 hover:text-maroon rounded-md hover:bg-rose-50" title="Edit Product">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>

                                    <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" onsubmit="return confirm('Remove {{ $product->sku }} from catalog? Photos will be cleaned up.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-800 rounded-md hover:bg-rose-50" title="Delete Saree">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-xs text-stone-500">
                                No sarees found in this view. Use "Quick Add" to list a saree in under 30 seconds!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-rose-50">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
