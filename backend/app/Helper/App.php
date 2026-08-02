<?php

use App\Models\Favorite;
use App\Models\MostVisit;
use App\Models\Place;
use App\Models\Save;
use App\Models\User;
use App\Models\VisitSite;
use BaconQrCode\Encoder\QrCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Nwidart\Modules\Facades\Module;

if (!function_exists('visit_item')) {
    function visit_item()
    {
        return;
        $ip = request()->ip();
        $data = ip_info($ip);
        if ($data) {
            $data['ip'] = $ip;
            MostVisit::firstOrCreate($data);
        }
    }
}

if (!function_exists('ip_info')) {
    function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE)
    {
        $output = NULL;
        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }
        $purpose = str_replace(array("name", "\n", "\t", " ", "-", "_"), [], strtolower(trim($purpose)));
        $support = array("country", "countrycode", "state", "region", "city", "location", "address");
        $continents = array(
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America"
        );
        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch ($purpose) {
                    case "location":
                        $output = array(
                            "city" => @$ipdat->geoplugin_city,
                            "state" => @$ipdat->geoplugin_regionName,
                            "country" => @$ipdat->geoplugin_countryName,
                            "country_code" => @$ipdat->geoplugin_countryCode,
                            "continent" => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            "continent_code" => @$ipdat->geoplugin_continentCode,
                            "latitude" => @$ipdat->geoplugin_latitude,
                            "longitude" => @$ipdat->geoplugin_longitude
                        );
                        break;
                    case "address":
                        $address = array($ipdat->geoplugin_countryName);
                        if (@strlen($ipdat->geoplugin_regionName) >= 1)
                            $address[] = $ipdat->geoplugin_regionName;
                        if (@strlen($ipdat->geoplugin_city) >= 1)
                            $address[] = $ipdat->geoplugin_city;
                        $output = implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case "state":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "region":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "country":
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case "countrycode":
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                    case "latitude":
                        $output = @$ipdat->geoplugin_latitude;
                        break;
                    case "longitude":
                        $output = @$ipdat->geoplugin_longitude;
                        break;
                }
            }
        }
        return $output;
    }
}

if (!function_exists('distance')) {
    function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        //   echo distance(32.9697, -96.80322, 29.46786, -98.53506, "M") . " Miles<br>";
        //   echo distance(32.9697, -96.80322, 29.46786, -98.53506, "K") . " Kilometers<br>";
        //   echo distance(32.9697, -96.80322, 29.46786, -98.53506, "N") . " Nautical Miles<br>";
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
    }
}

if (!function_exists('v_image')) {
    function v_image($ext = null)
    {
        return ($ext === null) ? 'mimes:jpg,png,jpeg,png,gif,bmp' : 'mimes:' . $ext;
    }
}

if (!function_exists('locales')) {
    function locales($exclude = "")
    {
        return array_filter(["ar", "en", "ru", "zh"], function ($locale) use ($exclude) {
            return $locale !== $exclude;
        });
    }
}

if (!function_exists('default_lang')) {
    function default_lang()
    {
        return 'ar';
    }
}

if (!function_exists('v_translations')) {
    function v_translations($attributes)
    {
        $validations = [];
        $required = ['name', 'title'];
        foreach (locales() as $locale) {
            foreach ($attributes as $attribute) {
                $validations[$locale . "." . $attribute] = in_array($attribute, $required) ? "required" : "";
            }
        }

        return $validations;
    }
}

if (!function_exists('visit')) {
    function visit($data)
    {
        $ins = VisitSite::updateOrCreate(
            [
                'ip' => $data['ip'],
                'page' => $data['page'],
            ],
            $data
        );
        if ($ins) {
            return true;
        } else {
            return false;
        }
    }
}
if (!function_exists('genrateEmailLink')) {
    function genrateEmailLink($email = null)
    {
        return '<p><a href="mailto:' . $email . '">' . $email . '</a>';
    }
}

if (!function_exists('genrateQrCode')) {
    function genrateQrCode($request_id = null)
    {
        $base = '';
        if ($request_id) {
            $image = $request_id . time() . '.svg';

            $qr_image = QrCode::size(50)
                ->format('svg')
                ->generate($request_id, $image);
            $base = svgToBase64($image);
            unlink($image);
        }
        return '<img src="' . $base . '"/>';
    }
}

