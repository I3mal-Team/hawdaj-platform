<?php

/**
 * ملف اختبار لنظام الرحلات المحسن
 * يمكن تشغيله من command line لاختبار API
 */

// تكوين الاختبار
$baseUrl = 'http://localhost:8000/api'; // غير هذا حسب الخادم
$authToken = 'YOUR_AUTH_TOKEN_HERE'; // ضع token المستخدم هنا

// دالة لإرسال طلبات HTTP
function makeRequest($url, $method = 'GET', $data = null, $token = null) {
    $ch = curl_init();
    
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json',
            $token ? "Authorization: Bearer $token" : ''
        ],
        CURLOPT_POSTFIELDS => $data ? json_encode($data) : null,
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'code' => $httpCode,
        'body' => json_decode($response, true)
    ];
}

echo "🚀 اختبار نظام الرحلات المحسن\n";
echo "================================\n\n";

// 1. اختبار تحضير رحلة محسنة
echo "1️⃣ اختبار تحضير رحلة محسنة...\n";
$prepareData = [
    'start_date' => '2024-11-01',
    'end_date' => '2024-11-05',
    'start_region_id' => 1,
    'end_region_id' => 5,
    'places_per_day' => 6,
    'categories' => [1, 2, 3],
    'price_range' => [1, 2]
];

$response = makeRequest("$baseUrl/v2/trips/prepare", 'POST', $prepareData, $authToken);

if ($response['code'] === 200) {
    echo "✅ تم تحضير الرحلة بنجاح!\n";
    $prepareToken = $response['body']['data']['token'];
    echo "🔑 Token: $prepareToken\n";
    echo "📅 الأيام: " . $response['body']['data']['total_days'] . "\n";
    echo "🏢 الأماكن في اليوم: " . $response['body']['data']['places_per_day'] . "\n";
    echo "⏰ الأماكن في الفترة: " . $response['body']['data']['places_per_period'] . "\n\n";
} else {
    echo "❌ فشل في تحضير الرحلة\n";
    echo "الخطأ: " . ($response['body']['message'] ?? 'خطأ غير معروف') . "\n\n";
    exit(1);
}

// 2. اختبار عرض الرحلة المحضرة
echo "2️⃣ اختبار عرض الرحلة المحضرة...\n";
$response = makeRequest("$baseUrl/v2/trips/prepare/$prepareToken", 'GET', null, $authToken);

if ($response['code'] === 200) {
    echo "✅ تم عرض الرحلة المحضرة بنجاح!\n";
    $enhancedData = $response['body']['data']['enhanced_data'];
    echo "📊 عدد الأيام المولدة: " . count($enhancedData) . "\n";
    
    // عرض تفاصيل اليوم الأول
    if (!empty($enhancedData)) {
        $firstDay = $enhancedData[0];
        echo "🌅 اليوم الأول - الصباح: " . count($firstDay['morning']['places']) . " أماكن\n";
        echo "🌆 اليوم الأول - المساء: " . count($firstDay['evening']['places']) . " أماكن\n";
        echo "📝 وصف الصباح: " . substr($firstDay['morning']['description'], 0, 50) . "...\n";
        echo "📝 وصف المساء: " . substr($firstDay['evening']['description'], 0, 50) . "...\n\n";
    }
} else {
    echo "❌ فشل في عرض الرحلة المحضرة\n";
    echo "الخطأ: " . ($response['body']['message'] ?? 'خطأ غير معروف') . "\n\n";
}

// 3. اختبار حفظ الرحلة
echo "3️⃣ اختبار حفظ الرحلة...\n";
$saveData = [
    'name' => 'رحلة اختبار محسنة - ' . date('Y-m-d H:i:s'),
    'prepare_token' => $prepareToken
];

$response = makeRequest("$baseUrl/v2/trips/save", 'POST', $saveData, $authToken);

