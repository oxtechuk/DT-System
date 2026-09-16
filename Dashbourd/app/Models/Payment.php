<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
 'amount' => 'decimal:2',
 'paid_at' => 'datetime',
 ];

 // ── Relationships ──

 public function order()
 {
 return $this->belongsTo(Order::class);
 }

 public function customer()
 {
 return $this->belongsTo(Customer::class);
 }

 public function deal()
 {
 return $this->hasOneThrough(Deal::class, Order::class, 'id', 'id', 'order_id', 'deal_id');
 }

 // ── Scopes ──

 public function scopeToday($query)
 {
 return $query->whereDate('paid_at', today());
 }

 public function scopeByMethod($query, string $method)
 {
 return $query->where('method', $method);
 }

 // ── Helpers ──

 public static function generateNumber(): string
 {
 $last = static::latest('id')->value('payment_number');
 $next = $last ? (intval(substr($last, 1)) + 1) : 1;
 return 'P' . str_pad($next, 5, '0', STR_PAD_LEFT);
 }
}
