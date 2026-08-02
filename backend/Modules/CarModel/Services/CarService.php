<?php

namespace Modules\CarModel\Services;

use App\Events\RealTimeMessage;
use App\Models\User;
use App\Services\SettingService;
use App\Services\UploadService;
use Modules\CarModel\Entities\CarDay;
use Modules\CarModel\Entities\CarPlate;
use Modules\CarModel\Entities\CarSetting;
use Modules\CarModel\Events\CarEvent;
use Modules\Report\Entities\ArchiveFile;

class CarService
{
    public $site_id;

    /**
     * @param $site_id
     */
    public function __construct($site_id)
    {
        $this->site_id = $site_id;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function setting()
    {
        if (is_null(cache("car_setting_$this->site_id"))) {
            cache()->forget("car_setting_$this->site_id");
        }

        return cache()->remember("car_setting_$this->site_id", 60 * 60,
            fn() => CarSetting::where('site_id', $this->site_id)->first()
        );
    }

    /**
     * @param $search_key
     * @return mixed
     */
    public function getCamera($search_key = null)
    {
        $query = CarPlate::where('site_id', $this->site_id)->select('camID');

        $query = $this->addConditionDate($query, $search_key, 'created_at');

        return $query->distinct()->pluck('camID')->toArray();
    }

    /**
     * @param null $search_key
     * @return mixed
     * @throws \Exception
     */
    public function getCarDay($search_key = null)
    {
        $query = CarDay::where('site_id', $this->site_id);

        $query = $this->addConditionDate($query, $search_key, 'day');

        if ($cars_days = $query->first()) {
            $query->select(
                \DB::raw('SUM(invitation) as invitation'),
                \DB::raw('SUM(no_invitation) as no_invitation')
            );
        }

        if (empty($cars_days)) {
            $cars_days = CarDay::updateOrCreate([
                'site_id' => $this->site_id,
                'day' => date('Y-m-d')
            ]);
        }

        return $cars_days;
    }

    /**
     * @param $search_key
     * @return mixed
     */
    public function getScreenShot($search_key = null)
    {
        $query = CarPlate::where('site_id', $this->site_id)->select('id', 'camID', 'image', 'created_at');

        if (empty($search_key)) {
            $query->where('notice_time', null);
        }

        $query = $this->addConditionDate($query, $search_key, 'created_at');

        return $query->latest()->limit(10)->get()->groupBy('camID');
    }

    /**
     * @return bool
     */
    public function getStatus(): bool
    {
        return CarPlate::where('notice_time', null)
                ->where('site_id', $this->site_id)
                ->whereDate('created_at', date('Y-m-d'))->count() > 0;
    }

    /**
     * @param $filter
     * @return mixed
     */
    public function handleExport($filter)
    {
        $date = getStartEndDate($filter['time']);

        $start_name = $date['start'] ?? 'first';
        $last_name = $date['end'] ?? 'end';

        $name = "car_file_from_{$start_name}_to_{$last_name}.{$filter['type']}";

        return ArchiveFile::firstOrCreate([
            'start' => $start_name ?? null,
            'end' => $last_name ?? null,
            'site_id' => $this->site_id,
            'type' => $filter['type'],
            'model_type' => 'CarModel',
            'user_id' => auth()->id(),
        ], [
            'name' => $name,
            'status' => false,
        ]);
    }

    /**
     * @param $car
     * @return object
     * @throws \Exception
     */
    public function updateDuration($car): object
    {
        $start = resolveDateTime($car->created_at->toDateString(), $this->setting()->start_time);

        $no_risk_duration = carbon()->now()->diffInMinutes($start);

        $get_speed = CarDay::where('site_id', $this->site_id)
            ->where('day', date('Y-m-d'))
            ->first();

        if (empty($get_speed)) {
            $get_speed = CarDay::create([
                'site_id' => $this->site_id,
                'day' => date('Y-m-d')
            ]);
        }

        $notice = CarPlate::where('notice_time', null)
            ->where('site_id', $car->site_id)
            ->whereDate('created_at', date('Y-m-d'))
            ->count();

        $status = $notice > 0 ? 1 : 0;

        $first_risk_row = CarPlate::whereDate('created_at', date('Y-m- d'))
            ->where('site_id', $car->site_id)
            ->orderBy('id', 'DESC')
            ->where('first_row', 1)
            ->first();

        if (empty($first_risk_row)) {
            $first_risk_row = CarPlate::whereDate('created_at', date('Y-m-d'))
                ->where('site_id', $car->site_id)
                ->orderBy('id', 'ASC')
                ->first();

            if (empty($first_risk_row)) {
                return (object)['invitation' => 0, 'no_invitation' => 0, 'car_status' => 0];
            }

            $first_risk_row->first_row = true;
            $first_risk_row->save();
        }

        if ($status) {
            $car_date = $first_risk_row->created_at;
        } else {
            $car_date = $car->created_at;
        }

        if ($car->detection_status == 'error') {
            if ($car->status) {
                --$get_speed->invitation;
                ++$get_speed->no_invitation;
            } else {
                ++$get_speed->invitation;
                --$get_speed->no_invitation;
            }
            $get_speed->save();

            $car->status = !$car->status;
            $car->save();
        }

        $risk_duration = carbon()->now()->diffInMinutes($car_date);
        $total_risk = $risk_duration + $first_risk_row->last_risk ?? 0;

        if (!$status) {
            $car->last_risk = $total_risk;
            $car->first_row = true;
            $car->save();
        }

        $get_speed->risk_duration = $total_risk;
        $get_speed->no_risk_duration = max($no_risk_duration - $total_risk, 0);
        $get_speed->save();

        $get_speed->car_status = $status;

        return $get_speed;
    }

    /**
     * @param $car
     * @param $request
     * @return void
     */
    public function noteWithAction($car, $request): void
    {
        if (isset($request['file'])) {
            $request['file'] = UploadService::store($request['file'], 'file');
        }

        if ($car) {
            $car->notes()->create([
                'notes' => $request['textNotes'] ?? null,
                'file' => $request['file'] ?? null,
                'user_id' => auth()->id()
            ]);
        }
    }

    /**
     * @param $search_key
     * @param bool $notification
     * @return array
     */
    public function handleTable($search_key = null, bool $notification = false): array
    {
        $columns = config('CarModel')['columns'];
        $limit = request()->input('length') ?? 10;
        $start = request()->input('start') ?? 0;
        $order = $columns[request()->input('order.0.column')] ?? "id";
        $dir = request()->input('order.0.dir') ?? 'desc';
        $camera = request('camera') ?? 'all';
        $status = request('status') ?? 'all';
        $detection_status = request('detection_status') ?? 'all';
        $invitation_status = request('invitation_status') ?? 'all';

        if ($notification) {
            $getCars = CarPlate::where('id', $notification)->latest();
        } else {
            $getCars = CarPlate::where('site_id', $this->site_id)->latest();

            $getCars = $this->addConditionDate($getCars, $search_key, 'created_at');

            if ($camera != 'all') {
                $getCars->where('camID', $camera);
            }

            if ($status != 'all') {
                if ($status == 'noticed') {
                    $getCars->where('notice_time', '!=', null);
                } elseif ($status == 'not_noticed') {
                    $getCars->where('notice_time', null);
                }
            }

            if ($invitation_status != 'all') {
                if ($invitation_status == 'invitation') {
                    $getCars->where('status', true);
                } elseif ($invitation_status == 'no_invitation') {
                    $getCars->where('status', false);
                }
            }

            if ($detection_status != 'all') {
                $getCars->where('detection_status', $detection_status);
            }
        }

        $totalRecords = $getCars->count();

        $table = $getCars->offset($start)->orderBy($order, $dir)->skip($start)->limit($limit)->get();

        return ['total' => $totalRecords, 'data' => $table];
    }

    /**
     * @param $model
     * @param $data
     * @return void
     */
    public function notifyUsers($model, $data): void
    {
        $this->users()->each(function ($user) use ($model, $data) {
            SettingService::notifyUser($user->id, 'notification_models', $data);
        });

        event(new CarEvent($this->site_id, $model));
    }

    /**
     * @return mixed
     */
    public function users()
    {
        $selected = \DB::table('user_sites')
            ->where('site_id', $this->site_id)
            ->pluck('user_id')
            ->toArray();

        return User::whereIn('id', $selected)->get();
    }

    /**
     * @param $query
     * @param $search_key
     * @param $column
     * @return mixed
     */
    public function addConditionDate($query, $search_key, $column)
    {

        $date = getStartEndDate($search_key);

        if ($date['start']) {
            $query->whereDate($column, '>=', $date['start']);
        }

        if ($date['end']) {
            $query->whereDate($column, '<=', $date['end']);
        }

        return $query;
    }
}
