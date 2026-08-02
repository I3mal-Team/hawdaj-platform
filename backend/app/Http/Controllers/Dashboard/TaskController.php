<?php


namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CarRequest;
use App\Models\ContractorRequest;
use App\Models\MaterialRequest;
use App\Models\VisitorRequest;
use App\Notifications\ContractRequestNotification;
use App\Notifications\VisitRequestNotifaction;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * @return Factory|View|JsonResponse|RedirectResponse
     */
    public function index(Request $request)
    {
        $active_type = 'visitors';
        $tasks = array();
        $wheres = array();
        if (isset($request->type)) {
            $active_type = $request->type;
        }
        try {
            if (isset($request->text)) {
                $wheres['text'] = $request->text;
            }
            if (isset($request->date_from)) {
                $wheres['date_from'] = $request->date_from;
            }
            if (isset($request->date_to)) {
                $wheres['date_to'] = $request->date_to;
            }
            if (isset($request->status)) {
                $wheres['status'] = $request->status;
            }
            $employee = auth()->user();
            $title = __('dashboard.my_tasks');

            if ($active_type == 'visitors') {
                $tasks = new VisitorRequest();
                $tasks = $tasks->whereHas('visitRequest', function ($q) use ($employee, $wheres) {
                    $q->where('site_id', session('site_id'));
                    if (isset($wheres['date_from']) && !isset($wheres['date_to'])) {
                        $q->whereBetween('from_date', [$wheres['date_from'], Carbon::now()->format('Y-m-d')]);
                    } else if (isset($wheres['date_from']) && isset($wheres['date_to'])) {
                        $q->whereBetween('from_date', [$wheres['date_from'], $wheres['date_to']]);
                    } else if (!isset($wheres['date_from']) && isset($wheres['date_to'])) {
                        $q->whereBetween('from_date', [Carbon::now()->format('Y-m-d'), $wheres['date_to']]);
                    }
                });
                $tasks = $tasks->whereHas('visitor', function ($visitor) use ($wheres) {
                    if (isset($wheres['text'])) {
                        $visitor->where('first_name', 'LIKE', "%{$wheres['text']}%");
                        $visitor->orWhere('last_name', 'LIKE', "%{$wheres['text']}%");
                    }
                });
                $tasks = $tasks->with(['visitRequest', 'visitRequest.host', 'health_check.question']);
                if (isset($wheres['status'])) {
                    if ($wheres['status'] == 'in_progress')
                        $wheres['status'] = null;
                    $tasks = $tasks->where('status', $wheres['status']);
                }
                $tasks = $tasks->latest();
                $tasks = $tasks->paginate(8);
            } else if ($active_type == 'materials') {
                $tasks = new MaterialRequest();
                $tasks = $tasks->where('status', '!=', 'canceled');
//                $tasks = $tasks->where('host_id', $employee->id);
//                $tasks = $tasks->where('site_id', session('site_id'));
                $tasks = $tasks->with(['host', 'requester']);
                $tasks = $tasks->latest();
                $tasks = $tasks->paginate(8);
            } else if ($active_type == 'cars') {
                $tasks = new CarRequest();
                $tasks = $tasks->where('status', '!=', 'canceled');
                $tasks = $tasks->where('host_id', $employee->id);
                $tasks = $tasks->where('site_id', session('site_id'));
                if (isset($wheres['status']))
                    $tasks = $tasks->where('status', $wheres['status']);
                if (isset($wheres['date_from']) && !isset($wheres['date_to'])) {
                    $tasks->whereBetween('delivery_date', [$wheres['date_from'], Carbon::now()->format('Y-m-d')]);
                } else if (isset($wheres['date_from']) && isset($wheres['date_to'])) {
                    $tasks->whereBetween('delivery_date', [$wheres['date_from'], $wheres['date_to']]);
                } else if (!isset($wheres['date_from']) && isset($wheres['date_to'])) {
                    $tasks->whereBetween('delivery_date', [Carbon::now()->format('Y-m-d'), $wheres['date_to']]);
                }
                $tasks = $tasks->whereHas('requester', function ($requester) use ($wheres) {
                    if (isset($wheres['text'])) {
                        $requester->where('first_name', 'LIKE', "%{$wheres['text']}%");
                        $requester->orWhere('last_name', 'LIKE', "%{$wheres['text']}%");
                    }
                });
                $tasks = $tasks->with(['host']);
                $tasks = $tasks->latest();
                $tasks = $tasks->paginate(8);
            } else {
                $tasks = new ContractorRequest();
                $tasks = $tasks->whereHas('contractRequest', function ($q) use ($employee, $wheres) {
                    $q->where('site_id', session('site_id'));
                    $q->where('contract_manager_id', $employee->id);
                    if (isset($wheres['date_from']) && !isset($wheres['date_to'])) {
                        $q->whereBetween('from_date', [$wheres['date_from'], Carbon::now()->format('Y-m-d')]);
                    } else if (isset($wheres['date_from']) && isset($wheres['date_to'])) {
                        $q->whereBetween('from_date', [$wheres['date_from'], $wheres['date_to']]);
                    } else if (!isset($wheres['date_from']) && isset($wheres['date_to'])) {
                        $q->whereBetween('from_date', [Carbon::now()->format('Y-m-d'), $wheres['date_to']]);
                    }
                });
                $tasks = $tasks->whereHas('contractor', function ($contractor) use ($wheres) {
                    if (isset($wheres['text'])) {
                        $contractor->where('first_name', 'LIKE', "%{$wheres['text']}%");
                        $contractor->orWhere('last_name', 'LIKE', "%{$wheres['text']}%");
                    }
                });
                if (isset($wheres['status'])) {
                    if ($wheres['status'] == 'in_progress')
                        $wheres['status'] = null;
                    $tasks = $tasks->where('status', $wheres['status']);
                }
                $tasks = $tasks->with(['contractor', 'contractRequest', 'contractRequest.contract_manager']);
                $tasks = $tasks->latest();
                $tasks = $tasks->paginate(8);
            }
            return view('dashboard.tasks.index', compact('title', 'tasks'));

        } catch (\Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    public function getTaskInfo($id)
    {
        $task = VisitorRequest::with('visitor', 'visitRequest.host')
            ->where('id', $id)
            ->first();

        return view('dashboard.tasks.taskModal', compact('task'));
    }

    public function getContractorTaskInfo($id)
    {
        $task = ContractorRequest::with('contractor', 'contractRequest.contract_manager')
            ->where('id', $id)
            ->first();

        return view('dashboard.tasks.contractorTaskModal', compact('task'));
    }

    public function getMaterialTaskInfo($id)
    {
        $materialRequest = MaterialRequest::with([
            'host', 'department', 'requester', 'requests', 'site', 'sender_site', 'sender_department', 'sender_host'
        ])->where('id', $id)->first();

        return view('dashboard.tasks.materialTaskModal', compact('materialRequest'));
    }

    public function getCarTaskInfo($id)
    {
        $carRequest = CarRequest::with('host', 'driver', 'car', 'site', 'department')
            ->where('id', $id)
            ->first();

        return view('dashboard.tasks.carTaskModal', compact('carRequest'));
    }

    public function taskAction(Request $request)
    {
        $visitorRequest = VisitorRequest::find($request->id);

        if ($request->health_status != null) {
            if ($request->health_status == 'Approve') {
                $visitorRequest->update(['health_status' => 1]);

                $visitor = $visitorRequest->visitor;

                $data = [
                    'host' => $visitorRequest->visitRequest->host,
                    'visitRequest' => $visitorRequest->visitRequest,
                    'invitation_id' => $visitorRequest->visitRequest->id,
                    'visitor_request_id' => $visitorRequest->id,
                    'type' => 'confirm_invitation',
                    'visitor_name' => $visitorRequest->visitor->full_name,
                ];

                $data['url'] = 'javascript:;';

                try {
                    $visitor->notify(new VisitRequestNotifaction([], $data));
                } catch (\Exception $e) {
                }

            } else {
                $visitorRequest->update(['health_status' => 0]);
            }
        }

        if ($request->status != null) {
            $visitorRequest->update([
                'status' => $request->status,
                'notes' => $request->notes
            ]);
        }

        return redirect(route('dashboard.tasks'))->with([
            'message' => trans('dashboard.task_action_successfully')
        ]);
    }

    public function contractorTaskAction(Request $request)
    {
        $contractorRequest = ContractorRequest::find($request->id);

        $contractor = $contractorRequest->contractor;

        $data = [
            'host' => $contractorRequest->contractRequest->contract_manager,
            'contractRequest' => $contractorRequest->contractRequest,
            'invitation_id' => $contractorRequest->contractRequest->id,
            'contractor_request_id' => $contractorRequest->id,
            'type' => 'confirm_invitation',
            'contractor_name' => $contractorRequest->contractor->full_name,
        ];

        $data['url'] = 'javascript:;';

        try {
            $contractor->notify(new ContractRequestNotification([], $data));
        } catch (\Exception $e) {
            return unKnownError($e->getMessage());
        }

        if ($request->status != null) {
            $contractorRequest->update([
                'status' => $request->status,
                'notes' => $request->notes
            ]);
        }

        return redirect(route('dashboard.tasks'))->with([
            'message' => trans('dashboard.task_action_successfully')
        ]);
    }

    public function materialTaskAction(Request $request)
    {
        $materialRequest = MaterialRequest::find($request->id);
        $materialRequest->update([
            'status' => $request->status,
            'notes' => $request->notes
        ]);
        return redirect(route('dashboard.tasks'))->with([
            'message' => trans('dashboard.task_action_successfully')
        ]);
    }

    public function carTaskAction(Request $request)
    {
        $carRequest = CarRequest::find($request->id);
        $carRequest->update([
            'status' => $request->status,
            'notes' => $request->notes
        ]);
        return redirect(route('dashboard.tasks'))->with([
            'message' => trans('dashboard.task_action_successfully')
        ]);
    }
}
