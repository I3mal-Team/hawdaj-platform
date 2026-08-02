<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuideTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'nikeName', 'description'];

    protected $table = 'guide_translation';
}
