<?php

use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminBannerController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminNewsletterController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminVideoAppointmentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VideoShoppingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Iswarya Silks – Public Storefront Routes
|--------------------------------------------------------------------------
| Pure luxury brand showcase with WhatsApp enquiry conversion.
| No cart, checkout, or orders.
*/

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// New Arrivals (auto-filtered by published_at desc)
Route::get('/new-arrivals', [ProductController::class, 'newArrivals'])->name('products.new-arrivals');

// Category page constrained to valid groups: weave | fabric | occasion | kanjeevaram
Route::get('/{group}/{slug}', [ProductController::class, 'category'])
    ->where('group', 'weave|fabric|occasion|kanjeevaram')
    ->name('categories.show');

// Product Detail page /product/{slug}
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');

// WhatsApp Enquiry Redirect (server-side snapshot + wa.me open)
Route::match(['get', 'post'], '/enquire/{product}', [EnquiryController::class, 'enquire'])->name('enquiry.redirect');

// Video Shopping Consultation
Route::get('/video-shopping', [VideoShoppingController::class, 'show'])->name('video-shopping');
Route::post('/video-shopping', [VideoShoppingController::class, 'store'])->name('video-shopping.store');

// Newsletter Subscription
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Static / Heritage Pages
Route::get('/about', [PageController::class, 'about'])->name('pages.about');
Route::get('/contact', [PageController::class, 'contact'])->name('pages.contact');

// SEO: Sitemap & Robots
Route::get('/sitemap.xml', [PageController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [PageController::class, 'robots'])->name('robots');

/*
|--------------------------------------------------------------------------
| Customer Authentication Routes (Fortify / Custom OTP)
|--------------------------------------------------------------------------
*/

Route::middleware('guest:web')->group(function () {
    Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [CustomerAuthController::class, 'loginWithPassword'])->name('auth.login.password');

    Route::post('/auth/otp/send', [CustomerAuthController::class, 'sendOtp'])->name('auth.otp.send');
    Route::get('/auth/otp/verify', [CustomerAuthController::class, 'showVerifyOtp'])->name('auth.otp.verify');
    Route::post('/auth/otp/verify', [CustomerAuthController::class, 'verifyOtp'])->name('auth.otp.verify.submit');

    Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [CustomerAuthController::class, 'register'])->name('auth.register');

    Route::get('/forgot-password', [CustomerAuthController::class, 'showForgotPassword'])->name('auth.forgot-password');
    Route::get('/reset-password', [CustomerAuthController::class, 'showResetPassword'])->name('auth.password.reset');
    Route::post('/reset-password', [CustomerAuthController::class, 'resetPassword'])->name('auth.password.reset.submit');
});

Route::post('/logout', [CustomerAuthController::class, 'logout'])->middleware('auth:web')->name('logout');

/*
|--------------------------------------------------------------------------
| Customer Account Area (Customer Auth Only)
|--------------------------------------------------------------------------
*/

Route::prefix('account')->middleware('auth:web')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'dashboard'])->name('dashboard');
    Route::get('/wishlist', [AccountController::class, 'wishlist'])->name('wishlist');
    Route::post('/wishlist/{productId}', [AccountController::class, 'toggleWishlist'])->name('wishlist.toggle');
    Route::get('/enquiries', [AccountController::class, 'enquiries'])->name('enquiries');
    Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
    Route::put('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Admin Portal Authentication Routes
|--------------------------------------------------------------------------
| Separate login flow with 5-attempt/15-minute brute-force lockout.
*/

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::get('/forced-password-change', [AdminAuthController::class, 'showChangePasswordForm'])->name('admin.password.force');
    Route::post('/forced-password-change', [AdminAuthController::class, 'updatePassword'])->name('admin.password.update');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

/*
|--------------------------------------------------------------------------
| Protected Admin Portal Routes
|--------------------------------------------------------------------------
| Guarded by 'admin' middleware and dedicated session.
*/

Route::prefix('admin')->middleware(['web', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Sarees / Products (fast daily management)
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/{product}/toggle-availability', [AdminProductController::class, 'toggleAvailability'])->name('products.toggle-availability');
    Route::post('/products/{product}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('products.toggle-status');
    Route::post('/products/{product}/images/{image}/primary', [AdminProductController::class, 'setPrimaryImage'])->name('products.images.primary');
    Route::delete('/products/{product}/images/{image}', [AdminProductController::class, 'deleteImage'])->name('products.images.delete');

    // Categories
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::post('/categories/{category}/toggle-homepage', [AdminCategoryController::class, 'toggleHomepage'])->name('categories.toggle-homepage');
    Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    // Customer WhatsApp Enquiries
    Route::get('/enquiries', function () {
        return view('admin.enquiries.index');
    })->name('enquiries.index');

    // Hero Banners
    Route::get('/banners', [AdminBannerController::class, 'index'])->name('banners.index');
    Route::get('/banners/create', [AdminBannerController::class, 'create'])->name('banners.create');
    Route::post('/banners', [AdminBannerController::class, 'store'])->name('banners.store');
    Route::get('/banners/{banner}/edit', [AdminBannerController::class, 'edit'])->name('banners.edit');
    Route::put('/banners/{banner}', [AdminBannerController::class, 'update'])->name('banners.update');
    Route::delete('/banners/{banner}', [AdminBannerController::class, 'destroy'])->name('banners.destroy');

    // Video Shopping Appointments
    Route::get('/appointments', [AdminVideoAppointmentController::class, 'index'])->name('appointments.index');
    Route::put('/appointments/{appointment}/status', [AdminVideoAppointmentController::class, 'updateStatus'])->name('appointments.status');

    // Newsletter Subscribers
    Route::get('/newsletter', [AdminNewsletterController::class, 'index'])->name('newsletter.index');

    // Storefront & WhatsApp Settings
    Route::get('/settings', [AdminSettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
});