if (!function_exists('genrateCancelButton')) {
    function genrateCancelButton($material_request_id = null)
    {
        $link = '';
        if ($material_request_id) {
            $link = '<a class="btn btn-sm btn-danger" target="_blank" href="' . route('dashboard.meeting-cancel', $material_request_id) . '">';
            $link .= __('dashboard.cancel_meeting');
            $link .= '</a>';
        }
        return $link;
    }
}

if (!function_exists('genrateConformBtn')) {
    function genrateConformBtn($link = null)
    {
        return '<a href="' . $link . '" target="_blank" class="btn btn-sm btn-primary">' . __('dashboard.tabConf') . '</a>';
    }
}

if (!function_exists('dateFormat')) {
    function dateFormat($date): string
    {
        return !is_numeric($date)
            ? Jenssegers\Date\Date::parse($date)->format('j F Y')
            : '----';
    }
}

if (!function_exists('timeFormat')) {
    function timeFormat($time): string
    {
        return Jenssegers\Date\Date::parse($time)->format('H:i A');
    }
}

if (!function_exists('resolveLang')) {
    function resolveLang($name)
    {
        if (is_array($name)) {
            $result = $name[app()->getLocale()];
            if (!$result) {
                $result = $name[(app()->getLocale() != 'ar') ? 'ar' : 'en'];
            }

            return $result;
        }

        return $name;
    }
}

if (!function_exists('getFileDir')) {
    function getFileDir()
    {
        return (app()->getLocale() != 'ar') ? '' : 'rtl.';
    }
}

if (!function_exists('userRoot')) {
    function userRoot()
    {
        return User::find(1);
    }
}

if (!function_exists('isRoot')) {
    function isRoot()
    {
        return auth()->id() == 1 || auth()->user()->hasRole('root');
    }
}

if (!function_exists('activeMenu')) {

    function activeMenu($index, ...$name): string
    {

        foreach ($name as $item) {
            if (request()->segment($index) == $item) {
                return 'menu-item-active';
            }
        }

        return "";
    }
}

if (!function_exists('errorMessage')) {
    function errorMessage($message = null)
    {
        $message = trans('dashboard.something_error') . '' . (env('APP_DEBUG') ? " : $message" : '');

        return request()->expectsJson()
            ? response()->json(['message' => $message], 400)
            : redirect()->back()->with(['status' => 'error', 'message' => $message]);
    }
}

if (!function_exists('successMessage')) {
    function successMessage($message = 'success')
    {
        return request()->expectsJson()
            ? response()->json(['message' => $message])
            : redirect()->back()->with(['status' => 'success', 'message' => $message]);
    }
}

if (!function_exists('utf8_strrev')) {
    function utf8_strrev($str = null)
    {
        if ($str) {
            preg_match_all('/./us', $str, $ar);
            return join('', array_reverse($ar[0]));
        }
        return null;
    }
}

function svgToBase64($filepath)
{
    if (file_exists($filepath)) {

        $filetype = pathinfo($filepath, PATHINFO_EXTENSION);

        if ($filetype === 'svg') {
            $filetype .= '+xml';
        }

        $get_img = file_get_contents($filepath);
        return 'data:image/' . $filetype . ';base64,' . base64_encode($get_img);
    }
}

if (!function_exists('resolveDateTime')) {
    function resolveDateTime($date, $time)
    {
        try {
            if (is_null($date)) {
                $date = carbon()->now()->toDateString();
            }

            $date = new DateTime($date . ' ' . $time);
        } catch (Exception $ex) {
            $time = date('H:i', mktime(0, 0, $time));
            $date = new DateTime($date . ' ' . $time);
        }

        $new_time = $date->format('Y-m-d H:i');

        return Carbon::parse($new_time);
    }
}

if (!function_exists('diffInMinutesHelper')) {
    function diffInMinutesHelper($start_time, $end_time)
    {
        $interval = $start_time->diff($end_time);
        $hours = $interval->format('%h');
        $minutes = $interval->format('%i');
        return $hours * 60 + $minutes;
    }
}

if (!function_exists('dateFormat')) {
    function dateFormat($date): string
    {
        return !is_numeric($date)
            ? Jenssegers\Date\Date::parse($date)->format('j F Y')
            : '----';
    }
}

