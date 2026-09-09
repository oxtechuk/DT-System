<?php

namespace App\Models;

use App\Enums\RoomStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'capacity',
        'status',
        'color',
        'description',
    ];

    protected $casts = [
        'status' => RoomStatus::class,
    ];

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', RoomStatus::Active->value);
    }

    public function isAvailable(): bool
    {
        return $this->status === RoomStatus::Active;
    }
}
