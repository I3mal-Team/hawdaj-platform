<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    private function getPhotoWithFallback(?string $size = null): ?string
    {
        $hasMedia = $this->resource->getFirstMedia('photo') !== null;
        if ($hasMedia) {
            return getMediaUrl($this->resource, 'photo', $size === 'small' ? 'small' : ($size === 'medium' ? 'medium' : ''));
        }
        $oldPhoto = $this->resource->getOriginal('photo');
        return getImageUrl($oldPhoto, $size);
    }

    public function toArray($request): array
    {
        return [
            "id" => $this->id,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "email" => $this->email,
            "phone" => $this->phone,
            "photo" => $this->getPhotoWithFallback(),
            "photo_small" => $this->getPhotoWithFallback('small'),
            "photo_medium" => $this->getPhotoWithFallback('medium'),
            "gender" => $this->gender,
            "full_name" => $this->first_name . ' ' . $this->last_name,
            "total_points" => $this->total_points ?? 0,
        ];
    }
}
