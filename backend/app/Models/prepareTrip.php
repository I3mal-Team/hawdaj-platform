<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class prepareTrip extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'enhanced_generated_data' => 'array',
        'categories_array' => 'array',
        'price_range_array' => 'array',
        'items' => 'array',
        'places' => 'array',
        'is_enhanced' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

//    protected $table = 'prepare_trips';

//    protected $fillable = [
//        'items',
//        'places',
//        'selected_categories',
//        'start_date',
//        'end_date',
//        'funny',
//        'funny_place_per_day',
//        'days',
//        'lat1',
//        'long1',
//        'lat2',
//        'long2',
//        'token',
//        'user_id',
//        'region1',
//        'region2',
//    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function region1Object()
    {
        return $this->belongsTo(Region::class, 'region1');
    }

    public function region2Object()
    {
        return $this->belongsTo(Region::class, 'region2');
    }

    // Enhanced prepare trip methods
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

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Get enhanced generated data
    public function getEnhancedDataAttribute()
    {
        return $this->enhanced_generated_data ?? [];
    }

    // Check if this prepare trip has been used to create a trip
    public function createdTrip()
    {
        return Trip::where('prepareToken', $this->token)->first();
    }
}
