<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class EmailHelper extends Model
{
    /**
     * Get email header
     *
     * @access public
     *
     * @return mixed
     */
    public static function getEmailHeader()
    {
        ob_start();
        ?>
        <div class="mail-header ">
            <div class="img-cont">
                <img src="<?= asset('images/mail.png') ?>" alt="" width="300" style="height:auto;display:block;" />
            </div>
            <h2 class="mail-title">
                Visitor Management Mail
            </h2>
        </div>
        <div class="mail-body">
        <?php
        return ob_get_clean();
    }


    /**
     * Get email body
     *
     * @access public
     *
     * @return mixed
     */
    public static function getMailBody($type = '', $model = '', $data = '')
    {
        $content = new MailTemplate();
        if ($type == 'visitor') {
            if(isset($data['type'])) {
                if ($data['type'] == 'new_invitation') {
                    $content = $content->where('id',2)->first()->content;
                    $content = str_replace("%visitor_name%", $model->first_name ?? ''.' '. $model->last_name ?? '', $content);
                    $content = str_replace("%host_name%", $data['host']->full_name ?? '', $content);
                    $content = str_replace("%host_email%", genrateEmailLink($data['host']->email ?? ''), $content);
                    $content = str_replace("%link%", genrateConformBtn($data['url'] ?? ''), $content);
                    $content = str_replace("%Invitation_id%", $data['invitation_id'] ?? '', $content);
                }
                else if($data['type'] == 'confirm_invitation') {
                    $content = $content->where('id',4)->first()->content;
                    $content = str_replace("%visitor_name%", $data['visitor_name'] ?? '', $content);
                    $content = str_replace("%request_no%", $data['visitor_request_id'] ?? '', $content);
                    $content = str_replace("%host_name%", $data['host']->full_name ?? '', $content);
                    $content = str_replace("%host_email%", genrateEmailLink($data['host']->email ?? ''), $content);
                    $content = str_replace("%host_phone%", $data['host']->phone ?? '', $content);
                    $content = str_replace("%from_date%", dateFormat($data['visitRequest']['from_date'] ?? ''), $content);
                    $content = str_replace("%to_date%", dateFormat($data['visitRequest']['to_date'] ?? ''), $content);
                    $content = str_replace("%from_time%", timeFormat($data['visitRequest']['from_fromtime'] ?? ''), $content);
                    $content = str_replace("%to_time%", timeFormat($data['visitRequest']['to_totime'] ?? ''), $content);
                    $content = str_replace("%qr_code%", genrateQrCode($data['visitor_request_id'] ?? ''), $content);
                    $content = str_replace("%cancel_button%", genrateCancelButton($data['visitor_request_id'] ?? ''), $content);
                }
                else {
                    $content = $content->where('id',5)->first()->content;
                    $content = str_replace("%visitor_name%", $data['visitor_name'] ?? '', $content);
                    $content = str_replace("%company%", $data['company'] ?? '', $content);
                    $content = str_replace("%reason%", $data['reason'] ?? '', $content);
                    $content = str_replace("%date%", $data['date'] ?? '', $content);
                    $content = str_replace("%time%", timeFormat($data['time'] ?? ''), $content);
                }
            }
        }
        else if ($type == 'new_user') {
            $content = $content->where('id',1)->first()->content;
            $content = str_replace("%name%", $model->first_name ?? '', $content);
            $content = str_replace("%password%", $data['password'] ?? '', $content);
        }
        else if ($type == 'material') {
            if (isset($data['type'])) {
                if ($data['type'] == 'confirm_invitation') {
                    $content = $content->where('id',3)->first()->content;
                    $content = str_replace("%transporter_name%", $data['transporter_name'] ?? '', $content);
                    $content = str_replace("%host_name%", $data['host']['full_name'] ?? '', $content);
                    $content = str_replace("%host_email%", genrateEmailLink($data['host']['email'] ?? ''), $content);
                    $content = str_replace("%host_phone%", $data['host']['phone'] ?? '', $content);
                    $content = str_replace("%from_date%", dateFormat($data['materialRequest']['from_date'] ?? ''), $content);
                    $content = str_replace("%to_date%", dateFormat($data['materialRequest']['to_date'] ?? ''), $content);
                    $content = str_replace("%from_time%", timeFormat($data['materialRequest']['from_fromtime'] ?? ''), $content);
                    $content = str_replace("%to_time%", timeFormat($data['materialRequest']['to_totime'] ?? ''), $content);
                    $content = str_replace("%qr_code%", genrateQrCode($data['material_request_id'] ?? ''), $content);
                }
                else if($data['type'] == 'notify_host') {
                    $content = $content->where('id',6)->first()->content;
                    $content = str_replace("%transporter_name%", $data['transporter_name'] ?? '', $content);
                    $content = str_replace("%company%", $data['transporter_company'] ?? '', $content);
                    $content = str_replace("%link%", genrateConformBtn($data['url']) ?? '', $content);
                    $content = str_replace("%invitation_id%", $data['invitation_id'] ?? '', $content);
                }
            }
        }
        else if($type == 'contractor') {
            if (isset($data['type'])) {
                if ($data['type'] == 'new_invitation') {
                    $content = $content->where('id',7)->first()->content;
                    $content = str_replace("%contractor_name%", $model->first_name ?? ''.' '. $model->last_name ?? '', $content);
                    $content = str_replace("%host_name%", $data['host']->full_name ?? '', $content);
                    $content = str_replace("%host_email%", genrateEmailLink($data['host']->email ?? ''), $content);
                    $content = str_replace("%link%", genrateConformBtn($data['url'] ?? ''), $content);
                    $content = str_replace("%Invitation_id%", $data['invitation_id'] ?? '', $content);
                }
                else if($data['type'] == 'confirm_invitation') {
                    $content = $content->where('id',8)->first()->content;
                    $content = str_replace("%contractor_name%", $data['contractor_name'] ?? '', $content);
                    $content = str_replace("%request_no%", $data['contractor_request_id'] ?? '', $content);
                    $content = str_replace("%host_name%", $data['host']->full_name ?? '', $content);
                    $content = str_replace("%host_email%", genrateEmailLink($data['host']->email ?? ''), $content);
                    $content = str_replace("%host_phone%", $data['host']->phone ?? '', $content);
                    $content = str_replace("%from_date%", dateFormat($data['contractRequest']['from_date'] ?? ''), $content);
                    $content = str_replace("%to_date%", dateFormat($data['contractRequest']['to_date'] ?? ''), $content);
                    $content = str_replace("%qr_code%", genrateQrCode($data['contractor_request_id'] ?? ''), $content);
                    $content = str_replace("%cancel_button%", genrateCancelButton($data['contractor_request_id'] ?? ''), $content);
                }
                else {
                    $content = $content->where('id',9)->first()->content;
                    $content = str_replace("%contractor_name%", $data['contractor_name'] ?? '', $content);
                    $content = str_replace("%company%", $data['company'] ?? '', $content);
                    $content = str_replace("%date%", $data['date'] ?? '', $content);
                    $content = str_replace("%time%", timeFormat($data['time'] ?? ''), $content);
                }
            }
        }
        return $content;
    }


    /**
     * Get email footer
     *
     * @access public
     *
     * @return mixed
     */
    public static function getEmailFooter()
    {
        ob_start();
        ?>
        </div>
        <div class="mail-footer">
            <p style="font-size: 13px; line-height: 13px; color: #fff; margin: 0; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">
                Copyrights 2022© Hawdaj Management System.</p>
        </div>
        <?php
        return ob_get_clean();
    }


}
