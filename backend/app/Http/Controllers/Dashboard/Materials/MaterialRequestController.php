<?php

namespace App\Http\Controllers\Dashboard\Materials;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Materials\MaterialPermissionRequest;
use App\Models\Department;
use App\Models\Material;
use App\Models\MaterialRequest;
use App\Models\Site;
use App\Models\Transporter;
use App\Models\User;
use App\Notifications\MatrialRequestNotifaction;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class MaterialRequestController extends Controller
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
        $title = trans('dashboard.show_materials');
        $requests = MaterialRequest::with([
            'host', 'department', 'requester', 'requests', 'site', 'sender_site', 'sender_department', 'sender_host'
        ])->latest()->get();

        return view('dashboard.materials.requests.index', compact('title', 'requests'));
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        $data = [];
        $title = __('dashboard.create_material_request');
        $data['departments'] = Department::select('id', 'name', 'notes')->get();
        $data['users'] = User::select('id', 'first_name', 'last_name')->get();
        $data['sites'] = Site::select('id', 'name')->get();

        return view('dashboard.materials.requests.create', compact('title', 'data'));
    }

    /**
     * @param MaterialPermissionRequest $request
     * @return Application|JsonResponse|RedirectResponse|Redirector
     */
    public function store(MaterialPermissionRequest $request)
    {
        try {
            DB::beginTransaction();

            $current_site = ($request->current_site == '1' && session()->has('site_id'))
                ? session('site_id')
                : $request->site_id;

            //Create transporter
            $transporter = Transporter::create([
                'company' => $request->contact_company,
                'contact_person' => $request->contact_person_name,
                'email' => $request->contact_person_email,
                'id_type' => $request->contact_id_type,
                'id_number' => $request->contact_id_number,
                'phone' => $request->contact_phone,
                'people_count' => $request->contact_people_count,
                'vehicle_details' => $request->contact_vehicle_details,
                'materials' => $request->contact_materials,
                'remarks' => $request->contact_remarks
            ]);

            $material_request = auth()->user()->materialRequest()->create(
                array_merge(['site_id' => $current_site, 'transporter_id' => $transporter->id],
                    $request->except('_token')
                ));

            $materials = Material::find($request->materials_id);

            $material_request->histories()->create([
                'date' => Carbon::now()->toDateString(),
                'time' => Carbon::now()->toTimeString(),
                'activity_type' => 'qr_code_send_successfully'
            ]);

            foreach ($materials as $material) {
                $material_request->requests()->attach($material_request->id, [
                    'material_id' => $material->id,
                ]);
            }

            DB::commit();

            return redirect('dashboard/material-requests')->with([
                'message' => trans('dashboard.request_added_successfully')
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }

    /**
     * @param MaterialRequest $materialRequest
     * @return JsonResponse
     */
    public function destroy(MaterialRequest $materialRequest): JsonResponse
    {
        $materialRequest->delete();
        return response()->json([
            'message' => trans('dashboard.request_delete_successfully')
        ]);
    }

    /**
     * @param MaterialRequest $materialRequest
     * @param Request $request
     * @return Application|JsonResponse|RedirectResponse|Redirector
     */
    public function status(MaterialRequest $materialRequest, Request $request)
    {
        if ($request->expectsJson()) {
            $message = __('dashboard.please_choice_valid_status');
            $type = 'error';

            if ($request->status === 'canceled') {
                $materialRequest->status = 'canceled';
                $materialRequest->save();

                $message = __('dashboard.materials_requests_canceled_success');
                $type = 'success';
            }

            return response()->json(['message' => $message, 'type' => $type]);
        }
        return redirect('/');
    }

    /**
     * @param MaterialRequest $materialRequest
     * @param Request $request
     * @return Application|JsonResponse|RedirectResponse|Redirector
     */
    public function approvedStatus(MaterialRequest $materialRequest, Request $request)
    {
        if ($request->expectsJson()) {
            $message = __('dashboard.please_choice_valid_status');
            $type = 'error';

            if ($request->status === 'approved') {
                $materialRequest->status = 'approved';
                $materialRequest->save();

                $host = User::find($materialRequest->host_id);

                $data = [
                    'host' => $host,
                    'materialRequest' => $materialRequest,
                    'invitation_id' => $materialRequest->id,
                    'material_request_id' => $materialRequest->id,
                    'type' => 'confirm_invitation',
                    'transporter_name' => $materialRequest->transporter->contact_person,
                ];

                try {
                    $materialRequest->transporter->notify(new MatrialRequestNotifaction($data));
                } catch (Exception $e) {
                }

                $message = __('dashboard.material_request_approved_success');
                $type = 'success';
            }

            return response()->json(['message' => $message, 'type' => $type]);
        }
        return redirect('/');
    }
}
