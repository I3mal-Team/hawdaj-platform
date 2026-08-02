<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PrepareTrips\PrepareTripCollection;
use App\Http\Resources\PrepareTrips\PrepareTripResource;
use App\Http\Resources\Trips\TripResource;
use App\Http\Resources\Trips\TripCollection;
use App\Models\prepareTrip;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Place;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Models\Region;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SaveNewTrip;

class TripController extends ApiModalController
{

    public function save_trip(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'item_per_day' => 'required|numeric',
            'days' => 'required|numeric',
            'items' => 'required',
            'date' => 'required',
            'user_id' => 'required|exists:users,id',
            'start_date' => 'nullable',
            'end_date' => 'nullable',
            'region1' => 'nullable',
            'region2' => 'nullable',
            'prepareToken' => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->error(422 , $validator->errors()->first());
        }

        $data = $request->all();
        $data['user_id'] = auth()->guard('api')->user()->id;
        $data['token'] = Str::random(20);
        unset($data['prepareToken']);

        if (!Trip::where("user_id", \Auth::guard('api')->id())->where("name", $data["name"])->exists()) {
            Trip::create($data);
        }

        if ($request->prepareToken) {
            prepareTrip::where("token", $request->prepareToken)->delete();
        }

        return $this->ok(__('dashboard.trip_prepared_successfully'));
    }

    public function save_trip_to_email(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'item_per_day' => 'required|numeric',
            'days' => 'required|numeric',
            'items' => 'required',
            'date' => 'required',
            'email' => 'required|email',
            'user_name' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error(422 , $validator->errors()->first());
        }

        $data = $request->only('name', 'item_per_day', 'days', 'items', 'date');
        $data['token'] = Str::random(20);
        Trip::create($data);
        $data = array_merge($request->only('email', 'user_name') , $data);
//        Mail::to($data['email'])->send(new SaveNewTrip($data));

        Mail::send('front.emails.trip', ['data' => $data], function ($message) use ($data) {
            $message->to($data['email'])->subject('Trip Details');
        });

        return $this->ok(__('dashboard.trip_sent_to_email_successfully'));
    }


    public function prepare_trip(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'daterange' => 'required',
            'type' => 'required',
            'funny_place_per_day' => 'required',
            'region1' => 'required',
            'region2' => 'required',
            'categories' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->error(422 , $validator->errors()->first());
        }

        $start_date = explode('-', $request->daterange)[1];
        $end_date = explode('-', $request->daterange)[0];

        $earlier = new \DateTime($start_date);
        $later = new \DateTime($end_date);
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
            $funny_place_per_day = request('funny_place_per_day', null);
            $need_count = $funny_place_per_day * $trip_days;
        } else {
            $funny_place_per_day = $request->funny_place_per_day;
            $need_count = $funny_place_per_day;
            $funny_place_per_day = $need_count / $trip_days;
        }

        $region1 = Region::where('id' , request("region1"))->first();
        $region2 = Region::where('id' , request("region2"))->first();

        $waypoints = self::getRoutePoints($region1->translate('en')->name, $region2->translate('en')->name);
        $places = collect($waypoints)->map(function ($waypoint) use($start_date, $funny_place_per_day){

            return Place::select("places.*")->selectRaw('(6371 * acos(cos(radians(?)) * cos(radians(lat)) * cos(radians(places.long) - radians(?)) + sin(radians(?)) * sin(radians(lat)))) AS distance', [
                    $waypoint['lat'],
                    $waypoint['lng'],
                    $waypoint['lat']
                ])
                ->having('distance', '<', 50)
                ->orderBy('distance', 'asc')
                ->where(function($query){
                    $query->when(request('categories', []) , function($query){
                        $query->where(function ($q) {
                            $categories = is_array(request('categories')) ? request('categories') : explode(',', request('categories'));
                            foreach ($categories as $category_id) {
                                $x[] = '[' . $category_id . ']';
                            }
                            $q->whereIn("categories", $x);
                        });
                    });
                })
                ->whereIn('price_id', request('price', []))
                ->where(function ($query) use($start_date){
                    $month = (int)date('m', strtotime($start_date));
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
                })
                ->with('city', 'region')
                ->limit($funny_place_per_day)
                ->get();
        })
        ->flatten(1)
        ->unique('id');

        // $placesArray = $places->toArray();
        // $keys = array_keys($placesArray);
        // shuffle($keys);
        // $shuffledArray = array_merge(array_flip($keys), $placesArray);
        // $randomKeys = array_rand($shuffledArray, count($shuffledArray)/2);
        // $shuffledPlaces = collect(array_intersect_key($placesArray, array_flip($randomKeys)));
        $selectedPlaces = $places->slice(0, $need_count);
        $placesPerDay = $selectedPlaces->chunk(floor($need_count / $days));

