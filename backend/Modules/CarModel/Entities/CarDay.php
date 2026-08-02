<?php

namespace Modules\CarModel\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\CarModel\Database\factories\CarDayFactory;

class CarDay extends Model
{
    use HasFactory;

    protected $table = 'car_days';

    protected $fillable = ['risk_duration', 'no_risk_duration', 'invitation', 'no_invitation','day', 'site_id'];

    protected static function newFactory(): CarDayFactory
    {
        return CarDayFactory::new();
    }

}
