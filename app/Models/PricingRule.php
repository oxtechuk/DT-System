<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'price'           => 'decimal:2',
        'effective_from'  => 'date',
        'effective_until' => 'date',
        'active'          => 'boolean',
    ];

    public function workspaceType(): BelongsTo
    {
        return $this->belongsTo(WorkspaceType::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Rules effective at a given date.
     */
    public function scopeEffectiveAt($query, \Carbon\Carbon $date)
    {
        return $query->where('effective_from', '<=', $date->toDateString())
            ->where(function ($q) use ($date) {
                $q->whereNull('effective_until')
                  ->orWhere('effective_until', '>=', $date->toDateString());
            });
    }
}
