<?php

namespace App\Traits;

trait HasIcon
{

    public function getIconAttribute()
    {
        if ($this->attributes['icon']) {
            if (file_exists('uploads/' . $this->attributes['icon'])) {
                return 'uploads/' . $this->attributes['icon'];
            }
        }
        return 'front_assets/imgs/zad1.jpg';
    }

}
