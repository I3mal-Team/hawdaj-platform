<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Settings\DepartmentRequest;
use App\Models\Department;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class DepartmentsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','check_site','check_guard']);
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('dashboard.settings.departments.index', [
            'title' => trans('dashboard.departments'),
            'departments' => Department::all()
        ]);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('dashboard.settings.departments.create', ['title' => __('dashboard.create_department')]);
    }

    /**
     * @param DepartmentRequest $request
     * @return Application|JsonResponse|RedirectResponse|Redirector
     */
    public function store(DepartmentRequest $request)
    {
        try {
            $data = $request->only(['name', 'notes']);
            Department::create($data);

            return redirect(route('dashboard.departments.index'))->with([
                'message' => trans('dashboard.department_added_successfully')
            ]);

        } catch (Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    /**
     * @param Department $department
     * @return Application|Factory|View
     */
    public function edit(Department $department)
    {
        return view('dashboard.settings.departments.edit', [
            'title' => __('dashboard.edit_department'),
            'department' => $department
        ]);
    }

    /**
     * @param DepartmentRequest $request
     * @param Department $department
     * @return Application|RedirectResponse|Redirector
     */
    public function update(DepartmentRequest $request, Department $department)
    {
        $department->update([
            'name' => $request->name,
            'notes' => $request->notes,
        ]);

        return redirect(route('dashboard.departments.index'))->with([
            'message' => trans('dashboard.department_updated_successfully')
        ]);
    }

    /**
     * @param Department $department
     * @return JsonResponse
     */
    public function destroy(Department $department): JsonResponse
    {
        $department->delete();

        return response()->json([
            'message' => trans('dashboard.department_delete_successfully')
        ]);
    }
}
