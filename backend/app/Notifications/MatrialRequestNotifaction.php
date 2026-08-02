<?php

namespace App\Notifications;

use App\Models\EmailHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class MatrialRequestNotifaction extends Notification implements ShouldQueue
{
    use Queueable;

    protected $data;

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
        $this->body = self::getContent($this->data);

        return (new MailMessage)->view(
            'dashboard.mailTemp', ['body' => $this->body]
        );
//        return (new MailMessage)->view("dashboard.emails.matrials.{$this->data['type']}",
//            ['data' => $this->data]
//        );
    }


    public function toArray($notifiable)
    {
        return [
            'invitation_id' => $this->data['invitation_id'],
            'host_id' => $this->data['host']->id,
            'transporter' => $notifiable->id,
            'message' => "New metting with $notifiable->contact_person"
        ];
    }

    public function toDatabase($notifiable)
    {
        return [
            'invitation_id' => $this->data['invitation_id'],
            'host_id' => $this->data['host']->id,
            'transporter' => $notifiable->id,
            'message' => "New metting with $notifiable->contact_person",
            'title' => 'New Material Request'
        ];
    }

    public static function getContent($data)
    {
        Log::info('inter in getContent');
        $app_content = '';
        $app_content .= EmailHelper::getEmailHeader();
        $app_content .= EmailHelper::getMailBody('material','material',$data);
        $app_content .= EmailHelper::getEmailFooter();
        return $app_content;
    }

}
