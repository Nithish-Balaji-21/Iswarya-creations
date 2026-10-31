<?php

namespace Database\Factories;

use App\Models\Banner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Banner>
 */
class BannerFactory extends Factory
{
    protected $model = Banner::class;

    public function definition(): array
    {
        return [
            'title' => 'Pure Kanjeevaram Bridal Silks',
            'eyebrow' => 'Handloom Heritage • Peelamedu Flagship',
            'subtitle' => 'Hand-spun mulberry silk woven with pure certified zari by generational artisans.',
            'desktop_image' => 'banners/hero-desktop-1.webp',
            'mobile_image' => 'banners/hero-mobile-1.webp',
            'link_url' => '/kanjeevaram/bridal-collection',
            'button_text' => 'Explore Bridal Silks',
            'sort_order' => 1,
            'is_active' => true,
        ];
    }
}
