<?php

namespace App\Http\Resources\Guides;

use App\Http\Resources\Categories\CategoryResource;
use App\Http\Resources\Places\RegionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GuideListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "order_id" => (int) ($this->order_id ?? 0),
            "name" => $this->name,
            "nickName" => $this->nickName,
            "description" => $this->description,
            "image" => getImageUrl($this->resource->getOriginal('image') ?? null, 'small'),
            "show_in_home" => $this->show_in_home,
            "regions" => $this->regions ? RegionResource::collection($this->allRegions()) : [],
//            "languages" => $this->languages ? RegionResource::collection($this->allRegions()) : [],
            'social' => [
                // 'facebook' => $this->facebook,
                'x' => $this->x,
            ],
        ];
    }
}
