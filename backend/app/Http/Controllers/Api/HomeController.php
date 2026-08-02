<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Guides\GuideCollection;
use App\Http\Resources\Swalefs\SwalefCollection;
use App\Models\Event;
use App\Models\Guide;
use App\Models\Place;
use App\Models\Store;
use App\Models\Setting;
use App\Models\MostVisit;
use App\Models\Swalef;
use App\Models\ZadElgadel;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\Zads\ZadCollection;
use App\Http\Resources\Events\EventCollection;
use App\Http\Resources\Places\PlaceCollection;
use App\Http\Resources\Stores\StoreCollection;
use App\Http\Resources\Services\ServiceResource;
use App\Http\Resources\Applications\ApplicationCollection;

class HomeController extends ApiModalController
{
    public function getHome(Request $request)
    {
        visit(['ip' => \request()->ip() ?? random_int(1000000000, 9999999999), 'page' => 'home', 'visits' => 1]);

        $cacheKey = 'home_data';
        $cacheTime = 60 * 60; // Cache for 2 hours (adjust as needed)

        // Attempt to retrieve cached data
        $responseData = Cache::remember($cacheKey, $cacheTime, function () use ($request){
            return $this->fetchHomeData($request);
        });

        return $this->success($responseData, 200 , __('dashboard.home_data'));
    }

