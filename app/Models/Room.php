<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
 use HasFactory;

 protected $fillable = [
 'name', 'code', 'capacity', 'status', 'color', 'description',
 ];

 // ── Relationships ──

 public function deals()
 {
 return $this->hasMany(Deal::class);
 }

 public function bookings()
 {
 return $this->hasMany(Booking::class);
 }

 public function activeDeals()
 {
 return $this->hasMany(Deal::class)->where('status', 'open');
 }

 // ── Scopes ──

 public function scopeActive($query)
 {
 return $query->where('status', 'active');
 }

 // ── Accessors ──

 public function getActiveDealsListAttribute()
 {
 return $this->relationLoaded('activeDeals')
 ? $this->activeDeals
 : $this->activeDeals()->with(['customer', 'workspaceType'])->get();
 }

 public function getIsPrivateOccupiedAttribute(): bool
 {
 return $this->active_deals_list->contains(function ($deal) {
 $code = $deal->workspaceType?->code;
 return in_array($code, ['private', 'meeting']);
 });
 }

 public function getOccupancyCountAttribute(): int
 {
 return $this->active_deals_list->count();
 }

 public function getRemainingCapacityAttribute(): int
 {
 $cap = max(1, (int) ($this->capacity ?: 1));
 return max(0, $cap - $this->occupancy_count);
 }

 public function getIsAvailableAttribute(): bool
 {
 if ($this->status !== 'active') {
 return false;
 }

 // If reserved as Private or Meeting, room is completely locked even for 1 person
 if ($this->is_private_occupied) {
 return false;
 }

 // Shared area: available until capacity is fully reached
 $cap = max(1, (int) ($this->capacity ?: 1));
 return $this->occupancy_count < $cap;
 }

 public function getDisplayColorAttribute(): string
 {
 return $this->color ?? '#4E8F35';
 }
}
