<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'description', 'quantity', 'status'];

    public function requests(): BelongsToMany
    {
        return $this->belongsToMany(MaterialRequest::class, 'materials_of_requests', 'material_id', 'material_request_id');
    }
}
