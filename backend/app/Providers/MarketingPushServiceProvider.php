<?php

namespace App\Providers;

use App\Services\MarketingPush\MarketingPushDispatcher;
use Illuminate\Support\ServiceProvider;

class MarketingPushServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        foreach (MarketingPushDispatcher::MODEL_TYPE_MAP as $class => $type) {
            $class::created(static function ($model) {
                MarketingPushDispatcher::onCreated($model);
            });

            if (in_array($type, ['place', 'store', 'event', 'zad', 'application', 'guide'], true)) {
                $class::updated(static function ($model) {
                    MarketingPushDispatcher::onUpdated($model);
                });
            }
        }
    }
}
