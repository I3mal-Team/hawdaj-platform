<?php

namespace Modules\CarModel\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CarNotes extends Model
{
    use HasFactory;

    protected $table = 'car_notes';

    protected $fillable = ['car_id', 'notes', 'file','user_id'];

    public function Car(): HasOne
    {
        return $this->hasOne(CarPlate::class, 'id', 'car_id');
    }

    public function owner()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
