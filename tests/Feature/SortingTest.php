<?php

namespace Tests\Feature;

use App\Enums\CategoryGroup;
use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SortingTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_page_sorts_by_price_ascending(): void
    {
        $category = Category::factory()->create([
            'slug' => 'bridal-silks',
            'group' => CategoryGroup::Kanjeevaram,
        ]);

        $low = Product::factory()->create([
            'name' => 'Low Price Saree',
            'price' => 20000,
            'discount_price' => null,
            'status' => ProductStatus::Published,
            'published_at' => now()->subDays(5),
        ]);

        $high = Product::factory()->create([
            'name' => 'High Price Saree',
            'price' => 70000,
            'discount_price' => null,
            'status' => ProductStatus::Published,
            'published_at' => now()->subDay(),
        ]);

        $category->products()->attach([$low->id, $high->id]);

        $response = $this->get(route('categories.show', [
            'group' => 'kanjeevaram',
            'slug' => 'bridal-silks',
            'sort' => 'price_asc',
        ]));

        $response->assertStatus(200);
        $products = $response->viewData('products');
        $this->assertEquals($low->id, $products->first()->id);
        $this->assertEquals($high->id, $products->last()->id);
    }

    public function test_category_page_sorts_by_price_descending(): void
    {
        $category = Category::factory()->create([
            'slug' => 'bridal-silks',
            'group' => CategoryGroup::Kanjeevaram,
        ]);

        $low = Product::factory()->create([
            'name' => 'Low Price Saree',
            'price' => 20000,
            'discount_price' => null,
            'status' => ProductStatus::Published,
            'published_at' => now()->subDays(5),
        ]);

        $high = Product::factory()->create([
            'name' => 'High Price Saree',
            'price' => 70000,
            'discount_price' => null,
            'status' => ProductStatus::Published,
            'published_at' => now()->subDay(),
        ]);

        $category->products()->attach([$low->id, $high->id]);

        $response = $this->get(route('categories.show', [
            'group' => 'kanjeevaram',
            'slug' => 'bridal-silks',
            'sort' => 'price_desc',
        ]));

        $response->assertStatus(200);
        $products = $response->viewData('products');
        $this->assertEquals($high->id, $products->first()->id);
        $this->assertEquals($low->id, $products->last()->id);
    }

    public function test_new_arrivals_page_sorts_properly(): void
    {
        $olderExpensive = Product::factory()->create([
            'name' => 'Older Expensive Saree',
            'price' => 90000,
            'status' => ProductStatus::Published,
            'published_at' => now()->subDays(10),
        ]);

        $newerAffordable = Product::factory()->create([
            'name' => 'Newer Affordable Saree',
            'price' => 15000,
            'status' => ProductStatus::Published,
            'published_at' => now()->subDays(1),
        ]);

        $response = $this->get(route('products.new-arrivals', ['sort' => 'price_asc']));
        $response->assertStatus(200);

        $products = $response->viewData('products');
        $this->assertEquals($newerAffordable->id, $products->first()->id);
        $this->assertEquals($olderExpensive->id, $products->last()->id);
    }
}

