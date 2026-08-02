<?php

namespace App\Http\Resources\Applications;

use App\Http\Resources\Categories\CategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationListResource extends JsonResource
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
            "link" => $this->link,
            "ios_link" => $this->ios_link,
            "android_link" => $this->android_link,
            "image" => $this->getImageWithFallback(),
            "image_small" => $this->getImageWithFallback('small'),
            "image_medium" => $this->getImageWithFallback('medium'),
            "title" => $this->title,
            "description" => $this->description,
            "active" => $this->active,
            "show_in_home" => $this->show_in_home,
            "categories" => $this->categories ? CategoryResource::collection($this->allCategories()) : [],
        ];
    }
}
