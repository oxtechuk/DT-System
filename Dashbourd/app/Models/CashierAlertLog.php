<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashierAlertLog extends Model
{
    protected $fillable = [
        'schedule_id',
        'action',
        'snoozed_minutes',
        'acted_by',
    ];

    protected $casts = [
        'snoozed_minutes' => 'integer',
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(CashierAlertSchedule::class, 'schedule_id');
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acted_by');
    }
}
