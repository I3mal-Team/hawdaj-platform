<?php
namespace App\Traits;
trait HasOfferTranslationUpdate  {

    public function updateTranslation()
    {
        foreach (locales() as $locale) {
            $title = request($locale . '.title');
            $this->translateOrNew($locale)->title = $title;
        }
        $this->save();
    }
}
