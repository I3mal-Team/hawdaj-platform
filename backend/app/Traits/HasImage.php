<?php

namespace App\Traits;

trait HasImage
{
    public function getImageAttribute()
    {
        if (method_exists($this, 'getFirstMediaUrl')) {
            $mediaUrl = $this->getFirstMediaUrl('image');
            if ($mediaUrl) {
                if (filter_var($mediaUrl, FILTER_VALIDATE_URL)) {
                    return $mediaUrl;
                }
                return asset($mediaUrl);
            }
        }

        if (isset($this->attributes['image']) && $this->attributes['image']) {
            if (file_exists('uploads/' . $this->attributes['image'])) {
                return 'uploads/' . $this->attributes['image'];
            }
        }
        return 'front_assets/imgs/zad1.jpg';
    }
}
