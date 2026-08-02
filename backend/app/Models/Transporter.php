<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Transporter extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'company',
        'contact_person',
        'email',
        'id_type',
        'id_number',
        'phone',
        'people_count',
        'vehicle_details',
        'materials',
        'remarks'
    ];
}
