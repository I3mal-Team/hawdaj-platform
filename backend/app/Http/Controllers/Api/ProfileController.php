<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Guides\GuideResource;
use App\Http\Resources\Profile\UserSocialResource;
use App\Http\Resources\Stories\StoryCollection;
use App\Http\Resources\Trips\TripCollection;
use App\Models\Guide;
use App\Models\Story;
use App\Models\Trip;
use App\Models\UserSocial;
use App\Models\UserLocation;
use Illuminate\Http\Request;
use App\Services\UploadService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\Profile\UserResource;

class ProfileController extends ApiModalController
{
    public function getProfile()
    {
        $is_login = auth('api')->check();

        if (! $is_login) {
            return $this->error(401, trans('dashboard.please_sign_up_first'));
        }

        $user = auth('api')->user();

        return $this->success(new UserResource($user),200, __('dashboard.user_profile'));
    }

    public function getProfilePageData()
    {
        $is_login = auth('api')->check();

        if (! $is_login) {
            return $this->error(401, trans('dashboard.please_sign_up_first'));
        }

        $user = auth('api')->user();
        $userData = new UserResource($user);

        $userGuideData = null;
        $userGuide = Guide::where('user_id', $user->id)->first();
        if($userGuide) {
            $userGuideData = new GuideResource($userGuide);
        }

        $userSocial = UserSocial::where('user_id', $user->id)->get();
        $social = $userSocial ? new UserSocialResource($userSocial) : null;
        $hasSocial = (bool) $userSocial;

        $stories = Story::where('user_id', $user->id);
        $totalStories = $stories->count();
        $stories = $stories->paginate(request('per_page' , 10))->onEachSide(2);
        $stories = new StoryCollection($stories);


        $trips = Trip::where('user_id', $user->id);
        $totalTrips = $trips->count();
        $trips = $trips->paginate(request('per_page' , 10));
        $trips = new TripCollection($trips);

        return $this->success([
            'PersonalData' => $userData,
            'userGuideData' => $userGuideData,
            'social' => $social,
            'hasSocial' => $hasSocial,
            'myStories' => $stories,
            'myTrips' => $trips,
            'totalStories' => $totalStories,
            'totalTrips' => $totalTrips
        ],200, __('dashboard.user_profile_page'));
    }

    public function updateProfile(Request $request)
    {
        $is_login = auth('api')->check();

        if (! $is_login) {
            return $this->error(401, trans('dashboard.please_sign_up_first'));
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'nullable',
            'gender' => 'nullable|string',
            // 'facebook' => ['nullable', 'string', 'min:3', 'url'],
            'x' => ['nullable', 'string', 'min:3', 'url'],
            'twitter' => ['nullable', 'string', 'min:3', 'url'],
            'instagram' => ['nullable', 'string', 'min:3', 'url'],
            'linkedin' => ['nullable', 'string', 'min:3', 'url'],
            'youtube' => ['nullable', 'string', 'min:3', 'url'],
            'tiktok' => ['nullable', 'string', 'min:3', 'url'],
        ], [], [
            'first_name' => trans('dashboard.first_name'),
            'last_name' => trans('dashboard.last_name'),
            'email' => trans('dashboard.email'),
            'phone' => trans('dashboard.phone'),
            'gender' => trans('dashboard.gender')
        ]);

        if ($validator->fails()) {
            return $this->error(422 , $validator->errors()->first());
        }

