<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Stichoza\GoogleTranslate\GoogleTranslate;

class NameObserver
{
    public function saving(Model $data)
    {
        $name = request('name');
        $data->translateOrNew('ar')->name = $name;

        $locales = locales("ar");
        foreach ($locales as $locale) {
            $name = GoogleTranslate::trans($name ?? '', $locale);
            $data->translateOrNew($locale)->name = $name;
        }
    }
}
