<?php

namespace Database\Factories;

use App\Enums\EnquirySource;
use App\Enums\EnquiryStatus;
use App\Models\Enquiry;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Enquiry>
 */
class EnquiryFactory extends Factory
{
    protected $model = Enquiry::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'product_name' => 'Kanjeevaram Bridal Silk Saree',
            'product_sku' => 'IC-0042',
            'product_price' => 42500.00,
            'customer_name' => fake()->name(),
            'customer_phone' => '+91'.fake()->numberBetween(6000000000, 9999999999),
            'source' => fake()->randomElement(EnquirySource::cases()),
            'page_url' => url('/product/kanjeevaram-bridal-silk-saree'),
            'status' => EnquiryStatus::New,
            'admin_note' => null,
        ];
    }

    public function contacted(): static
    {
        return $this->state(fn () => [
            'status' => EnquiryStatus::Contacted,
            'admin_note' => 'Spoke on WhatsApp. Customer requested video call demonstration.',
        ]);
    }

    public function closed(): static
    {
        return $this->state(fn () => [
            'status' => EnquiryStatus::Closed,
            'admin_note' => 'Customer visited Peelamedu showroom and purchased.',
        ]);
    }
}
