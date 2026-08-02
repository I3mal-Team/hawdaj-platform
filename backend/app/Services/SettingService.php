<?php

namespace App\Services;

use App\Events\RealTimeMessage;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\MailNotifiaction;

class SettingService
{
    public static function addSetting($settings = null, $user_id = null): void
    {
        if ($settings == null) {
            $settings = config('settings')['setting'];
        }

        if ($settings && !empty($settings)) {
            foreach ($settings as $key => $setting) {
                foreach ($setting as $setting_key => $value) {
                    Setting::updateOrCreate([
                        'key' => $setting_key,
                        'group' => $key,
                        'user_id' => $user_id
                    ], [
                        'value' => $value,

                    ]);
                }
            }
        }
    }

    /**
     * @param $user_id
     * @param $key
     * @return false|int
     */
    public static function checkSendCode($user_id, $key)
    {
        if ($key == 'notification_new_user' || $key == 'notification_edit_user') {
            $key = 'notification_users';
        }

        $setting = Setting::where('user_id', $user_id)->where('key', $key)->first();

        if ($key == 'notification_general') {
            return 1;
        }

        return $setting ? (int)$setting->value : false;
    }

    /**
     * @param $user_id
     * @param $key
     * @param $body
     * @param $password
     * @return void
     */
    public static function notifyUser($user_id, $key, $body, $password = null)
    {
        $code = self::checkSendCode($user_id, $key);

        if ($code) {
            $user = User::find($user_id);

            if (!empty($password)) {
                $user->send_password = $password;
                $body['password'] = $password;
            }

            $page = self::getPage($key, $body);

            $data = [
                'title' => $body['title'] ?? __('dashboard.hello') . ':' . $user->full_name,
                'sender' => $user,
                'url' => $body['url'] ?? 'javascript:;',
                'message' => $body['message']
            ];

            $via = self::handleViaChannal($code);

            try{
                $user->notify(new MailNotifiaction($page, $data, $via));
            }catch (\Exception $e) {
//                    return unKnownError($e->getMessage());
            }

            if (($code == 1 || $code == 3) && $key != 'notification_models') {
                try {
                    event(new RealTimeMessage($user_id, $data));
                } catch (\Exception $e) {
                    return;
                }
            }
        }
    }

    private static function getPage($key, $data)
    {
        $page = '';
        switch ($key) {
            case $key == 'notification_general':
                $page = (Setting::where('key', 'MAIL_NOTIFY_GENERAL')->first())->value;
                $page = str_replace(['[[body]]'], [$data['message']], $page);
                break;

            case $key == 'notification_new_user':
                $page = (Setting::where('key', 'MAIL_NOTIFY_NEW_USER')->first())->value;
                $page = str_replace(['[[name]]', '[[email]]', '[[password]]'],
                    [$data['full_name'], $data['email'], $data['password']], $page);
                break;

            case $key == 'notification_edit_user':
                $page = (Setting::where('key', 'MAIL_NOTIFY_UPDATE_USER')->first())->value;
                $page = str_replace(['[[name]]', '[[email]]', '[[password]]'],
                    [$data['full_name'], $data['email'], $data['password'] ?? ''], $page);
                break;

            case $key == 'notification_models':
                $page = (Setting::where('key', 'MAIL_NOTIFY_MODELS')->first())->value;
                $page = str_replace(['[[body]]'], [$data['message']], $page);

                break;
        }

        return $page;
    }

    public static function handleViaChannal($code): array
    {
        if ($code == 1) {

            $via = ['database'];

        } elseif ($code == 2) {

            $via = ['mail'];

        } elseif ($code == 3) {

            $via = ['mail', 'database'];
        }

        return $via;
    }
}
