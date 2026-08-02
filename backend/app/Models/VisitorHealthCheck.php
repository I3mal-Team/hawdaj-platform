<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitorHealthCheck extends Model
{
    use HasFactory;

    public bool $inPermission = true;

    protected $fillable = ['visitor_id', 'health_check_id', 'answer', 'visitor_request_id'];

    /**
     * @return BelongsTo
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(HealthCheck::class, 'health_check_id', 'id');
    }
}
