<?php

namespace App\Services\Enquiry;

use App\Enums\EnquirySource;
use App\Enums\EnquiryStatus;
use App\Models\Enquiry;
use App\Models\Product;
use App\Models\Setting;
use App\Support\IndianCurrency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class EnquiryService
{
    /**
     * Create an enquiry record server-side and generate the wa.me redirection URL.
     *
     * @return array{enquiry: Enquiry, whatsapp_url: string}
     */
    public function createAndBuildWhatsAppUrl(
        Product $product,
        EnquirySource $source = EnquirySource::ProductPage,
        ?Request $request = null,
        ?string $customerName = null,
        ?string $customerPhone = null
    ): array {
        $user = Auth::guard('web')->user();

        // Rate limiting: 10 enquiries per 10 minutes per IP/user
        $key = 'enquiry:'.($user ? "user-{$user->id}" : ($request ? $request->ip() : 'global'));
        RateLimiter::hit($key, 600);

        $name = $customerName ?? ($user ? $user->name : null);
        $phone = $customerPhone ?? ($user ? $user->phone : null);
        $pageUrl = $request ? $request->header('referer', url("/product/{$product->slug}")) : url("/product/{$product->slug}");

        // Snapshot current product price (discount price if active, else regular price)
        $snapshotPrice = $product->discount_price ?? $product->price;

        $enquiry = Enquiry::create([
            'user_id' => $user?->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_sku' => $product->sku,
            'product_price' => $snapshotPrice,
            'customer_name' => $name,
            'customer_phone' => $phone,
            'source' => $source,
            'page_url' => $pageUrl,
            'status' => EnquiryStatus::New,
        ]);

        $whatsAppNumber = Setting::whatsappNumber();

        // Build WhatsApp message text:
        // "Hello Iswarya Silks, I would like to enquire about {name} (SKU: {sku}) listed at ₹{price}. Link: {url}"
        // If price is null, omit the "listed at" clause.
        $message = "Hello Iswarya Silks, I would like to enquire about {$product->name} (SKU: {$product->sku})";

        if ($snapshotPrice !== null) {
            $formattedPrice = IndianCurrency::format((float) $snapshotPrice);
            $message .= " listed at {$formattedPrice}";
        }

        $message .= ". Link: {$pageUrl}";

        // Include high-resolution saree image link for WhatsApp rich media preview and showroom visual matching
        $imageUrl = $product->primary_image_url;
        if ($imageUrl) {
            $message .= " | Image: {$imageUrl}";
        }

        // Normalize whatsapp number (strip non-digits)
        $cleanNumber = preg_replace('/[^0-9]/', '', $whatsAppNumber);
        $whatsAppUrl = "https://wa.me/{$cleanNumber}?text=".rawurlencode($message);

        return [
            'enquiry' => $enquiry,
            'whatsapp_url' => $whatsAppUrl,
        ];
    }
}
