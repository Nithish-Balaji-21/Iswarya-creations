@extends('layouts.admin')

@section('title', 'Video Shopping Appointments – Iswarya Silks Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between pb-6 border-b border-rose-100">
        <div>
            <span class="text-xs uppercase font-bold tracking-widest text-gold">Virtual Concierge</span>
            <h1 class="text-3xl font-bold text-stone-900 font-jost mt-1">Video Shopping Appointments</h1>
            <p class="text-xs text-stone-500 mt-1">Scheduled video calls from patrons seeking bridal and handloom draper consultations.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-rose-100 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-brand-pearl text-stone-600 uppercase tracking-wider text-[10px] border-b border-rose-100">
                    <tr>
                        <th class="py-3 px-6">Customer</th>
                        <th class="py-3 px-4">Phone / WhatsApp</th>
                        <th class="py-3 px-4">Date & Slot</th>
                        <th class="py-3 px-4">Preferences / Note</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-6 text-right">Quick WhatsApp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-rose-50">
                    @forelse($appointments as $apt)
                        @php
                            $cleanPhone = preg_replace('/[^0-9]/', '', $apt->phone);
                            if (strlen($cleanPhone) === 10) $cleanPhone = '91' . $cleanPhone;
                            $waMsg = rawurlencode("Hello {$apt->name}, confirming your Iswarya Silks Video Shopping appointment for " . $apt->preferred_date->format('d M Y') . " ({$apt->preferred_time}).");
                        @endphp
                        <tr class="hover:bg-rose-50/20">
                            <td class="py-3.5 px-6">
                                <p class="font-bold text-stone-900">{{ $apt->name }}</p>
                                <span class="text-[10px] text-stone-400">Booked {{ $apt->created_at->diffForHumans() }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-mono font-semibold text-stone-800">
                                {{ $apt->phone }}
                            </td>
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-stone-900">{{ $apt->preferred_date->format('D, d M Y') }}</p>
                                <p class="text-[10px] text-stone-500">{{ $apt->preferred_time }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-stone-600 max-w-xs">
                                {{ $apt->note ?? 'General Saree Consultation' }}
                            </td>
                            <td class="py-3.5 px-4">
                                <form method="POST" action="{{ route('admin.appointments.update-status', $apt->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="text-[10px] font-semibold rounded-lg px-2 py-1 border {{ $apt->status->badgeClasses() }}">
                                        @foreach(\App\Enums\VideoAppointmentStatus::cases() as $st)
                                            <option value="{{ $st->value }}" {{ $apt->status === $st ? 'selected' : '' }}>
                                                {{ $st->label() }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td class="py-3.5 px-6 text-right">
                                <a href="https://wa.me/{{ $cleanPhone }}?text={{ $waMsg }}" target="_blank" rel="noopener noreferrer" class="btn-pill btn-whatsapp px-3 py-1 text-[11px] font-semibold inline-flex items-center gap-1">
                                    <span>Call / Chat</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-xs text-stone-400">
                                No video shopping appointments booked yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-rose-50">
            {{ $appointments->links() }}
        </div>
    </div>
</div>
@endsection
