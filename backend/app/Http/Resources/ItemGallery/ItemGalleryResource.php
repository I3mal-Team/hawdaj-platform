<?php

namespace App\Http\Resources\ItemGallery;

use App\Http\Resources\Gallaries\GallaryResource;
use App\Http\Resources\Languages\LanguageResource;
use App\Http\Resources\Places\RegionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemGalleryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "type" => $this->type,
            "parent_id" => $this->parent_id,
            "file" => getImageUrl($this->resource->getOriginal('file') ?? null),
            "active" => $this->active,
            "mime_type" => $this->mime_type,
        ];
    }
}
