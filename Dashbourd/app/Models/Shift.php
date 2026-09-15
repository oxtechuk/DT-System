<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'opening_cash' => 'float',
        'expected_cash' => 'float',
        'actual_cash' => 'float',
        'cash_difference' => 'float',
        'total_cash' => 'float',
        'total_instapay' => 'float',
        'total_wallet' => 'float',
        'total_expenses' => 'float',
        'total_revenue' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }
}
