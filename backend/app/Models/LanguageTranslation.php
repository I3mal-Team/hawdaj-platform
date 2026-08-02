<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'description'];

    protected $table = 'languages_translation';
}
