<?php

namespace App\Http\Resources\Search;

use App\Models\Event;
use App\Models\ZadElgadel;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Places\CityResource;
use App\Http\Resources\Places\RegionResource;
use App\Http\Resources\Gallaries\GallaryResource;

class SearchResource extends JsonResource
{
    /**
     * Get image with fallback to old image system
     *
     * @param string|null $size
     * @return string|null
     */
    private function getImageWithFallback(?string $size = null): ?string
    {
        $hasMedia = $this->resource->getFirstMedia('image') !== null;
        if ($hasMedia) {
            return getMediaUrl($this->resource, 'image', $size === 'small' ? 'small' : ($size === 'medium' ? 'medium' : ''));
        }
        $oldImage = $this->resource->getOriginal('image');
        return getImageUrl($oldImage, $size);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $type = ucfirst($this->type);
        if ($type == 'Zad') {
            $type = ZadElgadel::TYPE_ZAD_ELGADEL;
        }

        return [
            "id" => $this->id,
            "slug" => $this->slug,
            "type" => $this->type,
            "image" => $this->getImageWithFallback(),
            "image_small" => $this->getImageWithFallback('small'),
            "image_medium" => $this->getImageWithFallback('medium'),
            "address" => $this->address,
            "lat" => $this->lat,
            "long" => $this->long,
            "featured" => $this->featured,
            "title" => $this->title,
            "ratings_count" => count($this->ratings),
            "rate"   => round($this->rate),
            "city"   => CityResource::make($this->city),
            "region"   => RegionResource::make($this->region),
            'is_favorite' => isFavorite($this->id, $type)
        ];
    }
}
