@extends('layouts.admin')

@section('title', 'Manage Categories & Collections – Iswarya Silks Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between pb-6 border-b border-rose-100">
        <div>
            <span class="text-xs uppercase font-bold tracking-widest text-gold">Taxonomy & Navigation</span>
            <h1 class="text-3xl font-bold text-stone-900 font-jost mt-1">Categories & Collections</h1>
            <p class="text-xs text-stone-500 mt-1">Classified by group: Weave, Fabric, Occasion, and Kanjeevaram.</p>
        </div>

        <a href="{{ route('admin.categories.create') }}" class="btn-pill btn-maroon px-5 py-2.5 text-xs font-semibold tracking-wider uppercase flex items-center gap-1.5 shadow-2xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Add Category</span>
        </a>
    </div>

    <!-- Grouped Categories Tree -->
    <div class="space-y-8">
        @foreach($categories as $groupName => $items)
            <div class="bg-white rounded-2xl border border-rose-100 shadow-2xs overflow-hidden">
                <div class="bg-brand-pearl px-6 py-3 border-b border-rose-100 flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-widest text-maroon font-jost">
                        Group: {{ ucfirst($groupName) }} ({{ $items->count() }})
                    </span>
                    <span class="text-[10px] text-stone-400">Sort Order Listed</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="text-[10px] text-stone-400 uppercase tracking-wider border-b border-rose-50">
                            <tr>
                                <th class="py-2.5 px-6">Name & Slug</th>
                                <th class="py-2.5 px-4">Parent Category</th>
                                <th class="py-2.5 px-4">Cover / Banner</th>
                                <th class="py-2.5 px-4">Homepage Featured</th>
                                <th class="py-2.5 px-4">Sort Order</th>
                                <th class="py-2.5 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-rose-50">
                            @foreach($items as $cat)
                                <tr class="hover:bg-rose-50/20">
                                    <td class="py-3 px-6">
                                        <p class="font-bold text-stone-900">{{ $cat->name }}</p>
                                        <p class="text-[10px] text-stone-400 font-mono">/{{ $cat->group->value }}/{{ $cat->slug }}</p>
                                    </td>
                                    <td class="py-3 px-4 text-stone-600">
                                        {{ $cat->parent?->name ?? '—' }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2">
                                            @if($cat->cover_image)
                                                <img src="{{ $cat->cover_image_url }}" alt="{{ $cat->name }}" class="w-8 h-8 rounded object-cover border border-rose-100" title="Cover Image">
                                            @endif
                                            @if($cat->banner_image)
                                                <img src="{{ $cat->banner_image_url }}" alt="{{ $cat->name }}" class="w-12 h-6 rounded object-cover border border-rose-100" title="Banner Image">
                                            @endif
                                            @if(!$cat->cover_image && !$cat->banner_image)
                                                <span class="text-stone-300">—</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <form method="POST" action="{{ route('admin.categories.toggle-homepage', $cat->id) }}">
                                            @csrf
                                            <button type="submit" class="btn-pill px-2.5 py-0.5 text-[10px] font-semibold border transition-colors {{ $cat->show_on_homepage ? 'bg-gold-light text-gold-dark border-gold/40' : 'bg-stone-100 text-stone-500 border-stone-200' }}">
                                                {{ $cat->show_on_homepage ? '★ On Homepage' : 'Hidden' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="py-3 px-4 font-mono font-semibold text-stone-600">
                                        {{ $cat->sort_order }}
                                    </td>
                                    <td class="py-3 px-6 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.categories.edit', $cat->id) }}" class="p-1.5 text-stone-600 hover:text-maroon rounded hover:bg-rose-50">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                            <form method="POST" action="{{ route('admin.categories.destroy', $cat->id) }}" onsubmit="return confirm('Delete category {{ $cat->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-800 rounded hover:bg-rose-50">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
