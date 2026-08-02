<?php

namespace App\Http\Resources\Prices;

use Illuminate\Http\Resources\Json\JsonResource;

class PriceResource extends JsonResource
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
          "name" => $this->name
        ];
    }
}
