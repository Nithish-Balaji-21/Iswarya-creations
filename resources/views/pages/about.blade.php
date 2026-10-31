@extends('layouts.app')

@section('title', 'About Our Heritage – Iswarya Silks | Peelamedu, Coimbatore')
@section('meta_description', 'Learn about Iswarya Silks (ஐஸ்வர்யா சில்க்ஸ்), authentic handloom silk saree manufacturer based in Peelamedu, Coimbatore. Hand-woven mulberry silk and tested pure zari.')

@section('content')
    <section class="bg-brand-pearl border-b border-rose-100 py-16 text-center">
        <div class="max-w-3xl mx-auto px-4">
            <span class="text-xs uppercase tracking-widest text-gold font-semibold block mb-2">Our Story • Peelamedu, Coimbatore</span>
            <h1 class="text-3xl sm:text-5xl font-bold text-stone-900 font-jost tracking-tight mb-4">
                The Heritage of Iswarya Silks
            </h1>
            <p class="text-sm sm:text-base text-stone-600 font-light leading-relaxed">
                Preserving timeless South Indian handloom mastery, one shuttle at a time.
            </p>
        </div>
    </section>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-16">
        <!-- Story Section 1 -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            <div class="space-y-4 text-stone-700 text-sm leading-relaxed">
                <span class="text-xs font-bold uppercase tracking-wider text-maroon">Our Roots</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-stone-900 font-jost">Born in the Textile Capital of Tamil Nadu</h2>
                <p>
                    Nestled in Peelamedu, Coimbatore, <strong>Iswarya Silks (ஐஸ்வர்யா சில்க்ஸ்)</strong> was founded with a singular conviction: that handloom silk is not merely a garment, but a living sacred art form.
                </p>
                <p>
                    Unlike mass-produced commercial fabrics, an authentic Iswarya saree takes our master weavers up to 25 days to finish on pit looms and frame looms. Each motif—from the sacred Mayil (peacock) to the ornate Rudraksha and temple gopuram borders—carries spiritual gravitas.
                </p>
            </div>
            <div class="rounded-2xl overflow-hidden shadow-lg border border-rose-100 aspect-[4/3] bg-stone-200">
                <img src="{{ asset('images/heritage-loom.webp') }}" alt="Peelamedu Coimbatore Handloom" class="w-full h-full object-cover">
            </div>
        </div>

        <!-- Pillar Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pt-8 border-t border-rose-100">
            <div class="bg-white p-6 rounded-xl border border-rose-100 shadow-xs">
                <span class="w-8 h-8 rounded-full bg-rose-100 text-maroon font-bold flex items-center justify-center mb-4">1</span>
                <h3 class="text-base font-bold text-stone-900 font-jost mb-2">100% Tested Pure Zari</h3>
                <p class="text-xs text-stone-600 leading-relaxed">
                    We use exclusively tested metallic zari and pure silver thread dipped in gold wash, ensuring your heirlooms never tarnish.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl border border-rose-100 shadow-xs">
                <span class="w-8 h-8 rounded-full bg-rose-100 text-maroon font-bold flex items-center justify-center mb-4">2</span>
                <h3 class="text-base font-bold text-stone-900 font-jost mb-2">Artisan First Economy</h3>
                <p class="text-xs text-stone-600 leading-relaxed">
                    By operating as a direct showcase without intermediate retail markups, our weaver families receive direct dignified compensations.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl border border-rose-100 shadow-xs">
                <span class="w-8 h-8 rounded-full bg-rose-100 text-maroon font-bold flex items-center justify-center mb-4">3</span>
                <h3 class="text-base font-bold text-stone-900 font-jost mb-2">Direct Concierge Service</h3>
                <p class="text-xs text-stone-600 leading-relaxed">
                    No impersonal online checkout. You converse one-on-one with our Peelamedu showroom draper via WhatsApp to finalize your selection.
                </p>
            </div>
        </div>
    </div>
@endsection
