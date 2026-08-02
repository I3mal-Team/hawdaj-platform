<?php

namespace App\Observers;

use App\Models\Offer;
use Illuminate\Support\Str;
use Stichoza\GoogleTranslate\GoogleTranslate;


class OfferObserver
{
    public function saving(Offer $offer)
    {
        $title = request('title');
        $description = request('description');

        $offer->translateOrNew('ar')->title = $title;
        $offer->translateOrNew('ar')->description = $description;

        $locales = locales("ar");
        foreach ($locales as $locale) {
            $title = GoogleTranslate::trans($title ?? '', $locale);
            $description = GoogleTranslate::trans($description ?? '', $locale);

            $offer->translateOrNew($locale)->title = $title;
            $offer->translateOrNew($locale)->description = $description;
        }
    }
}
