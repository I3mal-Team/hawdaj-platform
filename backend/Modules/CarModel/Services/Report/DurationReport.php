<?php

namespace Modules\CarModel\Services\Report;

use Illuminate\Support\Facades\DB;
use JsonException;

class DurationReport extends BaseReport
{
    public $query;

    public function prepare($filter, $catId, bool $timeline = false): void
    {
        $format_date = $this->guessDateFormat($filter['start'], $filter['end']);
        $format_date = ($format_date !== '%h:00 %p') ? $format_date : '%d-%b-%Y';

        $query = DB::table($this->tableDay)->whereIn('site_id', \Arr::wrap($catId));

        if ($timeline) {
            $query->select(
                DB::raw("(DATE_FORMAT($this->tableDay.day, '$format_date')) as {$filter['groupBy']}"),
                DB::raw("SUM($this->tableDay.risk_duration) as risk_duration"),
                DB::raw("SUM($this->tableDay.no_risk_duration) as no_risk_duration"),
            );
        } else {
            $query->join('sites', 'sites.id', '=', "$this->tableDay.site_id")
                ->select(
                    "sites.name as {$filter['groupBy']}",
                    DB::raw("SUM($this->tableDay.risk_duration) as risk_duration"),
                    DB::raw("SUM($this->tableDay.no_risk_duration) as no_risk_duration"),
                );
        }

        $this->query = $query;
    }

    /**
     * @throws JsonException
     */
    public function getReport($filter): array
    {
        $filter['column'] = "$this->tableDay.day";

        $query = $this->handleDateFilter($this->query, $filter);

        return json_decode($query->groupBy($filter['groupBy'])
            ->get()
            ->mapWithKeys(function ($item) use ($filter) {
                return [$item->{$filter['groupBy']} => $item];
            }), true, 512, JSON_THROW_ON_ERROR);
    }

}
