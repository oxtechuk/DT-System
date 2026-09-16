<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HubSpotSyncLog extends Model
{
 use HasFactory;

 protected $table = 'hubspot_sync_logs';

 protected $fillable = [
 'entity_type',
 'entity_id',
 'hubspot_id',
 'action',
 'status',
 'payload',
 'response',
 'error_message',
 ];

 protected $casts = [
 'created_at' => 'datetime',
 'updated_at' => 'datetime',
 ];

 public function getStatusBadgeAttribute(): string
 {
 return match ($this->status) {
 'success' => '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-600 border border-emerald-500/20">ناجح</span>',
 'failed' => '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-600 border border-rose-500/20">فشل</span>',
 default => '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-600 border border-amber-500/20">قيد الانتظار</span>',
 };
 }
}
