<?php

namespace App\Http\Resources\Gallaries;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Places\CityResource;
use App\Http\Resources\Places\RegionResource;

class GallaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $mime_type = $this->mime_type;
        if (!in_array($mime_type, ['image', 'video', 'pdf', 'word', 'excel'])) {
            $mime_type = 'image';
        }
        return [
            "id" => $this->id,
            "file" => getImageUrl($this->resource->getOriginal('file') ?? null),
            "type" => $this->type,
            "mime_type" => $mime_type,
        ];
    }
}
