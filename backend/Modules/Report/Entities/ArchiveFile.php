<?php

namespace Modules\Report\Entities;

use App\Models\Site;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ArchiveFile extends Model
{
    use HasFactory;

    protected $table = 'archive_files';

    protected $fillable = ['start', 'end', 'site_id', 'type', 'url', 'size', 'model_type', 'name', 'status', 'user_id'];

    protected $appends = ['path_url'];

    public $inPermission = true;

    public function site(): HasOne
    {
        return $this->hasOne(Site::class, 'id', 'site_id');
    }

    public function getPathUrlAttribute()
    {

        if ($this->url) {
            return \Storage::disk('public')->url($this->url);
        }

        return null;

    }
}
