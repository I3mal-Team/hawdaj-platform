<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CarRequest;
use App\Models\ContractorRequest;
use App\Models\ContractorRequestCheckin;
use App\Models\HealthCheck;
use App\Models\MaterialRequest;
use App\Models\VisitorRequest;
use App\Notifications\CarRequestNotification;
use App\Notifications\ContractRequestNotification;
use App\Notifications\MatrialRequestNotifaction;
use App\Notifications\VisitRequestNotifaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;

class GuardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check_site']);
    }

    /**
     * @return Application|Factory|View|void
     */
    public function index()
    {
        if (auth()->user()->hasRole('guard')) {
            $title = __('dashboard.guard');
            return view('dashboard.guard.index', compact('title'));
        }
        abort(403);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse|void
     */
    public function search(Request $request)
    {
        if (auth()->user()->hasRole('guard')) {

            $title = __('dashboard.visit_data');

            $questions = HealthCheck::all();

            $visitorData = VisitorRequest::with(['visitRequest', 'visitRequest.host', 'visitRequest.department',
                'health_check.question',
            ])->whereHas('visitor', function ($q) use ($request) {
                if ($request->searchType == 2) {
                    $q->where('id', '=', $request->value);
                }
                if ($request->searchType == 3) {
                    $q->where('mobile', '=', $request->value);
                }
            });

            if ($request->searchType == 1) {
                $visitorData = $visitorData->where('id', '=', $request->value);
            }

            $visitorData = $visitorData->first();

            if ($visitorData) {
                $visitorData->load('visitor');

                return view('dashboard.guard.details', compact('title', 'questions', 'visitorData'));
            }

            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'No Visitor Data Found',
            ]);
        }
        abort(403);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse|void
     */
    public function ContractSearch(Request $request)
    {
        if (auth()->user()->hasRole('guard')) {

            $title = __('dashboard.contract_visit_data');

            $contractorData = ContractorRequest::with(['contractRequest', 'contractRequest.contract', 'contractRequest.contract_manager'])->whereHas('contractor', function ($q) use ($request) {
                if ($request->searchType == 2) {
                    $q->where('id', '=', $request->value);
                }
                if ($request->searchType == 3) {
                    $q->where('mobile', '=', $request->value);
                }
            })->whereHas('contractRequest', function ($q) use ($request) {
                if ($request->searchType == 1) {
                    $q->where('id', '=', $request->value);
                }
            });

            $contractorData = $contractorData->first();

            if ($contractorData) {
                $contractorData->load('contractor', 'checkins', 'last_checkin');

                return view('dashboard.guard.contractor_details', compact('title', 'contractorData'));
            }

            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'No Visitor Data Found',
            ]);
        }
        abort(403);
    }

    /**
     * @param Request $request
     * @return Application|JsonResponse|RedirectResponse|Redirector|void
     */
    public function takeAction(Request $request)
    {
        try {
            if (auth()->user()->hasRole('guard')) {
                DB::beginTransaction();

                $visitorRequest = VisitorRequest::whereId($request->visitor_request_id)
                    ->with('visitRequest.host', 'visitRequest.reason', 'visitor')
                    ->first();

                $success = false;

                if ($request->status_action == 'checkin' && dateFormat($visitorRequest->checkin_note) == dateFormat(Carbon::now())) {
                    $success = true;

                    $visitorRequest->update([
                        'status_action' => $request->status_action,
                        'checkin' => Carbon::now()->toDateTimeString(),
                        'checkin_note' => $request->some_note,
                    ]);

                    $visitorRequest->histroy()->create([
                        'date' => Carbon::now()->toDateString(),
                        'time' => Carbon::now()->toTimeString(),
                        'activity_type' => $request->status_action,
                        'comment' => $request->some_note,
                    ]);

                    $host = $visitorRequest->visitRequest->host;

                    $data = [
                        'host' => $host,
                        'visitor' => $visitorRequest->visitor,
                        'company' => $visitorRequest->visitor->company->name,
                        'invitation_id' => $visitorRequest->visitRequest->id,
                        'visitor_request_id' => $request->visitor_request_id,
                        'date' => Carbon::now()->toDateString(),
                        'time' => Carbon::now()->toTimeString(),
                        'reason' => optional($visitorRequest->visitRequest->reason)->reason ?? '---',
                        'type' => 'notify_host',
                    ];

                    $data['url'] = route('dashboard.tasks');

                    try {
                        $host->notify(new VisitRequestNotifaction([], $data));
                    } catch (Exception $e) {

                    }

                } elseif ($request->status_action == 'checkout' && !is_null($visitorRequest->status_action)) {
                    $success = true;

                    $visitorRequest->update([
                        'status_action' => $request->status_action,
                        'checkout_note' => $request->some_note,
                    ]);

                } elseif ($request->status_action == 'rejected' && is_null($visitorRequest->status_action)) {
                    $success = true;
                    $visitorRequest->update([
                        'status_action' => $request->status_action,
                        'checkout' => Carbon::now()->toDateTimeString(),
                        'refuse_note' => $request->some_note,
                    ]);
                }

                DB::commit();

                return $success
                ? redirect(route('dashboard.guard.index'))->with([
                    'status' => 'success',
                    'message' => "Visitor #$visitorRequest->visitor_id has been $request->status_action successfully",
                ])
                : redirect(route('dashboard.guard.index'))->with([
                    'status' => 'error',
                    'message' => "Can't take action on this visitor",
                ]);
            }

            abort(403);

        } catch (Exception $e) {

            DB::rollBack();

            $visitorRequest->histroy()->create([
                'date' => Carbon::now()->toDateString(),
                'time' => Carbon::now()->toTimeString(),
                'activity_type' => 'issue',
                'comment' => '',
            ]);
            return unKnownError($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return Application|JsonResponse|RedirectResponse|Redirector|void
     */
    public function takeContractorAction(Request $request)
    {
        try {
            if (auth()->user()->hasRole('guard')) {
                DB::beginTransaction();

                $contractorRequest = ContractorRequest::whereId($request->contractor_request_id)
                    ->with('contractRequest.contract_manager', 'contractor')
                    ->first();

                $last_checkin = $contractorRequest->last_checkin;

                $success = false;

                if ($request->status_action == 'checkin') {
                    $success = true;

                    ContractorRequestCheckin::create([
                        'contractor_request_id' => $contractorRequest->id,
                        'status_action' => $request->status_action,
                        'checkin' => Carbon::now()->toDateTimeString(),
                        'checkin_note' => $request->some_note,
                    ]);

                    $contractorRequest->histroy()->create([
                        'date' => Carbon::now()->toDateString(),
                        'time' => Carbon::now()->toTimeString(),
                        'activity_type' => $request->status_action,
                        'comment' => $request->some_note,
                    ]);

                    $contractManager = $contractorRequest->contractRequest->contract_manager;

                    $data = [
                        'host' => $contractManager,
                        'contractor' => $contractorRequest->contractor,
                        'company' => $contractorRequest->contractor->company->name,
                        'invitation_id' => $contractorRequest->contractRequest->id,
                        'contractor_request_id' => $request->contractor_request_id,
                        'contractor_name' => $contractorRequest->contractor->full_name,
                        'date' => Carbon::now()->toDateString(),
                        'time' => Carbon::now()->toTimeString(),
                        'type' => 'notify_host',
                    ];

                    $data['url'] = route('dashboard.tasks');

                    try {
                        $contractManager->notify(new ContractRequestNotification([], $data));
                    } catch (Exception $e) {
                        return unKnownError($e->getMessage());
                    }

                } elseif ($last_checkin && $request->status_action == 'checkout' && !is_null($last_checkin->status_action)) {
                    $success = true;

                    $last_checkin->update([
                        'status_action' => $request->status_action,
                        'checkout' => Carbon::now()->toDateTimeString(),
                        'checkout_note' => $request->some_note,
                    ]);

                } elseif ($request->status_action == 'rejected') {
                    $success = true;
                    $contractorRequest->update([
                        'status_action' => $request->status_action,
                        'checkout' => Carbon::now()->toDateTimeString(),
                        'refuse_note' => $request->some_note,
                    ]);
                }

                DB::commit();

                return $success
                ? redirect(route('dashboard.guard.index'))->with([
                    'status' => 'success',
                    'message' => "Contractor #$contractorRequest->contractor_id has been $request->status_action successfully",
                ])
                : redirect(route('dashboard.guard.index'))->with([
                    'status' => 'error',
                    'message' => "Can't take action on this Contractor",
                ]);
            }

            abort(403);

        } catch (Exception $e) {

            DB::rollBack();

            $contractorRequest->histroy()->create([
                'date' => Carbon::now()->toDateString(),
                'time' => Carbon::now()->toTimeString(),
                'activity_type' => 'issue',
                'comment' => '',
            ]);
            return unKnownError($e->getMessage());
        }
    }

    /**
     * @return Application|Factory|View|void
     */
    public function materialIndex()
    {
        if (auth()->user()->hasRole('guard')) {
            $title = __('dashboard.guard');
            return view('dashboard.materialguard.index', compact('title'));
        }
        abort(403);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse|void
     */
    public function materialSearch(Request $request)
    {
        if (auth()->user()->hasRole('guard')) {

            $title = __('dashboard.material_request_data');

            $materialData = MaterialRequest::with(['host', 'site', 'department'])
                ->whereHas('transporter', function ($q) use ($request) {
                    if ($request->searchType == 2) {
                        $q->where('id', '=', $request->value);
                    }
                    if ($request->searchType == 3) {
                        $q->where('phone', '=', $request->value);
                    }
                });

            if ($request->searchType == 1) {
                $materialData = $materialData->where('id', '=', $request->value);
            }

            $materialData = $materialData->first();

            if ($materialData) {
                $materialData->load('transporter');
                return view('dashboard.guard.material_details', compact('title', 'materialData'));
            }

            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'No transporter Data Found',
            ]);
        }
        abort(403);
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector|void
     */
    public function materialAction(Request $request)
    {
        if (auth()->user()->hasRole('guard')) {

            $materialRequest = MaterialRequest::whereId($request->material_of_request_id)
                ->with('host', 'transporter')
                ->first();

            $success = false;

            if ($request->status_action == 'checkin' && is_null($materialRequest->status_action)) {
                $success = true;

                $materialRequest->update([
                    'status_action' => $request->status_action,
                    'checkin' => Carbon::now()->toDateTimeString(),
                    'checkin_note' => $request->some_note,
                ]);

                $host = $materialRequest->host;

                $data = [
                    'host' => $host,
                    'materialRequest' => $materialRequest,
                    'invitation_id' => $materialRequest->id,
                    'material_request_id' => $materialRequest->id,
                    'type' => 'notify_host',
                    'transporter_name' => $materialRequest->transporter->contact_person,
                    'transporter_company' => $materialRequest->transporter->company,
                ];

                $data['url'] = route('dashboard.tasks');

                try {
                    $host->notify(new MatrialRequestNotifaction($data));
                } catch (Exception $e) {
                }

            } elseif ($request->status_action == 'checkout' && !is_null($materialRequest->status_action)) {
                $success = true;

                $materialRequest->update([
                    'status_action' => $request->status_action,
                    'checkout_note' => $request->some_note,
                ]);

            } elseif ($request->status_action == 'rejected' && is_null($materialRequest->status_action)) {
                $success = true;

                $materialRequest->update([
                    'status_action' => $request->status_action,
                    'checkout' => Carbon::now()->toDateTimeString(),
                    'refuse_note' => $request->some_note,
                ]);
            }

            return $success
            ? redirect(route('dashboard.guard.index'))->with([
                'status' => 'success',
                'message' => "Transporter #$materialRequest->transporter_id has been $request->status_action successfully",
            ])
            : redirect(route('dashboard.guard.index'))->with([
                'status' => 'error',
                'message' => "Can't take action on this transporter",
            ]);
        }
        abort(403);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse|void
     */
    public function carSearch(Request $request)
    {
        if (auth()->user()->hasRole('guard')) {

            $title = __('dashboard.car_request_data');

            $carData = CarRequest::with(['host', 'site', 'department'])
                ->whereHas('driver', function ($q) use ($request) {
                    if ($request->searchType == 2) {
                        $q->where('id', '=', $request->value);
                    }
                });

            if ($request->searchType == 1) {
                $carData = $carData->where('id', '=', $request->value);
            }

            $carData = $carData->first();

            if ($carData) {
                $carData->load('driver', 'car');
                return view('dashboard.guard.car_details', compact('title', 'carData'));
            }

            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'No driver Data Found',
            ]);
        }
        abort(403);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function carAction(Request $request): RedirectResponse
    {
        $carRequest = CarRequest::whereId($request->car_request_id)
            ->with('host', 'driver', 'car')
            ->first();

        $success = false;

        if ($request->status_action == 'checkin' && is_null($carRequest->status_action)) {
            $success = true;

            $carRequest->update([
                'status_action' => $request->status_action,
                'checkin' => Carbon::now()->toDateTimeString(),
                'checkin_note' => $request->some_note,
            ]);

            $host = $carRequest->host;

            $data = [
                'host' => $host,
                'carRequest' => $carRequest,
                'invitation_id' => $carRequest->id,
                'car_request_id' => $carRequest->id,
                'type' => 'notify_host',
                'driver_name' => $carRequest->driver->contact_person_name,
                'driver_id' => $carRequest->driver->id,
            ];

            $data['url'] = route('dashboard.tasks');

            try {
                $host->notify(new CarRequestNotification($data));
            } catch (Exception $e) {
            }

        } elseif ($request->status_action == 'checkout' && !is_null($carRequest->status_action)) {
            $success = true;

            $carRequest->update([
                'status_action' => $request->status_action,
                'checkout' => Carbon::now()->toDateTimeString(),
                'checkout_note' => $request->some_note,
            ]);

        } elseif ($request->status_action == 'rejected' && is_null($carRequest->status_action)) {

            $success = true;

            $carRequest->update([
                'status_action' => $request->status_action,
                'checkout' => Carbon::now()->toDateTimeString(),
                'refuse_note' => $request->some_note,
            ]);
        }

        return $success
        ? redirect()->back()->with([
            'status' => 'success',
            'message' => "Driver #$carRequest->driver_id has been $request->status_action successfully",
        ])
        : redirect()->back()->with([
            'status' => 'error',
            'message' => "Can't take action on this driver",
        ]);
    }
}