<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, SoftDeletes;

 protected $fillable = [
 'full_name',
 'name',
 'phone',
 'email',
 'password',
 'referral_code',
 'referred_by_id',
 'customer_type',
 'classification',
 'source',
 'status',
 'notes',
 'last_active_at',
 'hubspot_contact_id',
 'hubspot_synced_at',
 ];

 protected $hidden = [
 'password',
 'remember_token',
 ];

 protected $casts = [
 'password' => 'hashed',
 'hubspot_synced_at' => 'datetime',
 'last_active_at' => 'datetime',
 ];

 public static function classifications(): array
 {
     return [
         'freelancer' => 'فريلانسر / عمل حر',
         'high_school' => 'طالب ثانوي',
         'university' => 'طالب جامعي',
         'lecturer' => 'محاضر / مدرب',
         'other' => 'أخرى / عام',
     ];
 }

 public function getClassificationLabelAttribute(): string
 {
     $map = static::classifications();
     return $map[$this->classification ?? ''] ?? ($this->classification ?: 'غير محدد');
 }

 public function getIsAppOnlineAttribute(): bool
 {
     return $this->last_active_at !== null && $this->last_active_at->diffInMinutes(now()) <= 15;
 }

 // ── Relationships ──

 public function bookings()
 {
 return $this->hasMany(Booking::class);
 }

 public function deals()
 {
 return $this->hasMany(Deal::class);
 }

 public function orders()
 {
 return $this->hasMany(Order::class);
 }

 public function payments()
 {
 return $this->hasMany(Payment::class);
 }

 // ── Scopes ──

 public function scopeActive($query)
 {
 return $query->where('status', 'active');
 }

 public function scopeSearch($query, string $term)
 {
 return $query->where(function ($q) use ($term) {
 $q->where('full_name', 'like', "%{$term}%")
 ->orWhere('phone', 'like', "%{$term}%")
 ->orWhere('email', 'like', "%{$term}%");
 });
 }

 // ── Accessors ──

 public function getNameAttribute(): string
 {
 return $this->full_name ?? '';
 }

 public function setNameAttribute($value): void
 {
 $this->attributes['full_name'] = $value;
 }

 public function getInitialsAttribute(): string
 {
 if (empty($this->full_name)) {
 return '';
 }
 $words = array_values(array_filter(explode(' ', trim($this->full_name))));
 if (count($words) >= 2) {
 return mb_strtoupper(
 mb_substr($words[0], 0, 1, 'UTF-8') . mb_substr($words[1], 0, 1, 'UTF-8'),
 'UTF-8'
 );
 }
 return mb_strtoupper(mb_substr($this->full_name, 0, 2, 'UTF-8'), 'UTF-8');
 }

 public function referredBy()
 {
 return $this->belongsTo(Customer::class, 'referred_by_id');
 }

 public function referrals()
 {
 return $this->hasMany(Customer::class, 'referred_by_id');
 }

 // ── Referral & Loyalty Helpers ──

 public static function generateUniqueReferralCode(string $name = ''): string
 {
 $prefix = 'DT';
 if (!empty($name)) {
 $clean = preg_replace('/[^A-Za-z0-9]/', '', $name);
 if (!empty($clean)) {
 $prefix = strtoupper(substr($clean, 0, 3));
 }
 }
 do {
 $code = $prefix . rand(1000, 9999);
 } while (static::where('referral_code', $code)->exists());

 return $code;
 }

 /**
 * Calculate loyalty progress:
 * Rule: 5 visits + at least one visit >= 180 min (3h) -> 6th visit free.
 */
 public function getLoyaltyStatusAttribute(): array
 {
 $minMinutes = (int) Setting::get('loyalty_min_duration_minutes', 180);
 $requiredVisits = (int) Setting::get('loyalty_required_visits', 5);

 // All closed visits ordered by date
 $closedDeals = $this->deals()
 ->where('status', 'closed')
 ->orderBy('started_at', 'asc')
 ->get(['id', 'started_at', 'ended_at', 'duration_minutes']);

 $totalClosedVisits = $closedDeals->count();

 // Cycle length is (requiredVisits + 1) e.g. 5 visits + 1 free visit = 6
 $cycleLength = $requiredVisits + 1;
 $currentCycleVisitsCount = $totalClosedVisits % $cycleLength;

 // Take visits in current cycle
 $currentCycleDeals = $closedDeals->slice(-$currentCycleVisitsCount);

 // Check if any visit in current cycle exceeded minMinutes
 $hasLongVisit = $currentCycleDeals->contains(function ($deal) use ($minMinutes) {
 return ($deal->duration_minutes ?? 0) >= $minMinutes;
 });

 // Is 6th visit reward active?
 // Reward is ready if they have reached the required visits and completed the 3h session
 $rewardReady = ($currentCycleVisitsCount >= $requiredVisits) && $hasLongVisit;

 return [
 'total_visits' => $totalClosedVisits,
 'required_visits' => $requiredVisits,
 'current_visits' => min($currentCycleVisitsCount, $requiredVisits),
 'has_long_session' => $hasLongVisit,
 'min_minutes' => $minMinutes,
 'min_hours' => round($minMinutes / 60, 1),
 'reward_ready' => $rewardReady,
 'next_free' => $rewardReady,
 'progress_percent' => min(100, round(($currentCycleVisitsCount / $requiredVisits) * 100)),
 ];
 }
}

