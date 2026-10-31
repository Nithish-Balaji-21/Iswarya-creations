<?php

namespace App\Http\Controllers;

use App\Enums\VideoAppointmentStatus;
use App\Models\Setting;
use App\Models\VideoAppointment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VideoShoppingController extends Controller
{
    public function show(): View
    {
        $whatsAppNumber = Setting::whatsappNumber();
        $cleanNumber = preg_replace('/[^0-9]/', '', $whatsAppNumber);

        // Pre-composed WhatsApp direct video call message
        $videoCallMessage = rawurlencode('Hello Iswarya Silks, I would like to schedule a Live Video Shopping appointment to view your bridal and handloom silk saree collection.');
        $instantCallUrl = "https://wa.me/{$cleanNumber}?text={$videoCallMessage}";

        return view('pages.video-shopping', compact('instantCallUrl', 'whatsAppNumber'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^(\+91)?[6-9]\d{9}$/'],
            'preferred_date' => ['required', 'date', 'after_or_equal:today'],
            'preferred_time' => ['required', 'string', 'max:100'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $phone = $validated['phone'];
        $digits = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($digits) === 10) {
            $phone = '+91'.$digits;
        }

        $appointment = VideoAppointment::create([
            'user_id' => Auth::guard('web')->id(),
            'name' => $validated['name'],
            'phone' => $phone,
            'preferred_date' => $validated['preferred_date'],
            'preferred_time' => $validated['preferred_time'],
            'note' => $validated['note'] ?? null,
            'status' => VideoAppointmentStatus::Pending,
        ]);

        $whatsAppNumber = Setting::whatsappNumber();
        $cleanNumber = preg_replace('/[^0-9]/', '', $whatsAppNumber);
        $message = 'Hello Iswarya Silks, I have submitted a Video Shopping appointment request for '.$appointment->preferred_date->format('d M Y')." at {$appointment->preferred_time}. Name: {$appointment->name}, Phone: {$phone}.";
        $waRedirect = "https://wa.me/{$cleanNumber}?text=".rawurlencode($message);

        return redirect()->away($waRedirect);
    }
}
