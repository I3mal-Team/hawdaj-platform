<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArchiveFile extends Model
{
    use HasFactory;

    protected $table = 'archive_files';

    protected $fillable = ['start', 'end', 'site_id', 'type', 'url', 'size', 'model_type', 'name', 'status', 'user_id'];

    protected $appends = ['path_url'];

    public $inPermission = true;

    public function getPathUrlAttribute()
    {
        if ($this->url) {
            return \Storage::disk('public')->url($this->url);
        } else {
            return null;
        }

    }
}
