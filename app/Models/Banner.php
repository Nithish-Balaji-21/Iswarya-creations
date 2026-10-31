<?php

namespace App\Models;

use Database\Factories\BannerFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    /** @use HasFactory<BannerFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'eyebrow',
        'subtitle',
        'desktop_image',
        'mobile_image',
        'link_url',
        'button_text',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function getDesktopImageUrlAttribute(): string
    {
        return $this->resolveUrl($this->desktop_image);
    }

    public function getMobileImageUrlAttribute(): string
    {
        return $this->resolveUrl($this->mobile_image ?? $this->desktop_image);
    }

    private function resolveUrl(?string $path): string
    {
        if (! $path) {
            return asset('images/placeholder-banner.webp');
        }

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }
}
