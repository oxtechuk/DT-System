<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashierAlertSchedule extends Model
{
    protected $fillable = [
        'title',
        'description',
        'scheduled_at',
        'next_alert_at',
        'snooze_count',
        'status',
        'created_by',
        'completed_at',
    ];

    protected $casts = [
        'scheduled_at'  => 'datetime',
        'next_alert_at' => 'datetime',
        'completed_at'  => 'datetime',
        'snooze_count'  => 'integer',
    ];

    // ── Relations ──────────────────────────────────────────────────────────

    public function logs(): HasMany
    {
        return $this->hasMany(CashierAlertLog::class, 'schedule_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Helpers ────────────────────────────────────────────────────────────

    /**
     * Calculate the next snooze delay in minutes based on how many times
     * the cashier has already pressed "Remind later":
     *
     * 1st snooze  → 5 min
     * 2nd snooze  → 15 min
     * 3rd+ snooze → 60 min
     */
    public function nextSnoozeMinutes(): int
    {
        return match ($this->snooze_count) {
            0       => 5,
            1       => 15,
            default => 60,
        };
    }

    /**
     * Human-readable label for the "Remind Later" button.
     */
    public function snoozeLabel(): string
    {
        return match ($this->snooze_count) {
            0       => 'تذكير لاحقاً (بعد 5 دقائق)',
            1       => 'تذكير لاحقاً (بعد 15 دقيقة)',
            default => 'تذكير لاحقاً (بعد ساعة)',
        };
    }

    // ── Scopes ─────────────────────────────────────────────────────────────

    /** تنبيهات مستحقة الآن (next_alert_at <= now AND status in pending/snoozed) */
    public function scopeDueNow($query)
    {
        return $query
            ->whereIn('status', ['pending', 'snoozed'])
            ->where('next_alert_at', '<=', now());
    }
}
