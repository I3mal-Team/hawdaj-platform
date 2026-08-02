<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Settings\SaveCategoryOfApplicationRequest;
use App\Http\Requests\Dashboard\Settings\UpdateCategoryOfApplicationRequest;
use App\Models\CategoryOfApplication;
use App\Models\CategoryOfApplication as Category;
use App\Services\UploadService;
use Exception;

class CategoryOfApplicationController extends Controller
{
    // set permission
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:read-application-categories', ['only' => ['index']]);
        $this->middleware('permission:create-application-categories', ['only' => ['create', 'store']]);
        $this->middleware('permission:update-application-categories', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-application-categories', ['only' => ['destroy']]);
    }

    public function index()
    {
        $perPage = request('per_page') == -1 ? Category::count() : request('per_page' , 10);

        $all = Category::paginate($perPage);

        return view('dashboard.settings.categoriesOfApplication.index', [
            'title' => trans('dashboard.categories'),
            'categories' => $all,
        ]);
    }

    public function create()
    {
        return view('dashboard.settings.categoriesOfApplication.create', [
            'title' => __('dashboard.create_category'),
            'categories' => CategoryOfApplication::allParents(),
        ]);
    }

    public function store(SaveCategoryOfApplicationRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('icon')) {
                $data['icon'] = UploadService::store($request->icon, 'users');
            }
            Category::create($data);

            return redirect(route('dashboard.application-categories.index'))->with([
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

    public function edit($category)
    {
        $category = Category::find($category);
        return view('dashboard.settings.categoriesOfApplication.edit', [
            'title' => __('dashboard.edit_category'),
            'category' => $category,
            'categories' => CategoryOfApplication::allParents(),

        ]);
    }

    public function update(UpdateCategoryOfApplicationRequest $request,  $category)
    {
        $category = Category::find($category);
        $data = $request->validated();

        if ($request->hasFile('icon')) {
            UploadService::delete($category->icon);
            $data['icon'] = UploadService::store($request->icon, 'categories');
        }
        $category->update($data);
//        $category->updateTranslation();

        return redirect(route('dashboard.application-categories.index'))->with([
            'message' => trans('dashboard.updated_successfully'),
        ]);
    }

    public function destroy($id)
    {
        Category::find($id)->delete();

        return response()->json([
            'message' => trans('dashboard.category_delete_successfully'),
        ]);
    }
}