//        dd($need_count, $days, $placesPerDay);

        // $points = self::getRoutePoints($region1->translate('en')->name, $region2->translate('en')->name);

        //     foreach($points as $index => $point){
        //         $start_location = $point["start_location"];
        //         $end_location = $point["end_location"];
        //         $fnc = $index > 0 ? "orWhere" : "where";
        //         $places = $places->$fnc(function ($q) use($start_location , $end_location) {
        //             $q->whereBetween('lat', Arr::sort([$start_location['lat'], $end_location['lat']]))
        //             ->whereBetween('long', Arr::sort([$start_location['lng'], $end_location['lng']]));
        //         });
        //     }

        // $places =   $places->orderByRaw(DB::raw("
        // ST_Distance_Sphere(
        //     point(places.long, places.lat),
        //     point(" . request('long1') . ", " . request('lat1') . "))"));
        // $places = $places->take($need_count)->get();

        // if ($places->count() < ($need_count * $funny_place_per_day)) {
        //     $need = $need_count - $places->count();
        //     $new_places = Place::with('city', 'region')
        //         ->whereIn('price_id', request('price', []))
        //         ->where(function ($q) {
        //             $q->whereBetween('lat', Arr::sort([request('lat1'), request('lat2')]))
        //                 ->orWhereBetween('long', Arr::sort([request('long1'), request('long2')]));
        //         })->where(function ($q) use ($season, $request) {
        //             $q->where(function ($q) use ($request) {
        //                 // $q->where('region_id', $request->region2);
        //             })->where(function ($query) use ($season) {
        //                 $query->where('seasons', 'like', '%' . $season . '%')->orWhere("seasons", "طوال العام");
        //             });
        //         })
        //         ->orderByRaw(DB::raw("
        //     ST_Distance_Sphere(
        //         point(places.long, places.lat),
        //         point(" . request('long2') . ", " . request('lat2') . ")
        //     )
        // "))->take($need)->get();
        //     $places = array_merge($places->toArray(), $new_places->toArray());
        // }



        $data = [];
        $ids = [];
        $items = [];
        $i = 0;

        $data = $placesPerDay->map(function ($chunk) {
            return array_values($chunk->toArray());
        })->toArray();

        // Extract the IDs from each chunk
        $ids = array_map(function ($dayPlaces) {
            return array_column($dayPlaces, 'id');
        }, $data);
        $items = array_merge([] , ...$ids);

        $items = $ids;
        $places = $data;
        $daterange = request('daterange');
        $date = $start_date;
        $type = request('type');
        $funny = request('funny_place_per_day', null);
        $region1Object = $region1;
        $region2Object = $region2;
        $region1 = request('region1', null);
        $lat1 = request('lat1', null);
        $long1 = request('long1', null);
        $lat2 = request('lat2', null);
        $long2 = request('long2', null);
        $region2 = request('region2', null);
        $selected_categories = implode(',', $categories);
        $token = Str::random(20);

        prepareTrip::create([
            'items' => json_encode($items),
            'places' => json_encode($places),
            'selected_categories' => json_encode($selected_categories),
            'start_date' => $start_date,
            'end_date' => $end_date,
            'funny' => $funny,
            'funny_place_per_day' => $funny_place_per_day,
            'days' => $days,
            'region1' => $region1,
            'region2' => $region2,
            'lat1' => $lat1,
            'long1' => $long1,
            'lat2' => $lat2,
            'long2' => $long2,
            'token' => $token,
            'user_id' => \Auth::guard('api')->id(),
        ]);

        return $this->success(compact(
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
            'daterange',
            'start_date',
            'end_date',
            'region1Object',
            'region2Object',
            'token',
        ) , 200 , 'prepare trip');
    }


    public static function getRoutePoints($origin, $destination)
    {


        $params = http_build_query([
            'origin' => $origin,
            'destination' => $destination,
            'sensor' => 'true',
            'units' => 'metric',
            'mode' => 'driving',
            'key' => 'AIzaSyDDSTB8emANyFuYPdfvIGCq_3e0ZZ6lKJc',
        ]);

        $ch = curl_init();

        curl_setopt_array($ch, array(
          CURLOPT_URL => 'https://maps.googleapis.com/maps/api/directions/json?' . $params,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('key' => 'AIzaSyDDSTB8emANyFuYPdfvIGCq_3e0ZZ6lKJc'),
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer 22|sw1GfcyzXnhJDhvyb1306CiBNp13xiMbPZlq4BnV1b18b51e'
          ),
        ));

        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result, true);
        $result = $result["routes"][0]["legs"][0]["steps"] ?? [];

        $data = [];

        foreach($result as $row) {
            $row["start_location"]["name"] = $row["html_instructions"];
            $data[] = $row["end_location"];
        };
        return $data;
    }


    public function view_trip($token)
    {
        $trip = Trip::where('token', $token)->first();
        if (!$trip) {
            return $this->error(404 , 'trip not found');
        }

        $places = [];

        $items = $trip->items;

        if (is_string($items)) {
            $items = json_decode($items, true);
        }

        if($items){
            foreach ($items as $key => $value) {
                $places[$key] = Place::with('region', 'city')->whereIn('id', $value)->get();
            }
        }

        $start_date = $trip->start_date;
        $end_date = $trip->end_date;
        $region1 = $trip->region1;
        $region2 = $trip->region2;
        $region1Object = Region::where('id', $trip->region1)->first();
        $region2Object = Region::where('id', $trip->region2)->first();
        $daterange = $date = $trip->date;

        $days = $trip->days;

        $funny = $funny_place_per_day = $days;
        $type = null;

        return $this->success(compact(
            'funny', 'type', 'places', 'trip', 'date', 'days',
            'daterange', 'funny_place_per_day', 'start_date', 'end_date', 'region1',
            'region2', 'region1Object', 'region2Object'
        ) , 200 , __('dashboard.trip_details'));
    }

    public function delete_trip($token)
    {
        Trip::where("token" , $token)->where("user_id", \Auth::guard('api')->id())->delete();
        return $this->ok(__('dashboard.trip_deleted_successfully'));
    }

    public function my_trips(Request $request)
    {
        $trips = Trip::where('user_id', \Auth::guard('api')->id())->orderBy('id', 'desc');

        if (request('search')) {
            $trips->where('name', 'like', '%' . $request->search . '%');
        }

        $trips = $trips->paginate(request('per_page' , 10));
        $trips = new TripCollection($trips);

        return $this->success(['trips'=>$trips] , 200 , __('dashboard.my_trips_list'));
    }

    public function get_prepare_trip(Request $request, $token)
    {
        $prepareTrip = prepareTrip::where("token" , $token)->where("user_id", \Auth::guard('api')->id())->first();

        if (!$prepareTrip) {
            return null;
        }

        return $this->success(new PrepareTripResource($prepareTrip),200, __('dashboard.prepared_trip_details'));
    }

    public function delete_prepare_trip(Request $request, $token)
    {
        $prepareTrip = prepareTrip::where("token" , $token)->where("user_id", \Auth::guard('api')->id())->first();

        if (! $prepareTrip) {
            return $this->error(404 , __('dashboard.trip_not_found'));
        }

        $prepareTrip->delete();

        return $this->ok(__('dashboard.prepared_trip_deleted_successfully'));
    }

    public function get_my_trips(Request $request)
    {
        $trips = prepareTrip::where('user_id', \Auth::guard('api')->id())->orderBy('id', 'desc');

        if (request('search')) {
            $trips->where('name', 'like', '%' . $request->search . '%');
        }

        $trips = $trips->paginate(request('per_page' , 10));
        $trips = new PrepareTripCollection($trips);

        return $this->success([ 'trips' => $trips ], 200 , __('dashboard.my_trips_list'));
    }
}
