<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Settings\VisitReasonRequest;
use App\Models\Reason;
use Exception;

class VisitReasonController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $reasons = Reason::all();
        $title = trans('dashboard.reasons');
        return view('dashboard.settings.visit_reasons.index', compact('title', 'reasons'));
    }


    public function create()
    {
        $title = __('dashboard.create_reason');
        return view('dashboard.settings.visit_reasons.create', compact('title'));
    }


    public function store(VisitReasonRequest $request)
    {
        try {
            $data = $request->only('reason');
            $reason = Reason::create($data);

            return redirect(route('dashboard.visit-reasons.index'))->with([
                'message' => trans('dashboard.reason_added_successfully')
            ]);

        } catch (Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    public function edit(Reason $reason)
    {
        return view('dashboard.settings.visit_reasons.edit', [
            'title' => __('dashboard.edit_reason'),
            'reason' => $reason
        ]);
    }

    public function update(VisitReasonRequest $request, Reason $reason)
    {
        $reason->update([
            'reason' => $request->reason,
        ]);
        return redirect(route('dashboard.visit-reasons.index'))->with([
            'message' => trans('dashboard.reason_updated_successfully')
        ]);
    }

    public function destroy(Reason $reason)
    {
        $reason->delete();
        return response()->json([
            'message' => trans('dashboard.reason_delete_successfully')
        ]);
    }
}
