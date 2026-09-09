<?php

namespace App\Enums;

enum CustomerType: string
{
    case Registered = 'registered';
    case Guest      = 'guest';

    public function label(): string
    {
        return match($this) {
            self::Registered => 'Registered',
            self::Guest      => 'Guest',
        };
    }
}
