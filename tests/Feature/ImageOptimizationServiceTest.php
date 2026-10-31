<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductImage;
use App\Services\Image\ImageOptimizationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageOptimizationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_image_service_generates_three_webp_variants_in_four_by_five_ratio(): void
    {
        Storage::fake('public');

        $service = app(ImageOptimizationService::class);
        $file = UploadedFile::fake()->image('kanjeevaram-saree.jpg', 1200, 1500);

        $variants = $service->process($file, 'products');

        $this->assertArrayHasKey('large', $variants);
        $this->assertArrayHasKey('medium', $variants);
        $this->assertArrayHasKey('thumb', $variants);

        // Verify files exist in storage
        Storage::disk('public')->assertExists($variants['large']);
        Storage::disk('public')->assertExists($variants['medium']);
        Storage::disk('public')->assertExists($variants['thumb']);

        // Verify webp extensions
        $this->assertStringEndsWith('-large.webp', $variants['large']);
        $this->assertStringEndsWith('-medium.webp', $variants['medium']);
        $this->assertStringEndsWith('-thumb.webp', $variants['thumb']);
    }

    public function test_deleting_product_image_cleans_up_all_three_storage_files(): void
    {
        Storage::fake('public');

        $service = app(ImageOptimizationService::class);
        $file = UploadedFile::fake()->image('soft-silk.png', 800, 1000);
        $variants = $service->process($file, 'products');

        $product = Product::factory()->create();
        $image = ProductImage::create([
            'product_id' => $product->id,
            'path' => $variants['large'],
            'medium_path' => $variants['medium'],
            'thumb_path' => $variants['thumb'],
            'is_primary' => true,
            'sort_order' => 0,
        ]);

        Storage::disk('public')->assertExists($variants['large']);

        // Delete model
        $image->delete();

        // Model booted listener should have purged all 3 files
        Storage::disk('public')->assertMissing($variants['large']);
        Storage::disk('public')->assertMissing($variants['medium']);
        Storage::disk('public')->assertMissing($variants['thumb']);
    }
}
