<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Places\IndexRequest;
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
use App\Http\Resources\Search\SearchCollection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchController extends ApiModalController
{

    public function search(Request $request)
    {
        // Extract and validate request parameters
        $viewAs = $this->getViewAsTypes($request);
        $search = $request->get('search');
        $regionId = $request->get('region_id');
        $cityId = $request->get('city_id');
        $location = getLocationCoords();

        // Initialize result collections
        $results = collect([]);

        // Search each requested type
        if (in_array('events', $viewAs)) {
            $results = $results->merge($this->searchEvents($search, $regionId, $cityId, $location));
        }

        if (in_array('stores', $viewAs)) {
            $results = $results->merge($this->searchStores($search, $regionId, $cityId, $location));
        }

        if (in_array('zads', $viewAs)) {
            $results = $results->merge($this->searchZads($search, $regionId, $cityId, $location));
        }

        if (in_array('places', $viewAs)) {
            $results = $results->merge($this->searchPlaces($search, $regionId, $cityId, $location));
        }

        // Paginate results
        $perPage = request('per_page', 10);
        $page = request('page', 1);
        $paginatedResults = new LengthAwarePaginator(
            $results->forPage($page, $perPage),
            $results->count(),
            $perPage,
            $page
        );

        return $this->success(
            new SearchCollection($paginatedResults),
            200,
            'result of search'
        );
    }

    /**
     * Get view as types from request or return default
     *
     * @param Request $request
     * @return array
     */
    private function getViewAsTypes(Request $request): array
    {
        $viewAs = $request->get('viewAs');
        
        if ($viewAs) {
            return explode(',', $viewAs);
        }

        return ['places', 'stores', 'events', 'zads'];
    }

    /**
     * Search events
     *
     * @param string|null $search
     * @param int|null $regionId
     * @param int|null $cityId
     * @return \Illuminate\Support\Collection
     */
    private function searchEvents(?string $search, ?int $regionId, ?int $cityId, array $location)
    {
        $query = Event::with('ratings', 'city', 'region')
            ->where('active', 1);

        $this->applyRegionFilter($query, $regionId);
        $this->applyCityFilter($query, $cityId);
        $this->applySearchFilter($query, $search);

        if ($location['hasLocation']) {
            applyDistanceSorting($query, $location['lat'], $location['lng']);
        } else {
            $orderByClause = "
                CASE
                    WHEN date_from <= NOW() AND date_to >= NOW() THEN 1
                    WHEN date_from > NOW() THEN 2
                    ELSE 3
                END, date_from ASC
            ";
            $query->orderByRaw($orderByClause)
                ->orderBy('events.order_id', 'asc')
                ->orderBy('events.id', 'asc');
        }

        return $query->get();
    }

    /**
     * Search stores
     *
     * @param string|null $search
     * @param int|null $regionId
     * @param int|null $cityId
     * @param array $location
     * @return \Illuminate\Support\Collection
     */
    private function searchStores(?string $search, ?int $regionId, ?int $cityId, array $location)
    {
        $query = Store::with('ratings', 'city', 'region')
            ->where('active', 1);

        $this->applyRegionFilter($query, $regionId);
        $this->applyCityFilter($query, $cityId);
        $this->applySearchFilter($query, $search);
        $this->applyDistanceSorting($query, $location);

        return $query->get();
    }

    /**
     * Search zads (restaurants)
     *
     * @param string|null $search
     * @param int|null $regionId
     * @param int|null $cityId
     * @param array $location
     * @return \Illuminate\Support\Collection
     */
    private function searchZads(?string $search, ?int $regionId, ?int $cityId, array $location)
    {
        $query = ZadElgadel::with('ratings', 'city', 'region')
            ->where('active', 1);

        $this->applyRegionFilter($query, $regionId);
        $this->applyCityFilter($query, $cityId);
        $this->applySearchFilter($query, $search);
        $this->applyDistanceSorting($query, $location);

        return $query->get();
    }

    /**
     * Search places
     *
     * @param string|null $search
     * @param int|null $regionId
     * @param int|null $cityId
     * @param array $location
     * @return \Illuminate\Support\Collection
     */
    private function searchPlaces(?string $search, ?int $regionId, ?int $cityId, array $location)
    {
        $query = Place::with('ratings', 'city', 'region')
            ->where('active', 1);

        $this->applyRegionFilter($query, $regionId);
        $this->applyCityFilter($query, $cityId);
        $this->applySearchFilter($query, $search);
        $this->applyDistanceSorting($query, $location);

        return $query->get();
    }

    /**
     * Apply region filter to query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|null $regionId
     * @return void
     */
    private function applyRegionFilter($query, ?int $regionId): void
    {
        if ($regionId) {
            $query->where('region_id', $regionId);
        }
    }

    /**
     * Apply city filter to query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|null $cityId
     * @return void
     */
    private function applyCityFilter($query, ?int $cityId): void
    {
        if ($cityId) {
            $query->where('city_id', $cityId);
        }
    }

    /**
     * Apply search filter to query (title and description)
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $search
     * @return void
     */
    private function applySearchFilter($query, ?string $search): void
    {
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereTranslationLike('title', '%' . $search . '%')
                  ->orWhereTranslationLike('description', '%' . $search . '%');
            });
        }
    }

    /**
     * Apply distance sorting if location is available
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $location
     * @return void
     */
    private function applyDistanceSorting($query, array $location): void
    {
        if ($location['hasLocation']) {
            applyDistanceSorting($query, $location['lat'], $location['lng']);
        } else {
            applyListingOrder($query);
        }
    }

    public function global_map_data(){
        $data = MostVisit::selectRaw("country_code , count(*) * 1.5 as count , country as name")->groupBy('country_code')->get();
        return $this->success($data,200, __('dashboard.map_data'));
    }

}
