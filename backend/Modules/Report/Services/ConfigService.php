<?php

namespace Modules\Report\Services;

use App\Models\Config;
use Exception;
use Modules\Report\Entities\ChartDetails;

class ConfigService
{
    /**
     * Get All config stored
     *
     * @return mixed
     * @throws Exception
     */
    public static function get()
    {
        return cache()->remember("config." . auth()->id(), 60 * 60, function () {
            return Config::where('user_id', auth()->id())
                ->get()
                ->groupBy('key')
                ->map(function ($type) {
                    return $type->groupBy('value')->map(function ($value) {
                        return $value->filter(function ($key) {
                            if ($key->active != false) {
                                return $key;
                            }
                        })->map(function ($item) {
                            return $item->view;
                        });
                    });

                })->toArray();
        });
    }

    /**
     * Resolve view for any chart
     *
     * @param $data
     * @param $index
     * @param $type
     * @return array
     * @throws Exception
     */
    public static function handleView($data = null, $index, $type): array
    {
        if (is_null($data)) {
            $data = self::get();
        }

        $result = [];
        if ($data[$index]) {
            foreach ($data[$index] as $key => $chart) {
                if (in_array($type, $chart, true)) {
                    $result[] = $key;
                }
            }
        }
        return $result;
    }

    /**
     * return chart details
     *
     * @return mixed
     * @throws Exception
     */
    public static function getChartDetails()
    {
        return cache()->remember("chart_details." . app()->getLocale() . '.' . auth()->id(), 60 * 60, function () {
            return ChartDetails::where('user_id', auth()->id())
                ->select('type', 'id', 'title', 'description', 'time_unit')
                ->get()
                ->groupBy('type')
                ->map(fn($item) => $item->first())
                ->toArray();
        });
    }
}
