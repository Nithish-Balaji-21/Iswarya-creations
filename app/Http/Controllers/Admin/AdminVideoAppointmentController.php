<?php

namespace App\Http\Controllers\Admin;

use App\Enums\VideoAppointmentStatus;
use App\Http\Controllers\Controller;
use App\Models\VideoAppointment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminVideoAppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = VideoAppointment::with('user')->latest('preferred_date');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->paginate(20)->withQueryString();

        return view('admin.appointments.index', compact('appointments'));
    }

    public function updateStatus(Request $request, VideoAppointment $appointment): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string'],
        ]);

        $status = VideoAppointmentStatus::from($validated['status']);
        $appointment->update(['status' => $status]);

        return back()->with('success', "Appointment for {$appointment->name} updated to {$status->label()}.");
    }
}
