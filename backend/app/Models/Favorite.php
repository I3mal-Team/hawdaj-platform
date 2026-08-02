<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'favoritable_id',
        'favoritable_type',
        'user_id',
    ];

    public function favoritable()
    {
        return $this->morphTo();
    }
}
