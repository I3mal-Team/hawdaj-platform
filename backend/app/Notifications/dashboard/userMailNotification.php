<?php

namespace App\Notifications\dashboard;

use App\Models\EmailHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class userMailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $data;
    protected $user;
    protected $body;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($userData, $data)
    {
        $this->data = $data;
        $this->user = $userData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $this->body = self::getContent($this->user,$this->data);
        return (new MailMessage)->view(
            'dashboard.mailTemp', ['body' => $this->body]
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
            //
        ];
    }

    public static function getContent($user,$data)
    {
        $app_content = '';
        $app_content .= EmailHelper::getEmailHeader();
        $app_content .= EmailHelper::getMailBody('new_user',$user,$data);
        $app_content .= EmailHelper::getEmailFooter();
        return $app_content;
    }

}
