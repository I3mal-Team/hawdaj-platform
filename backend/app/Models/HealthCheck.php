<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HealthCheck extends Model
{
    use HasFactory , SoftDeletes;

    public $inPermission = true;

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'question'
    ];
}
