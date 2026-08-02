<?php

namespace App\Models;

use App\Traits\HasSettingTranslationUpdate;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model implements TranslatableContract
{
    use HasFactory, Translatable, HasSettingTranslationUpdate;

    protected $guarded = ['id'];
        public array $translatedAttributes = ['value'];
    protected $with = ['translations'];

    public function scopePrimary($query)
    {
        return $query->where('user_id', auth()->id());
    }

}
