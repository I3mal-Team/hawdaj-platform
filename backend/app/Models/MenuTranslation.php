<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['title', 'description'];
    protected $table = 'menu_translations';
}
