<?php

namespace App\Http\Resources\Offers;

use App\Http\Resources\Menu\MenuResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
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
          "image" => getImageUrl($this->resource->getOriginal('image') ?? null),
          "from" => $this->from,
          "to" => $this->to,
          "title"   => $this->title,
          "description"   => $this->description,
          "discount" => $this->discount,
          "price" => $this->menu->price,
          "price_after_discount" => $this->menu->price * ($this->discount / 100),
        ];
    }
}
