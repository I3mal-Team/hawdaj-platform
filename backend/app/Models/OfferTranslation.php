<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['title', 'description'];
    protected $table = 'offer_translations';
}
