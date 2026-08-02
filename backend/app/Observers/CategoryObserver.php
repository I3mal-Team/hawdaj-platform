<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Stichoza\GoogleTranslate\GoogleTranslate;

class CategoryObserver
{
    public function saving(Model $category)
    {
        $name = request('name');
        $notes = request('notes');
        $category->translateOrNew('ar')->name = $name;
        $category->translateOrNew('ar')->notes = $notes;

        $locales = locales("ar");
        foreach ($locales as $locale) {
            $name = GoogleTranslate::trans($name, $locale);
            $notes = GoogleTranslate::trans($notes ?? '', $locale);
            $category->translateOrNew($locale)->name = $name;
            $category->translateOrNew($locale)->notes = $notes;
        }
    }
}

