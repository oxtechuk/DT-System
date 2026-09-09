<?php

namespace App\Models;

use App\Enums\DealStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Deal extends Model
{
    use HasFactory;

    protected $fillable = [
        'deal_number',
        'customer_id',
        'booking_id',
        'room_id',
        'workspace_type_id',
        'started_at',
        'ended_at',
        'duration_minutes',
        'status',
        'notes',
        'created_by',
        'closed_by',
    ];

    protected $casts = [
        'status'     => DealStatus::class,
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
    ];

    // ─── Relationships ───────────────────────────────────────────────────────────

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function workspaceType(): BelongsTo
    {
        return $this->belongsTo(WorkspaceType::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────────

    public function isOpen(): bool
    {
        return $this->status === DealStatus::Open;
    }

    public function isClosed(): bool
    {
        return $this->status === DealStatus::Closed;
    }

    public function actualDurationMinutes(): int
    {
        $start = $this->started_at;
        $end   = $this->ended_at ?? now();

        return (int) ceil($start->diffInSeconds($end) / 60);
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────────

    public function scopeOpen($query)
    {
        return $query->where('status', DealStatus::Open->value);
    }

    public function scopeForRoom($query, int $roomId)
    {
        return $query->where('room_id', $roomId);
    }
}
