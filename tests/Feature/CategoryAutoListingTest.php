<?php

namespace Tests\Feature;

use App\Enums\CategoryGroup;
use App\Enums\ProductAvailability;
use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryAutoListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_newly_created_published_product_automatically_appears_on_its_category_page(): void
    {
        $category = Category::factory()->create([
            'name' => 'Bridal Kanjeevaram',
            'slug' => 'bridal-kanjeevaram',
            'group' => CategoryGroup::Kanjeevaram,
            'is_active' => true,
        ]);

        // Saree published today
        $product = Product::factory()->create([
            'name' => 'Crimson Muhurtham Korvai Saree',
            'sku' => 'IC-1001',
            'price' => 55000.00,
            'availability' => ProductAvailability::Available,
            'status' => ProductStatus::Published,
            'published_at' => now(),
        ]);

        $category->products()->attach($product->id);

        $response = $this->get(route('categories.show', [
            'group' => 'kanjeevaram',
            'slug' => 'bridal-kanjeevaram',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Crimson Muhurtham Korvai Saree');
        $response->assertSee('IC-1001');
    }

    public function test_draft_product_does_not_appear_on_public_category_page(): void
    {
        $category = Category::factory()->create([
            'name' => 'Soft Silk',
            'slug' => 'soft-silk',
            'group' => CategoryGroup::Fabric,
            'is_active' => true,
        ]);

        $draftProduct = Product::factory()->create([
            'name' => 'Unfinished Draft Saree',
            'sku' => 'IC-DRAFT-1',
            'status' => ProductStatus::Draft,
        ]);

        $category->products()->attach($draftProduct->id);

        $response = $this->get(route('categories.show', [
            'group' => 'fabric',
            'slug' => 'soft-silk',
        ]));

        $response->assertStatus(200);
        $response->assertDontSee('Unfinished Draft Saree');
        $response->assertDontSee('IC-DRAFT-1');
    }

    public function test_saree_linked_to_multiple_categories_appears_on_all_relevant_pages(): void
    {
        $weaveCategory = Category::factory()->create([
            'name' => 'Korvai Handloom',
            'slug' => 'korvai-handloom',
            'group' => CategoryGroup::Weave,
            'is_active' => true,
        ]);

        $occasionCategory = Category::factory()->create([
            'name' => 'Muhurtham Wedding',
            'slug' => 'muhurtham-wedding',
            'group' => CategoryGroup::Occasion,
            'is_active' => true,
        ]);

        $saree = Product::factory()->create([
            'name' => 'Royal Purple Korvai Saree',
            'sku' => 'IC-7777',
            'status' => ProductStatus::Published,
            'published_at' => now(),
        ]);

        $saree->categories()->sync([$weaveCategory->id, $occasionCategory->id]);

        // Check weave category page
        $res1 = $this->get(route('categories.show', ['group' => 'weave', 'slug' => 'korvai-handloom']));
        $res1->assertStatus(200);
        $res1->assertSee('Royal Purple Korvai Saree');

        // Check occasion category page
        $res2 = $this->get(route('categories.show', ['group' => 'occasion', 'slug' => 'muhurtham-wedding']));
        $res2->assertStatus(200);
        $res2->assertSee('Royal Purple Korvai Saree');
    }
}
