<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'teacher_visit_id',
        'amount',
        'commission_rate_snapshot',
        'visit_base_value_snapshot',
        'status',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission_rate_snapshot' => 'decimal:2',
        'visit_base_value_snapshot' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    // ── Relations ────────────────────────────────────────────────────────────

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function visit(): BelongsTo
    {
        return $this->belongsTo(TeacherVisit::class, 'teacher_visit_id');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    public function markAsPaid(): bool
    {
        return $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'قيد الانتظار',
            'paid' => 'مدفوع',
            'cancelled' => 'ملغي',
            default => $this->status,
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'paid' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            'pending' => 'bg-amber-50 text-amber-700 border border-amber-200',
            'cancelled' => 'bg-rose-50 text-rose-700 border border-rose-200',
            default => 'bg-neutral-100 text-neutral-600 border border-neutral-200',
        };
    }
}
