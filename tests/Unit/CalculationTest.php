<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Support\IndianCurrency;
use Tests\TestCase;

class CalculationTest extends TestCase
{
    public function test_indian_currency_formats_regular_numbers_correctly(): void
    {
        $this->assertEquals('₹500', IndianCurrency::format(500));
        $this->assertEquals('₹1,500', IndianCurrency::format(1500));
        $this->assertEquals('₹42,500', IndianCurrency::format(42500));
        $this->assertEquals('₹1,45,000', IndianCurrency::format(145000));
        $this->assertEquals('₹12,50,000', IndianCurrency::format(1250000));
        $this->assertEquals('₹1,25,00,000', IndianCurrency::format(12500000));
    }

    public function test_indian_currency_handles_null_as_price_on_request(): void
    {
        $this->assertEquals('Price on request', IndianCurrency::format(null));
    }

    public function test_indian_currency_handles_zero_and_decimals(): void
    {
        $this->assertEquals('₹0', IndianCurrency::format(0));
        $this->assertEquals('₹42,500.50', IndianCurrency::format(42500.50));
        $this->assertEquals('₹42,500', IndianCurrency::format(42500.00));
    }

    public function test_product_discount_and_effective_price_calculations(): void
    {
        $productWithDiscount = new Product([
            'price' => 50000,
            'discount_price' => 40000,
        ]);

        $this->assertTrue($productWithDiscount->has_discount);
        $this->assertEquals(20, $productWithDiscount->discount_percentage);
        $this->assertEquals(40000, $productWithDiscount->effective_price);
        $this->assertEquals('₹50,000', $productWithDiscount->formatted_price);
        $this->assertEquals('₹40,000', $productWithDiscount->formatted_discount_price);

        $productWithoutDiscount = new Product([
            'price' => 35000,
            'discount_price' => null,
        ]);

        $this->assertFalse($productWithoutDiscount->has_discount);
        $this->assertNull($productWithoutDiscount->discount_percentage);
        $this->assertEquals(35000, $productWithoutDiscount->effective_price);
        $this->assertEquals('₹35,000', $productWithoutDiscount->formatted_price);
        $this->assertNull($productWithoutDiscount->formatted_discount_price);

        $productPriceOnRequest = new Product([
            'price' => null,
            'discount_price' => null,
        ]);

        $this->assertFalse($productPriceOnRequest->has_discount);
        $this->assertNull($productPriceOnRequest->effective_price);
        $this->assertEquals('Price on request', $productPriceOnRequest->formatted_price);
    }
}

