<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Settings\SaveCategoryOfZadElgadelRequest;
use App\Http\Requests\Dashboard\Settings\UpdateCategoryOfZadElgadelRequest;
use App\Models\CategoryOfZad;
use App\Services\UploadService;
use Exception;
use Illuminate\Http\Request;

class CategoryOfZadElgadelController extends Controller
{
    // set permission
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:read-zadElgadelCategory', ['only' => ['index']]);
        $this->middleware('permission:create-zadElgadelCategory', ['only' => ['create', 'store']]);
        $this->middleware('permission:update-zadElgadelCategory', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-zadElgadelCategory', ['only' => ['destroy']]);
    }

    public function index()
    {
        $perPage = request('per_page') == -1 ? CategoryOfZad::count() : request('per_page' , 10);

        $all = CategoryOfZad::paginate($perPage);

        return view('dashboard.settings.categoriesOfZadElgadel.index', [
            'title' => trans('dashboard.categories'),
            'categories' => $all,
        ]);
    }

    public function create()
    {
        return view('dashboard.settings.categoriesOfZadElgadel.create', [
            'title' => __('dashboard.create_category'),
            'categories' => CategoryOfZad::allParents(),
        ]);
    }

    public function store(SaveCategoryOfZadElgadelRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('icon')) {
                $data['icon'] = UploadService::store($request->icon, 'users');
            }
            CategoryOfZad::create($data);

            return redirect(route('dashboard.zad_elgadel-categories.index'))->with([
                'message' => trans('dashboard.category_added_successfully'),
            ]);

        } catch (Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    public function show($id)
    {
        //
    }

    public function edit( $id)
    {
        return view('dashboard.settings.categoriesOfZadElgadel.edit', [
            'title' => __('dashboard.edit_category'),
            'category' => CategoryOfZad::find($id),
            'categories' => CategoryOfZad::allParents(),

        ]);
    }

    public function update(UpdateCategoryOfZadElgadelRequest $request, $id)
    {
        $data = $request->validated();

        $category = CategoryOfZad::find($id);

        if ($request->hasFile('icon')) {
            UploadService::delete($category->icon);
            $data['icon'] = UploadService::store($request->icon, 'categories');
        }

        $category->update($data);
//        $category->updateTranslation();

        return redirect(route('dashboard.zad_elgadel-categories.index'))->with([
            'message' => trans('dashboard.updated_successfully'),
        ]);
    }

    public function destroy($id)
    {
        CategoryOfZad::find($id)->delete();

        return response()->json([
            'message' => trans('dashboard.category_delete_successfully'),
        ]);
    }
}
