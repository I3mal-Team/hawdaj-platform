<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractorRequest extends Model
{
    use HasFactory;

    use SoftDeletes;

    public $inPermission = true;

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'contractor_id',
        'contract_request_id',
        'secret_code',
        'status',
        'notes'
    ];

    /**
     * @return BelongsTo
     */
    public function contractor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class, 'contractor_id');
    }

    /**
     * @return BelongsTo
     */
    public function contractRequest(): BelongsTo
    {
        return $this->belongsTo(ContractRequest::class, 'contract_request_id');
    }

    /**
     * @return MorphMany
     */
    public function histroy(): MorphMany
    {
        return $this->morphMany(History::class, 'historiable');
    }

    public function checkins(): HasMany
    {
        return $this->hasMany(ContractorRequestCheckin::class,'contractor_request_id');
    }

    public function last_checkin()
    {
        return $this->hasOne(ContractorRequestCheckin::class, 'contractor_request_id')->latest();
    }
}
