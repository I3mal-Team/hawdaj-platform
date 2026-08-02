<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialsOfRequests extends Model
{
    use HasFactory;

    protected $fillable = ['material_id', 'material_request_id', 'secret_code'];

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function materialRequest(): BelongsTo
    {
        return $this->belongsTo(MaterialRequest::class, 'material_request_id');
    }

}
