<?php

namespace Modules\CarModel\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarSetting extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'car_settings';

    protected $fillable = [
        'start_time', 'end_time', 'start_date', 'end_date', 'site_id', 'notification', 'screenshot', 'active', 'car'
    ];

}
