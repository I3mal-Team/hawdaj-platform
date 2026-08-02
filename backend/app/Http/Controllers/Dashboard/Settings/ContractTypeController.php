<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Settings\ContractTypeRequest;
use App\Models\ContractType;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class ContractTypeController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $contractTypes = ContractType::all();
        $title = trans('dashboard.contract_types');
        return view('dashboard.settings.contractTypes.index', compact('title', 'contractTypes'));
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        $title = __('dashboard.create_contract_type');
        return view('dashboard.settings.contractTypes.create', compact('title'));
    }

    /**
     * @param ContractTypeRequest $request
     * @return Application|JsonResponse|RedirectResponse|Redirector
     */
    public function store(ContractTypeRequest $request)
    {
        try {
            ContractType::create($request->only(['name', 'notes']));

            return redirect(route('dashboard.contract-types.index'))->with([
                'message' => trans('dashboard.contract_type_added_successfully')
            ]);

        } catch (Exception $e) {
            return unKnownError($e->getMessage());
        }
    }


    /**
     * @param ContractType $contractType
     * @return Application|Factory|View
     */
    public function edit(ContractType $contractType)
    {
        return view('dashboard.settings.contractTypes.edit', [
            'title' => __('dashboard.edit_contract_type'),
            'contractType' => $contractType
        ]);
    }

    /**
     * @param ContractTypeRequest $request
     * @param ContractType $contractType
     * @return Application|RedirectResponse|Redirector
     */
    public function update(ContractTypeRequest $request, ContractType $contractType)
    {
        $contractType->update([
            'name' => $request->name,
            'notes' => $request->notes,
        ]);

        return redirect(route('dashboard.contract-types.index'))->with([
            'message' => trans('dashboard.contract_type_updated_successfully')
        ]);
    }

    /**
     * @param ContractType $contractType
     * @return JsonResponse
     */
    public function destroy(ContractType $contractType): JsonResponse
    {
        $contractType->delete();

        return response()->json([
            'message' => trans('dashboard.contract_type_delete_successfully')
        ]);
    }
}
