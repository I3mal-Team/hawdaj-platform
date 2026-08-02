<?php

namespace App\Http\Controllers\Dashboard\Visits;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Visits\VisitActivityRequest;
use App\Models\Company;
use App\Models\Department;
use App\Models\HealthCheck;
use App\Models\Reason;
use App\Models\Site;
use App\Models\User;
use App\Models\Visitor;
use App\Models\VisitorHealthCheck;
use App\Models\VisitorRequest;
use App\Models\VisitType;
use App\Notifications\VisitRequestNotifaction;
use App\Services\UploadService;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class VisitActivityController extends Controller
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
        $title = __('dashboard.visits_activities');
        $type = \request('type');

        $visitorsReq = VisitorRequest::with('visitRequest', 'visitRequest.host', 'visitor')
            ->whereHas('visitRequest', function ($q) {
                $q->where('site_id', session('site_id'));
            });

        if ($type == null || $type == 'expected-visit') {
            $visitorsReq->whereNull('status_action');

        } elseif ($type == 'checkin-visit') {
            $visitorsReq->where('status_action', 'checkin')->whereNotNull('checkin');

        } elseif ($type == 'checkout-visit') {
            $visitorsReq->where('status_action', 'checkout')->whereNotNull('checkout');
        }

        return view('dashboard.visits.activities.index', [
            'title' => $title,
            'visitorsReq' => $visitorsReq->latest()->get(),
        ]);
    }

    /**
     * @return View
     */
    public function newVisit(): View
    {
        $title = __('dashboard.new-visit');
        $data['departments'] = Department::select('id', 'name', 'notes')->get();
        $data['reasons'] = Reason::select('id', 'reason')->get();
        $data['requests-type'] = VisitType::select('id', 'name')->get();
        $data['users'] = User::select('id', 'first_name', 'last_name')->get();
        $data['sites'] = Site::select('id', 'name')->get();
        $data['questions'] = HealthCheck::all();

        return view('dashboard.visits.activities.new_visit', [
            'title' => $title,
            'data' => $data,
        ]);
    }

    /**
     * @param VisitActivityRequest $request
     * @return Application|JsonResponse|RedirectResponse|Redirector
     */
    public function storeVisit(VisitActivityRequest $request)
    {
        try {
            DB::beginTransaction();

            $current_site = ($request->current_site == '1' && session()->has('site_id'))
                ? session('site_id')
                : $request->site_id;

            $data = $request->all();
            $data['site_id'] = $current_site;
            $data['from_date'] = Carbon::now()->toDateString();
            $data['to_date'] = Carbon::now()->toDateString();
            $data['status'] = 'confirmed';
            $data['from_fromtime'] = Carbon::now()->toTimeString();

            $visit = auth()->user()->visitRequest()->create($data);

            foreach ($request->visitors_id as $visitor_id) {
                $visit->visitors()->attach($visitor_id, [
                    'status_action' => 'checkin',
                    'checkin' => Carbon::now()->toDateTimeString(),
                ]);
            }

            DB::commit();

            return redirect(url('dashboard/visits-activities?type=checkin-visit'))->with([
                'message' => trans('dashboard.visitors_checkin_successfully')
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return unKnownError($e->getMessage());
        }
    }

    public function storeVisitor(VisitorRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->only(['first_name', 'last_name', 'id_number', 'id_type', 'mobile', 'email', 'gender',
                'nationality', 'comment', 'vehicle_detail', 'vehicle_material', 'vehicle_remark']);


            if ($request->hasFile('personal_photo')) {
                $data['personal_photo'] = UploadService::store($request->personal_photo, 'visitors');
            }

            if ($request->hasFile('id_copy')) {
                $data['id_copy'] = UploadService::store($request->id_copy, 'visitors');
            }
            $company = Company::updateOrCreate([
                'name' => $request->company,
            ], [
                'position' => $request->position,
                'mobile' => $request->company_mobile,
                'email' => $request->company_email
            ]);
            $data['company_id'] = $company->id;

            $visitor = Visitor::create($data);

            $questions = HealthCheck::select('id', 'question')->get();

            foreach ($questions as $health) {
                VisitorHealthCheck::create([
                    'visitor_id' => $visitor->id,
                    'health_check_id' => $health->id,
                    'answer' => $request->{'health_check_id_' . $health->id} ?? 0,
                    'visitor_request_id' => null
                ]);
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => trans('dashboard.visitor_added_successfully'),
                    'id' => $visitor->id,
                    'name' => $visitor->first_name . ' ' . $visitor->last_name
                ]);
            }

            return $visitor;
        } catch (Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }

    public function visitorData($id)
    {
        $visitorReq = VisitorRequest::with('visitor', 'visitRequest.host')
            ->where('id', $id)
            ->first();

        return view('dashboard.visits.activities.extra.taskModal', compact('visitorReq'));
    }

    public function resendLink(Request $request)
    {
        try {

            $visitorsReq = VisitorRequest::whereDate('created_at', Carbon::today()->toDateString())->with('visitor', 'visitRequest')->get();

            if ($request->type == 'invitation') {

                foreach ($visitorsReq as $visitorReq) {

                    $data = [
                        'host' => $visitorReq->visitRequest->host,
                        'invitation_id' => $visitorReq->id,
                        'type' => 'new_invitation',
                        'visitor_request_id' => $visitorReq->id,
                        'url' => route('dashboard.visitor-get-request', [
                            'visit_id' => $visitorReq->id,
                            'visitor_id' => $visitorReq->visitor_id,
                            'secret_code' => $visitorReq->secret_code,
                        ]),
                        'visitor_name' => $visitorReq->visitor->full_name
                    ];

                    try {
                        $visitorReq->visitor->notify(new VisitRequestNotifaction([],$data));
                    } catch (Exception $e) {
//                    return unKnownError($e->getMessage());
                    }
                }

            } else {

                foreach ($visitorsReq as $visitorReq) {

                    $data = [
                        'host' => $visitorReq->visitRequest->host,
                        'visitRequest' => $visitorReq->visitRequest,
                        'invitation_id' => $visitorReq->id,
                        'visitor_request_id' => $visitorReq->id,
                        'type' => 'confirm_invitation',
                        'url' => 'javascript:;',
                        'visitor_name' => $visitorReq->visitor->full_name,
                    ];

                    try {
                        $visitorReq->visitor->notify(new VisitRequestNotifaction([],$data));
                    } catch (Exception $e) {
//                    return unKnownError($e->getMessage());
                    }

                }
            }

            return response()->json(['message' => __("dashboard.$request->type").' '. __('dashboard.Code_Re_send_Successfully')]);

        } catch (Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    public function sendMessage(Request $request)
    {
        try {

            $visitorsReq = VisitorRequest::whereDate('created_at', Carbon::today())->with('visitor')->get();

            foreach ($visitorsReq as $visitorReq) {

                $data['message'] = $request->message;

                try {
                    $visitorReq->visitor->notify(new VisitRequestNotifaction([],$data));
                } catch (Exception $e) {
//                    return unKnownError($e->getMessage());
                }

            }

            return response()->json(['message' => "Message Send to visitors Successfully"]);

        } catch (Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    public function takeAction($vistReqId, Request $request)
    {
        if (in_array($request->status, ['checkin', 'checkout', 'rejected'])) {

            $visitorReq = VisitorRequest::find($vistReqId);

            if ($visitorReq) {
                $visitorReq->update([
                    'status_action' => $request->status,
                    $request->status => Carbon::now()->toDateTimeString(),
                    "{$request->status}_note" => $request->some_note
                ]);

                $visitorReq->histroy()->create([
                    'date' => Carbon::now()->toDateString(),
                    'time' => Carbon::now()->toTimeString(),
                    'activity_type' => $request->status,
                    'comment' => $request->some_note
                ]);

                return response()->json(['message' => "Visitor $request->status Successfully", 'status' => 'success']);
            }
        }

        return response()->json(['message' => 'Status Error', 'status' => 'error']);
    }
}
