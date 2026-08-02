<?php

namespace Modules\Report\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DraftReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'start', 'end', 'model_type', 'report_type', 'site_id', 'columns','groupBy','user_id',
        'user_id', 'time_type', 'time_range', 'unit', 'report_list'
    ];

    protected $casts = [
        'columns' => 'array',
        'site_id' => 'array'
    ];

    public function scopePrimary($query)
    {
        return $query->where('user_id', auth()->id());
    }

    public function createdBy(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function charts(): HasMany
    {
        return $this->hasMany(DraftChart::class, 'draft_id', 'id');
    }

}
