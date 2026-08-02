<?php

namespace App\Http\Controllers\Dashboard\Contracts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Contracts\ContractRequest;
use App\Models\Company;
use App\Models\Contract;
use App\Models\ContractType;
use App\Models\Department;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class ContractController extends Controller
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
        $title = trans('dashboard.show_contracts');
        $contracts = Contract::with(['supervisor', 'contract_manager', 'company', 'department', 'contract_type'])
            ->latest()
            ->get();

        return view('dashboard.contracts.index', compact('title', 'contracts'));
    }

    /**
     * @param $company_id
     * @return JsonResponse
     */
    public function getSupervisors($company_id): JsonResponse
    {
        $supervisors = User::whereHas('roles', fn($q) => $q->where('name', 'supervisor'))
            ->where('company_id', $company_id)->select('id', 'first_name', 'last_name')
            ->get();

        return response()->json(['data' => $supervisors, 'message' => __('dashboard.success'), 'code' => 200]);
    }

    /**
     * @param $company_id
     * @return JsonResponse
     */
    public function getContracts($company_id): JsonResponse
    {
        $contracts = Contract::where('company_id', $company_id)
            ->select('id', 'name', 'from_date', 'to_date')
            ->get();

        return response()->json(['data' => $contracts, 'message' => __('dashboard.success'), 'code' => 200]);
    }

    /**
     * @param $contract_id
     * @return JsonResponse
     */
    public function getContractDates($contract_id): JsonResponse
    {
        $data = array();
        $contractDate = Contract::where('id', $contract_id)
            ->select('id', 'name', 'from_date', 'to_date')
            ->first();
        $data['id'] = $contractDate->id;
        $data['name'] = $contractDate->name;
        $data['from_date'] = Carbon::parse($contractDate->from_date)->format('m/d/Y h:i A');
        $data['to_date'] = Carbon::parse($contractDate->to_date)->format('m/d/Y h:i A');
        $data = (object)$data;

        return response()->json(['data' => $data, 'message' => __('dashboard.success'), 'code' => 200]);

    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('dashboard.contracts.create', [
            'departments' => Department::all(),
            'companies' => Company::all(),
            'supervisors' => User::whereHas(
                'roles', function ($q) {
                $q->where('name', 'supervisor');
            }
            )->get(),
            'contract_managers' => User::whereHas(
                'roles', function ($q) {
                $q->where('name', 'contract_manager');
            }
            )->get(),
            'contract_types' => ContractType::all(),
            'title' => __('dashboard.create_contract')
        ]);
    }

    /**
     * @param ContractRequest $request
     * @return Application|JsonResponse|Redirector|RedirectResponse
     */
    public function store(ContractRequest $request)
    {
        try {
            Contract::create( $request->except('_token'));

            return redirect(route('dashboard.contracts.index'))->with([
                'message' => trans('dashboard.contract_added_successfully')
            ]);

        } catch (Exception $e) {
            return unKnownError($e->getMessage());
        }
    }


    /**
     * @param Contract $contract
     * @return RedirectResponse|View|Application|Factory|View|JsonResponse
     */
    public function edit(Contract $contract)
    {
        try {
            return view('dashboard.contracts.edit', [
                'title' => __('dashboard.edit_contract'),
                'departments' => Department::all(),
                'companies' => Company::all(),
                'supervisors' => User::whereHas('roles', fn($q) => $q->where('name', 'supervisor'))->get(),
                'contract_managers' => User::whereHas('roles', fn($q) => $q->where('name', 'contract_manager'))->get(),
                'contract_types' => ContractType::all(),
                'contract' => $contract
            ]);

        } catch (Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    /**
     * @param ContractRequest $request
     * @param Contract $contract
     * @return Application|RedirectResponse|Redirector
     */
    public function update(ContractRequest $request, Contract $contract)
    {
        $contract->update( $request->except('_token','_method'));

        return redirect(route('dashboard.contracts.index'))->with([
            'message' => trans('dashboard.contract_updated_successfully')
        ]);
    }

    /**
     * @param Contract $contract
     * @return JsonResponse
     */
    public function destroy(Contract $contract): JsonResponse
    {
        $contract->delete();

        return response()->json([
            'message' => trans('dashboard.contract_delete_successfully')
        ]);
    }
}
