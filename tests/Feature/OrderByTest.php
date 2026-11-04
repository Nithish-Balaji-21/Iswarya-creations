<?php

namespace Tests\Feature;

use App\Enums\CategoryGroup;
use App\Enums\ProductAvailability;
use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderByTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_by_with_coalesce_discount_price_and_regular_price(): void
    {
        // Product A: price 50000, discount 30000 -> effective 30000
        $prodA = Product::factory()->create([
            'name' => 'Product A',
            'price' => 50000,
            'discount_price' => 30000,
            'status' => ProductStatus::Published,
            'published_at' => now(),
        ]);

        // Product B: price 20000, no discount -> effective 20000
        $prodB = Product::factory()->create([
            'name' => 'Product B',
            'price' => 20000,
            'discount_price' => null,
            'status' => ProductStatus::Published,
            'published_at' => now(),
        ]);

        // Product C: price 40000, no discount -> effective 40000
        $prodC = Product::factory()->create([
            'name' => 'Product C',
            'price' => 40000,
            'discount_price' => null,
            'status' => ProductStatus::Published,
            'published_at' => now(),
        ]);

        $asc = Product::published()
            ->orderByRaw('COALESCE(discount_price, price) ASC')
            ->pluck('id')
            ->toArray();

        $this->assertEquals([$prodB->id, $prodA->id, $prodC->id], $asc);

        $desc = Product::published()
            ->orderByRaw('COALESCE(discount_price, price) DESC')
            ->pluck('id')
            ->toArray();

        $this->assertEquals([$prodC->id, $prodA->id, $prodB->id], $desc);
    }

    public function test_distinct_query_is_compatible_with_sql_modes_after_reorder(): void
    {
        Product::factory()->count(3)->create([
            'colour' => 'Royal Blue',
            'status' => ProductStatus::Published,
            'published_at' => now(),
        ]);

        Product::factory()->count(2)->create([
            'colour' => 'Crimson Red',
            'status' => ProductStatus::Published,
            'published_at' => now(),
        ]);

        // Test calling distinct on newArrivals scope which originally had an orderBy clause
        $colours = Product::newArrivals(30)
            ->reorder()
            ->whereNotNull('colour')
            ->distinct()
            ->pluck('colour');

        $this->assertCount(2, $colours);
        $this->assertTrue($colours->contains('Royal Blue'));
        $this->assertTrue($colours->contains('Crimson Red'));
    }

    public function test_order_by_after_join_with_unambiguous_columns(): void
    {
        $category = Category::factory()->create(['group' => CategoryGroup::Kanjeevaram]);

        $prod1 = Product::factory()->create(['price' => 10000, 'published_at' => now()->subDays(2)]);
        $prod2 = Product::factory()->create(['price' => 30000, 'published_at' => now()->subDay()]);

        $category->products()->attach([$prod1->id, $prod2->id]);

        $ordered = $category->products()
            ->published()
            ->reorder()
            ->orderBy('products.price', 'desc')
            ->get();

        $this->assertEquals($prod2->id, $ordered->first()->id);
        $this->assertEquals($prod1->id, $ordered->last()->id);
    }
}

