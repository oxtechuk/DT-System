<?php

namespace App\Enums;

enum DealStatus: string
{
    case Open      = 'open';
    case Closed    = 'closed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Open      => 'Open',
            self::Closed    => 'Closed',
            self::Cancelled => 'Cancelled',
        };
    }
}
