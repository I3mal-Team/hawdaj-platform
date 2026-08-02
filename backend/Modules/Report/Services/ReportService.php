<?php

namespace Modules\Report\Services;

use Illuminate\Support\Str;
use Module;
use Modules\Report\Entities\DraftReport;
use Psr\SimpleCache\InvalidArgumentException;

class ReportService
{
    /**
     * check this model have a class report or not
     *
     * @param $type
     * @return false|mixed
     */
    public static function enableReport($type)
    {
        $path = "\\Modules\\$type\Services\\{$type}Report";

        $reportClass = new $path();

        return is_object($reportClass) ? $reportClass : false;
    }

    /**
     * handle filter data and configuration to return report data
     *
     * @param $filter
     * @param $charts
     * @return array|false
     * @throws \Exception
     */
    public static function handle($filter, $charts)
    {
        try {
            if (!$reportObject = self::enableReport($filter['model_type'])) {
                return false;
            }

            if ($filter['type'] === 'specific') {
                $objectResult = $reportObject->specificShow($filter) ?? [];
            } else {
                $objectResult = $reportObject->comparisonShow($filter) ?? [];
            }

            $charts = is_array($charts) ? $charts : [$charts];

            $result = [];
            if ($filter['has_card'] && in_array('card', $charts, true)) {
                foreach ($filter['columns']['data'] as $column) {
                    $result['cards'][$column] = array_sum(array_column($objectResult, $column));
                }
            }

            //prepare data for each chart by ChartService
            foreach ($charts as $chart) {
                $method = 'prepare' . ucfirst($chart);
                if (method_exists(new ChartService(), $method)) {
                    $result[$chart] = ChartService::$method($objectResult, $filter);
                }
            }

            return $result;

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Get all modules for current user
     *
     * @return array
     */
    public static function getModules(): array
    {
        return array_values(array_filter(array_keys(Module::allEnabled()), static function ($item) {
            if (Str::endsWith($item, 'Model') && ReportService::enableReport($item)) {
                return $item;
            }
        }));
    }

    /**
     * Draft report and store all report configuration
     *
     * @param $draft
     * @param array $condIds
     * @return array
     * @throws \Exception
     */
    public static function handleDraft($draft, array $condIds = []): array
    {
        try {
            $filter = [
                'start' => $draft->start,
                'end' => $draft->end,
                'type' => $draft->report_type,
                'report_list' => $draft->report_list,
                'time_type' => $draft->time_type,
                'site_id' => $draft->site_id,
                'time_range' => $draft->time_range,
                'model_type' => $draft->model_type,
                'groupBy' => $draft->groupBy ?? 'date',
                'columns' => ['data' => $draft->columns, 'unit' => $draft->unit],
                'unit' => $draft->unit,
                'has_card' => $draft->has_card ?? true,
                'draft' => true
            ];

            if ($draft->report_type == 'specific') {
                $filter['site_id'] = is_string($draft->site_id)
                    ? \Arr::first(explode(',', $draft->site_id))
                    : \Arr::first($draft->site_id);

            } elseif ($draft->report_type == 'comparison') {
                $filter['site_ids'] = is_string($draft->site_id)
                    ? explode(',', $draft->site_id)
                    : $draft->site_id;
            }

            if (!empty($condIds)) {
                $details = $draft->charts()->where('active', true)
                    ->whereIn('id', $condIds)
                    ->select('type', 'title', 'id', 'description', 'time_unit')
                    ->get()
                    ->groupBy('type')
                    ->map(fn($item) => $item->first())
                    ->toArray();
            } else {
                $details = $draft->charts()->where('active', true)
                    ->select('type', 'title', 'id', 'description', 'time_unit')
                    ->get()
                    ->groupBy('type')
                    ->map(fn($item) => $item->first())
                    ->toArray();
            }

            $charts = array_keys($details);

            if (!$result = self::handle($filter, $charts)) {
                abort(403);
            }

            return [
                'data' => $result,
                'filter' => $filter,
                'charts' => $charts,
                'details' => $details
            ];

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Draft report and store all report configuration
     *
     * @param $pinned
     * @return array[]
     * @throws InvalidArgumentException
     * @throws \Exception
     */
    public static function handlePinned($pinned): array
    {
        try {
            cache()->forget("report_pinned_$pinned->id");

            $result = [];
            if (!cache()->has("report_pinned_$pinned->id") || empty(cache("report_pinned_$pinned->id"))) {
                $pinned_charts = $pinned->charts->groupBy('draft_id');

                foreach ($pinned_charts as $draft => $charts) {
                    $draft = DraftReport::find($draft);

                    if ($pinned->global_date) {
                        $draft->start = $pinned->start ?? now()->toDateString();
                        $draft->end = $pinned->end ?? now()->toDateString();
                    } else {
                        $date = getStartEndDate($draft->time_range);
                        $draft->start = $date['start'];
                        $draft->end = $date['end'];
                    }

                    $result[] = self::handleDraft($draft, $charts->pluck('id')->toArray());
                }

                cache()->remember("report_pinned_$pinned->id", 60 * 60, function () use ($result) {
                    return $result;
                });
            } else {
                $result = cache("report_pinned_$pinned->id");
            }

            return $result ?? [self::handleDefault()];

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param string $type
     * @param int $time_range
     * @return array
     * @throws \Exception
     */
    public static function handleDefault(string $type = 'MaskModel', int $time_range = 14): array
    {
        $date = getStartEndDate($time_range);
        $details = ConfigService::getChartDetails();
        $catID = auth()->id()->sites()->where('last_child', 1)->take(5)->toArray();

        $filter = [
            'model_type' => $type,
            'region_type' => 'all',
            'report_list' => 'actions',
            'start' => $date['start'],
            'end' => $date['end'],
            'time_range' => '14',
            'time_type' => 'dynamic',
            'type' => 'specific',
            'site_id' => $catID,
            'groupBy' => 'date',
            'unit' => 'number',
            'columns' => ['data' => ['notice', 'not_notice'], 'unit' => 'number'],
            'has_card' => true
        ];

        if (!$result = self::handle($filter, array_keys($details))) {
            abort(403);
        }

        return [
            'data' => $result,
            'filter' => $filter,
            'charts' => array_keys($details),
            'details' => $details
        ];
    }
}
