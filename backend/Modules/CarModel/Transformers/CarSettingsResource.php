<?php

namespace Modules\CarModel\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarSettingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request
     * @return array
     */
    public function toArray($request): array
    {
        return array(
            'id' => $this->id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'notification' => (boolean)$this->notification,
            'screenshot' => (boolean)$this->screenshot,
            'active' => (boolean)$this->active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        );
    }
}
