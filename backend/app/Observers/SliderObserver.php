<?php

namespace App\Observers;

use App\Models\Slider;
use Stichoza\GoogleTranslate\GoogleTranslate;

class SliderObserver
{
    public function saving(Slider $slider): void
    {
        $title = request('title');

        if ($title === null || $title === '') {
            return;
        }

        $slider->translateOrNew('ar')->title = $title;

        foreach (locales('ar') as $locale) {
            $translatedTitle = GoogleTranslate::trans($title, $locale);
            $slider->translateOrNew($locale)->title = $translatedTitle;
        }
    }
}
