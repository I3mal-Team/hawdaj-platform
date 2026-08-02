<?php

namespace App\Http\Controllers\Dashboard\Visits;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Visits\VisitPermissionRequest;
use App\Models\Department;
use App\Models\Reason;
use App\Models\Site;
use App\Models\User;
use App\Models\Visitor;
use App\Models\VisitRequest;
use App\Models\VisitType;
use App\Notifications\VisitRequestNotifaction;
use App\Services\UUIDService;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class VisitRequestController extends Controller
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
            $visits = VisitRequest::with('host', 'department', 'requester', 'visitors')->latest()->site()->get();

            return view('dashboard.visits.requests.index', [
                'title' => trans('dashboard.show_visits'),
                'visits' => $visits
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
            $data['departments'] = Department::select('id', 'name', 'notes')->get();
            $data['reasons'] = Reason::select('id', 'reason')->get();
            $data['requests-type'] = VisitType::select('id', 'name')->get();
            $data['users'] = User::select('id', 'first_name', 'last_name')->get();
            $data['sites'] = Site::select('id', 'name')->get();

            return view('dashboard.visits.requests.create', compact('title', 'data'));
        } catch (Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    /**
     * @param VisitPermissionRequest $request
     * @return JsonResponse|RedirectResponse|Redirector
     */
    public function store(VisitPermissionRequest $request)
    {
        try {
            DB::beginTransaction();

            $current_site = ($request->current_site == '1' && session()->has('site_id'))
                ? session('site_id')
                : $request->site_id;

            $visit = auth()->user()->visitRequest()->create([
                'site_id' => $current_site,
                'host_id' => $request->host_id,
                'department_id' => $request->department_id,
                'reason_id' => $request->reason_id,
                'visit_type_id' => $request->visit_type_id,
                'description' => $request->description,
                'requirments' => $request->requirments,
                'comment' => $request->comment,
                'from_date' => $request->from_date,
                'from_fromtime' => $request->from_fromtime,
                'from_totime' => $request->from_totime,
                'to_date' => $request->to_date,
                'to_fromtime' => $request->to_fromtime,
                'to_totime' => $request->to_totime,
            ]);

            $visitors = Visitor::find($request->visitors_id);
            $host = User::find($request->host_id);

            //Notify Visitors && Host
            $data = [
                'host' => $host,
                'visitor_request_id' => $visit->id,
                'invitation_id' => $visit->requester_id,
                'type' => 'new_invitation'
            ];

            foreach ($visitors as $visitor) {
                $code = new UUIDService($visitor->id + $visit->id + $host->id);
                $secret_code = $code->limit(10);

                $data['url'] = route('dashboard.visitor-get-request', [
                    'visit_id' => $visit->id,
                    'visitor_id' => $visitor->id,
                    'secret_code' => $secret_code,
                ]);

                $data['visitor_name'] = $visitor->full_name;

                $visit->visitors()->attach($visitor->id, [
                    'secret_code' => $secret_code,
                    'created_at' => Carbon::today(),
                    'updated_at' => Carbon::today(),
                ]);

                try {
                    $visitor->notify(new VisitRequestNotifaction($visitor, $data));
                } catch (Exception $e) {
                }
            }

            DB::commit();

            return redirect('dashboard/visits')->with([
                'message' => trans('dashboard.request_added_successfully')
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }

    /**
     * @param VisitRequest $visit
     * @param Request $request
     * @return JsonResponse|RedirectResponse|Redirector
     */
    public function status(VisitRequest $visit, Request $request)
    {
        try {
            if ($request->expectsJson()) {
                $message = __('dashboard.please_choice_valid_status');
                $type = 'error';

                if ($request->status == 'canceled') {
                    $visit->status = 'canceled';
                    $visit->save();

                    $message = __('dashboard.visits_invitation_canceled_successfully');
                    $type = 'success';
                }

                return response()->json(['message' => $message, 'type' => $type]);
            }

            return redirect('/');
        } catch (Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    /**
     * @param VisitRequest $visitRequest
     * @return JsonResponse
     */
    public function destroy(VisitRequest $visitRequest): JsonResponse
    {
        $visitRequest->delete();

        return response()->json([
            'message' => trans('dashboard.request_delete_successfully')
        ]);
    }
}
