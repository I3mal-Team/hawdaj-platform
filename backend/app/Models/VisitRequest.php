<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class VisitRequest extends Model
{
    use HasFactory, SoftDeletes;

    public bool $inPermission = true;

    protected $fillable = [
        'site_id', 'requester_id', 'recurring_visit', 'department_id', 'visitor_id', 'reason_id', 'visit_type_id',
        'description', 'requirments', 'comment', 'from_date', 'from_fromtime', 'from_totime', 'to_date', 'to_fromtime',
        'to_totime', 'host_id'
    ];

    protected $dates = ['deleted_at'];

    public function scopeSite($query)
    {
        if (!isRoot()) {
            return $query->where('site_id', session('site_id'));
        }
    }

    /**
     * @return HasOne
     */
    public function requester(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'requester_id');
    }

    /**
     * @return HasOne
     */
    public function host(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'host_id');
    }

    /**
     * @return BelongsTo
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * @return HasOne
     */
    public function department(): HasOne
    {
        return $this->hasOne(Department::class,'id','department_id');
    }

    /**
     * @return HasOne
     */
    public function reason(): HasOne
    {
        return $this->hasOne(Reason::class,'id','reason_id');
    }

    /**
     * @return HasOne
     */
    public function visitType(): HasOne
    {
        return $this->hasOne(VisitType::class,'id','visit_type_id');
    }

    /**
     * @return BelongsToMany
     */
    public function visitors(): BelongsToMany
    {
        return $this->belongsToMany(Visitor::class, 'visitor_requests',
            'visit_request_id', 'visitor_id');
    }

    /**
     * @return HasMany
     */
    public function visitorRequest(): HasMany
    {
        return $this->hasMany(VisitorRequest::class,'visit_request_id');
    }

}
