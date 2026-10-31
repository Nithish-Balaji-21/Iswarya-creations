<?php

namespace App\Enums;

enum CategoryGroup: string
{
    case Weave = 'weave';
    case Fabric = 'fabric';
    case Occasion = 'occasion';
    case Kanjeevaram = 'kanjeevaram';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Weave => 'Weave',
            self::Fabric => 'Fabric',
            self::Occasion => 'Occasion',
            self::Kanjeevaram => 'Kanjeevaram Sarees',
            self::Other => 'Special Collections',
        };
    }

    public static function routeGroups(): array
    {
        return [
            self::Weave->value,
            self::Fabric->value,
            self::Occasion->value,
            self::Kanjeevaram->value,
        ];
    }
}
