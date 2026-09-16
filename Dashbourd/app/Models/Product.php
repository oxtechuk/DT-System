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

 public function getIsActiveAttribute()
 {
 return $this->active;
 }
}
