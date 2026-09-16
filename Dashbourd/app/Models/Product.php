<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

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
        'description',
        'hubspot_product_id',
        'hubspot_synced_at',
    ];

    protected $casts = [
        'selling_price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'track_inventory' => 'boolean',
        'active' => 'boolean',
        'hubspot_synced_at' => 'datetime',
    ];

    // ── Relationships ──

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function ingredients()
    {
        return $this->hasMany(ProductIngredient::class)->with('rawMaterial');
    }

    public function rawMaterials()
    {
        return $this->belongsToMany(RawMaterial::class, 'product_ingredients')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    // ── Scopes ──

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('sku', 'like', "%{$term}%")
                ->orWhere('barcode', 'like', "%{$term}%");
        });
    }

    public function getPriceAttribute()
    {
        return $this->selling_price;
    }

    public function getCostAttribute()
    {
        if ($this->purchase_price !== null && (float) $this->purchase_price > 0) {
            return (float) $this->purchase_price;
        }
        return $this->calculateRecipeCost();
    }

    public function calculateRecipeCost(): float
    {
        $total = 0;
        foreach ($this->ingredients as $ing) {
            if ($ing->rawMaterial) {
                $total += ($ing->quantity * $ing->rawMaterial->unit_cost);
            }
        }
        return (float) round($total, 2);
    }

    public function getProfitMarginAttribute(): float
    {
        $cost = $this->cost;
        return (float) max(0, $this->selling_price - $cost);
    }

    public function getIsActiveAttribute()
    {
        return $this->active;
    }
}
