<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'workspace_type_id',
        'duration_minutes',
        'price',
        'effective_from',
        'effective_until',
        'active',
    ];

    protected $casts = [
        'price'          => 'decimal:2',
        'effective_from' => 'date',
        'effective_until' => 'date',
        'active'         => 'boolean',
    ];

    // ── Relationships ──

    public function workspaceType()
    {
        return $this->belongsTo(WorkspaceType::class);
    }

    // ── Scopes ──

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Get rules effective on a given date.
     */
    public function scopeEffectiveOn($query, \Carbon\Carbon $date)
    {
        return $query->where('effective_from', '<=', $date)
                     ->where(function ($q) use ($date) {
                         $q->whereNull('effective_until')
                           ->orWhere('effective_until', '>=', $date);
                     });
    }
}
