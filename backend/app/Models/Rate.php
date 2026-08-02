<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $guarded;

    protected $appends = [
        'created_at'
    ];

    public function getCreatedAtAttribute()
    {
        return isset($this->attributes['created_at']) ? date('Y-m-d h:i A', strtotime($this->attributes['created_at'])) : date('Y-m-d h:i A');
    }
}
