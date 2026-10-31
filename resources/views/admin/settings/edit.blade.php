@extends('layouts.admin')

@section('title', 'Storefront & WhatsApp Settings – Iswarya Silks Admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between pb-6 border-b border-rose-100">
        <div>
            <span class="text-xs uppercase font-bold tracking-widest text-gold">Store Configuration</span>
            <h1 class="text-3xl font-bold text-stone-900 font-jost mt-1">Storefront & WhatsApp Settings</h1>
            <p class="text-xs text-stone-500 mt-1">Configure your primary WhatsApp concierge number, showroom address, and announcement strip.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-rose-100 shadow-2xs">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6 text-xs">
            @csrf
            @method('PUT')

            <!-- WhatsApp Number Configuration -->
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-5 space-y-2">
                <div class="flex items-center gap-2 text-emerald-900 font-bold text-sm">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.587-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z"/></svg>
                    <span>Primary WhatsApp Concierge Number</span>
                </div>
                <p class="text-stone-600 text-[11px] leading-relaxed">
                    This number receives all public "Enquire on WhatsApp" taps across product cards, product details, and the floating concierge button. Include country code without '+' (e.g. <code>918973948912</code>).
                </p>
                <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $settings['whatsapp_number']) }}" required class="w-full text-xs font-mono font-bold px-3.5 py-2.5 border border-emerald-300 rounded-lg bg-white">
                @error('whatsapp_number') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
            </div>

            <!-- Announcement Bar Text -->
            <div>
                <label class="block font-semibold text-stone-700 uppercase tracking-wider mb-1">
                    Announcement Bar Text (Top of Public Storefront) <span class="text-rose-600">*</span>
                </label>
                <input type="text" name="announcement_text" value="{{ old('announcement_text', $settings['announcement_text']) }}" required class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg">
                <p class="text-[11px] text-stone-400 mt-1">Default: Free Express Shipping Across India | Handloom Certified Pure Silk</p>
                @error('announcement_text') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
            </div>

            <!-- Showroom Address -->
            <div>
                <label class="block font-semibold text-stone-700 uppercase tracking-wider mb-1">
                    Showroom Physical Address <span class="text-rose-600">*</span>
                </label>
                <textarea name="store_address" rows="3" required class="w-full text-xs px-3.5 py-2 border border-rose-200 rounded-lg">{{ old('store_address', $settings['store_address']) }}</textarea>
                @error('store_address') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
            </div>

            <!-- Instagram URL -->
            <div>
                <label class="block font-semibold text-stone-700 uppercase tracking-wider mb-1">
                    Instagram Profile URL <span class="text-rose-600">*</span>
                </label>
                <input type="url" name="instagram_url" value="{{ old('instagram_url', $settings['instagram_url']) }}" required placeholder="https://www.instagram.com/iswaryasilks" class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg">
                @error('instagram_url') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
            </div>

            <!-- Contact Phone & Email -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-stone-700 uppercase tracking-wider mb-1">Display Contact Phone</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone']) }}" class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg">
                </div>

                <div>
                    <label class="block font-semibold text-stone-700 uppercase tracking-wider mb-1">Display Concierge Email</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}" class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg">
                </div>
            </div>

            <div class="flex items-center justify-end pt-4 border-t border-rose-100">
                <button type="submit" class="btn-pill btn-maroon px-8 py-3 text-xs font-semibold tracking-wider uppercase shadow-md">
                    Save Storefront Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
