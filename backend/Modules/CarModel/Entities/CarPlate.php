<?php

namespace Modules\CarModel\Entities;

use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CarPlate extends Model
{
    use HasFactory;

    protected $table = 'car_plates';

    protected $fillable = [
        'site_id', 'camID', 'image', 'status', 'notice_time', 'last_risk', 'first_row', 'detection_status', 'plate_ar',
        'plate_en','plate_image','car_image'
    ];

    public function notes(): HasOne
    {
        return $this->hasOne(CarNotes::class, 'car_id', 'id');
    }

    public function getImageAttribute($image)
    {
        return resolvePhoto($image);
    }

    public function getCarImageAttribute($image)
    {
        if(is_null($image)){
            return resolvePhoto($this->image);
        }
        return resolvePhoto($image);
    }

    public function getPlateImageAttribute($image)
    {
        return resolvePhoto($image);
    }

    public function site(): HasOne
    {
        return $this->hasOne(Site::class, 'id', 'site_id');
    }

    public function scopeSite($query)
    {
        return $query->where('site_id', $query->site_id);
    }
}
