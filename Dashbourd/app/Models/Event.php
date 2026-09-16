<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'badge_text',
        'description',
        'speaker_name',
        'speaker_title',
        'location',
        'event_date',
        'time_text',
        'price',
        'banner_theme',
        'registration_url',
        'capacity',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'event_date' => 'date',
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'capacity' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->active()->where('event_date', '>=', today())->orderBy('event_date', 'asc');
    }

    public function scopeFeatured($query)
    {
        return $query->active()->where('is_featured', true);
    }
}
