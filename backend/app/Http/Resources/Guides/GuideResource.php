<?php

namespace App\Http\Resources\Guides;

use App\Http\Resources\Gallaries\GallaryResource;
use App\Http\Resources\Languages\LanguageResource;
use App\Http\Resources\Places\RegionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GuideResource extends JsonResource
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
            "type" => $this->type,
            "name" => $this->name,
            "nickName" => $this->nickName,
            "description" => $this->description,
            "image" => $this->getImageWithFallback(),
            "image_small" => $this->getImageWithFallback('small'),
            "image_medium" => $this->getImageWithFallback('medium'),
            "show_in_home" => $this->show_in_home,
            "experience" => $this->experience,
            "gender" => $this->user ? $this->user->gender : 0,
            "regions" => $this->regions ? RegionResource::collection($this->allRegions()) : [],
            "languages" => $this->languages ? LanguageResource::collection(collect($this->allLanguages())) : [],
            'social' => [
                // 'facebook' => $this->facebook,
                'x' => $this->x,
                'twitter' => $this->twitter,
                'instagram' => $this->instagram,
                'linkedin' => $this->linkedin,
                'personal_account' => $this->personal_account,
            ],
            "rate"   => round($this->rate),
            "ratings"  => $this->ratings,
            "galleries" => $this->galleries ? GallaryResource::collection($this->galleries) : [],
        ];
    }
}
