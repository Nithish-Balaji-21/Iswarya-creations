<?php

namespace Tests\Feature;

use App\Enums\CategoryGroup;
use App\Enums\ProductAvailability;
use App\Enums\ProductStatus;
use App\Enums\UserRole;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CrudOperationsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => UserRole::Admin, 'must_change_password' => false]);
    }

    public function test_admin_can_store_product_with_photos_and_categories(): void
    {
        Storage::fake('public');
        $category = Category::factory()->create();

        $photo1 = UploadedFile::fake()->image('saree1.jpg', 800, 1000);
        $photo2 = UploadedFile::fake()->image('saree2.jpg', 800, 1000);

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.products.store'), [
            'sku' => 'IC-0099',
            'name' => 'Royal Peacock Pure Silk Saree',
            'price' => 45000,
            'discount_price' => 41000,
            'colour' => 'Peacock Blue',
            'stock_quantity' => 1,
            'categories' => [$category->id],
            'photos' => [$photo1, $photo2],
            'is_published' => 1,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'sku' => 'IC-0099',
            'name' => 'Royal Peacock Pure Silk Saree',
            'price' => 45000,
            'status' => 'published',
        ]);

        $product = Product::where('sku', 'IC-0099')->first();
        $this->assertNotNull($product);
        $this->assertCount(1, $product->categories);
        $this->assertCount(2, $product->images);

        $primaryImage = $product->primaryImage;
        $this->assertNotNull($primaryImage);
        $this->assertTrue($primaryImage->is_primary);
    }

    public function test_admin_can_update_product(): void
    {
        $product = Product::factory()->create([
            'sku' => 'IC-0050',
            'name' => 'Original Name',
            'price' => 30000,
        ]);

        $response = $this->actingAs($this->admin, 'admin')->put(route('admin.products.update', $product), [
            'sku' => 'IC-0050',
            'name' => 'Updated Korvai Silk Saree',
            'price' => 35000,
            'discount_price' => 32000,
            'availability' => 'available',
            'status' => 'published',
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Korvai Silk Saree',
            'price' => 35000,
            'discount_price' => 32000,
        ]);
    }

    public function test_admin_can_toggle_product_availability(): void
    {
        $product = Product::factory()->create([
            'availability' => ProductAvailability::Available,
        ]);

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.products.toggle-availability', $product));

        $response->assertRedirect();
        $product->refresh();
        $this->assertEquals(ProductAvailability::SoldOut, $product->availability);
    }

    public function test_admin_can_toggle_product_status(): void
    {
        $product = Product::factory()->create([
            'status' => ProductStatus::Published,
        ]);

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.products.toggle-status', $product));

        $response->assertRedirect();
        $product->refresh();
        $this->assertEquals(ProductStatus::Draft, $product->status);
    }

    public function test_admin_can_delete_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.products.destroy', $product));

        $response->assertRedirect(route('admin.products.index'));
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_admin_can_create_update_and_delete_category(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.categories.store'), [
            'name' => 'Royal Banarasi',
            'group' => 'fabric',
            'description' => 'Pure Banarasi weave sarees',
            'sort_order' => 5,
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Royal Banarasi', 'group' => 'fabric']);

        $category = Category::where('name', 'Royal Banarasi')->first();

        // Update
        $this->actingAs($this->admin, 'admin')->put(route('admin.categories.update', $category), [
            'name' => 'Royal Banarasi Silks',
            'group' => 'fabric',
            'description' => 'Updated description',
            'sort_order' => 10,
        ]);

        $this->assertDatabaseHas('categories', ['name' => 'Royal Banarasi Silks', 'sort_order' => 10]);

        // Toggle homepage
        $this->actingAs($this->admin, 'admin')->post(route('admin.categories.toggle-homepage', $category));
        $category->refresh();
        $this->assertTrue($category->show_on_homepage);

        // Delete
        $this->actingAs($this->admin, 'admin')->delete(route('admin.categories.destroy', $category));
        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }

    public function test_admin_can_update_settings(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->put(route('admin.settings.update'), [
            'whatsapp_number' => '919876543210',
            'store_address' => 'Updated Address, Coimbatore',
            'instagram_url' => 'https://instagram.com/iswaryasilks_updated',
            'announcement_text' => 'New Festive Collection Live!',
        ]);

        $response->assertRedirect();
        $this->assertEquals('919876543210', Setting::whatsappNumber());
        $this->assertEquals('New Festive Collection Live!', Setting::announcementText());
    }
}

