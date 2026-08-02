<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserSocial extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'facebook',
        'x',
        'twitter',
        'instagram',
        'linkedin',
        'youtube',
        'tiktok',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }
}
