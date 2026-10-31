<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminBannerController extends Controller
{
    public function index(): View
    {
        $banners = Banner::ordered()->get();

        return view('admin.banners.index', compact('banners'));
    }

    public function create(): View
    {
        return view('admin.banners.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'eyebrow' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'link_url' => ['nullable', 'string', 'max:255'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'desktop_image' => ['required', 'image', 'max:12288'],
            'mobile_image' => ['nullable', 'image', 'max:12288'],
        ]);

        $desktopPath = $request->file('desktop_image')->store('banners', 'public');
        $mobilePath = $request->hasFile('mobile_image')
            ? $request->file('mobile_image')->store('banners', 'public')
            : null;

        Banner::create([
            'title' => $validated['title'],
            'eyebrow' => $validated['eyebrow'] ?? null,
            'subtitle' => $validated['subtitle'] ?? null,
            'link_url' => $validated['link_url'] ?? null,
            'button_text' => $validated['button_text'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'desktop_image' => $desktopPath,
            'mobile_image' => $mobilePath,
            'is_active' => true,
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Hero banner slide added.');
    }

    public function edit(Banner $banner): View
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'eyebrow' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'link_url' => ['nullable', 'string', 'max:255'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'desktop_image' => ['nullable', 'image', 'max:12288'],
            'mobile_image' => ['nullable', 'image', 'max:12288'],
        ]);

        if ($request->hasFile('desktop_image')) {
            if ($banner->desktop_image && Storage::disk('public')->exists($banner->desktop_image)) {
                Storage::disk('public')->delete($banner->desktop_image);
            }
            $banner->desktop_image = $request->file('desktop_image')->store('banners', 'public');
        }

        if ($request->hasFile('mobile_image')) {
            if ($banner->mobile_image && Storage::disk('public')->exists($banner->mobile_image)) {
                Storage::disk('public')->delete($banner->mobile_image);
            }
            $banner->mobile_image = $request->file('mobile_image')->store('banners', 'public');
        }

        $banner->title = $validated['title'];
        $banner->eyebrow = $validated['eyebrow'] ?? null;
        $banner->subtitle = $validated['subtitle'] ?? null;
        $banner->link_url = $validated['link_url'] ?? null;
        $banner->button_text = $validated['button_text'] ?? null;
        $banner->sort_order = $validated['sort_order'] ?? 0;
        $banner->is_active = $request->boolean('is_active');
        $banner->save();

        return redirect()->route('admin.banners.index')->with('success', 'Banner slide updated.');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        if ($banner->desktop_image && Storage::disk('public')->exists($banner->desktop_image)) {
            Storage::disk('public')->delete($banner->desktop_image);
        }
        if ($banner->mobile_image && Storage::disk('public')->exists($banner->mobile_image)) {
            Storage::disk('public')->delete($banner->mobile_image);
        }
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner slide removed.');
    }
}
