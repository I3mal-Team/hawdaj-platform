<?php

namespace App\Http\Resources\Stores;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreCollection extends JsonResource
{

    public function toArray($request)
    {
        return [
            'current_page' => $this->currentPage(),
            'items' => StoreListResource::collection($this->items()),
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
