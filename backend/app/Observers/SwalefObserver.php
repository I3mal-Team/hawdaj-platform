<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Stichoza\GoogleTranslate\GoogleTranslate;

class SwalefObserver
{
    public function saving(Model $data)
    {
        $title = request('title');
        $description = request('description');
        $content = request('content');
        $timePeriod = request('timePeriod');
        $location = request('location');
        $storyImportance = request('storyImportance');
        $relevanceToPresent = request('relevanceToPresent');
        $source = request('source');
        $audioStoryTitle = request('audioStoryTitle');
//        $mainCharacters = request('mainCharacters');

        if (! $title) {
            return;
        }

        $data->translateOrNew('ar')->title = $title;
        $data->translateOrNew('ar')->description = $description;
        $data->translateOrNew('ar')->content = $content;
        $data->translateOrNew('ar')->timePeriod = $timePeriod;
        $data->translateOrNew('ar')->location = $location;
        $data->translateOrNew('ar')->storyImportance = $storyImportance;
        $data->translateOrNew('ar')->relevanceToPresent = $relevanceToPresent;
        $data->translateOrNew('ar')->source = $source;
        $data->translateOrNew('ar')->audioStoryTitle = $audioStoryTitle;
//        $data->translateOrNew('ar')->mainCharacters = $mainCharacters;

        $locales = locales("ar");
        foreach ($locales as $locale) {
            $title = GoogleTranslate::trans($title, $locale);
            $description = GoogleTranslate::trans($description, $locale);
            $content = GoogleTranslate::trans($content, $locale);
            $timePeriod = GoogleTranslate::trans($timePeriod ?? "", $locale);
            $location = GoogleTranslate::trans($location ?? "", $locale);
            $storyImportance = GoogleTranslate::trans($storyImportance ?? "", $locale);
            $relevanceToPresent = GoogleTranslate::trans($relevanceToPresent ?? "", $locale);
            $source = GoogleTranslate::trans($source ?? "", $locale);
            $audioStoryTitle = GoogleTranslate::trans($audioStoryTitle ?? "", $locale);
//            $mainCharacters = GoogleTranslate::trans($mainCharacters ?? "", $locale);

            $data->translateOrNew($locale)->title = $title;
            $data->translateOrNew($locale)->description = $description;
            $data->translateOrNew($locale)->content = $content;
            $data->translateOrNew($locale)->timePeriod = $timePeriod;
            $data->translateOrNew($locale)->location = $location;
            $data->translateOrNew($locale)->storyImportance = $storyImportance;
            $data->translateOrNew($locale)->relevanceToPresent = $relevanceToPresent;
            $data->translateOrNew($locale)->source = $source;
            $data->translateOrNew($locale)->audioStoryTitle = $audioStoryTitle;
//            $data->translateOrNew($locale)->mainCharacters = $mainCharacters;
            if ($locale == "en")
                $data->slug = Str::slug($title);
        }

    }

}
