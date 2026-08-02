<?php

namespace Modules\CarModel\Services\Report;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class NotificationReport extends BaseReport
{
    public $totalQuery;
    public $durationQuery;

    public function prepare($filter, $catId, bool $timeline = false): void
    {
        $format_date = $this->guessDateFormat($filter['start'], $filter['end']);
        $filter['format_date'] = ($format_date !== '%h:00 %p') ? $format_date : '%d-%b-%Y';
        $filter['catId'] = $catId;

        if ($timeline) {
            $totalQuery = $this->prepareTimeLineQuery($filter, 'total_notification');
            $durationQuery = $this->prepareTimeLineQuery($filter, 'duration_time');
        } else {
            $totalQuery = $this->prepareBaseQuery($filter, 'total_notification');
            $durationQuery = $this->prepareBaseQuery($filter, 'duration_time');
        }

        $this->totalQuery = $totalQuery;
        $this->durationQuery = $durationQuery;
    }

    /**
     * @throws \JsonException
     */
    public function getReport($filter): array
    {
        $totalFilter = array_merge($filter, ['column' => "$this->mainTable.created_at"]);
        $durationFilter = array_merge($filter, ['column' => "$this->tableDay.day"]);

        $totalQuery = $this->handleDateFilter($this->totalQuery, $totalFilter);
        $durationQuery = $this->handleDateFilter($this->durationQuery, $durationFilter);

        $total = json_decode($totalQuery->groupBy($filter['groupBy'])
            ->get()
            ->mapWithKeys(function ($item) use ($filter) {
                return [$item->{$filter['groupBy']} => $item];
            }), true, 512, JSON_THROW_ON_ERROR);

        $duration = json_decode($durationQuery->groupBy($filter['groupBy'])
            ->get()
            ->mapWithKeys(function ($item) use ($filter) {
                return [$item->{$filter['groupBy']} => $item];
            }), true, 512, JSON_THROW_ON_ERROR);

        $result = array_merge_recursive_distinct($total, $duration);

        $columns = $filter['columns']['data'];

        return collect($result)->map(function ($item) use ($columns) {
            foreach ($columns as $column) {
                if (!isset($item[$column])) {
                    $item[$column] = 0;
                }
            }
            return $item;
        })->toArray();
    }

    /**
     * @param $filter
     * @param $type
     * @return Builder
     */
    private function prepareBaseQuery($filter, $type): Builder
    {
        if ($type === 'total_notification') {
            $query = DB::table($this->mainTable)
                ->join('sites','sites.id','=',"$this->mainTable.site_id")
                ->whereIn('site_id', \Arr::wrap($filter['catId']))
                ->select(
                    "sites.name as {$filter['groupBy']}",
                    DB::raw("COUNT($this->mainTable.id) as $type")
                );
        } else {
            $query = DB::table($this->tableDay)
                ->join('sites','sites.id','=',"$this->tableDay.site_id")
                ->whereIn('site_id', \Arr::wrap($filter['catId']))
                ->select(
                    "sites.name as {$filter['groupBy']}",
                    DB::raw("SUM($this->tableDay.risk_duration) as $type"),
                );
        }

        return $query;
    }

    /**
     * @param $filter
     * @param $type
     * @return Builder
     */
    private function prepareTimeLineQuery($filter, $type): Builder
    {
        if ($type === 'total_notification') {
            $query = DB::table($this->mainTable)
                ->whereIn('site_id', \Arr::wrap($filter['catId']))
                ->select(
                    DB::raw("(DATE_FORMAT($this->mainTable.created_at, '{$filter['format_date']}')) as {$filter['groupBy']}"),
                    DB::raw("COUNT($this->mainTable.id) as $type")
                );
        } else {
            $query = DB::table($this->tableDay)
                ->whereIn('site_id', \Arr::wrap($filter['catId']))
                ->select(
                    DB::raw("(DATE_FORMAT($this->tableDay.day, '{$filter['format_date']}')) as {$filter['groupBy']}"),
                    DB::raw("SUM($this->tableDay.risk_duration) as $type"),
                );
        }

        return $query;
    }

}
