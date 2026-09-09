<?php

namespace App\Enums;

enum RoomStatus: string
{
    case Active      = 'active';
    case Maintenance = 'maintenance';
    case Inactive    = 'inactive';

    public function label(): string
    {
        return match($this) {
            self::Active      => 'Active',
            self::Maintenance => 'Maintenance',
            self::Inactive    => 'Inactive',
        };
    }

    public function isAvailable(): bool
    {
        return $this === self::Active;
    }
}
