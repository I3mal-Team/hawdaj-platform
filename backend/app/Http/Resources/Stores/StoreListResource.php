<?php

namespace App\Http\Resources\Stores;

use App\Http\Resources\Places\CityResource;
use App\Http\Resources\Places\RegionResource;
use App\Http\Resources\Seo\SeoResource;
use App\Models\Favorite;
use App\Models\Store;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Prices\PriceResource;
use App\Http\Resources\Categories\CategoryResource;
use App\Http\Resources\Gallaries\GallaryResource;

class StoreListResource extends JsonResource
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

    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "order_id" => (int) $this->order_id,
            "slug" => $this->slug,
            "type" => $this->type,
            "categories" => $this->categories ? CategoryResource::collection($this->allCategories()) : [],
            "address"  => $this->address,
            "image" => $this->getImageWithFallback(),
            "image_small" => $this->getImageWithFallback('small'),
            "image_medium" => $this->getImageWithFallback('medium'),
            // "facebook_link"   => $this->facebook_link,
            "x_link"   => $this->x_link,
            "whatsapp"  => $this->whatsapp,
            "Instagram_link"   => $this->Instagram_link,
            "website_link"   => $this->website_link,
            "ticket_link"   => $this->ticket_link,
            "active"   => $this->active,
            "address_type"   => $this->address_type,
            "views_num"   => $this->views_num,
            "featured"   => $this->featured,
            "visited"   => $this->visited,
            "distance"   => $this->distance,
            "related_places"   => [],
            "near_places"   => [],
            "lat"   => $this->lat,
            "long"   => $this->long,
            "key_words"   => $this->key_words,
            "is_online"   => (bool) ($this->is_online ?? (! $this->lat && ! $this->long)),
            "rate"   => round($this->rate),
            "review"   => $this->review,
            "title"   => $this->title,
            "description"   => $this->description,
            "ratings"  => $this->ratings,
            "galleries" => $this->galleries ? GallaryResource::collection($this->galleries) : [],
            "city"   => $this->city ? CityResource::make($this->city) : null,
            "region"   => $this->region ? RegionResource::make($this->region) : null,
            'meta' => $this->ceo?SeoResource::make($this->ceo): null,
            'is_favorite' => isFavorite($this->id, Store::TYPE_STORE),
            'is_saved' => isSaved($this->id, Store::TYPE_STORE),
            "ownership_proof_file" => getImageUrl($this->resource->getOriginal('ownership_proof_file') ?? null, 'small'),
            "status" => $this->status,
            "rejected_reason" => $this->rejected_reason,
        ];
    }
}
