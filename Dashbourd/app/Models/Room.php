<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'capacity', 'status', 'color', 'description',
    ];

    // ── Relationships ──

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function activeDeals()
    {
        return $this->hasMany(Deal::class)->where('status', 'open');
    }

    // ── Scopes ──

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // ── Accessors ──

    public function getIsAvailableAttribute(): bool
    {
        return $this->status === 'active' && $this->activeDeals()->count() === 0;
    }

    public function getDisplayColorAttribute(): string
    {
        return $this->color ?? '#6366f1';
    }
}
