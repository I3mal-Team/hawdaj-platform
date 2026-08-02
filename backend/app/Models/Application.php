<?php

namespace App\Models;

use App\Observers\ApplicationObserver;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasPlaceTranslationUpdate;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Application extends Model implements TranslatableContract, HasMedia
{
    use HasFactory , Translatable , SoftDeletes , HasPlaceTranslationUpdate, InteractsWithMedia;

    protected $fillable = [
        'image',
        'type',
        'link',
        'ios_link',
        'android_link',
        'active',
        'categories',
        'show_in_home',
        'order_id',
    ];

    protected $casts = [
        'categories' => 'array',
        'active' => 'boolean',
        'show_in_home' => 'boolean',
    ];

    public $translatedAttributes = [
        'title',
        'description'
    ];

    protected $with = ['translations'];
    protected $translationForeignKey = 'application_id';

    protected static function boot()
    {
        parent::boot();

        static::observe(ApplicationObserver::class);
    }

    public function allCategories()
    {
        return \App\Models\CategoryOfApplication::whereIn('id',$this->categories)->get();
    }

    public function ratings()
    {
        return $this->hasMany(Rate::class, 'parent_id')->where('type', 'app')->orderBy('id', 'desc');
    }

    public function getRateAttribute()
    {
        return $this->ratings->count() ? round($this->ratings->sum('rate') / $this->ratings->count(), 1) : 0;
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
