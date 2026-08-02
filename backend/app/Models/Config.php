<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{

    public $inPermission = true;

    protected $fillable = [
        'key', 'value', 'view', 'user_id', 'active'
    ];

    public function scopePrimary($query)
    {
        return $query->where('user_id', auth()->id());
    }
}
