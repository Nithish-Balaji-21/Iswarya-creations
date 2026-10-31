<?php

namespace App\Models;

use App\Enums\EnquirySource;
use App\Enums\EnquiryStatus;
use App\Support\IndianCurrency;
use Database\Factories\EnquiryFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enquiry extends Model
{
    /** @use HasFactory<EnquiryFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'product_name',
        'product_sku',
        'product_price',
        'customer_name',
        'customer_phone',
        'source',
        'page_url',
        'status',
        'admin_note',
    ];

    protected function casts(): array
    {
        return [
            'product_price' => 'decimal:2',
            'source' => EnquirySource::class,
            'status' => EnquiryStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeStatus(Builder $query, EnquiryStatus|string $status): Builder
    {
        $value = $status instanceof EnquiryStatus ? $status->value : $status;

        return $query->where('status', $value);
    }

    public function getFormattedPriceAttribute(): string
    {
        return IndianCurrency::format($this->product_price ? (float) $this->product_price : null);
    }

    /**
     * Direct reply URL to the customer via WhatsApp if customer_phone exists.
     */
    public function getReplyWhatsAppUrlAttribute(): ?string
    {
        if (! $this->customer_phone) {
            return null;
        }

        // Normalize phone number to international format without + or spaces
        $phone = preg_replace('/[^0-9]/', '', $this->customer_phone);
        if (strlen($phone) === 10) {
            $phone = '91'.$phone;
        }

        $message = "Hello {$this->customer_name}, thank you for contacting Iswarya Silks regarding {$this->product_name} (SKU: {$this->product_sku}). How can we assist you today?";

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($message);
    }
}
