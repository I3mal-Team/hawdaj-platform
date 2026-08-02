<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ceo extends Model
{
    use HasFactory;

    protected $casts = [
        'key_words' => 'array'
    ];

    protected $guarded;

    public function keyWordsSentense()
    {
        return is_array($this->key_words) ? implode(',' , $this->key_words) : $this->key_words;
    }

}
