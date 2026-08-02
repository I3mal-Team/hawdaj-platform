<?php

namespace App\Http\Resources\Places;

use App\Http\Resources\Seo\SeoResource;
use App\Models\Place;
use App\Models\Store;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Prices\PriceResource;
use App\Http\Resources\Categories\CategoryResource;
use App\Http\Resources\Gallaries\GallaryResource;

class PlaceListResource extends JsonResource
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
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            "id" => $this->id,
            "order_id" => (int) $this->order_id,
            "slug" => $this->slug,
            "type" => $this->type,
            "categories" => $this->allCategories() ? CategoryResource::collection($this->allCategories()) : [],
            "address"  => $this->address,
            "image" => $this->getImageWithFallback(),
            "image_small" => $this->getImageWithFallback('small'),
            "image_medium" => $this->getImageWithFallback('medium'),
            "cover_image" => $this->getImageWithFallback('medium'),
            "ticket_link"   => $this->ticket_link,
            "website_link"   => $this->website_link,
            "instagram_link"   => $this->instagram_link,
            "whatsapp"  => $this->whatsapp,
            // "facebook_link"   => $this->facebook_link,
            "x_link"   => $this->x_link,
            "temperature"   => $this->temperature,
            "seasons"   => $this->seasons_tra,
            "related_places"   => [],
            "galleries" => $this->galleries ? GallaryResource::collection($this->galleries) : [],
            "price"   => $this->price ? PriceResource::make($this->price) : null,
            "city"   => $this->city ? CityResource::make($this->city) : null,
            "region"   => $this->region ? RegionResource::make($this->region) : null,
            "address_type"   => $this->address_type,
            "active"   => $this->active,
            "views_num"   => $this->views_num,
            "near_stores"   => $this->near_stores,
            "featured"   => $this->featured,
            "lat"   => $this->lat,
            "long"   => $this->long,
            "visited"   => $this->visited,
            "distance"   => $this->distance,
            "key_words"   => $this->key_words,
            "prefered"   => $this->prefered,
            "place_icon"   => $this->place_icon,
            "rate"   => round($this->rate),
            "review"   => $this->review,
            "title"   => $this->title,
            "description"   => $this->description,
            "ratings"  => $this->ratings,
            'meta' => $this->ceo?SeoResource::make($this->ceo): null,
            'is_favorite' => isFavorite($this->id, Place::TYPE_PLACE),
            'is_saved' => isSaved($this->id, Place::TYPE_PLACE),
            "ownership_proof_file" => getImageUrl($this->resource->getOriginal('ownership_proof_file') ?? null, 'small'),
            "status" => $this->status,
            "rejected_reason" => $this->rejected_reason,
        ];
    }
}
