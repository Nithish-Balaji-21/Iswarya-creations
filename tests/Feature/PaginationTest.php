<?php

namespace Tests\Feature;

use App\Enums\CategoryGroup;
use App\Enums\ProductStatus;
use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_page_paginates_products_with_query_string(): void
    {
        $category = Category::factory()->create([
            'slug' => 'all-kanjeevaram',
            'group' => CategoryGroup::Kanjeevaram,
        ]);

        $products = Product::factory()->count(30)->create([
            'status' => ProductStatus::Published,
            'published_at' => now(),
        ]);

        $category->products()->attach($products->pluck('id'));

        $response = $this->get(route('categories.show', [
            'group' => 'kanjeevaram',
            'slug' => 'all-kanjeevaram',
            'page' => 2,
        ]));

        $response->assertStatus(200);
        $paginator = $response->viewData('products');
        $this->assertEquals(2, $paginator->currentPage());
        $this->assertEquals(24, $paginator->perPage());
        $this->assertEquals(30, $paginator->total());
        $this->assertCount(6, $paginator->items());
    }

    public function test_admin_products_index_paginates_inventory(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin, 'must_change_password' => false]);
        Product::factory()->count(25)->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.products.index', ['page' => 2]));

        $response->assertStatus(200);
        $paginator = $response->viewData('products');
        $this->assertEquals(2, $paginator->currentPage());
        $this->assertEquals(20, $paginator->perPage());
        $this->assertEquals(25, $paginator->total());
        $this->assertCount(5, $paginator->items());
    }
}

