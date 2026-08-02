<?php

namespace App\Http\Controllers\Dashboard\Cars;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Cars\CarPermissionRequest;
use App\Imports\CarRequestImport;
use App\Models\Car;
use App\Models\CarRequest;
use App\Models\Department;
use App\Models\Driver;
use App\Models\MaterialRequest;
use App\Models\Site;
use App\Models\User;
use App\Notifications\CarRequestNotification;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Maatwebsite\Excel\Facades\Excel;

class CarRequestController extends Controller
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
        $title = trans('dashboard.car_request_list');
        $cars = CarRequest::with(['host', 'department', 'requester', 'site'])
            ->latest()
            ->get();

        return view('dashboard.cars.index', compact('title', 'cars'));
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        $data = [];
        $title = __('dashboard.create_car_request');
        $data['departments'] = Department::select('id', 'name', 'notes')->get();
        $data['users'] = User::select('id', 'first_name', 'last_name')->get();
        $data['sites'] = Site::select('id', 'name')->get();

        return view('dashboard.cars.create', compact('title', 'data'));
    }

    /**
     * @param CarPermissionRequest $request
     * @return Application|JsonResponse|RedirectResponse|Redirector
     */
    public function store(CarPermissionRequest $request)
    {
        try {
            \DB::beginTransaction();

            $current_site = ($request->current_site == '1' && session()->has('site_id'))
                ? session('site_id')
                : $request->site_id;

            $driver = Driver::create([
                'contact_person_name' => $request->contact_person_name,
                'email' => $request->email,
                'id_number' => $request->id_number,
                'phone' => $request->phone,
                'vehicle_details' => $request->vehicle_details,
                'licence' => $request->licence,
                'remarks' => $request->remarks
            ]);

            $car = Car::create([
                'plate_ar' => $request->plate_ar,
                'plate_en' => $request->plate_en,
                'description' => $request->description,
                'licence' => $request->licence,
                'type' => $request->type,
                'status' => 'approved',
            ]);

            auth()->user()->carRequest()->create([
                'site_id' => $current_site,
                'host_id' => $request->host_id,
                'department_id' => $request->department_id,
                'requester_id' => $request->requester_id,
                'driver_id' => $driver->id,
                'car_id' => $car->id,
                'delivery_date' => $request->delivery_date,
                'delivery_from_time' => $request->delivery_from_time,
                'delivery_to_time' => $request->delivery_to_time,
                'remarks' => $request->remarks,
            ]);

            \DB::commit();

            return redirect('dashboard/car-requests')->with([
                'message' => trans('dashboard.request_added_successfully')
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
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

            if ($request->status == 'canceled') {
                $materialRequest->status = 'canceled';
                $materialRequest->save();

                $message = __('car_request_cancel_successfully');
                $type = 'success';
            }

            return response()->json(['message' => $message, 'type' => $type]);
        }
        return redirect('/');
    }

    /**
     * @param CarRequest $carRequest
     * @param Request $request
     * @return Application|JsonResponse|RedirectResponse|Redirector
     */
    public function approvedStatus(CarRequest $carRequest, Request $request)
    {
        try {
            if ($request->expectsJson()) {
                $message = __('dashboard.please_choice_valid_status');
                $type = 'error';
                if ($request->status == 'approved') {
                    $carRequest->status = 'approved';
                    $carRequest->save();

                    $host = User::find($carRequest->host_id);

                    $data = [
                        'host' => $host,
                        'carRequest' => $carRequest,
                        'invitation_id' => $carRequest->id,
                        'car_request_id' => $carRequest->id,
                        'type' => 'confirm_invitation',
                        'driver_id' => $carRequest->driver->id,
                        'driver_name' => $carRequest->driver->contact_person_name,
                    ];

                    try {
                        $carRequest->driver->notify(new CarRequestNotification($data));
                    } catch (\Exception $e) {
                    }

                    $message = __('dashboard.car_request_approve_successfully');
                    $type = 'success';
                }

                return response()->json(['message' => $message, 'type' => $type]);
            }

            return redirect('/');
        } catch (\Exception $e) {
            \DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function uploadExcel(Request $request): RedirectResponse
    {
        try {
            $file = $request->file('car_excel');

            Excel::import(new CarRequestImport(), $file);

            return redirect()->back()->with([
                'message' => trans('dashboard.car_requests_uploaded_success')
            ]);

        } catch (\Exception $e) {
            return unKnownError($e->getMessage());
        }
    }
}
