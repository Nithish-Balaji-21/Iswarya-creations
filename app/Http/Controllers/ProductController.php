<?php

namespace App\Http\Controllers;

use App\Enums\CategoryGroup;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display category listing page /{group}/{slug}.
     */
    public function category(Request $request, string $group, string $slug): View
    {
        $categoryGroup = CategoryGroup::tryFrom($group);
        if (! $categoryGroup) {
            abort(404);
        }

        $category = Category::where('group', $categoryGroup->value)
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Base query for products in this category
        $query = $category->products()
            ->published()
            ->with(['images', 'primaryImage', 'categories']);

        // Filter: Price Range
        if ($request->filled('min_price')) {
            $query->where(function ($q) use ($request) {
                $min = (float) $request->min_price;
                $q->where('discount_price', '>=', $min)
                    ->orWhere(function ($sub) use ($min) {
                        $sub->whereNull('discount_price')->where('price', '>=', $min);
                    });
            });
        }
        if ($request->filled('max_price')) {
            $query->where(function ($q) use ($request) {
                $max = (float) $request->max_price;
                $q->where('discount_price', '<=', $max)
                    ->orWhere(function ($sub) use ($max) {
                        $sub->whereNull('discount_price')->where('price', '<=', $max);
                    });
            });
        }

        // Filter: Colour
        if ($request->filled('colour')) {
            $colours = is_array($request->colour) ? $request->colour : [$request->colour];
            $query->whereIn('colour', $colours);
        }

        // Filter: Availability
        if ($request->filled('availability')) {
            $query->where('availability', $request->availability);
        }

        // Filter: Secondary category filters (e.g. weave or occasion filter within a fabric category)
        if ($request->filled('filter_category')) {
            $filterCatIds = is_array($request->filter_category) ? $request->filter_category : [$request->filter_category];
            $query->whereHas('categories', function ($catQuery) use ($filterCatIds) {
                $catQuery->whereIn('categories.id', $filterCatIds);
            });
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'price_asc' => $query->orderByRaw('COALESCE(discount_price, price) ASC'),
            'price_desc' => $query->orderByRaw('COALESCE(discount_price, price) DESC'),
            'popularity' => $query->orderBy('view_count', 'desc'),
            default => $query->orderBy('published_at', 'desc')->orderBy('id', 'desc'),
        };

        $perPage = (int) config('iswarya.per_page', 24);
        $products = $query->paginate($perPage)->withQueryString();

        // Extract available filters for this category
        $availableColours = Product::published()
            ->whereHas('categories', fn ($q) => $q->where('categories.id', $category->id))
            ->whereNotNull('colour')
            ->distinct()
            ->pluck('colour');

        // Sibling & cross categories for secondary filtering
        $crossCategories = Category::active()
            ->where('id', '!=', $category->id)
            ->ordered()
            ->get()
            ->groupBy('group.value');

        return view('products.category', compact(
            'category',
            'products',
            'availableColours',
            'crossCategories',
            'sort'
        ));
    }

    /**
     * Display New Arrivals collection page.
     */
    public function newArrivals(Request $request): View
    {
        $query = Product::newArrivals(config('iswarya.new_arrivals_days', 30))
            ->with(['images', 'primaryImage', 'categories']);

        if ($request->filled('colour')) {
            $colours = is_array($request->colour) ? $request->colour : [$request->colour];
            $query->whereIn('colour', $colours);
        }

        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'price_asc' => $query->orderByRaw('COALESCE(discount_price, price) ASC'),
            'price_desc' => $query->orderByRaw('COALESCE(discount_price, price) DESC'),
            'popularity' => $query->orderBy('view_count', 'desc'),
            default => $query->orderBy('published_at', 'desc'),
        };

        $products = $query->paginate(config('iswarya.per_page', 24))->withQueryString();

        $availableColours = Product::newArrivals(30)
            ->whereNotNull('colour')
            ->distinct()
            ->pluck('colour');

        return view('products.new-arrivals', compact('products', 'availableColours', 'sort'));
    }

    /**
     * Display product detail page /product/{slug}.
     */
    public function show(Request $request, string $slug): View
    {
        $product = Product::where('slug', $slug)
            ->with(['images', 'categories'])
            ->firstOrFail();

        // Throttle view count increment per session
        $viewedKey = "viewed_product_{$product->id}";
        if (! $request->session()->has($viewedKey)) {
            $product->increment('view_count');
            $request->session()->put($viewedKey, true);
        }

        // Related sarees from same category
        $primaryCategory = $product->categories->first();
        $relatedProducts = $primaryCategory
            ? $primaryCategory->products()
                ->published()
                ->where('products.id', '!=', $product->id)
                ->with(['images', 'primaryImage'])
                ->take(4)
                ->get()
            : Product::published()
                ->where('id', '!=', $product->id)
                ->take(4)
                ->get();

        return view('products.show', compact('product', 'relatedProducts', 'primaryCategory'));
    }
}
