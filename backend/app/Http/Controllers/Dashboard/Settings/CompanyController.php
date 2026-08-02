<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Settings\CompanyRequest;
use App\Models\Company;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class CompanyController extends Controller
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
        $perPage = request('per_page') == -1 ? Company::count() : request('per_page' , 10);

        $all = Company::paginate($perPage);

        return view('dashboard.settings.companies.index', [
            'title' => trans('dashboard.companies'),
            'companies' => $all
        ]);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('dashboard.settings.companies.create', ['title' => __('dashboard.create_company')]);
    }

    /**
     * @param CompanyRequest $request
     * @return Application|JsonResponse|RedirectResponse|Redirector
     */
    public function store(CompanyRequest $request)
    {
        try {
            Company::create($request->except('_token'));

            return redirect(route('dashboard.companies.index'))->with([
                'message' => trans('dashboard.company_added_successfully')
            ]);

        } catch (Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    /**
     * @param Company $company
     * @return Application|Factory|View
     */
    public function edit(Company $company)
    {
        return view('dashboard.settings.companies.edit', [
            'title' => __('dashboard.edit_company'),
            'company' => $company
        ]);
    }

    /**
     * @param CompanyRequest $request
     * @param Company $company
     * @return Application|RedirectResponse|Redirector
     */
    public function update(CompanyRequest $request, Company $company)
    {
        $company->update([
            'name' => $request->name,
            'email' => $request->email,
            'position' => $request->position,
            'mobile' => $request->mobile,
            'type' => $request->type,
            'url' => $request->url,
            'description' => $request->description,
        ]);

        return redirect(route('dashboard.companies.index'))->with([
            'message' => trans('dashboard.company_updated_successfully')
        ]);
    }

    /**
     * @param Company $company
     * @return JsonResponse
     */
    public function destroy(Company $company): JsonResponse
    {
        $company->delete();

        return response()->json([
            'message' => trans('dashboard.company_delete_successfully')
        ]);
    }
}
