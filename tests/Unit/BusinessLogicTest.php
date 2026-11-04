<?php

namespace Tests\Unit;

use App\Enums\OtpPurpose;
use App\Enums\UserRole;
use App\Models\Enquiry;
use App\Models\OtpCode;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BusinessLogicTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_generate_next_sku_produces_sequential_skus(): void
    {
        $firstSku = Product::generateNextSku();
        $this->assertEquals('IC-0001', $firstSku);

        Product::factory()->create(['sku' => 'IC-0001']);

        $secondSku = Product::generateNextSku();
        $this->assertEquals('IC-0002', $secondSku);

        Product::factory()->create(['sku' => 'IC-0002']);

        $thirdSku = Product::generateNextSku();
        $this->assertEquals('IC-0003', $thirdSku);
    }

    public function test_setting_get_and_set_with_defaults(): void
    {
        $this->assertEquals('918973948912', Setting::whatsappNumber());

        Setting::set('whatsapp_number', '919876543210');
        $this->assertEquals('919876543210', Setting::whatsappNumber());

        Setting::set('custom_key', 'custom_value');
        $this->assertEquals('custom_value', Setting::get('custom_key'));
    }

    public function test_user_roles_and_lockout_logic(): void
    {
        $admin = new User(['role' => UserRole::Admin]);
        $customer = new User(['role' => UserRole::Customer]);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($admin->isCustomer());
        $this->assertTrue($customer->isCustomer());
        $this->assertFalse($customer->isAdmin());

        $user = User::factory()->create([
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ]);

        $this->assertFalse($user->isLocked());

        $user->incrementFailedAttempts(max: 3, lockMinutes: 10);
        $this->assertEquals(1, $user->failed_login_attempts);
        $this->assertFalse($user->isLocked());

        $user->incrementFailedAttempts(max: 3, lockMinutes: 10);
        $user->incrementFailedAttempts(max: 3, lockMinutes: 10);
        $this->assertEquals(3, $user->failed_login_attempts);
        $this->assertTrue($user->isLocked());

        $user->resetFailedAttempts();
        $this->assertEquals(0, $user->failed_login_attempts);
        $this->assertNull($user->locked_until);
        $this->assertFalse($user->isLocked());
    }

    public function test_enquiry_whatsapp_reply_url_generation(): void
    {
        $enquiry = new Enquiry([
            'customer_name' => 'Meenakshi Sundaram',
            'customer_phone' => '9876543210',
            'product_name' => 'Kanjeevaram Bridal Silk Saree',
            'product_sku' => 'IC-0042',
        ]);

        $replyUrl = $enquiry->reply_whats_app_url;
        $this->assertNotNull($replyUrl);
        $this->assertStringStartsWith('https://wa.me/919876543210?text=', $replyUrl);
        $this->assertStringContainsString('IC-0042', $replyUrl);
    }

    public function test_otp_code_expiry_and_verification(): void
    {
        $otp = OtpCode::create([
            'identifier' => 'test@iswaryasilks.com',
            'purpose' => OtpPurpose::Login,
            'otp_hash' => Hash::make('654321'),
            'attempts' => 0,
            'expires_at' => now()->addMinutes(10),
            'created_at' => now(),
        ]);

        $this->assertFalse($otp->isExpired());
        $this->assertFalse($otp->isVerified());

        $this->assertFalse($otp->verify('000000'));
        $this->assertEquals(1, $otp->attempts);
        $this->assertFalse($otp->isVerified());

        $this->assertTrue($otp->verify('654321'));
        $this->assertTrue($otp->isVerified());
    }
}

