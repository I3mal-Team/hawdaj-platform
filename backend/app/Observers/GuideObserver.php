<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Stichoza\GoogleTranslate\GoogleTranslate;

class GuideObserver
{
    public function saving(Model $data)
    {
        $name = request('name');
        $nickName = request('nickName');
        $description = request('description');

        if (! $name || ! $nickName || ! $description) {
            return;
        }

//        $data->translateOrNew('ar')->name = $name;
//        $data->translateOrNew('ar')->nickName = $nickName;
//        $data->translateOrNew('ar')->description = $description;

        $locales = locales();
        foreach ($locales as $locale) {
            $name = GoogleTranslate::trans($name ?? '', $locale);
            $nickName = GoogleTranslate::trans($nickName ?? '', $locale);
            $description = GoogleTranslate::trans($description ?? '', $locale);


            $data->translateOrNew($locale)->name = $name;
            $data->translateOrNew($locale)->nickName = $nickName;
            $data->translateOrNew($locale)->description = $description;
        }
    }
}
