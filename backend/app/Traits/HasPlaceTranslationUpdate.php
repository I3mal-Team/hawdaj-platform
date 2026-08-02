<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasPlaceTranslationUpdate
{

    public function updateTranslation()
    {
        foreach (locales() as $locale) {
            $title = request($locale . '.title');
            $description = request($locale . '.description');
            $this->translateOrNew($locale)->title = $title;
            $this->translateOrNew($locale)->description = $description;
        }
        $this->slug = Str::slug(request('en.title'));
        $this->save();
    }

}
