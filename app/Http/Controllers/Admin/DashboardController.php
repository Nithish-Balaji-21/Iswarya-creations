<?php

namespace App\Http\Controllers\Admin;

use App\Enums\EnquiryStatus;
use App\Enums\ProductAvailability;
use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Enquiry;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $activeSareesCount = Product::where('status', ProductStatus::Published)
            ->where('availability', ProductAvailability::Available)
            ->count();

        $newEnquiriesCount = Enquiry::where('status', EnquiryStatus::New)->count();

        $thisMonthEnquiriesCount = Enquiry::where('created_at', '>=', now()->startOfMonth())->count();

        $categoriesCount = Category::active()->count();

        $recentEnquiries = Enquiry::with('product')
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'activeSareesCount',
            'newEnquiriesCount',
            'thisMonthEnquiriesCount',
            'categoriesCount',
            'recentEnquiries'
        ));
    }
}
