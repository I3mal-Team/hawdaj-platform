<?php

namespace App\Http\Resources\Sliders;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
{
    private function imageUrl(?string $size = null): ?string
    {
        if ($this->resource->getFirstMedia('image') !== null) {
            return getMediaUrl($this->resource, 'image', $size === 'small' ? 'small' : ($size === 'medium' ? 'medium' : ''));
        }

        return null;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'link' => $this->link,
            'order_id' => (int) $this->order_id,
            'image' => $this->imageUrl(),
            'image_small' => $this->imageUrl('small'),
            'image_medium' => $this->imageUrl('medium'),
        ];
    }
}
