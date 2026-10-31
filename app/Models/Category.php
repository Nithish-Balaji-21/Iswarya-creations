<?php

namespace App\Models;

use App\Enums\CategoryGroup;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory, SoftDeletes;

    public const MEGA_MENU_CACHE_KEY = 'iswarya_mega_menu_categories';

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'group',
        'description',
        'cover_image',
        'banner_image',
        'show_on_homepage',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'group' => CategoryGroup::class,
            'show_on_homepage' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget(self::MEGA_MENU_CACHE_KEY);
        });

        static::deleted(function () {
            Cache::forget(self::MEGA_MENU_CACHE_KEY);
        });

        static::restored(function () {
            Cache::forget(self::MEGA_MENU_CACHE_KEY);
        });
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOnHomepage(Builder $query): Builder
    {
        return $query->where('show_on_homepage', true);
    }

    public function scopeByGroup(Builder $query, CategoryGroup|string $group): Builder
    {
        $groupValue = $group instanceof CategoryGroup ? $group->value : $group;

        return $query->where('group', $groupValue);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        if (! $this->cover_image) {
            return null;
        }

        if (str_starts_with($this->cover_image, 'http')) {
            return $this->cover_image;
        }

        return Storage::disk('public')->url($this->cover_image);
    }

    public function getBannerImageUrlAttribute(): ?string
    {
        if (! $this->banner_image) {
            return null;
        }

        if (str_starts_with($this->banner_image, 'http')) {
            return $this->banner_image;
        }

        return Storage::disk('public')->url($this->banner_image);
    }
}
