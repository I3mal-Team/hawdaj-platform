<?php

use Carbon\Carbon;

if (!function_exists('addCondationTime')) {
    function addCondationTime($item, $type)
    {
        if ($type == 1) {
            return round($item / 60, 2);
        }

        if ($type == 2) {
            return round(($item / 60) / 24, 2);
        }

        if ($type == 3) {
            return round($item * 60, 2);
        }

        if ($type == 4) {
            return round($item * 60 * 24, 2);
        }
    }
}

if (!function_exists('timeMap')) {
    function timeMap($data, $type)
    {
        return array_map(function ($item) use ($type) {
            return is_numeric($item) ? addCondationTime($item, $type) : $item;
        }, $data);
    }
}

if (!function_exists('handleTimeUnit')) {
    function handleTimeUnit($time = 0, $to = 'minute', $from = 'minute')
    {
        if ($time == 0) {
            return $time;
        }
        if ($to == 'minute' && $from == 'minute') {
            return $time;
        }
        $return_ype = 'array';

        if (!is_array($time)) {
            $return_ype = 'string';
            $time = [$time];
        }
        if ($to == 'hour' && $from == 'minute') {
            $result = timeMap($time, 1);
            return $return_ype == 'array' ? $result : Arr::first($result);
        }
        if ($to == 'day' && $from == 'minute') {
            $result = timeMap($time, 2);
            return $return_ype == 'array' ? $result : Arr::first($result);
        }
        if ($from == 'hour' && $to == 'minute') {
            $result = timeMap($time, 3);
            return $return_ype == 'array' ? $result : Arr::first($result);
        }
        if ($from == 'day' && $to == 'minute') {
            $result = timeMap($time, 4);
            return $return_ype == 'array' ? $result : Arr::first($result);
        }
        return 0;
    }
}

if (!function_exists('handleRange')) {
    function handleRange($range = null)
    {
        if ($range == 'today' || $range == null) {
            return trans('dashboard.today');
        }
        if ($range == 14) {
            return trans('dashboard.this_week');
        }
        if ($range == 16) {

            return trans('dashboard.this_month');
        }
        if ($range == 15) {
            return trans('dashboard.last_month');
        }
        if ($range == 13) {
            return trans('dashboard.last_week');
        }
        return $range;
    }
}

if (!function_exists('getStartEndDate')) {
    function getStartEndDate($time = null): array
    {
        if (is_null($time) || $time == "today") {

            $start_name = Carbon::today()->format("Y-m-d");
            $last_name = Carbon::today()->format("Y-m-d");

        } elseif ((int)$time == 14) {

            $start_name = Carbon::now()->startOfWeek()->subDays(1)->format("Y-m-d");
            $last_name = Carbon::now()->endOfWeek()->subDays(1)->format("Y-m-d");

        } elseif ((int)$time == 13) {

            $start_name = Carbon::now()->subWeek(1)->startOfWeek()->addDays(1)->format("Y-m-d");
            $last_name = Carbon::now()->subWeek(1)->endOfWeek()->subDays(1)->format("Y-m-d");

        } elseif ($time == 15) {

            $start_name = (new Carbon('first day of last month'))->format('Y-m-d');
            $last_name = (new Carbon('last day of last month'))->format('Y-m-d');

        } elseif ($time == 16) {

            $start_name = Carbon::now()->startOfMonth()->format('Y-m-d');
            $last_name = Carbon::now()->format('Y-m-d');

        } elseif ($time == 17) {

            $start_name = Carbon::now()->startOfYear()->format('Y-m-d');
            $last_name = Carbon::now()->format('Y-m-d');

        } else {
            $start_name = Carbon::create(date('Y'), $time)->startOfMonth()->format('Y-m-d');
            $last_name = Carbon::create(date('Y'), $time)->lastOfMonth()->format('Y-m-d');
        }

        return ['start' => $start_name, 'end' => $last_name];
    }
}

if (!function_exists('isExpectTime')) {
    function isExpectTime($module,$report_type, $column)
    {
        $column_unit = config("$module.report.$report_type.column_unit.$column");
        return $column_unit == 'time';
    }
}
