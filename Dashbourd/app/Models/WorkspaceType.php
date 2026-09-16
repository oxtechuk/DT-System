<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkspaceType extends Model
{
 use HasFactory;

 protected $fillable = [
 'name', 'code', 'pricing_mode', 'active',
 ];

 protected $casts = [
 'active' => 'boolean',
 ];

 // ── Relationships ──

 public function pricingRules()
 {
 return $this->hasMany(PricingRule::class);
 }

 public function deals()
 {
 return $this->hasMany(Deal::class);
 }

 public function bookings()
 {
 return $this->hasMany(Booking::class);
 }

 // ── Scopes ──

 public function scopeActive($query)
 {
 return $query->where('active', true);
 }

 // ── Helpers ──

 /**
 * Get active pricing rules for this type effective on a given date.
 */
 public function getEffectivePricingRules(?\Carbon\Carbon $date = null)
 {
 $date ??= now();
 return $this->pricingRules()
 ->active()
 ->effectiveOn($date)
 ->orderBy('duration_minutes')
 ->get();
 }
}
