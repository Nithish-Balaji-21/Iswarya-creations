<?php

namespace App\Livewire;

use App\Enums\EnquirySource;
use App\Models\Product;
use App\Models\Wishlist;
use App\Services\Enquiry\EnquiryService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProductCard extends Component
{
    public Product $product;

    public bool $isWishlisted = false;

    public string $source = 'card';

    public function mount(Product $product, string $source = 'card'): void
    {
        $this->product = $product;
        $this->source = $source;

        if (Auth::guard('web')->check()) {
            $this->isWishlisted = Wishlist::where('user_id', Auth::guard('web')->id())
                ->where('product_id', $product->id)
                ->exists();
        }
    }

    public function toggleWishlist(): void
    {
        if (! Auth::guard('web')->check()) {
            $this->redirectRoute('login');

            return;
        }

        $userId = Auth::guard('web')->id();
        $existing = Wishlist::where('user_id', $userId)
            ->where('product_id', $this->product->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $this->isWishlisted = false;
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $this->product->id,
            ]);
            $this->isWishlisted = true;
        }

        $this->dispatch('wishlist-updated');
    }

    public function enquireWhatsApp(EnquiryService $enquiryService)
    {
        $result = $enquiryService->createAndBuildWhatsAppUrl(
            product: $this->product,
            source: EnquirySource::Card,
            request: request()
        );

        return $this->redirect($result['whatsapp_url']);
    }

    public function render()
    {
        return view('livewire.product-card');
    }
}
