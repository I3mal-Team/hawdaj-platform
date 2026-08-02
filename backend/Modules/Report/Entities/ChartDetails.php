<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Str;

class ChartDetails extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'title', 'description', 'time_unit', 'user_id'];

//    /**
//     * @param $value
//     * @return array|Application|Translator|string|null
//     */
//    public function getTitleAttribute($value)
//    {
//        $key = \Str::snake($value);
//        return Str::startsWith(_("dashboard.$key"), 'dashboard.') ? $value : _("dashboard.$key");
//    }
}
