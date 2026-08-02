<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Enhanced Trip System Configuration
    |--------------------------------------------------------------------------
    |
    | تكوين نظام الرحلات المحسن
    |
    */

    // إعدادات عامة
    'enabled' => env('ENHANCED_TRIP_ENABLED', true),
    
    // إعدادات الأماكن
    'places' => [
        'max_per_day' => env('ENHANCED_TRIP_MAX_PLACES_PER_DAY', 20),
        'min_per_day' => env('ENHANCED_TRIP_MIN_PLACES_PER_DAY', 2),
        'search_radius_km' => env('ENHANCED_TRIP_SEARCH_RADIUS', 50),
        'max_total_places' => env('ENHANCED_TRIP_MAX_TOTAL_PLACES', 200),
    ],

    // إعدادات الفترات
    'periods' => [
        'morning' => [
            'name' => 'الفترة الصباحية',
            'name_en' => 'Morning Period',
            'icon' => '🌅',
        ],
        'evening' => [
            'name' => 'الفترة المسائية', 
            'name_en' => 'Evening Period',
            'icon' => '🌆',
        ],
    ],

    // النصوص المحفوظة للفترات
    'period_descriptions' => [
        'morning' => [
            'default' => 'استمتع بجولة صباحية رائعة في هذه الأماكن المميزة',
            'cultural' => 'ابدأ يومك بزيارة المعالم الثقافية والتاريخية الرائعة',
            'nature' => 'استمتع بجمال الطبيعة الخلابة في الصباح الباكر',
            'shopping' => 'تسوق في أفضل الأسواق والمراكز التجارية في الصباح',
            'entertainment' => 'استمتع بالأنشطة الترفيهية الصباحية المثيرة',
            'religious' => 'ابدأ يومك بزيارة المساجد والأماكن الدينية المقدسة',
            'historical' => 'اكتشف التاريخ العريق في هذه المعالم التاريخية',
            'food' => 'تذوق أشهى الأطباق في أفضل المطاعم الصباحية',
        ],
        'evening' => [
            'default' => 'اختتم يومك بزيارة هذه الأماكن الساحرة في المساء',
            'cultural' => 'استكشف التراث والثقافة في أجواء مسائية رائعة',
            'nature' => 'استمتع بغروب الشمس في أجمل المناظر الطبيعية',
            'shopping' => 'تسوق في الأسواق الليلية والمراكز التجارية المسائية',
            'entertainment' => 'استمتع بالحياة الليلية والأنشطة المسائية الممتعة',
            'religious' => 'اختتم يومك بزيارة المساجد في أوقات الصلاة المسائية',
            'historical' => 'استكشف المعالم التاريخية في إضاءة المساء الساحرة',
            'food' => 'استمتع بالعشاء في أفضل المطاعم والمقاهي المسائية',
        ],
    ],

    // إعدادات المواسم
    'seasons' => [
        'spring' => [
            'name' => 'موسم الربيع',
            'months' => [3, 4, 5],
            'description' => 'استمتع بجمال الطبيعة في فصل الربيع',
        ],
        'summer' => [
            'name' => 'موسم الصيف',
            'months' => [6, 7, 8],
            'description' => 'استمتع بالأنشطة الصيفية الرائعة',
        ],
        'autumn' => [
            'name' => 'موسم الخريف',
            'months' => [9, 10, 11],
            'description' => 'استمتع بأجواء الخريف المعتدلة',
        ],
        'winter' => [
            'name' => 'موسم الشتاء',
            'months' => [12, 1, 2],
            'description' => 'استمتع بالأجواء الشتوية الجميلة',
        ],
        'all_year' => [
            'name' => 'طوال العام',
            'description' => 'مناسب لجميع فصول السنة',
        ],
    ],

    // إعدادات Google Maps API
    'google_maps' => [
        'api_key' => env('GOOGLE_MAPS_API_KEY', 'AIzaSyDDSTB8emANyFuYPdfvIGCq_3e0ZZ6lKJc'),
        'timeout' => env('GOOGLE_MAPS_TIMEOUT', 30),
        'max_waypoints' => env('GOOGLE_MAPS_MAX_WAYPOINTS', 25),
    ],

    // إعدادات التخزين المؤقت
    'cache' => [
        'enabled' => env('ENHANCED_TRIP_CACHE_ENABLED', true),
        'ttl' => env('ENHANCED_TRIP_CACHE_TTL', 3600), // ساعة واحدة
        'prefix' => 'enhanced_trip_',
    ],

    // إعدادات التسجيل
    'logging' => [
        'enabled' => env('ENHANCED_TRIP_LOGGING_ENABLED', true),
        'level' => env('ENHANCED_TRIP_LOG_LEVEL', 'info'),
        'channel' => env('ENHANCED_TRIP_LOG_CHANNEL', 'daily'),
    ],

    // إعدادات التحقق من صحة البيانات
    'validation' => [
        'max_trip_duration_days' => env('ENHANCED_TRIP_MAX_DURATION', 30),
        'min_trip_duration_days' => env('ENHANCED_TRIP_MIN_DURATION', 1),
        'max_categories' => env('ENHANCED_TRIP_MAX_CATEGORIES', 10),
        'max_price_ranges' => env('ENHANCED_TRIP_MAX_PRICE_RANGES', 5),
    ],

    // إعدادات الأداء
    'performance' => [
        'chunk_size' => env('ENHANCED_TRIP_CHUNK_SIZE', 100),
        'max_concurrent_requests' => env('ENHANCED_TRIP_MAX_CONCURRENT', 5),
        'timeout' => env('ENHANCED_TRIP_TIMEOUT', 60),
    ],

    // إعدادات التجريب والتطوير
    'debug' => [
        'enabled' => env('ENHANCED_TRIP_DEBUG', false),
        'show_sql_queries' => env('ENHANCED_TRIP_DEBUG_SQL', false),
        'mock_google_api' => env('ENHANCED_TRIP_MOCK_GOOGLE', false),
    ],
];
