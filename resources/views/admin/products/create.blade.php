@extends('layouts.admin')

@section('title', 'Quick Add Saree – Iswarya Silks Admin')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-rose-100 p-6 sm:p-8"
         x-data="{
             files: [],
             previews: [],
             isDropping: false,
             isPublished: true,
             handleFiles(fileList) {
                 const newFiles = Array.from(fileList);
                 for (let file of newFiles) {
                     if (this.files.length >= 10) break;
                     if (!file.type.startsWith('image/')) continue;
                     this.files.push(file);
                     this.previews.push({
                         name: file.name,
                         url: URL.createObjectURL(file),
                         size: (file.size / (1024 * 1024)).toFixed(1) + 'MB'
                     });
                 }
                 this.syncInput();
             },
             removePhoto(index) {
                 if (this.previews[index]) {
                     URL.revokeObjectURL(this.previews[index].url);
                 }
                 this.files.splice(index, 1);
                 this.previews.splice(index, 1);
                 this.syncInput();
             },
             syncInput() {
                 const dt = new DataTransfer();
                 this.files.forEach(f => dt.items.add(f));
                 const input = document.getElementById('photo-input');
                 if (input) {
                     input.files = dt.files;
                 }
             }
         }">

        <div class="flex items-center justify-between pb-6 mb-6 border-b border-rose-100">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-gold">Under 30-Second Fast Listing</span>
                <h1 class="text-2xl font-bold text-stone-900 font-jost mt-0.5">Quick Add Silk Saree</h1>
            </div>

            <a href="{{ route('admin.products.index') }}" class="btn-pill border border-stone-200 px-4 py-2 text-xs font-semibold text-stone-600 hover:bg-stone-50">
                Cancel & Back
            </a>
        </div>

        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Hidden actual file input synced via DataTransfer -->
            <input type="file"
                   name="photos[]"
                   id="photo-input"
                   multiple
                   accept="image/*"
                   class="hidden">

            <!-- Photo Upload Dropzone (Up to 10 photos) -->
            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">
                    Saree Photos (Drag & Drop up to 10 photos) <span class="text-rose-600">*</span>
                </label>
                
                <div class="border-2 border-dashed border-rose-200 hover:border-maroon rounded-2xl p-6 text-center bg-brand-pearl/50 transition-colors relative"
                     :class="{ 'border-maroon bg-rose-50/50': isDropping }"
                     @dragover.prevent="isDropping = true"
                     @dragleave.prevent="isDropping = false"
                     @drop.prevent="isDropping = false; handleFiles($event.dataTransfer.files)">
                    
                    <input type="file"
                           multiple
                           accept="image/*"
                           id="browse-input"
                           @change="handleFiles($event.target.files); $event.target.value = ''"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                    <div class="space-y-2 pointer-events-none">
                        <div class="w-12 h-12 rounded-full bg-rose-100 text-maroon flex items-center justify-center mx-auto">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <p class="text-xs font-semibold text-stone-700">Click to browse or drop up to 10 saree photos</p>
                        <p class="text-[11px] text-stone-400">JPEG, PNG, WebP up to 25MB. Automatically cropped to 4:5 luxury ratio and converted to 400/900/1600px WebP.</p>
                    </div>
                </div>

                <!-- Instant Client-Side Previews -->
                <template x-if="previews.length > 0">
                    <div class="grid grid-cols-3 sm:grid-cols-5 md:grid-cols-6 gap-3 mt-4">
                        <template x-for="(preview, index) in previews" :key="index">
                            <div class="relative group aspect-[4/5] rounded-lg overflow-hidden border-2"
                                 :class="index === 0 ? 'border-maroon ring-2 ring-rose-200' : 'border-rose-100'">
                                <img :src="preview.url" class="w-full h-full object-cover">
                                
                                <template x-if="index === 0">
                                    <span class="absolute top-1 left-1 bg-maroon text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full uppercase tracking-wider">
                                        Primary
                                    </span>
                                </template>

                                <button type="button"
                                        @click="removePhoto(index)"
                                        class="absolute top-1 right-1 w-5 h-5 bg-black/70 hover:bg-rose-700 text-white rounded-full flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity">
                                    &times;
                                </button>
                            </div>
                        </template>
                    </div>
                </template>

                @error('photos') <span class="text-rose-600 text-xs block mt-1">{{ $message }}</span> @enderror
                @error('photos.*') <span class="text-rose-600 text-xs block mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Main Product Information Grid -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                <!-- Auto-generated Next SKU (Span 3) -->
                <div class="md:col-span-3">
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1">
                        SKU Code <span class="text-rose-600">*</span>
                    </label>
                    <div class="relative">
                        <input type="text"
                               name="sku"
                               value="{{ old('sku', $nextSku) }}"
                               required
                               class="w-full text-xs font-mono font-bold px-3 py-2.5 bg-brand-pearl border border-rose-200 rounded-lg text-stone-900 focus:outline-hidden focus:border-maroon">
                        <span class="absolute right-2.5 top-2.5 text-[10px] text-emerald-700 font-semibold bg-emerald-100 px-1.5 py-0.5 rounded">Next</span>
                    </div>
                    @error('sku') <span class="text-rose-600 text-xs block mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Saree Name (Span 6) -->
                <div class="md:col-span-6">
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1">
                        Saree Name / Title <span class="text-rose-600">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           placeholder="e.g. Peacock Blue Pure Kanjeevaram Bridal Silk Saree"
                           class="w-full text-xs px-3 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
                    @error('name') <span class="text-rose-600 text-xs block mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Primary Colour (Span 3) -->
                <div class="md:col-span-3">
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1">
                        Primary Colour
                    </label>
                    <input type="text"
                           name="colour"
                           value="{{ old('colour') }}"
                           placeholder="e.g. Crimson Maroon"
                           class="w-full text-xs px-3 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
                </div>
            </div>

            <!-- Pricing & Stock Row -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <!-- Price -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1">
                        Price (₹) <span class="text-[10px] text-stone-400 font-normal lowercase">(null = on request)</span>
                    </label>
                    <input type="number"
                           name="price"
                           step="0.01"
                           value="{{ old('price') }}"
                           placeholder="e.g. 42500"
                           class="w-full text-xs px-3 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
                </div>

                <!-- Discount Price -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1">
                        Discount Price (₹)
                    </label>
                    <input type="number"
                           name="discount_price"
                           step="0.01"
                           value="{{ old('discount_price') }}"
                           placeholder="e.g. 38500"
                           class="w-full text-xs px-3 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
                </div>

                <!-- Stock Quantity -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1">
                        Stock Quantity <span class="text-[10px] text-stone-400 font-normal lowercase">(null = single piece)</span>
                    </label>
                    <input type="number"
                           name="stock_quantity"
                           value="{{ old('stock_quantity', 1) }}"
                           placeholder="1"
                           class="w-full text-xs px-3 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
                </div>

                <!-- Price Note -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1">
                        Price Note
                    </label>
                    <input type="text"
                           name="price_note"
                           value="{{ old('price_note') }}"
                           placeholder="e.g. Indicative Price"
                           class="w-full text-xs px-3 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
                </div>
            </div>

            <!-- Category Checkboxes Organized by Group -->
            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">
                    Assign Categories & Collections <span class="text-rose-600">*</span>
                </label>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 p-4 rounded-xl bg-brand-pearl border border-rose-100 max-h-56 overflow-y-auto">
                    @foreach($categories as $groupName => $cats)
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-maroon border-b border-rose-200 pb-1 mb-2">
                                {{ ucfirst($groupName) }}
                            </p>
                            <div class="space-y-1.5 text-xs">
                                @foreach($cats as $cat)
                                    <label class="flex items-center gap-2 text-stone-700 cursor-pointer">
                                        <input type="checkbox"
                                               name="categories[]"
                                               value="{{ $cat->id }}"
                                               {{ in_array($cat->id, old('categories', [])) ? 'checked' : '' }}
                                               class="rounded text-maroon focus:ring-maroon">
                                        <span class="truncate">{{ $cat->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('categories') <span class="text-rose-600 text-xs block mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Short Description -->
            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1">
                    Short Description
                </label>
                <textarea name="short_description" rows="2" class="w-full text-xs px-3.5 py-2 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">{{ old('short_description', 'Handwoven pure silk saree from our Peelamedu, Coimbatore flagship.') }}</textarea>
            </div>

            <!-- Publish Toggle & Submit Action -->
            <div class="flex flex-col sm:flex-row items-center justify-between pt-6 border-t border-rose-100 gap-4">
                <div class="flex items-center gap-3">
                    <input type="hidden" name="is_published" :value="isPublished ? 1 : 0">
                    <button type="button"
                            @click="isPublished = !isPublished"
                            class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-hidden"
                            :class="isPublished ? 'bg-maroon' : 'bg-stone-300'">
                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"
                              :class="isPublished ? 'translate-x-5' : 'translate-x-0'"></span>
                    </button>
                    <div>
                        <p class="text-xs font-bold text-stone-900" x-text="isPublished ? 'Publish Immediately (Active on Storefront)' : 'Save as Draft (Internal Only)'"></p>
                        <p class="text-[10px] text-stone-500">Newly published sarees appear automatically in New Arrivals and Category pages.</p>
                    </div>
                </div>

                <button type="submit"
                        class="btn-pill btn-maroon px-10 py-3.5 text-xs font-semibold tracking-wider uppercase shadow-md hover:shadow-lg transition-transform active:scale-95 flex items-center gap-2">
                    <span>Save & List Saree</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
