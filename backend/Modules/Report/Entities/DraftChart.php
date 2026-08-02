<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DraftChart extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'title', 'description', 'time_unit', 'active', 'draft_id'];

    public function draft(): HasOne
    {
        return $this->hasOne(DraftReport::class, 'id', 'draft_id');
    }

    public function pinned(): BelongsToMany
    {
        return $this->belongsToMany(__CLASS__, 'pinned_charts',
            'chart_id', 'pinned_id')->withPivot('column_width', 'sort');
    }

//    public function getTitleAttribute($value): string
//    {
//        $key = \Str::snake($value);
//        return \Str::startsWith(_("dashboard.$key"), 'dashboard.') ? _("dashboard.$key") : _("dashboard.$key");
//    }
}
