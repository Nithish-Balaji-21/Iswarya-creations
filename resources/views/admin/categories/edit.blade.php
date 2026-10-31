@extends('layouts.admin')

@section('title', 'Edit Category ' . $category->name . ' – Iswarya Silks Admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between pb-6 border-b border-rose-100">
        <div>
            <span class="text-xs uppercase font-bold tracking-widest text-gold">Edit Category</span>
            <h1 class="text-2xl font-bold text-stone-900 font-jost mt-1">{{ $category->name }}</h1>
        </div>

        <a href="{{ route('admin.categories.index') }}" class="btn-pill border border-stone-200 px-4 py-2 text-xs font-semibold text-stone-600 hover:bg-stone-50">
            Back to Categories
        </a>
    </div>

    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-rose-100 shadow-2xs">
        <form method="POST" action="{{ route('admin.categories.update', $category->id) }}" enctype="multipart/form-data" class="space-y-5 text-xs">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-semibold text-stone-700 uppercase tracking-wider mb-1">Category Name <span class="text-rose-600">*</span></label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-stone-700 uppercase tracking-wider mb-1">Group Classification <span class="text-rose-600">*</span></label>
                    <select name="group" required class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg bg-white">
                        @foreach(\App\Enums\CategoryGroup::cases() as $grp)
                            <option value="{{ $grp->value }}" {{ old('group', $category->group->value) === $grp->value ? 'selected' : '' }}>
                                {{ $grp->label() }} ({{ $grp->value }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-stone-700 uppercase tracking-wider mb-1">Parent Category</label>
                    <select name="parent_id" class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg bg-white">
                        <option value="">None (Top-Level Category)</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-stone-700 uppercase tracking-wider mb-1">Cover Image</label>
                    @if($category->cover_image)
                        <img src="{{ $category->cover_image_url }}" alt="Cover" class="w-16 h-16 rounded object-cover mb-2 border border-rose-100">
                    @endif
                    <input type="file" name="cover_image" accept="image/*" class="w-full text-xs px-3 py-2 border border-rose-200 rounded-lg bg-white">
                </div>

                <div>
                    <label class="block font-semibold text-stone-700 uppercase tracking-wider mb-1">Banner Image</label>
                    @if($category->banner_image)
                        <img src="{{ $category->banner_image_url }}" alt="Banner" class="w-24 h-12 rounded object-cover mb-2 border border-rose-100">
                    @endif
                    <input type="file" name="banner_image" accept="image/*" class="w-full text-xs px-3 py-2 border border-rose-200 rounded-lg bg-white">
                </div>
            </div>

            <div>
                <label class="block font-semibold text-stone-700 uppercase tracking-wider mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full text-xs px-3.5 py-2 border border-rose-200 rounded-lg">{{ old('description', $category->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center pt-2">
                <div>
                    <label class="block font-semibold text-stone-700 uppercase tracking-wider mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" class="w-full text-xs px-3.5 py-2 border border-rose-200 rounded-lg">
                </div>

                <div class="pt-4">
                    <label class="flex items-center text-stone-700 cursor-pointer">
                        <input type="checkbox" name="show_on_homepage" value="1" {{ old('show_on_homepage', $category->show_on_homepage) ? 'checked' : '' }} class="rounded text-maroon focus:ring-maroon">
                        <span class="ml-2 font-semibold">Homepage Featured</span>
                    </label>
                </div>

                <div class="pt-4">
                    <label class="flex items-center text-stone-700 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }} class="rounded text-maroon focus:ring-maroon">
                        <span class="ml-2 font-semibold">Active & Visible</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-rose-100">
                <a href="{{ route('admin.categories.index') }}" class="btn-pill border border-stone-200 px-5 py-2 text-stone-600 hover:bg-stone-50">Cancel</a>
                <button type="submit" class="btn-pill btn-maroon px-8 py-2.5 font-semibold tracking-wider uppercase shadow-md">
                    Update Category
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