if (!function_exists('timeFormat')) {
    function timeFormat($time): string
    {
        return Jenssegers\Date\Date::parse($time)->format('h:i a');
    }
}

if (!function_exists('resolveLang')) {
    function resolveLang($name)
    {
        if (is_array($name)) {
            $result = $name[getLang()];
            if (!$result) {
                $result = $name[(getLang() === 'en') ? 'ar' : 'en'];
            }

            return $result;
        }
        return $name;
    }
}

if (!function_exists('getFileDir')) {
    function getFileDir()
    {
        return (getLang() === 'en') ? '' : 'rtl.';
    }
}

if (!function_exists('unKnownError')) {
    function unKnownError($message = null)
    {
        if (!$message)
            $message = trans('dashboard.something_error') . '' . (env('APP_DEBUG') ? " : $message" : '');

        return request()->expectsJson()
            ? response()->json(['message' => $message], 400)
            : redirect()->back()->with(['status' => 'error', 'message' => $message]);
    }
}

if (!function_exists('resolvePhoto')) {
    function resolvePhoto($image = null, $type = 'none')
    {
        $result = ($type === 'user'
            ? asset('dashboard_assets/media/users/default.jpg')
            : asset('dashboard_assets/media/blank.png'));
        if (is_null($image)) {
            return $result;
        }

        if (Str::startsWith($image, 'http')) {
            return $image;
        }

        return url($image);
    }
}

if (!function_exists('userRoot')) {
    function userRoot()
    {
        return User::find(1);
    }
}

if (!function_exists('getLocaleName')) {
    function getLocaleName(): string
    {
        return [
            "ar" => 'العربية',
            "en" => 'English',
            "ru" => 'Русский',
            "zh" => '简体中文',
        ][app()->getLocale()];
    }
}

if (!function_exists('getLang')) {
    function getLang()
    {
        return app()->getLocale();
    }
}

if (!function_exists('primaryID')) {
    function primaryID($id = null)
    {
        $user = $id ? User::find($id) : auth()->user();
        if (!empty($user)) {
            return $user->parent_id ?? $user->id;
        }
    }
}

if (!function_exists('Primary')) {
    function Primary()
    {
        return auth()->user()->parent_id ? User::find(auth()->user()->parent_id) : auth()->user();
    }
}

if (!function_exists('isRoot')) {
    function isRoot(): bool
    {
        return auth()->id() == 1 && is_null(auth()->user()->parent_id);
    }
}

if (!function_exists('getPermissions')) {

    function getPermissions($user)
    {
        return $user->roles->map(function ($role) {
            return $role->permissions;
        })->collapse();
    }
}

if (!function_exists('active')) {

    function active(...$items): string
    {
        $className = '';

        foreach ($items as $item) {
            if (request()->is("*/$item") || request()->is("*/$item/*")) {
                $className = 'menu-item-active';
                break;
            }
        }
        return $className;
    }
}

if (!function_exists('inputError')) {

    function inputError($name)
    {
        if (session('errors')) {

            return session('errors')->has($name) ? 'is-invalid' : '';
        }
    }
}

if (!function_exists('msgError')) {

    function msgError($name)
    {
        if (session('errors')) {

            return session('errors')->has($name) ? session('errors')->first($name) : '';
        }
    }
}

if (!function_exists('carbon')) {

    function carbon(): \Illuminate\Support\Carbon
    {
        return new \Illuminate\Support\Carbon;
    }
}

if (!function_exists('is_base64')) {
    function is_base64($s): bool
    {
        return (bool)preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s);
    }
}

if (!function_exists('shortModelName')) {
    function shortModelName($key)
    {
        $arr = [
            'SpeedModel' => 'forkliftspeed',
            'PpesModel' => 'safety',
            'BubblesModel' => 'bubbles',
        ];

        return $arr[$key] ?? $key;
    }
}

if (!function_exists('updateDotEnv')) {
    function updateDotEnv($key, $newValue, $delim = '')
    {

        $path = base_path('.env');
        // get old value from current env
        $oldValue = env($key);

        // was there any change?
        if ($oldValue === $newValue) {
            return;
        }

        // rewrite file content with changed data
        if (file_exists($path)) {
            // replace current value with new value
            file_put_contents(
                $path,
                str_replace(
                    $key . '=' . $delim . $oldValue . $delim,
                    $key . '=' . $delim . $newValue . $delim,
                    file_get_contents($path)
                )
            );
        }
    }
}

