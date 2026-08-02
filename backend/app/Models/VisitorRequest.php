<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class VisitorRequest extends Model
{
    use HasFactory;

    public bool $inPermission = true;

    protected $fillable = [
        'visitor_id', 'visit_request_id', 'secret_code', 'checkin', 'checkin_note', 'checkout', 'checkout_note',
        'refuse_note', 'status', 'notes', 'status_action', 'health_status'
    ];

    /**
     * @return BelongsTo
     */
    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class, 'visitor_id');
    }

    /**
     * @return BelongsTo
     */
    public function visitRequest(): BelongsTo
    {
        return $this->belongsTo(VisitRequest::class, 'visit_request_id');
    }

    /**
     * @return HasMany
     */
    public function health_check(): HasMany
    {
        return $this->hasMany(VisitorHealthCheck::class);
    }

    /**
     * @return MorphMany
     */
    public function histroy(): MorphMany
    {
        return $this->morphMany(History::class, 'historiable');
    }
}
