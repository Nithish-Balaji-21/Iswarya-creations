@extends('layouts.app')

@section('title', 'Visit Our Peelamedu Showroom – Iswarya Silks | Coimbatore')
@section('meta_description', 'Contact Iswarya Silks showroom in Peelamedu, Coimbatore. Chat on WhatsApp for saree orders, bridal consultations, and showroom directions.')

@section('content')
    <section class="bg-brand-pearl border-b border-rose-100 py-12 text-center">
        <div class="max-w-2xl mx-auto px-4">
            <span class="text-xs uppercase tracking-widest text-gold font-semibold block mb-1">We Are Here For You</span>
            <h1 class="text-3xl sm:text-4xl font-bold text-stone-900 font-jost tracking-tight mb-2">
                Visit & Contact Us
            </h1>
            <p class="text-xs sm:text-sm text-stone-600 font-light">
                Experience our curated handloom collections at our flagship showroom or chat with our team on WhatsApp.
            </p>
        </div>
    </section>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <!-- Contact Info Cards -->
            <div class="space-y-6">
                <!-- WhatsApp Card -->
                @php
                    $cleanWa = preg_replace('/[^0-9]/', '', $whatsAppNumber);
                @endphp
                <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-6 flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.587-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-emerald-950 font-jost">WhatsApp Concierge (Fastest Response)</h3>
                        <p class="text-xs text-emerald-800/80 mt-1 leading-relaxed">
                            Have questions about a specific saree, need photos in daylight, or wish to place an enquiry? Our Peelamedu team is available on WhatsApp.
                        </p>
                    </div>
                    <a href="https://wa.me/{{ $cleanWa }}?text={{ rawurlencode('Hello Iswarya Silks, I would like to enquire about your saree collection.') }}" target="_blank" rel="noopener noreferrer" class="btn-pill btn-whatsapp mt-4 py-3 text-xs tracking-wider uppercase font-semibold text-center block">
                        Open WhatsApp Chat
                    </a>
                </div>

                <!-- Showroom Address & Google Maps -->
                <div class="bg-white border border-rose-100 rounded-2xl p-6 shadow-xs space-y-3 text-xs">
                    <span class="text-xs uppercase font-bold tracking-wider text-maroon block">Flagship Showroom</span>
                    <h4 class="text-base font-bold text-stone-900 font-jost">Iswarya Silks (ஐஸ்வர்யா சில்க்ஸ்)</h4>
                    <p class="text-stone-600 leading-relaxed">
                        {{ $storeAddress }}
                    </p>
                    <div class="pt-2 border-t border-rose-50 text-stone-600 space-y-1.5">
                        <p><strong class="text-stone-800">Phone:</strong> <a href="tel:{{ preg_replace('/[^0-9+]/', '', \App\Models\Setting::contactPhone()) }}" class="text-stone-800 hover:text-maroon underline">{{ \App\Models\Setting::contactPhone() }}</a></p>
                        <p><strong class="text-stone-800">Email:</strong> <a href="mailto:{{ \App\Models\Setting::contactEmail() }}" class="text-maroon hover:underline font-medium">{{ \App\Models\Setting::contactEmail() }}</a></p>
                        <p><strong class="text-stone-800">Showroom Hours:</strong> 10:00 AM – 9:00 PM (Open All 7 Days)</p>
                    </div>
                    @if($googleMapsUrl)
                        <div class="pt-3">
                            <a href="{{ $googleMapsUrl }}" target="_blank" rel="noopener noreferrer" class="btn-pill inline-flex items-center gap-2 bg-stone-900 hover:bg-stone-800 text-white px-5 py-2.5 text-xs font-semibold tracking-wider uppercase transition-colors shadow-xs">
                                <svg class="w-4 h-4 text-gold shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>Get Directions on Google Maps</span>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Instagram Card -->
                <div class="bg-gradient-to-r from-purple-50 via-pink-50 to-amber-50 border border-pink-200/80 rounded-2xl p-5 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-amber-500 via-rose-500 to-purple-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-stone-900 uppercase tracking-wider">Follow On Instagram</h4>
                            <p class="text-xs text-stone-600 font-medium">{{ \App\Models\Setting::instagramHandle() }}</p>
                        </div>
                    </div>
                    <a href="{{ $instagramUrl }}" target="_blank" rel="noopener noreferrer" class="btn-pill bg-white hover:bg-stone-50 border border-rose-200 text-stone-800 hover:text-maroon px-4 py-2 text-xs font-semibold tracking-wider transition-colors">
                        View Profile &rarr;
                    </a>
                </div>
            </div>

            <!-- Video Shopping Banner & Interactive Box -->
            <div class="bg-white border border-rose-100 rounded-2xl p-8 shadow-xs flex flex-col justify-between">
                <div>
                    <span class="text-xs uppercase font-bold tracking-widest text-gold block mb-1">Shopping From Outside Coimbatore?</span>
                    <h3 class="text-2xl font-bold text-stone-900 font-jost mb-3">Live Video Consultation</h3>
                    <p class="text-xs text-stone-600 leading-relaxed mb-6 font-light">
                        Over 40% of our patrons shop remotely from Chennai, Bangalore, Mumbai, Singapore, and the US. Book a one-on-one virtual draper session to view real silk lustre, drape, and pallu motifs.
                    </p>

                    <div class="bg-brand-pearl p-4 rounded-xl border border-rose-100/70 mb-6 space-y-2 text-xs">
                        <p class="flex items-center gap-2 text-stone-700">
                            <span class="text-emerald-600 font-bold">✓</span>
                            <span>Direct camera view under 5500K daylight simulation lamps</span>
                        </p>
                        <p class="flex items-center gap-2 text-stone-700">
                            <span class="text-emerald-600 font-bold">✓</span>
                            <span>Matching blouse and contrast border pairing advice</span>
                        </p>
                        <p class="flex items-center gap-2 text-stone-700">
                            <span class="text-emerald-600 font-bold">✓</span>
                            <span>Direct door-step express courier across India & Worldwide</span>
                        </p>
                    </div>
                </div>

                <a href="{{ route('video-shopping') }}" class="btn-pill btn-maroon w-full py-3.5 text-xs uppercase tracking-wider font-semibold text-center block shadow-md">
                    Schedule Video Shopping Appointment
                </a>
            </div>
        </div>
    </div>
@endsection
