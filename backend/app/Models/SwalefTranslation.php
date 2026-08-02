<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SwalefTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'title', 'description', 'content', 'timePeriod', 'location', 'storyImportance', 'relevanceToPresent',
        'source', 'audioStoryTitle',
    ];
}

