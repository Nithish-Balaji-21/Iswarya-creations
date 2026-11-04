@extends('layouts.admin')

@section('title', 'Edit Saree ' . $product->sku . ' – Iswarya Silks Admin')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <div class="flex items-center justify-between pb-6 border-b border-rose-100">
        <div>
            <span class="text-xs uppercase font-bold tracking-widest text-gold">Edit Saree</span>
            <h1 class="text-2xl font-bold text-stone-900 font-jost mt-1">{{ $product->name }} ({{ $product->sku }})</h1>
        </div>

        <a href="{{ route('admin.products.index') }}" class="btn-pill border border-stone-200 px-4 py-2 text-xs font-semibold text-stone-600 hover:bg-stone-50">
            Back to Inventory
        </a>
    </div>

    <!-- Image Management Box (Reorder & Primary Picker) -->
    <div class="bg-white rounded-2xl p-6 border border-rose-100 shadow-2xs space-y-4">
        <div class="flex items-center justify-between border-b border-rose-50 pb-3">
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-stone-800">Saree Photos & Primary Cover</h3>
                <p class="text-[11px] text-stone-500">Pick which photo serves as the storefront primary cover.</p>
            </div>
            <span class="text-xs font-mono font-semibold text-stone-600">{{ $product->images->count() }} Images</span>
        </div>

        @if($product->images->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
                @foreach($product->images as $img)
                    <div class="relative group aspect-[4/5] rounded-xl overflow-hidden border-2 {{ $img->is_primary ? 'border-maroon ring-2 ring-rose-200' : 'border-rose-100' }}">
                        <img src="{{ $img->medium_url }}" alt="Saree variant" class="w-full h-full object-cover">
                        
                        <!-- Primary Badge -->
                        @if($img->is_primary)
                            <span class="absolute top-1.5 left-1.5 bg-maroon text-white text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider shadow-xs">
                                Primary Cover
                            </span>
                        @else
                            <form method="POST" action="{{ route('admin.products.images.primary', [$product->id, $img->id]) }}" class="absolute top-1.5 left-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                @csrf
                                <button type="submit" class="bg-white/95 text-stone-800 hover:text-maroon text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider shadow-md">
                                    Make Primary
                                </button>
                            </form>
                        @endif

                        <!-- Delete Image Form -->
                        <form method="POST" action="{{ route('admin.products.images.delete', [$product->id, $img->id]) }}" onsubmit="return confirm('Delete this image variant?')" class="absolute top-1.5 right-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-6 h-6 rounded-full bg-rose-600 hover:bg-rose-800 text-white flex items-center justify-center text-xs shadow-md">
                                &times;
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-stone-400 py-3">No images uploaded for this saree yet.</p>
        @endif
    </div>

    <!-- Product Update Form -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-rose-100 shadow-2xs">
        <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                <div class="md:col-span-3">
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required class="w-full text-xs font-mono font-bold px-3 py-2.5 border border-rose-200 rounded-lg">
                </div>

                <div class="md:col-span-6">
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1">Title</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full text-xs px-3 py-2.5 border border-rose-200 rounded-lg">
                </div>

                <div class="md:col-span-3">
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1">Colour</label>
                    <input type="text" name="colour" value="{{ old('colour', $product->colour) }}" class="w-full text-xs px-3 py-2.5 border border-rose-200 rounded-lg">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1">Price (₹)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" class="w-full text-xs px-3 py-2.5 border border-rose-200 rounded-lg">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1">Discount Price (₹)</label>
                    <input type="number" step="0.01" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}" class="w-full text-xs px-3 py-2.5 border border-rose-200 rounded-lg">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1">Availability</label>
                    <select name="availability" class="w-full text-xs px-3 py-2.5 border border-rose-200 rounded-lg bg-white">
                        @foreach(\App\Enums\ProductAvailability::cases() as $av)
                            <option value="{{ $av->value }}" {{ old('availability', $product->availability->value) === $av->value ? 'selected' : '' }}>
                                {{ $av->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1">Status</label>
                    <select name="status" class="w-full text-xs px-3 py-2.5 border border-rose-200 rounded-lg bg-white">
                        @foreach(\App\Enums\ProductStatus::cases() as $st)
                            <option value="{{ $st->value }}" {{ old('status', $product->status->value) === $st->value ? 'selected' : '' }}>
                                {{ $st->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Categories Selection -->
            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Categories</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 p-4 rounded-xl bg-brand-pearl border border-rose-100 max-h-48 overflow-y-auto text-xs">
                    @php
                        $assignedIds = $product->categories->pluck('id')->toArray();
                    @endphp
                    @foreach($categories as $cat)
                        <label class="flex items-center gap-2 text-stone-700 cursor-pointer">
                            <input type="checkbox" name="categories[]" value="{{ $cat->id }}" {{ in_array($cat->id, old('categories', $assignedIds)) ? 'checked' : '' }} class="rounded text-maroon focus:ring-maroon">
                            <span class="truncate">{{ $cat->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Add More Photos -->
            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1">Add Additional Photos</label>
                <input type="file" name="photos[]" multiple accept="image/*" class="w-full text-xs px-3 py-2 border border-rose-200 rounded-lg bg-white">
                <p class="text-[11px] text-stone-400 mt-1">Uploaded images will be automatically cropped to 4:5 and converted to 3 WebP sizes.</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1">Short Description</label>
                <textarea name="short_description" rows="2" class="w-full text-xs px-3.5 py-2 border border-rose-200 rounded-lg">{{ old('short_description', $product->short_description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1">Full Description (Sanitized HTML)</label>
                <textarea name="description" rows="4" class="w-full text-xs px-3.5 py-2 border border-rose-200 rounded-lg">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-rose-100">
                <a href="{{ route('admin.products.index') }}" class="btn-pill border border-stone-200 px-6 py-2.5 text-xs font-semibold text-stone-600 hover:bg-stone-50">
                    Cancel
                </a>
                <button type="submit" class="btn-pill btn-maroon px-8 py-2.5 text-xs font-semibold tracking-wider uppercase shadow-md">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
