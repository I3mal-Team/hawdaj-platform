<?php

namespace Modules\CarModel\Services\Report;

use Illuminate\Support\Facades\DB;
use JsonException;

class TotalReport extends BaseReport
{
    public $query;

    public function prepare($filter, $catId, bool $timeline = false): void
    {
        $format_date = $this->guessDateFormat($filter['start'], $filter['end']);

        $query = DB::table($this->mainTable)->whereIn('site_id', \Arr::wrap($catId));

        if ($timeline) {
            $query->select(
                DB::raw("(DATE_FORMAT($this->mainTable.created_at, '$format_date')) as {$filter['groupBy']}"),
                DB::raw("COUNT($this->mainTable.id) as count")
            );
        } else {
            $query->join('sites', 'sites.id', '=', "$this->mainTable.site_id")
                ->select(
                    "sites.name as {$filter['groupBy']}",
                    DB::raw("COUNT($this->mainTable.id) as count")
                );
        }

        $this->query = $query;
    }

    /**
     * @throws JsonException
     */
    public function getReport($filter): array
    {
        $filter['column'] = "$this->mainTable.created_at";

        $query = $this->handleDateFilter($this->query, $filter);

        return json_decode($query->groupBy($filter['groupBy'])
            ->get()
            ->mapWithKeys(function ($item) use ($filter) {
                return [$item->{$filter['groupBy']} => $item];
            }), true, 512, JSON_THROW_ON_ERROR);
    }
}
