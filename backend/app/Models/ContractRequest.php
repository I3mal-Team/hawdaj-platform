<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractRequest extends Model
{
    use HasFactory;

    use SoftDeletes;

    public $inPermission = true;

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'site_id',
        'company_id',
        'contract_id',
        'contract_manager_id',
        'from_date',
        'to_date',
        'notes',
    ];

    /**
     * @return BelongsTo
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return BelongsTo
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * @return BelongsTo
     */
    public function contract_manager(): BelongsTo
    {
        return $this->belongsTo(User::class,'contract_manager_id');
    }

    /**
     * @return HasMany
     */
    public function contractorRequests(): HasMany
    {
        return $this->hasMany(ContractorRequest::class,'contract_request_id');
    }

    public function contractors(): BelongsToMany
    {
        return $this->belongsToMany(Visitor::class, 'contractor_requests',
            'contract_request_id', 'contractor_id');
    }

}
