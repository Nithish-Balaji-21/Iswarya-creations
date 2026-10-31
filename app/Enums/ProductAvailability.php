<?php

namespace App\Enums;

enum ProductAvailability: string
{
    case Available = 'available';
    case SoldOut = 'sold_out';
    case OnRequest = 'on_request';

    public function label(): string
    {
        return match ($this) {
            self::Available => 'Available',
            self::SoldOut => 'Sold Out',
            self::OnRequest => 'Made to Order',
        };
    }

    public function isAvailable(): bool
    {
        return $this === self::Available;
    }

    public function isSoldOut(): bool
    {
        return $this === self::SoldOut;
    }
}
