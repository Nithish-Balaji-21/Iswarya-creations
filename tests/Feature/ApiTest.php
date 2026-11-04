<?php

namespace Tests\Feature;

use App\Enums\ProductAvailability;
use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_search_returns_autocomplete_results(): void
    {
        Product::factory()->create([
            'name' => 'Royal Korvai Silk Saree',
            'sku' => 'IC-0099',
            'colour' => 'Deep Violet',
            'status' => ProductStatus::Published,
            'availability' => ProductAvailability::Available,
        ]);

        Product::factory()->create([
            'name' => 'Banarasi Georgette Saree',
            'sku' => 'IC-0100',
            'colour' => 'Yellow Gold',
            'status' => ProductStatus::Published,
            'availability' => ProductAvailability::Available,
        ]);

        // Short query returns empty
        $response = $this->getJson(route('api.search', ['q' => 'k']));
        $response->assertStatus(200);
        $response->assertExactJson([]);

        // Search by name
        $response = $this->getJson(route('api.search', ['q' => 'Korvai']));
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['sku' => 'IC-0099']);

        // Search by colour
        $response = $this->getJson(route('api.search', ['q' => 'Violet']));
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['name' => 'Royal Korvai Silk Saree']);
    }

    public function test_api_products_show_returns_product_details_json(): void
    {
        $product = Product::factory()->create([
            'slug' => 'heritage-kanjeevaram-saree',
            'name' => 'Heritage Kanjeevaram Saree',
            'sku' => 'IC-0555',
            'price' => 60000,
            'status' => ProductStatus::Published,
        ]);

        $response = $this->getJson(route('api.products.show', $product->slug));

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $product->id,
            'name' => 'Heritage Kanjeevaram Saree',
            'sku' => 'IC-0555',
        ]);
    }
}

