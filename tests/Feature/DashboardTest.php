<?php

namespace Tests\Feature;

use App\Enums\EnquiryStatus;
use App\Enums\ProductAvailability;
use App\Enums\ProductStatus;
use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Enquiry;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_displays_accurate_metrics(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin, 'must_change_password' => false]);

        $sampleProduct = Product::factory()->create([
            'status' => ProductStatus::Published,
            'availability' => ProductAvailability::Available,
        ]);

        Product::factory()->count(2)->create([
            'status' => ProductStatus::Published,
            'availability' => ProductAvailability::Available,
        ]);

        Product::factory()->create([
            'status' => ProductStatus::Draft,
            'availability' => ProductAvailability::Available,
        ]);

        Category::factory()->count(4)->create(['is_active' => true]);

        Enquiry::factory()->count(2)->create([
            'product_id' => $sampleProduct->id,
            'status' => EnquiryStatus::New,
        ]);
        Enquiry::factory()->create([
            'product_id' => $sampleProduct->id,
            'status' => EnquiryStatus::Closed,
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('activeSareesCount', 3);
        $response->assertViewHas('newEnquiriesCount', 2);
        $response->assertViewHas('categoriesCount', 4);
        $response->assertSee('Admin Dashboard');
    }

    public function test_customer_dashboard_displays_accurate_metrics(): void
    {
        $customer = User::factory()->create(['role' => UserRole::Customer]);
        $product = Product::factory()->create();

        Wishlist::create([
            'user_id' => $customer->id,
            'product_id' => $product->id,
        ]);

        Enquiry::factory()->count(2)->create([
            'user_id' => $customer->id,
            'product_id' => $product->id,
        ]);

        $response = $this->actingAs($customer, 'web')->get(route('account.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('wishlistCount', 1);
        $response->assertViewHas('enquiriesCount', 2);
        $response->assertSee('Customer Portal');
        $response->assertSee($customer->name);
    }
}
