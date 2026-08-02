<?php

namespace App\Http\Resources\Seo;

use Illuminate\Http\Resources\Json\JsonResource;

class SeoResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'link' => $this->link,
            'keyWords' => $this->keyWordsSentense()
        ];
    }
}
