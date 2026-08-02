<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    public $inPermission = true;

    protected $fillable = ['key', 'value', 'view', 'user_id', 'active'];

}
