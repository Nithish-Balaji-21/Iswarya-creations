<?php

namespace Tests\Feature;

use App\Enums\CategoryGroup;
use App\Enums\ProductAvailability;
use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseQueriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_crud_database_lifecycle_on_product(): void
    {
        $product = Product::create([
            'sku' => 'IC-9999',
            'name' => 'Kanchipuram Silk Brocade',
            'slug' => 'kanchipuram-silk-brocade-ic-9999',
            'price' => 45000.00,
            'discount_price' => 38000.00,
            'availability' => ProductAvailability::Available,
            'status' => ProductStatus::Published,
            'published_at' => now(),
            'colour' => 'Royal Crimson',
        ]);

        $this->assertDatabaseHas('products', ['sku' => 'IC-9999', 'colour' => 'Royal Crimson']);

        $product->update([
            'colour' => 'Deep Maroon',
            'price' => 48000.00,
        ]);

        $this->assertDatabaseHas('products', ['sku' => 'IC-9999', 'colour' => 'Deep Maroon', 'price' => 48000.00]);

        $product->delete();

        $this->assertSoftDeleted('products', ['id' => $product->id]);
        $this->assertEquals(0, Product::where('sku', 'IC-9999')->count());
        $this->assertEquals(1, Product::withTrashed()->where('sku', 'IC-9999')->count());

        $product->restore();
        $this->assertEquals(1, Product::where('sku', 'IC-9999')->count());
    }

    public function test_null_value_handling_in_queries(): void
    {
        Product::factory()->create([
            'discount_price' => null,
            'colour' => null,
            'price' => 30000,
        ]);

        $nullColoursCount = Product::whereNull('colour')->count();
        $this->assertEquals(1, $nullColoursCount);

        $nullDiscountCount = Product::whereNull('discount_price')->count();
        $this->assertEquals(1, $nullDiscountCount);
    }

    public function test_eager_loading_relationships_without_n_plus_one(): void
    {
        $category = Category::factory()->create(['group' => CategoryGroup::Kanjeevaram]);
        $products = Product::factory()->count(3)->create();
        $category->products()->attach($products->pluck('id'));

        $loadedProducts = Product::with(['categories', 'primaryImage', 'images'])->get();

        $this->assertCount(3, $loadedProducts);
        $this->assertTrue($loadedProducts->first()->relationLoaded('categories'));
        $this->assertTrue($loadedProducts->first()->relationLoaded('images'));
    }
}

