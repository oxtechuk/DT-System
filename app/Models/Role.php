<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'color'];

    // ── Relations ──────────────────────────────────────────────────────────

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // ── Predefined Role Colors ─────────────────────────────────────────────
    public function badgeColor(): string
    {
        return match ($this->slug) {
            'owner'     => 'bg-purple-100 text-purple-700 border-purple-200',
            'manager'   => 'bg-blue-100 text-blue-700 border-blue-200',
            'cashier'   => 'bg-green-100 text-green-700 border-green-200',
            'reception' => 'bg-amber-100 text-amber-700 border-amber-200',
            'staff'     => 'bg-gray-100 text-gray-600 border-gray-200',
            default     => 'bg-slate-100 text-slate-600 border-slate-200',
        };
    }
}
