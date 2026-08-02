<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegionTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];
    public function getNameAttribute($name)
    {
        return str_replace("'", '', $name);
    }
}
