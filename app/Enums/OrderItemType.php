<?php

namespace App\Enums;

enum OrderItemType: string
{
    case Session = 'session';   // workspace session charge
    case Product = 'product';   // drinks, snacks, etc.
    case Custom  = 'custom';
    case Other   = 'other';

    public function label(): string
    {
        return match($this) {
            self::Session => 'Session',
            self::Product => 'Product',
            self::Custom  => 'Custom',
            self::Other   => 'Other',
        };
    }
}
