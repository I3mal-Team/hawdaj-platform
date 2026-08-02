<?php

namespace App\Observers;

use App\Models\Menu;
use Stichoza\GoogleTranslate\GoogleTranslate;


class MenuObserver
{
    /**
     * Handle the Menu "created" event.
     *
     * @param  \App\Models\Menu  $menu
     * @return void
     */
    public function created(Menu $menu)
    {
        $title = request('title');
        $description = request('description');
        $menu->translateOrNew('ar')->title = $title;
        $menu->translateOrNew('ar')->description = $description;

        $locales = locales("ar");
        foreach ($locales as $locale) {
            $title = GoogleTranslate::trans($title, $locale);
            $notes = request()->note?GoogleTranslate::trans($description, $locale):'';
            $menu->translateOrNew($locale)->title = $title;
            $menu->translateOrNew($locale)->description = $description;
        }

        $menu->save();
    }

    /**
     * Handle the Menu "updated" event.
     *
     * @param  \App\Models\Menu  $menu
     * @return void
     */
    public function updated(Menu $menu)
    {
        //
    }

    /**
     * Handle the Menu "deleted" event.
     *
     * @param  \App\Models\Menu  $menu
     * @return void
     */
    public function deleted(Menu $menu)
    {
        //
    }

    /**
     * Handle the Menu "restored" event.
     *
     * @param  \App\Models\Menu  $menu
     * @return void
     */
    public function restored(Menu $menu)
    {
        //
    }

    /**
     * Handle the Menu "force deleted" event.
     *
     * @param  \App\Models\Menu  $menu
     * @return void
     */
    public function forceDeleted(Menu $menu)
    {
        //
    }
}
