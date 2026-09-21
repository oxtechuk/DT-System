<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'role_id',
        'status',
        'notes',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Relations ──────────────────────────────────────────────────────────

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    // ── Helpers ────────────────────────────────────────────────────────────

    public function hasPermission(string $permissionName): bool
    {
        if (!$this->role) return false;
        return $this->role->permissions()->where('name', $permissionName)->exists();
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /** Initials for avatar fallback */
    public function initials(): string
    {
        $parts = explode(' ', $this->name);
        $init  = mb_substr($parts[0], 0, 1);
        if (count($parts) > 1) {
            $init .= mb_substr($parts[1], 0, 1);
        }
        return mb_strtoupper($init);
    }
}
