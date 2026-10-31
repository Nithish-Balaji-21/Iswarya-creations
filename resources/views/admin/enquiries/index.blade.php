@extends('layouts.admin')

@section('title', 'Manage Customer WhatsApp Enquiries – Iswarya Silks Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between pb-6 border-b border-rose-100">
        <div>
            <span class="text-xs uppercase font-bold tracking-widest text-gold">Concierge Conversion Desk</span>
            <h1 class="text-3xl font-bold text-stone-900 font-jost mt-1">WhatsApp Customer Enquiries</h1>
            <p class="text-xs text-stone-500 mt-1">Manage status, log internal staff notes, and reply directly on WhatsApp.</p>
        </div>
    </div>

    <!-- Livewire Enquiry Manager -->
    <livewire:admin.admin-enquiry-manager />
</div>
@endsection
