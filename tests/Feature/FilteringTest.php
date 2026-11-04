<?php

namespace Tests\Feature;

use App\Enums\CategoryGroup;
use App\Enums\ProductAvailability;
use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilteringTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_page_filters_by_price_range(): void
    {
        $category = Category::factory()->create([
            'slug' => 'wedding-silks',
            'group' => CategoryGroup::Occasion,
        ]);

        $affordable = Product::factory()->create([
            'name' => 'Budget Silk Saree',
            'price' => 15000,
            'discount_price' => null,
            'status' => ProductStatus::Published,
            'published_at' => now(),
        ]);

        $luxury = Product::factory()->create([
            'name' => 'Royal Heritage Silk Saree',
            'price' => 85000,
            'discount_price' => null,
            'status' => ProductStatus::Published,
            'published_at' => now(),
        ]);

        $category->products()->attach([$affordable->id, $luxury->id]);

        $response = $this->get(route('categories.show', [
            'group' => 'occasion',
            'slug' => 'wedding-silks',
            'min_price' => '10000',
            'max_price' => '30000',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Budget Silk Saree');
        $response->assertDontSee('Royal Heritage Silk Saree');
    }

    public function test_category_page_filters_by_colour(): void
    {
        $category = Category::factory()->create([
            'slug' => 'pure-zari',
            'group' => CategoryGroup::Weave,
        ]);

        $blueSaree = Product::factory()->create([
            'name' => 'Peacock Blue Korvai Saree',
            'colour' => 'Peacock Blue',
            'status' => ProductStatus::Published,
            'published_at' => now(),
        ]);

        $greenSaree = Product::factory()->create([
            'name' => 'Emerald Green Silk Saree',
            'colour' => 'Emerald Green',
            'status' => ProductStatus::Published,
            'published_at' => now(),
        ]);

        $category->products()->attach([$blueSaree->id, $greenSaree->id]);

        $response = $this->get(route('categories.show', [
            'group' => 'weave',
            'slug' => 'pure-zari',
            'colour' => ['Peacock Blue'],
        ]));

        $response->assertStatus(200);
        $response->assertSee('Peacock Blue Korvai Saree');
        $response->assertDontSee('Emerald Green Silk Saree');
    }

    public function test_category_page_filters_by_availability(): void
    {
        $category = Category::factory()->create([
            'slug' => 'traditional-silks',
            'group' => CategoryGroup::Fabric,
        ]);

        $inStock = Product::factory()->create([
            'name' => 'Ready In Showroom Saree',
            'availability' => ProductAvailability::Available,
            'status' => ProductStatus::Published,
            'published_at' => now(),
        ]);

        $soldOut = Product::factory()->create([
            'name' => 'Archived Loom Piece Saree',
            'availability' => ProductAvailability::SoldOut,
            'status' => ProductStatus::Published,
            'published_at' => now(),
        ]);

        $category->products()->attach([$inStock->id, $soldOut->id]);

        $response = $this->get(route('categories.show', [
            'group' => 'fabric',
            'slug' => 'traditional-silks',
            'availability' => 'available',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Ready In Showroom Saree');
        $response->assertDontSee('Archived Loom Piece Saree');
    }
}

