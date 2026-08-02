<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Application;
use App\Models\CategoryOfApplication;
use App\Services\UploadService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Applications\StoreApplicationRequest;
use App\Http\Requests\Dashboard\Applications\UpdateApplicationRequest;


class ApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:read-applications', ['only' => ['index']]);
        $this->middleware('permission:create-applications', ['only' => ['create', 'store']]);
        $this->middleware('permission:update-applications', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-applications', ['only' => ['destroy']]);
    }

    public function index()
    {
        $application = applyListingOrder(Application::query());
        $application->when(request()->title, function ($item) {
            $item->whereTranslationLike('title', '%' . request()->title . '%');
        });

        $perPage = request('per_page') == -1 ? $application->count() : request('per_page' , 10);

        $applications = $application->paginate($perPage);

        $title = __('dashboard.applications');
        return view('dashboard.applications.index', compact('applications', 'title'));
    }

    public function create()
    {
        $title = __('dashboard.create_application');

        $categories = CategoryOfApplication::all();

        return view('dashboard.applications.create', compact( 'title', 'categories'));
    }

    public function store(StoreApplicationRequest $request)
    {
        $data = $request->validated();
        $data['order_id'] = (int) ($data['order_id'] ?? 0);

        if (request('categories')) {
            $data['categories'] = collect($request->categories)->map(fn($i) => (int)$i);
        }

        $data['active'] = 1;

        $application = Application::create($data);

        if (request()->hasFile('image')) {
            $application->clearMediaCollection('image');
            $application->addMediaFromRequest('image')->toMediaCollection('image');
        }

        if ($application) {
            return redirect(route('dashboard.applications.index'))->with([
                'message' => trans('dashboard.application_added_successfully'),
            ]);
        } else {
            abort(404);
        }
    }

    public function edit($id)
    {
        $application = Application::findOrFail($id);
        $categories = CategoryOfApplication::all();

        $title = __('dashboard.edit_application');

        return view('dashboard.applications.edit', compact('application', 'title', 'categories'));
    }

    public function update(UpdateApplicationRequest $request, $id)
    {
        $application = Application::findOrFail($id);

        $data = $request->validated();
        $data['order_id'] = (int) ($data['order_id'] ?? 0);

        $data['active'] = 1;

        if (request('categories')) {
            $data['categories'] = collect($request->categories)->map(fn($i) => (int)$i);
        }

        $application->update($data);

        if ($request->hasFile('image')) {
            $application->clearMediaCollection('image');
            $application->addMediaFromRequest('image')->toMediaCollection('image');
        }

        return redirect(route('dashboard.applications.index'))->with([
            'message' => trans('dashboard.application_updated_successfully'),
        ]);
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            if (request('archive')) {
                $application = Application::withTrashed()->find($id);
                if (file_exists(public_path('uploads/' . $application->image))) {
                    unlink(public_path('uploads/' . $application->image));
                }
                $application->forceDelete();
            } else {
                $application = Application::findOrFail($id);
                if (file_exists(public_path('uploads/' . $application->image))) {
                    unlink(public_path('uploads/' . $application->image));
                }
                $application->delete();
            }
            DB::commit();

            return response()->json([
                'message' => trans('dashboard.delete_successfully'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return unKnownError($e->getMessage());
        }
    }
}
