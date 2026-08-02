<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Events\EventCollection;
use App\Http\Resources\Places\PlaceCollection;
use App\Http\Resources\Stores\StoreCollection;
use App\Http\Resources\Zads\ZadCollection;
use App\Models\Event;
use App\Models\Place;
use App\Models\Store;
use App\Models\ZadElgadel;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PropertiesController extends ApiModalController
{
    public function index(Request $request)
    {
        $user_id = \Auth::guard('api')->id();

        $places = Place::with('ratings', 'galleries', 'city', 'region', 'ceo')->where('added_by_user', 1)->where('user_id', $user_id);
        $stores = Store::with('ratings', 'galleries', 'city', 'region', 'ceo')->where('added_by_user', 1)->where('user_id', $user_id);
        $events = Event::with('ratings', 'galleries', 'city', 'region', 'ceo')->where('added_by_user', 1)->where('user_id', $user_id);
        $zad_elgadels = ZadElgadel::with('ratings', 'galleries', 'city', 'region', 'ceo')->where('added_by_user', 1)->where('user_id', $user_id);

        $places = applyListingOrder($places)->paginate(request('per_page' , 1000));
        $places = new PlaceCollection($places);

        $stores = applyListingOrder($stores)->paginate(request('per_page' , 1000));
        $stores = new StoreCollection($stores);

        $events = applyListingOrder($events)->paginate(request('per_page' , 1000));
        $events = new EventCollection($events);

        $zad_elgadels = applyListingOrder($zad_elgadels)->paginate(request('per_page' , 1000));
        $zad_elgadels = new ZadCollection($zad_elgadels);

        return $this->success([
            'places' => $places,
            'stores' => $stores,
            'events' => $events,
            'zad_elgadels' => $zad_elgadels,
        ],200,'Lis of my properties');
    }

    public function store(Request $request)
    {
        // Determine the translation table based on type
        $type = $request->type ?? '';
        $translationTable = 'place_translations'; // default
        if ($type == 'store') {
            $translationTable = 'store_translations';
        } elseif ($type == 'event') {
            $translationTable = 'event_translations';
        } elseif ($type == 'zad') {
            $translationTable = 'zad_elgadel_translations';
        }

        $rules = [
            'type' => ['required', 'string', 'in:place,store,event,zad'],
            'title' => ['required', Rule::unique($translationTable, 'title')],
            'description' => ['required'],
            'categories' => ['required_if:type,place,store'],
            'categories.*' => ['required_if:type,place,store'],
            'image' => ['required'],
            'whatsapp' => ['nullable'],
            // 'facebook_link' => ['nullable'],
            'x_link' => ['nullable'],
            'instagram_link' => ['nullable'],
            'website_link' => ['nullable'],
            'price_id' => ['nullable', Rule::exists('prices', 'id')],
            'seasons' => ['nullable', 'array', Rule::in('spring', 'summer', 'winter', 'fall' , 'all_year')],
            'ticket_link' => ['nullable'],
            'menu_file' => ['required_if:type,zas'],
            'food_categories' => ['nullable', 'array'],
            'food_categories.*' => ['nullable', Rule::exists('food_category_of_zads', 'id')],
            'date_from' => ['required_if:type,event', 'date', 'after_or_equal:today'],
            'date_to' => ['required_if:type,event', 'date', 'after:date_from'],
            'video_url' => ['nullable', 'url'],
            'ownership_proof_file' => ['required', 'mimes:pdf,doc,docx,jpg,jpeg,png,gif,svg'],
        ];

        // For stores, add con_type specific validation
        if ($request->type == 'store') {
            $rules['con_type'] = ['required', 'in:online,local'];
            $rules['address_type'] = ['required_if:con_type,local', 'in:link,map'];
            $rules['lat'] = ['nullable'];
            $rules['long'] = ['nullable'];
            $rules['address'] = ['nullable'];
            $rules['region_id'] = ['required_if:con_type,local', 'nullable', Rule::exists('regions', 'id')];
            $rules['city_id'] = ['required_if:con_type,local', 'nullable', Rule::exists('cities', 'id')];
        } else {
            // For other types, keep old validation
            $rules['lat'] = ['required'];
            $rules['long'] = ['required'];
            $rules['address'] = ['nullable'];
            $rules['region_id'] = ['required', Rule::exists('regions', 'id')];
            $rules['city_id'] = ['required', Rule::exists('cities', 'id')];
        }

        $validator = Validator::make($request->all(), $rules, [
                'type' => trans('dashboard.type'),
                'title' => trans('dashboard.title'),
                'description' => trans('dashboard.description'),
                'categories' => trans('dashboard.categories'),
                'categories.*' => trans('dashboard.categories'),
                'lat' => trans('dashboard.lat'),
                'long' => trans('dashboard.long'),
                'address' => trans('dashboard.address'),
                'region_id' => trans('dashboard.region_id'),
                'city_id' => trans('dashboard.city_id'),
                'con_type' => trans('dashboard.con_type'),
                'address_type' => trans('dashboard.address_type'),
                'image' => trans('dashboard.image'),
                'whatsapp' => trans('dashboard.whatsapp'),
                // 'facebook_link' => trans('dashboard.facebook_link'),
                'x_link' => trans('dashboard.x_link'),
                'instagram_link' => trans('dashboard.instagram_link'),
                'website_link' => trans('dashboard.website_link'),
                'price_id' => trans('dashboard.price_id'),
                'seasons' => trans('dashboard.seasons'),
                'ticket_link' => trans('dashboard.ticket_link'),
                'menu_file' => trans('dashboard.menu_file'),
                'food_categories' => trans('dashboard.food_categories'),
                'food_categories.*' => trans('dashboard.food_categories'),
                'date_from' => trans('dashboard.date_from'),
                'date_to' => trans('dashboard.date_to'),
                'video_url' => trans('dashboard.video_url'),
                'ownership_proof_file' => trans('dashboard.ownership_proof_file'),
        ]);

        if ($validator->fails()) {
            return $this->error(422, $validator->errors()->first());
        }

        $this->createPropertByType($request->type);

        return $this->success([],200, __('dashboard.message_sent_successfully'));
    }

    public function createPropertByType($type)
    {
        switch ($type) {
            case 'place':
                $this->store_place();
                break;
            case 'store':
                $this->store_store();
                break;
            case 'event':
                $this->store_event();
                break;
            case 'zad':
                $this->store_zad_elgadel();
                break;
        }
    }

    public function autoSetData()
    {
        $data = [];
        $data['active'] = 0;
        $data['status'] = 'pending';
//        $data['show_in_home'] = 0;
        // Don't set address_type by default - it will be set based on con_type for stores
        if (request('type') != 'store') {
            $data['address_type'] = 'map';
        }
        $data['added_by_user'] = 1;
        $data['user_id'] = \Auth::guard('api')->id();

        // Handle image upload based on type (stores will handle their own image in store_store)
        // Image will be handled after model creation using medialibrary

        if (request()->hasFile('ownership_proof_file')) {
            $data['ownership_proof_file'] = UploadService::store(\request('image'), 'ownership_proof_file');
        }

        if (request('categories')) {
            $data['categories'] = collect(\request('categories'))->map(fn($i) => (int)$i);
        }

        return $data;
    }

    public function store_place()
    {
        $autoSetData = $this->autoSetData();

        $data = array_merge($autoSetData, request()->only(
            'title', 'description', 'lat', 'long', 'address', 'whatsapp', /* 'facebook_link', */ 'x_link', 'instagram_link', 'website_link',
            'region_id', 'city_id', 'price_id',
        ));

        if (\request('seasons')) {
            $data['seasons'] = is_array(\request('seasons')) ? implode(',', collect(\request('seasons'))->map(function ($i) {
                return __('dashboard.' . $i, [], 'ar');
            })->toArray()) : null;
        }

        $place = Place::create($data);

        if (request()->hasFile('image') && request('type') != 'store') {
            $place->clearMediaCollection('image');
            $place->addMediaFromRequest('image')->toMediaCollection('image');
        }

        return $place;
    }

    public function store_store()
    {
        $autoSetData = $this->autoSetData();

        $data = array_merge($autoSetData, request()->only(
            'title', 'description', 'whatsapp', 'facebook_link', 'instagram_link', 'website_link',
        ));

        // Handle con_type (online/local) and convert to is_online
        if (isset(request()->con_type)) {
            $data['is_online'] = (request()->con_type == 'online') ? 1 : 0;
        }

        // Handle address_type and location data based on con_type
        if (isset(request()->con_type) && request()->con_type == 'local') {
            // If con_type is 'local', use address_type from request (could be 'link' or 'map')
            if (request()->address_type == 'map') {
                // If explicitly set to 'map', use map logic
                $data['address_type'] = 'map';
                $data['address'] = request('address_place') ?? request('address');
                $data['lat'] = request('lat') ?: null;
                $data['long'] = request('long') ?: null;
            } else {
                // Default to 'link' if not 'map'
                $data['address_type'] = 'link';
                $data['address'] = request('address');
                $link = explode('@', $data['address']);
                if (is_array($link)) {
                    $lat_long = explode(',', end($link));
                    if (is_array($lat_long) && count($lat_long) == 3) {
                        $data['lat'] = isset($lat_long[0]) && is_numeric($lat_long[0]) ? $lat_long[0] : null;
                        $data['long'] = isset($lat_long[1]) && is_numeric($lat_long[1]) ? $lat_long[1] : null;
                    }
                }
            }
            // Set region_id and city_id if provided
            if (request()->has('region_id')) {
                $data['region_id'] = request('region_id');
            }
            if (request()->has('city_id')) {
                $data['city_id'] = request('city_id');
            }
        } elseif (isset(request()->con_type) && request()->con_type == 'online') {
            // If online, ensure lat and long are cleared and address_type is link
            $data['address_type'] = 'link';
            $data['address'] = request('address');
            $data['lat'] = null;
            $data['long'] = null;
            // region_id and city_id are optional for online stores
            if (request()->has('region_id')) {
                $data['region_id'] = request('region_id');
            }
            if (request()->has('city_id')) {
                $data['city_id'] = request('city_id');
            }
        } else {
            // Fallback for backward compatibility (if con_type not provided)
            if (request()->address_type == 'map') {
                $data['address_type'] = 'map';
                $data['address'] = request('address_place') ?? request('address');
                $data['lat'] = request('lat') ?: null;
                $data['long'] = request('long') ?: null;
            } elseif (request()->address_type == 'link') {
                $data['address_type'] = 'link';
                $data['address'] = request('address');
                $link = explode('@', $data['address']);
                if (is_array($link)) {
                    $lat_long = explode(',', end($link));
                    if (is_array($lat_long) && count($lat_long) == 3) {
                        $data['lat'] = isset($lat_long[0]) && is_numeric($lat_long[0]) ? $lat_long[0] : null;
                        $data['long'] = isset($lat_long[1]) && is_numeric($lat_long[1]) ? $lat_long[1] : null;
                    }
                }
            } else {
                $data['address_type'] = 'link';
                $data['address'] = request('address');
                $data['lat'] = request('lat') ?: null;
                $data['long'] = request('long') ?: null;
            }
            if (request()->has('region_id')) {
                $data['region_id'] = request('region_id');
            }
            if (request()->has('city_id')) {
                $data['city_id'] = request('city_id');
            }
        }

        $store = Store::create($data);

        if (request()->hasFile('image')) {
            $store->clearMediaCollection('image');
            $store->addMediaFromRequest('image')->toMediaCollection('image');
        }
    }
    public function store_event()
    {
        $autoSetData = $this->autoSetData();

        $data = array_merge($autoSetData, request()->only(
            'title', 'description', 'lat', 'long', 'address', 'whatsapp', /* 'facebook_link', */ 'x_link', 'instagram_link', 'website_link',
            'region_id', 'city_id', 'video_url', 'ticket_link', 'date_from', 'date_to',
        ));

        $event = Event::create($data);

        if (request()->hasFile('image')) {
            $event->clearMediaCollection('image');
            $event->addMediaFromRequest('image')->toMediaCollection('image');
        }
    }
    public function store_zad_elgadel()
    {
        $autoSetData = $this->autoSetData();

        if (isset($autoSetData['categories'])) {
            unset($autoSetData['categories']);
        }

        $data = array_merge($autoSetData, request()->only(
            'title', 'description', 'lat', 'long', 'address', 'whatsapp', /* 'facebook_link', */ 'x_link', 'instagram_link', 'website_link',
            'region_id', 'city_id', 'menu_file',
        ));

//        try {
//            DB::beginTransaction();
            $record = ZadElgadel::create($data);

            if (request()->hasFile('image')) {
                $record->clearMediaCollection('image');
                $record->addMediaFromRequest('image')->toMediaCollection('image');
            }

//            if (request('categories')) {
//                $record->categories()->attach(request('categories'));
//            }

            if (request('food_categories')) {
                $record->foodCategories()->attach(request('food_categories'));
            }

//            DB::commit();

            return $this->success($record, 201, __('dashboard.zad_created_successfully'));

//        } catch (\Throwable $th) {
//            DB::rollBack();
//            return $this->error(500, 'Error creating Zad Elgadel: ' . $th->getMessage());
//        }
    }
}
