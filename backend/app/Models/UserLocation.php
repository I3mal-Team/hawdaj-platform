<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lat',
        'lng',
    ];

    protected $casts = [
        'lat' => 'float',
        'lng' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
