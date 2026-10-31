<?php

namespace App\Http\Controllers;

use App\Enums\EnquirySource;
use App\Models\Product;
use App\Services\Enquiry\EnquiryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class EnquiryController extends Controller
{
    public function __construct(
        protected EnquiryService $enquiryService
    ) {}

    /**
     * Handle "Enquire on WhatsApp" tap:
     * 1. Create enquiry record server-side (snapshotting name, SKU, price)
     * 2. Redirect to WhatsApp (or return URL for JSON clients)
     */
    public function enquire(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        $key = 'enquiry-rate:'.($request->user()?->id ?? $request->ip());
        if (RateLimiter::tooManyAttempts($key, 12)) {
            $seconds = RateLimiter::availableIn($key);
            if ($request->wantsJson()) {
                return response()->json(['error' => "Please wait {$seconds} seconds before enquiring again."], 429);
            }

            return back()->withErrors(['enquiry' => 'Please wait a moment before sending another enquiry.']);
        }
        RateLimiter::hit($key, 60);

        $sourceStr = $request->input('source', EnquirySource::ProductPage->value);
        $source = EnquirySource::tryFrom($sourceStr) ?? EnquirySource::ProductPage;

        $customerName = $request->input('customer_name');
        $customerPhone = $request->input('customer_phone');

        $result = $this->enquiryService->createAndBuildWhatsAppUrl(
            product: $product,
            source: $source,
            request: $request,
            customerName: $customerName,
            customerPhone: $customerPhone
        );

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'enquiry_id' => $result['enquiry']->id,
                'whatsapp_url' => $result['whatsapp_url'],
            ]);
        }

        return redirect()->away($result['whatsapp_url']);
    }
}
