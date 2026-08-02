<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CarRequestNotification extends Notification
{
    use Queueable;

    protected $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }


    public function via()
    {
        return ['mail', 'database'];
    }

    public function toMail()
    {
        return (new MailMessage)->view("dashboard.emails.cars.{$this->data['type']}",
            ['data' => $this->data]
        );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'invitation_id' => $this->data['invitation_id'],
            'host_id' => $this->data['host']->id,
            'driver' => $this->data['driver_id'],
            'title' => trans('dashboard.New Car'),
            'message' => trans('dashboard.New Meeting With').$this->data['driver_name'],
        ];
    }

    public function toDatabase($notifiable)
    {
        return [
            'invitation_id' => $this->data['invitation_id'],
            'host_id' => $this->data['host']->id,
            'driver' => $this->data['driver_id'],
            'title' =>  trans('dashboard.New Car'),
            'message' => trans('dashboard.New Meeting With').$this->data['driver_name'],
        ];
    }
}
