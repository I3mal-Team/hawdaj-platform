<?php

namespace App\Models;

use App\Observers\MenuObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasMenuTranslationUpdate;

class Menu extends Model
{
    use HasFactory;
    use Translatable;
    use SoftDeletes;
    use HasMenuTranslationUpdate;

    public $translatedAttributes = ['description', 'title'];
    protected $fillable = ['image' , 'zad_id', 'price', 'order_id'];
    protected $table = 'menus';
    protected $with = ['translations'];
    protected $translationForeignKey = 'menu_id';

    protected static function boot()
    {
        parent::boot();
        static::observe(MenuObserver::class);
    }

    public function zad(): BelongsTo
    {
        return $this->belongsTo(ZadElgadel::class , 'zad_id');
    }
}
