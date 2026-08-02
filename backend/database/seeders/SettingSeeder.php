<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = $this->findSetting('APP_URL');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'http://localhost',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('CLIENT_URL');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'https://ncms-staging-website.azurewebsites.net',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('APP_NAME');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'Visitor Management',
                'group' => 'app',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('COMPANY_NAME');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => ' ',
                'group' => 'app',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('COMPANY_TELEPHONE');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => ' ',
                'group' => 'app',
                'type' => 'number',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('COMPANY_TELEPHONE');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => ' ',
                'group' => 'app',
                'type' => 'number',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('SITE_ADDRESS');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => ' ',
                'group' => 'app',
                'type' => 'textarea',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('APP_LOGO');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => 'media/1634994815.png',
                'group' => 'app',
                'type' => 'file',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('APP_LOGO_SM');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => 'media/1634994763.png',
                'group' => 'app',
                'type' => 'file',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('APP_KEY');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'base64:OYPpqSn1YsClsk/8dkpJhuB2PIE5T34RzR3d0a19nwM=',
                'group' => 'app',
                'type' => 'textarea',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('LOG_CHANNEL');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'stack',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('LOG_CHANNEL');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'stack',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        if (env('APP_ENV') == 'production') {
            $setting = $this->findSetting('APP_ENV');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'production',
                    'group' => 'app',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('APP_DEBUG');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'false',
                    'group' => 'app',
                    'type' => 'boolean',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_CONNECTION');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'mysql',
                    'group' => 'database',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_HOST');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'localhost',
                    'group' => 'database',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_PORT');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => '3306',
                    'group' => 'database',
                    'type' => 'number',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_DATABASE');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'legal_development',
                    'group' => 'database',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_USERNAME');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'root',
                    'group' => 'database',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_PASSWORD');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => '',
                    'group' => 'database',
                    'type' => 'password',
                    'editable' => false,
                ])->save();
            }
        } elseif (env('APP_ENV') == 'staging') {
            $setting = $this->findSetting('APP_ENV');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'staging',
                    'group' => 'app',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('APP_DEBUG');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'true',
                    'group' => 'app',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_CONNECTION');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'mysql',
                    'group' => 'database',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_HOST');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'lds',
                    'group' => 'database',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_PORT');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => '3306',
                    'group' => 'database',
                    'type' => 'number',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_DATABASE');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'legal_development',
                    'group' => 'database',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_USERNAME');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'root',
                    'group' => 'database',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_PASSWORD');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => '',
                    'group' => 'database',
                    'type' => 'number',
                    'editable' => false,
                ])->save();
            }
        } else {
            $setting = $this->findSetting('APP_ENV');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'local',
                    'group' => 'app',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('APP_DEBUG');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'true',
                    'group' => 'app',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_CONNECTION');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'mysql',
                    'group' => 'database',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_HOST');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => '127.0.0.1',
                    'group' => 'database',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_PORT');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => '3306',
                    'group' => 'database',
                    'type' => 'number',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_DATABASE');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'vms',
                    'group' => 'database',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_USERNAME');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'root',
                    'group' => 'database',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_PASSWORD');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => '',
                    'group' => 'database',
                    'type' => 'password',
                    'editable' => false,
                ])->save();
            }
        }
        $setting = $this->findSetting('BROADCAST_DRIVER');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'log',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('CACHE_DRIVER');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'file',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('QUEUE_CONNECTION');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'sync',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('SESSION_DRIVER');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'file',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('SESSION_LIFETIME');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '120',
                'group' => 'app',
                'type' => 'number',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('REDIS_HOST');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '127.0.0.1',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('REDIS_PASSWORD');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'group' => 'app',
                'type' => 'password',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('REDIS_PORT');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '6379',
                'group' => 'app',
                'type' => 'number',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('MAIL_DRIVER');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'smtp',
                'group' => 'mail',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('MAIL_HOST');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'smtp.gmail.com',
                'group' => 'mail',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('MAIL_PORT');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '587',
                'group' => 'mail',
                'type' => 'number',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('MAIL_USERNAME');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'red.devile2011@gmail.com',
                'group' => 'mail',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('MAIL_PASSWORD');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'kixrddkxdgliadrz',
                'group' => 'mail',
                'type' => 'password',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('MAIL_ENCRYPTION');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'tls',
                'group' => 'mail',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('AWS_ACCESS_KEY_ID');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('AWS_SECRET_ACCESS_KEY');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('AWS_DEFAULT_REGION');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'us-east-1',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('AWS_BUCKET');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('PUSHER_APP_ID');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('PUSHER_APP_KEY');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('PUSHER_APP_SECRET');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('PUSHER_APP_CLUSTER');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'mt1',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('FACEBOOK');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('GOOGLE');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('TWITTER');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('INSTGRAM');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('MIX_PUSHER_APP_KEY');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '${PUSHER_APP_KEY}',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('MIX_PUSHER_APP_CLUSTER');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '${PUSHER_APP_CLUSTER}',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('QUEUE_DRIVER');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'database',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('MAIL_NOTIFY_NEW_USER');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => ' ',
                'group' => 'mail_template',
                'type' => 'textarea',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('MAIL_NOTIFY_CHANGE_PASSWORD');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => ' ',
                'group' => 'mail_template',
                'type' => 'textarea',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('TITLE');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => 'Hawdaj',
                'group' => 'app',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }

        $setting = $this->findSetting('SECTION_TITLE');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => 'Hawdaj',
                'group' => 'main_services',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('SECTION_DESCRIPTION');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => 'text',
                'group' => 'main_services',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('SECTION_NEW_PROJECT');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => '15',
                'group' => 'main_services',
                'type' => 'number',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('SECTION_GUIDE_NUMBER');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => '15',
                'group' => 'main_services',
                'type' => 'number',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('SECTION_PARTNERS');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => '15',
                'group' => 'main_services',
                'type' => 'number',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('SECTION_CONTENT');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => 'هذا النص للتجربه',
                'group' => 'main_services',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('WHAT_WE_DO_TITLE');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => 'ماذا نقدم في هودج',
                'group' => 'what_we_do',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('WHAT_WE_DO_DESCRIPTION');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => 'خدماتنا تتميز بأنها مـــا يتمنـــــــــاه المســــافر',
                'group' => 'what_we_do',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }

        $setting = $this->findSetting('WHAT_WE_DO_CARD_ICON1');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => '
                <svg width="4rem" height="4rem" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 3C0 1.34315 1.34315 0 3 0H61C62.6569 0 64 1.34315 64 3V61C64 62.6569 62.6569 64 61 64H3C1.34315 64 0 62.6569 0 61V3Z" fill="#84548E"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M32.0002 16.0008V46.77C32.0002 46.77 24.0921 43.5605 20.4422 35.8578C16.7923 28.155 17.2493 17.8846 17.2493 17.8846L32.0002 16.0008ZM32.0002 16.0008V46.77C32.0002 46.77 39.9083 43.5605 43.5582 35.8578C47.2081 28.155 46.7511 17.8846 46.7511 17.8846L32.0002 16.0008Z" fill="white"></path>
                    <path d="M25.8463 30.4858L30.3218 34.4621L38.154 27.0775" stroke="black" stroke-width="2.46154" stroke-linecap="round"></path>
                </svg>
                ',
                'group' => 'what_we_do',
                'type' => 'textarea',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('WHAT_WE_DO_CARD_TITLE1');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => 'آمن وموثوق',
                'group' => 'what_we_do',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('WHAT_WE_DO_CARD_DETAILS1');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => 'هذا نص وهمي قابل للتغير بنص بديل ومناسب لهذا الموضوع',
                'group' => 'what_we_do',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }

        $setting = $this->findSetting('WHAT_WE_DO_CARD_ICON2');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => '
                <svg width="4rem" height="4rem" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="64" height="64" rx="3" fill="#84548E"></rect>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12.8344 17.9018C12.5 17.3463 12.3077 16.6957 12.3077 16.0002C12.3077 13.961 13.9608 12.3079 16 12.3079C18.0392 12.3079 19.6923 13.961 19.6923 16.0002C19.6923 16.6957 19.5 17.3463 19.1656 17.9018L16 23.3848L12.8344 17.9018ZM15.9709 17.8463C14.9647 17.8308 14.1539 17.0103 14.1539 16.0004C14.1539 14.9808 14.9804 14.1542 16 14.1542C17.0196 14.1542 17.8462 14.9808 17.8462 16.0004C17.8462 17.0103 17.0354 17.8308 16.0292 17.8463H15.9709Z" fill="white"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M43.6036 44.9787C43.2692 44.4232 43.0769 43.7726 43.0769 43.0771C43.0769 41.0379 44.73 39.3848 46.7692 39.3848C48.8084 39.3848 50.4615 41.0379 50.4615 43.0771C50.4615 43.7726 50.2692 44.4233 49.9348 44.9787L46.7692 50.4617L43.6036 44.9787ZM46.7401 44.9232C45.734 44.9077 44.9231 44.0872 44.9231 43.0773C44.9231 42.0577 45.7497 41.2312 46.7693 41.2312C47.7889 41.2312 48.6154 42.0577 48.6154 43.0773C48.6154 44.0872 47.8046 44.9077 46.7984 44.9232H46.7401Z" fill="white"></path>
                    <path d="M31.6889 16.619C33.8702 16.5545 36.0283 16.6653 37.932 17.0432C38.2654 17.1094 38.5893 16.8928 38.6554 16.5594C38.7216 16.2261 38.505 15.9022 38.1716 15.836C36.1481 15.4343 33.8913 15.3225 31.6525 15.3888C31.3128 15.3988 31.0455 15.6824 31.0556 16.0221C31.0656 16.3618 31.3492 16.6291 31.6889 16.619Z" fill="white"></path>
                    <path d="M25.3942 17.2329C25.7298 17.1791 25.9582 16.8634 25.9044 16.5278C25.8506 16.1922 25.535 15.9638 25.1994 16.0176C24.0436 16.203 22.9671 16.414 22.0167 16.6313L22.2909 17.8311C23.2141 17.6201 24.2641 17.4141 25.3942 17.2329Z" fill="white"></path>
                    <path d="M44.146 18.8549C43.9269 18.5951 43.5387 18.5621 43.2789 18.7813C43.0191 19.0004 42.9861 19.3887 43.2053 19.6484C43.7928 20.3451 44.1825 21.1918 44.3124 22.2306C44.4189 23.0825 44.3143 23.8581 44.0398 24.5769C43.9185 24.8944 44.0776 25.2501 44.3952 25.3714C44.7127 25.4926 45.0683 25.3335 45.1896 25.016C45.536 24.1088 45.6652 23.13 45.5336 22.078C45.3745 20.8044 44.8882 19.7347 44.146 18.8549Z" fill="white"></path>
                    <path d="M41.4579 29.2661C41.7381 29.0737 41.8092 28.6907 41.6168 28.4105C41.4244 28.1303 41.0413 28.0592 40.7612 28.2516C39.4471 29.154 37.8589 29.9653 36.1324 30.7206C35.8211 30.8568 35.6791 31.2197 35.8153 31.531C35.9515 31.8424 36.3144 31.9844 36.6258 31.8482C38.3871 31.0776 40.0538 30.2305 41.4579 29.2661Z" fill="white"></path>
                    <path d="M31.5937 33.8318C31.9126 33.7144 32.0761 33.3607 31.9587 33.0417C31.8414 32.7228 31.4877 32.5593 31.1687 32.6767C30.7285 32.8386 30.2882 32.9993 29.8505 33.159L29.8482 33.1599C28.5607 33.6297 27.2953 34.0914 26.1175 34.5549C25.8013 34.6793 25.6458 35.0366 25.7702 35.3529C25.8947 35.6691 26.252 35.8246 26.5682 35.7002C27.7304 35.2428 28.9747 34.7888 30.2578 34.3206L30.2596 34.3199C30.7003 34.1591 31.1456 33.9966 31.5937 33.8318Z" fill="white"></path>
                    <path d="M21.8684 37.9746C22.1499 37.7843 22.2239 37.4017 22.0335 37.1202C21.8432 36.8386 21.4606 36.7646 21.1791 36.955C20.2567 37.5785 19.5051 38.2907 19.1299 39.1349C18.6208 40.2804 18.5226 41.398 18.8313 42.448C18.9272 42.7741 19.2692 42.9607 19.5953 42.8649C19.9213 42.769 20.108 42.427 20.0121 42.1009C19.7968 41.3684 19.8472 40.5515 20.2546 39.6347C20.4926 39.0994 21.0222 38.5466 21.8684 37.9746Z" fill="white"></path>
                    <path d="M23.8874 45.6325C23.5781 45.4917 23.2132 45.6282 23.0723 45.9375C22.9314 46.2468 23.0679 46.6117 23.3772 46.7526C24.9875 47.486 26.9556 48.0663 29.1896 48.4803C29.5237 48.5422 29.8449 48.3215 29.9068 47.9874C29.9687 47.6532 29.748 47.3321 29.4139 47.2701C27.249 46.8689 25.3826 46.3136 23.8874 45.6325Z" fill="white"></path>
                    <path d="M35.2199 47.9267C34.8805 47.9093 34.5913 48.1704 34.5739 48.5098C34.5565 48.8492 34.8176 49.1385 35.157 49.1558C36.1284 49.2056 37.1288 49.2309 38.1538 49.2309V48.0002C37.149 48.0002 36.1697 47.9753 35.2199 47.9267Z" fill="white"></path>
                </svg>
                ',
                'group' => 'what_we_do',
                'type' => 'textarea',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('WHAT_WE_DO_CARD_TITLE2');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => 'سهولة السفر',
                'group' => 'what_we_do',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('WHAT_WE_DO_CARD_DETAILS2');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => 'هذا نص وهمي قابل للتغير بنص بديل ومناسب لهذا الموضوع',
                'group' => 'what_we_do',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }

        $setting = $this->findSetting('WHAT_WE_DO_CARD_ICON3');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => '
                <svg width="4rem" height="4rem" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="64" height="64" rx="3" fill="#84548E"></rect>
                    <path d="M32.967 13.9452C32.433 13.4029 31.5671 13.4029 31.0331 13.9452L20.6593 24.4806C19.7978 25.3555 20.408 26.8515 21.6263 26.8515H26.53C26.53 29.9196 28.979 32.4068 32 32.4068C35.0211 32.4068 37.4701 29.9196 37.4701 26.8515H42.3738C43.5921 26.8515 44.2023 25.3555 43.3408 24.4806L32.967 13.9452Z" fill="white"></path>
                    <path d="M44.3077 46.3658C44.3077 51.2324 41.5998 50.7293 37.6389 49.9935C35.9496 49.6796 34.0323 49.3234 32 49.3234C29.9677 49.3234 28.0505 49.6796 26.3612 49.9935C22.4003 50.7293 19.6924 51.2324 19.6924 46.3658C19.6924 39.4235 25.2027 33.7956 32 33.7956C38.7974 33.7956 44.3077 39.4235 44.3077 46.3658Z" fill="white"></path>
                </svg>
                ',
                'group' => 'what_we_do',
                'type' => 'textarea',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('WHAT_WE_DO_CARD_TITLE3');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => 'مساعدة مباشرة',
                'group' => 'what_we_do',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('WHAT_WE_DO_CARD_DETAILS3');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => 'هذا نص وهمي قابل للتغير بنص بديل ومناسب لهذا الموضوع',
                'group' => 'what_we_do',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }
    }

    protected function findSetting($key)
    {
        return Setting::firstOrNew(['key' => $key]);
    }
}
