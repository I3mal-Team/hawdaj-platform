<?php

namespace Database\Seeders;

use App\Models\MailTemplate;
use Illuminate\Database\Seeder;

class MailTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // store template
        MailTemplate::firstOrCreate([
            'type' => 'users',
            'title' => 'new user email',
            'content' => '<p>username is : %name%</p><p>password is : %password%</p>',
            'key_words' => json_encode(['username', 'password'])
        ]);
        MailTemplate::firstOrCreate([
            'type' => 'visitor',
            'title' => 'visit conformation',
            'content' => '<p>welcome %visitor_name%</p><p>You have a new meeting with :</p><p>Guard</p><p>%host_name%</p><p>%host_email%</p><p>%link%</p><p>#Invitation ID</p><p>%Invitation_id%</p>',
            'key_words' => json_encode(['visitor_name', 'host_name', 'host_email', 'link', 'Invitation_id'])
        ]);
        MailTemplate::firstOrCreate([
            'type' => 'material',
            'title' => 'material_confirm_invitation',
            'content' => '<p>Your Material Request has been <strong>confirmed</strong></p><p><strong>welcome : </strong>%transporter_name%</p><p>Your <strong>Host </strong>is :&nbsp;</p><p>%host_name%</p><p>%host_email%</p><p>%host_phone%</p><p><strong>Permission Validity</strong></p><p><strong>From : </strong>%from_date% &nbsp;%from_time%</p><p><strong>To: </strong>%to_date% &nbsp;%to_time%</p><p>&nbsp;</p><p>%qr_code%</p>',
            'key_words' => json_encode(['host_name', 'transporter_name', 'host_email', 'host_phone', 'from_date', 'from_time', 'to_date', 'to_time', 'qr_code'])
        ]);
        MailTemplate::firstOrCreate([
            'type' => 'confirm_invitation',
            'title' => 'visitor_confirm_invitation',
            'content' => '<p><strong>welcome : </strong>%visitor_name%</p><p><strong>your meeting has been confirmed</strong></p><p>Your <strong>Host </strong>is :&nbsp;</p><p>%host_name%</p><p>%host_email%</p><p>%host_phone%</p><p># &nbsp;%request_no%</p><p><strong>From : </strong>%from_date% &nbsp;%from_time%</p><p><strong>To: </strong>%to_date% &nbsp;%to_time%</p><p>%qr_code%</p><p>%cancel_button%</p>',
            'key_words' => json_encode(['visitor_name', 'host_name', 'host_email', 'host_phone', 'request_no', 'from_date', 'from_time', 'to_date', 'to_time', 'qr_code', 'cancel_button'])
        ]);
        MailTemplate::firstOrCreate([
            'type' => 'notify_host_visitor',
            'title' => 'visitor_notify_host',
            'content' => '<p><strong>your visitor </strong>%visitor_name% <strong>checked_in</strong></p><p><strong>company : </strong>%company%</p><p><strong>purpose : </strong>%reason%</p><p><strong>Date </strong>: %date% &nbsp;&nbsp;<strong>Time &nbsp;</strong>%time%</p>',
            'key_words' => json_encode(['visitor_name', 'company', 'reason', 'date', 'time'])
        ]);
        MailTemplate::firstOrCreate([
            'type' => 'notify_host_material',
            'title' => 'material_notify_host',
            'content' => '<p><strong>Transporter Notification</strong></p><p>transporter name : &nbsp;%transporter_name%</p><p>company : &nbsp;%company%</p><p>%link%</p><p>%invitation_id%</p>',
            'key_words' => json_encode(['transporter_name', 'company', 'link', 'invitation_id'])
        ]);
        MailTemplate::firstOrCreate([
            'type' => 'contractor',
            'title' => 'contractor conformation',
            'content' => '<p>welcome %contractor_name% were happy to make contract with you</p><p>You have a new meeting with :</p><p>Guard</p><p>%host_name%</p><p>%host_email%</p><p>%link%</p><p>#Invitation ID</p><p>%Invitation_id%</p>',
            'key_words' => json_encode(['contractor_name', 'host_name', 'host_email', 'link', 'Invitation_id'])
        ]);
        MailTemplate::firstOrCreate([
            'type' => 'confirm_invitation',
            'title' => 'contractor_confirm_invitation',
            'content' => '<p><strong>welcome : </strong>%contractor_name%</p><p><strong>your meeting has been confirmed</strong></p><p>Your <strong>Host </strong>is :&nbsp;</p><p>%host_name%</p><p>%host_email%</p><p>%host_phone%</p><p># &nbsp;%request_no%</p><p><strong>From : </strong>%from_date%</p><p><strong>To: </strong>%to_date%&nbsp;</p><p>%qr_code%</p><p>%cancel_button%</p>',
            'key_words' => json_encode(['contractor_name', 'host_name', 'host_email', 'host_phone', 'request_no', 'from_date', 'to_date', 'qr_code', 'cancel_button'])
        ]);
        MailTemplate::firstOrCreate([
            'type' => 'notify_host_contractor',
            'title' => 'contractor_notify_host',
            'content' => '<p><strong>your contractor </strong>%contractor_name% <strong>checked_in</strong></p><p><strong>company : </strong>%company%</p><p><strong>Date </strong>: %date% &nbsp;&nbsp;<strong>Time &nbsp;</strong>%time%</p>',
            'key_words' => json_encode(['contractor_name', 'company', 'date', 'time'])
        ]);
    }
}
