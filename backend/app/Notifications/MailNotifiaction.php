<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MailNotifiaction extends Notification
{
    use Queueable;

    protected $page, $data;

    public function __construct($page = '', $data = [], $via = [])
    {
        $this->page = $page;
        $this->data = $data;
        $this->via = $via;
    }

    public function via(): array
    {
        return $this->via;
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject($this->data['title'])
            ->view('dashboard.emails.index', ['page' => $this->page]);
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => $this->data['title'] ?? '',
            'type' => $this->data['type'] ?? '',
            'message' => $this->data['message'] ?? '',
            'url' => $this->data['url'] ?? 'javascript:;',
            'from_user_name' => $this->data['sender']['full_name'],
            'from_user_email' => $this->data['sender']['email'],
            'from_user_avatar' => $this->data['sender']['photo'],
            'to_user_id' => $notifiable->id
        ];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => $this->data['title'] ?? '',
            'message' => $this->data['message'] ?? '',
            'url' => $this->data['url'] ?? 'javascript:;',
        ];
    }
}
