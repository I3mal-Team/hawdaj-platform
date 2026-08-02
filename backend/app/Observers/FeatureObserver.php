<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Stichoza\GoogleTranslate\GoogleTranslate;

class FeatureObserver
{
    public function saving(Model $data)
    {
        $name = request('name');
        $description = request('description');
        $data->translateOrNew('ar')->name = $name;
        $data->translateOrNew('ar')->description = $description;

        $locales = locales("ar");
        foreach ($locales as $locale) {
            $name = GoogleTranslate::trans($name ?? '', $locale);
            $description = GoogleTranslate::trans($description ?? '', $locale);
            $data->translateOrNew($locale)->name = $name;
            $data->translateOrNew($locale)->description = $description;
        }
    }
}