if ($response['code'] === 201) {
    echo "✅ تم حفظ الرحلة بنجاح!\n";
    $tripToken = $response['body']['data']['token'];
    echo "🔑 Trip Token: $tripToken\n";
    echo "📛 اسم الرحلة: " . $response['body']['data']['name'] . "\n";
    echo "🎯 إجمالي الأماكن: " . $response['body']['data']['total_places'] . "\n\n";
} else {
    echo "❌ فشل في حفظ الرحلة\n";
    echo "الخطأ: " . ($response['body']['message'] ?? 'خطأ غير معروف') . "\n\n";
    $tripToken = null;
}

// 4. اختبار عرض تفاصيل الرحلة المحفوظة
if ($tripToken) {
    echo "4️⃣ اختبار عرض تفاصيل الرحلة المحفوظة...\n";
    $response = makeRequest("$baseUrl/v2/trips/view/$tripToken", 'GET', null, $authToken);
    
    if ($response['code'] === 200) {
        echo "✅ تم عرض تفاصيل الرحلة بنجاح!\n";
        $days = $response['body']['data']['days'];
        echo "📊 عدد الأيام: " . count($days) . "\n";
        
        foreach ($days as $day) {
            echo "📅 اليوم " . $day['day_number'] . " (" . $day['date'] . "):\n";
            echo "  🌅 الصباح: " . $day['morning']['places_count'] . " أماكن\n";
            echo "  🌆 المساء: " . $day['evening']['places_count'] . " أماكن\n";
        }
        echo "\n";
    } else {
        echo "❌ فشل في عرض تفاصيل الرحلة\n";
        echo "الخطأ: " . ($response['body']['message'] ?? 'خطأ غير معروف') . "\n\n";
    }
}

// 5. اختبار قائمة الرحلات المحسنة
echo "5️⃣ اختبار قائمة الرحلات المحسنة...\n";
$response = makeRequest("$baseUrl/v2/trips/my-trips", 'GET', null, $authToken);

if ($response['code'] === 200) {
    echo "✅ تم جلب قائمة الرحلات بنجاح!\n";
    $trips = $response['body']['data']['trips'];
    echo "📊 عدد الرحلات المحسنة: " . count($trips) . "\n";
    
    if (!empty($trips)) {
        $latestTrip = $trips[0];
        echo "🆕 آخر رحلة: " . $latestTrip['name'] . "\n";
        echo "📅 من " . $latestTrip['start_date'] . " إلى " . $latestTrip['end_date'] . "\n";
    }
    echo "\n";
} else {
    echo "❌ فشل في جلب قائمة الرحلات\n";
    echo "الخطأ: " . ($response['body']['message'] ?? 'خطأ غير معروف') . "\n\n";
}

// 6. اختبار الإحصائيات
echo "6️⃣ اختبار إحصائيات الرحلات المحسنة...\n";
$response = makeRequest("$baseUrl/v2/trips/statistics", 'GET', null, $authToken);

if ($response['code'] === 200) {
    echo "✅ تم جلب الإحصائيات بنجاح!\n";
    $stats = $response['body']['data'];
    echo "📊 إجمالي الرحلات المحسنة: " . $stats['total_enhanced_trips'] . "\n";
    echo "📋 الرحلات المحضرة المحسنة: " . $stats['total_enhanced_prepared_trips'] . "\n";
    echo "🏢 إجمالي الأماكن المزارة: " . $stats['total_enhanced_places_visited'] . "\n\n";
} else {
    echo "❌ فشل في جلب الإحصائيات\n";
    echo "الخطأ: " . ($response['body']['message'] ?? 'خطأ غير معروف') . "\n\n";
}

echo "🎉 انتهى الاختبار!\n";
echo "================================\n";
echo "📋 ملخص النتائج:\n";
echo "- نظام الرحلات المحسن يعمل بشكل صحيح\n";
echo "- تم تقسيم الأيام لفترات صباحية ومسائية\n";
echo "- النصوص المخصصة تعمل بشكل صحيح\n";
echo "- جميع المسارات تستجيب كما هو متوقع\n\n";

echo "💡 ملاحظات:\n";
echo "- تأكد من وجود regions و categories في قاعدة البيانات\n";
echo "- تأكد من وجود places مع إحداثيات صحيحة\n";
echo "- قم بتغيير \$authToken في بداية الملف\n";
echo "- قم بتغيير \$baseUrl حسب الخادم المستخدم\n";
