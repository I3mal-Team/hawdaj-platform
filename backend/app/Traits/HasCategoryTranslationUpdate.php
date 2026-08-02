<?php

namespace App\Traits;

trait HasCategoryTranslationUpdate
{

    public function updateTranslation()
    {
        foreach (locales() as $locale) {
            $name = request($locale . '.name');
            $notes = request($locale . '.notes');
            $this->translateOrNew($locale)->name = $name;
            $this->translateOrNew($locale)->notes = $notes;
        }
        $this->save();
    }

}