        $user = auth('api')->user();

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
        ]);

        $userSocial = UserSocial::updateOrCreate(
            ['user_id' => $user->id],
            [
                // 'facebook' => $request->facebook,
                'x' => $request->x,
                'twitter' => $request->twitter,
                'instagram' => $request->instagram,
                'linkedin' => $request->linkedin,
                'youtube' => $request->youtube,
                'tiktok' => $request->tiktok,
            ]
        );

        return $this->success([
            'personalData' => new UserResource($user),
            'social' => new UserSocialResource($userSocial),
        ],200, __('dashboard.profile_updated_successfully'));
    }

    public function updateFcmToken(Request $request)
    {
        if (!auth('api')->check()) {
            return $this->error(401, trans('dashboard.please_sign_up_first'));
        }

        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required|string|max:512',
        ], [], [
            'fcm_token' => 'FCM token',
        ]);

        if ($validator->fails()) {
            return $this->error(422, $validator->errors()->first());
        }

        $user = auth('api')->user();
        $user->update([
            'fcm_token' => $request->input('fcm_token'),
        ]);

        return $this->success(null, 200, __('dashboard.fcm_token_saved'));
    }

    public function updatePassword(Request $request)
    {
        $is_login = auth('api')->check();

        if (! $is_login) {
            return $this->error(401, trans('dashboard.please_sign_up_first'));
        }

        $validator = Validator::make($request->all(), [
            'currentPassword' => 'required|string',
            'password' => 'required|string|confirmed|min:6',
        ],[], [
            'currentPassword' => trans('dashboard.current_password'),
            'password' => trans('dashboard.password')
        ]);

        if ($validator->fails()) {
            return $this->error(422 , $validator->errors()->first());
        }

        $user = auth('api')->user();

        if (!Hash::check($request->currentPassword, $user->password)) {
            return $this->error(422, __('dashboard.current_password_incorrect'));
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return $this->success(new UserResource($user),200, __('dashboard.password_updated_successfully'));
    }

    public function updateSocial(Request $request)
    {
        $is_login = auth('api')->check();

        if (! $is_login) {
            return $this->error(401, trans('dashboard.please_sign_up_first'));
        }

        $validator = Validator::make($request->all(), [
            // 'facebook' => ['nullable', 'string', 'min:3', 'url'],
            'x' => ['nullable', 'string', 'min:3', 'url'],
            'twitter' => ['nullable', 'string', 'min:3', 'url'],
            'instagram' => ['nullable', 'string', 'min:3', 'url'],
            'linkedin' => ['nullable', 'string', 'min:3', 'url'],
            'youtube' => ['nullable', 'string', 'min:3', 'url'],
            'tiktok' => ['nullable', 'string', 'min:3', 'url'],
        ]);

        if ($validator->fails()) {
            return $this->error(422 , $validator->errors()->first());
        }

        $user = auth('api')->user();

        $userSocial = UserSocial::updateOrCreate(
            ['user_id' => $user->id],
            [
                // 'facebook' => $request->facebook,
                'x' => $request->x,
                'twitter' => $request->twitter,
                'instagram' => $request->instagram,
                'linkedin' => $request->linkedin,
                'youtube' => $request->youtube,
                'tiktok' => $request->tiktok,
            ]
        );

        return $this->success(new UserSocialResource($userSocial),200, __('dashboard.social_media_updated_successfully'));
    }

    public function updatePhoto(Request $request)
    {
        $is_login = auth('api')->check();

        if (! $is_login) {
            return $this->error(401, trans('dashboard.please_sign_up_first'));
        }

        $user = auth('api')->user();

        if ($request->hasFile('photo')) {
            $validator = Validator::make($request->all(), [
                'photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
            ]);

            if ($validator->fails()) {
                return $this->error(422 , $validator->errors()->first());
            }

            $user->clearMediaCollection('photo');
            $user->addMediaFromRequest('photo')->toMediaCollection('photo');
        } elseif ($request->has('photo') && ($request->photo === null || $request->photo === '')) {
            $user->clearMediaCollection('photo');
        }

        $user->refresh();

        return $this->success(new UserResource($user),200, __('dashboard.photo_updated_successfully'));
    }

    public function deleteProfile(Request $request)
    {
        $is_login = auth('api')->check();

        if (! $is_login) {
            return $this->error(401, trans('dashboard.please_sign_up_first'));
        }

        $user = auth('api')->user();

        // Delete related data first
        UserSocial::where('user_id', $user->id)->delete();
        Guide::where('user_id', $user->id)->delete();
        Story::where('user_id', $user->id)->delete();
        Trip::where('user_id', $user->id)->delete();

        // Delete the user
        $user->delete();

        return $this->success(null, 200, __('dashboard.profile_deleted_successfully'));
    }

    public function updateLocation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ], [], [
            'lat' => trans('dashboard.latitude'),
            'lng' => trans('dashboard.longitude'),
        ]);
    
        if ($validator->fails()) {
            return $this->error(422, $validator->errors()->first());
        }
    
        $isAuthenticated = auth('api')->check();
        $userId = $isAuthenticated ? auth('api')->id() : null;
        $userIp = $request->ip() ?? $request->header('X-Forwarded-For') ?? 'unknown';
        
        $identifier = $userId ? "user_{$userId}" : "ip_" . md5($userIp);
        $cacheKey = "user_location_{$identifier}";
        $cacheTTL = 5 * 60;
    
        $cachedLocation = Cache::get($cacheKey);
        
        if ($cachedLocation && isset($cachedLocation['lat']) && isset($cachedLocation['lng'])) {
            $distance = $this->distanceInMeters(
                $cachedLocation['lat'],
                $cachedLocation['lng'],
                $request->lat,
                $request->lng
            );
            
            $lastUpdateTime = isset($cachedLocation['updated_at']) 
                ? \Carbon\Carbon::parse($cachedLocation['updated_at'])
                : now();
    
            if ($distance < 30 && $lastUpdateTime->diffInMinutes(now()) < 5) {
                return $this->success([
                    'location' => [
                        'lat' => $cachedLocation['lat'],
                        'lng' => $cachedLocation['lng'],
                    ]
                ], 200, __('dashboard.location_not_changed'));
            }
        }
    
        if ($isAuthenticated && $userId) {
            $userLocation = UserLocation::updateOrCreate(
                ['user_id' => $userId],
                [
                    'lat' => $request->lat,
                    'lng' => $request->lng,
                ]
            );
    
            Cache::put($cacheKey, [
                'lat' => $userLocation->lat,
                'lng' => $userLocation->lng,
                'updated_at' => $userLocation->updated_at->toIso8601String(),
            ], $cacheTTL);
    
            return $this->success([
                'location' => [
                    'lat' => $userLocation->lat,
                    'lng' => $userLocation->lng,
                ]
            ], 200, __('dashboard.location_updated_successfully'));
        } else {
            $locationData = [
                'lat' => (float) $request->lat,
                'lng' => (float) $request->lng,
                'updated_at' => now()->toIso8601String(),
            ];
            
            Cache::put($cacheKey, $locationData, $cacheTTL);
    
            return $this->success([
                'location' => [
                    'lat' => $locationData['lat'],
                    'lng' => $locationData['lng'],
                ]
            ], 200, __('dashboard.location_updated_successfully'));
        }
    }

    private function distanceInMeters($lat1, $lng1, $lat2, $lng2)
{
    $earthRadius = 6371000;

    $dLat = deg2rad($lat2 - $lat1);
    $dLng = deg2rad($lng2 - $lng1);

    $a = sin($dLat / 2) * sin($dLat / 2) +
        cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
        sin($dLng / 2) * sin($dLng / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return $earthRadius * $c;
}
}
