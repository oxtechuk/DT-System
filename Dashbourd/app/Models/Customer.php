<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'customer_type',
        'source',
        'status',
        'notes',
    ];

    // ── Relationships ──

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // ── Scopes ──

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('full_name', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%");
        });
    }

    // ── Accessors ──

    public function getNameAttribute(): string
    {
        return $this->full_name ?? '';
    }

    public function getInitialsAttribute(): string
    {
        if (empty($this->full_name)) {
            return '';
        }
        $words = array_values(array_filter(explode(' ', trim($this->full_name))));
        if (count($words) >= 2) {
            return mb_strtoupper(
                mb_substr($words[0], 0, 1, 'UTF-8') . mb_substr($words[1], 0, 1, 'UTF-8'),
                'UTF-8'
            );
        }
        return mb_strtoupper(mb_substr($this->full_name, 0, 2, 'UTF-8'), 'UTF-8');
    }

    public function getActiveDealsCountAttribute(): int
    {
        return $this->deals()->where('status', 'open')->count();
    }
}
