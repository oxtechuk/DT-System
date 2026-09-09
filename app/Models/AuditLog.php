<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    public $timestamps = false;            // only created_at, no updated_at
    public $incrementing = true;

    protected $fillable = [
        'user_id',
        'action',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Audit logs are immutable — no updates allowed.
     */
    public static function boot(): void
    {
        parent::boot();

        static::updating(function () {
            throw new \RuntimeException('Audit logs are immutable and cannot be updated.');
        });
    }

    /**
     * Create a log entry quickly from anywhere.
     */
    public static function record(
        string $action,
        Model $subject,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?int $userId = null
    ): self {
        return static::create([
            'user_id'        => $userId ?? auth()->id(),
            'action'         => $action,
            'auditable_type' => get_class($subject),
            'auditable_id'   => $subject->getKey(),
            'old_values'     => $oldValues,
            'new_values'     => $newValues,
            'ip_address'     => request()->ip(),
            'user_agent'     => request()->userAgent(),
            'created_at'     => now(),
        ]);
    }
}
