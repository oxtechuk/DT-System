<?php

namespace App\Models;

use App\Enums\OrderItemType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'item_type'  => OrderItemType::class,
        'unit_price' => 'decimal:2',
        'total'      => 'decimal:2',
        'metadata'   => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
