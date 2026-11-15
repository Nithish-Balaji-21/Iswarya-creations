<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProductAvailability;
use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Enquiry;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalSareesCount = Product::count();

        $activeSareesCount = Product::where('status', ProductStatus::Published)
            ->where('availability', ProductAvailability::Available)
            ->count();

        $soldOutCount = Product::where('availability', ProductAvailability::SoldOut)->count();
        $draftSareesCount = Product::where('status', ProductStatus::Draft)->count();

        $categoriesCount = Category::active()->count();
        $bannersCount = Banner::count();

        // Keep for backwards-compatible test assertions
        $newEnquiriesCount = Enquiry::where('status', \App\Enums\EnquiryStatus::New)->count();
        $thisMonthEnquiriesCount = Enquiry::where('created_at', '>=', now()->startOfMonth())->count();

        $recentSarees = Product::with(['primaryImage', 'categories'])
            ->latest()
            ->take(8)
            ->get();

        $storeSettings = [
            'whatsapp_number' => Setting::whatsappNumber(),
            'store_address' => Setting::storeAddress(),
            'google_maps_url' => Setting::googleMapsUrl(),
            'announcement_text' => Setting::announcementText(),
            'announcement_enabled' => Setting::announcementEnabled(),
            'showroom_timings' => Setting::showroomTimings(),
        ];

        return view('admin.dashboard', compact(
            'totalSareesCount',
            'activeSareesCount',
            'soldOutCount',
            'draftSareesCount',
            'categoriesCount',
            'bannersCount',
            'newEnquiriesCount',
            'thisMonthEnquiriesCount',
            'recentSarees',
            'storeSettings'
        ));
    }
}
