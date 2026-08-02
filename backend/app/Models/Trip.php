<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Trip extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'enhanced_data' => 'array',
        'morning_descriptions' => 'array',
        'evening_descriptions' => 'array',
        'items' => 'array',
        'is_enhanced' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function setSlugAttribute()
    {
        $this->attributes['token'] = Str::random(20);
    }

    public function region1Object()
    {
        return $this->belongsTo(Region::class, 'region1');
    }

    public function region2Object()
    {
        return $this->belongsTo(Region::class, 'region2');
    }

    // Enhanced trip methods
    public function scopeEnhanced($query)
    {
        return $query->where('is_enhanced', true);
    }

    public function scopeRegular($query)
    {
        return $query->where('is_enhanced', false);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Get enhanced data with periods
    public function getEnhancedDaysAttribute()
    {
        if (!$this->is_enhanced || !$this->enhanced_data) {
            return [];
        }

        return $this->enhanced_data;
    }

    // Get morning description for specific day
    public function getMorningDescription($dayNumber)
    {
        if (!$this->morning_descriptions || !isset($this->morning_descriptions[$dayNumber - 1])) {
            return 'استمتع بجولة صباحية رائعة في هذه الأماكن المميزة';
        }

        return $this->morning_descriptions[$dayNumber - 1];
    }

    // Get evening description for specific day
    public function getEveningDescription($dayNumber)
    {
        if (!$this->evening_descriptions || !isset($this->evening_descriptions[$dayNumber - 1])) {
            return 'اختتم يومك بزيارة هذه الأماكن الساحرة';
        }

        return $this->evening_descriptions[$dayNumber - 1];
    }

    // Get total places count for enhanced trips
    public function getTotalPlacesAttribute()
    {
        if ($this->is_enhanced && $this->enhanced_data) {
            $total = 0;
            foreach ($this->enhanced_data as $day) {
                $total += count($day['morning']['places'] ?? []);
                $total += count($day['evening']['places'] ?? []);
            }
            return $total;
        }

        // For regular trips, count items
        if ($this->items && is_array($this->items)) {
            $total = 0;
            foreach ($this->items as $dayItems) {
                $total += is_array($dayItems) ? count($dayItems) : 0;
            }
            return $total;
        }

        return 0;
    }
}
