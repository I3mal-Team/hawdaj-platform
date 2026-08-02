<?php

namespace App\Models;

use App\Observers\SliderObserver;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Slider extends Model implements TranslatableContract, HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use Translatable;

    protected $fillable = [
        'order_id',
        'active',
        'link',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public $translatedAttributes = [
        'title',
    ];

    protected $with = ['translations'];

    protected $translationForeignKey = 'slider_id';

    protected static function boot(): void
    {
        parent::boot();
        static::observe(SliderObserver::class);
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
            ->width(400)
            ->height(250)
            ->sharpen(10)
            ->nonQueued()
            ->performOnCollections('image');

        $this->addMediaConversion('medium')
            ->width(800)
            ->height(500)
            ->sharpen(10)
            ->nonQueued()
            ->performOnCollections('image');
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_id')->orderBy('id');
    }
}
