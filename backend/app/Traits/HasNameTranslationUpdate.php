<?php

namespace App\Traits;

trait HasNameTranslationUpdate
{

    public function updateTranslation()
    {
        foreach (locales() as $locale) {
            $name = request($locale . '.name');
            $this->translateOrNew($locale)->name = $name;
        }
        $this->save();
    }

}
