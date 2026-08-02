<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class LandMark extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'description',
        'address',
        'address_type',
        'image',
        'user_id',
        'type',
        'status',
        'active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg', 'image/webp'])
            ->useDisk('media');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('small')
            ->width(250)
            ->height(250)
            ->sharpen(10)
            ->nonQueued()
            ->performOnCollections('image');

        $this->addMediaConversion('medium')
            ->width(500)
            ->height(500)
            ->sharpen(10)
            ->nonQueued()
            ->performOnCollections('image');
    }
}
