<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSettingController extends Controller
{
    public function edit(): View
    {
        $settings = [
            'whatsapp_number' => Setting::get('whatsapp_number', config('iswarya.whatsapp_number')),
            'store_address' => Setting::get('store_address', config('iswarya.store_address')),
            'google_maps_url' => Setting::get('google_maps_url', config('iswarya.google_maps_url')),
            'google_maps_embed' => Setting::get('google_maps_embed', 'https://maps.google.com/maps?q=Iswarya+Silks+Peelamedu+Coimbatore&t=&z=15&ie=UTF8&iwloc=&output=embed'),
            'instagram_url' => Setting::get('instagram_url', config('iswarya.instagram_url')),
            'announcement_text' => Setting::get('announcement_text', config('iswarya.default_announcement')),
            'announcement_enabled' => Setting::get('announcement_enabled', '1'),
            'showroom_timings' => Setting::get('showroom_timings', '10:00 AM – 9:00 PM (Open All 7 Days)'),
            'contact_phone' => Setting::get('contact_phone', config('iswarya.contact_phone')),
            'contact_email' => Setting::get('contact_email', config('iswarya.contact_email')),
        ];

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'whatsapp_number' => ['required', 'string'],
            'store_address' => ['required', 'string'],
            'google_maps_url' => ['nullable', 'url'],
            'google_maps_embed' => ['nullable', 'string'],
            'instagram_url' => ['required', 'url'],
            'announcement_text' => ['required', 'string', 'max:255'],
            'announcement_enabled' => ['nullable', 'boolean'],
            'showroom_timings' => ['nullable', 'string', 'max:255'],
            'contact_phone' => ['nullable', 'string'],
            'contact_email' => ['nullable', 'email'],
        ]);

        $validated['announcement_enabled'] = $request->has('announcement_enabled') ? '1' : '0';

        foreach ($validated as $key => $val) {
            Setting::set($key, $val);
        }

        return back()->with('success', 'Storefront settings and WhatsApp configuration updated.');
    }
}
