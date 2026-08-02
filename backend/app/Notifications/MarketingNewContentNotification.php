<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class MarketingNewContentNotification extends Notification
{
    /** @var string */
    protected $title;

    /** @var string */
    protected $body;

    /** @var array<string, string> */
    protected $data;

    /**
     * @param array<string, string> $data
     */
    public function __construct(string $title, string $body, array $data = [])
    {
        $this->title = $title;
        $this->body = $body;
        $this->data = $data;
    }

    /**
     * @param mixed $notifiable
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return [FcmChannel::class];
    }

    /**
     * @param mixed $notifiable
     */
    public function toFcm($notifiable): FcmMessage
    {
        return FcmMessage::create()
            ->setData($this->data)
            ->setNotification(
                FcmNotification::create()
                    ->setTitle($this->title)
                    ->setBody($this->body)
            );
    }
}
