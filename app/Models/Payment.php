<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_number',
        'order_id',
        'customer_id',
        'type',
        'method',
        'amount',
        'reference',
        'paid_at',
        'received_by',
        'notes',
    ];

    protected $casts = [
        'method'   => PaymentMethod::class,
        'amount'   => 'decimal:2',
        'paid_at'  => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function isPhysicalCash(): bool
    {
        return $this->method === PaymentMethod::Cash;
    }

    public function scopeByMethod($query, PaymentMethod $method)
    {
        return $query->where('method', $method->value);
    }

    public function scopeInDateRange($query, $from, $to)
    {
        return $query->whereBetween('paid_at', [$from, $to]);
    }
}