    private function fetchHomeData(Request $request)
    {

        //---------------------------------------------------------------------
        //-------------------------  get places -------------------------------
        //---------------------------------------------------------------------
        $places = Place::with('ratings', 'galleries', 'city', 'region', 'ceo')
            ->where('active', 1)->where('prefered', 1);

        // Get location coordinates for distance sorting
        $location = getLocationCoords();
        if ($location['hasLocation']) {
            $places = applyDistanceSorting($places, $location['lat'], $location['lng']);
        } else {
            applyListingOrder($places);
        }

        $places = $places->paginate(request('places_per_page' , 14));
        $places = new PlaceCollection($places);

        //---------------------------------------------------------------------
        //-------------------------  get top visited places -------------------
        //---------------------------------------------------------------------
        $topVisitedPlaces = Place::with('ratings', 'galleries', 'city', 'region', 'ceo')
            ->where('active', 1);

        // Apply distance sorting if location is available, otherwise sort by views
        if ($location['hasLocation']) {
            $topVisitedPlaces = applyDistanceSorting($topVisitedPlaces, $location['lat'], $location['lng']);
        } else {
            $topVisitedPlaces->orderBy('views_num', 'DESC')
                ->orderBy('order_id', 'asc')
                ->orderBy('id', 'asc');
        }

        $topVisitedPlaces = $topVisitedPlaces->paginate(request('top_visited_places_per_page' , 16));
        $topVisitedPlaces = new PlaceCollection($topVisitedPlaces);

        //---------------------------------------------------------------------
        //-------------------------  get top events -------------------------------
        //---------------------------------------------------------------------

        $topEvents = Event::with('ratings', 'galleries', 'city', 'region', 'ceo')
            ->where('active', 1)->where('date_to', '>', Carbon::today())
            ->orderBy('views_num', 'DESC')
            ->orderBy('order_id', 'asc')
            ->orderBy('id', 'asc');
        $topEvents = $topEvents->paginate(request('top_events_per_page' , 7))->onEachSide(2);

        $topEvents = new EventCollection($topEvents);
        //---------------------------------------------------------------------
        //-------------------------  get Zad Elgadel ( restaurants) -----------
        //---------------------------------------------------------------------

        $zads = ZadElgadel::select('zad_elgadels.*')->with('ratings', 'galleries', 'city', 'region', 'ceo')
            ->where('active', 1);

        // Apply distance sorting if location is available
        if ($location['hasLocation']) {
            $zads = applyDistanceSorting($zads, $location['lat'], $location['lng']);
        } else {
            applyListingOrder($zads);
        }

        $zads = $zads->paginate(request('zad_per_page' , 6));

        $zads = new ZadCollection($zads);

        //---------------------------------------------------------------------
        //-------------------------  get top visited stores -------------------
        //---------------------------------------------------------------------

        $topVisitedStores = Store::with('ratings', 'galleries', 'city', 'region', 'ceo')
            ->where('active', 1);

        // Apply distance sorting if location is available, otherwise sort by views
        if ($location['hasLocation']) {
            $topVisitedStores = applyDistanceSorting($topVisitedStores, $location['lat'], $location['lng']);
        } else {
            $topVisitedStores->orderBy('views_num', 'DESC')
                ->orderBy('order_id', 'asc')
                ->orderBy('id', 'asc');
        }

        $topVisitedStores = $topVisitedStores->paginate(request('top_stores_per_page' , 10));
        $topVisitedStores = new StoreCollection($topVisitedStores);

        //---------------------------------------------------------------------
        //-------------------------  get Applications -------------------------
        //---------------------------------------------------------------------

        $applications = Application::where('active', 1);

        if (request('search')) {
            $applications = $applications->where(function ($q) use ($request) {
                $q->whereTranslationLike('title', '%' . $request->search . '%')
                    ->orWhereTranslationLike('description', '%' . $request->search . '%');
            });
        }

        $applications = applyListingOrder($applications)->paginate(request('application_per_page' , Application::count() ))->onEachSide(2);

        $applications = new ApplicationCollection($applications);

        //---------------------------------------------------------------------
        //-------------------------  get swalefs -------------------------
        //---------------------------------------------------------------------

        $swalefs = Swalef::where('show_in_home', 1);

        if (request('search')) {
            $swalefs = $swalefs->where(function ($q) use ($request) {
                $q->whereTranslationLike('title', '%' . $request->search . '%')
                    ->orWhereTranslationLike('description', '%' . $request->search . '%');
            });
        }

        $swalefs = applyListingOrder($swalefs)->paginate(request('application_per_page' , Swalef::count() ))->onEachSide(2);

        $swalefs = new SwalefCollection($swalefs);

        //---------------------------------------------------------------------
        //-------------------------  get Guides -------------------------
        //---------------------------------------------------------------------

        $guides = Guide::where('show_in_home', 1);
//        $guides = Guide::query();

        if (request('search')) {
            $guides = $guides->where(function ($q) use ($request) {
                $q->whereTranslationLike('name', '%' . $request->search . '%')
                    ->orWhereTranslationLike('description', '%' . $request->search . '%');
            });
        }

        $guides = applyListingOrder($guides)->paginate(request('guide_per_page' , Guide::count() ))->onEachSide(2);

        $guides = new GuideCollection($guides);

        //---------------------------------------------------------------------
        //-------------------------  get Services -------------------------
        //---------------------------------------------------------------------

        $services = Setting::where('group', 'main_services')->where('type', 'repeater')->get();
        $services = ServiceResource::collection($services);

        //---------------------------------------------------------------------
        //-------------------------  get global map data -------------------------
        //---------------------------------------------------------------------

        $globalMapData = MostVisit::selectRaw("country_code, floor(count(*) * 10) as count, country as name")
            ->groupBy('country_code')
            ->get();


        //---------------------------------------------------------------------
        //-------------------------  get map data -------------------------
        //---------------------------------------------------------------------

        $MapData = MostVisit::selectRaw("count(*) as count ,latitude,longitude, country as name")
            ->groupBy('country')->get();

        //---------------------------------------------------------------------
        //-------------------------  response ---------------------------------
        //---------------------------------------------------------------------

        return [
            'places' => $places,
            'topVisitedPlaces' => $topVisitedPlaces,
            'topEvents' => $topEvents,
            'zads' => $zads,
            'topVisitedStores' => $topVisitedStores,
            'applications' => $applications,
            'swalefs' => $swalefs,
            'guides' => $guides,
            'services' => $services,
            'globalMapData' => $globalMapData,
            'MapData' => $MapData,
        ];
    }
}
