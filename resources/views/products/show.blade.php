@extends('layouts.app')

@section('title', $product->name . ' (SKU: ' . $product->sku . ') – Iswarya Silks')
@section('meta_description', Str::limit(strip_tags($product->short_description ?? $product->name . ' handcrafted pure silk saree from Iswarya Silks, Peelamedu, Coimbatore.'), 160))
@section('canonical', route('products.show', $product->slug))
@section('og_image', $product->primary_image_url)

@section('structured_data')
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "{{ addslashes($product->name) }}",
  "image": [
    "{{ $product->primary_image_url }}"
  ],
  "description": "{{ addslashes(strip_tags($product->short_description ?? $product->name)) }}",
  "sku": "{{ $product->sku }}",
  "brand": {
    "@type": "Brand",
    "name": "Iswarya Silks"
  },
  "offers": {
    "@type": "Offer",
    "url": "{{ route('products.show', $product->slug) }}",
    "priceCurrency": "INR",
    "price": "{{ $product->effective_price ?? 0 }}",
    "availability": "{{ $product->is_sold_out ? 'https://schema.org/OutOfStock' : 'https://schema.org/InStock' }}",
    "itemCondition": "https://schema.org/NewCondition",
    "seller": {
      "@type": "Organization",
      "name": "Iswarya Silks – Peelamedu, Coimbatore"
    }
  }
}
</script>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <nav class="flex text-xs text-stone-500 py-4 border-b border-rose-100" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li><a href="{{ route('home') }}" class="hover:text-maroon">Home</a></li>
                <li><span class="text-stone-300">/</span></li>
                @if($primaryCategory)
                    <li>
                        <a href="{{ route('categories.show', ['group' => $primaryCategory->group->value, 'slug' => $primaryCategory->slug]) }}" class="hover:text-maroon capitalize">
                            {{ $primaryCategory->name }}
                        </a>
                    </li>
                    <li><span class="text-stone-300">/</span></li>
                @endif
                <li class="font-semibold text-stone-800 truncate max-w-xs">{{ $product->name }}</li>
            </ol>
        </nav>

        <!-- Livewire Product Detail Interactive Page -->
        <livewire:product-detail-page :product="$product" :related-products="$relatedProducts" />
    </div>
@endsection
