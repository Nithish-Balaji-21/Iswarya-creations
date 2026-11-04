<?php

namespace Tests\Feature;

use App\Enums\CategoryGroup;
use App\Enums\EnquirySource;
use App\Enums\ProductAvailability;
use App\Enums\ProductStatus;
use App\Enums\VideoAppointmentStatus;
use App\Models\Category;
use App\Models\Enquiry;
use App\Models\Product;
use App\Models\User;
use App\Models\VideoAppointment;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_customer_shopping_and_enquiry_journey(): void
    {
        // 1. Visit Home
        $homeRes = $this->get(route('home'));
        $homeRes->assertStatus(200);

        // 2. Browse Category
        $category = Category::factory()->create([
            'name' => 'Bridal Kanjeevaram',
            'slug' => 'bridal-kanjeevaram',
            'group' => CategoryGroup::Kanjeevaram,
            'is_active' => true,
        ]);

        $product = Product::factory()->create([
            'name' => 'Muhurtham Pure Silk Brocade',
            'slug' => 'muhurtham-pure-silk-brocade-ic-0001',
            'sku' => 'IC-0001',
            'price' => 52000,
            'discount_price' => 48000,
            'availability' => ProductAvailability::Available,
            'status' => ProductStatus::Published,
            'published_at' => now(),
        ]);
        $category->products()->attach($product->id);

        $catRes = $this->get(route('categories.show', [
            'group' => 'kanjeevaram',
            'slug' => 'bridal-kanjeevaram',
        ]));
        $catRes->assertStatus(200);
        $catRes->assertSee('Muhurtham Pure Silk Brocade');

        // 3. View Product Detail Page
        $pdpRes = $this->get(route('products.show', $product->slug));
        $pdpRes->assertStatus(200);
        $pdpRes->assertSee('Muhurtham Pure Silk Brocade');
        $pdpRes->assertSee('IC-0001');

        // 4. Submit WhatsApp Enquiry
        $enquiryRes = $this->post(route('enquiry.redirect', $product), [
            'customer_name' => 'Soundarya Raj',
            'customer_phone' => '+919876543210',
            'source' => EnquirySource::ProductPage->value,
        ]);

        $enquiryRes->assertRedirect();
        $this->assertStringContainsString('wa.me', $enquiryRes->headers->get('Location'));
        $this->assertStringContainsString('IC-0001', $enquiryRes->headers->get('Location'));

        $this->assertDatabaseHas('enquiries', [
            'product_id' => $product->id,
            'product_sku' => 'IC-0001',
            'customer_name' => 'Soundarya Raj',
            'product_price' => 48000.00, // snapshot discounted price
        ]);

        // 5. Customer Authentication & Wishlist
        $customer = User::factory()->create();
        $this->actingAs($customer, 'web');

        $wishlistRes = $this->post(route('account.wishlist.toggle', $product->id));
        $wishlistRes->assertRedirect();

        $this->assertDatabaseHas('wishlists', [
            'user_id' => $customer->id,
            'product_id' => $product->id,
        ]);

        $wishlistPageRes = $this->get(route('account.wishlist'));
        $wishlistPageRes->assertStatus(200);
        $wishlistPageRes->assertSee('Muhurtham Pure Silk Brocade');

        // 6. Book Video Shopping Consultation
        $consultRes = $this->post(route('video-shopping.store'), [
            'name' => 'Soundarya Raj',
            'phone' => '+919876543210',
            'preferred_date' => now()->addDays(2)->toDateString(),
            'preferred_time' => '11:00 AM - 12:00 PM',
            'note' => 'Looking for bridal Muhurtham silks in red and gold',
        ]);

        $consultRes->assertRedirect();
        $this->assertStringContainsString('wa.me', $consultRes->headers->get('Location'));

        $this->assertDatabaseHas('video_appointments', [
            'name' => 'Soundarya Raj',
            'phone' => '+919876543210',
            'status' => VideoAppointmentStatus::Pending->value,
        ]);
    }
}

