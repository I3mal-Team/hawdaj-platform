<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Models\Gate;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class GateController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('dashboard.settings.gates.index', [
            'title' => trans('dashboard.gates'),
            'gates' => Gate::all()
        ]);
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('dashboard.settings.gates.create', ['title' => __('dashboard.create_gate')]);
    }

    /**
     * @param Request $request
     * @return Application|JsonResponse|RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        try {
            Gate::create($request->only('name'));

            return redirect(route('dashboard.gates.index'))->with([
                'message' => trans('dashboard.gate_added_successfully')
            ]);

        } catch (\Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    /**
     * @param Gate $gate
     * @return Application|Factory|View
     */
    public function edit(Gate $gate)
    {
        return view('dashboard.settings.gates.edit', [
            'title' => __('dashboard.edit_gate'),
            'gate' => $gate
        ]);
    }

    /**
     * @param Request $request
     * @param Gate $gate
     * @return Application|RedirectResponse|Redirector
     */
    public function update(Request $request, Gate $gate)
    {
        $gate->update([
            'name' => $request->name,
        ]);

        return redirect(route('dashboard.gates.index'))->with([
            'message' => trans('dashboard.gate_updated_successfully')
        ]);
    }

    /**
     * @param Gate $gate
     * @return JsonResponse
     */
    public function destroy(Gate $gate): JsonResponse
    {
        $gate->delete();
        return response()->json([
            'message' => trans('dashboard.gate_delete_successfully')
        ]);
    }
}
