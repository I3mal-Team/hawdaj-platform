<?php

namespace App\Http\Resources\PrepareTrips;

use App\Http\Resources\Places\RegionResource;
use App\Models\Place;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Prices\PriceResource;
use App\Http\Resources\Categories\CategoryResource;

class PrepareTripResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        dd($this->places, json_decode($this->places));

        $lacesData = [];

        $items = json_decode($this->items, true);
        if($items){
            foreach ($items as $key => $value) {
                $lacesData[$key] = Place::with('region', 'city')->whereIn('id', $value)->get();
            }
        }
//        dd($testPlaces);

//        $places = $this->multi_json_decode($this->places);

        return [
            "name"=> $this->name,
            "items"=> json_decode($this->items),
//            "places"=> json_decode($this->places),
            "places"=> $lacesData,
            "selected_categories"=> json_decode($this->selected_categories),
            "start_date"=> $this->start_date,
            "end_date"=> $this->end_date,
            "funny"=> $this->funny,
            "funny_place_per_day"=> $this->funny_place_per_day,
            "days"=> $this->days,
            "region1"=> $this->region1,
            "region1Object" => $this->region1Object ? RegionResource::make($this->region1Object) : null,
            "region2"=> $this->region2,
            "region2Object"=> $this->region2Object ? RegionResource::make( $this->region2Object) : null,
            "lat1"=> $this->lat1,
            "long1"=> $this->long1,
            "lat2"=> $this->lat2,
            "long2"=> $this->long2,
            "user_id"=> $this->user_id,
            "user"=> $this->user,
            "token"=> $this->token,
        ];

    }

    function multi_json_decode($string, $depth = 2) {
        $data = $string;

        for ($i = 0; $i < $depth; $i++) {
            if (is_string($data)) {
                $decoded = json_decode($data, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data = $decoded;
                } else {
                    break;
                }
            } else {
                break;
            }
        }

        return $data;
    }
}
