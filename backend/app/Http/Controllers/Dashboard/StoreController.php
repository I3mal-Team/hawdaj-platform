<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Exports\StoresExport;
use App\Http\Requests\Dashboard\SaveStoreRequest;
use App\Http\Requests\Dashboard\UpdateStoreRequest;
use App\Models\CategoryOfStore;
use App\Models\City;
use App\Models\Place;
use App\Models\Region;
use App\Models\Store;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class StoreController extends Controller
{
    // set permission
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:read-store', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-store', ['only' => ['create', 'store']]);
        $this->middleware('permission:update-store', ['only' => ['edit', 'update', 'activate', 'updateStatus']]);
        $this->middleware('permission:delete-store', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        visit(['ip' => \request()->ip(), 'page' => 'stores', 'visits' => 1]);

        $allCategories = CategoryOfStore::all();

        $all = Store::with('user')
            ->when($request->title, function ($q) use ($request) {
                return $q->whereTranslationLike('title', '%' . $request->title . '%');
            })->when($request->user_places, function ($q) {
                return $q->whereNotNull('user_id');
            });

        if (request('category_id')) {
            $categories_id = explode(',' , request('category_id'));
            $all->where(function($query)use($categories_id){
                foreach ($categories_id as $category_id) {
                    $query->orWhereRaw("JSON_CONTAINS(categories, '" . $category_id . "' )");
                }
            });
        }

        if (request('archive', 0)) {
            $all = $all->onlyTrashed();
        }

        $all = $all->latest();

        $perPage = request('per_page') == -1 ? $all->count() : request('per_page' , 10);

        $all = $all->paginate($perPage);

        return view('dashboard.stores.index', [
            'title' => trans('dashboard.stores'),
            'stores' => $all,
            'categories' => $allCategories,
        ]);
    }


    public function show(int $id)
    {
        $store = Store::find($id);

        if (!$store) {
            return view('dashboard.stores.show', compact('store'))->with([
                'message' => trans('dashboard.store_not_found'),
                'type' => 'error'
            ]);
        }

        return view('dashboard.stores.show', compact('store'));
    }

    public function activate(Request $request)
    {

        $active = $request->input('checked');
        $store_id = $request->input('storeId');

        if ($store_id) {
            $store = Store::find($store_id);

            if ($active == 1) {
                $store->update([
                    'active' => 1,
                ]);
            } else {
                $store->update([
                    'active' => 0,
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => "activation updated",
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
        $request->validate([
            'status' => 'required|in:pending,accepted,rejected',
            'rejected_reason' => 'required_if:status,rejected'
        ]);

        $store = Store::findOrFail($id);

        $store->update([
            'status' => $request->status,
            'rejected_reason' => $request->status === 'rejected' ? $request->rejected_reason : null
        ]);

        return redirect()->back()->with([
            'message' => trans('dashboard.status_updated_successfully'),
            'type' => 'success'
        ]);
    }


    public function create()
    {
        return view('dashboard.stores.create', [
            'title' => __('dashboard.create_store'),
            'categories' => CategoryOfStore::all(),
            'places' => Place::all(),
            'stores' => Store::all(),
            'regions' => Region::all()
        ]);
    }


    public function store(SaveStoreRequest $request)
    {
        try {
            $data = $request->only([
                'title', 'categories', 'address_type', 'address', 'description',
                'active', 'featured', /* 'facebook_link', */ 'x_link', 'whatsapp', 'Instagram_link',
                'website_link', 'visited', 'lat', 'long', 'region_id', 'city_id', 'order_id',
            ]);


            if (isset($request->active)) {
                $data['active'] = 1;
            } else {
                $data['active'] = 0;
            }

            if (isset($request->featured)) {
                $data['featured'] = 1;
            } else {
                $data['featured'] = 0;
            }

            // Handle con_type (online/local) and convert to is_online
            if (isset($request->con_type)) {
                $data['is_online'] = ($request->con_type == 'online') ? 1 : 0;
            }

            // If con_type is 'local', use address_type from request (could be 'link' or 'map')
            if (isset($request->con_type) && $request->con_type == 'local') {
                if ($request->address_type == 'map') {
                    // If explicitly set to 'map', use map logic
                    $data['address_type'] = 'map';
                    $data['address'] = request('address_place') ?? request('address');
                    $data['lat'] = request('lat') ?: null;
                    $data['long'] = request('long') ?: null;
                } else {
                    // Default to 'link' if not 'map'
                    $data['address_type'] = 'link';
                    $data['address'] = request('address');
                    $link = explode('@', $data['address']);
                    if (is_array($link)) {
                        $lat_long = explode(',', end($link));
                        if (is_array($lat_long) && count($lat_long) == 3) {
                            $data['lat'] = isset($lat_long[0]) && is_numeric($lat_long[0]) ? $lat_long[0] : null;
                            $data['long'] = isset($lat_long[1]) && is_numeric($lat_long[1]) ? $lat_long[1] : null;
                        }
                    }
                }
            } elseif (isset($request->con_type) && $request->con_type == 'online') {
                // If online, ensure lat and long are cleared and address_type is link
                $data['address_type'] = 'link';
                $data['address'] = request('address');
                $data['lat'] = null;
                $data['long'] = null;
            } elseif ($request->address_type == 'map') {
                $data['address_type'] = 'map';
                $data['address'] = request('address_place');
                $data['lat'] = request('lat') ?: null;
                $data['long'] = request('long') ?: null;
            } elseif ($request->address_type == 'link') {
                $data['address_type'] = 'link';
                $data['address'] = request('address');
                $link = explode('@', $data['address']);
                if (is_array($link)) {
                    $lat_long = explode(',', end($link));
                    if (is_array($lat_long) && count($lat_long) == 3) {
                        $data['lat'] = isset($lat_long[0]) && is_numeric($lat_long[0]) ? $lat_long[0] : null;
                        $data['long'] = isset($lat_long[1]) && is_numeric($lat_long[1]) ? $lat_long[1] : null;
                    }
                }
            } else {
                $data['address_type'] = 'link';
                $data['address'] = request('address');
                $data['lat'] = request('lat') ?: null;
                $data['long'] = request('long') ?: null;
            }


            if (request('categories')) {
                $data['categories'] = collect($request->categories)->map(fn($i) => (int)$i);
            }

            $data['order_id'] = (int) ($data['order_id'] ?? 0);

            $res = Store::create($data);

            if ($request->hasFile('image')) {
                $res->clearMediaCollection('image');
                $res->addMediaFromRequest('image')->toMediaCollection('image');
            }

            DB::commit();

            if ($res) {
                return redirect(route('dashboard.stores.edit', $res->id))->with([
                    'message' => trans('dashboard.stores_added_successfully'),
                ]);
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
            return unKnownError($e->getMessage());
        }
    }


    public function edit(Store $store)
    {
        $inside_map = isset($place['distance']) && $place['distance'] > 0 ? Store::select('id', 'lat', 'long')->where('id', '!=', $store->id)->where('active', 1)->get()->map(function ($i) use ($store) {
            if (round(distance($store['lat'], $store['long'], $i['lat'], $i['long'], "M"), 1) <= $store['distance'] ?? 0) {
                return (string)$i['id'];
            }
        }) : [];

        $all_related_items = array_unique(array_merge(array_filter($inside_map ? $inside_map->toArray() : []) ?? [], $store->related_stores ?? []));

        return view('dashboard.stores.edit', [
            'title' => __('dashboard.edit_store'),
            'categories' => CategoryOfStore::all(),
            'places' => Place::all(),
            'stores' => Store::all(),
            'data' => $store,
            'all_related_items' => $all_related_items,
            'regions' => Region::all(),
            'cities' => City::where('region_id', $store->region_id)->get()
        ]);
    }


    public function related(Request $request, $id)
    {
        try {

            $request->validate([
                'related_stores' => 'nullable',
                'distance' => 'nullable'
            ]);

            $inputs = $request->only(['related_stores', 'distance']);
            $inputs['related_stores'] = isset($inputs['related_stores']) ? $inputs['related_stores'] : null;
            $data = Store::find($id);
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
                'near_places' => 'nullable',
            ]);

            $inputs = $request->only(['near_places']);
            $inputs['near_places'] = isset($inputs['near_places']) ? $inputs['near_places'] : null;
            $data = Store::find($id);
            $data->update($inputs);

            return redirect()->back()->with([
                'message' => trans('dashboard.updated_successfully'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }


    public function update(UpdateStoreRequest $request, $id)
    {
        try {
            // Get the existing store to check old values
            $existingStore = Store::findOrFail($id);
            $oldIsOnline = $existingStore->is_online ?? false;
            $oldConType = $oldIsOnline ? 'online' : 'local';

            $data = $request->only([
                'title', 'categories', 'featured', 'related_stores', 'near_places', 'address_type', 'address', 'lat', 'long', 'description', 'image',
                'active', /* 'facebook_link', */ 'x_link', 'whatsapp', 'Instagram_link',
                'website_link', 'visited', 'region_id', 'city_id', 'con_type', 'order_id',
            ]);

            // Handle con_type changes
            if (isset($request->con_type)) {
                $newConType = $request->con_type;
                
                // If changed from 'local' to 'online', clear lat and long
                if ($oldConType == 'local' && $newConType == 'online') {
                    $data['lat'] = null;
                    $data['long'] = null;
                    $data['address_type'] = 'link'; // Set to link for online stores
                }
                
                // If changed from 'online' to 'local', use the address_type from request
                if ($oldConType == 'online' && $newConType == 'local') {
                    // Use address_type from request (could be 'link' or 'map')
                    // is_online will be set to 0 below
                }
            }

            // If con_type is 'local', use address_type from request
            if (isset($request->con_type) && $request->con_type == 'local') {
                // Use address_type from request - could be 'link' or 'map'
                if (isset($request->address_type) && $request->address_type == 'map') {
                    // If explicitly set to 'map', use map logic
                    $data['address_type'] = 'map';
                    $data['address'] = request('address');
                    $data['lat'] = request('lat') ?: null;
                    $data['long'] = request('long') ?: null;
                } elseif (isset($request->address_type) && $request->address_type == 'link') {
                    // If explicitly set to 'link', use link logic
                    $data['address_type'] = 'link';
                    $data['address'] = request('address');
                    $link = explode('@', $data['address']);
                    if (is_array($link)) {
                        $lat_long = explode(',', end($link));
                        if (is_array($lat_long) && count($lat_long) == 3) {
                            $data['lat'] = isset($lat_long[0]) && is_numeric($lat_long[0]) ? $lat_long[0] : null;
                            $data['long'] = isset($lat_long[1]) && is_numeric($lat_long[1]) ? $lat_long[1] : null;
                        }
                    }
                } else {
                    // If not set, keep existing value or default to 'link'
                    if (!isset($data['address_type']) || $data['address_type'] == '') {
                        $data['address_type'] = 'link';
                    }
                    $data['address'] = request('address');
                }
            } elseif (isset($request->con_type) && $request->con_type == 'online') {
                // If online, ensure lat and long are cleared and address_type is link
                $data['address_type'] = 'link';
                $data['address'] = request('address');
                $data['lat'] = null;
                $data['long'] = null;
            } elseif ($request->address_type == 'map') {
                $data['address_type'] = 'map';
                $data['address'] = request('address');
                $data['lat'] = request('lat') ?: null;
                $data['long'] = request('long') ?: null;
            } elseif ($request->address_type == 'link') {
                $data['address_type'] = 'link';
                $data['address'] = request('address');
                $link = explode('@', $data['address']);
                if (is_array($link)) {
                    $lat_long = explode(',', end($link));
                    if (is_array($lat_long) && count($lat_long) == 3) {
                        $data['lat'] = isset($lat_long[0]) && is_numeric($lat_long[0]) ? $lat_long[0] : null;
                        $data['long'] = isset($lat_long[1]) && is_numeric($lat_long[1]) ? $lat_long[1] : null;
                    }
                }
            } else {
                $data['address_type'] = 'link';
                $data['address'] = request('address');
                $data['lat'] = request('lat') ?: null;
                $data['long'] = request('long') ?: null;
            }

            if (isset($request->active)) {
                $data['active'] = 1;
            } else {
                $data['active'] = 0;
            }

            // Handle con_type (online/local) and convert to is_online
            if (isset($request->con_type)) {
                $data['is_online'] = ($request->con_type == 'online') ? 1 : 0;
            }
            unset($data['con_type']); // Remove con_type from data as it's not a database column

            if (isset($request->featured)) {
                $data['featured'] = 1;
            } else {
                $data['featured'] = 0;
            }

            if (request('categories')) {
                $data['categories'] = collect($request->categories)->map(fn($i) => (int)$i);
            }

            $data['order_id'] = (int) ($data['order_id'] ?? 0);
//            dd($data);

            $res = Store::where('id', $id)->first();

            if ($res) {
                $res->update($data);

                if ($request->hasFile('image')) {
                    $res->clearMediaCollection('image');
                    $res->addMediaFromRequest('image')->toMediaCollection('image');
                }
//                $res->updateTranslation();
                DB::commit();
                return redirect(route('dashboard.stores.edit', $res->id))->with([
                    'message' => trans('dashboard.updated_successfully'),
                ]);
            } else {
                abort(404);
            }
        } catch (\Exception $e) {

            DB::rollBack();
            // throw $e;
            return unKnownError($e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            return Store::withTrashed()->find($id)->restore();
        } catch (\Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            if (request('archive')) {
                $store = Store::withTrashed()->find($id);
                $store->forceDelete();
            } else {
                $store = Store::findOrFail($id);
                $store->delete();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }

    public function destroy_selected(Request $request)
    {
        $arr_ids = $request->array_ids;
        if (request('archive')) {
            Store::withTrashed()->whereIn('id', $arr_ids)->forceDelete();
        } else {
            Store::whereIn('id', $arr_ids)->delete();
        }
        return response()->json([
            'status' => true,
            'message' => "stores deleted",
        ]);
    }

    public function export(Request $request)
    {
        $stores = Store::when($request->title, function ($q) use ($request) {
            return $q->whereTranslationLike('title', '%' . $request->title . '%');
        });

        if (request('category_id')) {
            $categories_id = explode(',' , request('category_id'));
            $stores->where(function($query)use($categories_id){
                foreach ($categories_id as $category_id) {
                    $query->orWhereRaw("JSON_CONTAINS(categories, '" . $category_id . "' )");
                }
            });
        }

        if (request('archive', 0)) {
            $stores = $stores->onlyTrashed();
        }

        $stores = $stores->latest()->get();

        return Excel::download(new StoresExport($stores), 'stores_' . date('Y-m-d_H-i-s') . '.xlsx');
    }
}
