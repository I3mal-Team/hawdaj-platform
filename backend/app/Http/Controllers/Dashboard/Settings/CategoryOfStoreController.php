<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Settings\SaveCategoryOfStoreRequest;
use App\Http\Requests\Dashboard\Settings\UpdateCategoryOfStoreRequest;
use App\Models\CategoryOfStore as Category;
use App\Models\CategoryOfStore;
use App\Services\UploadService;
use Exception;
use Illuminate\Http\Request;

class CategoryOfStoreController extends Controller
{
    // set permission
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:read-storesCategory', ['only' => ['index']]);
        $this->middleware('permission:create-storesCategory', ['only' => ['create', 'store']]);
        $this->middleware('permission:update-storesCategory', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-storesCategory', ['only' => ['destroy']]);
    }

    public function index()
    {
        $perPage = request('per_page') == -1 ? CategoryOfStore::count() : request('per_page' , 10);

        $all = CategoryOfStore::paginate($perPage);

        return view('dashboard.settings.categoriesOfStore.index', [
            'title' => trans('dashboard.categories'),
            'categories' => $all,
        ]);
    }

    public function create()
    {
        return view('dashboard.settings.categoriesOfStore.create', [
            'title' => __('dashboard.create_category'),
            'categories' => CategoryOfStore::allParents(),
        ]);
    }

    public function store(SaveCategoryOfStoreRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('icon')) {
                $data['icon'] = UploadService::store($request->icon, 'users');
            }
            CategoryOfStore::create($data);

            return redirect(route('dashboard.store-categories.index'))->with([
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
        $category = CategoryOfStore::find($category);
        return view('dashboard.settings.categoriesOfStore.edit', [
            'title' => __('dashboard.edit_category'),
            'category' => $category,
            'categories' => CategoryOfStore::allParents(),

        ]);
    }

    public function update(UpdateCategoryOfStoreRequest $request,  $category)
    {
        $category = CategoryOfStore::find($category);
        $data = $request->validated();

        if ($request->hasFile('icon')) {
            UploadService::delete($category->icon);
            $data['icon'] = UploadService::store($request->icon, 'categories');
        }
        $category->update($data);
//        $category->updateTranslation();

        return redirect(route('dashboard.store-categories.index'))->with([
            'message' => trans('dashboard.updated_successfully'),
        ]);
    }

    public function destroy($id)
    {
        CategoryOfStore::find($id)->delete();

        return response()->json([
            'message' => trans('dashboard.category_delete_successfully'),
        ]);
    }
}
