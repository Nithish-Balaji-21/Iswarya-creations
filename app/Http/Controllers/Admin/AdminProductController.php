<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProductAvailability;
use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\Image\ImageOptimizationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminProductController extends Controller
{
    public function __construct(
        protected ImageOptimizationService $imageService
    ) {}

    /**
     * Product inventory table with filters and instant toggles.
     */
    public function index(Request $request): View
    {
        $query = Product::with(['primaryImage', 'images', 'categories'])
            ->latest('id');

        // Search by SKU, Name, Colour
        if ($request->filled('q')) {
            $term = trim($request->q);
            $query->where(function ($q) use ($term) {
                $q->where('sku', 'like', "%{$term}%")
                    ->orWhere('name', 'like', "%{$term}%")
                    ->orWhere('colour', 'like', "%{$term}%");
            });
        }

        // Filter by Category
        if ($request->filled('category_id')) {
            $query->whereHas('categories', fn ($cat) => $cat->where('categories.id', $request->category_id));
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Availability
        if ($request->filled('availability')) {
            $query->where('availability', $request->availability);
        }

        $products = $query->paginate(20)->withQueryString();
        $categories = Category::active()->ordered()->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show Quick Add page.
     */
    public function create(): View
    {
        $categories = Category::active()->ordered()->get()->groupBy('group.value');
        $nextSku = Product::generateNextSku();

        return view('admin.products.create', compact('categories', 'nextSku'));
    }

    /**
     * Store newly created saree and process boutique WebP images.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'price_note' => ['nullable', 'string', 'max:255'],
            'colour' => ['nullable', 'string', 'max:100'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['nullable', 'boolean'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],
            'photos' => ['required', 'array', 'min:1', 'max:10'],
            'photos.*' => ['image', 'max:25600'],
        ], [
            'photos.required' => 'Please upload at least one photo of the saree.',
            'photos.max' => 'You can upload up to 10 photos per saree.',
            'categories.required' => 'Please select at least one category/collection.',
        ]);

        $isPublished = $request->boolean('is_published', true);
        $status = $isPublished ? ProductStatus::Published : ProductStatus::Draft;

        $product = Product::create([
            'sku' => $validated['sku'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']).'-'.strtolower($validated['sku']),
            'short_description' => ! empty($validated['short_description'])
                ? $validated['short_description']
                : 'Handwoven pure silk saree from our Peelamedu, Coimbatore flagship.',
            'description' => ! empty($validated['description'])
                ? $validated['description']
                : '<p>Crafted by master weavers using certified pure mulberry silk and tested zari. Direct handloom quality.</p>',
            'colour' => $validated['colour'] ?? null,
            'price' => isset($validated['price']) && $validated['price'] !== '' ? (float) $validated['price'] : null,
            'discount_price' => isset($validated['discount_price']) && $validated['discount_price'] !== '' ? (float) $validated['discount_price'] : null,
            'price_note' => $validated['price_note'] ?? null,
            'availability' => ProductAvailability::Available,
            'stock_quantity' => $validated['stock_quantity'] ?? null,
            'status' => $status,
            'published_at' => $isPublished ? now() : null,
            'specs' => [
                'zari_type' => 'Tested Half-Fine Gold Zari',
                'blouse_piece' => 'Included (Matching unstitched)',
                'care' => 'Dry Clean Only',
                'weave_technique' => 'Handloom Weave',
                'origin' => 'Peelamedu, Coimbatore',
            ],
        ]);

        $product->categories()->sync($validated['categories']);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $variants = $this->imageService->process($photo, 'products');

                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $variants['large'],
                    'medium_path' => $variants['medium'],
                    'thumb_path' => $variants['thumb'],
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', "Saree {$product->sku} ({$product->name}) has been saved and published!");
    }

    /**
     * Show Edit Product page.
     */
    public function edit(Product $product): View
    {
        $product->load(['images', 'categories']);
        $categories = Category::active()->ordered()->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update product details.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku,'.$product->id],
            'price' => ['nullable', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'price_note' => ['nullable', 'string', 'max:255'],
            'colour' => ['nullable', 'string', 'max:100'],
            'availability' => ['required', 'string'],
            'status' => ['required', 'string'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['exists:categories,id'],
            'photos.*' => ['nullable', 'image', 'max:12288'],
        ]);

        $status = ProductStatus::from($validated['status']);
        $wasDraft = $product->status === ProductStatus::Draft;

        $product->update([
            'name' => $validated['name'],
            'sku' => $validated['sku'],
            'slug' => Str::slug($validated['name']).'-'.strtolower($validated['sku']),
            'price' => $validated['price'] ?? null,
            'discount_price' => $validated['discount_price'] ?? null,
            'price_note' => $validated['price_note'] ?? null,
            'colour' => $validated['colour'] ?? null,
            'availability' => ProductAvailability::from($validated['availability']),
            'status' => $status,
            'stock_quantity' => $validated['stock_quantity'] ?? null,
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'published_at' => ($status === ProductStatus::Published && (! $product->published_at || $wasDraft)) ? now() : $product->published_at,
        ]);

        // Sync categories
        $product->categories()->sync($validated['categories'] ?? []);

        // Process any additional uploaded photos
        if ($request->hasFile('photos')) {
            $currentCount = $product->images()->count();
            foreach ($request->file('photos') as $idx => $photo) {
                $variants = $this->imageService->process($photo, 'products');
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $variants['large'],
                    'medium_path' => $variants['medium'],
                    'thumb_path' => $variants['thumb'],
                    'is_primary' => $currentCount === 0 && $idx === 0,
                    'sort_order' => $currentCount + $idx,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', "Product {$product->sku} updated successfully.");
    }

    /**
     * Inline One-Tap Toggle: Available <-> Sold Out
     */
    public function toggleAvailability(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        $newStatus = $product->availability === ProductAvailability::Available
            ? ProductAvailability::SoldOut
            : ProductAvailability::Available;

        $product->update(['availability' => $newStatus]);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'availability' => $newStatus->value,
                'label' => $newStatus->label(),
            ]);
        }

        return back()->with('success', "Availability for {$product->sku} updated to {$newStatus->label()}.");
    }

    /**
     * Inline One-Tap Toggle: Published <-> Draft
     */
    public function toggleStatus(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        $newStatus = $product->status === ProductStatus::Published
            ? ProductStatus::Draft
            : ProductStatus::Published;

        $product->update([
            'status' => $newStatus,
            'published_at' => $newStatus === ProductStatus::Published ? ($product->published_at ?? now()) : $product->published_at,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'product_status' => $newStatus->value,
                'label' => $newStatus->label(),
            ]);
        }

        return back()->with('success', "Status for {$product->sku} updated to {$newStatus->label()}.");
    }

    /**
     * Set Primary Image.
     */
    public function setPrimaryImage(Product $product, ProductImage $image): RedirectResponse
    {
        $product->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        return back()->with('success', 'Primary cover photo updated.');
    }

    /**
     * Delete Image and variants.
     */
    public function deleteImage(Product $product, ProductImage $image): RedirectResponse
    {
        $isPrimary = $image->is_primary;
        $image->delete(); // booted listener deletes files

        if ($isPrimary) {
            $next = $product->images()->first();
            $next?->update(['is_primary' => true]);
        }

        return back()->with('success', 'Image removed.');
    }

    /**
     * Delete product.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $sku = $product->sku;
        // Delete all image records which triggers file cleanup
        foreach ($product->images as $img) {
            $img->delete();
        }
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', "Product {$sku} removed from catalog.");
    }
}
