<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_page_displays_updated_email_instagram_and_google_maps(): void
    {
        Setting::set('contact_email', 'iswaryacreations1975@gmail.com');
        Setting::set('instagram_url', 'https://www.instagram.com/iswaryasilks.cbe?psln=MXJleGxzaHhpNmY0MQ==');
        Setting::set('google_maps_url', 'https://share.google/qQvd7CVGrSjmYrHhM');

        $response = $this->get(route('pages.contact'));

        $response->assertStatus(200);
        $response->assertSee('iswaryacreations1975@gmail.com');
        $response->assertSee('https://www.instagram.com/iswaryasilks.cbe?psln=MXJleGxzaHhpNmY0MQ==', false);
        $response->assertSee('https://share.google/qQvd7CVGrSjmYrHhM', false);
        $response->assertSee('Get Directions on Google Maps');
    }

    public function test_homepage_and_footer_display_updated_social_and_location_links(): void
    {
        Setting::set('contact_email', 'iswaryacreations1975@gmail.com');
        Setting::set('instagram_url', 'https://www.instagram.com/iswaryasilks.cbe?psln=MXJleGxzaHhpNmY0MQ==');
        Setting::set('google_maps_url', 'https://share.google/qQvd7CVGrSjmYrHhM');

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('@iswaryasilks.cbe');
        $response->assertSee('https://www.instagram.com/iswaryasilks.cbe?psln=MXJleGxzaHhpNmY0MQ==', false);
        $response->assertSee('https://share.google/qQvd7CVGrSjmYrHhM', false);
        $response->assertSee('iswaryacreations1975@gmail.com');
    }

    public function test_admin_can_update_storefront_settings_including_google_maps_url(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
            'must_change_password' => false,
        ]);

        $response = $this->actingAs($admin, 'admin')->put(route('admin.settings.update'), [
            'whatsapp_number' => '918973948912',
            'store_address' => '124, Avinashi Road, Peelamedu, Coimbatore - 641004',
            'google_maps_url' => 'https://share.google/qQvd7CVGrSjmYrHhM',
            'instagram_url' => 'https://www.instagram.com/iswaryasilks.cbe?psln=MXJleGxzaHhpNmY0MQ==',
            'announcement_text' => 'Handloom Certified Pure Silk',
            'contact_phone' => '+91 89739 48912',
            'contact_email' => 'iswaryacreations1975@gmail.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('https://share.google/qQvd7CVGrSjmYrHhM', Setting::googleMapsUrl());
        $this->assertEquals('iswaryacreations1975@gmail.com', Setting::contactEmail());
        $this->assertEquals('https://www.instagram.com/iswaryasilks.cbe?psln=MXJleGxzaHhpNmY0MQ==', Setting::instagramUrl());
    }
}
