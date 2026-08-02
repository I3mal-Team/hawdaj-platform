<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'type', 'site_id', 'status', 'status_remarks', 'host_id', 'requester_id', 'department_id', 'transporter_id',
        'company', 'contact_person', 'phone', 'email', 'department', 'address', 'delivery_date', 'delivery_from_time',
        'delivery_to_time', 'return_date', 'return_from_time', 'return_to_time', 'dispatch_date', 'dispatch_from_time',
        'dispatch_to_time', 'remarks', 'checkin', 'checkin_note', 'checkout', 'checkout_note', 'refuse_note',
        'status_action', 'notes', 'sender_site_id', 'sender_host_id', 'sender_department_id'
    ];

    public function host(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'host_id');
    }

    public function requester(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'requester_id');
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function sender_site(): BelongsTo
    {
        return $this->belongsTo(Site::class,'sender_site_id');
    }

    public function sender_department(): BelongsTo
    {
        return $this->belongsTo(Department::class,'sender_department_id');
    }

    public function sender_host(): BelongsTo
    {
        return $this->belongsTo(User::class,'sender_host_id');
    }

    public function scopeSite($query)
    {
        if (!isRoot()) {
            return $query->where('site_id', session('site_id'));
        }
    }

    public function department(): HasOne
    {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }

    public function transporter(): HasOne
    {
        return $this->hasOne(Transporter::class, 'id', 'transporter_id');
    }

    public function requests(): BelongsToMany
    {
        return $this->belongsToMany(Material::class, 'materials_of_requests', 'material_request_id', 'material_id');
    }

    public function histories(): MorphMany
    {
        return $this->morphMany(History::class, 'historiable');
    }
}
