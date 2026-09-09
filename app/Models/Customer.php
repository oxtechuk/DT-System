<?php

namespace App\Models;

use App\Enums\CustomerType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    protected $casts = [
        'customer_type' => CustomerType::class,
    ];

    // ─── Relationships ───────────────────────────────────────────────────────────

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeRegistered($query)
    {
        return $query->where('customer_type', CustomerType::Registered->value);
    }
}
