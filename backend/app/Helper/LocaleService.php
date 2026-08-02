<?php

namespace App\Helper;

use Mcamara\LaravelLocalization\LaravelLocalization;

class LocaleService
{
    public static function setLocale(): ?string
    {
        $locale = (new LaravelLocalization)->setLocale();
        if ($locale) {
            config(['translation-manager.route.prefix' => '/' . $locale . '/dashboard/setting/translations']);
            $text = '<?php return ' . var_export(config('translation-manager'), true) . ';';
            file_put_contents(config_path('translation-manager.php'), $text);
        }
        return $locale;
    }
}
