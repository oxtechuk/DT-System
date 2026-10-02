<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'unit',
        'current_stock',
        'unit_cost',
        'minimum_stock',
        'active',
        'notes',
    ];

    protected $casts = [
        'current_stock' => 'decimal:2',
        'unit_cost' => 'decimal:4',
        'minimum_stock' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function productIngredients()
    {
        return $this->hasMany(ProductIngredient::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_ingredients')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->current_stock <= $this->minimum_stock;
    }

    public function getUnitLabelAttribute(): string
    {
        return match ($this->unit) {
            'gram' => 'جرام',
            'ml' => 'مل',
            'piece' => 'قطعة',
            'pack' => 'باكت / كيس',
            'kg' => 'كجم',
            'liter' => 'لتر',
            default => $this->unit,
        };
    }
}
