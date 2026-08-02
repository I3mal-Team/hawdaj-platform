<?php

namespace App\Http\Resources\EnhancedTrip;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Places\PlaceResource;

class EnhancedTripDayResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'day_number' => $this->day_number,
            'date' => $this->date->format('Y-m-d'),
            'date_formatted' => $this->date->format('l, F j, Y'),
            'morning' => [
                'description' => $this->morning_description,
                'places_count' => $this->morning_places_count,
                'places' => PlaceResource::collection($this->whenLoaded('morningPlaces')),
                'places_ids' => $this->morning_places ?? []
            ],
            'evening' => [
                'description' => $this->evening_description,
                'places_count' => $this->evening_places_count,
                'places' => PlaceResource::collection($this->whenLoaded('eveningPlaces')),
                'places_ids' => $this->evening_places ?? []
            ],
            'total_places_count' => $this->total_places_count,
            'status' => $this->status,
        ];
    }
}
