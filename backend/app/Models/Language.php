<?php

namespace App\Models;

use App\Observers\LanguageObserver;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    protected $fillable = [
        'image',
        'active',
        'order_id',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public $translatedAttributes = [
        'name',
        'description',
    ];

    protected $with = ['translations'];
    protected $translationForeignKey = 'language_id';

//    protected static function boot()
//    {
//        parent::boot();
//
//        static::observe(LanguageObserver::class);
//    }
}
