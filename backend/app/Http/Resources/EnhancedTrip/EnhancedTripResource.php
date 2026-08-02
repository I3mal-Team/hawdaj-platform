<?php

namespace App\Http\Resources\EnhancedTrip;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnhancedTripResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'token' => $this->token,
            'start_date' => $this->start_date->format('Y-m-d'),
            'end_date' => $this->end_date->format('Y-m-d'),
            'total_days' => $this->total_days,
            'places_per_day' => $this->places_per_day,
            'total_places' => $this->total_places,
            'status' => $this->status,
            'start_region' => [
                'id' => $this->startRegion->id,
                'name' => $this->startRegion->name,
                'name_en' => $this->startRegion->translate('en')->name ?? null,
            ],
            'end_region' => [
                'id' => $this->endRegion->id,
                'name' => $this->endRegion->name,
                'name_en' => $this->endRegion->translate('en')->name ?? null,
            ],
            'categories' => $this->categories,
            'price_range' => $this->price_range,
            'days' => EnhancedTripDayResource::collection($this->whenLoaded('days')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
