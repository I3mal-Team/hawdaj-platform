<?php

namespace App\Http\Resources\Favorites;

use App\Http\Resources\Places\CityResource;
use App\Http\Resources\Places\RegionResource;
use App\Models\Event;
use App\Models\Place;
use App\Models\Store;
use App\Models\Swalef;
use App\Models\ZadElgadel;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteResource extends JsonResource
{
    public function toArray($request)
    {
        $model = $this->getFavoritable($this->favoritable_id, $this->favoritable_type)->first();

        if (!$model) {
            return [
                "id" => $this->id,
                "favoritable_id" => $this->favoritable_id,
                "favoritable_type" => lcfirst($this->favoritable_type),
                "message" => "Resource not found"
            ];
        }

        $ratings = $model->relationLoaded('ratings') ? $model->ratings->avg('rate') : 0;

        return [
            "id" => $this->id,
            "favoritable_id" => $this->favoritable_id,
            "favoritable_slug" => $model->slug ?? '',
            "favoritable_type" => lcfirst($this->favoritable_type),
            "favoritable_image" => getImageUrl($model->getOriginal('image') ?? null, 'small'),
            "favoritable_title" => $model->translateOrDefault(app()->getLocale())->title ?? '',
            "favoritable_address" => $model->address ?? '',
            "favoritable_lat" => $model->lat ?? '',
            "favoritable_long" => $model->long ?? '',
            "favoritable_ratings" => $ratings,
            "favoritable_featured" => $model->featured ?? false,
            "favoritable_region" => $model->relationLoaded('region') ? RegionResource::make($model->region) : null,
            "favoritable_city" => $model->relationLoaded('city') ? CityResource::make($model->city) : null,
        ];
    }
    public function getFavoritable($id, $type)
    {
        if ($type == 'Place') {
            return Place::with(['region', 'city', 'translations', 'ratings'])->where('id', $id);
        }
        if ($type == 'Store') {
            return Store::with(['region', 'city', 'translations', 'ratings'])->where('id', $id);
        }
        if ($type == 'Swalef') {
            return Swalef::where('id', $id);
        }
        if ($type == 'Event') {
            return Event::where('id', $id);
        }
        if ($type == 'Zad_elgadel') {
            return ZadElgadel::where('id', $id);
        }

        return '';
    }
}
