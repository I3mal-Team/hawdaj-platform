<?php

namespace App\Http\Resources\Languages;

use Illuminate\Http\Resources\Json\JsonResource;

class LanguageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "order_id" => (int) ($this->order_id ?? 0),
            "name" => $this->name,
        ];
    }
}
