<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_registration_requires_name_and_contact(): void
    {
        $response = $this->post(route('auth.register'), []);

        $response->assertSessionHasErrors(['name', 'password']);
    }

    public function test_customer_registration_rejects_duplicate_email(): void
    {
        User::factory()->create(['email' => 'duplicate@example.com']);

        $response = $this->post(route('auth.register'), [
            'name' => 'Test User',
            'email' => 'duplicate@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_video_shopping_appointment_validates_required_fields(): void
    {
        $response = $this->post(route('video-shopping.store'), [
            'name' => '',
            'phone' => 'invalid-phone',
            'preferred_date' => now()->subDay()->toDateString(), // Past date invalid
            'preferred_time' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'phone', 'preferred_date', 'preferred_time']);
    }

    public function test_newsletter_subscription_requires_valid_email(): void
    {
        $response = $this->post(route('newsletter.subscribe'), [
            'email' => 'not-an-email',
        ]);

        $response->assertSessionHasErrors('email');
    }
}

