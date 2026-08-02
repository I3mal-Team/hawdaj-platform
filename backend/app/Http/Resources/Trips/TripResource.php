<?php

namespace App\Http\Resources\Trips;

use App\Http\Resources\Places\RegionResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Prices\PriceResource;
use App\Http\Resources\Categories\CategoryResource;

class TripResource extends JsonResource
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
            "name"=> $this->name,
            "item_per_day"=> $this->item_per_day,
            "days"=> $this->days,
            "items"=> is_string($this->items) ? $this->items : json_encode($this->items),
            "date"=> $this->date,
            "user_id"=> $this->user_id,
            "created_at"=> $this->created_at->diffForHumans(),
            "email"=> $this->email,
            "token"=> $this->token,
            "start_date"=> $this->start_date ? $this->start_date->format('Y-m-d') : null,
            "end_date"=> $this->end_date ? $this->end_date->format('Y-m-d') : null,
            "region1Object" => $this->region1Object ? RegionResource::make( $this->region1Object) : null,
            "region2Object"=> $this->region2Object ? RegionResource::make( $this->region2Object) : null,
        ];

    }
}
