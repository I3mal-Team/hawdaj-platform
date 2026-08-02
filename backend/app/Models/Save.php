<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Save extends Model
{
    use HasFactory;

    protected $fillable = [
        'saved_id',
        'saved_type',
        'user_id',
    ];
}
