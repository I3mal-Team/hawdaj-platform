<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\LandMark;
use App\Models\Place;
use App\Models\Store;
use App\Models\ZadElgadel;
use App\Models\Swalef;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Stichoza\GoogleTranslate\GoogleTranslate;

class UsersPlacesController extends Controller
{
    private const POINTS_REWARD = 10;
    private const DEFAULT_ADDRESS_TYPE = 'link';
    private const DEFAULT_DISPLAY_TYPE = 'banner';

    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:read-places', ['only' => ['index']]);
        $this->middleware('permission:create-places', ['only' => ['create', 'store']]);
        $this->middleware('permission:update-places', ['only' => ['edit', 'update', 'activate']]);
        $this->middleware('permission:delete-places', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        visit(['ip' => request()->ip(), 'page' => 'places', 'visits' => 1]);

        $query = LandMark::with('user');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', '%' . $search . '%')
                            ->orWhere('last_name', 'like', '%' . $search . '%')
                            ->orWhere('phone', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });
            });
        }

        $perPage = request('per_page') == -1 
            ? $query->count() 
            : request('per_page', 10);

        $places = $query->latest()->paginate($perPage)->withQueryString();
        
        $title = trans('dashboard.user_places');

        return view('dashboard.users_places.index', compact('places', 'title'));
    }

    public function show(LandMark $users_place)
    {
        $users_place->load('user');
        $title = trans('dashboard.user_places');

        return view('dashboard.users_places.show', [
            'title' => $title,
            'place' => $users_place,
        ]);
    }

    public function approve(int $id)
    {
        try {
            DB::transaction(function () use ($id) {
                $landMark = LandMark::findOrFail($id);
                $user = User::findOrFail($landMark->user_id);

                if (! $landMark->active) {
                    $this->handleActivation($landMark, $user);
                }
            });

            return redirect()->route('dashboard.users_places.index')->with([
                'message' => trans('dashboard.activation_accepted'),
                'status' => 'success',
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->with([
                'message' => $e->getMessage(),
                'status' => 'error',
            ]);
        }
    }

    public function activate(Request $request)
    {
        $placeId = $request->input('place_id');
        
        if (!$placeId) {
            return $this->errorResponse('activation cant be updated');
        }

        return DB::transaction(function () use ($request, $placeId) {
            $landMark = LandMark::findOrFail($placeId);
            $user = User::findOrFail($landMark->user_id);

            if ($request->input('checked') == 1) {
                $typeNames = [
                    'place' => trans('dashboard.place'),
                    'store' => trans('dashboard.store'),
                    'zad' => trans('dashboard.zad'),
                    'swalef' => trans('dashboard.swalef'),
                    'event' => trans('dashboard.event'),
                ];
                
                $typeName = $typeNames[$landMark->type] ?? $landMark->type;
                
                $this->handleActivation($landMark, $user);
                
                $message = trans('dashboard.activation_success_message', ['type' => $typeName]);
                
                return response()->json([
                    'status' => true,
                    'message' => $message,
                    'deleted' => true,
                    'id' => $placeId,
                ]);
            } else {
                $this->handleDeactivation($landMark, $user);
                return $this->successResponse(trans('dashboard.activation_accepted'));
            }
        });
    }

    public function force_destroy($id)
    {
        $place = LandMark::findOrFail($id);
        $place->delete();
        
        return back()->with([
            'message' => trans('dashboard.places_deleted_successfully'),
        ]);
    }

    private function handleActivation(LandMark $landMark, User $user): void
    {
        $this->copyLandMarkToTable($landMark);
        $landMark->delete();
        $this->updateUserPoints($user, self::POINTS_REWARD);
    }

    private function handleDeactivation(LandMark $landMark, User $user): void
    {
        $landMark->update(['active' => 0]);
        $this->deactivateCorrespondingRecord($landMark);
        $this->updateUserPoints($user, -self::POINTS_REWARD);
    }

    private function updateUserPoints(User $user, int $points): void
    {
        $user->increment('total_points', $points);
    }

    private function copyLandMarkToTable(LandMark $landMark): void
    {
        $typeHandlers = [
            'place' => fn() => $this->copyToPlace($landMark),
            'store' => fn() => $this->copyToStore($landMark),
            'zad' => fn() => $this->copyToZad($landMark),
            'swalef' => fn() => $this->copyToSwalef($landMark),
            'event' => fn() => $this->copyToEvent($landMark),
        ];

        $handler = $typeHandlers[$landMark->type] ?? null;
        if ($handler) {
            $handler();
        }
    }

    private function deactivateCorrespondingRecord(LandMark $landMark): void
    {
        $typeHandlers = [
            'place' => fn() => $this->deactivatePlace($landMark),
            'store' => fn() => $this->deactivateStore($landMark),
            'zad' => fn() => $this->deactivateZad($landMark),
            'swalef' => fn() => $this->deactivateSwalef($landMark),
            'event' => fn() => $this->deactivateEvent($landMark),
        ];

        $handler = $typeHandlers[$landMark->type] ?? null;
        if ($handler) {
            $handler();
        }
    }

    private function copyToPlace(LandMark $landMark): void
    {
        if ($this->recordExists(Place::class, $landMark->user_id, $landMark->address)) {
            return;
        }

        $place = $this->createRecord(Place::class, $this->getPlaceData($landMark));
        $this->setTranslations($place, $landMark->title, $landMark->description);
        $this->copyImage($landMark, $place);
    }

    private function copyToStore(LandMark $landMark): void
    {
        if ($this->recordExists(Store::class, $landMark->user_id, $landMark->address)) {
            return;
        }

        $store = $this->createRecord(Store::class, $this->getStoreData($landMark));
        $this->setTranslations($store, $landMark->title, $landMark->description);
        $this->copyImage($landMark, $store);
    }

    private function copyToZad(LandMark $landMark): void
    {
        if ($this->recordExists(ZadElgadel::class, $landMark->user_id, $landMark->address)) {
            return;
        }

        $zad = $this->createRecord(ZadElgadel::class, $this->getZadData($landMark));
        $this->setTranslations($zad, $landMark->title, $landMark->description);
        $this->copyImage($landMark, $zad);
    }

    private function copyToSwalef(LandMark $landMark): void
    {
        if (Swalef::whereTranslation('title', $landMark->title, 'ar')->exists()) {
            return;
        }

        $swalef = $this->createRecord(Swalef::class, $this->getSwalefData($landMark));
        $this->setTranslations($swalef, $landMark->title, $landMark->description);
        $this->copyImage($landMark, $swalef);
    }

    private function copyToEvent(LandMark $landMark): void
    {
        if ($this->recordExists(Event::class, $landMark->user_id, $landMark->address)) {
            return;
        }

        $event = $this->createRecord(Event::class, $this->getEventData($landMark));
        $this->setTranslations($event, $landMark->title, $landMark->description);
        $this->copyImage($landMark, $event);
    }

    private function recordExists(string $modelClass, ?int $userId, ?string $address): bool
    {
        return $modelClass::where('user_id', $userId)
            ->where('address', $address)
            ->exists();
    }

    private function createRecord(string $modelClass, array $data)
    {
        return $modelClass::withoutEvents(function () use ($modelClass, $data) {
            return $modelClass::create($data);
        });
    }

    private function getPlaceData(LandMark $landMark): array
    {
        [$lat, $long] = $this->extractLatLong($landMark->address, $landMark->address_type);

        return [
            'address' => $landMark->address,
            'address_type' => $landMark->address_type ?? self::DEFAULT_ADDRESS_TYPE,
            'user_id' => $landMark->user_id,
            'active' => 1,
            'lat' => $lat,
            'long' => $long,
        ];
    }

    private function getStoreData(LandMark $landMark): array
    {
        [$lat, $long] = $this->extractLatLong($landMark->address, $landMark->address_type);

        return [
            'address' => $landMark->address,
            'address_type' => $landMark->address_type ?? self::DEFAULT_ADDRESS_TYPE,
            'user_id' => $landMark->user_id,
            'active' => 1,
            'lat' => $lat,
            'long' => $long,
        ];
    }

    private function getZadData(LandMark $landMark): array
    {
        [$lat, $long] = $this->extractLatLong($landMark->address, $landMark->address_type);

        return [
            'address' => $landMark->address,
            'address_type' => $landMark->address_type ?? self::DEFAULT_ADDRESS_TYPE,
            'user_id' => $landMark->user_id,
            'active' => 1,
            'lat' => $lat,
            'long' => $long,
        ];
    }

    private function getSwalefData(LandMark $landMark): array
    {
        return [
            'active' => 1,
            'user_id' => $landMark->user_id,
        ];
    }

    private function getEventData(LandMark $landMark): array
    {
        [$lat, $long] = $this->extractLatLong($landMark->address, $landMark->address_type);
        $eventType = $this->mapEventType($landMark->address_type);
        
        $user = $landMark->user;
        $regionId = $user->region_id ?? null;
        $cityId = $user->city_id ?? null;

        return [
            'address' => $landMark->address,
            'type' => $eventType,
            'user_id' => $landMark->user_id,
            'active' => 1,
            'lat' => $lat,
            'long' => $long,
            'display_type' => self::DEFAULT_DISPLAY_TYPE,
            'region_id' => $regionId,
            'city_id' => $cityId,
            'visited' => 0,
        ];
    }

    private function mapEventType(?string $addressType): string
    {
        if ($addressType == 'latlong') {
            return 'latlng';
        }
        return $addressType ?? self::DEFAULT_ADDRESS_TYPE;
    }

    private function extractLatLong(?string $address, ?string $addressType): array
    {
        if ($addressType !== 'link' || !$address) {
            return [null, null];
        }

        $link = explode('@', $address);
        if (count($link) < 2) {
            return [null, null];
        }

        $latLong = explode(',', end($link));
        if (count($latLong) < 2) {
            return [null, null];
        }

        $lat = is_numeric($latLong[0]) ? (float)$latLong[0] : null;
        $long = is_numeric($latLong[1]) ? (float)$latLong[1] : null;

        return [$lat, $long];
    }

    private function copyImage(LandMark $landMark, $model): void
    {
        if ($landMark->hasMedia('image')) {
            $media = $landMark->getFirstMedia('image');
            if ($media) {
                $model->addMediaFromUrl($media->getUrl())->toMediaCollection('image');
            }
            return;
        }

        if ($landMark->image) {
            $imagePath = public_path('uploads/' . $landMark->image);
            if (file_exists($imagePath)) {
                $model->addMedia($imagePath)->toMediaCollection('image');
            }
        }
    }

    private function setTranslations($model, string $title, string $description): void
    {
        $model->translateOrNew('ar')->title = $title;
        $model->translateOrNew('ar')->description = $description;

        foreach (locales('ar') as $locale) {
            $translatedTitle = GoogleTranslate::trans($title, $locale);
            $translatedDescription = GoogleTranslate::trans($description, $locale);
            
            $model->translateOrNew($locale)->title = $translatedTitle;
            $model->translateOrNew($locale)->description = $translatedDescription;
            
            if ($locale === 'en') {
                $model->slug = Str::slug($translatedTitle);
            }
        }
        
        $model->save();
    }

    private function deactivatePlace(LandMark $landMark): void
    {
        Place::where('user_id', $landMark->user_id)
            ->where('address', $landMark->address)
            ->update(['active' => 0]);
    }

    private function deactivateStore(LandMark $landMark): void
    {
        Store::where('user_id', $landMark->user_id)
            ->where('address', $landMark->address)
            ->update(['active' => 0]);
    }

    private function deactivateZad(LandMark $landMark): void
    {
        ZadElgadel::where('user_id', $landMark->user_id)
            ->where('address', $landMark->address)
            ->update(['active' => 0]);
    }

    private function deactivateSwalef(LandMark $landMark): void
    {
        Swalef::where('title', $landMark->title)->update(['active' => 0]);
    }

    private function deactivateEvent(LandMark $landMark): void
    {
        Event::where('user_id', $landMark->user_id)
            ->where('address', $landMark->address)
            ->update(['active' => 0]);
    }

    private function successResponse(string $message): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
        ]);
    }

    private function errorResponse(string $message): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message,
        ]);
    }
}
