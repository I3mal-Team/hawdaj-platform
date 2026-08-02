<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PinnedChart extends Model
{
    use HasFactory;

    protected $fillable = [
        'pinned_id', 'chart_id', 'column_width', 'sort'
    ];

    protected static function boot() {
        parent::boot();
        static::deleting(function(PinnedReport $pinnedReport) {
            cache()->forget("report_pinned_$pinnedReport->id");
        });
        static::updating(function(PinnedReport $pinnedReport) {
            cache()->forget("report_pinned_$pinnedReport->id");
        });
    }

    public function charts(): HasMany
    {
        return $this->hasMany(DraftChart::class, 'id', 'chart_id');
    }

    public function pinned(): HasMany
    {
        return $this->hasMany(PinnedReport::class, 'id', 'pinned_id');
    }
}
