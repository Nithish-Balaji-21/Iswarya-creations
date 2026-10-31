<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CategoryGroup;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminCategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::with('parent')
            ->ordered()
            ->get()
            ->groupBy('group.value');

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        $parentCategories = Category::active()->whereNull('parent_id')->ordered()->get();

        return view('admin.categories.create', compact('parentCategories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'group' => ['required', 'string'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'show_on_homepage' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'cover_image' => ['nullable', 'image', 'max:10240'],
            'banner_image' => ['nullable', 'image', 'max:10240'],
        ]);

        $group = CategoryGroup::from($validated['group']);
        $slug = Str::slug($validated['name']);

        // Ensure unique slug
        $baseSlug = $slug;
        $counter = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('categories', 'public');
        }

        $bannerPath = null;
        if ($request->hasFile('banner_image')) {
            $bannerPath = $request->file('banner_image')->store('categories', 'public');
        }

        Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'group' => $group,
            'parent_id' => $validated['parent_id'] ?? null,
            'description' => $validated['description'] ?? null,
            'show_on_homepage' => $request->boolean('show_on_homepage'),
            'sort_order' => $validated['sort_order'] ?? 0,
            'cover_image' => $coverPath,
            'banner_image' => $bannerPath,
            'is_active' => true,
        ]);

        return redirect()->route('admin.categories.index')->with('success', "Category '{$validated['name']}' created successfully.");
    }

    public function edit(Category $category): View
    {
        $parentCategories = Category::active()
            ->whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->ordered()
            ->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'group' => ['required', 'string'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'show_on_homepage' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'cover_image' => ['nullable', 'image', 'max:10240'],
            'banner_image' => ['nullable', 'image', 'max:10240'],
        ]);

        if ($request->hasFile('cover_image')) {
            if ($category->cover_image && Storage::disk('public')->exists($category->cover_image)) {
                Storage::disk('public')->delete($category->cover_image);
            }
            $category->cover_image = $request->file('cover_image')->store('categories', 'public');
        }

        if ($request->hasFile('banner_image')) {
            if ($category->banner_image && Storage::disk('public')->exists($category->banner_image)) {
                Storage::disk('public')->delete($category->banner_image);
            }
            $category->banner_image = $request->file('banner_image')->store('categories', 'public');
        }

        $category->name = $validated['name'];
        $category->group = CategoryGroup::from($validated['group']);
        $category->parent_id = $validated['parent_id'] ?? null;
        $category->description = $validated['description'] ?? null;
        $category->show_on_homepage = $request->boolean('show_on_homepage');
        $category->sort_order = $validated['sort_order'] ?? 0;
        $category->is_active = $request->boolean('is_active');
        $category->save();

        return redirect()->route('admin.categories.index')->with('success', "Category '{$category->name}' updated.");
    }

    public function toggleHomepage(Category $category): RedirectResponse
    {
        $category->update(['show_on_homepage' => ! $category->show_on_homepage]);

        return back()->with('success', "Homepage visibility toggled for {$category->name}.");
    }

    public function destroy(Category $category): RedirectResponse
    {
        $name = $category->name;
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', "Category '{$name}' deleted.");
    }
}
