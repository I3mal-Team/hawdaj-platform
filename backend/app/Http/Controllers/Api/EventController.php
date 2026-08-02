<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Resources\Events\EventResource;
use App\Http\Resources\Events\EventCollection;

class EventController extends ApiModalController
{
    public function index(Request $request)
    {
        $events = Event::with('ratings', 'galleries', 'city', 'region', 'ceo')->where('active', 1);

        // Get location coordinates for distance sorting
        $location = getLocationCoords();
        $lat = $location['lat'];
        $lng = $location['lng'];
        $shouldSortByDistance = $location['hasLocation'];

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
            $categories_id = explode(',' , request('category_id'));
            $events->where(function($query)use($categories_id){
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

            $events = $events
                ->whereBetween('lat', [$lower_latitude, $upper_latitude])
                ->whereBetween('long', [$lower_longitude, $upper_longitude]);
        }

        // Apply distance sorting if location is available (closest to farthest)
        if ($shouldSortByDistance) {
            $events = applyDistanceSorting($events, $lat, $lng);
        } else {
            // Default sorting by event dates (active events first, then upcoming, then past)
            $orderByClause = "
                CASE
                    WHEN date_from <= NOW() AND date_to >= NOW() THEN 1
                    WHEN date_from > NOW() THEN 2
                    ELSE 3
                END, date_from ASC
            ";
            $events = $events->orderByRaw($orderByClause)
                ->orderBy('events.order_id', 'asc')
                ->orderBy('events.id', 'asc');
        }

        $events = $events->paginate(request('per_page', 10))->onEachSide(2);
        $events = new EventCollection($events);

        return $this->success($events, 200, __('dashboard.list_of_events'));
    }

    public function show($slug)  {
        $event = Event::where("slug" , $slug)->firstOrFail();
        return $this->success(new EventResource($event),200, __('dashboard.event_details'));
    }
}
