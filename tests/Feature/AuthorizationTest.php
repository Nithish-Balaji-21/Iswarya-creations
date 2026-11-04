<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_protected_account_routes(): void
    {
        $response = $this->get(route('account.dashboard'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('account.wishlist'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('account.profile'));
        $response->assertRedirect(route('login'));
    }

    public function test_guest_is_redirected_from_protected_admin_routes(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('admin.login'));

        $response = $this->get(route('admin.products.index'));
        $response->assertRedirect(route('admin.login'));

        $response = $this->get(route('admin.categories.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_customer_cannot_access_admin_portal(): void
    {
        $customer = User::factory()->create(['role' => UserRole::Customer]);

        $response = $this->actingAs($customer, 'admin')->get(route('admin.dashboard'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_with_must_change_password_is_forced_to_update_password(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
            'must_change_password' => true,
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));
        $response->assertRedirect(route('admin.password.force'));
    }
}

