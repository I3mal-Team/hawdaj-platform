<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\MarketingNewContentNotification;
use App\Services\MarketingPush\MarketingCopyBuilder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BroadcastMarketingNewContentJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /** @var string */
    protected $modelClass;

    /** @var int */
    protected $modelId;

    /** @var string */
    protected $contentType;

    public function __construct(string $modelClass, int $modelId, string $contentType)
    {
        $this->modelClass = $modelClass;
        $this->modelId = $modelId;
        $this->contentType = $contentType;
    }

    public function handle(MarketingCopyBuilder $copy): void
    {
        $model = $this->modelClass::query()->find($this->modelId);
        if (!$model) {
            return;
        }

        $chunk = (int) config('marketing_push.user_chunk_size', 150);

        User::query()
            ->whereNull('deleted_at')
            ->whereNotNull('fcm_token')
            ->where('fcm_token', '!=', '')
            ->orderBy('id')
            ->select(['id', 'first_name', 'fcm_token'])
            ->chunkById($chunk, function ($users) use ($copy, $model) {
                foreach ($users as $user) {
                    try {
                        [$title, $body, $data] = $copy->build($user, $this->contentType, $model);
                        $user->notify(new MarketingNewContentNotification($title, $body, $data));
                    } catch (\Throwable $e) {
                        Log::notice('marketing_push.user_notify_failed', [
                            'user_id' => $user->id,
                            'message' => $e->getMessage(),
                        ]);
                    }
                }
            });
    }
}