if (!function_exists('isModuleEnabled')) {
    function isModuleEnabled($moduleName)
    {

        $enabledModules = Module::allEnabled();
        foreach ($enabledModules as $module) {
            if (strtolower($module) === strtolower($moduleName)) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('writeTranslation')) {
    function writeTranslation($line, $lang = 'en')
    {
        $content = file(lang_path("$lang/dashboard.php"));
        $lines = array();

        $string = file_get_contents(lang_path("$lang/dashboard.php"));

        if (strpos($string, $line) !== false) {
            return true;
        }

        foreach ($content as $row) {
            $lines[] = $row;
            if (strpos($row, "return [") !== false || strpos($row, "return array(") !== false) {
                $lines[] = $line;
            }
        }

        file_put_contents(lang_path("$lang/dashboard.php"), $lines);

        return true;
    }
}

if (!function_exists('handleTrans')) {
    function handleTrans($trans, $return = null)
    {
        $key = Str::snake($trans);

        if ($return == null) {
            $return = $trans;
        }
        return Str::startsWith(__("dashboard.$key"), 'dashboard.') ? $return : __("dashboard.$key");
    }
}

if (!function_exists('sync_places')) {

    function sync_places()
    {
        $data = Http::get('https://hawdaj7.com/api/v1/topics/12/page/1/count/10');

        $topics = $data ? json_decode($data, true)['topics'] : [];

        $data = collect($topics)->map(function ($item) {
            $image = explode('/', $item['photo_file']);
            return [
                'title' => $item['title'],
                'description' => $item['details'],
                'views_num' => $item['visits'],
                'city_id' => $item['fields'][1]['value'],
                'region_id' => $item['fields'][0]['value'],
                'seasons' => $item['fields'][2]['value'],
                'price_id' => $item['fields'][4]['value'],
                'categories' => collect($item['Joined_categories'])->map(function ($i) {
                    return $i['id'];
                }),
                'image' => $image ? end($image) : null
            ];
        });

        return Place::insert($data->toArray());
    }
}

if (!function_exists('isFavorite')) {


    function isFavorite(int $id, string $type): bool
    {
        if (! auth('api')->check()) {
            return false;
        }

        $user_id = auth('api')->user()->id;

        $isFavorite = Favorite::where('user_id', $user_id)->where('favoritable_type', $type)
            ->where('favoritable_id', $id)->first();

        if ($isFavorite) {
            return true;
        }

        return false;
    }
}

if (!function_exists('isSaved')) {

    function isSaved(int $id, string $type): bool
    {
        if (! auth('api')->check()) {
            return false;
        }

        $user_id = auth('api')->user()->id;

        $isSaved = Save::where('user_id', $user_id)->where('saved_type', $type)
            ->where('saved_id', $id)->first();

        if ($isSaved) {
            return true;
        }

        return false;
    }
}

if (!function_exists('removeUploadAndAddSmall')) {

    function removeUploadAndAddSmall(string $path): string
    {
        if (strpos($path, 'front_assets') !== false) {
            return $path;
        }
//        if (strpos($path, 'uploads/') !== false) {
            $path = str_replace('uploads/', '', $path);
//            $path = 'uploads/small-' . $path;
            return 'uploads/small-' . $path;
//        }
//
//        return $path;
    }
}

if (!function_exists('removeUploadAndAddMedium')) {

    function removeUploadAndAddMedium(string $path): string
    {
        if (strpos($path, 'front_assets') !== false) {
            return $path;
        }

        $path = str_replace('uploads/', '', $path);
        return 'uploads/medium-' . $path;
    }
}

if (!function_exists('getAppUrl')) {
    function getAppUrl(): string
    {
        $request = request();
        if ($request && method_exists($request, 'getSchemeAndHttpHost')) {
            $url = $request->getSchemeAndHttpHost();
            if ($url) {
                return rtrim($url, '/');
            }
        }
        return rtrim(config('app.url', 'https://dashboard.hawdaj.net'), '/');
    }
}

if (!function_exists('getImageUrl')) {
    function getImageUrl(?string $path, ?string $size = null): ?string
    {
        if (!$path) {
            return null;
        }
        
        // Remove 'uploads/' prefix if exists
        $path = str_replace('uploads/', '', $path);
        
        // Remove 'front_assets/' prefix if exists (default images)
        if (strpos($path, 'front_assets/') === 0) {
            return getAppUrl() . '/' . $path;
        }
        
        // Keep original extension for old images (don't convert to .webp)
        // The path already has the correct extension from the database
        
        // Add size prefix if needed
        if ($size === 'small') {
            $path = 'small-' . $path;
        } elseif ($size === 'medium') {
            $path = 'medium-' . $path;
        }
        
        return getAppUrl() . '/uploads/' . $path;
    }
}

if (!function_exists('getMediaUrl')) {
    function getMediaUrl($model, string $collectionName, string $conversionName = ''): ?string
    {
        if (!$model || !method_exists($model, 'getFirstMediaUrl')) {
            return null;
        }

        $url = $model->getFirstMediaUrl($collectionName, $conversionName);
        
        if (!$url && method_exists($model, 'getOriginal')) {
            $oldImage = $model->getOriginal('image');
            if ($oldImage) {
                $path = $oldImage;
                $path = str_replace('uploads/', '', $path);
                $path = ltrim($path, '/');
                
                if ($conversionName) {
                    $pathInfo = pathinfo($path);
                    $filename = $pathInfo['filename'];
                    $extension = $pathInfo['extension'] ?? 'jpg';
                    
                    if (in_array(strtolower($extension), ['png', 'gif', 'webp'])) {
                        $extension = 'jpg';
                    }
                    
                    $path = $pathInfo['dirname'] . '/conversions/' . $filename . '-' . $conversionName . '.' . $extension;
                }
                
                $baseUrl = getAppUrl();
                return $baseUrl . '/storage/' . $path;
            }
        }
        
        if (!$url) {
            return null;
        }

        // Handle malformed absolute URLs like "https:/domain/path"
        if (Str::startsWith($url, 'https:/') && !Str::startsWith($url, 'https://')) {
            $url = preg_replace('#^https:/+#i', 'https://', $url);
        } elseif (Str::startsWith($url, 'http:/') && !Str::startsWith($url, 'http://')) {
            $url = preg_replace('#^http:/+#i', 'http://', $url);
        }
        
        // Treat any http/https URL as absolute (FILTER_VALIDATE_URL may fail with non-ASCII filenames)
        if (Str::startsWith($url, ['http://', 'https://'])) {
            return $url;
        }
        
        $baseUrl = getAppUrl();
        $url = ltrim($url, '/');
        
        return $baseUrl . '/' . $url;
    }
}

if (!function_exists('detectMimeType')) {

    function detectMimeType($mime_type) {
        if (str_starts_with($mime_type, 'image/')) {
            return 'image';
        } elseif (str_starts_with($mime_type, 'video/')) {
            return 'video';
        } elseif ($mime_type == 'application/pdf') {
            return 'pdf';
        } elseif (in_array($mime_type, ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])) {
            return 'word';
        } elseif (in_array($mime_type, ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])) {
            return 'excel';
        } else {
            return 'image'; // fallback to image
        }
    }
}

