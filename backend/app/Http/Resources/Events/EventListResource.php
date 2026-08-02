<?php

namespace App\Http\Resources\Events;

use App\Http\Resources\Seo\SeoResource;
use App\Models\Event;
use App\Models\Store;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Places\CityResource;
use App\Http\Resources\Places\RegionResource;
use App\Http\Resources\Gallaries\GallaryResource;

class EventListResource extends JsonResource
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
            "date_from" => $this->date_from,
            "date_to" => $this->date_to,
            "closed" => !now()->between($this->date_from , $this->date_to),
            "ticket_link" => $this->ticket_link,
            "image" => $this->getImageWithFallback(),
            "image_small" => $this->getImageWithFallback('small'),
            "image_medium" => $this->getImageWithFallback('medium'),
            // "facebook" => $this->facebook,
            "x" => $this->x,
            "whatsapp" => $this->whatsapp,
            "instagram" => $this->instagram,
            "website" => $this->website,
            "display_type" => $this->display_type,
            "address" => $this->address,
            "video_url" => $this->video_url,
            "link" => $this->link,
            "lat" => $this->lat,
            "long" => $this->long,
            "distance" => $this->distance ?? null,
            "views_num" => $this->views_num,
            "featured" => $this->featured,
            "visited" => $this->visited,
            "active" => $this->active,
            "related" => $this->related,
            "address_type" => $this->address_type,
            "title" => $this->title,
            "description" => $this->description,
            "ratings" => $this->ratings,
            "rate"   => round($this->rate),
            "galleries" => GallaryResource::collection($this->galleries),
            "city"   => CityResource::make($this->city),
            "region"   => RegionResource::make($this->region),
            'meta' => $this->ceo?SeoResource::make($this->ceo): null,
            'is_favorite' => isFavorite($this->id, Event::TYPE_EVENT),
            'is_saved' => isSaved($this->id, Event::TYPE_EVENT),
            "ownership_proof_file" => getImageUrl($this->resource->getOriginal('ownership_proof_file') ?? null, 'small'),
            "status" => $this->status,
            "rejected_reason" => $this->rejected_reason,
        ];
    }
}
