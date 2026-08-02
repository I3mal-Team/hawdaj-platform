<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Visitor extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    public bool $inPermission = true;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'first_name', 'last_name', 'id_number', 'id_type', 'mobile', 'email', 'gender', 'nationality', 'company_id', 'comment',
        'vehicle_detail', 'vehicle_material', 'vehicle_remark', 'personal_photo', 'id_copy','type'
    ];

    protected $appends = ['full_name'];

    /**
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * @return BelongsToMany
     */
    public function requests(): BelongsToMany
    {
        return $this->belongsToMany(VisitorRequest::class, 'visitor_requests',
            'visitor_id', 'visit_request_id');
    }

    /**
     * @return HasMany
     */
    public function healthChecks(): HasMany
    {
        return $this->hasMany(HealthCheck::class);
    }

    /**
     * @return mixed
     */
    public function routeNotificationForMail()
    {
        return $this->email;
    }

    /**
     * @return HasOne
     */
    public function company(): HasOne
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }
}
