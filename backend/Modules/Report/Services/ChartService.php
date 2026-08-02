<?php

namespace Modules\Report\Services;

class ChartService
{
    public static $chart_details;

    public function __construct()
    {
        self::$chart_details = ConfigService::getChartDetails();
    }

    /**
     * prepare data to coincide Bar Chart
     *
     * @param $data
     * @param $filter
     * @return array
     */
    public static function prepareBar($data, $filter): array
    {
        $result['sites'] = array_keys($data);
        $unit = self::$chart_details['bar']['time_unit'];

        foreach ($filter['columns']['data'] as $column) {
            $value = array_values(\Arr::pluck($data, $column));
            $value = self::expectTime($filter, $column) ? handleTimeUnit($value, $unit) : $value;

            $result['result'][] = [
                'name' => self::display($column),
                'type' => 'column',
                'data' => $value
            ];
        }

        return $result;
    }

    /**
     * Prepare data to coincide Line chart
     *
     * @param $data
     * @param $filter
     * @return array
     */
    public static function prepareLine($data, $filter): array
    {
        $unit = self::$chart_details['bar']['time_unit'];

        foreach ($filter['columns']['data'] as $key => $column) {
            $result[$key]['data'] = array_values(collect($data)
                ->transform(function ($item, $key) use ($column, $unit, $filter) {
                    $value = self::expectTime($filter, $column)
                        ? handleTimeUnit($item[$column], $unit)
                        : $item[$column];

                    return ['x' => $key, 'y' => $value];
                })->toArray());

            $result[$key]['name'] = self::display($column);
        }

        return $result ?? [];
    }

    /**
     * prepare data to coincide Pie Chart
     *
     * @param $data
     * @param $filter
     * @return array
     */
    public static function preparePie($data, $filter): array
    {
        $unit = self::$chart_details['pie']['time_unit'];

        foreach ($filter['columns']['data'] as $column) {
            $value = array_values(array_map(fn($item) => (int)$item, \Arr::pluck($data, $column)));
            $value = self::expectTime($filter, $column) ? handleTimeUnit($value, $unit) : $value;

            $result[$column] = [
                'name' => array_filter(\Arr::pluck($data, $filter['groupBy'])),
                'value' => $value ?? 0,
            ];
        }

        return $result ?? [];
    }

    /**
     * prepare data to coincide Table Chart
     *
     * @param $data
     * @param $filter
     * @return array
     */
    public static function prepareTable($data, $filter): array
    {
        $final = array_values($data);
        $unit = self::$chart_details['table']['time_unit'];

        $result = [];
        foreach ($final as $element) {
            foreach ($element as $key => $item) {
                if (in_array($key, $filter['columns']['data'], true)) {
                    if ($filter['unit'] == 'time') {
                        $item = handleTimeUnit($item, $unit);
                        $display_unit = __("dashboard.$unit");
                    } elseif ($filter['unit'] == 'mixed') {
                        if (self::expectTime($filter, $key)) {
                            $item = handleTimeUnit($item, $unit);
                            $display_unit = __("dashboard.$unit");
                        } else {
                            $display_unit = __("dashboard.record");
                        }
                    }else{
                        $display_unit = __("dashboard.record");
                    }
                    $element[$key] = $item . ' ' . $display_unit;

                } else {
                    $element[$key] = $item;
                }
            }
            $result[] = $element;
        }

        return [
            'table' => $result,
            'columns' => array_keys($result[0] ?? [])
        ];
    }


    /**
     * Check column is expected time or not
     *
     * @param $filter
     * @param $column
     * @return bool
     */
    public static function expectTime($filter, $column): bool
    {
        $unit = $filter['columns']['unit'];

        if ($unit == 'time') {
            return true;
        } elseif ($unit == 'mixed') {
            $column_unit = config("{$filter['model_type']}.report.{$filter['report_list']}.column_unit.$column");
            return $column_unit == 'time';
        } else {
            return false;
        }
    }

    /**
     * @param $column
     * @return mixed
     */
    public static function display($column)
    {
        $filter = [
            'risk_duration' => trans('dashboard.risk_duration'),
            'no_risk_duration' => trans('dashboard.no_risk_duration'),
            'notice' => trans('dashboard.noticed'),
            'duration_time' => trans('dashboard.duration_time'),
            'total_notification' => trans('dashboard.total_notification'),
            'not_notice' => trans('dashboard.not_noticed'),
            'date' => trans('dashboard.date'),
            'invitation' => trans('dashboard.invitation'),
            'no_invitation' => trans('dashboard.no_invitation'),
        ];

        return $filter[$column] ?? $column;
    }
}
