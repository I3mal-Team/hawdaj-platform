<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Exports\PlacesExport;
use App\Http\Requests\Dashboard\StorePlaceRequest;
use App\Http\Requests\Dashboard\UpdatePlaceRequest;
use App\Models\Category;
use App\Models\City;
use App\Models\Place;
use App\Models\Price;
use App\Models\Rate;
use App\Models\Region;
use App\Models\Store;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PlacesController extends Controller
{
    //
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

        $regions = Region::all();
        $cities = City::all();
        $categories = Category::all();

        $places = Place::with('user')
            ->when($request->title, function ($q) use ($request) {
                return $q->whereTranslationLike('title', '%' . $request->title . '%');
            })
            ->when($request->region_id, function ($qq) use ($request) {
                return $qq->where('region_id', $request->region_id);
            })
            ->when($request->city_id, function ($qqq) use ($request) {
                return $qqq->where('city_id', $request->city_id);
            })
            ->when($request->user_places, function ($q) {
                return $q->whereNotNull('user_id');
            });
        if (request('season')) {
            $places = $places->where('seasons', 'like', '%' . request('season') . '%');
        }
        if (request('category_id')) {
            $x = '[' . request('category_id') . ']';
            $places = $places->whereRaw("JSON_CONTAINS(categories, '" . $x . "' )");
        }
        if (request('archive', 0)) {
            $places = $places->onlyTrashed();
        }

        if ($regionId = auth()->user()->region_id) {
            $places->where('region_id', $regionId);
        }

        $places = $places->latest();

        $perPage = request('per_page') == -1 ? $places->count() : request('per_page' , 10);

        $places = $places->paginate($perPage);

        $seasons = [
            "طوال السنة",
            "موسم الربيع",
            "موسم الصيف",
            "موسم الخريف",
            "موسم الشتاء"
        ];

        $title =  trans('dashboard.places');

        return view('dashboard.places.index', compact('places', 'regions', 'cities', 'categories', 'seasons', 'title'));
    }


    public function show(int $id)
    {
        $place = Place::find($id);

        if (!$place) {
            return view('dashboard.places.show', compact('place'))->with([
                'message' => trans('dashboard.place_not_found'),
                'type' => 'error'
            ]);
        }

        return view('dashboard.places.show', compact('place'));
    }

    public function activate(Request $request)
    {

        $active = $request->input('checked');
        $place_id = $request->input('placeId');

        if ($place_id) {
            $place = Place::find($place_id);

            if ($active == 1) {
                $place->update([
                    'active' => 1,
                ]);
            } else {
                $place->update([
                    'active' => 0,
                ]);
            }
            return response()->json([
                'status' => true,
                'message' => trans('dashboard.activation_updated'),
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "activation cant be updated",
            ]);
        }
    }

    public function updateStatus(Request $request, $id)
    {
//        try {
            $request->validate([
                'status' => 'required|in:pending,accepted,rejected',
                'rejected_reason' => 'required_if:status,rejected'
            ]);

            $place = Place::findOrFail($id);

            $place->update([
                'status' => $request->status,
                'rejected_reason' => $request->status === 'rejected' ? $request->rejected_reason : null
            ]);

            return redirect()->back()->with([
                'message' => trans('dashboard.status_updated_successfully'),
                'type' => 'success'
            ]);
//        } catch (\Exception $e) {
//            return redirect()->back()->with([
//                'message' => trans('dashboard.error_occurred'),
//                'type' => 'error'
//            ]);
//        }
    }

    public function create()
    {
        return view('dashboard.places.create', [
            'title' => __('dashboard.create_place'),
            'categories' => Category::all(),
            'regions' => Region::all(),
            'cities' => City::all(),
            'prices' => Price::all(),
            'places' => Place::all(),
            'stores' => Store::all(),
            'key_words' => Arr::flatten(Place::whereNotNull('key_words')->select('key_words')->get()->pluck('key_words')->toArray())
        ]);
    }

    public function store(StorePlaceRequest $request)
    {
        DB::beginTransaction();
        try {

            $data = $request->except('_token', 'link');

            $data['featured'] = isset($request->featured) ? 1 : 0;
            $data['active'] = isset($request->active) ? 1 : 0;

            if ($request->address_type == 'map') {
                $data['address'] = request('address');
                $data['lat'] = request('lat');
                $data['long'] = request('long');
            } elseif ($request->address_type == 'link') {
                $data['address'] = request('link');
                $link = explode('@', $data['address']);
                if (is_array($link)) {
                    $lat_long = explode(',', end($link));
                    if (is_array($lat_long) && count($lat_long) == 3) {
                        $data['lat'] = isset($lat_long[0]) && is_numeric($lat_long[0]) ? $lat_long[0] : null;
                        $data['long'] = isset($lat_long[1]) && is_numeric($lat_long[1]) ? $lat_long[1] : null;
                    }
                }
            } else {
                $data['address'] = request('address');
                $data['lat'] = request('lat');
                $data['long'] = request('long');
            }


            if (request('categories')) {
                $data['categories'] = collect($request->categories)->map(fn($i) => (int)$i);
            }

            if (request('seasons')) {
                $data['seasons'] = is_array($request->seasons) ? implode(',', collect($request->seasons)->map(function ($i) {
                    return __('dashboard.' . $i, [], 'ar');
                })->toArray()) : null;
            }

            if (request('new_key_words', null)) {

                foreach (request('new_key_words', []) as $key => $value) {
                    $new[] = $value['new_key_words'];
                }
                $data['key_words'] = array_merge(request('key_words', []), $new);
                unset($data['new_key_words']);
            }

            $data['user_id'] = auth()->user()->id;
            $data['order_id'] = (int) ($data['order_id'] ?? 0);

            $place = Place::create($data);

            if (request()->hasFile('image')) {
                $place->clearMediaCollection('image');
                $place->addMediaFromRequest('image')->toMediaCollection('image');
            }

            DB::commit();
            if ($place) {
                return redirect(route('dashboard.places.edit', $place->id))->with([
                    'message' => trans('dashboard.places_added_successfully'),
                ]);
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }


    public function edit(Place $place)
    {
        $reg = isset($place->region->cities) ? $place->region->cities : [];

        $inside_map = isset($place['distance']) && $place['distance'] > 0 ? Place::select('id', 'lat', 'long')->where('id', '!=', $place->id)->where('active', 1)->get()->map(function ($i) use ($place) {
            if (round(distance($place['lat'], $place['long'], $i['lat'], $i['long'], "M"), 1) <= $place['distance'] ?? 0) {
                return $i['id'];
            }
        }) : [];

        $all_related_items = array_unique(array_merge(array_filter($inside_map ? $inside_map->toArray() : []) ?? [], $place->related_places ?? []));

        return view('dashboard.places.edit', [
            'title' => __('dashboard.edit_place'),
            'categories' => Category::all(),
            'regions' => Region::all(),
            'prices' => Price::all(),
            'places' => Place::all(),
            'stores' => Store::all(),
            'data' => $place,
            'mycities' => $reg,
            'all_related_items' => $all_related_items
        ]);
    }

    public function related(Request $request, $id)
    {
        try {
            $request->validate([
                'related_places' => 'nullable',
                'distance' => 'nullable',
            ]);

            $inputs = $request->only(['related_places', 'distance']);
            $inputs['related_places'] = isset($inputs['related_places']) ? $inputs['related_places'] : null;
            $data = Place::find($id);
            $data->update($inputs);

            return redirect()->back()->with([
                'message' => trans('dashboard.updated_successfully'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }

    public function near(Request $request, $id)
    {
        try {
            $request->validate([
                'near_stores' => 'required',
            ]);

            $inputs = $request->only(['near_stores']);
            $inputs['near_stores'] = isset($inputs['near_stores']) ? $inputs['near_stores'] : null;
            $data = Place::find($id);
            $data->update($inputs);

            return redirect()->back()->with([
                'message' => trans('dashboard.updated_successfully'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }


    public function update(UpdatePlaceRequest $request, $id)
    {
        try {

            $data = $request->except(['_token', '_method', 'link', ...locales()]);

            if (!empty($request->city_id)) {
                $data['city_id'] = $request->city_id;
            } else {
                unset($data['city_id']);
            }

            if ($request->address_type == 'map') {
                $data['address'] = request('address');
                $data['lat'] = request('lat');
                $data['long'] = request('long');
            } elseif ($request->address_type == 'link') {
                $data['address'] = request('link');
                $link = explode('@', $data['address']);
                if (is_array($link)) {
                    $lat_long = explode(',', end($link));
                    if (is_array($lat_long) && count($lat_long) == 3) {
                        $data['lat'] = isset($lat_long[0]) && is_numeric($lat_long[0]) ? $lat_long[0] : null;
                        $data['long'] = isset($lat_long[1]) && is_numeric($lat_long[1]) ? $lat_long[1] : null;
                    }
                }
            } else {
                $data['address'] = request('address');
                $data['lat'] = request('lat');
                $data['long'] = request('long');
            }

            if (isset($request->active)) {
                $data['active'] = 1;
            } else {
                $data['active'] = 0;
            }

            $data['featured'] = $request->featured ? 1 : 0;
            $data['prefered'] = $request->prefered ? 1 : 0;
            $data['order_id'] = (int) $request->input('order_id', 0);

            if (request('categories')) {
                $data['categories'] = collect($request->categories)->map(fn($i) => (int)$i);
            }

            if (request('seasons')) {
                $data['seasons'] = is_array($request->seasons) ? implode(',', collect($request->seasons)->map(function ($i) {
                    return __('dashboard.' . $i, [], 'ar');
                })->toArray()) : null;
            }

            if (request()->hasFile('image')) {
                $data['image'] = UploadService::store($request->image, 'places');
            }

            $place = Place::where('id', $id)->first();
            $place->update($data);

            DB::commit();

            return redirect()->back()->with([
                'message' => trans('dashboard.updated_successfully'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }

    public function destroy(Place $place)
    {

        try {
            $place->delete();
            return redirect()->back()->with([
                'message' => trans('dashboard.places_deleted_successfully'),
            ]);
        } catch (\Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
             Place::withTrashed()->find($id)->restore();
            return response()->json([
               'message' =>  trans('dashboard.restore_successfully')
            ]);
        } catch (\Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    public function force_destroy($id)
    {
        try {
            return Place::withTrashed()->find($id)->forceDelete();
        } catch (\Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    public function destroy_selected(Request $request)
    {
        if (isset($request->array_ids) && request('type', null) == 'reviews') {
            Rate::whereIn('id', is_array($request->array_ids) ? $request->array_ids : json_decode($request->array_ids))->delete();
            return response()->json([
                'status' => true,
                'message' => trans('dashboard.rates_deleted_successfully'),
            ]);
        } else if (isset($request->array_ids)) {
            if (request('archive')) {
                Place::withTrashed()->whereIn('id', is_array($request->array_ids) ? $request->array_ids : json_decode($request->array_ids))->forceDelete();
            } else {
                Place::whereIn('id', is_array($request->array_ids) ? $request->array_ids : json_decode($request->array_ids))->delete();
            }
            return response()->json([
                'status' => true,
                'message' => trans('dashboard.places_deleted_successfully'),
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => trans('dashboard.places_deleted_successfully'),
            ]);
        }
    }

    public function reviews($id)
    {
        $title = __('dashboard.rates');
        $reviews = Rate::where('parent_id', $id)->where('type', 'places')->paginate(10);
        return view('dashboard.places.reviews', compact('reviews', 'title'));
    }

    public function destroy_reviews($id)
    {
        try {
            Rate::find($id)->delete();
            return redirect()->back()->with([
                'message' => trans('dashboard.rate_deleted_successfully'),
            ]);
        } catch (\Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $places = Place::when($request->title, function ($q) use ($request) {
            return $q->whereTranslationLike('title', '%' . $request->title . '%');
        })
            ->when($request->region_id, function ($qq) use ($request) {
                return $qq->where('region_id', $request->region_id);
            })
            ->when($request->city_id, function ($qqq) use ($request) {
                return $qqq->where('city_id', $request->city_id);
            });

        if (request('season')) {
            $places = $places->where('seasons', 'like', '%' . request('season') . '%');
        }
        if (request('category_id')) {
            $x = '[' . request('category_id') . ']';
            $places = $places->whereRaw("JSON_CONTAINS(categories, '" . $x . "' )");
        }
        if (request('archive', 0)) {
            $places = $places->onlyTrashed();
        }

        if ($regionId = auth()->user()->region_id) {
            $places->where('region_id', $regionId);
        }

        $places = $places->latest()->get();

        return Excel::download(new PlacesExport($places), 'places_' . date('Y-m-d_H-i-s') . '.xlsx');
    }
}
