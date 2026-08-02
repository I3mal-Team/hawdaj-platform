<?php

namespace App\Http\Controllers\Dashboard\Contracts;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Contract;
use App\Models\ContractRequest;
use App\Models\Site;
use App\Models\Visitor;
use App\Notifications\ContractRequestNotification;
use App\Services\UUIDService;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class ContractRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * @return View|JsonResponse|RedirectResponse
     */

    public function index()
    {
        try {
            $contractRequests = ContractRequest::with('site', 'company', 'contract', 'contractorRequests')->latest()->get();

            return view('dashboard.contracts.requests.index', [
                'title' => trans('dashboard.show_contract_requests'),
                'contractRequests' => $contractRequests
            ]);

        } catch (Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    /**
     * @return View|JsonResponse|RedirectResponse
     */
    public function create(): View
    {
        try {
            $title = __('dashboard.create_visit');
            $data['contracts'] = Contract::select('id', 'name', 'supervisor_id', 'from_date', 'to_date')->with('supervisor')->get();
            $data['sites'] = Site::select('id', 'name')->get();
            $data['companies'] = Company::select('id', 'name')->get();

            return view('dashboard.contracts.requests.create', compact('title', 'data'));
        } catch (Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return Application|JsonResponse|RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $current_site = ($request->current_site == '1' && session()->has('site_id'))
                ? session('site_id')
                : $request->site_id;

            $fromDate = $request->from_date;
            $toDate = $request->to_date;

            $fromDate = str_replace('/', '-', $fromDate);
            $newFromDate = date("Y-m-d G:i", strtotime($fromDate));

            $toDate = str_replace('/', '-', $toDate);
            $newToDate = date("Y-m-d G:i", strtotime($toDate));

            $request->request->add(['from_date' => $newFromDate]);
            $request->request->add(['to_date' => $newToDate]);

            $visit = auth()->user()->contractRequest()->create([
                'site_id' => $current_site,
                'company_id' => $request->company_id,
                'contract_id' => $request->contract_id,
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'notes' => $request->notes,
            ]);

            $visitors = Visitor::find($request->visitors_id);
            $host = auth()->user();

            //Notify Visitors && Host
            $data = [
                'host' => $host,
                'contract_request_id' => $visit->id,
                'invitation_id' => $visit->id,
                'type' => 'new_invitation'
            ];

            foreach ($visitors as $visitor) {
                $code = new UUIDService($visitor->id + $visit->id + $host->id);
                $secret_code = $code->limit(10);

                $data['url'] = route('dashboard.contractor-get-request', [
                    'contract_request_id' => $visit->id,
                    'contractor_id' => $visitor->id,
                    'secret_code' => $secret_code,
                ]);

                $data['contractor_name'] = $visitor->full_name;

                $visit->contractors()->attach($visitor->id, [
                    'secret_code' => $secret_code,
                    'created_at' => Carbon::today(),
                    'updated_at' => Carbon::today(),
                ]);

                try {
                    $visitor->notify(new ContractRequestNotification($visitor, $data));
                } catch (Exception $e) {
                    return unKnownError($e->getMessage());
                }
            }

            DB::commit();

            return redirect('dashboard/contract-requests')->with([
                'message' => trans('dashboard.request_added_successfully')
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }
}
