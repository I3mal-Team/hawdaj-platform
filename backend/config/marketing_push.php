<?php

return [

    /*
    |--------------------------------------------------------------------------
    | تفعيل إشعارات المحتوى الجديد (FCM)
    |--------------------------------------------------------------------------
    */
    'enabled' => env('MARKETING_PUSH_ENABLED', true),

    /*
    | لا ترسل عند إنشاء السجلات من الـ CLI (مثلاً seeders) إلا إذا فعلت ذلك صراحةً
    */
    'allow_console_model_events' => env('MARKETING_PUSH_ALLOW_CONSOLE', false),

    /*
    | اسم طابور الـ Job (اختياري)
    */
    'queue' => env('MARKETING_PUSH_QUEUE', null),

    /*
    | حجم الدفعة عند إرسال الإشعارات
    */
    'user_chunk_size' => (int) env('MARKETING_PUSH_CHUNK', 150),
];
