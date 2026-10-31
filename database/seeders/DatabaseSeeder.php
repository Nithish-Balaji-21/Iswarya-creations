<?php

namespace Database\Seeders;

use App\Enums\CategoryGroup;
use App\Enums\EnquirySource;
use App\Enums\EnquiryStatus;
use App\Enums\ProductAvailability;
use App\Enums\ProductStatus;
use App\Enums\UserRole;
use App\Enums\VideoAppointmentStatus;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Enquiry;
use App\Models\NewsletterSubscriber;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Setting;
use App\Models\User;
use App\Models\VideoAppointment;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with luxury demo data.
     */
    public function run(): void
    {
        // 0. Ensure WebP assets exist
        AssetGenerator::generateAll();

        // 1. Settings
        $settings = [
            'whatsapp_number' => '918973948912',
            'store_address' => '124, Avinashi Road, Peelamedu, Coimbatore - 641004, Tamil Nadu, India',
            'instagram_url' => 'https://www.instagram.com/iswaryasilks/',
            'announcement_text' => 'Free Express Shipping Across India | Handloom Certified Pure Silk',
            'contact_phone' => '+91 89739 48912',
            'contact_email' => 'concierge@iswaryasilks.com',
        ];
        foreach ($settings as $k => $v) {
            Setting::updateOrCreate(['key' => $k], ['value' => $v]);
        }

        // 2. Default Admin User (must_change_password = true, credentials from .env)
        $adminEmail = env('ADMIN_DEFAULT_EMAIL', 'admin@iswaryasilks.com');
        $adminPassword = env('ADMIN_DEFAULT_PASSWORD', 'Iswarya@Admin2026');

        $admin = User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Iswarya Silks Admin',
                'phone' => '+918973948912',
                'password' => Hash::make($adminPassword),
                'role' => UserRole::Admin,
                'must_change_password' => true,
                'failed_login_attempts' => 0,
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
            ]
        );

        // 3. Demo Customer User
        $customer = User::updateOrCreate(
            ['email' => 'priya@example.com'],
            [
                'name' => 'Priya Ramanathan',
                'phone' => '+919876543210',
                'password' => Hash::make('Secret123!'),
                'role' => UserRole::Customer,
                'must_change_password' => false,
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
            ]
        );

        // 4. Categories (Weave, Fabric, Occasion, Kanjeevaram)
        $categoriesData = [
            // Kanjeevaram Group
            [
                'name' => 'Bridal Kanjeevaram',
                'slug' => 'bridal-kanjeevaram',
                'group' => CategoryGroup::Kanjeevaram,
                'description' => 'Heavy handloom pure silk bridal sarees with authentic gold and silver tested zari.',
                'cover_image' => 'categories/bridal-kanjeevaram-cover.webp',
                'banner_image' => 'categories/bridal-kanjeevaram-banner.webp',
                'show_on_homepage' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Traditional Korvai',
                'slug' => 'traditional-korvai',
                'group' => CategoryGroup::Kanjeevaram,
                'description' => 'Contrasting body and pallu linked using ancient two-shuttle handloom interlocking.',
                'cover_image' => 'categories/traditional-korvai-cover.webp',
                'banner_image' => 'categories/traditional-korvai-banner.webp',
                'show_on_homepage' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Pure Gold Zari Kanjeevaram',
                'slug' => 'pure-gold-zari-kanjeevaram',
                'group' => CategoryGroup::Kanjeevaram,
                'description' => 'Collector edition sarees spun with certified pure tested gold zari threads.',
                'cover_image' => 'categories/pure-gold-zari-kanjeevaram-cover.webp',
                'banner_image' => 'categories/pure-gold-zari-kanjeevaram-banner.webp',
                'show_on_homepage' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Tissue Kanjeevaram',
                'slug' => 'tissue-kanjeevaram',
                'group' => CategoryGroup::Kanjeevaram,
                'description' => 'Lustrous metallic zari warp woven into silk for royal receptions and sangeet night.',
                'cover_image' => 'categories/tissue-kanjeevaram-cover.webp',
                'banner_image' => 'categories/tissue-kanjeevaram-banner.webp',
                'show_on_homepage' => true,
                'sort_order' => 4,
            ],

            // Weave Group
            [
                'name' => 'Korvai Handloom',
                'slug' => 'korvai-handloom',
                'group' => CategoryGroup::Weave,
                'description' => 'Masterful shuttle interlocking creating razor-sharp contrasting borders without printed lines.',
                'cover_image' => 'categories/korvai-handloom-cover.webp',
                'banner_image' => 'categories/korvai-handloom-banner.webp',
                'show_on_homepage' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Jacquard Brocade',
                'slug' => 'jacquard-brocade',
                'group' => CategoryGroup::Weave,
                'description' => 'Intricate botanical and peacock motifs woven across the entire drape with dense zari.',
                'cover_image' => 'categories/jacquard-brocade-cover.webp',
                'banner_image' => 'categories/jacquard-brocade-banner.webp',
                'show_on_homepage' => false,
                'sort_order' => 6,
            ],

            // Fabric Group
            [
                'name' => 'Mulberry Pure Silk',
                'slug' => 'mulberry-pure-silk',
                'group' => CategoryGroup::Fabric,
                'description' => 'Grade-A pure Mulberry silk certified by the Silk Mark Organisation of India.',
                'cover_image' => 'categories/mulberry-pure-silk-cover.webp',
                'banner_image' => 'categories/mulberry-pure-silk-banner.webp',
                'show_on_homepage' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Soft Silk (Lightweight)',
                'slug' => 'soft-silk',
                'group' => CategoryGroup::Fabric,
                'description' => 'Breathable, featherweight pure silks designed for ease, modern drape, and long celebrations.',
                'cover_image' => 'categories/soft-silk-cover.webp',
                'banner_image' => 'categories/soft-silk-banner.webp',
                'show_on_homepage' => true,
                'sort_order' => 8,
            ],
            [
                'name' => 'Organza Kanjeevaram',
                'slug' => 'organza-kanjeevaram',
                'group' => CategoryGroup::Fabric,
                'description' => 'Sheer pure silk organza woven with traditional heavy Kanjeevaram zari borders.',
                'cover_image' => 'categories/organza-kanjeevaram-cover.webp',
                'banner_image' => 'categories/organza-kanjeevaram-banner.webp',
                'show_on_homepage' => true,
                'sort_order' => 9,
            ],
            [
                'name' => 'Banarasi Pure Katan',
                'slug' => 'banarasi-pure-katan',
                'group' => CategoryGroup::Fabric,
                'description' => 'Finest Varanasi pure silk katan woven with intricate gold and silver kadwa motifs.',
                'cover_image' => 'categories/banarasi-pure-katan-cover.webp',
                'banner_image' => 'categories/banarasi-pure-katan-banner.webp',
                'show_on_homepage' => true,
                'sort_order' => 10,
            ],

            // Occasion Group
            [
                'name' => 'Muhurtham & Wedding',
                'slug' => 'muhurtham-wedding',
                'group' => CategoryGroup::Occasion,
                'description' => 'Regal bridal sarees featuring traditional auspicious colors, temple borders, and divine motifs.',
                'cover_image' => 'categories/muhurtham-wedding-cover.webp',
                'banner_image' => 'categories/muhurtham-wedding-banner.webp',
                'show_on_homepage' => true,
                'sort_order' => 11,
            ],
            [
                'name' => 'Reception & Sangeet',
                'slug' => 'reception-sangeet',
                'group' => CategoryGroup::Occasion,
                'description' => 'Contemporary glamor and tissue drapes that dazzle under grand reception lights.',
                'cover_image' => 'categories/reception-sangeet-cover.webp',
                'banner_image' => 'categories/reception-sangeet-banner.webp',
                'show_on_homepage' => true,
                'sort_order' => 12,
            ],
        ];

        $categoryModels = [];
        foreach ($categoriesData as $c) {
            $cat = Category::updateOrCreate(
                ['slug' => $c['slug']],
                array_merge($c, ['is_active' => true])
            );
            $categoryModels[$c['slug']] = $cat;
        }

        // 5. Banners
        $bannersData = [
            [
                'title' => 'Pure Kanjeevaram Bridal Silks',
                'eyebrow' => 'Peelamedu Flagship • Coimbatore',
                'subtitle' => 'Handwoven with certified pure mulberry silk and tested gold zari on generational pit-looms.',
                'link_url' => '/kanjeevaram/bridal-kanjeevaram',
                'button_text' => 'Explore Bridal Collection',
                'desktop_image' => 'banners/banner-1-desktop.webp',
                'mobile_image' => 'banners/banner-1-mobile.webp',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'The Korvai Masterpiece Edit',
                'eyebrow' => 'Timeless Craftsmanship',
                'subtitle' => 'Two-shuttle interlock weaving honoring Tamil Nadu’s purest handloom traditions.',
                'link_url' => '/weave/korvai-handloom',
                'button_text' => 'View Korvai Edit',
                'desktop_image' => 'banners/banner-2-desktop.webp',
                'mobile_image' => 'banners/banner-2-mobile.webp',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Featherweight Soft Silks',
                'eyebrow' => 'Modern Grace',
                'subtitle' => 'Contemporary motifs woven with airy, fluid pure silk for effortless all-day draping.',
                'link_url' => '/fabric/soft-silk',
                'button_text' => 'Discover Soft Silks',
                'desktop_image' => 'banners/banner-3-desktop.webp',
                'mobile_image' => 'banners/banner-3-mobile.webp',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($bannersData as $b) {
            Banner::updateOrCreate(['title' => $b['title']], $b);
        }

        // 6. ~12 Sample Sarees (Authentic handloom items)
        $sarees = [
            [
                'sku' => 'IC-0001',
                'name' => 'Peacock Blue Korvai Pure Kanjeevaram Silk Saree',
                'short_description' => 'A magnificent peacock blue body complemented by an auspicious crimson korvai border handwoven with tested gold zari.',
                'colour' => 'Peacock Blue',
                'price' => 48500.00,
                'discount_price' => 42500.00,
                'price_note' => 'Indicative loom price',
                'availability' => ProductAvailability::Available,
                'stock_quantity' => 1,
                'status' => ProductStatus::Published,
                'is_featured' => true,
                'view_count' => 142,
                'published_at' => now()->subDays(2),
                'categories' => ['bridal-kanjeevaram', 'korvai-handloom', 'mulberry-pure-silk', 'muhurtham-wedding'],
                'specs' => [
                    'zari_type' => 'Tested Pure Gold Zari (Half-Fine)',
                    'blouse_piece' => 'Crimson Red contrast silk (80 cm unstitched)',
                    'care' => 'Dry clean only. Store wrapped in pure unbleached muslin cloth.',
                    'weave_technique' => 'Traditional Korvai Shuttle Interlocking',
                ],
            ],
            [
                'sku' => 'IC-0002',
                'name' => 'Crimson Red Muhurtham Bridal Zari Saree',
                'short_description' => 'The ultimate Tamil bridal classic: deep auspicious crimson red woven with pure gold zari temple borders and mayil (peacock) motifs.',
                'colour' => 'Crimson Red',
                'price' => 68000.00,
                'discount_price' => 62000.00,
                'price_note' => 'Bridal trousseau cut',
                'availability' => ProductAvailability::Available,
                'stock_quantity' => 1,
                'status' => ProductStatus::Published,
                'is_featured' => true,
                'view_count' => 310,
                'published_at' => now()->subDays(4),
                'categories' => ['bridal-kanjeevaram', 'pure-gold-zari-kanjeevaram', 'mulberry-pure-silk', 'muhurtham-wedding'],
                'specs' => [
                    'zari_type' => 'Certified Pure Gold Zari (Silver wire gold plated)',
                    'blouse_piece' => 'Matching Crimson Red with heavy sleeve zari border',
                    'care' => 'Dry clean only. Air out in indirect shade twice yearly.',
                    'weave_technique' => 'Handloom Petni Border with Three-Ply Silk Warp',
                ],
            ],
            [
                'sku' => 'IC-0003',
                'name' => 'Mustard Gold Brocade Banarasi Katan Silk',
                'short_description' => 'Warm turmeric mustard yellow embellished with intricate all-over floral kadwa jaal brocade in lustrous antique gold zari.',
                'colour' => 'Mustard Gold',
                'price' => 38000.00,
                'discount_price' => 34500.00,
                'price_note' => 'Hand-cut loom piece',
                'availability' => ProductAvailability::Available,
                'stock_quantity' => 2,
                'status' => ProductStatus::Published,
                'is_featured' => true,
                'view_count' => 95,
                'published_at' => now()->subDays(6),
                'categories' => ['banarasi-pure-katan', 'jacquard-brocade', 'reception-sangeet'],
                'specs' => [
                    'zari_type' => 'Antique Gold Zari Jaal',
                    'blouse_piece' => 'Included with coordinating border work',
                    'care' => 'Dry clean only.',
                    'weave_technique' => 'Katan Silk Kadwa Hand-Weave',
                ],
            ],
            [
                'sku' => 'IC-0004',
                'name' => 'Emerald Green Vaira Oosi Pure Kanjeevaram Saree',
                'short_description' => 'A royal forest green canvas etched with needle-thin gold zari pin-stripes (Vaira Oosi) and an opulent contrast maroon pallu.',
                'colour' => 'Emerald Green',
                'price' => 54000.00,
                'discount_price' => null,
                'price_note' => 'Master artisan single piece',
                'availability' => ProductAvailability::Available,
                'stock_quantity' => 1,
                'status' => ProductStatus::Published,
                'is_featured' => true,
                'view_count' => 218,
                'published_at' => now()->subDays(7),
                'categories' => ['bridal-kanjeevaram', 'korvai-handloom', 'mulberry-pure-silk', 'muhurtham-wedding'],
                'specs' => [
                    'zari_type' => 'Pure Tested Gold Zari',
                    'blouse_piece' => 'Deep Maroon contrast silk',
                    'care' => 'Dry clean only. Roll fold to avoid creasing zari lines.',
                    'weave_technique' => 'Vaira Oosi (Diamond Needle Pinstripes) & Korvai',
                ],
            ],
            [
                'sku' => 'IC-0005',
                'name' => 'Pastel Blush Pink Soft Silk Temple Saree',
                'short_description' => 'Subtle romantic pastel blush pink crafted in featherweight pure silk, adorned with delicate rose gold zari temple motifs.',
                'colour' => 'Blush Pink',
                'price' => 26500.00,
                'discount_price' => 23900.00,
                'price_note' => 'Contemporary weave',
                'availability' => ProductAvailability::Available,
                'stock_quantity' => 3,
                'status' => ProductStatus::Published,
                'is_featured' => false,
                'view_count' => 84,
                'published_at' => now()->subDays(10),
                'categories' => ['soft-silk', 'reception-sangeet'],
                'specs' => [
                    'zari_type' => 'Rose Gold Soft Zari',
                    'blouse_piece' => 'Included in matching blush tone',
                    'care' => 'Dry clean only.',
                    'weave_technique' => 'Feather-Soft Silk Drape Weave',
                ],
            ],
            [
                'sku' => 'IC-0006',
                'name' => 'Deep Magenta & Violet Jacquard Silk Saree',
                'short_description' => 'A dramatic duo-tone shot silk shimmering between deep magenta and royal violet, crowned with intricate rudraksh zari motifs.',
                'colour' => 'Deep Magenta',
                'price' => 45000.00,
                'discount_price' => 39500.00,
                'price_note' => null,
                'availability' => ProductAvailability::Available,
                'stock_quantity' => 1,
                'status' => ProductStatus::Published,
                'is_featured' => false,
                'view_count' => 163,
                'published_at' => now()->subDays(12),
                'categories' => ['traditional-korvai', 'jacquard-brocade', 'mulberry-pure-silk'],
                'specs' => [
                    'zari_type' => 'High Grade Tested Zari',
                    'blouse_piece' => 'Contrast Deep Violet unstitched 80cm',
                    'care' => 'Dry clean only.',
                    'weave_technique' => 'Shot Silk Two-Tone Jacquard Weaving',
                ],
            ],
            [
                'sku' => 'IC-0007',
                'name' => 'Rani Pink & Gold Tissue Silk Saree',
                'short_description' => 'An extravagant celebration saree woven with pure silk warp and metallic gold zari weft, radiating breathtaking luminescence.',
                'colour' => 'Rani Pink',
                'price' => 72000.00,
                'discount_price' => 65000.00,
                'price_note' => 'Exclusive tissue heirloom',
                'availability' => ProductAvailability::Available,
                'stock_quantity' => 1,
                'status' => ProductStatus::Published,
                'is_featured' => true,
                'view_count' => 420,
                'published_at' => now()->subDays(14),
                'categories' => ['tissue-kanjeevaram', 'bridal-kanjeevaram', 'reception-sangeet'],
                'specs' => [
                    'zari_type' => 'Pure Gold Tissue Metallic Weft',
                    'blouse_piece' => 'Heavy Tissue Silk blouse included',
                    'care' => 'Dry clean only. Handle with utmost care.',
                    'weave_technique' => 'Gold Tissue Interlocking Warp',
                ],
            ],
            [
                'sku' => 'IC-0008',
                'name' => 'Royal Purple Korvai Border Kanjeevaram',
                'short_description' => 'Aristocratic imperial purple silk adorned with solid contrast orange-red rettai pettu (double petni) border in gleaming gold.',
                'colour' => 'Royal Purple',
                'price' => 49500.00,
                'discount_price' => null,
                'price_note' => null,
                'availability' => ProductAvailability::SoldOut, // Tests Sold Out badge!
                'stock_quantity' => 0,
                'status' => ProductStatus::Published,
                'is_featured' => false,
                'view_count' => 190,
                'published_at' => now()->subDays(16),
                'categories' => ['traditional-korvai', 'korvai-handloom', 'mulberry-pure-silk', 'muhurtham-wedding'],
                'specs' => [
                    'zari_type' => 'Tested Pure Zari',
                    'blouse_piece' => 'Contrast Orange-Red matching border',
                    'care' => 'Dry clean only.',
                    'weave_technique' => 'Rettai Pettu Two-Shuttle Handloom',
                ],
            ],
            [
                'sku' => 'IC-0009',
                'name' => 'Teal Organza Handloom Silk with Zari Buttas',
                'short_description' => 'Crisp translucent teal organza silk sprinkled with delicate coin buttas and framed by a rich Banarasi zari border.',
                'colour' => 'Teal Blue',
                'price' => 29000.00,
                'discount_price' => 25500.00,
                'price_note' => 'Lightweight partywear',
                'availability' => ProductAvailability::Available,
                'stock_quantity' => 2,
                'status' => ProductStatus::Published,
                'is_featured' => false,
                'view_count' => 112,
                'published_at' => now()->subDays(18),
                'categories' => ['organza-kanjeevaram', 'reception-sangeet'],
                'specs' => [
                    'zari_type' => 'Gold and Silver Fine Zari',
                    'blouse_piece' => 'Tone-on-tone pure raw silk',
                    'care' => 'Dry clean only. Steam iron only on reverse.',
                    'weave_technique' => 'Crisp Handspun Organza Warp',
                ],
            ],
            [
                'sku' => 'IC-0010',
                'name' => 'Muted Sage Green Trousseau Pure Silk',
                'short_description' => 'Sophisticated pastel sage green adorned with antique silver zari floral vines and subtle silver temple edging.',
                'colour' => 'Sage Green',
                'price' => 36000.00,
                'discount_price' => 31500.00,
                'price_note' => null,
                'availability' => ProductAvailability::Available,
                'stock_quantity' => 2,
                'status' => ProductStatus::Published,
                'is_featured' => false,
                'view_count' => 98,
                'published_at' => now()->subDays(20),
                'categories' => ['soft-silk', 'mulberry-pure-silk'],
                'specs' => [
                    'zari_type' => 'Matte Antique Silver Zari',
                    'blouse_piece' => 'Included matching sage green',
                    'care' => 'Dry clean only.',
                    'weave_technique' => 'Lightweight Jacquard Brocade',
                ],
            ],
            [
                'sku' => 'IC-0011',
                'name' => 'Maroon & Antique Gold Bridal Kanjeevaram (Heirloom)',
                'short_description' => 'An opulent heirloom bridal masterpiece woven on special order with certified pure silver wire gold-plated zari. Price on request.',
                'colour' => 'Maroon',
                'price' => null, // Tests "Price on request" and null-price WhatsApp enquire logic!
                'discount_price' => null,
                'price_note' => 'Price on request (Exclusive heirloom)',
                'availability' => ProductAvailability::OnRequest,
                'stock_quantity' => 1,
                'status' => ProductStatus::Published,
                'is_featured' => true,
                'view_count' => 540,
                'published_at' => now()->subDays(22),
                'categories' => ['bridal-kanjeevaram', 'pure-gold-zari-kanjeevaram', 'muhurtham-wedding'],
                'specs' => [
                    'zari_type' => '24K Pure Gold Plated Certified Silver Zari',
                    'blouse_piece' => 'Matching maroon with full back/sleeve zari jaal',
                    'care' => 'Dry clean only. Includes wooden velvet preservation box.',
                    'weave_technique' => 'Generational Master Weaver Pit-Loom',
                ],
            ],
            [
                'sku' => 'IC-0012',
                'name' => 'Sunrise Coral & Mango Yellow Soft Silk Saree',
                'short_description' => 'A joyful burst of South Indian morning light: glowing sunrise coral intertwined with warm mango yellow and sweet golden border.',
                'colour' => 'Coral & Yellow',
                'price' => 24500.00,
                'discount_price' => 21900.00,
                'price_note' => 'Loom direct',
                'availability' => ProductAvailability::Available,
                'stock_quantity' => 4,
                'status' => ProductStatus::Published,
                'is_featured' => false,
                'view_count' => 135,
                'published_at' => now()->subDays(25),
                'categories' => ['soft-silk', 'reception-sangeet'],
                'specs' => [
                    'zari_type' => 'Fine Gold Zari',
                    'blouse_piece' => 'Contrast Mango Yellow',
                    'care' => 'Dry clean only.',
                    'weave_technique' => 'Soft Silk Twill Weave',
                ],
            ],
        ];

        foreach ($sarees as $num => $sData) {
            $catSlugs = $sData['categories'];
            unset($sData['categories']);

            $slug = Str::slug($sData['name']).'-'.strtolower($sData['sku']);
            $product = Product::updateOrCreate(
                ['sku' => $sData['sku']],
                array_merge($sData, ['slug' => $slug])
            );

            // Attach categories
            $catIds = [];
            foreach ($catSlugs as $cSlug) {
                if (isset($categoryModels[$cSlug])) {
                    $catIds[] = $categoryModels[$cSlug]->id;
                }
            }
            $product->categories()->sync($catIds);

            // Attach 2 multi-size images (Primary + Hover Secondary)
            ProductImage::updateOrCreate(
                ['product_id' => $product->id, 'sort_order' => 0],
                [
                    'path' => "products/{$product->sku}-1-large.webp",
                    'medium_path' => "products/{$product->sku}-1-med.webp",
                    'thumb_path' => "products/{$product->sku}-1-thumb.webp",
                    'is_primary' => true,
                ]
            );

            ProductImage::updateOrCreate(
                ['product_id' => $product->id, 'sort_order' => 1],
                [
                    'path' => "products/{$product->sku}-2-large.webp",
                    'medium_path' => "products/{$product->sku}-2-med.webp",
                    'thumb_path' => "products/{$product->sku}-2-thumb.webp",
                    'is_primary' => false,
                ]
            );
        }

        // 7. Seed Wishlist items for Demo Customer
        $firstProduct = Product::where('sku', 'IC-0001')->first();
        $secondProduct = Product::where('sku', 'IC-0002')->first();
        if ($firstProduct && $customer) {
            Wishlist::updateOrCreate(['user_id' => $customer->id, 'product_id' => $firstProduct->id]);
        }
        if ($secondProduct && $customer) {
            Wishlist::updateOrCreate(['user_id' => $customer->id, 'product_id' => $secondProduct->id]);
        }

        // 8. Seed sample enquiries for Admin Concierge Desk
        if ($firstProduct) {
            Enquiry::updateOrCreate(
                ['product_sku' => $firstProduct->sku, 'customer_phone' => '+919876543210'],
                [
                    'user_id' => $customer->id,
                    'product_id' => $firstProduct->id,
                    'product_name' => $firstProduct->name,
                    'product_price' => $firstProduct->current_price,
                    'customer_name' => 'Priya Ramanathan',
                    'source' => EnquirySource::ProductPage,
                    'page_url' => url("/product/{$firstProduct->slug}"),
                    'status' => EnquiryStatus::New,
                    'admin_note' => 'Customer is inquiring for daughter’s November wedding. Interested in video call drape demo.',
                    'created_at' => now()->subHours(3),
                ]
            );
        }

        if ($secondProduct) {
            Enquiry::updateOrCreate(
                ['product_sku' => $secondProduct->sku, 'customer_phone' => '+919443215678'],
                [
                    'user_id' => null,
                    'product_id' => $secondProduct->id,
                    'product_name' => $secondProduct->name,
                    'product_price' => $secondProduct->current_price,
                    'customer_name' => 'Ananya Karthik (Chennai)',
                    'source' => EnquirySource::Card,
                    'page_url' => url("/product/{$secondProduct->slug}"),
                    'status' => EnquiryStatus::Contacted,
                    'admin_note' => 'Sent HD video on WhatsApp. Patron confirmed pure zari purity certificate.',
                    'created_at' => now()->subDay(),
                ]
            );
        }

        // 9. Seed sample Video Appointment
        VideoAppointment::updateOrCreate(
            ['phone' => '+919876543210', 'preferred_date' => now()->addDays(2)->toDateString()],
            [
                'user_id' => $customer->id,
                'name' => 'Priya Ramanathan',
                'preferred_time' => '11:00 AM - 12:00 PM',
                'note' => 'Looking for 3 pure Kanjeevarams for reception and muhurtham.',
                'status' => VideoAppointmentStatus::Confirmed,
            ]
        );

        // 10. Seed sample Newsletter Subscriber
        NewsletterSubscriber::updateOrCreate(
            ['email' => 'connoisseur@silks.com'],
            ['subscribed_at' => now()->subDays(5)]
        );
    }
}
