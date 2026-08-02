<?php

namespace App\Http\Resources\Categories;

use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "order_id" => (int) ($this->order_id ?? 0),
            "icon"  => getImageUrl($this->resource->getOriginal('icon') ?? null),
            "name" => $this->name
        ];
    }
}
