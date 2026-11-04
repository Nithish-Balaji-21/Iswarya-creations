<?php

namespace App\Models;

use App\Enums\ProductAvailability;
use App\Enums\ProductStatus;
use App\Support\IndianCurrency;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sku',
        'name',
        'slug',
        'short_description',
        'description',
        'colour',
        'price',
        'discount_price',
        'price_note',
        'availability',
        'stock_quantity',
        'status',
        'is_featured',
        'published_at',
        'view_count',
        'specs',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'discount_price' => 'decimal:2',
            'availability' => ProductAvailability::class,
            'status' => ProductStatus::class,
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
            'view_count' => 'integer',
            'stock_quantity' => 'integer',
            'specs' => 'array',
        ];
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function enquiries(): HasMany
    {
        return $this->hasMany(Enquiry::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', ProductStatus::Published)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('availability', ProductAvailability::Available);
    }

    public function scopeNewArrivals(Builder $query, ?int $days = null): Builder
    {
        $days = $days ?? config('iswarya.new_arrivals_days', 30);

        return $query->published()
            ->where('published_at', '>=', now()->subDays($days))
            ->orderBy('published_at', 'desc');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Helpers
    |--------------------------------------------------------------------------
    */

    public function getFormattedPriceAttribute(): string
    {
        return IndianCurrency::format($this->price ? (float) $this->price : null);
    }

    public function getFormattedDiscountPriceAttribute(): ?string
    {
        if ($this->discount_price === null) {
            return null;
        }

        return IndianCurrency::format((float) $this->discount_price);
    }

    public function getHasDiscountAttribute(): bool
    {
        return $this->discount_price !== null && $this->price !== null && $this->discount_price < $this->price;
    }

    public function getDiscountPercentageAttribute(): ?int
    {
        if (! $this->has_discount || ! $this->price) {
            return null;
        }

        return (int) round((($this->price - $this->discount_price) / $this->price) * 100);
    }

    public function getEffectivePriceAttribute(): ?float
    {
        return $this->discount_price ? (float) $this->discount_price : ($this->price ? (float) $this->price : null);
    }

    public function getIsSoldOutAttribute(): bool
    {
        return $this->availability === ProductAvailability::SoldOut;
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        // Try relation loaded primaryImage, or first image, or fallback placeholder
        $primary = $this->relationLoaded('primaryImage') && $this->primaryImage
            ? $this->primaryImage
            : ($this->relationLoaded('images') ? $this->images->firstWhere('is_primary', true) ?? $this->images->first() : $this->images()->where('is_primary', true)->first() ?? $this->images()->first());

        if ($primary) {
            return $primary->large_url;
        }

        return asset('images/placeholder-saree.webp');
    }

    public function getSecondaryImageUrlAttribute(): string
    {
        $images = $this->relationLoaded('images') ? $this->images : $this->images()->get();

        if ($images->count() > 1) {
            return $images->get(1)->large_url;
        }

        return $this->primary_image_url;
    }

    /**
     * Generate next sequential SKU safely.
     */
    public static function generateNextSku(): string
    {
        $prefix = config('iswarya.sku_prefix', 'IC-');
        $digits = config('iswarya.sku_digits', 4);

        return DB::transaction(function () use ($prefix, $digits) {
            $driver = DB::connection()->getDriverName();
            $orderSql = match ($driver) {
                'sqlite' => 'CAST(SUBSTR(sku, ?) AS INTEGER) DESC',
                'pgsql' => 'CAST(SUBSTRING(sku FROM ?) AS INTEGER) DESC',
                default => 'CAST(SUBSTRING(sku, ?) AS UNSIGNED) DESC',
            };

            // Find latest matching SKU lock for update
            $latest = self::withTrashed()
                ->where('sku', 'like', $prefix.'%')
                ->lockForUpdate()
                ->orderByRaw($orderSql, [strlen($prefix) + 1])
                ->value('sku');

            if ($latest && preg_match('/^'.preg_quote($prefix, '/').'(\d+)$/', $latest, $matches)) {
                $nextNumber = ((int) $matches[1]) + 1;
            } else {
                $nextNumber = 1;
            }

            return $prefix.str_pad((string) $nextNumber, $digits, '0', STR_PAD_LEFT);
        });
    }
}
