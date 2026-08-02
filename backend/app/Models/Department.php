<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes ;

    public $inPermission = true;

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'name', 'notes'
    ];


    public function users()
    {
        return $this->hasMany(User::class);
    }
}
