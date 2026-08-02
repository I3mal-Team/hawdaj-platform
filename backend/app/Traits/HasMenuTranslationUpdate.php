<?php
namespace App\Traits;

trait HasMenuTranslationUpdate
{
    public function updateTranslation()
    {
        foreach (locales() as $locale) {
            $title = request($locale . '.title');
            $description = request($locale . '.description');
            $this->translateOrNew($locale)->title = $title;
            $this->translateOrNew($locale)->description = $description;
        }
        $this->save();
    }
}