<?php

namespace Tests\Feature;

use App\Enums\OtpPurpose;
use App\Enums\UserRole;
use App\Models\User;
use App\Services\Otp\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_login_with_valid_password(): void
    {
        $user = User::factory()->create([
            'email' => 'patron@example.com',
            'password' => Hash::make('Secret123!'),
            'role' => UserRole::Customer,
        ]);

        $response = $this->post(route('auth.login.password'), [
            'identifier' => 'patron@example.com',
            'password' => 'Secret123!',
        ]);

        $response->assertRedirect(route('account.dashboard'));
        $this->assertAuthenticatedAs($user, 'web');
    }

    public function test_customer_login_fails_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'patron@example.com',
            'password' => Hash::make('Secret123!'),
        ]);

        $response = $this->from(route('login'))->post(route('auth.login.password'), [
            'identifier' => 'patron@example.com',
            'password' => 'WrongPassword',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('identifier');
        $this->assertGuest('web');
    }

    public function test_customer_can_register_new_account(): void
    {
        $response = $this->post(route('auth.register'), [
            'name' => 'Kavitha Ramachandran',
            'email' => 'kavitha@example.com',
            'phone' => '+919876543210',
            'password' => 'SecurePass123!',
            'password_confirmation' => 'SecurePass123!',
        ]);

        $response->assertRedirect(route('account.dashboard'));
        $this->assertDatabaseHas('users', [
            'email' => 'kavitha@example.com',
            'phone' => '+919876543210',
            'role' => UserRole::Customer->value,
        ]);
        $this->assertAuthenticated('web');
    }

    public function test_customer_can_login_via_otp(): void
    {
        $otpService = app(OtpService::class);
        $plainOtp = $otpService->generate('customer@iswaryasilks.com', OtpPurpose::Login);

        $response = $this->post(route('auth.otp.verify.submit'), [
            'identifier' => 'customer@iswaryasilks.com',
            'purpose' => 'login',
            'otp' => $plainOtp,
        ]);

        $response->assertRedirect(route('account.dashboard'));
        $this->assertDatabaseHas('users', [
            'email' => 'customer@iswaryasilks.com',
            'role' => UserRole::Customer->value,
        ]);
        $this->assertAuthenticated('web');
    }

    public function test_customer_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->post(route('logout'));

        $response->assertRedirect(route('home'));
        $this->assertGuest('web');
    }
}

