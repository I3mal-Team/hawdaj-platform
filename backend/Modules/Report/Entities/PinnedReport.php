<?php

namespace Modules\Report\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PinnedReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'start', 'end', 'time_type', 'global_date', 'time_range', 'default', 'default', 'active', 'user_id'
    ];

    public function scopePrimary($query)
    {
        return $query->where('user_id', auth()->id());
    }

    public function createdBy(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function charts(): BelongsToMany
    {
        return $this->belongsToMany(DraftChart::class, 'pinned_charts',
            'pinned_id', 'chart_id')->withPivot('column_width', 'sort');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function (PinnedReport $pinnedReport) {
            cache()->forget("report_pinned_$pinnedReport->id");
        });
    }

    public function pinnedCharts(): HasMany
    {
        return $this->hasMany(PinnedChart::class, 'pinned_id', 'id')->orderBy('sort');
    }
}
