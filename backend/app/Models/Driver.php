<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Driver extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'contact_person_name',
        'email',
        'id_number',
        'phone',
        'vehicle_details',
        'licence',
        'remarks'
    ];

    public function carRequest()
    {
        return $this->hasMany(CarRequest::class);
    }
}
