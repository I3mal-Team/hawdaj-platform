<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Resources\Json\JsonResource;

class UserSocialResource extends JsonResource
{
    public function toArray($request): array
    {

        return parent::toArray($request);

//        return [
//            'id' => $this->id,
//            // 'facebook' => $this->facebook,
//            'x' => $this->x,
//            'twitter' => $this->twitter,
//            'instagram' => $this->instagram,
//            'linkedin' => $this->linkedin,
//            'youtube' => $this->youtube,
//            'tiktok' => $this->tiktok,
//            'user_id' => $this->user_id,
//        ];
    }
}
