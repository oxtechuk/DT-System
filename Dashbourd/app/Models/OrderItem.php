<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'item_type',
        'name',
        'quantity',
        'unit_price',
        'total',
        'metadata',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total'      => 'decimal:2',
        'metadata'   => 'array',
    ];

    // ── Relationships ──

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
