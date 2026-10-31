@extends('layouts.app')

@section('title', 'Personalized Video Shopping – Iswarya Silks | Peelamedu, Coimbatore')
@section('meta_description', 'Experience virtual silk saree shopping via WhatsApp video call. View real drape, luster, and borders live from our Peelamedu, Coimbatore flagship.')

@section('content')
    <!-- Hero Banner -->
    <section class="bg-gradient-to-b from-brand-pearl to-white border-b border-rose-100 py-16 sm:py-20 text-center">
        <div class="max-w-3xl mx-auto px-4">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-semibold tracking-wider uppercase bg-emerald-50 text-emerald-800 border border-emerald-200 mb-4">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Virtual Concierge Available</span>
            </span>
            <h1 class="text-3xl sm:text-5xl font-bold text-stone-900 font-jost tracking-tight mb-4">
                Personalized Video Shopping
            </h1>
            <p class="text-sm sm:text-base text-stone-600 font-light leading-relaxed">
                Connect one-on-one with our Peelamedu showroom draper via WhatsApp Video Call. Experience true silk lustre, border intricacies, and drape before you purchase.
            </p>
        </div>
    </section>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-10 items-start">
            <!-- Left Column: How it Works & Instant WhatsApp Call (Span 5) -->
            <div class="md:col-span-5 space-y-6">
                <!-- Instant Call Card -->
                <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 text-white rounded-2xl p-6 shadow-xl relative overflow-hidden">
                    <div class="relative z-10 space-y-3">
                        <span class="text-xs uppercase font-bold tracking-widest text-emerald-100">Direct Concierge</span>
                        <h3 class="text-xl font-bold font-jost">Need an Instant Consultation?</h3>
                        <p class="text-xs text-emerald-50 leading-relaxed font-light">
                            Our Peelamedu draper is on the showroom floor right now. Start a WhatsApp conversation directly.
                        </p>
                        <a href="{{ $instantCallUrl }}" target="_blank" rel="noopener noreferrer" class="btn-pill bg-white text-emerald-800 hover:bg-emerald-50 w-full py-3 text-xs tracking-wider uppercase font-bold flex items-center justify-center gap-2 shadow-md transition-colors mt-2">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.587-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z"/></svg>
                            <span>WhatsApp Video Call Now</span>
                        </a>
                    </div>
                </div>

                <!-- 3 Steps -->
                <div class="bg-white rounded-2xl p-6 border border-rose-100 shadow-xs space-y-4 text-xs">
                    <h4 class="text-xs uppercase font-bold tracking-wider text-stone-900 border-b border-rose-50 pb-2">
                        How Virtual Shopping Works
                    </h4>
                    
                    <div class="flex gap-3 items-start">
                        <span class="w-6 h-6 rounded-full bg-rose-100 text-maroon font-bold flex items-center justify-center shrink-0">1</span>
                        <div>
                            <p class="font-semibold text-stone-800">Select Date & Time</p>
                            <p class="text-stone-500 mt-0.5">Submit your preferred appointment slot using the form.</p>
                        </div>
                    </div>

                    <div class="flex gap-3 items-start">
                        <span class="w-6 h-6 rounded-full bg-rose-100 text-maroon font-bold flex items-center justify-center shrink-0">2</span>
                        <div>
                            <p class="font-semibold text-stone-800">WhatsApp Confirmation</p>
                            <p class="text-stone-500 mt-0.5">Our coordinator will confirm the call on WhatsApp with tailored catalog previews.</p>
                        </div>
                    </div>

                    <div class="flex gap-3 items-start">
                        <span class="w-6 h-6 rounded-full bg-rose-100 text-maroon font-bold flex items-center justify-center shrink-0">3</span>
                        <div>
                            <p class="font-semibold text-stone-800">Live Video Drape</p>
                            <p class="text-stone-500 mt-0.5">Watch authentic sarees unfurled under natural lighting and discuss custom blouse pairing.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Appointment Booking Form (Span 7) -->
            <div class="md:col-span-7 bg-white p-8 rounded-2xl border border-rose-100 shadow-sm">
                <div class="mb-6">
                    <span class="text-xs uppercase font-bold tracking-widest text-gold">Schedule Viewing</span>
                    <h2 class="text-2xl font-bold text-stone-900 font-jost mt-1">Book an Appointment</h2>
                    <p class="text-xs text-stone-500 mt-1">Private video call slots available 10:00 AM – 8:00 PM IST daily.</p>
                </div>

                <form method="POST" action="{{ route('video-shopping.store') }}" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-semibold text-stone-700 mb-1">Your Full Name <span class="text-rose-600">*</span></label>
                        <input type="text" name="name" value="{{ old('name', auth('web')->user()?->name) }}" required placeholder="e.g. Priyadarshini Ramasamy" class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
                        @error('name') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-stone-700 mb-1">Mobile / WhatsApp Number <span class="text-rose-600">*</span></label>
                        <input type="tel" name="phone" value="{{ old('phone', auth('web')->user()?->phone) }}" required placeholder="+91 98765 43210" class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
                        @error('phone') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-stone-700 mb-1">Preferred Date <span class="text-rose-600">*</span></label>
                            <input type="date" name="preferred_date" value="{{ old('preferred_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
                            @error('preferred_date') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-stone-700 mb-1">Preferred Time Slot <span class="text-rose-600">*</span></label>
                            <select name="preferred_time" required class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon bg-white">
                                <option value="Morning (10:30 AM - 12:30 PM)">Morning (10:30 AM - 12:30 PM IST)</option>
                                <option value="Afternoon (02:00 PM - 04:00 PM)">Afternoon (02:00 PM - 04:00 PM IST)</option>
                                <option value="Evening (04:30 PM - 06:30 PM)">Evening (04:30 PM - 06:30 PM IST)</option>
                                <option value="Night (07:00 PM - 08:30 PM)">Night (07:00 PM - 08:30 PM IST)</option>
                            </select>
                            @error('preferred_time') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-stone-700 mb-1">Saree Preferences / Wedding Requirements (Optional)</label>
                        <textarea name="note" rows="3" placeholder="Tell us if you are looking for Muhurtham Kanjeevaram, specific colours, budget range, or reception styles..." class="w-full text-xs px-3.5 py-2.5 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">{{ old('note') }}</textarea>
                    </div>

                    <button type="submit" class="btn-pill btn-maroon w-full py-3.5 text-xs uppercase tracking-wider font-semibold shadow-md hover:shadow-lg transition-transform active:scale-95">
                        Confirm Appointment on WhatsApp
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
