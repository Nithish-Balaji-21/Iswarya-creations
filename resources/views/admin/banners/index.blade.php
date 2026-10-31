@extends('layouts.admin')

@section('title', 'Manage Hero Banners – Iswarya Silks Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between pb-6 border-b border-rose-100">
        <div>
            <span class="text-xs uppercase font-bold tracking-widest text-gold">Storefront Carousel</span>
            <h1 class="text-3xl font-bold text-stone-900 font-jost mt-1">Hero Banner Slides</h1>
            <p class="text-xs text-stone-500 mt-1">Manage desktop and mobile hero slides, button links, and order.</p>
        </div>

        <a href="{{ route('admin.banners.create') }}" class="btn-pill btn-maroon px-5 py-2.5 text-xs font-semibold tracking-wider uppercase flex items-center gap-1.5 shadow-2xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Add Banner Slide</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-rose-100 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-brand-pearl text-stone-600 uppercase tracking-wider text-[10px] border-b border-rose-100">
                    <tr>
                        <th class="py-3 px-6">Desktop Preview</th>
                        <th class="py-3 px-4">Title & Eyebrow</th>
                        <th class="py-3 px-4">CTA Link</th>
                        <th class="py-3 px-4">Sort Order</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-rose-50">
                    @forelse($banners as $banner)
                        <tr class="hover:bg-rose-50/20">
                            <td class="py-3.5 px-6">
                                <div class="w-32 aspect-[16/7] rounded-lg overflow-hidden border border-rose-100 bg-stone-900">
                                    <img src="{{ $banner->desktop_image_url }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-gold block">{{ $banner->eyebrow }}</span>
                                <p class="font-bold text-stone-900">{{ $banner->title }}</p>
                                <p class="text-[11px] text-stone-500 line-clamp-1 mt-0.5">{{ $banner->subtitle }}</p>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-[11px] text-stone-600">
                                {{ $banner->link_url ?? 'None' }}
                            </td>
                            <td class="py-3.5 px-4 font-mono font-bold text-stone-700">
                                {{ $banner->sort_order }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold {{ $banner->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-stone-100 text-stone-500' }}">
                                    {{ $banner->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.banners.edit', $banner->id) }}" class="p-1.5 text-stone-600 hover:text-maroon rounded hover:bg-rose-50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.banners.destroy', $banner->id) }}" onsubmit="return confirm('Delete this banner slide?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-800 rounded hover:bg-rose-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-xs text-stone-400">
                                No hero banners uploaded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
