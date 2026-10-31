<div class="py-8" x-data="{ zoomActive: false, zoomX: 0, zoomY: 0 }">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Gallery Section (Col 7 on Desktop) -->
        <div class="lg:col-span-7 flex flex-col-reverse sm:flex-row gap-4">
            <!-- Thumbnail Rail -->
            @if($images->count() > 1)
                <div class="flex sm:flex-col gap-3 overflow-x-auto sm:overflow-y-auto sm:max-h-[640px] scrollbar-none pb-2 sm:pb-0 sm:w-20 shrink-0">
                    @foreach($images as $index => $img)
                        <button type="button"
                                wire:click="selectImage({{ $index }})"
                                class="w-16 h-20 sm:w-20 sm:h-25 rounded-md overflow-hidden border-2 transition-all duration-200 shrink-0 focus:outline-hidden {{ $selectedImageIndex === $index ? 'border-maroon ring-2 ring-rose-200' : 'border-rose-100 opacity-70 hover:opacity-100' }}">
                            <img src="{{ $img->thumb_url }}" alt="{{ $product->name }} thumb {{ $index + 1 }}" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif

            <!-- Main Image Frame with Hover Zoom & Lightbox Trigger -->
            <div class="relative flex-1 aspect-[4/5] bg-brand-pearl rounded-lg overflow-hidden border border-rose-100 group">
                @if($activeImage)
                    <div class="relative w-full h-full cursor-zoom-in"
                         @mousemove="zoomActive = true; zoomX = ($event.offsetX / $event.target.offsetWidth) * 100; zoomY = ($event.offsetY / $event.target.offsetHeight) * 100"
                         @mouseleave="zoomActive = false"
                         @click="$wire.set('lightboxOpen', true)">
                        
                        <img src="{{ $activeImage->large_url }}"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover transition-transform duration-300 pointer-events-none"
                             :style="zoomActive ? `transform-origin: ${zoomX}% ${zoomY}%; transform: scale(1.6);` : 'transform: scale(1);'">
                    </div>
                @else
                    <img src="{{ asset('images/placeholder-saree.webp') }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @endif

                <!-- Lightbox Zoom Icon Button -->
                <button type="button"
                        wire:click="$set('lightboxOpen', true)"
                        class="absolute bottom-4 right-4 p-2.5 rounded-full bg-white/90 hover:bg-white text-stone-700 shadow-md backdrop-blur-xs transition-transform hover:scale-105"
                        title="View Fullscreen Lightbox">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                    </svg>
                </button>

                <!-- Badges -->
                <div class="absolute top-4 left-4 flex flex-col gap-1.5 pointer-events-none">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-white/95 text-maroon shadow-xs border border-rose-100">
                        Handloom Pure Silk
                    </span>
                    @if($product->is_sold_out)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-stone-900 text-white shadow-xs">
                            Sold Out
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Details Section (Col 5 on Desktop) -->
        <div class="lg:col-span-5 flex flex-col justify-between">
            <div>
                <!-- Brand & SKU Breadcrumb -->
                <div class="flex items-center justify-between text-xs text-stone-500 mb-2">
                    <span class="tracking-widest uppercase font-medium text-gold">Iswarya Silks • Coimbatore</span>
                    <span class="font-mono">SKU: {{ $product->sku }}</span>
                </div>

                <!-- Title -->
                <h1 class="text-2xl sm:text-3xl font-bold text-stone-900 font-jost tracking-tight mb-3">
                    {{ $product->name }}
                </h1>

                <!-- Price Area -->
                <div class="flex items-baseline gap-3 mb-6 pb-6 border-b border-rose-100">
                    @if($product->price === null)
                        <span class="text-xl font-semibold text-gold tracking-wider uppercase">Price on request</span>
                    @elseif($product->has_discount)
                        <span class="text-3xl font-bold text-maroon font-jost">{{ $product->formatted_discount_price }}</span>
                        <span class="text-lg text-stone-400 line-through">{{ $product->formatted_price }}</span>
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-maroon">
                            Save {{ $product->discount_percentage }}%
                        </span>
                    @else
                        <span class="text-3xl font-bold text-stone-900 font-jost">{{ $product->formatted_price }}</span>
                    @endif

                    @if($product->price_note)
                        <span class="text-xs text-stone-500 italic block mt-1">({{ $product->price_note }})</span>
                    @endif
                </div>

                <!-- Availability & Colour -->
                <div class="grid grid-cols-2 gap-4 mb-6 text-xs">
                    <div class="bg-brand-pearl p-3 rounded-lg border border-rose-100/60">
                        <span class="text-stone-500 block mb-0.5">Availability</span>
                        <span class="font-semibold {{ $product->is_sold_out ? 'text-stone-500' : 'text-emerald-700' }}">
                            {{ $product->availability->label() }}
                        </span>
                    </div>

                    <div class="bg-brand-pearl p-3 rounded-lg border border-rose-100/60">
                        <span class="text-stone-500 block mb-0.5">Primary Colour</span>
                        <span class="font-semibold text-stone-800">{{ $product->colour ?? 'Traditional Silk Shade' }}</span>
                    </div>
                </div>

                <!-- Short Description -->
                @if($product->short_description)
                    <p class="text-sm text-stone-600 leading-relaxed mb-6 font-normal">
                        {{ $product->short_description }}
                    </p>
                @endif

                <!-- Large Pill CTAs -->
                <div class="space-y-3 mb-8">
                    <!-- WhatsApp Enquiry Button -->
                    <button type="button"
                            wire:click="openEnquiryModal"
                            class="btn-pill btn-whatsapp w-full py-3.5 px-6 text-sm tracking-wide font-semibold flex items-center justify-center gap-2.5 shadow-md hover:shadow-lg transition-transform active:scale-95">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.587-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z"/>
                        </svg>
                        <span>Enquire on WhatsApp</span>
                    </button>

                    <div class="flex gap-3">
                        <!-- Wishlist Button -->
                        <button type="button"
                                wire:click="toggleWishlist"
                                class="btn-pill flex-1 py-3 px-4 text-xs font-semibold tracking-wider uppercase border border-rose-200 bg-white hover:bg-rose-50/50 text-stone-700 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 {{ $isWishlisted ? 'text-maroon fill-maroon' : 'text-stone-400' }}" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span>{{ $isWishlisted ? 'Saved in Wishlist' : 'Add to Wishlist' }}</span>
                        </button>

                        <!-- Live Video Appointment -->
                        <a href="{{ route('video-shopping') }}" class="btn-pill flex-1 py-3 px-4 text-xs font-semibold tracking-wider uppercase border border-gold/40 bg-gold-light/40 hover:bg-gold-light text-stone-800 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 text-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            <span>Video View</span>
                        </a>
                    </div>
                </div>

                <!-- Handloom Guarantee Notice -->
                <div class="bg-rose-50/60 rounded-lg p-3.5 border border-rose-100 flex items-start gap-3 mb-6">
                    <svg class="w-5 h-5 text-maroon shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <div class="text-xs text-stone-700 leading-relaxed">
                        <p class="font-semibold text-stone-900">100% Authentic Handloom Silk Guarantee</p>
                        <p class="text-stone-500 mt-0.5">Woven in Tamil Nadu handloom clusters. Delivered directly from our Peelamedu, Coimbatore flagship.</p>
                    </div>
                </div>

                <!-- Specifications Accordion -->
                <div class="border-t border-rose-100 divide-y divide-rose-100" x-data="{ openTab: 'specs' }">
                    <!-- Tab 1: Product Specifications -->
                    <div class="py-3">
                        <button type="button" @click="openTab = openTab === 'specs' ? '' : 'specs'" class="w-full flex items-center justify-between text-left text-xs font-semibold uppercase tracking-wider text-stone-800">
                            <span>Saree Specifications</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="openTab === 'specs' ? 'rotate-180 text-maroon' : 'text-stone-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="openTab === 'specs'" x-transition class="mt-3 text-xs text-stone-600 space-y-2">
                            @if(!empty($product->specs))
                                <dl class="grid grid-cols-2 gap-2">
                                    @foreach($product->specs as $key => $val)
                                        <dt class="text-stone-400 capitalize">{{ str_replace('_', ' ', $key) }}:</dt>
                                        <dd class="font-medium text-stone-800">{{ $val }}</dd>
                                    @endforeach
                                </dl>
                            @else
                                <p>Zari: Certified Tested Zari • Blouse: Included • Care: Dry Clean Only</p>
                            @endif
                        </div>
                    </div>

                    <!-- Tab 2: Craftsmanship & Heritage -->
                    <div class="py-3">
                        <button type="button" @click="openTab = openTab === 'heritage' ? '' : 'heritage'" class="w-full flex items-center justify-between text-left text-xs font-semibold uppercase tracking-wider text-stone-800">
                            <span>Craftsmanship & Origin</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="openTab === 'heritage' ? 'rotate-180 text-maroon' : 'text-stone-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="openTab === 'heritage'" x-transition class="mt-3 text-xs text-stone-600 leading-relaxed space-y-2" style="display: none;">
                            <p>Hand-woven using the traditional Korvai interlock technique where border and body warp threads are woven seamlessly together. Requires two master artisans working in synchronicity.</p>
                            <p>Shipped directly from Peelamedu, Coimbatore with Silk Mark certification tag.</p>
                        </div>
                    </div>

                    <!-- Tab 3: Detailed Description -->
                    @if($product->description)
                        <div class="py-3">
                            <button type="button" @click="openTab = openTab === 'desc' ? '' : 'desc'" class="w-full flex items-center justify-between text-left text-xs font-semibold uppercase tracking-wider text-stone-800">
                                <span>Weaver's Description</span>
                                <svg class="w-4 h-4 transition-transform duration-200" :class="openTab === 'desc' ? 'rotate-180 text-maroon' : 'text-stone-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="openTab === 'desc'" x-transition class="mt-3 text-xs text-stone-600 leading-relaxed prose prose-stone max-w-none" style="display: none;">
                                {!! clean($product->description) !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Related Sarees Section -->
    @if($relatedProducts->count() > 0)
        <div class="mt-20 pt-12 border-t border-rose-100">
            <div class="text-center mb-8">
                <span class="text-xs uppercase tracking-widest text-gold font-semibold">Curated Pairings</span>
                <h2 class="text-2xl font-bold text-stone-900 font-jost mt-1">You May Also Admire</h2>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)
                    <livewire:product-card :product="$related" :key="'related-'.$related->id" />
                @endforeach
            </div>
        </div>
    @endif

    <!-- Fullscreen Lightbox Modal -->
    @if($lightboxOpen && $activeImage)
        <div class="fixed inset-0 z-50 bg-black/95 flex items-center justify-center p-4 sm:p-8"
             @keydown.escape.window="$wire.set('lightboxOpen', false)">
            <button type="button"
                    wire:click="$set('lightboxOpen', false)"
                    class="absolute top-6 right-6 text-white/80 hover:text-white p-2 rounded-full bg-white/10 hover:bg-white/20 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="max-w-4xl max-h-full overflow-hidden flex flex-col items-center">
                <img src="{{ $activeImage->large_url }}" alt="{{ $product->name }} fullscreen" class="max-h-[85vh] w-auto object-contain rounded-lg shadow-2xl">
                <div class="mt-4 text-center text-white/80 text-sm">
                    <p class="font-semibold">{{ $product->name }} ({{ $product->sku }})</p>
                </div>
            </div>
        </div>
    @endif

    <!-- WhatsApp Enquiry Modal -->
    @if($enquiryModalOpen)
        <div class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4"
             @keydown.escape.window="$wire.closeEnquiryModal">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-rose-100 relative">
                <button type="button"
                        wire:click="closeEnquiryModal"
                        class="absolute top-4 right-4 text-stone-400 hover:text-stone-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <div class="text-center mb-5">
                    <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.587-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-stone-900">Enquire on WhatsApp</h3>
                    <p class="text-xs text-stone-500 mt-1">Direct concierge connection with our Peelamedu showroom staff.</p>
                </div>

                <div class="bg-brand-pearl p-3 rounded-lg mb-4 text-xs border border-rose-100 flex items-center gap-3">
                    <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="w-12 h-15 object-cover rounded">
                    <div>
                        <p class="font-semibold text-stone-800">{{ $product->name }}</p>
                        <p class="text-[10px] text-stone-500">SKU: {{ $product->sku }}</p>
                        <p class="text-xs font-bold text-maroon">{{ $product->formatted_price }}</p>
                    </div>
                </div>

                <form wire:submit="submitEnquiry" class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-stone-700 mb-1">Your Name (Optional)</label>
                        <input type="text" wire:model="customerName" placeholder="Enter your name" class="w-full text-xs px-3 py-2 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-stone-700 mb-1">Your Phone / WhatsApp (Optional)</label>
                        <input type="text" wire:model="customerPhone" placeholder="+91 98765 43210" class="w-full text-xs px-3 py-2 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
                    </div>

                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="btn-pill btn-whatsapp w-full py-3 text-xs tracking-wider uppercase font-semibold flex items-center justify-center gap-2 mt-4 shadow-md">
                        <span>Continue to WhatsApp</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
