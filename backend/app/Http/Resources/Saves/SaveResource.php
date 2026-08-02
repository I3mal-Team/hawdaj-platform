<?php

namespace App\Http\Resources\Saves;

use App\Http\Resources\Places\CityResource;
use App\Http\Resources\Places\RegionResource;
use App\Models\Event;
use App\Models\Place;
use App\Models\Store;
use App\Models\Swalef;
use App\Models\ZadElgadel;
use Illuminate\Http\Resources\Json\JsonResource;

class SaveResource extends JsonResource
{
    public function toArray($request)
    {
        $model = $this->getSavedModel($this->saved_id, $this->saved_type);

        if (!$model) {
            return [
                "id" => $this->id,
                "saved_id" => $this->saved_id,
                "saved_type" => lcfirst($this->saved_type),
                "message" => "Resource not found"
            ];
        }

        $ratings = $model->relationLoaded('ratings') ? $model->ratings->avg('rate') : 0;

        return [
            "id" => $this->id,
            "saved_id" => $this->saved_id,
            "saved_slug" => $model->slug ?? '',
            "saved_type" => lcfirst($this->saved_type),
            "saved_image" => getImageUrl($model->getOriginal('image') ?? null, 'small'),
            "saved_title" => $model->translateOrDefault(app()->getLocale())->title ?? '',
            "saved_address" => $model->address ?? '',
            "saved_lat" => $model->lat ?? '',
            "saved_long" => $model->long ?? '',
            "saved_ratings" => $ratings,
            "saved_featured" => $model->featured ?? false,
            "saved_region" => $model->relationLoaded('region') ? RegionResource::make($model->region) : null,
            "saved_city" => $model->relationLoaded('city') ? CityResource::make($model->city) : null,
        ];
    }

    public function getSavedModel($id, $type)
    {
        if ($type == 'Place') {
            return Place::with(['region', 'city', 'translations', 'ratings'])
                ->where('id', $id)
                ->first();
        }
        if ($type == 'Store') {
            return Store::with(['region', 'city', 'translations', 'ratings'])
                ->where('id', $id)
                ->first();
        }
        if ($type == 'Swalef') {
            return Swalef::where('id', $id)->first();
        }
        if ($type == 'Event') {
            return Event::where('id', $id)->first();
        }
        if ($type == 'Zad_elgadel') {
            return ZadElgadel::where('id', $id)->first();
        }

        return null;
    }
}
