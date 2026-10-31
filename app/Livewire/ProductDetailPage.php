<?php

namespace App\Livewire;

use App\Enums\EnquirySource;
use App\Models\Product;
use App\Models\Wishlist;
use App\Services\Enquiry\EnquiryService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProductDetailPage extends Component
{
    public Product $product;

    public Collection $relatedProducts;

    public int $selectedImageIndex = 0;

    public bool $lightboxOpen = false;

    public bool $isWishlisted = false;

    public bool $enquiryModalOpen = false;

    public string $customerName = '';

    public string $customerPhone = '';

    public string $customMessage = '';

    public function mount(Product $product, Collection $relatedProducts): void
    {
        $this->product = $product;
        $this->relatedProducts = $relatedProducts;

        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $this->customerName = $user->name;
            $this->customerPhone = $user->phone ?? '';
            $this->isWishlisted = Wishlist::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->exists();
        }
    }

    public function selectImage(int $index): void
    {
        $this->selectedImageIndex = $index;
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

    public function openEnquiryModal(): void
    {
        $this->enquiryModalOpen = true;
    }

    public function closeEnquiryModal(): void
    {
        $this->enquiryModalOpen = false;
    }

    public function submitEnquiry(EnquiryService $enquiryService)
    {
        $result = $enquiryService->createAndBuildWhatsAppUrl(
            product: $this->product,
            source: EnquirySource::ProductPage,
            request: request(),
            customerName: ! empty($this->customerName) ? $this->customerName : null,
            customerPhone: ! empty($this->customerPhone) ? $this->customerPhone : null
        );

        $this->enquiryModalOpen = false;

        return $this->redirect($result['whatsapp_url']);
    }

    public function render()
    {
        $images = $this->product->images;
        $activeImage = $images->get($this->selectedImageIndex) ?? $images->first();

        return view('livewire.product-detail-page', compact('images', 'activeImage'));
    }
}
