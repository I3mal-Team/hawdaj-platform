<?php

namespace App\Http\Resources\Stories;

use Illuminate\Http\Resources\Json\JsonResource;

class StoryCollection extends JsonResource
{
    public function toArray($request)
    {
        return [

            'current_page' => $this->currentPage(),
            'items' => StoryListResource::collection($this->items()),
            'first_page_url' => $this->url(1),
            'from' => $this->firstItem(),
            'last_page' => $this->lastPage(),
            'last_page_url' => $this->url($this->lastPage()),
            'next_page_url' => $this->nextPageUrl(),
            'path' => $this->url(1),
            'per_page' => $this->perPage(),
            'prev_page_url' => $this->previousPageUrl(),
            'to' => $this->lastItem(),
            'total' => $this->total()
        ];
    }
}
