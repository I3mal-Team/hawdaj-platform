<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Stores\StoreResource;
use App\Http\Resources\Stores\StoreCollection;
use App\Models\Store;
use Illuminate\Http\Request;
use DB;
use App\Models\Place;
use App\Http\Resources\Places\PlaceCollection;

class StoreController extends ApiModalController
{
    public function stats(Request $request)
    {
//        $stores = DB::table('stores')->selectRaw("
//            SUM((CASE WHEN is_online = 0 THEN 1 END)) as total_offlines ,
//            SUM((CASE WHEN is_online = 1 THEN 1 END)) as total_onlines
//        ")
//        ->where('active', 1)
//        ->first();

        $stores = DB::table('stores')->selectRaw("
            SUM(CASE WHEN is_online = 0 THEN 1 ELSE 0 END) as total_offlines,
            SUM(CASE WHEN is_online = 1 THEN 1 ELSE 0 END) as total_onlines
        ")
            ->where('active', 1)
            ->first();

        return $this->success(compact('stores'),200, __('dashboard.stats_of_stores'));
    }

    public function index(Request $request)
    {
        $stores = Store::with('ratings', 'galleries', 'city', 'region', 'ceo')->where('active', 1);

        // Get location coordinates for distance sorting
        $location = getLocationCoords();
        $lat = $location['lat'];
        $lng = $location['lng'];
        $shouldSortByDistance = $location['hasLocation'];

        if (request('search')) {
            $stores = $stores->where(function ($q) use ($request) {
                $q->whereTranslationLike('title', '%' . $request->search . '%')
                    ->orWhereTranslationLike('description', '%' . $request->search . '%');
            });
        }

        if (request('category_id')) {
            $categories_id = explode(',' , request('category_id'));
            $stores->where(function($query)use($categories_id){
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

            $stores = $stores
                ->whereBetween('lat', [$lower_latitude, $upper_latitude])
                ->whereBetween('long', [$lower_longitude, $upper_longitude]);
        }

        if (request('region_id')) {
            $stores = $stores->where('region_id', request('region_id'));
        }

        if (request('city_id')) {
            $stores = $stores->where('city_id', request('city_id'));
        }

        if (request('address_type')) {
            $stores = $stores->where('address_type', request('address_type'));
        }

        if (request('is_online') !== null) {
            $isOnline = request('is_online') == 1;
            $stores = $stores->where('is_online', $isOnline);
        }

        // Apply distance sorting if location is available (closest to farthest)
        if ($shouldSortByDistance) {
            $stores = applyDistanceSorting($stores, $lat, $lng);
        } else {
            if (request('top_visited')) {
                $stores->orderBy('views_num', 'DESC')
                    ->orderBy('order_id', 'asc')
                    ->orderBy('id', 'asc');
            }

            if (request('top_featured')) {
                $stores->where('featured', true);
            }

            if (! request('top_visited')) {
                applyListingOrder($stores);
            }
        }

        $stores = $stores->paginate(request('per_page', 10));
        $stores = new StoreCollection($stores);

        return $this->success($stores, 200, __('dashboard.list_of_stores'));
    }

    public function show($slug)
    {
        $store = Store::where("slug" , $slug)->firstOrFail();

        return $this->success(new StoreResource($store),200, __('dashboard.store_details'));
    }

    /**
     * list places with optional filter function
     *
     * @param IndexRequest $request
     * @return StoreResource
     */
    public function related_stores($slug)//: AnonymousResourceCollection
    {
        $store = Store::select("related_stores")->where("slug" , $slug)->firstOrFail();
        $storesQuery = Store::whereIn('id',$store->related_stores ?? []);
        
        // Get location coordinates for distance sorting
        $location = getLocationCoords();
        if ($location['hasLocation']) {
            $storesQuery = applyDistanceSorting($storesQuery, $location['lat'], $location['lng']);
        } else {
            applyListingOrder($storesQuery);
        }

        $stores = $storesQuery->paginate(request('per_page' , 10));
        $stores = new StoreCollection($stores);
        return $this->success($stores,200, __('dashboard.related_stores'));
    }

}
