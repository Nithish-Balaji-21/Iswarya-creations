<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminLockoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_account_locks_after_5_consecutive_failed_attempts(): void
    {
        $admin = User::factory()->create([
            'email' => 'storeadmin@iswaryasilks.com',
            'password' => Hash::make('CorrectPassword123!'),
            'role' => UserRole::Admin,
            'must_change_password' => false,
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ]);

        // Fail 4 times
        for ($i = 1; $i <= 4; $i++) {
            $response = $this->from(route('admin.login'))->post(route('admin.login.submit'), [
                'email' => 'storeadmin@iswaryasilks.com',
                'password' => 'WrongPassword',
            ]);

            $response->assertRedirect(route('admin.login'));
            $admin->refresh();
            $this->assertEquals($i, $admin->failed_login_attempts);
            $this->assertFalse($admin->isLocked());
        }

        // 5th failed attempt triggers 15-minute lockout
        $response = $this->from(route('admin.login'))->post(route('admin.login.submit'), [
            'email' => 'storeadmin@iswaryasilks.com',
            'password' => 'WrongPassword',
        ]);

        $admin->refresh();
        $this->assertEquals(5, $admin->failed_login_attempts);
        $this->assertTrue($admin->isLocked());
        $this->assertNotNull($admin->locked_until);
        $this->assertTrue($admin->locked_until->isFuture());

        // Attempting to log in with CORRECT password while locked is rejected
        $response = $this->from(route('admin.login'))->post(route('admin.login.submit'), [
            'email' => 'storeadmin@iswaryasilks.com',
            'password' => 'CorrectPassword123!',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('admin');
    }

    public function test_successful_admin_login_resets_failed_attempts(): void
    {
        $admin = User::factory()->create([
            'email' => 'manager@iswaryasilks.com',
            'password' => Hash::make('ValidAdmin2026!'),
            'role' => UserRole::Admin,
            'must_change_password' => false,
            'failed_login_attempts' => 3,
        ]);

        $response = $this->post(route('admin.login.submit'), [
            'email' => 'manager@iswaryasilks.com',
            'password' => 'ValidAdmin2026!',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin, 'admin');

        $admin->refresh();
        $this->assertEquals(0, $admin->failed_login_attempts);
        $this->assertNull($admin->locked_until);
    }

    public function test_admin_is_forced_to_change_password_on_first_login(): void
    {
        $admin = User::factory()->create([
            'email' => 'newadmin@iswaryasilks.com',
            'password' => Hash::make('TemporaryInitialPass!'),
            'role' => UserRole::Admin,
            'must_change_password' => true,
        ]);

        $response = $this->post(route('admin.login.submit'), [
            'email' => 'newadmin@iswaryasilks.com',
            'password' => 'TemporaryInitialPass!',
        ]);

        // Redirects to forced password change
        $response->assertRedirect(route('admin.password.force'));

        // Admin updates password
        $updateResponse = $this->actingAs($admin, 'admin')->post(route('admin.password.update'), [
            'password' => 'NewPermanentPass2026!',
            'password_confirmation' => 'NewPermanentPass2026!',
        ]);

        $updateResponse->assertRedirect(route('admin.dashboard'));

        $admin->refresh();
        $this->assertFalse($admin->must_change_password);
        $this->assertTrue(Hash::check('NewPermanentPass2026!', $admin->password));
    }

    public function test_customer_user_cannot_access_admin_portal(): void
    {
        $customer = User::factory()->create([
            'role' => UserRole::Customer,
        ]);

        $response = $this->actingAs($customer, 'web')->get(route('admin.dashboard'));

        // AdminMiddleware rejects customer and redirects to admin.login
        $response->assertRedirect(route('admin.login'));
    }
}
