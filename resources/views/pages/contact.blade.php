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

                <!-- Showroom Address -->
                <div class="bg-white border border-rose-100 rounded-2xl p-6 shadow-xs space-y-3 text-xs">
                    <span class="text-xs uppercase font-bold tracking-wider text-maroon block">Flagship Showroom</span>
                    <h4 class="text-base font-bold text-stone-900 font-jost">Iswarya Silks (ஐஸ்வர்யா சில்க்ஸ்)</h4>
                    <p class="text-stone-600 leading-relaxed">
                        {{ $storeAddress }}
                    </p>
                    <div class="pt-2 border-t border-rose-50 text-stone-600 space-y-1.5">
                        <p><strong class="text-stone-800">Phone:</strong> {{ config('iswarya.contact_phone') }}</p>
                        <p><strong class="text-stone-800">Email:</strong> {{ config('iswarya.contact_email') }}</p>
                        <p><strong class="text-stone-800">Showroom Hours:</strong> 10:00 AM – 9:00 PM (Open All 7 Days)</p>
                    </div>
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
