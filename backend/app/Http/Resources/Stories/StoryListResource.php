<?php

namespace App\Http\Resources\Stories;

use Illuminate\Http\Resources\Json\JsonResource;

class StoryListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "order_id" => (int) ($this->order_id ?? 0),
            "type" => $this->type,
            "text" => $this->text,
           // "file" => $this->file ? env('IMAGE_PATH', 'https://dashboard.hawdaj.net/uploads/small-') . $this->file : null,
            "file" => getImageUrl($this->resource->getOriginal('file') ?? null, 'small'),
            "status" => $this->status,
            "total_views" => $this->total_views ?? 0,
            "total_likes" => $this->total_likes ?? 0,
            "total_comments" => $this->total_comments ?? 0,
            "total_shares" => $this->total_shares ?? 0,
        ];
    }
}
