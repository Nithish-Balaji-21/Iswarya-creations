<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    /**
     * Search products for live autocomplete (top 6 results).
     */
    public function search(Request $request): JsonResponse
    {
        $term = trim($request->input('q', ''));

        if (strlen($term) < 2) {
            return response()->json([]);
        }

        $products = Product::published()
            ->where(function ($query) use ($term) {
                $query->where('name', 'like', "%{$term}%")
                    ->orWhere('sku', 'like', "%{$term}%")
                    ->orWhere('colour', 'like', "%{$term}%")
                    ->orWhere('short_description', 'like', "%{$term}%");
            })
            ->with(['primaryImage', 'categories'])
            ->take(6)
            ->get();

        $results = $products->map(function (Product $product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'slug' => $product->slug,
                'url' => route('products.show', $product->slug),
                'price' => $product->formatted_price,
                'discount_price' => $product->formatted_discount_price,
                'has_discount' => $product->has_discount,
                'thumbnail' => $product->primary_image_url,
                'category' => $product->categories->first()?->name,
                'availability' => $product->availability->label(),
                'is_sold_out' => $product->is_sold_out,
            ];
        });

        return response()->json($results);
    }

    public function show(Product $product): JsonResponse
    {
        $product->load(['images', 'categories']);

        return response()->json([
            'id' => $product->id,
            'sku' => $product->sku,
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => $product->formatted_price,
            'discount_price' => $product->formatted_discount_price,
            'availability' => $product->availability->value,
            'specs' => $product->specs,
            'images' => $product->images->map(fn ($img) => [
                'large' => $img->large_url,
                'medium' => $img->medium_url,
                'thumb' => $img->thumb_url,
            ]),
        ]);
    }
}
