<?php

namespace App\Services\Image;

use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use InvalidArgumentException;

class ImageOptimizationService
{
    /**
     * Allowed MIME types for saree boutique uploads.
     */
    protected array $allowedMimes = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/webp',
    ];

    /**
     * Max upload file size in bytes (25 MB).
     */
    protected int $maxSizeBytes = 25 * 1024 * 1024;

    /**
     * Target resolutions in 4:5 aspect ratio.
     */
    protected array $dimensions = [
        'large' => ['width' => 1600, 'height' => 2000],
        'medium' => ['width' => 900, 'height' => 1125],
        'thumb' => ['width' => 400, 'height' => 500],
    ];

    /**
     * Validate and process image into 3 WebP variants (400px, 900px, 1600px) cropped to 4:5.
     *
     * @return array{large: string, medium: string, thumb: string}
     */
    public function process(UploadedFile|string $source, string $directory = 'products'): array
    {
        $this->validate($source);

        $sourcePath = $source instanceof UploadedFile ? $source->getRealPath() : $source;
        $filenameBase = Str::uuid()->toString();
        $disk = Storage::disk('public');

        $paths = [];

        foreach ($this->dimensions as $key => $dim) {
            // Read source and create fresh image instance per resolution
            $img = method_exists(Image::getFacadeRoot(), 'read')
                ? Image::read($sourcePath)
                : Image::decodePath($sourcePath);

            // EXIF auto-orientation
            if (method_exists($img, 'orient')) {
                $img->orient();
            }

            // Crop/fit to 4:5 luxury portrait ratio using cover
            $img->cover($dim['width'], $dim['height']);

            // Convert to WebP and strip metadata
            $webpEncoded = method_exists($img, 'toWebp')
                ? $img->toWebp(85)
                : $img->encodeUsingFileExtension('webp');

            $relativePath = "{$directory}/{$filenameBase}-{$key}.webp";
            $disk->put($relativePath, (string) $webpEncoded);

            $paths[$key] = $relativePath;
        }

        return [
            'large' => $paths['large'],
            'medium' => $paths['medium'],
            'thumb' => $paths['thumb'],
        ];
    }

    /**
     * Validate source MIME and size.
     */
    public function validate(UploadedFile|string $source): void
    {
        if ($source instanceof UploadedFile) {
            if ($source->getSize() > $this->maxSizeBytes) {
                throw new InvalidArgumentException('Image file exceeds the maximum allowed size of 12MB.');
            }

            $mime = $source->getMimeType();
            if (! in_array($mime, $this->allowedMimes, true)) {
                throw new InvalidArgumentException("Invalid image MIME type [{$mime}]. Allowed: JPEG, PNG, WebP.");
            }
        } elseif (is_string($source)) {
            if (! file_exists($source)) {
                throw new InvalidArgumentException("Source image does not exist at [{$source}].");
            }

            if (filesize($source) > $this->maxSizeBytes) {
                throw new InvalidArgumentException('Image file exceeds the maximum allowed size of 12MB.');
            }
        }
    }

    /**
     * Delete all 3 image variants from disk.
     */
    public function deleteVariants(ProductImage|array|null $image): void
    {
        if (! $image) {
            return;
        }

        $disk = Storage::disk('public');

        if ($image instanceof ProductImage) {
            $files = [$image->path, $image->medium_path, $image->thumb_path];
        } else {
            $files = [
                $image['large'] ?? $image['path'] ?? null,
                $image['medium'] ?? $image['medium_path'] ?? null,
                $image['thumb'] ?? $image['thumb_path'] ?? null,
            ];
        }

        foreach ($files as $file) {
            if ($file && $disk->exists($file) && ! str_starts_with($file, 'http')) {
                $disk->delete($file);
            }
        }
    }
}
