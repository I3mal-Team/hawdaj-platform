<?php

namespace App\Http\Resources\Swalefs;

use App\Http\Resources\Categories\CategoryResource;
use App\Http\Resources\Gallaries\GallaryResource;
use App\Models\Event;
use App\Models\Swalef;
use Illuminate\Http\Resources\Json\JsonResource;

class SwalefResource extends JsonResource
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
        $parentData = parent::toArray($request);
        if (isset($parentData['image'])) {
            $parentData['image'] = $this->getImageWithFallback();
            $parentData['image_small'] = $this->getImageWithFallback('small');
            $parentData['image_medium'] = $this->getImageWithFallback('medium');
        }
        return array_merge($parentData, [
            'order_id' => (int) ($this->order_id ?? 0),
            'is_favorite' => isFavorite($this->id, Swalef::TYPE_SWALEF),
            'is_saved' => isSaved($this->id, Swalef::TYPE_SWALEF),
            "categories" => $this->allCategories() ? CategoryResource::collection($this->allCategories()) : [],
            "galleries" => $this->galleries ? GallaryResource::collection($this->galleries) : [],
            "mainCharacters" => json_decode($this->mainCharacters, true),
        ]);
    }
}
