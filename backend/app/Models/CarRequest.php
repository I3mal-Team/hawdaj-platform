<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'site_id', 'status', 'host_id', 'requester_id', 'department_id', 'driver_id', 'car_id', 'delivery_date',
        'delivery_from_time', 'delivery_to_time', 'remarks', 'status_action', 'checkin', 'checkin_note', 'checkout',
        'checkout_note', 'refuse_note', 'notes'
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function requester(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'requester_id');
    }

    public function host(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'host_id');
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function department(): HasOne
    {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }
}
