<?php

namespace App\Models;

use App\Observers\GuideObserver;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Guide extends Model implements TranslatableContract, HasMedia
{
    use HasFactory, Translatable, InteractsWithMedia;

    protected $fillable = [
        'regions',
        'languages',
        'experience',
        'facebook',
        'x',
        'twitter',
        'linkedin',
        'instagram',
        'personal_account',
        'show_in_home',
        'active',
        'user_id',
        'order_id',
    ];

    protected $casts = [
        'regions' => 'array',
        'languages' => 'array',
        'show_in_home' => 'boolean',
    ];

    public $translatedAttributes = [
        'name',
        'nickName',
        'description',
    ];

    protected $with = ['translations'];
    protected $translationForeignKey = 'guide_id';

    protected static function boot()
    {
        parent::boot();

        static::observe(GuideObserver::class);
    }

    public function getTypeAttribute()
    {
        return 'guide';
    }

    public function allLanguages()
    {
        return Language::whereIn('id', $this->languages)->get();
    }

    public function allRegions()
    {
        return Region::whereIn('id', $this->regions)->get();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rate::class, 'parent_id')->where('type', 'guide')->orderBy('id', 'desc');
    }

    public function getRateAttribute()
    {
        return $this->ratings->count() ? round($this->ratings->sum('rate') / $this->ratings->count(), 1) : 0;
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class, "parent_id")->where('type', '=', 'guides');
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
