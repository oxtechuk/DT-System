<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Deal extends Model
{
 use HasFactory, SoftDeletes;

 protected $fillable = [
 'deal_number',
 'customer_id',
 'booking_id',
 'room_id',
 'workspace_type_id',
 'pricing_rule_id',
 'applied_price',
 'started_at',
 'ended_at',
 'duration_minutes',
 'billable_duration_minutes',
 'status',
 'notes',
 'created_by',
 'closed_by',
 'hubspot_deal_id',
 'hubspot_synced_at',
 ];

 protected $casts = [
 'started_at' => 'datetime',
 'ended_at' => 'datetime',
 'applied_price' => 'decimal:2',
 'hubspot_synced_at' => 'datetime',
 ];

 // ── Relationships ──

 public function customer()
 {
 return $this->belongsTo(Customer::class);
 }

 public function room()
 {
 return $this->belongsTo(Room::class);
 }

 public function workspaceType()
 {
 return $this->belongsTo(WorkspaceType::class);
 }

 public function booking()
 {
 return $this->belongsTo(Booking::class);
 }

 public function order()
 {
 return $this->hasOne(Order::class);
 }

 public function pricingRule()
 {
 return $this->belongsTo(PricingRule::class);
 }

 public function createdBy()
 {
 return $this->belongsTo(\App\Models\User::class, 'created_by');
 }

 public function closedBy()
 {
 return $this->belongsTo(\App\Models\User::class, 'closed_by');
 }

 // ── Scopes ──

 public function scopeOpen(Builder $query): Builder
 {
 return $query->where('status', 'open');
 }

 public function scopeToday(Builder $query): Builder
 {
 return $query->whereDate('started_at', today());
 }

 // ── Accessors ──

 public function getIsOpenAttribute(): bool
 {
 return $this->status === 'open';
 }

 /**
 * Live duration in minutes from started_at to now (for open deals).
 */
 public function getLiveDurationMinutesAttribute(): int
 {
 if ($this->status !== 'open') {
 return (int) $this->duration_minutes;
 }
 return (int) now()->diffInMinutes($this->started_at);
 }

 // ── Helpers ──

 public static function generateNumber(): string
 {
 $numbers = static::withTrashed()->pluck('deal_number');
 $max = 0;
 foreach ($numbers as $num) {
 if (preg_match('/^D(\d+)$/', $num, $matches)) {
 $val = (int) $matches[1];
 if ($val > $max) {
 $max = $val;
 }
 }
 }
 $next = $max + 1;
 do {
 $candidate = 'D' . str_pad($next, 5, '0', STR_PAD_LEFT);
 $exists = static::withTrashed()->where('deal_number', $candidate)->exists();
 if ($exists) {
 $next++;
 }
 } while ($exists);

 return $candidate;
 }
}
