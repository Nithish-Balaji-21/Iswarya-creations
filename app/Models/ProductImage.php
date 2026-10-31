<?php

namespace App\Models;

use Database\Factories\ProductImageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    /** @use HasFactory<ProductImageFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'path',
        'medium_path',
        'thumb_path',
        'is_primary',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::deleted(function (self $image) {
            $disk = Storage::disk('public');
            foreach ([$image->path, $image->medium_path, $image->thumb_path] as $file) {
                if ($file && $disk->exists($file) && ! str_starts_with($file, 'http')) {
                    $disk->delete($file);
                }
            }
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getLargeUrlAttribute(): string
    {
        return $this->resolveUrl($this->path);
    }

    public function getMediumUrlAttribute(): string
    {
        return $this->resolveUrl($this->medium_path ?? $this->path);
    }

    public function getThumbUrlAttribute(): string
    {
        return $this->resolveUrl($this->thumb_path ?? $this->medium_path ?? $this->path);
    }

    private function resolveUrl(?string $path): string
    {
        if (! $path) {
            return asset('images/placeholder-saree.webp');
        }

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }
}
