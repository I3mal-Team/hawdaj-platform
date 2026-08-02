<?php

namespace App\Services;

use App\Models\Place;
use App\Models\Region;
use App\Models\prepareTrip;
use App\Models\Trip;
use App\Http\Resources\Places\PlaceResource;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EnhancedTripService
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

    public function planEnhancedTrip(array $data, array $excludePlaceIds = []): prepareTrip
    {
        // Validate and prepare data
        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);
        $totalDays = $startDate->diffInDays($endDate) + 1;
        $placesPerPeriod = ceil($data['places_per_day'] / 2);

        // Create prepare trip record using existing structure
        $prepareTrip = prepareTrip::create([
            'user_id' => $data['user_id'],
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'region1' => $data['start_region_id'],
            'region2' => $data['end_region_id'],
            'days' => $totalDays,
            'funny_place_per_day' => $data['places_per_day'],
            'token' => Str::random(20),

            // Enhanced fields
            'is_enhanced' => true,
            'places_per_period' => $placesPerPeriod,
            'categories_array' => $data['categories'] ?? [],
            'price_range_array' => $data['price_range'] ?? [],
        ]);

        // Generate enhanced trip plan
        $enhancedData = $this->generateEnhancedTripPlan($prepareTrip, $data, $excludePlaceIds);

        // Save enhanced data and maintain backward compatibility
        $prepareTrip->update([
            'enhanced_generated_data' => $enhancedData,
            'items' => $this->extractItemsForBackwardCompatibility($enhancedData),
            'places' => $this->extractPlacesForBackwardCompatibility($enhancedData),
        ]);

        return $prepareTrip;
    }

    private function generateEnhancedTripPlan(prepareTrip $prepareTrip, array $data, array $excludePlaceIds = []): array
    {
        $region1 = Region::find($prepareTrip->region1);
        $region2 = Region::find($prepareTrip->region2);

        // If start and end regions are the same, find places in that region directly
        if ($prepareTrip->region1 == $prepareTrip->region2) {
            $allPlaces = $this->findPlacesInRegion(
                $prepareTrip->region1,
                $prepareTrip->categories_array ?? [],
                $prepareTrip->price_range_array ?? [],
                $prepareTrip->start_date,
                $prepareTrip->days * $prepareTrip->funny_place_per_day,
                $excludePlaceIds
            );
        } else {
            $waypoints = $this->getRouteWaypoints(
                $region1->translate('en')->name,
                $region2->translate('en')->name
            );

            // If waypoints are empty (e.g., same location or API error), fallback to region search
            if (empty($waypoints)) {
                $allPlaces = $this->findPlacesInRegion(
                    $prepareTrip->region1,
                    $prepareTrip->categories_array ?? [],
                    $prepareTrip->price_range_array ?? [],
                    $prepareTrip->start_date,
                    $prepareTrip->days * $prepareTrip->funny_place_per_day,
                    $excludePlaceIds
                );
            } else {
                $allPlaces = $this->findPlacesAlongRoute(
                    $waypoints,
                    $prepareTrip->categories_array ?? [],
                    $prepareTrip->price_range_array ?? [],
                    $prepareTrip->start_date,
                    $prepareTrip->days * $prepareTrip->funny_place_per_day,
                    $excludePlaceIds
                );
            }
        }

        return $this->organizePlacesByPeriodsAndDays($allPlaces, $prepareTrip);
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

    private function findPlacesAlongRoute(array $waypoints, array $categories, array $priceRange, $startDate, int $totalPlacesNeeded, array $excludePlaceIds = []): Collection
    {
        $season = $this->determineSeason(Carbon::parse($startDate));
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
                ->when(!empty($excludePlaceIds), function ($query) use ($excludePlaceIds) {
                    $query->whereNotIn('id', $excludePlaceIds);
                })
                ->with(['city', 'region', 'price', 'galleries', 'ceo'])
                ->limit(ceil($totalPlacesNeeded / count($waypoints)))
                ->get();

            $places = $places->merge($nearbyPlaces);
        }

        return $places->unique('id')->take($totalPlacesNeeded);
    }

    /**
     * Find places in a specific region (used when start and end regions are the same)
     */
    private function findPlacesInRegion(int $regionId, array $categories, array $priceRange, $startDate, int $totalPlacesNeeded, array $excludePlaceIds = []): Collection
    {
        $season = $this->determineSeason(Carbon::parse($startDate));

        $places = Place::where('region_id', $regionId)
            ->where('active', 1)
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
            ->when(!empty($excludePlaceIds), function ($query) use ($excludePlaceIds) {
                $query->whereNotIn('id', $excludePlaceIds);
            })
            ->with(['city', 'region', 'price', 'galleries', 'ceo'])
            ->inRandomOrder()
            ->limit($totalPlacesNeeded)
            ->get();

        return $places;
    }

    private function organizePlacesByPeriodsAndDays(Collection $places, prepareTrip $prepareTrip): array
    {
        $days = [];
        $placesPerDay = $prepareTrip->funny_place_per_day;
        $placesPerPeriod = $prepareTrip->places_per_period;

        $currentDate = Carbon::parse($prepareTrip->start_date);
        $usedPlaceIds = [];
        $lastPlaceLocation = null; // Track last place location for re-sorting
        
        // Get user's current location for initial sorting
        $userLocation = getLocationCoords();
        if ($userLocation['hasLocation']) {
            $lastPlaceLocation = ['lat' => $userLocation['lat'], 'lng' => $userLocation['lng']];
        }

        for ($dayIndex = 0; $dayIndex < $prepareTrip->days; $dayIndex++) {
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

            // Get day items for city info
            $dayItems = $dayPlaces->pluck('id')->toArray();
            $cityInfo = $this->getDayMainCityInfo($dayPlaces, $dayItems);

            $days[] = [
                'day_number' => $dayIndex + 1,
                'date' => $currentDate->format('Y-m-d'),
                'city' => $cityInfo,
                'morning' => [
                    'places' => $morningPlaces->map(function ($place) {
                        return (new PlaceResource($place))->toArray(request());
                    })->values()->toArray(),
                    'description' => $this->generatePeriodDescription(self::MORNING_PERIOD, $prepareTrip->categories_array ?? [])
                ],
                'evening' => [
                    'places' => $eveningPlaces->map(function ($place) {
                        return (new PlaceResource($place))->toArray(request());
                    })->values()->toArray(),
                    'description' => $this->generatePeriodDescription(self::EVENING_PERIOD, $prepareTrip->categories_array ?? [])
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
        $categoryType = $this->determineCategoryType($categories);

        return $descriptions[$categoryType] ?? $descriptions['default'];
    }

    private function determineCategoryType(array $categories): string
    {
        // This is a placeholder - implement based on your category structure
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

    public function saveEnhancedTrip(prepareTrip $prepareTrip, array $data): Trip
    {
        DB::beginTransaction();

        try {
            // Generate enhanced data from custom items
            $customItems = $data['items'] ?? [];
            $enhancedData = $this->generateEnhancedDataFromCustomItems($customItems, $prepareTrip);

            $morningDescriptions = [];
            $eveningDescriptions = [];

            foreach ($enhancedData as $day) {
                $morningDescriptions[] = $day['morning']['description'] ?? 'استمتع بجولة صباحية رائعة';
                $eveningDescriptions[] = $day['evening']['description'] ?? 'اختتم يومك بزيارة هذه الأماكن الساحرة';
            }

            // Calculate total places from custom items
            $totalPlaces = array_sum(array_map('count', $customItems));

            // Create the trip using existing structure with enhanced fields
            $trip = Trip::create([
                'name' => $data['name'],
                'user_id' => $prepareTrip->user_id,
                'start_date' => $prepareTrip->start_date,
                'end_date' => $prepareTrip->end_date,
                'region1' => $prepareTrip->region1,
                'region2' => $prepareTrip->region2,
                'days' => $prepareTrip->days,
                'item_per_day' => $prepareTrip->funny_place_per_day,
                'date' => $prepareTrip->start_date,
                'token' => Str::random(20),
//                'total_places' => $totalPlaces,

                // Use custom items instead of prepare trip items
                'items' => $customItems,

                // Enhanced fields
                'is_enhanced' => true,
                'places_per_period' => $prepareTrip->places_per_period,
                'enhanced_data' => $enhancedData,
                'morning_descriptions' => $morningDescriptions,
                'evening_descriptions' => $eveningDescriptions,
            ]);

            $prepareTrip->delete();

            DB::commit();
            return $trip;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Generate enhanced data structure from custom items
     */
    private function generateEnhancedDataFromCustomItems(array $customItems, prepareTrip $prepareTrip): array
    {
        $days = [];
        $currentDate = Carbon::parse($prepareTrip->start_date);
        $placesPerPeriod = $prepareTrip->places_per_period;

        foreach ($customItems as $dayIndex => $dayItems) {
            if ($dayIndex >= $prepareTrip->days) break;

            // Get place details for the day with all necessary relationships
            $places = Place::whereIn('id', $dayItems)
                ->with(['city', 'region', 'price', 'galleries', 'ceo'])
                ->get()
                ->keyBy('id');

            // Split places into morning and evening periods
            $morningPlaceIds = array_slice($dayItems, 0, $placesPerPeriod);
            $eveningPlaceIds = array_slice($dayItems, $placesPerPeriod);

            // Build morning places array using PlaceResource
            $morningPlaces = [];
            foreach ($morningPlaceIds as $placeId) {
                if ($places->has($placeId)) {
                    $place = $places->get($placeId);
                    $morningPlaces[] = (new PlaceResource($place))->toArray(request());
                }
            }

            // Build evening places array using PlaceResource
            $eveningPlaces = [];
            foreach ($eveningPlaceIds as $placeId) {
                if ($places->has($placeId)) {
                    $place = $places->get($placeId);
                    $eveningPlaces[] = (new PlaceResource($place))->toArray(request());
                }
            }

            // Get the main city for this day (most frequent city among places)
            $cityInfo = $this->getDayMainCityInfo($places, $dayItems);

            $days[] = [
                'day_number' => $dayIndex + 1,
                'date' => $currentDate->format('Y-m-d'),
                'city' => $cityInfo,
                'morning' => [
                    'places' => $morningPlaces,
                    'description' => $this->generatePeriodDescription(self::MORNING_PERIOD, $prepareTrip->categories_array ?? [])
                ],
                'evening' => [
                    'places' => $eveningPlaces,
                    'description' => $this->generatePeriodDescription(self::EVENING_PERIOD, $prepareTrip->categories_array ?? [])
                ]
            ];

            $currentDate->addDay();
        }

        return $days;
    }

    /**
     * Get the main city information for a day based on places
     */
    private function getDayMainCityInfo(Collection $places, array $dayItems): array
    {
        // Count occurrences of each city
        $cityCounts = [];
        $cityData = [];

        foreach ($dayItems as $placeId) {
            if ($places->has($placeId)) {
                $place = $places->get($placeId);
                $cityName = $place->city->name ?? 'غير محدد';
                $cityId = $place->city->id ?? null;

                if (!isset($cityCounts[$cityName])) {
                    $cityCounts[$cityName] = 0;
                    $cityData[$cityName] = [
                        'id' => $cityId,
                        'name' => $cityName,
                        'region' => $place->region->name ?? null,
                    ];
                }
                $cityCounts[$cityName]++;
            }
        }

        // Get the most frequent city
        if (empty($cityCounts)) {
            return [
                'id' => null,
                'name' => 'غير محدد',
                'region' => null,
                'description' => 'استمتع بيوم رائع في هذه المنطقة المميزة'
            ];
        }

        $mainCityName = array_keys($cityCounts, max($cityCounts))[0];
        $mainCity = $cityData[$mainCityName];

        return [
            'id' => $mainCity['id'],
            'name' => $mainCity['name'],
            'region' => $mainCity['region'],
            'description' => $this->generateCityDescription($mainCity['name'], $mainCity['region'])
        ];
    }

    /**
     * Generate a description for a city
     */
    private function generateCityDescription(string $cityName, ?string $regionName): string
    {
        $descriptions = [
            'default' => "استمتع بيوم رائع في مدينة {$cityName} واستكشف معالمها المميزة",
            'with_region' => "اكتشف جمال مدينة {$cityName} في منطقة {$regionName} واستمتع بتجربة لا تُنسى",
        ];

        if ($regionName && $regionName !== $cityName) {
            return $descriptions['with_region'];
        }

        return $descriptions['default'];
    }

    // Helper methods for backward compatibility
    private function extractItemsForBackwardCompatibility(array $enhancedData): array
    {
        $items = [];
        foreach ($enhancedData as $day) {
            $dayItems = [];

            // Add morning places
            foreach ($day['morning']['places'] ?? [] as $place) {
                $dayItems[] = $place['id'];
            }

            // Add evening places
            foreach ($day['evening']['places'] ?? [] as $place) {
                $dayItems[] = $place['id'];
            }

            $items[] = $dayItems;
        }

        return $items;
    }

    private function extractPlacesForBackwardCompatibility(array $enhancedData): array
    {
        $places = [];
        foreach ($enhancedData as $day) {
            $dayPlaces = [];

            // Add morning places
            foreach ($day['morning']['places'] ?? [] as $place) {
                $dayPlaces[] = $place;
            }

            // Add evening places
            foreach ($day['evening']['places'] ?? [] as $place) {
                $dayPlaces[] = $place;
            }

            $places[] = $dayPlaces;
        }

        return $places;
    }
}
