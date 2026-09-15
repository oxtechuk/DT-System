<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'customer_id',
        'deal_id',
        'booking_id',
        'subtotal',
        'discount',
        'tax',
        'total',
        'paid_amount',
        'remaining_amount',
        'status',
        'closed_at',
        'created_by',
        'closed_by',
    ];

    protected $casts = [
        'subtotal'         => 'decimal:2',
        'discount'         => 'decimal:2',
        'tax'              => 'decimal:2',
        'total'            => 'decimal:2',
        'paid_amount'      => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'closed_at'        => 'datetime',
    ];

    // ── Relationships ──

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // ── Scopes ──

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    // ── Helpers ──

    public static function generateNumber(): string
    {
        $last = static::withTrashed()->latest('id')->value('order_number');
        $next = $last ? (intval(substr($last, 1)) + 1) : 1;
        return 'O' . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Recalculate totals from items.
     */
    public function recalculate(): void
    {
        $subtotal = $this->items()->sum('total');
        $total    = $subtotal - $this->discount + $this->tax;
        $remaining = max(0, $total - $this->paid_amount);

        $this->update([
            'subtotal'         => $subtotal,
            'total'            => $total,
            'remaining_amount' => $remaining,
            'status'           => $remaining <= 0 ? 'paid' : ($this->paid_amount > 0 ? 'partially_paid' : 'open'),
        ]);
    }
}
