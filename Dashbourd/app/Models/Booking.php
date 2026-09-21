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

    public function getDurationFormattedAttribute(): string
    {
        if (!$this->start_at || !$this->end_at) return '';
        $mins = $this->start_at->diffInMinutes($this->end_at);
        $days = (int) ($mins / (24 * 60));
        $remainingMins = $mins % (24 * 60);
        $hours = (int) ($remainingMins / 60);

        if ($days >= 30) {
            $months = (int) ($days / 30);
            return "اشتراك {$months} شهر";
        }
        if ($days >= 7) {
            $weeks = (int) ($days / 7);
            return "اشتراك {$weeks} أسبوع";
        }
        if ($days > 0) {
            return "{$days} يوم" . ($hours > 0 ? " و {$hours} ساعة" : "");
        }
        return "{$hours} ساعة";
    }

    public function getIsMultiDayAttribute(): bool
    {
        if (!$this->start_at || !$this->end_at) return false;
        return $this->start_at->diffInHours($this->end_at) >= 20;
    }

    public function getCalendarColorAttribute(): string
    {
        if ($this->status === 'cancelled') return '#9CA3AF'; // Gray
        if ($this->status === 'checked_in') return '#059669'; // Emerald
        
        $wsCode = $this->workspaceType?->code ?? '';
        if (in_array($wsCode, ['monthly', 'weekly', 'subscription']) || $this->is_multi_day) {
            return '#2563EB'; // Blue for subscriptions
        }
        if (in_array($wsCode, ['private', 'meeting']) || ($this->room && in_array($this->room->type, ['private', 'meeting']))) {
            return '#4E8F35'; // DDT Green for private/meeting rooms
        }
        return '#D97706'; // Amber for shared desk/hourly space
    }

    public function getBookingTypeLabelAttribute(): string
    {
        $wsCode = $this->workspaceType?->code ?? '';
        if (in_array($wsCode, ['monthly', 'weekly', 'subscription']) || $this->is_multi_day) {
            return 'اشتراك مدة (أسبوعي/شهري)';
        }
        if (in_array($wsCode, ['private', 'meeting']) || ($this->room && in_array($this->room->type, ['private', 'meeting']))) {
            return 'حجز قاعة خاصة بالساعة';
        }
        return 'حجز مساحة مشتركة بالساعة';
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

    public function toCalendarEvent(): array
    {
        $customerName = $this->customer ? $this->customer->full_name : 'عميل مباشر';
        $roomName = $this->room ? $this->room->name : 'مساحة عامة';
        $typeLabel = $this->booking_type_label;
        $color = $this->calendar_color;

        return [
            'id' => (string) $this->id,
            'title' => "{$customerName} ({$roomName})",
            'start' => $this->start_at ? $this->start_at->toIso8601String() : null,
            'end' => $this->end_at ? $this->end_at->toIso8601String() : null,
            'allDay' => $this->is_multi_day,
            'backgroundColor' => $color,
            'borderColor' => $color,
            'textColor' => '#ffffff',
            'extendedProps' => [
                'id' => $this->id,
                'booking_number' => $this->booking_number,
                'customer_id' => $this->customer_id,
                'customer_name' => $customerName,
                'customer_phone' => $this->customer?->phone ?? '',
                'customer_email' => $this->customer?->email ?? '',
                'room_id' => $this->room_id,
                'room_name' => $roomName,
                'room_type' => $this->room?->type_label ?? 'غرفة',
                'workspace_type_id' => $this->workspace_type_id,
                'workspace_type_name' => $this->workspaceType?->name ?? 'مساحة عمل',
                'booking_type_label' => $typeLabel,
                'duration_formatted' => $this->duration_formatted,
                'duration_hours' => $this->duration_hours,
                'is_multi_day' => $this->is_multi_day,
                'start_formatted' => $this->start_at ? $this->start_at->format('Y-m-d H:i') : '',
                'end_formatted' => $this->end_at ? $this->end_at->format('Y-m-d H:i') : '',
                'start_time' => $this->start_at ? $this->start_at->format('h:i A') : '',
                'end_time' => $this->end_at ? $this->end_at->format('h:i A') : '',
                'start_date' => $this->start_at ? $this->start_at->format('Y-m-d') : '',
                'status' => $this->status,
                'status_label' => $this->status_label,
                'notes' => $this->notes ?? '',
                'can_checkin' => in_array($this->status, ['confirmed', 'pending']),
            ]
        ];
    }
}
