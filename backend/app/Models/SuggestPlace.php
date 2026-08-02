<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuggestPlace extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'name', 'email', 'type', 'address', 'link', 'lat', 'long', 'active'];
}
