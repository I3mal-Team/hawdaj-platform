<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

class AuditLog extends Model
{
    public $table = 'audit_logs';

    public $inPermission = true;

    protected $fillable = [
        "description",
        'subject_id',
        'subject_type',
        'user_id',
        'properties',
        'host',
    ];

    protected $casts = [
        'properties' => 'collection',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function scopePrimary($query)
    {
        return $query->where('user_id', auth()->id());
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
