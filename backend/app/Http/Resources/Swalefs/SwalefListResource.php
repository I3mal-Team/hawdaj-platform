<?php

namespace App\Http\Resources\Swalefs;

use App\Http\Resources\Categories\CategoryResource;
use App\Http\Resources\Gallaries\GallaryResource;
use App\Models\Event;
use App\Models\Swalef;
use Illuminate\Http\Resources\Json\JsonResource;

class SwalefListResource extends JsonResource
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
        return array_merge(parent::toArray($request), [
            'order_id' => (int) ($this->order_id ?? 0),
            'is_favorite' => isFavorite($this->id, Swalef::TYPE_SWALEF),
            'is_saved' => isSaved($this->id, Swalef::TYPE_SWALEF),
            "image" => $this->getImageWithFallback(),
            "image_small" => $this->getImageWithFallback('small'),
            "image_medium" => $this->getImageWithFallback('medium'),
            "categories" => $this->allCategories() ? CategoryResource::collection($this->allCategories()) : [],
            "galleries" => $this->galleries ? GallaryResource::collection($this->galleries) : [],
            "mainCharacters" => json_decode($this->mainCharacters, true),
        ]);
    }
}
