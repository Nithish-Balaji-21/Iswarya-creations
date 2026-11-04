<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Brand & Store Details
    |--------------------------------------------------------------------------
    */
    'brand_name' => 'Iswarya Silks',
    'brand_tagline' => 'Peelamedu, Coimbatore',
    'brand_tagline_ta' => 'ஐஸ்வர்யா சில்க்ஸ்',

    /*
    |--------------------------------------------------------------------------
    | SKU Configuration
    |--------------------------------------------------------------------------
    */
    'sku_prefix' => env('ISWARYA_SKU_PREFIX', 'IC-'),
    'sku_digits' => 4, // e.g. IC-0001

    /*
    |--------------------------------------------------------------------------
    | WhatsApp & Contact Configuration
    |--------------------------------------------------------------------------
    */
    'whatsapp_number' => env('ISWARYA_WHATSAPP_NUMBER', '918973948912'),
    'contact_phone' => '+91 89739 48912',
    'contact_email' => 'iswaryacreations1975@gmail.com',
    'store_address' => '124, Avinashi Road, Peelamedu, Coimbatore, Tamil Nadu 641004, India',
    'google_maps_url' => 'https://share.google/qQvd7CVGrSjmYrHhM',
    'instagram_url' => 'https://www.instagram.com/iswaryasilks.cbe?psln=MXJleGxzaHhpNmY0MQ==',
    'instagram_handle' => '@iswaryasilks.cbe',

    /*
    |--------------------------------------------------------------------------
    | Default Announcement Bar Text
    |--------------------------------------------------------------------------
    */
    'default_announcement' => 'Free Express Shipping Across India | Handloom Certified Pure Silk',

    /*
    |--------------------------------------------------------------------------
    | Catalog & Pagination
    |--------------------------------------------------------------------------
    */
    'per_page' => 24,
    'new_arrivals_days' => 30, // Products published in last 30 days
    'currency_symbol' => '₹',

    /*
    |--------------------------------------------------------------------------
    | Security & Authentication
    |--------------------------------------------------------------------------
    */
    'max_login_attempts' => 5,
    'lockout_minutes' => 15,
    'otp_expiry_minutes' => 10,
    'max_otp_attempts' => 5,

    /*
    |--------------------------------------------------------------------------
    | Media Pipeline Settings
    |--------------------------------------------------------------------------
    */
    'image_sizes' => [
        'thumb' => 400,
        'medium' => 900,
        'large' => 1600,
    ],
    'image_aspect_ratio' => '4:5',
];
