<?php

namespace App\Providers;

use Barryvdh\TranslationManager\TranslationServiceProvider;

class TranslationProvider extends TranslationServiceProvider
{
    public function boot()
    {
        $vendor_path = base_path('vendor/barryvdh/laravel-translation-manager/src');
        $viewPath = $vendor_path . '/../resources/views';
        $this->loadViewsFrom($viewPath, 'translation-manager');
        $this->publishes([
            $viewPath => base_path('resources/views/vendor/translation-manager'),
        ], 'views');

        $migrationPath = $vendor_path . '/../database/migrations';
        $this->publishes([
            $migrationPath => base_path('database/migrations'),
        ], 'migrations');
        $this->loadRoutesFrom(base_path('routes/translations.php'));
    }

}
