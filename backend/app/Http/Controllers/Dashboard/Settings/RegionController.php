<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Settings\SaveRegionRequest;
use App\Http\Requests\Dashboard\Settings\UpdateRegionRequest;
use App\Models\Region;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class RegionController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:read-region', ['only' => ['index']]);
        $this->middleware('permission:create-region', ['only' => ['create', 'store']]);
        $this->middleware('permission:update-region', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-region', ['only' => ['destroy']]);
    }

    public function index(): View
    {
        $perPage = request('per_page') == -1 ? Region::count() : request('per_page' , 10);

        $all = Region::paginate($perPage);

        return view('dashboard.settings.regions.index', [
            'title' => trans('dashboard.regions'),
            'regions' => $all
        ]);
    }

    public function create(): View
    {
        return view('dashboard.settings.regions.create', [
            'title' => __('dashboard.create_region'),
        ]);
    }

    public function store(SaveRegionRequest $request)
    {
        try {
            $data = $request->validated();

            Region::create($data);

            return redirect(route('dashboard.regions.index'))->with([
                'message' => trans('dashboard.region_added_successfully'),
            ]);

        } catch (Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    public function edit(Region $region)
    {
        return view('dashboard.settings.regions.edit', [
            'title' => __('dashboard.edit_region'),
            'region' => $region,

        ]);
    }

    public function update(UpdateRegionRequest $request, Region $region)
    {
        $region->update($request->validated());

        return redirect(route('dashboard.regions.index'))->with([
            'message' => trans('dashboard.updated_successfully'),
        ]);
    }

    public function destroy(Region $region): JsonResponse
    {
        $region->delete();

        return response()->json([
            'message' => trans('dashboard.region_delete_successfully'),
        ]);
    }
}
