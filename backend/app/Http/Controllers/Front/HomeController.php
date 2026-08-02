<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Mail\SaveNewTrip;
use App\Models\Caravan;
use App\Models\Category;
use App\Models\CategoryOfStore;
use App\Models\CategoryOfZad;
use App\Models\City;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\MostVisit;
use App\Models\Opinion;
use App\Models\Place;
use App\Models\Price;
use App\Models\Rate;
use App\Models\Store;
use App\Models\Swalef;
use App\Models\Trip;
use App\Models\User;
use App\Models\ZadElgadel;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Newsletter\Newsletter;

class HomeController extends Controller
{

    public function save_trip_to_email(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'user_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toArray(), 422);
        }

        $data = $request->except('_token', 'user_name', 'email');
        $data['token'] = Str::random(20);
        Trip::create($data);
        $key = "current_trip_" . Auth::id();
        Cache::forget($key);
        try {
            Mail::to($data['email'])->send(new SaveNewTrip($data));
        } catch (\Throwable $th) {
            //throw $th;
        }

        return response()->json([]);
    }

    public function save_trip(Request $request)
    {
        $data = $request->except('_token');
        $data['user_id'] = auth()->user()->id;

        if (!Trip::where("user_id", Auth::id())->where("name", $data["name"])->exists()) {
            Trip::create($data);
        }
        $key = "current_trip_" . Auth::id();
        Cache::forget($key);
        return redirect()->route('front.my_trips');
    }

    public function my_trips()
    {
        $trips = Trip::where('user_id', auth()->user()->id)->get();
        $places = [];
        return view('front.trip.my_trips', compact('trips', 'places'));
    }

    public function logout()
    {
        auth()->logout();
        return redirect('/ar');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toArray(), 422);
        }

        if (!auth()->attempt($request->only('email', 'password'))) {
            return response()->json(['password' => __("Invalid Data")], 422);
        }

        return response()->json([]);

    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'register_email' => 'required|unique:users,email',
            'register_password' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toArray(), 422);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->register_email,
            'photo' => "assets/media/avatars/150-2.jpg",
            'password' => Hash::make($request->register_password),
        ]);
        if ($user) {
            auth()->login($user);
        }
        return response()->json([]);
    }

    public function action_selected_places(Request $request)
    {

        $key = "current_trip_" . Auth::id();

        if (!Cache::has($key)) {
            Cache::put($key, $request->all());
        } else
            $request->merge(Cache::get($key));

        $validator = Validator::make($request->all(), [
            'daterange' => 'required',
            'type' => 'required',
            'funny_place_per_day' => 'required',
            'region1' => 'required',
            'region2' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->to(route("front.index"))->withErrors($validator);
        }

        $start_date = explode('-', $request->daterange)[1];
        $end_date = explode('-', $request->daterange)[0];

        $earlier = new DateTime($start_date);
        $later = new DateTime($end_date);
        $categories = [];
        $season = null;

        if (request('region1') && request('region2')) {
            if (request('region1') > request('region2')) {
                $order = 'desc';
            } else {
                $order = 'asc';
            }
        }
        $days = $trip_days = ((int)($later->diff($earlier)->format("%a")) + 1); //3 // request('trip_days', null) ??

        $days = $days > 1 ? $days - 1 : $days;
        if (request('type') == 'day') {
            $need_count = $request->funny_place_per_day * $trip_days;
            $funny_place_per_day = request('funny_place_per_day', null);
        } else {
            $funny_place_per_day = $request->funny_place_per_day;
            $need_count = $trip_days * $funny_place_per_day;

//            if ($funny_place_per_day > 4) {
//                $need_count = 4 * $trip_days;
//                $funny_place_per_day = 4;
//            }
        }

        $places = Place::with('city', 'region')
            ->whereIn('price_id', request('price', []));

        $month = (int)date('m', strtotime($start_date));
        $places->where(function ($query) use ($month) {
            if ($month >= 3 && $month <= 5) {
                $season = 'موسم الربيع';
            } elseif ($month >= 6 && $month <= 8) {
                $season = 'موسم الصيف';
            } elseif ($month >= 9 && $month <= 11) {
                $season = 'موسم الخريف';
            } elseif ($month >= 12 || $month <= 2) {
                $season = 'موسم الشتاء';
            }
            $query->where('seasons', 'like', '%' . $season . '%')->orWhere("seasons", "طوال العام");
        });

        $places = $places->where(function ($q) {
            $q->where('region_id', request('region1'));
        });


        if (request('categories', [])) {
            $categories = is_array(request('categories')) ? request('categories') : explode(',', request('categories'));
            $places = $places->where(function ($q) use ($categories) {
                foreach ($categories as $category_id) {
                    $x[] = '[' . $category_id . ']';
                }
                $q->whereIn("categories", $x);
            });

        }

        $places = $places->where(function ($q) {
            $q->whereBetween('lat', Arr::sort([request('lat1'), request('lat2')]))
                ->orWhereBetween('long', Arr::sort([request('long1'), request('long2')]));
        });

        $places = $places->orderByRaw(DB::raw("
                                ST_Distance_Sphere(
                                    point(places.long, places.lat),
                                    point(" . request('long1') . ", " . request('lat1') . ")
                                )
                            "));
        $places = $places->take($need_count)->get();
        if ($places->count() < ($need_count * $funny_place_per_day)) {
            $need = $need_count - $places->count();
            $new_places = Place::with('city', 'region')
                ->whereIn('price_id', request('price', []))
                ->where(function ($q) {
                    $q->whereBetween('lat', Arr::sort([request('lat1'), request('lat2')]))
                        ->orWhereBetween('long', Arr::sort([request('long1'), request('long2')]));
                })->where(function ($q) use ($season, $request) {
                    $q->where(function ($q) use ($request) {
                        $q->where('region_id', $request->region2);
                    })->where(function ($query) use ($season) {
                        $query->where('seasons', 'like', '%' . $season . '%')->orWhere("seasons", "طوال العام");
                    });
                })
                ->orderByRaw(DB::raw("
            ST_Distance_Sphere(
                point(places.long, places.lat),
                point(" . request('long2') . ", " . request('lat2') . ")
            )
        "))->take($need)->get();
            $places = array_merge($places->toArray(), $new_places->toArray());
        }

        $data = [];
        $ids = [];
        $items = [];
        $i = 0;

        foreach ($places as $key => $value) {
            if (count($data[$i] ?? []) < $funny_place_per_day) {
                $data[$i][] = $value;
                $ids[$i][] = $value['id'];
            } else {
                $i = $i + 1;
                $data[$i][] = $value;
                $ids[$i][] = $value['id'];
            }
            $items[] = $value['id'];
        }

        $items = $ids;
        $places = $data;
        $daterange = request('daterange');
        $date = $start_date;
        $type = request('type');
        $funny = request('funny_place_per_day', null);
        $region1 = request('region1', null);
        $lat1 = request('lat1', null);
        $long1 = request('long1', null);
        $lat2 = request('lat2', null);
        $long2 = request('long2', null);
        $region2 = request('region2', null);
        $selected_categories = implode(',', $categories);
        return view('front.trip.selected_places', compact(
            'selected_categories',
            'season',
            'region1',
            'lat1',
            'long1',
            'lat2',
            'long2',
            'region2',
            'places',
            'days',
            'funny_place_per_day',
            'funny',
            'items',
            'date',
            'type',
            'daterange'
        ));
    }

    public function view_trip($id)
    {
        $trip = Trip::find($id);
        if (!$trip) {
            $trip = Trip::where('token', $id)->first();
        }

        if (!$trip) {
            return redirect('notfound');
        }

        $places = [];

        $items = (json_decode($trip->items, true));
        foreach ($items as $key => $value) {
            $places[$key] = Place::with('region', 'city')->whereIn('id', $value)->get();
        }

        $daterange = $date = $trip->date;

        $days = $trip->days;

        $funny = $funny_place_per_day = $days;
        $type = null;

        return view('front.trip.selected_places', compact('funny', 'type', 'places', 'trip', 'date', 'days', 'daterange', 'funny_place_per_day'));
    }

    public function delete_trip($id)
    {
        Trip::whereId($id)->where("user_id", Auth::id())->delete();
        return redirect()->route('front.my_trips');
    }

    public function most_visited()
    {

        $most_data = [
            [
                "id" => "AF",
                "name" => "Afghanistan",
                "value" => 32358260,
            ], [
                "id" => "AL",
                "name" => "Albania",
                "value" => 3215988,
            ], [
                "id" => "DZ",
                "name" => "Algeria",
                "value" => 35980193,
            ], [
                "id" => "AO",
                "name" => "Angola",
                "value" => 19618432,
            ], [
                "id" => "AR",
                "name" => "Argentina",
                "value" => 40764561,
            ], [
                "id" => "AM",
                "name" => "Armenia",
                "value" => 3100236,
            ], [
                "id" => "AU",
                "name" => "Australia",
                "value" => 22605732,
            ], [
                "id" => "AT",
                "name" => "Austria",
                "value" => 8413429,
            ], [
                "id" => "AZ",
                "name" => "Azerbaijan",
                "value" => 9306023,
            ], [
                "id" => "BH",
                "name" => "Bahrain",
                "value" => 1323535,
            ], [
                "id" => "BD",
                "name" => "Bangladesh",
                "value" => 150493658,
            ], [
                "id" => "BY",
                "name" => "Belarus",
                "value" => 9559441,
            ], [
                "id" => "BE",
                "name" => "Belgium",
                "value" => 10754056,
            ], [
                "id" => "BJ",
                "name" => "Benin",
                "value" => 9099922,
            ], [
                "id" => "BT",
                "name" => "Bhutan",
                "value" => 738267,
            ], [
                "id" => "BO",
                "name" => "Bolivia",
                "value" => 10088108,
            ], [
                "id" => "BA",
                "name" => "Bosnia and Herzegovina",
                "value" => 3752228,
            ], [
                "id" => "BW",
                "name" => "Botswana",
                "value" => 2030738,
            ], [
                "id" => "BR",
                "name" => "Brazil",
                "value" => 196655014,
            ], [
                "id" => "BN",
                "name" => "Brunei",
                "value" => 405938,
            ], [
                "id" => "BG",
                "name" => "Bulgaria",
                "value" => 7446135,
            ], [
                "id" => "BF",
                "name" => "Burkina Faso",
                "value" => 16967845,
            ], [
                "id" => "BI",
                "name" => "Burundi",
                "value" => 8575172,
            ], [
                "id" => "KH",
                "name" => "Cambodia",
                "value" => 14305183,
            ], [
                "id" => "CM",
                "name" => "Cameroon",
                "value" => 20030362,
            ], [
                "id" => "CA",
                "name" => "Canada",
                "value" => 34349561,
            ], [
                "id" => "CV",
                "name" => "Cape Verde",
                "value" => 500585,
            ], [
                "id" => "CF",
                "name" => "Central African Rep.",
                "value" => 4486837,
            ], [
                "id" => "TD",
                "name" => "Chad",
                "value" => 11525496,
            ], [
                "id" => "CL",
                "name" => "Chile",
                "value" => 17269525,
            ], [
                "id" => "CN",
                "name" => "China",
                "value" => 1347565324,
            ], [
                "id" => "CO",
                "name" => "Colombia",
                "value" => 46927125,
            ], [
                "id" => "KM",
                "name" => "Comoros",
                "value" => 753943,
            ], [
                "id" => "CD",
                "name" => "Congo, Dem. Rep.",
                "value" => 67757577,
            ], [
                "id" => "CG",
                "name" => "Congo, Rep.",
                "value" => 4139748,
            ], [
                "id" => "CR",
                "name" => "Costa Rica",
                "value" => 4726575,
            ], [
                "id" => "CI",
                "name" => "Cote d'Ivoire",
                "value" => 20152894,
            ], [
                "id" => "HR",
                "name" => "Croatia",
                "value" => 4395560,
            ], [
                "id" => "CU",
                "name" => "Cuba",
                "value" => 11253665,
            ], [
                "id" => "CY",
                "name" => "Cyprus",
                "value" => 1116564,
            ], [
                "id" => "CZ",
                "name" => "Czech Rep.",
                "value" => 10534293,
            ], [
                "id" => "DK",
                "name" => "Denmark",
                "value" => 5572594,
            ], [
                "id" => "DJ",
                "name" => "Djibouti",
                "value" => 905564,
            ], [
                "id" => "DO",
                "name" => "Dominican Rep.",
                "value" => 10056181,
            ], [
                "id" => "EC",
                "name" => "Ecuador",
                "value" => 14666055,
            ], [
                "id" => "EG",
                "name" => "Egypt",
                "value" => 82536770,
            ], [
                "id" => "SV",
                "name" => "El Salvador",
                "value" => 6227491,
            ], [
                "id" => "GQ",
                "name" => "Equatorial Guinea",
                "value" => 720213,
            ], [
                "id" => "ER",
                "name" => "Eritrea",
                "value" => 5415280,
            ], [
                "id" => "EE",
                "name" => "Estonia",
                "value" => 1340537,
            ], [
                "id" => "ET",
                "name" => "Ethiopia",
                "value" => 84734262,
            ], [
                "id" => "FJ",
                "name" => "Fiji",
                "value" => 868406,
            ], [
                "id" => "FI",
                "name" => "Finland",
                "value" => 5384770,
            ], [
                "id" => "FR",
                "name" => "France",
                "value" => 63125894,
            ], [
                "id" => "GA",
                "name" => "Gabon",
                "value" => 1534262,
            ], [
                "id" => "GM",
                "name" => "Gambia",
                "value" => 1776103,
            ], [
                "id" => "GE",
                "name" => "Georgia",
                "value" => 4329026,
            ], [
                "id" => "DE",
                "name" => "Germany",
                "value" => 82162512,
            ], [
                "id" => "GH",
                "name" => "Ghana",
                "value" => 24965816,
            ], [
                "id" => "GR",
                "name" => "Greece",
                "value" => 11390031,
            ], [
                "id" => "GT",
                "name" => "Guatemala",
                "value" => 14757316,
            ], [
                "id" => "GN",
                "name" => "Guinea",
                "value" => 10221808,
            ], [
                "id" => "GW",
                "name" => "Guinea-Bissau",
                "value" => 1547061,
            ], [
                "id" => "GY",
                "name" => "Guyana",
                "value" => 756040,
            ], [
                "id" => "HT",
                "name" => "Haiti",
                "value" => 10123787,
            ], [
                "id" => "HN",
                "name" => "Honduras",
                "value" => 7754687,
            ], [
                "id" => "HK",
                "name" => "Hong Kong, China",
                "value" => 7122187,
            ], [
                "id" => "HU",
                "name" => "Hungary",
                "value" => 9966116,
            ], [
                "id" => "IS",
                "name" => "Iceland",
                "value" => 324366,
            ], [
                "id" => "IN",
                "name" => "India",
                "value" => 1241491960,
            ], [
                "id" => "ID",
                "name" => "Indonesia",
                "value" => 242325638,
            ], [
                "id" => "IR",
                "name" => "Iran",
                "value" => 74798599,
            ], [
                "id" => "IQ",
                "name" => "Iraq",
                "value" => 32664942,
            ], [
                "id" => "IE",
                "name" => "Ireland",
                "value" => 4525802,
            ], [
                "id" => "IL",
                "name" => "Israel",
                "value" => 7562194,
            ], [
                "id" => "IT",
                "name" => "Italy",
                "value" => 60788694,
            ], [
                "id" => "JM",
                "name" => "Jamaica",
                "value" => 2751273,
            ], [
                "id" => "JP",
                "name" => "Japan",
                "value" => 126497241,
            ], [
                "id" => "JO",
                "name" => "Jordan",
                "value" => 6330169,
            ], [
                "id" => "KZ",
                "name" => "Kazakhstan",
                "value" => 16206750,
            ], [
                "id" => "KE",
                "name" => "Kenya",
                "value" => 41609728,
            ], [
                "id" => "KP",
                "name" => "Korea, Dem. Rep.",
                "value" => 24451285,
            ], [
                "id" => "KR",
                "name" => "Korea, Rep.",
                "value" => 48391343,
            ], [
                "id" => "KW",
                "name" => "Kuwait",
                "value" => 2818042,
            ], [
                "id" => "KG",
                "name" => "Kyrgyzstan",
                "value" => 5392580,
            ], [
                "id" => "LA",
                "name" => "Laos",
                "value" => 6288037,
            ], [
                "id" => "LV",
                "name" => "Latvia",
                "value" => 2243142,
            ], [
                "id" => "LB",
                "name" => "Lebanon",
                "value" => 4259405,
            ], [
                "id" => "LS",
                "name" => "Lesotho",
                "value" => 2193843,
            ], [
                "id" => "LR",
                "name" => "Liberia",
                "value" => 4128572,
            ], [
                "id" => "LY",
                "name" => "Libya",
                "value" => 6422772,
            ], [
                "id" => "LT",
                "name" => "Lithuania",
                "value" => 3307481,
            ], [
                "id" => "LU",
                "name" => "Luxembourg",
                "value" => 515941,
            ], [
                "id" => "MK",
                "name" => "Macedonia, FYR",
                "value" => 2063893,
            ], [
                "id" => "MG",
                "name" => "Madagascar",
                "value" => 21315135,
            ], [
                "id" => "MW",
                "name" => "Malawi",
                "value" => 15380888,
            ], [
                "id" => "MY",
                "name" => "Malaysia",
                "value" => 28859154,
            ], [
                "id" => "ML",
                "name" => "Mali",
                "value" => 15839538,
            ], [
                "id" => "MR",
                "name" => "Mauritania",
                "value" => 3541540,
            ], [
                "id" => "MU",
                "name" => "Mauritius",
                "value" => 1306593,
            ], [
                "id" => "MX",
                "name" => "Mexico",
                "value" => 114793341,
            ], [
                "id" => "MD",
                "name" => "Moldova",
                "value" => 3544864,
            ], [
                "id" => "MN",
                "name" => "Mongolia",
                "value" => 2800114,
            ], [
                "id" => "ME",
                "name" => "Montenegro",
                "value" => 632261,
            ], [
                "id" => "MA",
                "name" => "Morocco",
                "value" => 32272974,
            ], [
                "id" => "MZ",
                "name" => "Mozambique",
                "value" => 23929708,
            ], [
                "id" => "MM",
                "name" => "Myanmar",
                "value" => 48336763,
            ], [
                "id" => "NA",
                "name" => "Namibia",
                "value" => 2324004,
            ], [
                "id" => "NP",
                "name" => "Nepal",
                "value" => 30485798,
            ], [
                "id" => "NL",
                "name" => "Netherlands",
                "value" => 16664746,
            ], [
                "id" => "NZ",
                "name" => "New Zealand",
                "value" => 4414509,
            ], [
                "id" => "NI",
                "name" => "Nicaragua",
                "value" => 5869859,
            ], [
                "id" => "NE",
                "name" => "Niger",
                "value" => 16068994,
            ], [
                "id" => "NG",
                "name" => "Nigeria",
                "value" => 162470737,
            ], [
                "id" => "NO",
                "name" => "Norway",
                "value" => 4924848,
            ], [
                "id" => "OM",
                "name" => "Oman",
                "value" => 2846145,
            ], [
                "id" => "PK",
                "name" => "Pakistan",
                "value" => 176745364,
            ], [
                "id" => "PA",
                "name" => "Panama",
                "value" => 3571185,
            ], [
                "id" => "PG",
                "name" => "Papua New Guinea",
                "value" => 7013829,
            ], [
                "id" => "PY",
                "name" => "Paraguay",
                "value" => 6568290,
            ], [
                "id" => "PE",
                "name" => "Peru",
                "value" => 29399817,
            ], [
                "id" => "PH",
                "name" => "Philippines",
                "value" => 94852030,
            ], [
                "id" => "PL",
                "name" => "Poland",
                "value" => 38298949,
            ], [
                "id" => "PT",
                "name" => "Portugal",
                "value" => 10689663,
            ], [
                "id" => "PR",
                "name" => "Puerto Rico",
                "value" => 3745526,
            ], [
                "id" => "QA",
                "name" => "Qatar",
                "value" => 1870041,
            ], [
                "id" => "RO",
                "name" => "Romania",
                "value" => 21436495,
            ], [
                "id" => "RU",
                "name" => "Russia",
                "value" => 142835555,
            ], [
                "id" => "RW",
                "name" => "Rwanda",
                "value" => 10942950,
            ], [
                "id" => "SA",
                "name" => "Saudi Arabia",
                "value" => 28082541,
            ], [
                "id" => "SN",
                "name" => "Senegal",
                "value" => 12767556,
            ], [
                "id" => "RS",
                "name" => "Serbia",
                "value" => 9853969,
            ], [
                "id" => "SL",
                "name" => "Sierra Leone",
                "value" => 5997486,
            ], [
                "id" => "SG",
                "name" => "Singapore",
                "value" => 5187933,
            ], [
                "id" => "SK",
                "name" => "Slovak Republic",
                "value" => 5471502,
            ], [
                "id" => "SI",
                "name" => "Slovenia",
                "value" => 2035012,
            ], [
                "id" => "SB",
                "name" => "Solomon Islands",
                "value" => 552267,
            ], [
                "id" => "SO",
                "name" => "Somalia",
                "value" => 9556873,
            ], [
                "id" => "ZA",
                "name" => "South Africa",
                "value" => 50459978,
            ], [
                "id" => "ES",
                "name" => "Spain",
                "value" => 46454895,
            ], [
                "id" => "LK",
                "name" => "Sri Lanka",
                "value" => 21045394,
            ], [
                "id" => "SD",
                "name" => "Sudan",
                "value" => 34735288,
            ], [
                "id" => "SR",
                "name" => "Suriname",
                "value" => 529419,
            ], [
                "id" => "SZ",
                "name" => "Swaziland",
                "value" => 1203330,
            ], [
                "id" => "SE",
                "name" => "Sweden",
                "value" => 9440747,
            ], [
                "id" => "CH",
                "name" => "Switzerland",
                "value" => 7701690,
            ], [
                "id" => "SY",
                "name" => "Syria",
                "value" => 20766037,
            ], [
                "id" => "TW",
                "name" => "Taiwan",
                "value" => 23072000,
            ], [
                "id" => "TJ",
                "name" => "Tajikistan",
                "value" => 6976958,
            ], [
                "id" => "TZ",
                "name" => "Tanzania",
                "value" => 46218486,
            ], [
                "id" => "TH",
                "name" => "Thailand",
                "value" => 69518555,
            ], [
                "id" => "TG",
                "name" => "Togo",
                "value" => 6154813,
            ], [
                "id" => "TT",
                "name" => "Trinidad and Tobago",
                "value" => 1346350,
            ], [
                "id" => "TN",
                "name" => "Tunisia",
                "value" => 10594057,
            ], [
                "id" => "TR",
                "name" => "Turkey",
                "value" => 73639596,
            ], [
                "id" => "TM",
                "name" => "Turkmenistan",
                "value" => 5105301,
            ], [
                "id" => "UG",
                "name" => "Uganda",
                "value" => 34509205,
            ], [
                "id" => "UA",
                "name" => "Ukraine",
                "value" => 45190180,
            ], [
                "id" => "AE",
                "name" => "United Arab Emirates",
                "value" => 7890924,
            ], [
                "id" => "GB",
                "name" => "United Kingdom",
                "value" => 62417431,
            ], [
                "id" => "US",
                "name" => "United States",
                "value" => 313085380,
            ], [
                "id" => "UY",
                "name" => "Uruguay",
                "value" => 3380008,
            ], [
                "id" => "UZ",
                "name" => "Uzbekistan",
                "value" => 27760267,
            ], [
                "id" => "VE",
                "name" => "Venezuela",
                "value" => 29436891,
            ], [
                "id" => "PS",
                "name" => "West Bank and Gaza",
                "value" => 4152369,
            ], [
                "id" => "VN",
                "name" => "Vietnam",
                "value" => 88791996,
            ], [
                "id" => "YE",
                "name" => "Yemen, Rep.",
                "value" => 24799880,
            ], [
                "id" => "ZM",
                "name" => "Zambia",
                "value" => 13474959,
            ], [
                "id" => "ZW",
                "name" => "Zimbabwe",
                "value" => 12754378,
            ]
        ];

        $most_data = array_map(function ($i) {
            $count = MostVisit::where('country_code', $i['id'])->count();
            if ($count)
                return [
                    'id' => $i['id'],
                    'name' => $i['name'],
                    'value' => $count
                ];
        }, $most_data);

        return collect(
            array_values(array_filter($most_data))
        );
        // return json_encode($most_data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);

    }

    public function index()
    {
        visit_item();
        $caravans = Caravan::get();
        $events = Event::where('active', 1)
            ->where('date_from', '<=', now())
            ->where('date_to', '>=', now())
            ->get();
        $swalefs = Swalef::where('active', 1)->get();
        $zad_elgadels = ZadElgadel::where('active', 1)->take(3)->get();
        $stores_data = Store::where('active', 1)->take(5)->get();
        $places_data = Place::where('featured', 1)->where('active', 1)->take(5)->get();
        $place_categories = Category::whereNull('parent_id')->get();
        $most_data = Cache::remember("most_visited", 60 * 60 * 24, function () {
            return $this->most_visited();
        });

        return view('front.index', [
            'title' => __('dashboard.show_title', ['title' => __('dashboard.home')]),
        ], compact('caravans', 'events', 'place_categories', 'stores_data', 'places_data', 'swalefs', 'most_data', 'zad_elgadels'));
    }

    public function searchPlaces(Request $request)
    {
        if (!$request->search) {
            return [];
        }

        $result_places = Place::with('city', 'region')
            ->where(function ($q) use ($request) {
                $q->whereTranslationLike('title', '%' . $request->search . '%')
                    ->orWhereTranslationLike('description', '%' . $request->search . '%');
            })->where('active', 1)->take(10)->get();

        $result_stores = Store::with('city', 'region')
            ->where(function ($q) use ($request) {
                $q->whereTranslationLike('title', '%' . $request->search . '%')
                    ->orWhereTranslationLike('description', '%' . $request->search . '%');
            })->where('active', 1)->take(10)->get();

        $result_zads = ZadElgadel::with('city', 'region')
            ->where(function ($q) use ($request) {
                $q->whereTranslationLike('title', '%' . $request->search . '%')
                    ->orWhereTranslationLike('description', '%' . $request->search . '%');
            })->where('active', 1)->take(10)->get();


        $places = array_merge(($result_places ? $result_places->toArray() : []), ($result_stores ? $result_stores->toArray() : []), ($result_zads ? $result_zads->toArray() : []));

        return $places ?? [];
    }

    public function events(Request $request)
    {
        $categories = Category::whereNull('parent_id')->get();
        $events = Event::with('ratings', 'galleries', 'city', 'region')->where('active', 1);
        if (request('search')) {
            $events = $events->where(function ($q) use ($request) {
                $q->whereTranslationLike('title', '%' . $request->search . '%')
                    ->orWhereTranslationLike('description', '%' . $request->search . '%');
            });
        }

        if (request('daterange')) {
            $dates = explode('-', $request->daterange);
            $date_from = date('Y-m-d', strtotime(trim($dates[0])));
            $date_to = date('Y-m-d', strtotime(trim($dates[1])));

            $events = $events->where(function ($q) use ($date_to, $date_from) {
                $q->where('date_from', '<=', $date_from)->where('date_to', '>=', $date_to);
            })->orWhere(function ($q) use ($date_to, $date_from) {
                $q->where('date_from', '>=', $date_from)->where('date_to', '<=', $date_to);
            });
        }

        if (request('address_type')) {
            $events = $events->where('type', request('address_type'));
        }

        if (request('category_id')) {
            $x = '[' . request('category_id') . ']';
            $events = $events->whereRaw("JSON_CONTAINS(categories, '" . $x . "' )");
        }

        if (request('lat') && request('lng') && request('x') && request('y')) {
            $latitude = request('lat');
            $longitude = request('lng');
            $x = request('x');
            $y = request('y');

            $upper_latitude = $latitude + ($x + 2); //Change .50 to small values
            $lower_latitude = $latitude - ($x + 1); //Change .50 to small values
            $upper_longitude = $longitude + ($y + 2); //Change .50 to small values
            $lower_longitude = $longitude - ($y + 1); //Change .50 to small values

            $events = $events
                ->whereBetween('lat', [$lower_latitude, $upper_latitude])
                ->whereBetween('long', [$lower_longitude, $upper_longitude]);
        }

        $places = $events->paginate(10)->onEachSide(2);
        if (request()->wantsJson())
            return view('front.event.cards', compact('places'))->render();

        $events_data = Event::with('ratings', 'city', 'region')->select('id', 'slug', 'lat', 'long', 'image', 'city_id', 'region_id')
            ->whereNotNull('lat')->whereNotNull('long')->whereIn('id', $events->pluck('id'))->paginate(10);
        $events_data_for_map = $events_data->toArray()['data'];
        return view('front.event.all', compact('categories', 'events_data_for_map', 'places'));
    }

    public function eventDetails($id)
    {
        $event = Event::with('ratings', 'galleries', 'city', 'region')->where('active', 1)->where('slug', $id)->firstOrFail();

        $event_for_map = Event::with('city', 'region')->select('id', 'slug', 'lat', 'long', 'image', 'city_id', 'region_id')->where('active', 1)->findOrFail($event->id);
        $inside_map = Event::select('id', 'slug', 'lat', 'long')->where('id', '!=', $event->id)->where('active', 1)->get()->map(function ($i) use ($event) {
            if (round(distance($event['lat'], $event['long'], $i['lat'], $i['long'], "M"), 1) <= $event['distance'] ?? 0) {
                return $i['id'];
            }
        });
        $all_related_items = array_unique(array_merge(array_filter($inside_map->toArray()) ?? [], $event->related ?? []));

        if (!in_array($id, session()->get('ids', []))) {
            $ids = session()->get('ids', []);
            $ids[] = $id;
            session()->put('ids', $ids);
            $event->update(['views_num' => $event->views_num + 1]);
        }
        $place = [];
        return view('front.event.details', compact('event', 'event_for_map', 'place'));
    }

    public function Places(Request $request)
    {

        $places = Place::with('ratings', 'galleries', 'city', 'region')->where('active', 1);

        if (request('search')) {
            $places = $places->where(function ($q) use ($request) {
                $q->whereTranslationLike('title', '%' . $request->search . '%')
                    ->orWhereTranslationLike('description', '%' . $request->search . '%');
            });
        }

        if (request('city_id')) {
            $places = $places->whereIn("city_id", request('city_id'));
        }

        if (request('region_id')) {
            $places = $places->whereIn("region_id", request('region_id'));
        }
        if (request('category_id')) {
            if (is_array(request('category_id'))) {
                foreach (request('category_id') as $category_id) {
                    $x[] = '[' . $category_id . ']';
                }
            } else {
                $x[] = '[' . request('category_id') . ']';
            }
            $places = $places->whereIn("categories", $x);
        }

        if (request('sub_category_id')) {
            if (is_array(request('sub_category_id'))) {
                foreach (request('sub_category_id') as $category_id) {
                    $x[] = '[' . $category_id . ']';
                }
            } else {
                $x[] = '[' . request('sub_category_id') . ']';
            }
            $places = $places->whereIn("categories", $x);
        }

        if (request('lat') && request('lng') && request('x') && request('y')) {
            $latitude = request('lat');
            $longitude = request('lng');
            $x = request('x');
            $y = request('y');

            $upper_latitude = $latitude + ($x + 2); //Change .50 to small values
            $lower_latitude = $latitude - ($x + 1); //Change .50 to small values
            $upper_longitude = $longitude + ($y + 2); //Change .50 to small values
            $lower_longitude = $longitude - ($y + 1); //Change .50 to small values

            $places = $places
                ->whereBetween('lat', [$lower_latitude, $upper_latitude])
                ->whereBetween('long', [$lower_longitude, $upper_longitude]);
        }

        if (request('price_id')) {
            $places = $places->where('price_id', request('price_id'));
        }

        if (request('temperature')) {
            $places = $places->where('temperature', request('temperature'));
        }

        $places = $places->paginate(10)->onEachSide(2);
        if (\request()->wantsJson())
            return view('front.place.cards', compact('places'))->render();

        $places_data = Place::with('ratings', 'city', 'region')->select('id', 'slug', 'lat', 'long', 'image', 'city_id', 'region_id')
            ->whereNotNull('lat')->whereNotNull('long')->whereIn('id', $places->pluck('id'))->paginate(10);
        $places_data_for_map = $places_data->toArray()['data'];
        $categories = Category::whereNull('parent_id')->get();
        $prices = Price::where('show', 1)->get();
        return view('front.place.all', compact('places', 'categories', 'prices', 'places_data_for_map'));
    }

    public function PlaceDetails($id)
    {
        $place = Place::with('ratings', 'galleries', 'city', 'region')->where('active', 1)->where('slug', $id)->firstOrFail();

        $place_for_map = Place::with('city', 'region')->select('id', 'slug', 'lat', 'long', 'image', 'city_id', 'region_id')->where('active', 1)->findOrFail($place->id);
        $inside_map = Place::select('id', 'slug', 'lat', 'long')->where('id', '!=', $place->id)->where('active', 1)->get()->map(function ($i) use ($place) {
            if (round(distance($place['lat'], $place['long'], $i['lat'], $i['long'], "M"), 1) <= $place['distance'] ?? 0) {
                return $i['id'];
            }
        });

        $all_related_items = array_unique(array_merge(array_filter($inside_map->toArray()) ?? [], $place->related_places ?? []));

        $best_Places = $place ? Place::with('ratings', 'galleries', 'city', 'region')->whereIn('id', $all_related_items ?? [])->where('active', 1)->take(20)->get() : null;
        $best_stores = $place ? store::with('ratings', 'galleries', 'city', 'region')->whereIn('id', $place->near_stores ?? [])->where('active', 1)->take(20)->get() : null;
        if (!in_array($id, session()->get('ids', []))) {
            $ids = session()->get('ids', []);
            $ids[] = $id;
            session()->put('ids', $ids);
            $place->update(['views_num' => $place->views_num + 1]);
        }

        return view('front.place.details', compact('place', 'best_Places', 'best_stores', 'place_for_map'));
    }

    public function contactus()
    {
        $contactus = true;
        return view('front.contactus.index', compact('contactus'));
    }

    public function ratePlaces(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'email' => [Rule::requiredIf(Auth::guest()), 'email'],
            'name' => [Rule::requiredIf(Auth::guest()), 'string', 'max:255'],
            'rateText' => 'required|min:10',
            'rate' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toArray(), 422);
        }


        if (auth()->check()) {
            $data['user_id'] = auth()->user()->id;
            $data['name'] = auth()->user()->first_name . ' ' . auth()->user()->last_name;
            $data['email'] = auth()->user()->email;
        }

        switch ($request->type) {
            case 'places':
                $data['parent_id'] = Place::where('slug', $request->parent_id)->first()->id;
                break;
            case 'stores':
                $data['parent_id'] = Store::where('slug', $request->parent_id)->first()->id;
                break;
            case 'swalefs':
                $data['parent_id'] = Swalef::where('slug', $request->parent_id)->first()->id;
                break;
            case 'events':
                $data['parent_id'] = Event::where('slug', $request->parent_id)->first()->id;
                break;
            default:
                $data['parent_id'] = $request->parent_id;
                break;
        }

        return Rate::create($data);
    }

    public function getSubCategory()
    {
        return Category::whereIn('parent_id', request('parent_id'))->get();
    }

    public function getCities()
    {
        return City::whereIn('region_id', request('parent_id'))
            ->with(['region'])
            ->get();
    }

    public function uploadMessage(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[\pL\s\-]+$/u|max:25',
            'email' => 'required|email',
            'phone' => 'required|max:14',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toArray(), 422);
        }

        $request_data = $request->all();

        $data = Opinion::create($request_data);
        if ($request->ajax()) {
            session()->put('success', __("Sent successfully"));
            return $data;
        }
        return redirect()->route('front.index')->with(['success' => __("Sent successfully")]);
    }

    public function newsletter(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $request_data = $request->all();

        $newsletter = app()->make(Newsletter::class);
        $newsletter->subscribe($request_data['email'], ['TYPE' => 'members']);

        if ($request->ajax()) {
            session()->put('success', __("Sent successfully"));
            return response()->json([]);
        }
        return redirect()->route('front.index')->with(['success' => __("Sent successfully")]);
    }

    public function stores(Request $request)
    {
        $stores = Store::with('ratings', 'galleries', 'city', 'region')->where('active', 1);

        if (request('search')) {
            $stores = $stores->where(function ($q) use ($request) {
                $q->whereTranslationLike('title', '%' . $request->search . '%')
                    ->orWhereTranslationLike('description', '%' . $request->search . '%');
            });
        }

        if (request('category_id')) {
            $x = '[' . implode(',', request('category_id')) . ']';
            $stores = $stores->whereRaw("JSON_CONTAINS(categories, '" . $x . "' )");
        }

        if (request('sub_category_id')) {
            $x = '[' . implode(',', request('sub_category_id')) . ']';
            $stores = $stores->whereRaw("JSON_CONTAINS(categories, '" . $x . "' )");
        }

        if (request('lat') && request('lng') && request('x') && request('y')) {
            $latitude = request('lat');
            $longitude = request('lng');
            $x = request('x');
            $y = request('y');

            $upper_latitude = $latitude + ($x + 2); //Change .50 to small values
            $lower_latitude = $latitude - ($x + 1); //Change .50 to small values
            $upper_longitude = $longitude + ($y + 2); //Change .50 to small values
            $lower_longitude = $longitude - ($y + 1); //Change .50 to small values

            $stores = $stores
                ->whereBetween('lat', [$lower_latitude, $upper_latitude])
                ->whereBetween('long', [$lower_longitude, $upper_longitude]);
        }

        if (request('address_type')) {
            $stores = $stores->where('address_type', request('address_type'));
        }

        $stores = $stores->paginate(20);
        if (\request()->wantsJson())
            return view('front.store.cards', compact('stores'))->render();
        $categories = CategoryOfStore::whereNull('parent_id')->get();
        return view('front.store.all', compact('stores', 'categories'));
    }

    public function storeDetails($id)
    {
        $store = Store::with('ratings', 'galleries', 'city', 'region')->where('slug', $id)->firstOrFail();
        $best_stores = $store ? Store::with('ratings', 'galleries', 'city', 'region')->whereIn('id', $store->related_stores ?? [])->get() : null;
        $best_Places = $store ? Place::with('ratings', 'galleries', 'city', 'region')->whereIn('id', $store->near_places ?? [])->where('active', 1)->get() : null;
        if (!in_array($id, session()->get('ids', []))) {
            $ids = session()->get('ids', []);
            $ids[] = $id;
            session()->put('ids', $ids);
            $store->update(['views_num' => $store->views_num + 1]);
        }
        return view('front.store.details', compact('store', 'best_stores', 'best_Places'));
    }

    public function zads(Request $request)
    {
        $categories = CategoryOfZad::whereNull('parent_id')->get();
        $stores = ZadElgadel::with('ratings', 'galleries', 'city', 'region')->where('active', 1);

        if (request('search')) {
            $stores = $stores->where(function ($q) use ($request) {
                $q->whereTranslationLike('title', '%' . $request->search . '%')
                    ->orWhereTranslationLike('description', '%' . $request->search . '%');
            });
        }

        if (request('category_id')) {
            $x = '["' . request('category_id') . '"]';
            $stores = $stores->whereRaw("JSON_CONTAINS(categories, '" . $x . "' )");
        }

        if (request('sub_category_id')) {
            $x = '["' . request('sub_category_id') . '"]';
            $stores = $stores->whereRaw("JSON_CONTAINS(categories, '" . $x . "' )");
        }

        if (request('lat') && request('lng') && request('x') && request('y')) {
            $latitude = request('lat');
            $longitude = request('lng');
            $x = request('x');
            $y = request('y');

            $upper_latitude = $latitude + ($x + 2); //Change .50 to small values
            $lower_latitude = $latitude - ($x + 1); //Change .50 to small values
            $upper_longitude = $longitude + ($y + 2); //Change .50 to small values
            $lower_longitude = $longitude - ($y + 1); //Change .50 to small values

            $stores = $stores
                ->whereBetween('lat', [$lower_latitude, $upper_latitude])
                ->whereBetween('long', [$lower_longitude, $upper_longitude]);
        }

        if (request('address_type')) {
            $stores = $stores->where('address_type', request('address_type'));
        }

        $stores = $stores->paginate(20);
        if (request()->wantsJson())
            return view('front.store.cards', compact('stores'))->render();
        $zad = 1;
        return view('front.store.all', compact('stores', 'categories', 'zad'));
    }

    public function zadDetails($id)
    {
        $store = ZadElgadel::with('ratings', 'galleries', 'city', 'region')->where('slug', $id)->firstOrFail();
        $best_stores = $store ? Store::with('ratings', 'galleries', 'city', 'region')->whereIn('id', $store->related_stores ?? [])->get() : null;
        $best_Places = $store ? Place::with('ratings', 'galleries', 'city', 'region')->whereIn('id', $store->near_places ?? [])->where('active', 1)->get() : null;
        if (!in_array($id, session()->get('ids', []))) {
            $ids = session()->get('ids', []);
            $ids[] = $id;
            session()->put('ids', $ids);
            $store->update(['views_num' => $store->views_num + 1]);
        }
        $zad = 1;
        return view('front.zad.details', compact('store', 'best_stores', 'best_Places', 'zad'));
    }


    public function getFullMap()
    {
        $size = request('size', 5);

        $all_places = Place::with('city', 'region')->select('id', 'slug', 'lat', 'long', 'image', 'city_id', 'region_id', 'views_num', 'place_icon')->where('active', 1)->whereNotNull('lat')->whereNotNull('long')->take($size)->get();

        $all_stores = Store::with('city', 'region')->select('id', 'slug', 'lat', 'long', 'image', 'views_num')->where('active', 1)->whereNotNull('lat')->whereNotNull('long')->take($size)->get();

        $all_zad_elgadels = ZadElgadel::with('city', 'region')->select('id', 'slug', 'lat', 'long', 'image', 'views_num')->where('active', 1)->whereNotNull('lat')->whereNotNull('long')->take($size)->get();

        $places = array_merge(($all_places ? $all_places->toArray() : []), ($all_stores ? $all_stores->toArray() : []), ($all_zad_elgadels ? $all_zad_elgadels->toArray() : []));

        $pupular_places = Place::with('city', 'region', 'ratings')->where('active', 1)->whereNotNull('lat')->whereNotNull('long')->orderBy('views_num', 'desc')->take(10)->get();

        $pupular_stores = Store::with('city', 'region', 'ratings')->where('active', 1)->whereNotNull('lat')->whereNotNull('long')->orderBy('views_num', 'desc')->take(10)->get();

        $pupular_zad_elgadelss = ZadElgadel::with('city', 'region', 'ratings')->where('active', 1)->whereNotNull('lat')->whereNotNull('long')->orderBy('views_num', 'desc')->take(10)->get();

        $map_most_pupular = array_merge(($pupular_places ? $pupular_places->toArray() : []), ($pupular_stores ? $pupular_stores->toArray() : []), ($pupular_zad_elgadelss ? $pupular_zad_elgadelss->toArray() : []));

        // $places = json_decode(json_encode((object) $map_places), FALSE);

        $map_most_pupular_places = json_decode(json_encode((object)$map_most_pupular), FALSE);

        return view('front.place.map', compact('places', 'map_most_pupular_places'));
    }

    public function getFullMapData()
    {
        $size = request('size', 100);

        $all_places = Place::with('city', 'region')->select('id', 'slug', 'lat', 'long', 'image', 'city_id', 'region_id', 'views_num', 'place_icon')->where('active', 1)->whereNotNull('lat')->whereNotNull('long')->take($size)->get();

        $all_stores = Store::with('city', 'region')->select('id', 'slug', 'lat', 'long', 'image', 'views_num')->where('active', 1)->whereNotNull('lat')->whereNotNull('long')->take($size)->get();

        $all_zad_elgadels = ZadElgadel::with('city', 'region')->select('id', 'slug', 'lat', 'long', 'image', 'views_num')->where('active', 1)->whereNotNull('lat')->whereNotNull('long')->take($size)->get();

        $places = array_merge(($all_places ? $all_places->toArray() : []), ($all_stores ? $all_stores->toArray() : []), ($all_zad_elgadels ? $all_zad_elgadels->toArray() : []));

        return json_encode($places);
    }

    public function sync_places_data()
    {
        try {
            DB::beginTransaction();

//            $places = Place::whereNull('slug')->get();
            $stores = Store::whereNull('slug')->get();
            $swalefs = Swalef::whereNull('slug')->get();
            $zads = ZadElgadel::whereNull('slug')->get();
            $trips = Trip::whereNull('token')->get();

//            foreach ($places as $place) {
//                $place->update(['slug' => Str::slug($place->title)]);
//            }
            foreach ($stores as $store) {
                $store->update(['slug' => Str::slug($store->title)]);
            }
            foreach ($swalefs as $swalef) {
                $swalef->update(['slug' => Str::slug($swalef->title)]);
            }
            foreach ($zads as $zad) {
                $zad->update(['slug' => Str::slug($zad->title)]);
            }
            foreach ($trips as $trip) {
                $trip->update(['token' => Str::random(20)]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function save_places()
    {
        $data = Http::get('https://hawdaj7.com/api/v1/topics/12/page/1/count/10000');

        $topics = $data ? json_decode($data, true)['topics'] : [];

        $data = collect($topics)->map(function ($item) {

            $galary = $this->save_gallaries($item);

            $city_id = collect($item['fields'])->where('type', 6)->where('title', "المدينة")->first();
            $region_id = collect($item['fields'])->where('type', 6)->where('title', "المناطق")->first();
            $seasons = collect($item['fields'])->where('type', 7)->where('title', "افضل المواسم")->first();
            $price_id = collect($item['fields'])->where('type', 6)->where('title', "الاسعار")->first();
            $temperature = collect($item['fields'])->where('type', 2)->where('title', "درجة الحرارة")->first();

            return [
                'id' => $item['id'],
                'title' => $item['title'],
                'description' => $item['details'],
                'views_num' => $item['visits'],
                'city_id' => $city_id ? $city_id['value'] : null,
                'region_id' => $region_id ? $region_id['value'] : null,
                'seasons' => $seasons ? $seasons['value'] : null,
                'price_id' => $price_id ? $price_id['value'] : null,
                'temperature' => $temperature ? $temperature['value'] : null,
                'lat' => $this->save_map($item)['lat'],
                'long' => $this->save_map($item)['long'],
                'active' => 1,
                'categories' => collect($item['Joined_categories'])->map(function ($i) {
                    $cat = Category::whereTranslation('name', $i['title'])->first();
                    return $cat ? $cat->id : $i['id'];
                }),
                'image' => $this->save_image($item)
            ];
        });

        Place::insert($data->toArray());
    }

    public function save_prices()
    {
        $prices = [
            ['id' => 1, 'name' => 'مجاني', 'show' => 1],
            ['id' => 2, 'name' => 'مرتفع', 'show' => 1],
            ['id' => 3, 'name' => 'منخفض', 'show' => 1],
            ['id' => 4, 'name' => 'متوسط', 'show' => 1],
        ];

        Price::insert($prices);
    }

    public function save_map($item)
    {
        $map = Http::get('https://hawdaj7.com/api/v1/topic/maps/' . $item['id']);
        $map_data = $map ? json_decode($map, true)['maps'] : [];
        return isset($map_data[0]) ? [
            'lat' => $map_data[0]['longitude'],
            'long' => $map_data[0]['latitude'],
        ] : [
            'lat' => null,
            'long' => null,
        ];
    }

    public function save_gallaries($item)
    {
        $galary = Http::get('https://hawdaj7.com/api/v1/topic/photos/' . $item['id']);
        $galary_data = $galary ? json_decode($galary, true)['photos'] : [];
        $AllGalarydata = collect($galary_data)->map(function ($i) use ($item) {
            return [
                'parent_id' => $item['id'],
                'file' => $this->save_image($i),
                'type' => 'places',
            ];
        });

        Gallery::insert($AllGalarydata->toArray());
    }

    public function save_categories()
    {
        $data = Http::get('http://hawdaj7.com/api/v1/categories/12');

        $categories = $data ? json_decode($data, true)['categories'] : [];
        foreach ($categories as $key => $value) {
            $cat = Category::create([
                'parent_id' => null,
                'icon' => $this->save_image($value),
            ]);

            if ($value['sub_categories']) {
                foreach ($value['sub_categories'] as $skey => $svalue) {
                    Category::create([
                        'parent_id' => $cat->id,
                        'icon' => $this->save_image($svalue),
                    ]);
                }
            }
        }
    }

    public function save_citiest_and_regions()
    {
        $cities = [
            [
                'name' => 'عفيف',
                'region_id' => 1
            ],
            [
                'name' => 'القويعية',
                'region_id' => 1
            ],
            [
                'name' => 'الدوادمي',
                'region_id' => 1
            ],
            [
                'name' => 'الرين',
                'region_id' => 1
            ],
            [
                'name' => 'وادى الدواسر',
                'region_id' => 1
            ],
            [
                'name' => 'السليل',
                'region_id' => 1
            ],
            [
                'name' => 'الأفلاج',
                'region_id' => 1
            ],
            [
                'name' => 'الرين',
                'region_id' => 1
            ],
            [
                'name' => 'حوطة بنى تميم',
                'region_id' => 1
            ],
            [
                'name' => 'الحريق',
                'region_id' => 1
            ],
            [
                'name' => 'الخرج',
                'region_id' => 1
            ],
            [
                'name' => 'الدلم',
                'region_id' => 1
            ],
            [
                'name' => 'المزاحمية',
                'region_id' => 1
            ],
            [
                'name' => 'ضرما',
                'region_id' => 1
            ],
            [
                'name' => 'مرات',
                'region_id' => 1
            ],
            [
                'name' => 'شقراء',
                'region_id' => 1
            ],
            [
                'name' => 'الغاط',
                'region_id' => 1
            ],
            [
                'name' => 'الزلفى',
                'region_id' => 1
            ],
            [
                'name' => 'الخرج',
                'region_id' => 1
            ],
            [
                'name' => 'الرياض',
                'region_id' => 1
            ],
            [
                'name' => 'حريملاء',
                'region_id' => 1
            ],
            [
                'name' => 'ثادق',
                'region_id' => 1
            ],
            [
                'name' => 'رماح',
                'region_id' => 1
            ],
            [
                'name' => 'المجمعة',
                'region_id' => 1
            ],
            [
                'name' => 'العيص',
                'region_id' => 3
            ],
            [
                'name' => 'العلا',
                'region_id' => 3
            ],
            [
                'name' => 'خبير',
                'region_id' => 3
            ],
            [
                'name' => 'ينبع',
                'region_id' => 3
            ],
            [
                'name' => 'المدينة المنورة',
                'region_id' => 3
            ],
            [
                'name' => 'الحناكية',
                'region_id' => 3
            ],
            [
                'name' => 'بدر',
                'region_id' => 3
            ],
            [
                'name' => 'وادى الفرع',
                'region_id' => 3
            ],
            [
                'name' => 'رابغ',
                'region_id' => 2
            ],
            [
                'name' => 'خليص',
                'region_id' => 2
            ],
            [
                'name' => 'الكامل',
                'region_id' => 2
            ],
            [
                'name' => 'الجموم',
                'region_id' => 2
            ],
            [
                'name' => 'جدة',
                'region_id' => 2
            ],
            [
                'name' => 'الجموم',
                'region_id' => 2
            ],
            [
                'name' => 'مكة المكرمة',
                'region_id' => 2
            ],
            [
                'name' => 'الطائف',
                'region_id' => 2
            ],
            [
                'name' => 'بحرة',
                'region_id' => 2
            ],
            [
                'name' => 'الليث',
                'region_id' => 2
            ],
            [
                'name' => 'القنفذة',
                'region_id' => 2
            ],
            [
                'name' => 'العرضيات',
                'region_id' => 2
            ],
            [
                'name' => 'أضم',
                'region_id' => 2
            ],
            [
                'name' => 'ميسان',
                'region_id' => 2
            ],
            [
                'name' => 'تربة',
                'region_id' => 2
            ],
            [
                'name' => 'رنية',
                'region_id' => 2
            ],
            [
                'name' => 'الخرمة',
                'region_id' => 2
            ],
            [
                'name' => 'الموية',
                'region_id' => 2
            ],
            [
                'name' => 'بريدة',
                'region_id' => null
            ],
            [
                'name' => 'عنيزة',
                'region_id' => null
            ],
            [
                'name' => 'الرس',
                'region_id' => null
            ],
            [
                'name' => 'المذنب',
                'region_id' => null
            ],
            [
                'name' => 'البكيرية',
                'region_id' => null
            ],
            [
                'name' => 'البدائع',
                'region_id' => null
            ],
            [
                'name' => 'الأسياح',
                'region_id' => null
            ],
            [
                'name' => 'النبهانية',
                'region_id' => null
            ],
            [
                'name' => 'الشماسية',
                'region_id' => null
            ],
            [
                'name' => 'عيون الجواء',
                'region_id' => null
            ],
            [
                'name' => 'رياض الخبراء',
                'region_id' => null
            ],
            [
                'name' => 'عقلة الصقور',
                'region_id' => null
            ],
            [
                'name' => 'ضرية',
                'region_id' => null
            ],
            [
                'name' => 'أبها',
                'region_id' => null
            ],
            [
                'name' => 'خميس مشيط',
                'region_id' => null
            ],
            [
                'name' => 'بيشة',
                'region_id' => null
            ],
            [
                'name' => 'النماص',
                'region_id' => null
            ],
            [
                'name' => 'محايل عسير',
                'region_id' => null
            ],
            [
                'name' => 'ظهران الجنوب',
                'region_id' => null
            ],
            [
                'name' => 'تثليث',
                'region_id' => null
            ],
            [
                'name' => 'سراة عبيدة',
                'region_id' => null
            ],
            [
                'name' => 'رجال ألمع',
                'region_id' => null
            ],
            [
                'name' => 'بلقرن',
                'region_id' => null
            ],
            [
                'name' => 'أحد رفيدة',
                'region_id' => null
            ],
            [
                'name' => 'المجاردة',
                'region_id' => null
            ],
            [
                'name' => 'البرك',
                'region_id' => null
            ],
            [
                'name' => 'بارق',
                'region_id' => null
            ],
            [
                'name' => 'تنومة',
                'region_id' => null
            ],
            [
                'name' => 'طريب',
                'region_id' => null
            ],
            [
                'name' => 'تبوك',
                'region_id' => null
            ],
            [
                'name' => 'الوجه',
                'region_id' => null
            ],
            [
                'name' => 'ضبا',
                'region_id' => null
            ],
            [
                'name' => 'تيماء',
                'region_id' => null
            ],
            [
                'name' => 'أملج',
                'region_id' => null
            ],
            [
                'name' => 'حقل',
                'region_id' => null
            ],
            [
                'name' => 'البدع',
                'region_id' => null
            ],
            [
                'name' => 'حائل',
                'region_id' => null
            ],
            [
                'name' => 'بقعاء',
                'region_id' => null
            ],
            [
                'name' => 'الغزالة',
                'region_id' => null
            ],
            [
                'name' => 'الشنان',
                'region_id' => null
            ],
            [
                'name' => 'الحائط',
                'region_id' => null
            ],
            [
                'name' => 'السليمي',
                'region_id' => null
            ],
            [
                'name' => 'الشملي',
                'region_id' => null
            ],
            [
                'name' => 'موقق',
                'region_id' => null
            ],
            [
                'name' => 'سميراء',
                'region_id' => null
            ],
            [
                'name' => 'عرعر',
                'region_id' => null
            ],
            [
                'name' => 'رفحاء',
                'region_id' => null
            ],
            [
                'name' => 'طريف',
                'region_id' => null
            ],
            [
                'name' => 'العويقيلة',
                'region_id' => null
            ],
            [
                'name' => 'جازان',
                'region_id' => null
            ],
            [
                'name' => 'صبيا',
                'region_id' => null
            ],
            [
                'name' => 'أبو عريش',
                'region_id' => null
            ],
            [
                'name' => 'صامطة',
                'region_id' => null
            ],
            [
                'name' => 'بيش',
                'region_id' => null
            ],
            [
                'name' => 'الدرب',
                'region_id' => null
            ],
            [
                'name' => 'الحرث',
                'region_id' => null
            ],
            [
                'name' => 'ضمد',
                'region_id' => null
            ],
            [
                'name' => 'الريث',
                'region_id' => null
            ],
            [
                'name' => 'جزر فرسان',
                'region_id' => null
            ],
            [
                'name' => 'الدائر',
                'region_id' => null
            ],
            [
                'name' => 'العارضة',
                'region_id' => null
            ],
            [
                'name' => 'أحد المسارحة',
                'region_id' => null
            ],
            [
                'name' => 'العيدابي',
                'region_id' => null
            ],
            [
                'name' => 'فيفاء',
                'region_id' => null
            ],
            [
                'name' => 'الطوال',
                'region_id' => null
            ],
            [
                'name' => 'هروب',
                'region_id' => null
            ],
            [
                'name' => 'شرورة',
                'region_id' => null
            ],
            [
                'name' => 'حبونا',
                'region_id' => null
            ],
            [
                'name' => 'بدر الجنوب',
                'region_id' => null
            ],
            [
                'name' => 'يدمه',
                'region_id' => null
            ],
            [
                'name' => 'ثار',
                'region_id' => null
            ],
            [
                'name' => 'خباش',
                'region_id' => null
            ],
            [
                'name' => 'الخرخير',
                'region_id' => null
            ],
            [
                'name' => 'الباحة',
                'region_id' => null
            ],
            [
                'name' => 'بلجرشي',
                'region_id' => null
            ],
            [
                'name' => 'المندق',
                'region_id' => null
            ],
            [
                'name' => 'المخواة',
                'region_id' => null
            ],
            [
                'name' => 'قلوة',
                'region_id' => null
            ],
            [
                'name' => 'العقيق',
                'region_id' => null
            ],
            [
                'name' => 'القرى',
                'region_id' => null
            ],
            [
                'name' => 'غامد الزناد',
                'region_id' => null
            ],
            [
                'name' => 'الحجرة',
                'region_id' => null
            ],
            [
                'name' => 'بني حسن',
                'region_id' => null
            ],
            [
                'name' => 'سكاكا',
                'region_id' => null
            ],
            [
                'name' => 'القريات',
                'region_id' => null
            ],
            [
                'name' => 'دومة الجندل',
                'region_id' => null
            ],
            [
                'name' => 'طبرجل',
                'region_id' => null
            ],
            [
                'name' => 'الدمام',
                'region_id' => null
            ],
            [
                'name' => 'الإحساء',
                'region_id' => null
            ],
            [
                'name' => 'حفر الباطن',
                'region_id' => null
            ],
            [
                'name' => 'الجييل',
                'region_id' => null
            ],
            [
                'name' => 'القطيف',
                'region_id' => null
            ],
            [
                'name' => 'الخبر',
                'region_id' => null
            ],
            [
                'name' => 'الخفجي',
                'region_id' => null
            ],
            [
                'name' => 'رأس تنورة',
                'region_id' => null
            ],
            [
                'name' => 'بقيق',
                'region_id' => null
            ],
            [
                'name' => 'النعيرية',
                'region_id' => null
            ],
            [
                'name' => 'قرية العليا',
                'region_id' => null
            ],
            [
                'name' => 'العديد',
                'region_id' => null
            ],
        ];

        foreach ($cities as $key => $value) {
            City::create($value);
        }
    }

    function save_image($item)
    {
        if (isset($item['photo_file'])) {
            $image_url = $item['photo_file'];
            $folder = 'topics';
        } elseif (isset($item['url'])) {
            $image_url = $item['url'];
            $folder = 'topics';
        } elseif (isset($item['photo'])) {
            $image_url = $item['photo'];
            $folder = 'category';
        }

        if (isset($image_url)) {
            $image = explode('/', $image_url);
            // $contents = file_get_contents($image_url);
            // Storage::disk('public')->put($folder . '/' . end($image), $contents);
            return $folder . '/' . end($image);
        }
        return null;
    }

    public function get_all_swalefs()
    {
        $places = [];
        $swalefs = Swalef::orderBy('id', 'desc')->where('active', 1)->paginate(12);
        if (request()->wantsJson())
            return view('front.swalef.cards', compact('swalefs'))->render();

        return view('front.swalef.all', compact('places', 'swalefs'));
    }

    public function one_page_swalefs($id)
    {
        $places = [];
        $swalef = Swalef::where('active', 1)->where('slug', $id)->firstOrFail();
        $swalefs = Swalef::inRandomOrder()->where('id', '!=', $swalef->id)->where('active', 1)->take(4)->get();
        return view('front.swalef.one', compact('places', 'swalef', 'swalefs'));
    }
}
