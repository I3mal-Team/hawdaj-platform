<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PinnedReport extends Model
{
    use HasFactory;

    public bool $inPermission = true;

    protected $fillable = [
        'title', 'start', 'end', 'default', 'default', 'active', 'user_id'
    ];

    public function scopePrimary($query)
    {
        return $query->where('user_id', auth()->id());
    }

    public function charts(): BelongsToMany
    {
        return $this->belongsToMany(DraftChart::class, 'pinned_charts', 'pinned_id', 'chart_id')
            ->withPivot('column_width', 'sort');
    }

    public function pinnedCharts(): HasMany
    {
        return $this->hasMany(PinnedChart::class, 'pinned_id', 'id')->orderBy('sort');
    }
}
