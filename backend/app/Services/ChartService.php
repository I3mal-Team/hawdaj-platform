<?php

namespace App\Services;

class ChartService
{
    public static function prepareLine($data, $filter): array
    {
        foreach ($filter['columns'] as $key => $column) {
            $result[$key]['data'] = array_values(collect($data)->transform(function ($item, $key) use ($column) {
                return [
                    'x' => $key,
                    'y' => $item[$column]
                ];
            })->toArray());

            $result[$key]['name'] = self::display($column);
        }
        return $result ?? [];
    }

    public static function prepareBar($data, $filter): array
    {
        $result['categories'] = array_keys($data);

        foreach ($filter['columns'] as $column) {
            $result['series'][] = [
                'name' => self::display($column),
                'type' => 'column',
                'data' => array_filter(\Arr::pluck($data, $column))
            ];
        }

        return $result;
    }

    public static function preparePie($data, $filter): array
    {
        foreach ($filter['columns'] as $column) {
            $result[$column] = [
                'name' => array_filter(\Arr::pluck($data, $filter['groupBy'])),
                'value' => array_filter(array_map(fn($item) => (int)$item, \Arr::pluck($data, $column))),
            ];
        }

        return $result ?? [];
    }

    public static function prepareTable($data): array
    {
        $final = array_values($data);

        return [
            'table' => $final,
            'columns' => array_keys($final[0] ?? [])
        ];
    }

    public static function display($column)
    {
        $filter = [
            'risk' => trans('dashboard.risk'),
            'no_risk' => trans('dashboard.no_risk'),
            'date' => trans('dashboard.date')
        ];

        return $filter[$column] ?? $column;
    }
}
