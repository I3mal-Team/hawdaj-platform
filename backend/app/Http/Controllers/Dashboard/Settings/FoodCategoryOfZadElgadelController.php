<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Settings\SaveFoodCategoryOfZadElgadelRequest;
use App\Http\Requests\Dashboard\Settings\UpdateFoodCategoryOfZadElgadelRequest;
use App\Models\CategoryOfStore as Category;
use App\Models\FoodCategoryOfZad;
use App\Services\UploadService;
use Exception;
use Illuminate\Http\Request;

class FoodCategoryOfZadElgadelController extends Controller
{

    // set permission
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:read-zadElgadelFood', ['only' => ['index']]);
        $this->middleware('permission:create-zadElgadelFood', ['only' => ['create', 'store']]);
        $this->middleware('permission:update-zadElgadelFood', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-zadElgadelFood', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perPage = request('per_page') == -1 ? FoodCategoryOfZad::count() : request('per_page' , 10);

        $all = FoodCategoryOfZad::paginate($perPage);

        return view('dashboard.settings.foodCategoriesOfZadElgadel.index', [
            'title' => trans('dashboard.food-categories'),
            'categories' => $all
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.settings.foodCategoriesOfZadElgadel.create', [
            'title' => __('dashboard.create_category'),
            'categories' => FoodCategoryOfZad::allParents(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveFoodCategoryOfZadElgadelRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('icon')) {
                $data['icon'] = UploadService::store($request->icon, 'food-categories');
            }
            FoodCategoryOfZad::create($data);

            return redirect(route('dashboard.zad_elgadel-food-categories.index'))->with([
                'message' => trans('dashboard.category_added_successfully'),
            ]);

        } catch (Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function edit( $id)
    {
        return view('dashboard.settings.foodCategoriesOfZadElgadel.edit', [
            'title' => __('dashboard.edit_category'),
            'category' => FoodCategoryOfZad::find($id),
            'categories' => FoodCategoryOfZad::allParents(),
        ]);
    }

    public function update(UpdateFoodCategoryOfZadElgadelRequest $request, $id)
    {
        $data = $request->validated();

        $category = FoodCategoryOfZad::find($id);

        if ($request->hasFile('icon')) {
            UploadService::delete($category->icon);
            $data['icon'] = UploadService::store($request->icon, 'food-categories');
        }

        $category->update($data);

        return redirect(route('dashboard.zad_elgadel-food-categories.index'))->with([
            'message' => trans('dashboard.updated_successfully'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        FoodCategoryOfZad::find($id)->delete();

        return response()->json([
            'message' => trans('dashboard.category_delete_successfully'),
        ]);
    }
}
