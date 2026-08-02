<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\EnhancedPrepareTrip\PrepareTripRequest;
use App\Http\Requests\EnhancedTrip\ReprepareEnhancedTripRequest;
use App\Http\Requests\EnhancedTrip\SaveEnhancedTripRequest;
use App\Http\Resources\Places\PlaceResource;
use App\Models\Place;
use App\Models\Trip;
use App\Models\prepareTrip;
use App\Services\EnhancedTripService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EnhancedTripV2Controller extends ApiModalController
{
    private EnhancedTripService $tripService;

    public function __construct(EnhancedTripService $tripService)
    {
        $this->tripService = $tripService;
    }

    /**
     * Prepare a new enhanced trip with morning/evening periods
     */
    public function prepareEnhancedTrip(PrepareTripRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::guard('api')->id();

            $prepareTrip = $this->tripService->planEnhancedTrip($data);

            return $this->success([
                'token' => $prepareTrip->token,
                'start_date' => $prepareTrip->start_date->format('Y-m-d'),
                'end_date' => $prepareTrip->end_date->format('Y-m-d'),
                'total_days' => $prepareTrip->days,
                'places_per_day' => $prepareTrip->funny_place_per_day,
                'places_per_period' => $prepareTrip->places_per_period,
                'start_region' => [
                    'id' => $prepareTrip->region1Object->id,
                    'name' => $prepareTrip->region1Object->name,
                ],
                'end_region' => [
                    'id' => $prepareTrip->region2Object->id,
                    'name' => $prepareTrip->region2Object->name,
                ],
                'enhanced_data' => $prepareTrip->enhanced_data,
            ], 200, __('dashboard.enhanced_trip_prepared_successfully'));

        } catch (\Exception $e) {
            Log::error('Enhanced Trip Preparation Error: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'user_id' => Auth::guard('api')->id()
            ]);

            return $this->error(500, __('dashboard.enhanced_trip_preparation_error'));
        }
    }

    /**
     * Re-prepare enhanced trip with new suggestions based on existing prepare trip
     */
    public function reprepareEnhancedTrip(ReprepareEnhancedTripRequest $request): JsonResponse
    {
        try {
            // Get the existing prepare trip
            $existingPrepareTrip = prepareTrip::where('token', $request->prepare_token)
                ->where('user_id', Auth::guard('api')->id())
                ->where('is_enhanced', true)
                ->first();

            if (!$existingPrepareTrip) {
                return $this->error(404, __('dashboard.prepared_trip_not_found'));
            }

            // Prepare data from existing trip
            $data = [
                'start_date' => $existingPrepareTrip->start_date->format('Y-m-d'),
                'end_date' => $existingPrepareTrip->end_date->format('Y-m-d'),
                'start_region_id' => $existingPrepareTrip->region1,
                'end_region_id' => $existingPrepareTrip->region2,
                'places_per_day' => $existingPrepareTrip->funny_place_per_day,
                'categories' => $existingPrepareTrip->categories_array ?? [],
                'price_range' => $existingPrepareTrip->price_range_array ?? [],
                'user_id' => Auth::guard('api')->id(),
            ];

            // Extract place IDs from existing trip to exclude them
            $excludePlaceIds = [];
            if ($existingPrepareTrip->enhanced_data) {
                foreach ($existingPrepareTrip->enhanced_data as $day) {
                    // Extract morning places
                    foreach ($day['morning']['places'] ?? [] as $place) {
                        $excludePlaceIds[] = $place['id'];
                    }
                    // Extract evening places
                    foreach ($day['evening']['places'] ?? [] as $place) {
                        $excludePlaceIds[] = $place['id'];
                    }
                }
            }

            // Create new prepare trip with new suggestions (excluding previous places)
            $newPrepareTrip = $this->tripService->planEnhancedTrip($data, array_unique($excludePlaceIds));

            return $this->success([
                'token' => $newPrepareTrip->token,
                'start_date' => $newPrepareTrip->start_date->format('Y-m-d'),
                'end_date' => $newPrepareTrip->end_date->format('Y-m-d'),
                'total_days' => $newPrepareTrip->days,
                'places_per_day' => $newPrepareTrip->funny_place_per_day,
                'places_per_period' => $newPrepareTrip->places_per_period,
                'start_region' => [
                    'id' => $newPrepareTrip->region1Object->id,
                    'name' => $newPrepareTrip->region1Object->name,
                ],
                'end_region' => [
                    'id' => $newPrepareTrip->region2Object->id,
                    'name' => $newPrepareTrip->region2Object->name,
                ],
                'enhanced_data' => $newPrepareTrip->enhanced_data,
            ], 200, __('dashboard.enhanced_trip_reprepared_successfully'));

        } catch (\Exception $e) {
            Log::error('Enhanced Trip Re-preparation Error: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'user_id' => Auth::guard('api')->id()
            ]);

            return $this->error(500, __('dashboard.enhanced_trip_repreparation_error'));
        }
    }

    /**
     * Get enhanced prepared trip details
     */
    public function getEnhancedPreparedTrip(string $token): JsonResponse
    {
        $prepareTrip = prepareTrip::where('token', $token)
            ->where('user_id', Auth::guard('api')->id())
            ->where('is_enhanced', true)
            ->with(['region1Object', 'region2Object'])
            ->first();

        if (!$prepareTrip) {
            return $this->error(404, __('dashboard.prepared_trip_not_found'));
        }

        // Re-process places to ensure images are up-to-date with all 3 sizes
        $enhancedDays = [];
        foreach ($prepareTrip->enhanced_data as $dayIndex => $day) {
            $dayNumber = $dayIndex + 1;

            // Extract place IDs from morning and evening
            $morningPlaceIds = array_column($day['morning']['places'] ?? [], 'id');
            $eveningPlaceIds = array_column($day['evening']['places'] ?? [], 'id');
            $allPlaceIds = array_filter(array_merge($morningPlaceIds, $eveningPlaceIds));

            // Load places with relationships if there are any place IDs
            $places = collect([]);
            if (!empty($allPlaceIds)) {
                $places = Place::whereIn('id', $allPlaceIds)
                    ->with(['city', 'region', 'price', 'galleries', 'ceo', 'ratings'])
                    ->get()
                    ->keyBy('id');
            }

            // Re-process morning places with updated images
            $morningPlaces = [];
            foreach ($morningPlaceIds as $placeId) {
                if ($places->has($placeId)) {
                    $place = $places->get($placeId);
                    $morningPlaces[] = (new PlaceResource($place))->toArray(request());
                }
            }

            // Re-process evening places with updated images
            $eveningPlaces = [];
            foreach ($eveningPlaceIds as $placeId) {
                if ($places->has($placeId)) {
                    $place = $places->get($placeId);
                    $eveningPlaces[] = (new PlaceResource($place))->toArray(request());
                }
            }

            $enhancedDays[] = [
                'day_number' => $dayNumber,
                'date' => $day['date'],
                'city' => $day['city'] ?? [
                    'id' => null,
                    'name' => 'غير محدد',
                    'region' => null,
                    'description' => 'استمتع بيوم رائع في هذه المنطقة المميزة'
                ],
                'morning' => [
                    'description' => $day['morning']['description'] ?? '',
                    'places' => $morningPlaces,
                ],
                'evening' => [
                    'description' => $day['evening']['description'] ?? '',
                    'places' => $eveningPlaces,
                ],
            ];
        }

        return $this->success([
            'token' => $prepareTrip->token,
            'start_date' => $prepareTrip->start_date->format('Y-m-d'),
            'end_date' => $prepareTrip->end_date->format('Y-m-d'),
            'total_days' => $prepareTrip->days,
            'places_per_day' => $prepareTrip->funny_place_per_day,
            'places_per_period' => $prepareTrip->places_per_period,
            'start_region' => [
                'id' => $prepareTrip->region1Object->id,
                'name' => $prepareTrip->region1Object->name,
            ],
            'end_region' => [
                'id' => $prepareTrip->region2Object->id,
                'name' => $prepareTrip->region2Object->name,
            ],
            'enhanced_data' => $enhancedDays,
            'created_at' => $prepareTrip->created_at->format('Y-m-d H:i:s'),
            'user_id' => $prepareTrip->user_id,
        ], 200, __('dashboard.prepared_trip_details'));
    }

    /**
     * Save enhanced prepared trip as permanent trip
     */
    public function saveEnhancedTrip(SaveEnhancedTripRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            
            $prepareTrip = prepareTrip::where('token', $validated['prepare_token'])
                ->where('user_id', Auth::guard('api')->id())
                ->where('is_enhanced', true)
                ->first();

            if (!$prepareTrip) {
                return $this->error(404, __('dashboard.prepared_trip_not_found'));
            }

            // Check if trip with same name already exists for user
            $existingTrip = Trip::where('user_id', Auth::guard('api')->id())
                ->where('name', $validated['name'])
                ->exists();

            if ($existingTrip) {
                return $this->error(422, __('dashboard.trip_name_exists'));
            }

            $trip = $this->tripService->saveEnhancedTrip($prepareTrip, $validated);

            return $this->success([
                'id' => $trip->id,
                'name' => $trip->name,
                'token' => $trip->token,
                'start_date' => $trip->start_date->format('Y-m-d'),
                'end_date' => $trip->end_date->format('Y-m-d'),
                'total_days' => $trip->days,
                'places_per_day' => $trip->item_per_day,
                'places_per_period' => $trip->places_per_period,
                'total_places' => $trip->total_places,
                'is_enhanced' => $trip->is_enhanced,
                'enhanced_data' => $trip->enhanced_days,
            ], 201, __('dashboard.trip_saved_successfully'));

        } catch (\Exception $e) {
            Log::error('Enhanced Trip Save Error: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'user_id' => Auth::guard('api')->id()
            ]);

            return $this->error(500, __('dashboard.trip_save_error'));
        }
    }

    /**
     * Get user's enhanced trips
     */
    public function getMyEnhancedTrips(Request $request): JsonResponse
    {
        $query = Trip::where('user_id', Auth::guard('api')->id())
            ->where('is_enhanced', true)
            ->with(['region1Object', 'region2Object'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $trips = $query->paginate($request->get('per_page', 10));

        $tripsData = $trips->items();
        $formattedTrips = array_map(function ($trip) {
            return [
                'id' => $trip->id,
                'name' => $trip->name,
                'token' => $trip->token,
                'start_date' => $trip->start_date->format('Y-m-d'),
                'end_date' => $trip->end_date->format('Y-m-d'),
                'total_days' => $trip->days,
                'places_per_day' => $trip->item_per_day,
                'places_per_period' => $trip->places_per_period,
                'total_places' => $trip->total_places,
                'start_region' => [
                    'id' => $trip->region1Object->id,
                    'name' => $trip->region1Object->name,
                ],
                'end_region' => [
                    'id' => $trip->region2Object->id,
                    'name' => $trip->region2Object->name,
                ],
                'enhanced_data' => $trip->enhanced_days,
                'created_at' => $trip->created_at->format('Y-m-d H:i:s'),
            ];
        }, $tripsData);

        return $this->success([
            'trips' => $formattedTrips,
            'pagination' => [
                'current_page' => $trips->currentPage(),
                'last_page' => $trips->lastPage(),
                'per_page' => $trips->perPage(),
                'total' => $trips->total(),
            ]
        ], 200, __('dashboard.my_enhanced_trips'));
    }

    /**
     * Get user's enhanced prepared trips
     */
    public function getMyEnhancedPreparedTrips(Request $request): JsonResponse
    {
        $query = prepareTrip::where('user_id', Auth::guard('api')->id())
            ->where('is_enhanced', true)
            ->with(['region1Object', 'region2Object'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $query->whereHas('region1Object', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhereHas('region2Object', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $preparedTrips = $query->paginate($request->get('per_page', 10));

        $preparedTripsData = $preparedTrips->items();
        $formattedPreparedTrips = array_map(function ($prepareTrip) {
            return [
                'token' => $prepareTrip->token,
                'start_date' => $prepareTrip->start_date->format('Y-m-d'),
                'end_date' => $prepareTrip->end_date->format('Y-m-d'),
                'total_days' => $prepareTrip->days,
                'places_per_day' => $prepareTrip->funny_place_per_day,
                'places_per_period' => $prepareTrip->places_per_period,
                'start_region' => [
                    'id' => $prepareTrip->region1Object->id,
                    'name' => $prepareTrip->region1Object->name,
                ],
                'end_region' => [
                    'id' => $prepareTrip->region2Object->id,
                    'name' => $prepareTrip->region2Object->name,
                ],
                'created_at' => $prepareTrip->created_at->format('Y-m-d H:i:s'),
            ];
        }, $preparedTripsData);

        return $this->success([
            'prepared_trips' => $formattedPreparedTrips,
            'pagination' => [
                'current_page' => $preparedTrips->currentPage(),
                'last_page' => $preparedTrips->lastPage(),
                'per_page' => $preparedTrips->perPage(),
                'total' => $preparedTrips->total(),
            ]
        ], 200, __('dashboard.my_prepared_trips'));
    }

    /**
     * View enhanced trip details
     */
    public function viewEnhancedTrip(string $token): JsonResponse
    {
        $trip = Trip::where('token', $token)
            ->where('is_enhanced', true)
            ->with(['region1Object', 'region2Object'])
            ->first();

        if (!$trip) {
            return $this->error(404, __('dashboard.trip_not_found'));
        }

        // Check if user owns this trip
        if ($trip->user_id !== Auth::guard('api')->id()) {
            return $this->error(403, __('dashboard.trip_access_denied'));
        }

        // Re-process places to ensure images are up-to-date
        $enhancedDays = [];
        foreach ($trip->enhanced_days as $dayIndex => $day) {
            $dayNumber = $dayIndex + 1;

            // Extract place IDs from morning and evening
            $morningPlaceIds = array_column($day['morning']['places'] ?? [], 'id');
            $eveningPlaceIds = array_column($day['evening']['places'] ?? [], 'id');
            $allPlaceIds = array_filter(array_merge($morningPlaceIds, $eveningPlaceIds));

            // Load places with relationships if there are any place IDs
            $places = collect([]);
            if (!empty($allPlaceIds)) {
                $places = Place::whereIn('id', $allPlaceIds)
                    ->with(['city', 'region', 'price', 'galleries', 'ceo', 'ratings'])
                    ->get()
                    ->keyBy('id');
            }

            // Re-process morning places with updated images
            $morningPlaces = [];
            foreach ($morningPlaceIds as $placeId) {
                if ($places->has($placeId)) {
                    $place = $places->get($placeId);
                    $morningPlaces[] = (new PlaceResource($place))->toArray(request());
                }
            }

            // Re-process evening places with updated images
            $eveningPlaces = [];
            foreach ($eveningPlaceIds as $placeId) {
                if ($places->has($placeId)) {
                    $place = $places->get($placeId);
                    $eveningPlaces[] = (new PlaceResource($place))->toArray(request());
                }
            }

            $enhancedDays[] = [
                'day_number' => $dayNumber,
                'date' => $day['date'],
                'city' => $day['city'] ?? [
                    'id' => null,
                    'name' => 'غير محدد',
                    'region' => null,
                    'description' => 'استمتع بيوم رائع في هذه المنطقة المميزة'
                ],
                'morning' => [
                    'description' => $trip->getMorningDescription($dayNumber),
                    'places' => $morningPlaces,
                    'places_count' => count($morningPlaces),
                ],
                'evening' => [
                    'description' => $trip->getEveningDescription($dayNumber),
                    'places' => $eveningPlaces,
                    'places_count' => count($eveningPlaces),
                ],
                'total_places_count' => count($morningPlaces) + count($eveningPlaces),
            ];
        }

        return $this->success([
            'id' => $trip->id,
            'user_id' => $trip->user_id,
            'name' => $trip->name,
            'token' => $trip->token,
            'start_date' => $trip->start_date->format('Y-m-d'),
            'end_date' => $trip->end_date->format('Y-m-d'),
            'total_days' => $trip->days,
            'places_per_day' => $trip->item_per_day,
            'places_per_period' => $trip->places_per_period,
            'total_places' => $trip->total_places,
            'start_region' => [
                'id' => $trip->region1Object->id,
                'name' => $trip->region1Object->name,
            ],
            'end_region' => [
                'id' => $trip->region2Object->id,
                'name' => $trip->region2Object->name,
            ],
            'days' => $enhancedDays,
            'created_at' => $trip->created_at->format('Y-m-d H:i:s'),
        ], 200, __('dashboard.trip_details'));
    }

    /**
     * Delete an enhanced trip
     */
    public function deleteEnhancedTrip(string $token): JsonResponse
    {
        $trip = Trip::where('token', $token)
            ->where('user_id', Auth::guard('api')->id())
            ->where('is_enhanced', true)
            ->first();

        if (!$trip) {
            return $this->error(404, __('dashboard.trip_not_found'));
        }

        $trip->delete();

        return $this->success(null, 200, __('dashboard.trip_deleted_successfully'));
    }

    /**
     * Delete an enhanced prepared trip
     */
    public function deleteEnhancedPreparedTrip(string $token): JsonResponse
    {
        $prepareTrip = prepareTrip::where('token', $token)
            ->where('user_id', Auth::guard('api')->id())
            ->where('is_enhanced', true)
            ->first();

        if (!$prepareTrip) {
            return $this->error(404, __('dashboard.prepared_trip_not_found'));
        }

        $prepareTrip->delete();

        return $this->success(null, 200, __('dashboard.prepared_trip_deleted_successfully'));
    }

    /**
     * Get enhanced trip statistics for user
     */
    public function getEnhancedTripStatistics(): JsonResponse
    {
        $userId = Auth::guard('api')->id();

        $stats = [
            'total_enhanced_trips' => Trip::where('user_id', $userId)->where('is_enhanced', true)->count(),
            'total_enhanced_prepared_trips' => prepareTrip::where('user_id', $userId)->where('is_enhanced', true)->count(),
            'total_enhanced_places_visited' => Trip::where('user_id', $userId)
                ->where('is_enhanced', true)
                ->get()
                ->sum('total_places'),
        ];

        return $this->success($stats, 200, __('dashboard.enhanced_trip_statistics'));
    }
}
