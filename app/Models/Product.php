<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'sku',
        'barcode',
        'unit',
        'selling_price',
        'purchase_price',
        'minimum_stock',
        'track_inventory',
        'active',
    ];

    protected $casts = [
        'selling_price'   => 'decimal:2',
        'purchase_price'  => 'decimal:2',
        'track_inventory' => 'boolean',
        'active'          => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
