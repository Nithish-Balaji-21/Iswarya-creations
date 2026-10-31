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
            'instagram_url' => Setting::get('instagram_url', config('iswarya.instagram_url')),
            'announcement_text' => Setting::get('announcement_text', config('iswarya.default_announcement')),
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
            'instagram_url' => ['required', 'url'],
            'announcement_text' => ['required', 'string', 'max:255'],
            'contact_phone' => ['nullable', 'string'],
            'contact_email' => ['nullable', 'email'],
        ]);

        foreach ($validated as $key => $val) {
            Setting::set($key, $val);
        }

        return back()->with('success', 'Storefront settings and WhatsApp configuration updated.');
    }
}