if (!function_exists('getLocationCoords')) {
    /**
     * Get location coordinates (lat, lng) for distance sorting
     * Priority: 1. Request parameters 2. User's saved location (from cache or database) 3. Guest IP location (from cache)
     *
     * @param float|null $requestLat
     * @param float|null $requestLng
     * @return array{lat: float|null, lng: float|null, hasLocation: bool}
     */
    function getLocationCoords($requestLat = null, $requestLng = null): array
    {
        $lat = $requestLat ?? request('lat');
        $lng = $requestLng ?? request('lng');

        // If location is provided in request, use it (highest priority)
        if ($lat && $lng) {
            return [
                'lat' => (float) $lat,
                'lng' => (float) $lng,
                'hasLocation' => true
            ];
        }

        // Try to get user's last saved location (from cache first, then database)
        if (auth('api')->check()) {
            $user = auth('api')->user();
            if ($user) {
                $cacheKey = "user_location_user_{$user->id}";
                $cacheTTL = 5 * 60; // 5 minutes
                
                // Try to get from cache first (much faster)
                $cachedLocation = \Illuminate\Support\Facades\Cache::get($cacheKey);
                if ($cachedLocation && isset($cachedLocation['lat'], $cachedLocation['lng'])) {
                    return [
                        'lat' => (float) $cachedLocation['lat'],
                        'lng' => (float) $cachedLocation['lng'],
                        'hasLocation' => true
                    ];
                }
                
                // If not in cache, get from database
                $userLocation = \App\Models\UserLocation::where('user_id', $user->id)->first();
                if ($userLocation && $userLocation->lat && $userLocation->lng) {
                    // Update cache for next time
                    \Illuminate\Support\Facades\Cache::put($cacheKey, [
                        'lat' => (float) $userLocation->lat,
                        'lng' => (float) $userLocation->lng,
                        'updated_at' => $userLocation->updated_at->toIso8601String(),
                    ], $cacheTTL);
                    
                    return [
                        'lat' => (float) $userLocation->lat,
                        'lng' => (float) $userLocation->lng,
                        'hasLocation' => true
                    ];
                }
            }
        } else {
            // For guests: try to get location from cache using IP
            $userIp = request()->ip() ?? request()->header('X-Forwarded-For') ?? 'unknown';
            $ipHash = md5($userIp);
            $cacheKey = "user_location_ip_{$ipHash}";
            
            $cachedLocation = \Illuminate\Support\Facades\Cache::get($cacheKey);
            if ($cachedLocation && isset($cachedLocation['lat'], $cachedLocation['lng'])) {
                return [
                    'lat' => (float) $cachedLocation['lat'],
                    'lng' => (float) $cachedLocation['lng'],
                    'hasLocation' => true
                ];
            }
        }

        return [
            'lat' => null,
            'lng' => null,
            'hasLocation' => false
        ];
    }
}

