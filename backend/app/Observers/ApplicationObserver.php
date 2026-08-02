<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Stichoza\GoogleTranslate\GoogleTranslate;

class ApplicationObserver
{

    public function saving(Model $data)
    {
        $title = request('title');
        $description = request('description');

        $data->translateOrNew('ar')->title = $title;
        $data->translateOrNew('ar')->description = $description;

        $locales = locales("ar");
        foreach ($locales as $locale) {
            $title = GoogleTranslate::trans($title ?? '', $locale);
            $description = GoogleTranslate::trans($description ?? '', $locale);

            if ($locale == 'en') {
                $data->slug = Str::slug($title);
            }

            $data->translateOrNew($locale)->title = $title;
            $data->translateOrNew($locale)->description = $description;
        }
    }
}
