<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffNotification extends Model
{
    protected $fillable = [
        'user_id', 'sent_by', 'title', 'body',
        'icon', 'color', 'scheduled_at', 'read_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'read_at'      => 'datetime',
    ];

    // ── Relations ──────────────────────────────────────────────────────────
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    // ── Scopes ──────────────────────────────────────────────────────────────
    /** إشعارات مستحقة الآن وغير مقروءة */
    public function scopeDueUnread($query)
    {
        return $query
            ->whereNull('read_at')
            ->where('scheduled_at', '<=', now());
    }

    // ── Helpers ─────────────────────────────────────────────────────────────
    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    /** Tailwind classes for icon background based on color */
    public function colorClasses(): array
    {
        return match ($this->color) {
            'blue'  => ['bg' => 'bg-blue-100',  'text' => 'text-blue-600',  'border' => 'border-blue-200'],
            'amber' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-600', 'border' => 'border-amber-200'],
            'red'   => ['bg' => 'bg-red-100',   'text' => 'text-red-600',   'border' => 'border-red-200'],
            default => ['bg' => 'bg-green-100', 'text' => 'text-green-600', 'border' => 'border-green-200'],
        };
    }
}
