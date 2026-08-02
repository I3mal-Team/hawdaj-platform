<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractorRequestCheckin extends Model
{
    use HasFactory;

    use SoftDeletes;

    public $inPermission = true;

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'contractor_request_id',
        'status_action',
        'checkin',
        'checkin_note',
        'checkout',
        'checkout_note',
        'refuse_note'
    ];
}
