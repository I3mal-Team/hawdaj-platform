<?php

$message = 'Hello [[name]] <br> Your Email [[email]]<br> Your Password [[password]]';
$general = 'Hello User <br> [[body]]';

return [
    'name' => 'settings',
    'setting' => [
        'web_information' => [
            'company_name' => 'Saudi Ceramics',
            'website_name' => 'Saudi Ceramics',
            'website_url' => '',
            'copyright' => 'Saudi Ceramics',
            'site_description' => 'Saudi Ceramics Description',
            'meta_keyword' => 'Saudi Ceramics',
        ],
        'web_properties' => [
            'website_logo_large' => '',
            'website_logo_small' => '',
            'website_fav_icon' => '',
        ],
        'web_config' => [
            'mail_host' => '',
            'mail_port' => '',
            'mail_username' => '',
            'mail_password' => '',
            'mail_encryption' => '',
        ],
        'mail_template' => [
            'MAIL_NOTIFY_NEW_USER' => $message,
            'MAIL_NOTIFY_UPDATE_USER' => $message,
            'MAIL_NOTIFY_MODELS' => $general,
            'MAIL_NOTIFY_GENERAL' => $general,
        ],
        'notification' => [
            'notification_users' => 1,
            'notification_models' => 1
        ]
    ]

];


