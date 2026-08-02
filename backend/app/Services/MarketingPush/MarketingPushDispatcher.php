<?php

namespace App\Services\MarketingPush;

use App\Jobs\BroadcastMarketingNewContentJob;
use App\Models\Application;
use App\Models\Event;
use App\Models\Guide;
use App\Models\Menu;
use App\Models\Offer;
use App\Models\Place;
use App\Models\Store;
use App\Models\Swalef;
use App\Models\ZadElgadel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MarketingPushDispatcher
{
    /** @var array<class-string, string> */
    public const MODEL_TYPE_MAP = [
        Place::class => 'place',
        Store::class => 'store',
        Event::class => 'event',
        ZadElgadel::class => 'zad',
        Application::class => 'application',
        Menu::class => 'menu',
        Offer::class => 'offer',
        Swalef::class => 'swalef',
        Guide::class => 'guide',
    ];

    public static function firebaseCredentialsConfigured(): bool
    {
        $path = env('FIREBASE_CREDENTIALS') ?: env('GOOGLE_APPLICATION_CREDENTIALS');
        if (!$path || !is_string($path)) {
            return false;
        }

        if (Str::startsWith($path, '/') || preg_match('#^[A-Za-z]:\\\\#', $path)) {
            return is_file($path);
        }

        return is_file(base_path($path));
    }

    public static function enabled(): bool
    {
        return (bool) config('marketing_push.enabled', true);
    }

    public static function onCreated(Model $model): void
    {
        if (!self::enabled() || !self::firebaseCredentialsConfigured()) {
            return;
        }

        if (app()->runningInConsole() && !config('marketing_push.allow_console_model_events', false)) {
            return;
        }

        $type = self::typeFor($model);
        if (!$type) {
            return;
        }

        if (!self::qualifiesForCreated($model, $type)) {
            return;
        }

        self::dispatchJob($model, $type);
    }

    public static function onUpdated(Model $model): void
    {
        if (!self::enabled() || !self::firebaseCredentialsConfigured()) {
            return;
        }

        if (app()->runningInConsole() && !config('marketing_push.allow_console_model_events', false)) {
            return;
        }

        $type = self::typeFor($model);
        if (!$type) {
            return;
        }

        if (!self::qualifiesForActivation($model, $type)) {
            return;
        }

        self::dispatchJob($model, $type);
    }

    private static function typeFor(Model $model): ?string
    {
        return self::MODEL_TYPE_MAP[get_class($model)] ?? null;
    }

    private static function qualifiesForCreated(Model $model, string $type): bool
    {
        if (self::requiresActiveColumn($type)) {
            return self::isActive($model->getAttribute('active'));
        }

        return true;
    }

    private static function qualifiesForActivation(Model $model, string $type): bool
    {
        if (!self::requiresActiveColumn($type)) {
            return false;
        }

        return $model->wasChanged('active') && self::isActive($model->getAttribute('active'));
    }

    private static function requiresActiveColumn(string $type): bool
    {
        return in_array($type, ['place', 'store', 'event', 'zad', 'application', 'guide'], true);
    }

    /**
     * @param mixed $value
     */
    private static function isActive($value): bool
    {
        return $value === true || $value === 1 || $value === '1';
    }

    private static function dispatchJob(Model $model, string $type): void
    {
        try {
            $pending = BroadcastMarketingNewContentJob::dispatch(
                get_class($model),
                (int) $model->getKey(),
                $type
            );
            $queue = config('marketing_push.queue');
            if (!empty($queue)) {
                $pending->onQueue((string) $queue);
            }
        } catch (\Throwable $e) {
            Log::warning('marketing_push.dispatch_failed', [
                'message' => $e->getMessage(),
                'type' => $type,
                'id' => $model->getKey(),
            ]);
        }
    }
}
