<?php

namespace App\Mail\Dashboard;

use http\Env;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class userPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $userData;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        Log::info(['data is' => $data]);
        $this->userData = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->userData['email'], \env('MAIL_FROM_NAME'))
            ->view('dashboard.emails.newUserMail',['data' => $this->userData]);
    }
}
