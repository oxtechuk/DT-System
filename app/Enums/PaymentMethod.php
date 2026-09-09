<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Cash     = 'cash';
    case InstaPay = 'instapay';
    case Wallet   = 'wallet';

    public function label(): string
    {
        return match($this) {
            self::Cash     => 'Cash',
            self::InstaPay => 'InstaPay',
            self::Wallet   => 'Wallet',
        };
    }

    public function isPhysicalCash(): bool
    {
        return $this === self::Cash;
    }
}
