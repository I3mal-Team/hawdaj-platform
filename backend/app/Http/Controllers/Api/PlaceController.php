<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Places\IndexRequest;
use App\Http\Resources\Places\PlaceResource;
use App\Http\Resources\Places\PlaceCollection;
use App\Models\Place;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PlaceController extends ApiModalController
{
    /**
     * list places with optional filter function
     *
     * @param IndexRequest $request
     * @return PlaceResource
     */
    public function index(IndexRequest $request)//: AnonymousResourceCollection
    {
        $places = Place::with('ratings', 'galleries', 'city', 'region', 'ceo')->where('active', 1);

        // Get location coordinates for distance sorting
        $location = getLocationCoords();
        $lat = $location['lat'];
        $lng = $location['lng'];
        $shouldSortByDistance = $location['hasLocation'];

        if (request('search')) {
            $places = $places->where(function ($q) use ($request) {
                $q->whereTranslationLike('title', '%' . $request->search . '%');
//                    ->orWhereTranslationLike('description', '%' . $request->search . '%');
            });
        }

        if (request('city_id')) {
            $places = $places->where("city_id", request('city_id'));
        }

        if (request('region_id')) {
            $places = $places->where("region_id", request('region_id'));
        }

        if (request('category_id')) {
            $categories_id = explode(',' , request('category_id'));
            $places->where(function($query)use($categories_id){
                foreach ($categories_id as $category_id) {
                    $query->orWhereRaw("JSON_CONTAINS(categories, '" . $category_id . "' )");
                }
            });
        }

        if (request('sub_category_id')) {
            $categories_id = explode(',' , request('category_id'));
            $places->where(function($query)use($categories_id){
                foreach ($categories_id as $category_id) {
                    $query->orWhereRaw("JSON_CONTAINS(categories, '" . $category_id . "' )");
                }
            });
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

        // Apply distance sorting if location is available (closest to farthest)
        if ($shouldSortByDistance) {
            $places = applyDistanceSorting($places, $lat, $lng);
        } else {
            if (request('top_visited')) {
                $places->orderBy('views_num', 'DESC')
                    ->orderBy('order_id', 'asc')
                    ->orderBy('id', 'asc');
            }

            if (request('top_featured')) {
                $places->where('featured', true);
            }

            if (! request('top_visited')) {
                applyListingOrder($places);
            }
        }

        $places = $places->paginate(request('per_page', 10));
        $places = new PlaceCollection($places);
        return $this->success($places, 200, __('dashboard.list_of_places'));
    }

    /**
     * list places with optional filter function
     *
     * @param IndexRequest $request
     * @return PlaceResource
     */
    public function related_places($slug)//: AnonymousResourceCollection
    {
        $place = Place::select("related_places")->where("slug" , $slug)->firstOrFail();
        $placesQuery = Place::whereIn('id',$place->related_places ?? []);
        
        // Get location coordinates for distance sorting
        $location = getLocationCoords();
        if ($location['hasLocation']) {
            $placesQuery = applyDistanceSorting($placesQuery, $location['lat'], $location['lng']);
        } else {
            applyListingOrder($placesQuery);
        }

        $places = $placesQuery->paginate(request('per_page' , 10));
        $places = new PlaceCollection($places);
        return $this->success($places,200, __('dashboard.related_places'));
    }

    /**
     * Show Place
     *
     * @param Place $place
     * @return JsonResource
     */
    public function show($slug)
    {
        $place = Place::where("slug" , $slug)->firstOrFail();
        return $this->success(new PlaceResource($place),200, __('dashboard.place_details'));
    }

    public function placesDataForMap()
    {
        $placesQuery = Place::where(function ($q) {
                $q->when(request('search') , function($q){
                    $q->whereTranslationLike('title', '%' . request('search') . '%')
                    ->orWhereTranslationLike('description', '%' . request('search') . '%');
                });
            })
            ->whereNotNull('lat')
            ->whereNotNull('long')
            ->with('ratings', 'city', 'region');
        
        // Get location coordinates for distance sorting (important for map view)
        $location = getLocationCoords();
        if ($location['hasLocation']) {
            $placesQuery = applyDistanceSorting($placesQuery, $location['lat'], $location['lng']);
        } else {
            applyListingOrder($placesQuery);
        }

        $places_data_for_map = $placesQuery->paginate(request('per_page' , 10));
        $places = new PlaceCollection($places_data_for_map);
        return $this->success($places,200, __('dashboard.list_of_places'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'unique:place_translations,title'],
            'description' => ['required'],
            'image' => ['required'],
            'categories' => ['required'],
            'categories.*' => ['required', Rule::exists('categories', 'id')],
            'region_id' => ['required', Rule::exists('regions', 'id')],
            'city_id' => ['required', Rule::exists('cities', 'id')],
            'price_id' => ['required', Rule::exists('prices', 'id')],
            'seasons' => ['required', 'array', Rule::in('spring', 'summer', 'winter', 'fall' , 'all_year')],
            'lat' => ['required'],
            'long' => ['required'],
            'address' => ['nullable'],
            // 'facebook_link' => ['nullable'],
            'x_link' => ['nullable'],
            'whatsapp' => ['nullable'],
            'instagram_link' => ['nullable'],
            'website_link' => ['nullable'],
            'ticket_link' => ['nullable'],

//            'address_type' => ['required', Rule::in('link', 'map', 'latlong')],
            ], [
            'title.required' => 'العنوان مطلوب',
            'title.unique' => 'العنوان موجود بالفعل',
            'description.required' => 'الوصف مطلوب',
            'image.required' => 'الصورة مطلوبة',
            'categories.required' => 'التصنيفات مطلوبة',
            'categories.*.required' => 'التصنيفات مطلوبة',
            'region_id.required' => 'المنطقة مطلوبة',
            'city_id.required' => 'المدينة مطلوبة',
            'price_id.required' => 'السعر مطلوب',
            'seasons.required' => 'المواسم مطلوبة',
            'address_type.required' => 'نوع العنوان مطلوب',
        ]);

        if ($validator->fails()) {
            return $this->error(422, $validator->errors()->first());
        }

        $data = $request->all();
        $data['active'] = 0;
        $data['show_in_home'] = 0;
        $data['address_type'] = 'map';

        if (request()->hasFile('image')) {
        }

        if (request('categories')) {
            $data['categories'] = collect($request->categories)->map(fn($i) => (int)$i);
        }

        if (request('seasons')) {
            $data['seasons'] = is_array($request->seasons) ? implode(',', collect($request->seasons)->map(function ($i) {
                return __('dashboard.' . $i, [], 'ar');
            })->toArray()) : null;
        }

        $data['user_id'] = Auth::guard('api')->id();

        $place = Place::create($data);

        if (request()->hasFile('image')) {
            $place->clearMediaCollection('image');
            $place->addMediaFromRequest('image')->toMediaCollection('image');
        }

        return $this->success([],200, __('dashboard.message_sent_successfully'));
    }
}
