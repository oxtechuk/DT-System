<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkspaceType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'pricing_mode',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class);
    }

    public function pricingRules(): HasMany
    {
        return $this->hasMany(PricingRule::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
