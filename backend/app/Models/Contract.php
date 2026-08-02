<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory;

    use SoftDeletes;

    public $inPermission = true;

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'name',
        'company_id',
        'supervisor_id',
        'department_id',
        'contract_manager_id',
        'contract_type_id',
        'from_date',
        'to_date',
        'description'
    ];

    public function supervisor()
    {
        return $this->hasOne(User::class, 'id', 'supervisor_id');
    }

    public function contract_manager()
    {
        return $this->hasOne(User::class, 'id', 'contract_manager_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class,'department_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }

    public function contract_type()
    {
        return $this->belongsTo(ContractType::class,'contract_type_id');
    }
}
