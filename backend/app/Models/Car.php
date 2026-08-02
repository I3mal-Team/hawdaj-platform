<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'plate_ar', 'plate_en', 'description', 'status','licence','type'
    ];

    public function carRequest()
    {
        return $this->hasMany(CarRequest::class);
    }

}
