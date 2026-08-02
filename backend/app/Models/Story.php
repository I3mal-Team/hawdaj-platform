<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Story extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'file',
        'text',
        'status',
        'user_id',
        'total_views',
        'total_likes',
        'total_comments',
        'total_shares',
        'order_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }
}