if (!function_exists('applyDistanceSorting')) {
    /**
     * Apply distance calculation and sorting to a query builder
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param float $lat
     * @param float $lng
     * @param string $latColumn Column name for latitude (default: 'lat')
     * @param string $lngColumn Column name for longitude (default: 'long')
     * @return \Illuminate\Database\Eloquent\Builder
     */
    function applyDistanceSorting($query, float $lat, float $lng, string $latColumn = 'lat', string $lngColumn = 'long')
    {
        $tableName = $query->getModel()->getTable();
        
        return $query->selectRaw("$tableName.*, 
            (6371 * acos(cos(radians(?)) * cos(radians($tableName.$latColumn)) * cos(radians($tableName.$lngColumn) - radians(?)) + sin(radians(?)) * sin(radians($tableName.$latColumn)))) AS distance", 
            [$lat, $lng, $lat]
        )->orderBy('distance', 'asc')
            ->orderBy($tableName.'.order_id', 'asc')
            ->orderBy($tableName.'.id', 'asc');
    }
}

if (!function_exists('applyListingOrder')) {
    /**
     * Default list ordering when location-based sorting is not used.
     */
    function applyListingOrder($query)
    {
        $tableName = $query->getModel()->getTable();

        return $query->orderBy($tableName.'.order_id', 'asc')
            ->orderBy($tableName.'.id', 'asc');
    }
}

if (!function_exists('extractImageFromHtml')) {
    /**
     * Extract first image URL from HTML content
     *
     * @param string|null $html
     * @return string|null
     */
    function extractImageFromHtml(?string $html): ?string
    {
        if (!$html) {
            return null;
        }
        
        // Use regex to find first img tag
        if (preg_match('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $html, $matches)) {
            $src = $matches[1];

            // Handle malformed absolute URLs like "https:/domain/path"
            if (Str::startsWith($src, 'https:/') && !Str::startsWith($src, 'https://')) {
                $src = preg_replace('#^https:/+#i', 'https://', $src);
            } elseif (Str::startsWith($src, 'http:/') && !Str::startsWith($src, 'http://')) {
                $src = preg_replace('#^http:/+#i', 'http://', $src);
            }
            
            // Treat any http/https URL as absolute (FILTER_VALIDATE_URL may fail with non-ASCII filenames)
            if (Str::startsWith($src, ['http://', 'https://'])) {
                return $src;
            }

            // If it's a relative path, make it absolute
            if (!filter_var($src, FILTER_VALIDATE_URL)) {
                $src = ltrim($src, '/');
                if (strpos($src, 'storage/') === 0 || strpos($src, 'uploads/') === 0) {
                    return getAppUrl() . '/' . $src;
                }
                return getAppUrl() . '/' . $src;
            }
            return $src;
        }
        
        return null;
    }
}
