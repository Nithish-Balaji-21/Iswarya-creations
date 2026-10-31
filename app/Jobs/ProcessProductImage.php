<?php

namespace App\Jobs;

use App\Models\ProductImage;
use App\Services\Image\ImageOptimizationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessProductImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $productId,
        public string $tempFilePath,
        public bool $isPrimary = false,
        public int $sortOrder = 0
    ) {}

    public function handle(ImageOptimizationService $imageService): void
    {
        try {
            if (! File::exists($this->tempFilePath)) {
                Log::warning("Temporary image file not found: {$this->tempFilePath}");

                return;
            }

            $variants = $imageService->process($this->tempFilePath, 'products');

            ProductImage::create([
                'product_id' => $this->productId,
                'path' => $variants['large'],
                'medium_path' => $variants['medium'],
                'thumb_path' => $variants['thumb'],
                'is_primary' => $this->isPrimary,
                'sort_order' => $this->sortOrder,
            ]);

            // Clean up temporary upload file
            File::delete($this->tempFilePath);
        } catch (Throwable $e) {
            Log::error("Failed to process product image for product [{$this->productId}]: ".$e->getMessage());
            throw $e;
        }
    }
}
