@extends('layouts.admin')

@section('title', 'Storefront & Website Settings – Iswarya Silks Admin')
@section('page_heading', 'Storefront & Website Configuration')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-6 border-b border-stone-200 gap-4">
        <div>
            <span class="text-xs uppercase font-bold tracking-widest text-gold-dark">Global Storefront Settings</span>
            <h1 class="text-2xl sm:text-3xl font-bold text-stone-900 font-jost mt-1">Website Command Center</h1>
            <p class="text-xs text-stone-500 mt-1">Manage your active WhatsApp concierge number, showroom maps, announcements, and contact details across the entire website.</p>
        </div>

        <a href="{{ route('home') }}" target="_blank" class="btn-pill border border-stone-300 bg-white hover:bg-stone-50 px-4 py-2 text-xs font-semibold text-stone-700 inline-flex items-center gap-1.5 shadow-2xs">
            <svg class="w-3.5 h-3.5 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            <span>Preview Storefront</span>
        </a>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-8 text-xs">
        @csrf
        @method('PUT')

        <!-- SECTION 1: WhatsApp Concierge -->
        <div class="bg-white rounded-2xl border border-rose-100 shadow-xs overflow-hidden">
            <div class="bg-emerald-50/70 border-b border-emerald-100 p-5 flex items-center justify-between">
                <div class="flex items-center gap-2.5 text-emerald-950 font-bold text-sm">
                    <svg class="w-5 h-5 fill-current text-emerald-600" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.587-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z"/></svg>
                    <span>1. WhatsApp Concierge Connection</span>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded-full">Primary Conversion</span>
            </div>
            
            <div class="p-6 space-y-4">
                <div>
                    <label class="block font-bold text-stone-800 uppercase tracking-wider mb-1">
                        Showroom WhatsApp Phone Number <span class="text-rose-600">*</span>
                    </label>
                    <p class="text-stone-500 text-[11px] mb-2 leading-relaxed">
                        This phone number powers the entire website's "Enquire on WhatsApp", floating chat concierge, and product inquiry buttons. Enter country code + number with digits only (no spaces, hyphens, or '+' sign).
                    </p>
                    <div class="flex items-center gap-2 max-w-md">
                        <span class="px-3.5 py-2.5 bg-stone-100 border border-stone-300 text-stone-600 rounded-lg font-mono font-bold text-xs">+</span>
                        <input type="text" 
                               name="whatsapp_number" 
                               value="{{ old('whatsapp_number', $settings['whatsapp_number']) }}" 
                               required 
                               placeholder="918973948912"
                               class="w-full text-xs font-mono font-bold px-3.5 py-2.5 border border-stone-300 rounded-lg focus:outline-hidden focus:border-maroon focus:ring-1 focus:ring-maroon bg-white">
                    </div>
                    @error('whatsapp_number') <span class="text-rose-600 text-[11px] block mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- SECTION 2: Showroom Address & Google Maps -->
        <div class="bg-white rounded-2xl border border-rose-100 shadow-xs overflow-hidden">
            <div class="bg-brand-pearl border-b border-rose-100 p-5 flex items-center justify-between">
                <div class="flex items-center gap-2.5 text-stone-900 font-bold text-sm">
                    <svg class="w-5 h-5 text-maroon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>2. Flagship Showroom Location & Google Maps</span>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider bg-rose-100 text-maroon px-2.5 py-0.5 rounded-full">Peelamedu Flagship</span>
            </div>

            <div class="p-6 space-y-5">
                <!-- Physical Address -->
                <div>
                    <label class="block font-bold text-stone-800 uppercase tracking-wider mb-1">
                        Showroom Physical Address <span class="text-rose-600">*</span>
                    </label>
                    <textarea name="store_address" 
                              rows="3" 
                              required 
                              class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon focus:ring-1 focus:ring-maroon leading-relaxed">{{ old('store_address', $settings['store_address']) }}</textarea>
                    <p class="text-[11px] text-stone-400 mt-1">Displayed in website footer, Contact Us page, and Google Structured Data.</p>
                    @error('store_address') <span class="text-rose-600 text-[11px] block mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Google Maps Link -->
                <div>
                    <label class="block font-bold text-stone-800 uppercase tracking-wider mb-1">
                        Google Maps Direct Share / Directions Link
                    </label>
                    <div class="flex gap-2">
                        <input type="url" 
                               name="google_maps_url" 
                               value="{{ old('google_maps_url', $settings['google_maps_url']) }}" 
                               placeholder="https://share.google/qQvd7CVGrSjmYrHhM" 
                               class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon focus:ring-1 focus:ring-maroon">
                        @if(!empty($settings['google_maps_url']))
                            <a href="{{ $settings['google_maps_url'] }}" 
                               target="_blank" 
                               class="btn-pill border border-rose-200 bg-brand-pearl hover:bg-rose-50 text-stone-700 px-4 py-2.5 text-xs font-semibold whitespace-nowrap inline-flex items-center gap-1">
                                <span>Test Link</span>
                                <svg class="w-3.5 h-3.5 text-maroon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        @endif
                    </div>
                    <p class="text-[11px] text-stone-400 mt-1">Direct link that opens Google Maps navigation for patrons visiting Peelamedu.</p>
                    @error('google_maps_url') <span class="text-rose-600 text-[11px] block mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Google Maps Embed Code -->
                <div>
                    <label class="block font-bold text-stone-800 uppercase tracking-wider mb-1">
                        Google Maps Interactive Embed (URL or &lt;iframe&gt;)
                    </label>
                    <textarea name="google_maps_embed" 
                              rows="3" 
                              placeholder="https://maps.google.com/maps?q=... or <iframe src='...'></iframe>" 
                              class="w-full text-xs font-mono px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon focus:ring-1 focus:ring-maroon leading-relaxed">{{ old('google_maps_embed', $settings['google_maps_embed'] ?? '') }}</textarea>
                    <p class="text-[11px] text-stone-400 mt-1">
                        Powers the interactive map on the public Contact Us page. Paste either a Google Maps embed URL or the full embed <code>&lt;iframe&gt;</code> snippet.
                    </p>
                    @error('google_maps_embed') <span class="text-rose-600 text-[11px] block mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- SECTION 3: Storefront Top Announcement Bar -->
        <div class="bg-white rounded-2xl border border-rose-100 shadow-xs overflow-hidden">
            <div class="bg-brand-pearl border-b border-rose-100 p-5 flex items-center justify-between">
                <div class="flex items-center gap-2.5 text-stone-900 font-bold text-sm">
                    <svg class="w-5 h-5 text-gold-dark" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <span>3. Top Announcement Strip</span>
                </div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" 
                           name="announcement_enabled" 
                           value="1" 
                           {{ old('announcement_enabled', $settings['announcement_enabled']) == '1' ? 'checked' : '' }} 
                           class="rounded text-maroon focus:ring-maroon">
                    <span class="text-xs font-bold text-stone-700">Display On Storefront</span>
                </label>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <label class="block font-bold text-stone-800 uppercase tracking-wider mb-1">
                        Announcement Bar Text (Maroon Header Strip) <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" 
                           name="announcement_text" 
                           value="{{ old('announcement_text', $settings['announcement_text']) }}" 
                           required 
                           class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon focus:ring-1 focus:ring-maroon">
                    <p class="text-[11px] text-stone-400 mt-1">Example: Free Express Shipping Across India | Handloom Certified Pure Silk</p>
                    @error('announcement_text') <span class="text-rose-600 text-[11px] block mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- SECTION 4: Contact & Showroom Hours -->
        <div class="bg-white rounded-2xl border border-rose-100 shadow-xs overflow-hidden">
            <div class="bg-brand-pearl border-b border-rose-100 p-5 flex items-center justify-between">
                <div class="flex items-center gap-2.5 text-stone-900 font-bold text-sm">
                    <svg class="w-5 h-5 text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>4. Showroom Timings & Direct Communications</span>
                </div>
            </div>

            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Phone -->
                    <div>
                        <label class="block font-bold text-stone-800 uppercase tracking-wider mb-1">Showroom Landline / Mobile</label>
                        <input type="text" 
                               name="contact_phone" 
                               value="{{ old('contact_phone', $settings['contact_phone']) }}" 
                               class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon focus:ring-1 focus:ring-maroon">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block font-bold text-stone-800 uppercase tracking-wider mb-1">Showroom Email Address</label>
                        <input type="email" 
                               name="contact_email" 
                               value="{{ old('contact_email', $settings['contact_email']) }}" 
                               placeholder="iswaryacreations1975@gmail.com" 
                               class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon focus:ring-1 focus:ring-maroon">
                    </div>
                </div>

                <!-- Showroom Timings -->
                <div>
                    <label class="block font-bold text-stone-800 uppercase tracking-wider mb-1">Showroom Operating Hours</label>
                    <input type="text" 
                           name="showroom_timings" 
                           value="{{ old('showroom_timings', $settings['showroom_timings']) }}" 
                           placeholder="10:00 AM – 9:00 PM (Open All 7 Days)" 
                           class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon focus:ring-1 focus:ring-maroon">
                    <p class="text-[11px] text-stone-400 mt-1">Displayed in website footer and Contact Us page.</p>
                </div>

                <!-- Instagram -->
                <div>
                    <label class="block font-bold text-stone-800 uppercase tracking-wider mb-1">Instagram Official Profile URL <span class="text-rose-600">*</span></label>
                    <input type="url" 
                           name="instagram_url" 
                           value="{{ old('instagram_url', $settings['instagram_url']) }}" 
                           required 
                           placeholder="https://www.instagram.com/iswaryasilks.cbe?psln=MXJleGxzaHhpNmY0MQ==" 
                           class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon focus:ring-1 focus:ring-maroon">
                    @error('instagram_url') <span class="text-rose-600 text-[11px] block mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Sticky Save Button -->
        <div class="sticky bottom-4 z-20 bg-white/95 backdrop-blur-xs p-4 rounded-2xl border border-rose-100 shadow-xl flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-stone-500 font-medium text-center sm:text-left">Changes apply immediately across all public storefront pages.</p>
            <button type="submit" class="btn-pill btn-maroon w-full sm:w-auto px-8 py-3 text-xs font-semibold tracking-wider uppercase shadow-md hover:shadow-lg transition-transform active:scale-95 text-center">
                Save All Settings
            </button>
        </div>
    </form>
</div>
@endsection
