<?php

namespace App\Models;

use     Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarOfRequest extends Model
{
    use HasFactory;

    protected $fillable = ['car_id', 'car_request_id','secret_code'];

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    public function carRequest()
    {
        return $this->belongsTo(CarRequest::class, 'car_request_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

}
