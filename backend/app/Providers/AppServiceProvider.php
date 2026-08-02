<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Notification;
use App\Models\Place;
use App\Models\Price;
use App\Models\Region;
use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Module;
use Modules\Report\Entities\PinnedReport;
use App\Models\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (env('APP_ENV') === 'production') {
            $this->app['request']->server->set('HTTPS', true);
        }

        collect(scandir(app_path('Helper')))->filter(function ($file) {
            return Str::contains($file, '.php');
        })->each(function ($item) {
            require_once app_path('Helper') . '/' . $item;
        });

        $this->app->singleton('pinned_reports', function ($app) {
            return PinnedReport::primary()->latest()->limit(5)->get();
        });

        $this->app->singleton('models', function ($app) {
            return array_filter(array_keys(Module::allEnabled()), function ($item) {
                if (Str::endsWith($item, 'Model')) {
                    return $item;
                }
            });
        });

        $this->app->singleton('notifications', function ($app) {
            $notification = Notification::primary()->latest()->limit(20)->get();

            if ($notification) {
                return $notification;
            }
            return [];
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultstringLength(191);

        try {

            $title = Setting::where('key', 'TITLE')->first()['value'];
            if ($title)
                view()->share(compact('title'));

            $settings = Setting::get();

            $categories = Category::all();
            $regions = Region::all();
            // $cities = City::all();

            $seasons = [
                "طوال العام",
                "موسم الربيع",
                "موسم الصيف",
                "موسم الخريف",
                "موسم الشتاء"
            ];
            $key_words = [];
            foreach (Place::select('key_words')->get() as $key => $value) {
                if ($value['key_words']) {
                    foreach ($value->key_words as $key => $value) {
                        array_push($key_words, $value);
                    }
                }
            }
            $prices = Price::all();

            if ($settings)
                view()->share(compact('settings', 'key_words', 'categories', 'seasons', 'prices', 'regions'));
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        Paginator::useBootstrap();
    }
}
