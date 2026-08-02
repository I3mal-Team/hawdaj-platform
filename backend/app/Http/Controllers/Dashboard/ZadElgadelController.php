<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Exports\ZadElgadelsExport;
use App\Http\Requests\Dashboard\SaveZadElgadelRequest;
use App\Http\Requests\Dashboard\UpdateZadElgadelRequest;
use App\Models\CategoryOfZad;
use App\Models\FoodCategoryOfZad;
use App\Models\City;
use App\Models\Place;
use App\Models\Region;
use App\Models\Store;
use App\Models\ZadElgadel;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ZadElgadelController extends Controller
{

    // set permission
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:read-zadElgadel', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-zadElgadel', ['only' => ['create', 'store']]);
        $this->middleware('permission:update-zadElgadel', ['only' => ['edit', 'update', 'updateStatus']]);
        $this->middleware('permission:delete-zadElgadel', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        visit(['ip' => \request()->ip(), 'page' => 'stores', 'visits' => 1]);

        $allCategories = CategoryOfZad::all();

        $all = ZadElgadel::with('user')
            ->when($request->title, function ($q) use ($request) {
                return $q->whereTranslationLike('title', '%' . $request->title . '%');
            })->when($request->categories, function ($qq) use ($request) {
                return $qq->where('categories', $request->categories);
            })->when($request->user_places, function ($q) {
                return $q->whereNotNull('user_id');
            });


        if (request('archive', 0)) {
            $all = $all->onlyTrashed();
        }

        $all = $all->latest();

        $perPage = request('per_page') == -1 ? $all->count() : request('per_page' , 10);

        $all = $all->paginate($perPage);

        return view('dashboard.zad_elgadels.index', [
            'title' => trans('dashboard.zad_elgadels'),
            'zad_elgadels' => $all,
            'categories' => $allCategories,
        ]);
    }

    public function show(int $id)
    {
        $zad_elgadel = ZadElgadel::find($id);

        if (!$zad_elgadel) {
            return view('dashboard.zad_elgadels.show', compact('zad_elgadel'))->with([
                'message' => trans('dashboard.zad_elgadel_not_found'),
                'type' => 'error'
            ]);
        }

        return view('dashboard.zad_elgadels.show', compact('zad_elgadel'));
    }

    public function activate(Request $request)
    {

        $active = $request->input('checked');
        $zad_elgadel_id = $request->input('zad_elgadelId');

        if ($zad_elgadel_id) {
            $zad_elgadel = ZadElgadel::find($zad_elgadel_id);

            if ($active == 1) {
                $zad_elgadel->update([
                    'active' => 1,
                ]);
            } else {
                $zad_elgadel->update([
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

        $zad_elgadel = ZadElgadel::findOrFail($id);

        $zad_elgadel->update([
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
        return view('dashboard.zad_elgadels.create', [
            'title' => __('dashboard.new_zad_elgadels'),
            'categories' => CategoryOfZad::all(),
            'food_categories' => FoodCategoryOfZad::all(),
        ]);
    }


    public function store(SaveZadElgadelRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->only([
                'title', 'address_type', 'address', 'description',
                'active', 'featured', /* 'facebook_link', */ 'x_link', 'whatsapp', 'Instagram_link',
                'website_link', 'visited', 'lat', 'long', 'city_id','image' , 'region_id','menu_file', 'order_id',
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

            if ($request->address_type == 'map') {
                $data['address_type'] = 'map';
                $data['lat'] = request('lat');
                $data['long'] = request('long');
                $mapLabel = trim((string) request('address_map'));
                $data['address'] = $mapLabel !== ''
                    ? $mapLabel
                    : (is_numeric($data['lat']) && is_numeric($data['long'])
                        ? $data['lat'] . ',' . $data['long']
                        : (request('address') ?? ''));
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
                $data['address'] = request('address');
                $data['lat'] = request('lat');
                $data['long'] = request('long');
            }

           if (request()->hasFile('menu_file')) {
                $data['menu_file'] = UploadService::store($request->menu_file, 'zad_elgadels');
            }

            $data['order_id'] = (int) ($data['order_id'] ?? 0);

            $res = ZadElgadel::create($data);

            if (request()->hasFile('image')) {
                $res->clearMediaCollection('image');
                $res->addMediaFromRequest('image')->toMediaCollection('image');
            }

            $res->categories()->attach($request->categories);
            $res->foodCategories()->attach($request->food_categories);

            DB::commit();

            if ($res) {
                return redirect(route('dashboard.zad_elgadels.edit', $res->id))->with([
                    'message' => trans('dashboard.zad_elgadels_added_successfully'),
                    'status' => 'success'
                ]);
            } else {
                abort(404);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors($e->errors())->with([
                'message' => trans('dashboard.validation_error'),
                'status' => 'error'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with([
                'message' => trans('dashboard.error_occurred') . ': ' . $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }


    public function edit(ZadElgadel $zad_elgadel)
    {

        $reg = isset($zad_elgadel->region->cities) ? $zad_elgadel->region->cities : [];

        return view('dashboard.zad_elgadels.edit', [
            'title' => __('dashboard.edit_zad_elgadels'),
            'categories' => CategoryOfZad::all(),
            'food_categories' => FoodCategoryOfZad::all(),
            'regions' => Region::all(),
            'data' => $zad_elgadel,
            'mycities' => $reg,
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
            $data = ZadElgadel::find($id);
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
            $data = ZadElgadel::find($id);
            $data->update($inputs);

            return redirect()->back()->with([
                'message' => trans('dashboard.updated_successfully'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }


    public function update(UpdateZadElgadelRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = $request->only([
                'title', 'featured', 'related_stores', 'near_places', 'address_type', 'address', 'lat', 'long', 'description', 'image',
                'active', /* 'facebook_link', */ 'x_link', 'whatsapp', 'Instagram_link',
                'website_link', 'visited', 'city_id', 'region_id', 'menu_file', 'order_id',
            ]);

            if ($request->address_type == 'map') {
                $data['address_type'] = 'map';
                $data['lat'] = request('lat');
                $data['long'] = request('long');
                $mapLabel = trim((string) request('address_map'));
                $data['address'] = $mapLabel !== ''
                    ? $mapLabel
                    : (is_numeric($data['lat']) && is_numeric($data['long'])
                        ? $data['lat'] . ',' . $data['long']
                        : (request('address') ?? ''));
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
                $data['address'] = request('address');
                $data['lat'] = request('lat');
                $data['long'] = request('long');
            }


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

            $data['order_id'] = (int) ($data['order_id'] ?? 0);

            $res = ZadElgadel::where('id', $id)->first();

            if ($res) {
                $res->update($data);

                if (request()->hasFile('image')) {
                    $res->clearMediaCollection('image');
                    $res->addMediaFromRequest('image')->toMediaCollection('image');
                }

                $res->categories()->sync($request->categories);
                $res->foodCategories()->sync($request->food_categories);

                DB::commit();
                return redirect(route('dashboard.zad_elgadels.edit', $res->id))->with([
                    'message' => trans('dashboard.updated_successfully'),
                    'status' => 'success',
                ]);
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with([
                'message' => trans('dashboard.error_occurred') . ': ' . $e->getMessage(),
                'status' => 'error',
            ]);
        }
    }

    public function restore($id)
    {
        try {
            return ZadElgadel::withTrashed()->find($id)->restore();
        } catch (\Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            if (request('archive')) {
                $zad_elgadel = ZadElgadel::withTrashed()->find($id);
                $zad_elgadel->categories()->detach();
                $zad_elgadel->foodCategories()->detach();
                $zad_elgadel->forceDelete();
            } else {
                $zad_elgadel = ZadElgadel::findOrFail($id);
                $zad_elgadel->categories()->detach();
                $zad_elgadel->foodCategories()->detach();
                $zad_elgadel->delete();
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
            ZadElgadel::withTrashed()->whereIn('id', $arr_ids)->forceDelete();
        } else {
            ZadElgadel::whereIn('id', $arr_ids)->delete();
        }
        return response()->json([
            'status' => true,
            'message' => "zad elgadels deleted",
        ]);
    }

    public function export(Request $request)
    {
        $zadElgadels = ZadElgadel::when($request->title, function ($q) use ($request) {
            return $q->whereTranslationLike('title', '%' . $request->title . '%');
        })->when($request->categories, function ($qq) use ($request) {
            return $qq->where('categories', $request->categories);
        });

        if (request('archive', 0)) {
            $zadElgadels = $zadElgadels->onlyTrashed();
        }

        $zadElgadels = $zadElgadels->latest()->get();

        return Excel::download(new ZadElgadelsExport($zadElgadels), 'zad_elgadels_' . date('Y-m-d_H-i-s') . '.xlsx');
    }
}
