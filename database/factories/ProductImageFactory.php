<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductImage>
 */
class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'path' => 'products/sample-'.fake()->numberBetween(1, 10).'.webp',
            'medium_path' => 'products/sample-'.fake()->numberBetween(1, 10).'-medium.webp',
            'thumb_path' => 'products/sample-'.fake()->numberBetween(1, 10).'-thumb.webp',
            'is_primary' => false,
            'sort_order' => fake()->numberBetween(0, 5),
        ];
    }

    public function primary(): static
    {
        return $this->state(fn () => [
            'is_primary' => true,
            'sort_order' => 0,
        ]);
    }
}
