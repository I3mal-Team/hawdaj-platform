<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes, HasApiTokens, InteractsWithMedia;

    public bool $inPermission = true;

    protected $guarded = ['id'];

    protected $hidden = ['password', 'remember_token',];

    protected $casts = [
        'email_verified_at' => 'datetime',
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
     * @return HasOne
     */
    public function department(): HasOne
    {
        return $this->hasOne(Department::class, 'id', 'department_id')->withDefault(['name' => 'All']);
    }

    /**
     * @return HasOne
     */
    public function location(): HasOne
    {
        return $this->hasOne(UserLocation::class);
    }

    /**
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * @return BelongsToMany
     */
    public function sites(): BelongsToMany
    {
        return $this->belongsToMany(Site::class, 'user_sites', 'user_id', 'site_id');
    }

    public function gates(): BelongsToMany
    {
        return $this->belongsToMany(Gate::class, 'user_gates', 'user_id', 'gate_id');
    }

    /**
     * @return HasMany
     */
    public function visitRequest(): HasMany
    {
        return $this->hasMany(VisitRequest::class, 'requester_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function contractRequest(): HasMany
    {
        return $this->hasMany(ContractRequest::class, 'contract_manager_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function materialRequest(): HasMany
    {
        return $this->hasMany(MaterialRequest::class, 'requester_id', 'id');
    }

    /**
     * @return mixed
     */
    public function routeNotificationForMail()
    {
        return $this->email;
    }

    /**
     * @return string|array|null
     */
    public function routeNotificationForFcm()
    {
        $token = $this->fcm_token;

        return ($token !== null && $token !== '') ? $token : null;
    }

    /**
     * @return HasMany
     */
    public function carRequest(): HasMany
    {
        return $this->hasMany(CarRequest::class, 'requester_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg', 'image/webp'])
            ->useDisk('media');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('small')
            ->width(250)
            ->height(250)
            ->sharpen(10)
            ->nonQueued()
            ->performOnCollections('photo');

        $this->addMediaConversion('medium')
            ->width(500)
            ->height(500)
            ->sharpen(10)
            ->nonQueued()
            ->performOnCollections('photo');
    }
}
