<?php

namespace App\Notifications;

use App\Models\EmailHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContractRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $data;
    protected $visitor;
    protected $body;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($visitorData, $data)
    {
        $this->data = $data;
        $this->visitor = $visitorData;
    }

    public function via(): array
    {
        return ['mail', 'database'];
    }

    /**
     * @return MailMessage
     */
    public function toMail(): MailMessage
    {
        $this->body = self::getContent($this->visitor,$this->data);
        return (new MailMessage)->view(
            'dashboard.mailTemp', ['body' => $this->body]
        );
//        return (new MailMessage)->view("dashboard.emails.contracts.{$this->data['type']}", ['data' => $this->data]);
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
            'visitor_id' => $notifiable->id,
            'message' => "New meting with $notifiable->first_name"
        ];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'invitation_id' => $this->data['invitation_id'],
            'host_id' => $this->data['host']->id,
            'contractor_id' => $notifiable->id,
            'message' => "New meeting with $notifiable->first_name",
            'title' => __('dashboard.new_contractor'),
        ];
    }
    public static function getContent($visitor,$data)
    {
        $app_content = '';
        $app_content .= EmailHelper::getEmailHeader();
        $app_content .= EmailHelper::getMailBody('contractor',$visitor,$data);
        $app_content .= EmailHelper::getEmailFooter();
        return $app_content;
    }

}
