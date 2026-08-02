<?php

namespace App\Http\Controllers\Dashboard\Materials;

use App\Http\Controllers\Controller;
use App\Models\MaterialRequest;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class MaterialActivityController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function materialRequesterTasks()
    {
        return view('dashboard.materials.activities.index', [
            'title' => __('dashboard.material-activities'),
            'requests' => MaterialRequest::all(),
            'completed_requests' => MaterialRequest::where('status', 'approved')->get()
        ]);
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function getRequesterMaterialInfo($id)
    {
        return view('dashboard.materials.activities.extra._modal', [
            'request' => MaterialRequest::where('id', $id)->first()
        ]);
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function materialAction(Request $request)
    {
        $materialRequest = MaterialRequest::find($request->id);
        if ($materialRequest) {
            $materialRequest->update([
                'status' => $request->status,
                'status_remarks' => $request->status_remarks
            ]);

            $materialRequest->histories()->create([
                'date' => Carbon::now()->toDateString(),
                'time' => Carbon::now()->toTimeString(),
                'activity_type' => $request->status,
                'comment' => $request->status_remarks
            ]);
        }

        return redirect('/')->with([
            'message' => trans('dashboard.material_request_update_success')
        ]);
    }
}
