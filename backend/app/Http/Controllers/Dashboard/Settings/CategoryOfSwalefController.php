<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Settings\SaveCategoryOfSwalefRequest;
use App\Http\Requests\Dashboard\Settings\UpdateCategoryOfSwalefRequest;
use App\Models\CategoryOfSwalef;
use App\Services\UploadService;
use Exception;

class CategoryOfSwalefController extends Controller
{
    // set permission
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:read-swalef-categories', ['only' => ['index']]);
        $this->middleware('permission:create-swalef-categories', ['only' => ['create', 'store']]);
        $this->middleware('permission:update-swalef-categories', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-swalef-categories', ['only' => ['destroy']]);
    }

    public function index()
    {
        $perPage = request('per_page') == -1 ? CategoryOfSwalef::count() : request('per_page' , 10);

        $all = CategoryOfSwalef::paginate($perPage);

        return view('dashboard.settings.categoriesOfSwalef.index', [
            'title' => trans('dashboard.categories'),
            'categories' => $all,
        ]);
    }

    public function create()
    {
        return view('dashboard.settings.categoriesOfSwalef.create', [
            'title' => __('dashboard.create_category'),
            'categories' => CategoryOfSwalef::allParents(),
        ]);
    }

    public function store(SaveCategoryOfSwalefRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('icon')) {
                $data['icon'] = UploadService::store($request->icon, 'categories');
            }

            CategoryOfSwalef::create($data);

            return redirect(route('dashboard.swalef-categories.index'))->with([
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
        $category = CategoryOfSwalef::find($category);
        return view('dashboard.settings.categoriesOfSwalef.edit', [
            'title' => __('dashboard.edit_category'),
            'category' => $category,
            'categories' => CategoryOfSwalef::allParents(),

        ]);
    }

    public function update(UpdateCategoryOfSwalefRequest $request,  $category)
    {
        $category = CategoryOfSwalef::find($category);
        $data = $request->validated();

        if ($request->hasFile('icon')) {
            UploadService::delete($category->icon);
            $data['icon'] = UploadService::store($request->icon, 'categories');
        }
        $category->update($data);
//        $category->updateTranslation();

        return redirect(route('dashboard.swalef-categories.index'))->with([
            'message' => trans('dashboard.updated_successfully'),
        ]);
    }

    public function destroy($id)
    {
        CategoryOfSwalef::find($id)->delete();

        return response()->json([
            'message' => trans('dashboard.category_delete_successfully'),
        ]);
    }
}
