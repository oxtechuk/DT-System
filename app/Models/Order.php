<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'deal_id',
        'subtotal',
        'discount',
        'tax',
        'total',
        'paid_amount',
        'remaining_amount',
        'status',
        'created_by',
        'closed_by',
        'closed_at',
    ];

    protected $casts = [
        'status'     => OrderStatus::class,
        'subtotal'   => 'decimal:2',
        'discount'   => 'decimal:2',
        'tax'        => 'decimal:2',
        'total'      => 'decimal:2',
        'paid_amount'      => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'closed_at'  => 'datetime',
    ];

    // ─── Relationships ───────────────────────────────────────────────────────────

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────────

    public function isPaid(): bool
    {
        return $this->status === OrderStatus::Paid;
    }

    public function isOpen(): bool
    {
        return $this->status === OrderStatus::Open;
    }

    public function recalculate(): void
    {
        $this->subtotal         = $this->items->sum('total');
        $this->total            = $this->subtotal - $this->discount + $this->tax;
        $this->paid_amount      = $this->payments->sum('amount');
        $this->remaining_amount = max(0, $this->total - $this->paid_amount);

        if ($this->remaining_amount <= 0) {
            $this->status = OrderStatus::Paid;
        } elseif ($this->paid_amount > 0) {
            $this->status = OrderStatus::PartiallyPaid;
        }
    }
}
