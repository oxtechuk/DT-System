<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_number',
        'customer_id',
        'room_id',
        'workspace_type_id',
        'start_at',
        'end_at',
        'status',
        'source',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    // ── Relationships ──

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function workspaceType()
    {
        return $this->belongsTo(WorkspaceType::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    // ── Static Helpers ──

    public static function generateNumber(): string
    {
        $prefix = 'BK-' . date('Ymd') . '-';
        $last = static::where('booking_number', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        if ($last) {
            $sequence = (int) substr($last->booking_number, -4) + 1;
        } else {
            $sequence = 1;
        }

        return $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    // ── Accessors ──

    public function getDurationHoursAttribute(): float
    {
        if ($this->start_at && $this->end_at) {
            return round($this->start_at->diffInMinutes($this->end_at) / 60, 1);
        }
        return 0;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'confirmed' => 'مؤكد',
            'pending' => 'قيد الانتظار',
            'checked_in' => 'تم تسجيل الدخول',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي',
            'no_show' => 'لم يحضر',
            default => $this->status,
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'confirmed' => 'bg-[#EBF4E8] text-[#3B6E28] border border-[#DCE8D4]',
            'checked_in' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            'pending' => 'bg-amber-50 text-amber-700 border border-amber-200',
            'completed' => 'bg-neutral-100 text-neutral-600 border border-neutral-200',
            'cancelled', 'no_show' => 'bg-rose-50 text-rose-700 border border-rose-200',
            default => 'bg-neutral-100 text-neutral-600 border border-neutral-200',
        };
    }
}
