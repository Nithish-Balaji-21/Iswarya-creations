<?php

namespace App\Enums;

enum EnquirySource: string
{
    case ProductPage = 'product_page';
    case Card = 'card';
    case Wishlist = 'wishlist';
    case WebForm = 'web_form';

    public function label(): string
    {
        return match ($this) {
            self::ProductPage => 'Product Page',
            self::Card => 'Product Card',
            self::Wishlist => 'Wishlist',
            self::WebForm => 'Web Form',
        };
    }
}
