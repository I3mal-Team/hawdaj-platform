<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Report\Entities\DraftChart;

class DraftReport extends Model
{
    use HasFactory;

    public bool $inPermission = true;

    protected $fillable = [
        'title', 'start', 'end', 'model_type', 'report_type', 'site_id', 'columns',
        'active', 'user_id', 'time_type', 'time_range', 'unit', 'report_list'
    ];

    protected $casts = [
        'columns' => 'array',
        'site_id' => 'array'
    ];

    public function scopePrimary($query)
    {
        return $query->where('user_id', auth()->id());
    }

    public function charts(): HasMany
    {
        return $this->hasMany(DraftChart::class, 'draft_id', 'id');
    }
}
