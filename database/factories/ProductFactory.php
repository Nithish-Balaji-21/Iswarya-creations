<?php

namespace Database\Factories;

use App\Enums\ProductAvailability;
use App\Enums\ProductStatus;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $prefix = config('iswarya.sku_prefix', 'IC-');
        $name = fake()->words(3, true).' Silk Saree';
        $price = fake()->randomElement([18500, 24500, 32000, 42500, 65000, 89000, 125000]);
        $hasDiscount = fake()->boolean(30);

        return [
            'sku' => $prefix.fake()->unique()->numerify('####'),
            'name' => ucwords($name),
            'slug' => Str::slug($name).'-'.fake()->unique()->randomNumber(4),
            'short_description' => 'Exquisite handwoven pure silk saree adorned with authentic pure zari craftsmanship.',
            'description' => '<p>Crafted by master weavers in the handloom clusters of Tamil Nadu, this masterpiece combines royal elegance with traditional heritage.</p>',
            'colour' => fake()->randomElement(['Crimson Maroon', 'Royal Blue', 'Emerald Green', 'Mustard Gold', 'Deep Purple', 'Rani Pink', 'Peacock Teal']),
            'price' => $price,
            'discount_price' => $hasDiscount ? $price - 3000 : null,
            'price_note' => 'Includes stitched blouse piece option',
            'availability' => ProductAvailability::Available,
            'stock_quantity' => fake()->randomElement([null, 1, 2, 3]),
            'status' => ProductStatus::Published,
            'is_featured' => fake()->boolean(25),
            'published_at' => now()->subDays(fake()->numberBetween(1, 20)),
            'view_count' => fake()->numberBetween(10, 500),
            'specs' => [
                'zari_type' => 'Tested Half-Fine Gold Zari',
                'blouse_piece' => 'Included (Matching 80cm unstitched fabric)',
                'care' => 'Dry Clean Only. Store in breathable muslin cloth.',
                'weave_technique' => 'Handloom Korvai Weaving',
                'origin' => 'Peelamedu, Coimbatore',
            ],
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'status' => ProductStatus::Draft,
            'published_at' => null,
        ]);
    }

    public function published(): static
    {
        return $this->state(fn () => [
            'status' => ProductStatus::Published,
            'published_at' => now()->subDay(),
        ]);
    }

    public function soldOut(): static
    {
        return $this->state(fn () => [
            'availability' => ProductAvailability::SoldOut,
        ]);
    }

    public function priceOnRequest(): static
    {
        return $this->state(fn () => [
            'price' => null,
            'discount_price' => null,
        ]);
    }
}
