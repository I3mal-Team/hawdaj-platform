<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryOfSwalefTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'notes'];
}
