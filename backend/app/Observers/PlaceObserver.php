<?php

namespace App\Observers;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Stichoza\GoogleTranslate\GoogleTranslate;

class PlaceObserver
{
    public function creating(Model $data)
    {
        if (request()->has('title')) {
            $title = request('title');
            $description = request('description');
            $data->translateOrNew('ar')->title = $title;
            $data->translateOrNew('ar')->description = $description;

            $locales = locales("ar");
            foreach ($locales as $locale) {
                $title = GoogleTranslate::trans($title ?? '', $locale);
                $description = GoogleTranslate::trans($description ?? '', $locale);
                $data->translateOrNew($locale)->title = $title;
                $data->translateOrNew($locale)->description = $description;
                if ($locale == "en")
                    $data->slug = Str::slug($title);
            }
        }
    }

    public function saving(Model $data)
    {
        if ($data->isDirty('title') || $data->isDirty('description')) {

            $title = request('title');
            $description = request('description');
            $data->translateOrNew('ar')->title = $title;
            $data->translateOrNew('ar')->description = $description;

            $locales = locales("ar");
            foreach ($locales as $locale) {
                $title = GoogleTranslate::trans($title ?? '', $locale);
                $description = GoogleTranslate::trans($description ?? '', $locale);
                $data->translateOrNew($locale)->title = $title;
                $data->translateOrNew($locale)->description = $description;
                if ($locale == "en")
                    $data->slug = Str::slug($title);
            }
        }
    }
}
