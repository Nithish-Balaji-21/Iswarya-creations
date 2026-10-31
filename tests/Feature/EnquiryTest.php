<?php

namespace Tests\Feature;

use App\Enums\EnquirySource;
use App\Enums\EnquiryStatus;
use App\Enums\ProductAvailability;
use App\Enums\ProductStatus;
use App\Models\Enquiry;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnquiryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Setting::updateOrCreate(['key' => 'whatsapp_number'], ['value' => '918973948912']);
    }

    public function test_enquiry_logs_server_side_snapshot_and_redirects_to_whatsapp(): void
    {
        $product = Product::factory()->create([
            'name' => 'Peacock Blue Korvai Pure Kanjeevaram Silk Saree',
            'sku' => 'IC-0001',
            'price' => 48500.00,
            'discount_price' => 42500.00,
            'availability' => ProductAvailability::Available,
            'status' => ProductStatus::Published,
        ]);

        $response = $this->post(route('enquiry.redirect', $product), [
            'source' => EnquirySource::ProductPage->value,
            'customer_name' => 'Devi Sri',
            'customer_phone' => '+919876543210',
        ]);

        // 1. Verify enquiry logged in database
        $this->assertDatabaseHas('enquiries', [
            'product_id' => $product->id,
            'product_sku' => 'IC-0001',
            'product_name' => 'Peacock Blue Korvai Pure Kanjeevaram Silk Saree',
            'product_price' => 42500.00, // Snapshots effective discounted price
            'customer_name' => 'Devi Sri',
            'customer_phone' => '+919876543210',
            'source' => EnquirySource::ProductPage->value,
            'status' => EnquiryStatus::New->value,
        ]);

        // 2. Verify WhatsApp URL redirection
        $response->assertRedirect();
        $targetUrl = $response->headers->get('Location');

        $this->assertStringStartsWith('https://wa.me/918973948912?text=', $targetUrl);
        $this->assertStringContainsString('IC-0001', $targetUrl);
        $this->assertStringContainsString('listed%20at%20%E2%82%B942%2C500', $targetUrl); // Indian currency grouping ₹42,500
    }

    public function test_enquiry_omits_listed_at_clause_when_price_is_null(): void
    {
        $product = Product::factory()->create([
            'name' => 'Exclusive Heirloom Pure Gold Zari Saree',
            'sku' => 'IC-9999',
            'price' => null, // Price on request
            'discount_price' => null,
            'availability' => ProductAvailability::OnRequest,
            'status' => ProductStatus::Published,
        ]);

        $response = $this->get(route('enquiry.redirect', $product));

        $response->assertRedirect();
        $targetUrl = $response->headers->get('Location');

        // Message should NOT contain "listed at"
        $this->assertStringNotContainsString('listed%20at', $targetUrl);
        $this->assertStringContainsString('IC-9999', $targetUrl);
        $this->assertStringContainsString('Hello%20Iswarya%20Silks', $targetUrl);

        $this->assertDatabaseHas('enquiries', [
            'product_sku' => 'IC-9999',
            'product_price' => null,
        ]);
    }

    public function test_logged_in_customer_details_are_automatically_copied_to_enquiry(): void
    {
        $user = User::factory()->create([
            'name' => 'Aarthi Krishnan',
            'phone' => '+919123456789',
        ]);

        $product = Product::factory()->create([
            'name' => 'Soft Silk Saree',
            'sku' => 'IC-0005',
            'price' => 25000.00,
        ]);

        $response = $this->actingAs($user, 'web')->post(route('enquiry.redirect', $product));

        $response->assertRedirect();

        $this->assertDatabaseHas('enquiries', [
            'user_id' => $user->id,
            'product_sku' => 'IC-0005',
            'customer_name' => 'Aarthi Krishnan',
            'customer_phone' => '+919123456789',
        ]);
    }
}
