<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'commission_rate',
        'qr_code',
        'bio',
        'is_active',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // ── Boot ────────────────────────────────────────────────────────────────

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Teacher $teacher) {
            if (empty($teacher->qr_code)) {
                $teacher->qr_code = strtoupper('TCH-'.Str::random(8));
            }
        });
    }

    // ── Relations ────────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function visits(): HasMany
    {
        return $this->hasMany(TeacherVisit::class);
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(TeacherCommission::class);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    public function totalVisits(): int
    {
        return $this->visits()->count();
    }

    public function totalEarnings(): float
    {
        return (float) $this->commissions()->where('status', 'paid')->sum('amount');
    }

    public function pendingCommission(): float
    {
        return (float) $this->commissions()->where('status', 'pending')->sum('amount');
    }

    public function qrUrl(): string
    {
        return route('scan.show', $this->qr_code);
    }

    public function qrImageUrl(): string
    {
        $url = urlencode($this->qrUrl());

        return "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={$url}";
    }

    /**
     * Get the associated Customer profile for the teacher to access the mobile customer portal.
     */
    public function customer(): ?Customer
    {
        $phone = $this->user?->phone;
        $cleanPhone = preg_replace('/[^\d]/', '', (string) $phone);
        $last9 = strlen((string) $cleanPhone) >= 9 ? substr((string) $cleanPhone, -9) : $cleanPhone;
        $email = $this->user?->email;

        return Customer::where(function ($q) use ($phone, $last9, $email) {
            if (! empty($phone)) {
                $q->where('phone', $phone);
                if (! empty($last9)) {
                    $q->orWhere('phone', 'like', "%{$last9}");
                }
            }
            if (! empty($email)) {
                $q->orWhere('email', $email);
            }
        })->first();
    }
}
