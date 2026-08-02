<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class VisitType extends Model
{
    use HasFactory, SoftDeletes;

    public bool $inPermission = true;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'notes'];

    /**
     * @return HasMany
     */
    public function visitRequests(): HasMany
    {
        return $this->hasMany(VisitRequest::class);
    }
}
