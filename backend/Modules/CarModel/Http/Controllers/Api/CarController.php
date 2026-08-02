<?php

namespace Modules\CarModel\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiModalController;
use App\Services\PlateServices;
use App\Services\UploadService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Modules\CarModel\Entities\CarDay;
use Modules\CarModel\Entities\CarPlate;
use Modules\CarModel\Entities\CarSetting;
use Modules\CarModel\Http\Requests\CarRequest;
use Modules\CarModel\Http\Requests\CarSettingRequest;
use Modules\CarModel\Services\CarService;
use Modules\CarModel\Transformers\CarSettingsResource;

class CarController extends ApiModalController
{
    public CarService $carService;

    public function __construct()
    {
        $this->carService = new CarService(request('site_id'));
    }

    /**
     * @param CarSettingRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getSettings(CarSettingRequest $request): JsonResponse
    {
        if (empty($this->carService->setting())) {
            return $this->error(404, 'No Setting in this site');
        }

        $result = new CarSettingsResource($this->carService->setting());

        $message = trans('dashboard.data_retrieved_successfully');

        return $this->success($result, 200, $message);
    }

    /**
     * @param CarRequest $request
     * @return JsonResponse|RedirectResponse
     */
    public function save(CarRequest $request)
    {
        try {
            if (empty($this->carService->setting())) {
                CarSetting::create([
                    'start_date' => '2022-01-01',
                    "end_date" => "2025-01-01",
                    'start_time' => "09:00",
                    "end_time" => "23:59",
                    'site_id' => $request->site_id
                ]);
            }

            $data = $request->all();
            $plateService = new PlateServices();
            $plate = $plateService->resolvePlate($data['plate_card']);

            if ($plate == false) {
                return $this->error(400, 'car plate row not inserted due to unmoral shape');
            }

            $data['plate_ar'] = $plate['plate_ar']['plate'];
            $data['plate_en'] = $plate['plate_en']['plate'];
            $data['car_image'] = UploadService::store($request->car_image, "models/cars/$request->site_id");
            $data['plate_image'] = UploadService::store($request->plate_image, "models/cars/$request->site_id");

            $carRequest = \App\Models\CarRequest::whereHas('car', function ($q) use ($data) {
                return $q->where('plate_en', $data['plate_en'])->orWhere('plate_ar', $data['plate_ar']);
            })->where('site_id', $request->site_id)->where('delivery_date', now()->toDateString())->first();

            $status = true;
            if (empty($carRequest)) {
                $status = false;
            }

            $data['status'] = $status;
            $car = CarPlate::create($data);
            $car_day = CarDay::where('site_id', $request->site_id)->where('day', date('Y-m-d'))->latest()->first();
            $column = $status ? 'invitation' : 'no_invitation';

            if ($car_day) {
                $car_day->increment($column);
            } else {
                CarDay::create([
                    'site_id' => $request->site_id,
                    'invitation' => $request->status ? 1 : 0,
                    'no_invitation' => $request->status ? 0 : 1,
                    'day' => date('Y-m-d'),
                ]);
            }

            try {
                $body['title'] = __('dashboard.cars_detection');
                $body['message'] = __('dashboard.New Car waiting with plate').$car->plate_en;
                $body['url'] = "/dashboard/cars/$request->site_id?redirect_id=$car->id";
                $this->carService->notifyUsers($car, $body);

            } catch (Exception $e) {
                return $this->ok(trans('dashboard.added_successfully'));
            }

            return $this->ok(trans('dashboard.added_successfully'));

        } catch (Exception $ex) {
            return unKnownError($ex->getMessage());
        }
    }
}
