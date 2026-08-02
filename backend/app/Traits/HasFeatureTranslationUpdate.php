<?php

namespace App\Traits;

trait HasFeatureTranslationUpdate
{

    public function updateTranslation()
    {
        foreach (locales() as $locale) {
            $name = request($locale . '.name');
            $description = request($locale . '.description');
            $this->translateOrNew($locale)->name = $name;
            $this->translateOrNew($locale)->description = $description;
        }
        $this->save();
    }

}
