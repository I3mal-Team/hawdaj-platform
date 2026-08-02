<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = "notifications";

    public bool $inPermission = true;

    protected $fillable = array('id','type','notifiable_type','notifiable_type','data','read_at','notifiable_id');

    public function scopePrimary($query)
    {
        return $query->where('notifiable_id', auth()->id());
    }
}
