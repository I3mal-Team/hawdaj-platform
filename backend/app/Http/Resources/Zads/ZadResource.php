<?php

namespace App\Http\Resources\Zads;

use App\Http\Resources\Gallaries\GallaryResource;
use App\Http\Resources\Seo\SeoResource;
use App\Models\Event;
use App\Models\ZadElgadel;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Categories\ZadCategoryResource;
use App\Http\Resources\Categories\CategoryResource;
use App\Http\Resources\Places\RegionResource;
use App\Http\Resources\Places\CityResource;

class ZadResource extends JsonResource
{
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
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "order_id" => (int) $this->order_id,
            "slug" => $this->slug,
            "type" => $this->type,
            "categories" => !$this->allCategories()->isEmpty() ? ZadCategoryResource::collection($this->allCategories()) : [],
            "food_categories" => !$this->allFoodCategories()->isEmpty() ? CategoryResource::collection($this->allFoodCategories()) : [],
            "address" => $this->address,
            "image" => $this->getImageWithFallback(),
            "image_small" => $this->getImageWithFallback('small'),
            "image_medium" => $this->getImageWithFallback('medium'),
            "menu_file" => $this->menu_file,
            // "facebook_link" => $this->facebook_link,
            "x_link" => $this->x_link,
            "whatsapp" => $this->whatsapp,
            "related_places" => [],
            "Instagram_link" => $this->Instagram_link,
            "website_link" => $this->website_link,
            "ticket_link" => $this->ticket_link,
            "lat" => $this->lat,
            "long" => $this->long,
            "distance" => $this->distance ?? null,
            "active" => $this->active,
            "address_type" => $this->address_type,
            "featured" => $this->featured,
            "visited" => $this->visited,
            "views_num" => $this->views_num,
            "rate" => round($this->rate),
            "review" => $this->review,
            "title" => $this->title,
            "description" => $this->description,
            "ratings" => $this->ratings,
            "galleries" => $this->galleries ? GallaryResource::collection($this->galleries) : [],
            "city" => $this->city ? CityResource::make($this->city) : null,
            "region" => $this->region ? RegionResource::make($this->region) : null,
            'meta' => $this->ceo?SeoResource::make($this->ceo): null,
            'is_favorite' => isFavorite($this->id, ZadElgadel::TYPE_ZAD_ELGADEL),
            'is_saved' => isSaved($this->id, ZadElgadel::TYPE_ZAD_ELGADEL),
        ];
    }
}
