<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Open          = 'open';
    case PartiallyPaid = 'partially_paid';
    case Paid          = 'paid';
    case Cancelled     = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Open          => 'Open',
            self::PartiallyPaid => 'Partially Paid',
            self::Paid          => 'Paid',
            self::Cancelled     => 'Cancelled',
        };
    }

    public function isPaid(): bool
    {
        return $this === self::Paid;
    }
}
