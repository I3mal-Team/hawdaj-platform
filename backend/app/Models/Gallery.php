<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $guarded;

    //    public function place() {
    //        return $this->belongsTo(Place::class);
    //    }

    public function getFileAttribute()
    {
        if ($this->attributes['file']) {
            if (file_exists('uploads/' . $this->attributes['file'])) {
                return 'uploads/' . $this->attributes['file'];
            }
        }
        return 'front_assets/imgs/zad1.jpg';
    }
}
