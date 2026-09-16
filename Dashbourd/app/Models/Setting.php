<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
 use HasFactory;

 protected $fillable = [
 'key', 'value', 'type', 'group', 'label',
 ];

 // ── Static helpers ──

 /**
 * Get a setting value by key, with optional default.
 */
 public static function get(string $key, mixed $default = null): mixed
 {
 $setting = static::where('key', $key)->first();
 if (!$setting) return $default;

 return match ($setting->type) {
 'boolean' => (bool) $setting->value,
 'json' => json_decode($setting->value, true),
 default => $setting->value,
 };
 }

 /**
 * Set or create a setting value.
 */
 public static function set(string $key, mixed $value, string $type = 'string', string $group = 'general'): void
 {
 static::updateOrCreate(
 ['key' => $key],
 [
 'value' => is_array($value) ? json_encode($value) : $value,
 'type' => $type,
 'group' => $group,
 ]
 );
 }

 /**
 * Get all settings as a flat key->value array.
 */
 public static function getAllAsArray(): array
 {
 return static::all()->pluck('value', 'key')->toArray();
 }
}
