<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TeacherVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'customer_id',
        'visitor_name',
        'visitor_phone',
        'visited_at',
        'notes',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    // ── Relations ────────────────────────────────────────────────────────────

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function commission(): HasOne
    {
        return $this->hasOne(TeacherCommission::class);
    }
}
