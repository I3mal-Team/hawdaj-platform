<?php

namespace App\Http\Resources\Menu;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            "id" => $this->id,
            "order_id" => (int) ($this->order_id ?? 0),
            "title" => $this->title,
            'price' => $this->price,
            "description" => $this->description,
            "image" => getImageUrl($this->resource->getOriginal('image') ?? null),
        ];
    }
}
