@extends('layouts.admin')

@section('title', 'Edit Banner Slide – Iswarya Silks Admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between pb-6 border-b border-rose-100">
        <div>
            <span class="text-xs uppercase font-bold tracking-widest text-gold">Storefront Carousel</span>
            <h1 class="text-2xl font-bold text-stone-900 font-jost mt-1">Edit Banner Slide</h1>
        </div>

        <a href="{{ route('admin.banners.index') }}" class="btn-pill border border-stone-200 px-4 py-2 text-xs font-semibold text-stone-600 hover:bg-stone-50">
            Back to Banners
        </a>
    </div>

    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-rose-100 shadow-2xs">
        <form method="POST" action="{{ route('admin.banners.update', $banner->id) }}" enctype="multipart/form-data" class="space-y-5 text-xs">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-stone-700 uppercase tracking-wider mb-1">Eyebrow Tag</label>
                    <input type="text" name="eyebrow" value="{{ old('eyebrow', $banner->eyebrow) }}" class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg">
                </div>

                <div>
                    <label class="block font-semibold text-stone-700 uppercase tracking-wider mb-1">Slide Title <span class="text-rose-600">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $banner->title) }}" required class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg">
                </div>
            </div>

            <div>
                <label class="block font-semibold text-stone-700 uppercase tracking-wider mb-1">Subtitle</label>
                <textarea name="subtitle" rows="2" class="w-full text-xs px-3.5 py-2 border border-rose-200 rounded-lg">{{ old('subtitle', $banner->subtitle) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-stone-700 uppercase tracking-wider mb-1">Desktop Image</label>
                    @if($banner->desktop_image)
                        <img src="{{ $banner->desktop_image_url }}" alt="Desktop" class="w-32 aspect-[16/7] rounded object-cover mb-2 border border-rose-100">
                    @endif
                    <input type="file" name="desktop_image" accept="image/*" class="w-full text-xs px-3 py-2 border border-rose-200 rounded-lg bg-white">
                </div>

                <div>
                    <label class="block font-semibold text-stone-700 uppercase tracking-wider mb-1">Mobile Image</label>
                    @if($banner->mobile_image)
                        <img src="{{ $banner->mobile_image_url }}" alt="Mobile" class="w-20 aspect-square rounded object-cover mb-2 border border-rose-100">
                    @endif
                    <input type="file" name="mobile_image" accept="image/*" class="w-full text-xs px-3 py-2 border border-rose-200 rounded-lg bg-white">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block font-semibold text-stone-700 uppercase tracking-wider mb-1">Link URL</label>
                    <input type="text" name="link_url" value="{{ old('link_url', $banner->link_url) }}" class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg">
                </div>

                <div>
                    <label class="block font-semibold text-stone-700 uppercase tracking-wider mb-1">Button Text</label>
                    <input type="text" name="button_text" value="{{ old('button_text', $banner->button_text) }}" class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
                <div>
                    <label class="block font-semibold text-stone-700 uppercase tracking-wider mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order) }}" class="w-full text-xs px-3.5 py-2 border border-rose-200 rounded-lg">
                </div>

                <div class="pt-4">
                    <label class="flex items-center text-stone-700 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $banner->is_active) ? 'checked' : '' }} class="rounded text-maroon focus:ring-maroon">
                        <span class="ml-2 font-semibold">Active in Hero Carousel</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-rose-100">
                <a href="{{ route('admin.banners.index') }}" class="btn-pill border border-stone-200 px-5 py-2 text-stone-600 hover:bg-stone-50">Cancel</a>
                <button type="submit" class="btn-pill btn-maroon px-8 py-2.5 font-semibold tracking-wider uppercase shadow-md">
                    Update Slide
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
