<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryOfZadTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'notes'];
}

