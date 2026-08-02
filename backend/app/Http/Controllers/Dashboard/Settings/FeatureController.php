<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Settings\StoreFeatureRequest;
use App\Http\Requests\Dashboard\Settings\UpdateFeatureRequest;
use App\Models\Feature;
use Illuminate\Support\Facades\DB;

class FeatureController extends Controller
{
    // set permission
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:read-feature', ['only' => ['index']]);
        $this->middleware('permission:create-feature', ['only' => ['create', 'store']]);
        $this->middleware('permission:update-feature', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-feature', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perPage = request('per_page') == -1 ? Feature::count() : request('per_page' , 10);

        $all = Feature::paginate($perPage);

        return view('dashboard.settings.features.index', [
            'title' => trans('dashboard.features'),
            'features' => $all,
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.settings.features.create', ['title' => __('dashboard.create_feature')]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFeatureRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->only([
                "name", "description",
            ]);
            Feature::create($data);
            DB::commit();
            return redirect(route('dashboard.features.index'))->with([
                'message' => trans('dashboard.feature_added_successfully'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Feature $feature
     * @return \Illuminate\Http\Response
     */
    public function edit(Feature $feature)
    {
        return view('dashboard.settings.features.edit', [
            'title' => __('dashboard.edit_feature'),
            'feature' => $feature,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Feature $feature
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFeatureRequest $request, Feature $feature)
    {
        DB::beginTransaction();
        try {
            $feature->updateTranslation();
            DB::commit();
            return redirect(route('dashboard.features.index'))->with([
                'message' => trans('dashboard.feature_update_successfully'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Feature $feature
     * @return \Illuminate\Http\Response
     */
    public function destroy(Feature $feature)
    {
        DB::beginTransaction();
        try {
            $feature->delete();
            DB::commit();
            return response()->json([
                'message' => trans('dashboard.company_delete_successfully'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }
}
