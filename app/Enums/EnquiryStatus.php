<?php

namespace App\Enums;

enum EnquiryStatus: string
{
    case New = 'new';
    case Contacted = 'contacted';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::New => 'New Enquiry',
            self::Contacted => 'Contacted',
            self::Closed => 'Closed',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::New => 'bg-amber-100 text-amber-800 border-amber-200',
            self::Contacted => 'bg-blue-100 text-blue-800 border-blue-200',
            self::Closed => 'bg-emerald-100 text-emerald-800 border-emerald-200',
        };
    }
}
