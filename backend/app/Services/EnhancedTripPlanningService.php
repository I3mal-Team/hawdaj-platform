<?php

namespace App\Services;

use App\Models\Place;
use App\Models\Region;
use App\Models\EnhancedPrepareTrip;
use App\Models\EnhancedTrip;
use App\Models\EnhancedTripDay;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EnhancedTripPlanningService
{
    private const MORNING_PERIOD = 'morning';
    private const EVENING_PERIOD = 'evening';

    private const PERIOD_DESCRIPTIONS = [
        'morning' => [
            'default' => 'استمتع بجولة صباحية رائعة في هذه الأماكن المميزة',
            'cultural' => 'ابدأ يومك بزيارة المعالم الثقافية والتاريخية',
            'nature' => 'استمتع بجمال الطبيعة في الصباح الباكر',
            'shopping' => 'تسوق في أفضل الأسواق والمراكز التجارية',
            'entertainment' => 'استمتع بالأنشطة الترفيهية الصباحية'
        ],
        'evening' => [
            'default' => 'اختتم يومك بزيارة هذه الأماكن الساحرة',
            'cultural' => 'استكشف التراث والثقافة في أجواء مسائية رائعة',
            'nature' => 'استمتع بغروب الشمس في أجمل المناظر الطبيعية',
            'shopping' => 'تسوق في الأسواق الليلية والمراكز التجارية',
            'entertainment' => 'استمتع بالحياة الليلية والأنشطة المسائية'
        ]
    ];

    public function planTrip(array $data): EnhancedPrepareTrip
    {
        // Validate and prepare data
        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);
        $totalDays = $startDate->diffInDays($endDate) + 1;

        // Create prepare trip record
        $prepareTrip = EnhancedPrepareTrip::create([
            'user_id' => $data['user_id'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'start_region_id' => $data['start_region_id'],
            'end_region_id' => $data['end_region_id'],
            'total_days' => $totalDays,
            'places_per_day' => $data['places_per_day'],
            'places_per_period' => ceil($data['places_per_day'] / 2),
            'categories' => $data['categories'] ?? [],
            'price_range' => $data['price_range'] ?? [],
            'status' => 'active'
        ]);

        // Generate trip plan
        $tripData = $this->generateTripPlan($prepareTrip);
        
        // Save generated data
        $prepareTrip->update(['generated_data' => $tripData]);

        return $prepareTrip;
    }

    private function generateTripPlan(EnhancedPrepareTrip $prepareTrip): array
    {
        $waypoints = $this->getRouteWaypoints(
            $prepareTrip->startRegion->translate('en')->name,
            $prepareTrip->endRegion->translate('en')->name
        );

        $allPlaces = $this->findPlacesAlongRoute(
            $waypoints,
            $prepareTrip->categories,
            $prepareTrip->price_range,
            $prepareTrip->start_date,
            $prepareTrip->total_days * $prepareTrip->places_per_day
        );

        return $this->organizePlacesByDays($allPlaces, $prepareTrip);
    }

    private function getRouteWaypoints(string $origin, string $destination): array
    {
        $params = http_build_query([
            'origin' => $origin,
            'destination' => $destination,
            'sensor' => 'true',
            'units' => 'metric',
            'mode' => 'driving',
            'key' => config('services.google.maps_api_key', 'AIzaSyDDSTB8emANyFuYPdfvIGCq_3e0ZZ6lKJc'),
        ]);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://maps.googleapis.com/maps/api/directions/json?' . $params,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);
        $steps = $data["routes"][0]["legs"][0]["steps"] ?? [];

        $waypoints = [];
        foreach ($steps as $step) {
            $waypoints[] = $step["end_location"];
        }

        return $waypoints;
    }

    private function findPlacesAlongRoute(array $waypoints, array $categories, array $priceRange, Carbon $startDate, int $totalPlacesNeeded): Collection
    {
        $season = $this->determineSeason($startDate);
        $places = collect();

        foreach ($waypoints as $waypoint) {
            $nearbyPlaces = Place::select("places.*")
                ->selectRaw('(6371 * acos(cos(radians(?)) * cos(radians(lat)) * cos(radians(places.long) - radians(?)) + sin(radians(?)) * sin(radians(lat)))) AS distance', [
                    $waypoint['lat'],
                    $waypoint['lng'],
                    $waypoint['lat']
                ])
                ->having('distance', '<', 50)
                ->orderBy('distance', 'asc')
                ->when(!empty($categories), function ($query) use ($categories) {
                    $query->where(function ($q) use ($categories) {
                        foreach ($categories as $categoryId) {
                            $q->orWhere("categories", 'like', '%[' . $categoryId . ']%');
                        }
                    });
                })
                ->when(!empty($priceRange), function ($query) use ($priceRange) {
                    $query->whereIn('price_id', $priceRange);
                })
                ->where(function ($query) use ($season) {
                    $query->where('seasons', 'like', '%' . $season . '%')
                          ->orWhere("seasons", "طوال العام");
                })
                ->with(['city', 'region'])
                ->limit(ceil($totalPlacesNeeded / count($waypoints)))
                ->get();

            $places = $places->merge($nearbyPlaces);
        }

        return $places->unique('id')->take($totalPlacesNeeded);
    }

    private function organizePlacesByDays(Collection $places, EnhancedPrepareTrip $prepareTrip): array
    {
        $days = [];
        $placesPerDay = $prepareTrip->places_per_day;
        $placesPerPeriod = $prepareTrip->places_per_period;
        $totalPlacesNeeded = $prepareTrip->total_days * $placesPerDay;
        
        $currentDate = $prepareTrip->start_date->copy();
        $usedPlaceIds = [];
        $lastPlaceLocation = null; // Track last place location for re-sorting
        
        // Get user's current location for initial sorting
        $userLocation = getLocationCoords();
        if ($userLocation['hasLocation']) {
            $lastPlaceLocation = ['lat' => $userLocation['lat'], 'lng' => $userLocation['lng']];
        }

        for ($dayIndex = 0; $dayIndex < $prepareTrip->total_days; $dayIndex++) {
            // Get available places (not used yet)
            $availablePlaces = $places->whereNotIn('id', $usedPlaceIds);
            
            // If no available places, re-sort all places from last location
            if ($availablePlaces->isEmpty()) {
                if ($lastPlaceLocation) {
                    // Re-sort all places by distance from last visited place
                    $availablePlaces = $places->map(function ($place) use ($lastPlaceLocation) {
                        $distance = $this->calculateDistance(
                            $lastPlaceLocation['lat'],
                            $lastPlaceLocation['lng'],
                            $place->lat,
                            $place->long
                        );
                        $place->distance = $distance;
                        return $place;
                    })->sortBy('distance');
                } else {
                    // Use original order if no location
                    $availablePlaces = $places;
                }
                
                // Reset used places to allow reuse
                $usedPlaceIds = [];
            } else {
                // Sort available places by distance from last location
                if ($lastPlaceLocation) {
                    $availablePlaces = $availablePlaces->map(function ($place) use ($lastPlaceLocation) {
                        $distance = $this->calculateDistance(
                            $lastPlaceLocation['lat'],
                            $lastPlaceLocation['lng'],
                            $place->lat,
                            $place->long
                        );
                        $place->distance = $distance;
                        return $place;
                    })->sortBy('distance');
                }
            }

            // Take places for this day
            $dayPlaces = $availablePlaces->take($placesPerDay);
            
            // Update last place location (use last place of the day)
            if ($dayPlaces->isNotEmpty()) {
                $lastPlace = $dayPlaces->last();
                $lastPlaceLocation = ['lat' => $lastPlace->lat, 'lng' => $lastPlace->long];
                $usedPlaceIds = array_merge($usedPlaceIds, $dayPlaces->pluck('id')->toArray());
            }

            $morningPlaces = $dayPlaces->take($placesPerPeriod);
            $eveningPlaces = $dayPlaces->skip($placesPerPeriod);

            $days[] = [
                'day_number' => $dayIndex + 1,
                'date' => $currentDate->format('Y-m-d'),
                'morning' => [
                    'places' => $morningPlaces->values()->toArray(),
                    'description' => $this->generatePeriodDescription(self::MORNING_PERIOD, $prepareTrip->categories)
                ],
                'evening' => [
                    'places' => $eveningPlaces->values()->toArray(),
                    'description' => $this->generatePeriodDescription(self::EVENING_PERIOD, $prepareTrip->categories)
                ]
            ];

            $currentDate->addDay();
        }

        return $days;
    }

    private function calculateDistance(float $lat1, float $lng1, ?float $lat2, ?float $lng2): float
    {
        if (!$lat2 || !$lng2) {
            return 999999; // Very large distance for places without coordinates
        }

        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    private function generatePeriodDescription(string $period, array $categories): string
    {
        $descriptions = self::PERIOD_DESCRIPTIONS[$period];
        
        if (empty($categories)) {
            return $descriptions['default'];
        }

        // Determine the main category type and return appropriate description
        // This is a simplified version - you can enhance this based on your category structure
        $categoryType = $this->determineCategoryType($categories);
        
        return $descriptions[$categoryType] ?? $descriptions['default'];
    }

    private function determineCategoryType(array $categories): string
    {
        // This is a placeholder - implement based on your category structure
        // You might want to query the categories table to determine the type
        return 'default';
    }

    private function determineSeason(Carbon $date): string
    {
        $month = $date->month;
        
        if ($month >= 3 && $month <= 5) {
            return 'موسم الربيع';
        } elseif ($month >= 6 && $month <= 8) {
            return 'موسم الصيف';
        } elseif ($month >= 9 && $month <= 11) {
            return 'موسم الخريف';
        } else {
            return 'موسم الشتاء';
        }
    }

    public function saveTrip(EnhancedPrepareTrip $prepareTrip, array $data): EnhancedTrip
    {
        DB::beginTransaction();
        
        try {
            // Create the main trip
            $trip = EnhancedTrip::create([
                'name' => $data['name'],
                'user_id' => $prepareTrip->user_id,
                'start_date' => $prepareTrip->start_date,
                'end_date' => $prepareTrip->end_date,
                'start_region_id' => $prepareTrip->start_region_id,
                'end_region_id' => $prepareTrip->end_region_id,
                'total_days' => $prepareTrip->total_days,
                'places_per_day' => $prepareTrip->places_per_day,
                'categories' => $prepareTrip->categories,
                'price_range' => $prepareTrip->price_range,
                'status' => 'active',
                'created_by_prepare_token' => $prepareTrip->token
            ]);

            // Create trip days with places
            foreach ($prepareTrip->generated_data as $dayData) {
                $tripDay = EnhancedTripDay::create([
                    'trip_id' => $trip->id,
                    'day_number' => $dayData['day_number'],
                    'date' => $dayData['date'],
                    'morning_description' => $dayData['morning']['description'],
                    'evening_description' => $dayData['evening']['description'],
                    'morning_places' => array_column($dayData['morning']['places'], 'id'),
                    'evening_places' => array_column($dayData['evening']['places'], 'id'),
                    'status' => 'active'
                ]);

                // Attach morning places
                foreach ($dayData['morning']['places'] as $index => $place) {
                    $tripDay->morningPlaces()->attach($place['id'], [
                        'order' => $index + 1,
                        'period' => 'morning'
                    ]);
                }

                // Attach evening places
                foreach ($dayData['evening']['places'] as $index => $place) {
                    $tripDay->eveningPlaces()->attach($place['id'], [
                        'order' => $index + 1,
                        'period' => 'evening'
                    ]);
                }
            }

            // Mark prepare trip as used
            $prepareTrip->update(['status' => 'used']);

            DB::commit();
            return $trip;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
