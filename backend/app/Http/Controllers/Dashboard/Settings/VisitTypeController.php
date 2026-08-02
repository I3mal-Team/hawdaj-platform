<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Settings\VisitTypeRequest;
use App\Models\VisitType;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class VisitTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('dashboard.settings.visit_types.index', [
            'title' => trans('dashboard.visit_types'),
            'visitTypes' => VisitType::all(),
        ]);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('dashboard.settings.visit_types.create', [
            'title' => __('dashboard.create_visit_type'),
        ]);
    }

    /**
     * @param VisitTypeRequest $request
     * @return JsonResponse|RedirectResponse|Redirector
     */
    public function store(VisitTypeRequest $request)
    {
        try {
            VisitType::create($request->only(['name', 'notes']));

            return redirect(route('dashboard.visit-types.index'))->with([
                'message' => trans('dashboard.visit_type_added_successfully'),
            ]);

        } catch (Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    /**
     * @param VisitType $visitType
     * @return Factory|View
     */
    public function edit(VisitType $visitType)
    {
        return view('dashboard.settings.visit_types.edit', [
            'title' => __('dashboard.edit_visit_type'),
            'visitType' => $visitType,
        ]);
    }

    /**
     * @param VisitTypeRequest $request
     * @param VisitType $visitType
     * @return RedirectResponse|Redirector
     */
    public function update(VisitTypeRequest $request, VisitType $visitType)
    {
        try {
            $visitType->update([
                'name' => $request->name,
                'notes' => $request->notes,
            ]);

            return redirect(route('dashboard.visit-types.index'))->with([
                'message' => trans('dashboard.visit_type_updated_successfully'),
            ]);

        } catch (Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    /**
     * @param VisitType $visitType
     * @return JsonResponse
     */
    public function destroy(VisitType $visitType): JsonResponse
    {
        $visitType->delete();

        return response()->json([
            'message' => trans('dashboard.visit_type_delete_successfully'),
        ]);
    }
}
