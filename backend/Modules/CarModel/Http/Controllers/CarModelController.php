<?php

namespace Modules\CarModel\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\CarModel\Entities\CarPlate;
use Modules\CarModel\Entities\CarSetting;
use Modules\CarModel\Services\CarService;

class CarModelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
//        $this->middleware('permission:update-car_plate', ['only' => ['takeAction']]);
    }

    public function index($site_id = null)
    {
        $title = __('dashboard.car_model');

        if (auth()->user()->hasRole('admin')) {
            $sites = Site::all();
        } else {
            $sites = \DB::table('user_sites')->where('user_id', auth()->id())->get();
        }

        if ($site_id) {
            $child = $sites->where('id', '=', $site_id)->first();
        } else {
            $child = $sites->first();
        }

        $site_id = $child->id;
        $search_key = request()->car_export;
        $service = new CarService($site_id);

        return view('CarModel::index', [
            'title' => $title,
            'sites' => $sites,
            'site_id' => $site_id,
            'car_status' => $service->getStatus(),
            'car_days' => $service->getCarDay($search_key),
            'getScreenShots' => $service->getScreenShot($search_key),
            'camera' => $service->getCamera($search_key),
            'car_setting' => $service->setting(),
        ]);
    }

    public function saveSetting(Request $request): RedirectResponse
    {
        $data = $request->except('_token');

        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date|date_format:Y-m-d|before:end_date',
            'end_date' => 'required|date|date_format:Y-m-d|after:start_date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['status' => 'error', 'message' => $validator->errors()->first()]);
        }

        CarSetting::updateOrCreate([
            'site_id' => $data['site_id']
        ], [
            'end_time' => $data['end_time'],
            'end_date' => $data['end_date'],
            'start_time' => $data['start_time'],
            'start_date' => $data['start_date'],
            'notification' => isset($data['notification']) ? true : false,
            'screenshot' => isset($data['screenshot']) ? true : false,
        ]);

        cache()->forget("car_setting_$request->site_id");

        return redirect()->back()->with([
            'message' => trans('dashboard.setting_added_successfully')
        ]);
    }

    public function get($site_id): JsonResponse
    {
        $get_cars = CarPlate::where('site_id', $site_id)->get();

        return response()->json($get_cars);
    }

    public function getCarTable($site_id)
    {
        $search_key = request('car_export');
        $notification = empty(request('redirect_id')) ? false : request('redirect_id');

        $service = new CarService($site_id);

        $table_data = $service->handleTable($search_key, $notification);

        $response = [
            "draw" => (int)request()->input('draw'),
            "recordsTotal" => (int)$table_data['total'],
            "recordsFiltered" => (int)$table_data['total'],
            "aaData" => $table_data['data']
        ];

        return json_encode($response);
    }

    public function takeAction(CarPlate $car)
    {
        try {
            $car->notice_time = carbon()->now();
            $car->detection_status = \request('detection_status');
            $car->save();

            $service = new CarService($car->site_id);

            if (request()->textNotes || request()->file) {
                $service->noteWithAction($car, request()->only('textNotes', 'file'));
            }

            $car_update = $service->updateDuration($car);

            if (!$car_update) {
                return response()->json(['status' => 'error', 'message' => 'Failed to change status']);
            }

            return response()->json($car_update);

        } catch (\Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    public function liveMode(Request $request)
    {
        if ($request->status) {
            return redirect(url("dashboard/cars/{$request->site_id}?car_export=today"));
        }

        return redirect(url("dashboard/cars/{$request->site_id}"));
    }

    public function exportFile(): JsonResponse
    {
        $type = request('type');

        $filter = [
            'time' => request('time'),
            'type' => $type
        ];

        $service = new CarService(request('last_child'));

        $service->handleExport($filter);

        return response()->json(['message' => trans('dashboard.file_will_prepare_soon')]);
    }

}
