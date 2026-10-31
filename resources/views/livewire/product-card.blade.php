<div class="luxury-card group relative flex flex-col overflow-hidden bg-white border border-rose-100/70 hover:border-maroon/30 transition-all duration-300">
    <!-- Image Area with Hover-Swap -->
    <div class="relative w-full aspect-[4/5] overflow-hidden bg-brand-pearl">
        <a href="{{ route('products.show', $product->slug) }}" class="block w-full h-full">
            <!-- Primary Image -->
            <img src="{{ $product->primary_image_url }}"
                 alt="{{ $product->name }}"
                 loading="lazy"
                 class="w-full h-full object-cover object-center luxury-card-img-zoom transition-opacity duration-500 {{ $product->secondary_image_url !== $product->primary_image_url ? 'group-hover:opacity-0' : '' }}">
            
            <!-- Secondary Image on Hover (if available) -->
            @if($product->secondary_image_url !== $product->primary_image_url)
                <img src="{{ $product->secondary_image_url }}"
                     alt="{{ $product->name }} alternate view"
                     loading="lazy"
                     class="absolute inset-0 w-full h-full object-cover object-center luxury-card-img-zoom opacity-0 group-hover:opacity-100 transition-opacity duration-500">
            @endif
        </a>

        <!-- Badges (Top Left) -->
        <div class="absolute top-3 left-3 flex flex-col gap-1.5 z-10 pointer-events-none">
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold tracking-wider uppercase bg-white/95 text-maroon shadow-xs backdrop-blur-xs border border-rose-100">
                Pure Silk
            </span>

            @if($product->is_sold_out)
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold tracking-wider uppercase bg-stone-900/90 text-white shadow-xs">
                    Sold Out
                </span>
            @elseif($product->has_discount)
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold tracking-wider uppercase bg-gold text-white shadow-xs">
                    {{ $product->discount_percentage }}% OFF
                </span>
            @endif
        </div>

        <!-- Wishlist Button (Top Right) -->
        <button type="button"
                wire:click="toggleWishlist"
                class="absolute top-3 right-3 p-2 rounded-full bg-white/90 hover:bg-white text-stone-700 shadow-sm transition-all duration-200 z-10 hover:scale-110 focus:outline-hidden"
                title="{{ $isWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
            <svg class="w-4 h-4 {{ $isWishlisted ? 'text-maroon fill-maroon' : 'text-stone-500 hover:text-maroon' }}" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </button>

        <!-- Quick View Overlay on Desktop -->
        <div class="absolute inset-x-3 bottom-3 opacity-0 group-hover:opacity-100 transition-opacity duration-200 hidden sm:block z-10">
            <a href="{{ route('products.show', $product->slug) }}" class="btn-pill w-full py-2 bg-white/95 hover:bg-white text-maroon text-xs tracking-wider uppercase font-semibold shadow-md text-center backdrop-blur-xs transition-colors">
                View Saree Details
            </a>
        </div>
    </div>

    <!-- Product Meta -->
    <div class="p-4 flex flex-col flex-1 justify-between">
        <div>
            <div class="flex items-center justify-between text-[11px] text-stone-500 mb-1">
                <span>SKU: {{ $product->sku }}</span>
                @if($product->colour)
                    <span class="truncate max-w-[120px]">{{ $product->colour }}</span>
                @endif
            </div>

            <h3 class="text-sm font-semibold text-brand-text group-hover:text-maroon transition-colors line-clamp-1 mb-1.5 font-jost">
                <a href="{{ route('products.show', $product->slug) }}">
                    {{ $product->name }}
                </a>
            </h3>

            <!-- Price -->
            <div class="flex items-baseline gap-2 mb-3">
                @if($product->price === null)
                    <span class="text-xs font-semibold text-gold tracking-wide uppercase">Price on request</span>
                @elseif($product->has_discount)
                    <span class="text-base font-bold text-maroon">{{ $product->formatted_discount_price }}</span>
                    <span class="text-xs text-stone-400 line-through">{{ $product->formatted_price }}</span>
                @else
                    <span class="text-base font-bold text-stone-900">{{ $product->formatted_price }}</span>
                @endif
            </div>
        </div>

        <!-- WhatsApp Enquiry CTA -->
        <div class="pt-2 border-t border-rose-50">
            <button type="button"
                    wire:click="enquireWhatsApp"
                    wire:loading.attr="disabled"
                    class="btn-pill btn-whatsapp w-full py-2 px-3 text-xs tracking-wide font-medium flex items-center justify-center gap-2 shadow-xs transition-transform active:scale-95">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.587-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z"/>
                </svg>
                <span>Enquire on WhatsApp</span>
            </button>
        </div>
    </div>
</div>
