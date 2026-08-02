<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EnhancedPrepareTrip\PrepareTripRequest;
use App\Http\Requests\EnhancedTrip\SaveTripRequest;
use App\Http\Resources\EnhancedTrip\EnhancedTripResource;
use App\Http\Resources\EnhancedTrip\EnhancedPrepareTripResource;
use App\Models\EnhancedTrip;
use App\Models\EnhancedPrepareTrip;
use App\Services\EnhancedTripPlanningService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EnhancedTripController extends ApiModalController
{
    private EnhancedTripPlanningService $tripPlanningService;

    public function __construct(EnhancedTripPlanningService $tripPlanningService)
    {
        $this->tripPlanningService = $tripPlanningService;
    }

    /**
     * Prepare a new trip with enhanced features including morning/evening periods
     */
    public function prepareTrip(PrepareTripRequest $request): JsonResponse
    {
        try {
            $prepareTrip = $this->tripPlanningService->planTrip($request->validated());

            return $this->success(
                new EnhancedPrepareTripResource($prepareTrip->load(['startRegion', 'endRegion'])),
                200,
                __('dashboard.enhanced_trip_prepared_successfully')
            );
        } catch (\Exception $e) {
            Log::error('Enhanced Trip Preparation Error: ' . $e->getMessage(), [
                'request_data' => $request->validated(),
                'user_id' => Auth::guard('api')->id()
            ]);

            return $this->error(500, __('dashboard.enhanced_trip_preparation_error'));
        }
    }

    /**
     * Get prepared trip details
     */
    public function getPreparedTrip(string $token): JsonResponse
    {
        $prepareTrip = EnhancedPrepareTrip::where('token', $token)
            ->forUser(Auth::guard('api')->id())
            ->with(['startRegion', 'endRegion'])
            ->first();

        if (!$prepareTrip) {
            return $this->error(404, __('dashboard.prepared_trip_not_found'));
        }

        return $this->success(
            new EnhancedPrepareTripResource($prepareTrip),
            200,
            __('dashboard.prepared_trip_details')
        );
    }

    /**
     * Save prepared trip as permanent trip
     */
    public function saveTrip(SaveTripRequest $request): JsonResponse
    {
        try {
            $prepareTrip = EnhancedPrepareTrip::where('token', $request->prepare_token)
                ->forUser(Auth::guard('api')->id())
                ->active()
                ->first();

            if (!$prepareTrip) {
                return $this->error(404, __('dashboard.prepared_trip_not_found'));
            }

            // Check if trip with same name already exists for user
            $existingTrip = EnhancedTrip::where('user_id', Auth::guard('api')->id())
                ->where('name', $request->name)
                ->exists();

            if ($existingTrip) {
                return $this->error(422, __('dashboard.trip_name_exists'));
            }

            $trip = $this->tripPlanningService->saveTrip($prepareTrip, $request->validated());

            return $this->success(
                new EnhancedTripResource($trip->load(['days', 'startRegion', 'endRegion'])),
                201,
                __('dashboard.trip_saved_successfully')
            );
        } catch (\Exception $e) {
            Log::error('Enhanced Trip Save Error: ' . $e->getMessage(), [
                'request_data' => $request->validated(),
                'user_id' => Auth::guard('api')->id()
            ]);

            return $this->error(500, __('dashboard.trip_save_error'));
        }
    }

    /**
     * Get user's saved trips
     */
    public function getMyTrips(Request $request): JsonResponse
    {
        $query = EnhancedTrip::forUser(Auth::guard('api')->id())
            ->with(['startRegion', 'endRegion'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $trips = $query->paginate($request->get('per_page', 10));

        return $this->success([
            'trips' => EnhancedTripResource::collection($trips->items()),
            'pagination' => [
                'current_page' => $trips->currentPage(),
                'last_page' => $trips->lastPage(),
                'per_page' => $trips->perPage(),
                'total' => $trips->total(),
            ]
        ], 200, __('dashboard.my_enhanced_trips'));
    }

    /**
     * Get user's prepared trips
     */
    public function getMyPreparedTrips(Request $request): JsonResponse
    {
        $query = EnhancedPrepareTrip::forUser(Auth::guard('api')->id())
            ->with(['startRegion', 'endRegion'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $query->whereHas('startRegion', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhereHas('endRegion', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $preparedTrips = $query->paginate($request->get('per_page', 10));

        return $this->success([
            'prepared_trips' => EnhancedPrepareTripResource::collection($preparedTrips->items()),
            'pagination' => [
                'current_page' => $preparedTrips->currentPage(),
                'last_page' => $preparedTrips->lastPage(),
                'per_page' => $preparedTrips->perPage(),
                'total' => $preparedTrips->total(),
            ]
        ], 200, __('dashboard.my_prepared_trips'));
    }

    /**
     * View trip details
     */
    public function viewTrip(string $token): JsonResponse
    {
        $trip = EnhancedTrip::where('token', $token)
            ->with([
                'days.morningPlaces.city',
                'days.morningPlaces.region',
                'days.eveningPlaces.city',
                'days.eveningPlaces.region',
                'startRegion',
                'endRegion'
            ])
            ->first();

        if (!$trip) {
            return $this->error(404, 'الرحلة غير موجودة');
        }

        // Check if user owns this trip or if it's public
        if ($trip->user_id !== Auth::guard('api')->id() && $trip->status !== 'public') {
            return $this->error(403, __('dashboard.trip_access_denied'));
        }

        return $this->success(
            new EnhancedTripResource($trip),
            200,
            __('dashboard.trip_details')
        );
    }

    /**
     * Delete a trip
     */
    public function deleteTrip(string $token): JsonResponse
    {
        try {
            $trip = EnhancedTrip::where('token', $token)
                ->forUser(Auth::guard('api')->id())
                ->first();

            if (!$trip) {
                return $this->error(404, __('dashboard.trip_not_found'));
            }

            DB::beginTransaction();

            // Delete related trip days and pivot records
            $trip->days()->each(function ($day) {
                $day->morningPlaces()->detach();
                $day->eveningPlaces()->detach();
                $day->delete();
            });

            // Delete the trip
            $trip->delete();

            DB::commit();

            return $this->success(null, 200, __('dashboard.trip_deleted_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Enhanced Trip Delete Error: ' . $e->getMessage(), [
                'token' => $token,
                'user_id' => Auth::guard('api')->id()
            ]);

            return $this->error(500, __('dashboard.trip_delete_error'));
        }
    }

    /**
     * Delete a prepared trip
     */
    public function deletePreparedTrip(string $token): JsonResponse
    {
        $prepareTrip = EnhancedPrepareTrip::where('token', $token)
            ->forUser(Auth::guard('api')->id())
            ->first();

        if (!$prepareTrip) {
            return $this->error(404, __('dashboard.prepared_trip_not_found'));
        }

        $prepareTrip->delete();

        return $this->success(null, 200, __('dashboard.prepared_trip_deleted_successfully'));
    }

    /**
     * Update trip status
     */
    public function updateTripStatus(Request $request, string $token): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:active,completed,cancelled'
        ]);

        $trip = EnhancedTrip::where('token', $token)
            ->forUser(Auth::guard('api')->id())
            ->first();

        if (!$trip) {
            return $this->error(404, __('dashboard.trip_not_found'));
        }

        $trip->update(['status' => $request->status]);

        return $this->success(
            new EnhancedTripResource($trip->load(['startRegion', 'endRegion'])),
            200,
            __('dashboard.trip_status_updated_successfully')
        );
    }

    /**
     * Get trip statistics for user
     */
    public function getTripStatistics(): JsonResponse
    {
        $userId = Auth::guard('api')->id();

        $stats = [
            'total_trips' => EnhancedTrip::forUser($userId)->count(),
            'active_trips' => EnhancedTrip::forUser($userId)->where('status', 'active')->count(),
            'completed_trips' => EnhancedTrip::forUser($userId)->where('status', 'completed')->count(),
            'prepared_trips' => EnhancedPrepareTrip::forUser($userId)->active()->count(),
            'total_places_visited' => EnhancedTrip::forUser($userId)
                ->with('days')
                ->get()
                ->sum('total_places'),
        ];

        return $this->success($stats, 200, __('dashboard.enhanced_trip_statistics'));
    }
}
