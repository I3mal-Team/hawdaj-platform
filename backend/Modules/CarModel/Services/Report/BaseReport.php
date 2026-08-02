<?php

namespace Modules\CarModel\Services\Report;

abstract class BaseReport
{
    /**
     * Main Table Of Report getting Important data
     *
     * @var string
     */
    public string $mainTable = 'car_plates';

    /**
     * Get Daily Data By use this table
     *
     * @var string
     */
    public string $tableDay = 'car_days';

    /**
     * Prepare Important Properties to execute report
     *
     * @param $filter
     * @param $catId
     * @param bool $timeline
     * @return void
     */
    abstract public function prepare($filter, $catId, bool $timeline = false): void;

    /**
     * Return Data to draw report
     *
     * @param $filter
     * @return array
     */
    abstract public function getReport($filter): array;

    /**
     * Guess Correct Date Format Based on duration
     *
     * @param $start
     * @param $end
     * @return string
     */
    public function guessDateFormat($start = null, $end = null): string
    {
        $to = carbon()->createFromFormat('Y-m-d H:s:i', "$start 00:00:00");
        $from = carbon()->createFromFormat('Y-m-d H:s:i', "$end 11:59:59");
        $diff = $to->diffInDays($from);

        $diff = !$diff ? 'today' : $diff;

        switch ($diff) {
            case ($diff === 'today'):
                return '%h:00 %p';
            case ($diff < 32 && $diff >= 1):
                return '%d-%b-%Y';
//            case ($diff < 32 && $diff > 15):
//                return 'Week %u-%b-%Y';
            case ($diff < 367 && $diff >= 32):
                return '%b-%Y';
            case ($diff > 367):
                return '%Y';
            default:
                return "%m-%Y";
        }
    }

    /**
     * Helper Function Use to handle time range
     *
     * @param $query
     * @param $filter
     * @return mixed
     */
    public function handleDateFilter($query, $filter)
    {
        if ($filter['start']) {
            $start = ($filter['start'] > date('Y-m-d')) ? date('Y-m-d') : $filter['start'];
            $query->whereDate($filter['column'], '>=', $start);
        }

        if ($filter['end']) {
            $query->whereDate($filter['column'], '<=', $filter['end']);
        }

        return $query;
    }
}
