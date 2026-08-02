<?php

namespace Modules\CarModel\Services\Report;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class CompareInvitationReport extends BaseReport
{
    public $invitationQuery;
    public $noInvitationQuery;

    public function prepare($filter, $catId, bool $timeline = false): void
    {
        $filter['format_date'] = $this->guessDateFormat($filter['start'], $filter['end']);
        $filter['catId'] = $catId;

        if ($timeline) {
            $invitationQuery = $this->prepareTimeLineQuery($filter, "invitation");
            $noInvitationQuery = $this->prepareTimeLineQuery($filter, "no_invitation");
        } else {
            $invitationQuery = $this->prepareBaseQuery($filter, "invitation");
            $noInvitationQuery = $this->prepareBaseQuery($filter, "no_invitation");
        }

        $this->invitationQuery = $invitationQuery;
        $this->noInvitationQuery = $noInvitationQuery;
    }

    /**
     * @throws \JsonException
     */
    public function getReport($filter): array
    {
        $filter['column'] = "$this->mainTable.created_at";

        $invitationQuery = $this->handleDateFilter($this->invitationQuery, $filter);
        $noInvitationQuery = $this->handleDateFilter($this->noInvitationQuery, $filter);

        $invitation = json_decode($invitationQuery->groupBy($filter['groupBy'])
            ->get()
            ->mapWithKeys(function ($item) use ($filter) {
                return [$item->{$filter['groupBy']} => $item];
            }), true, 512, JSON_THROW_ON_ERROR);

        $noInvitation = json_decode($noInvitationQuery->groupBy($filter['groupBy'])
            ->get()
            ->mapWithKeys(function ($item) use ($filter) {
                return [$item->{$filter['groupBy']} => $item];
            }), true, 512, JSON_THROW_ON_ERROR);

        $result = array_merge_recursive_distinct($invitation, $noInvitation);

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
        return DB::table($this->mainTable)
            ->join('sites', 'sites.id', '=', "$this->mainTable.site_id")
            ->whereIn("$this->mainTable.site_id", \Arr::wrap($filter['catId']))
            ->where("$this->mainTable.status", '=', $type == "invitation")
            ->select(
                "sites.name as {$filter['groupBy']}",
                DB::raw("COUNT($this->mainTable.id) as $type")
            );
    }

    /**
     * @param $filter
     * @param $type
     * @return Builder
     */
    private function prepareTimeLineQuery($filter, $type): Builder
    {
        return DB::table($this->mainTable)
            ->whereIn("$this->mainTable.site_id", \Arr::wrap($filter['catId']))
            ->where("$this->mainTable.status", '=', $type == "invitation")
            ->select(
                DB::raw("(DATE_FORMAT($this->mainTable.created_at, '{$filter['format_date']}')) as {$filter['groupBy']}"),
                DB::raw("COUNT($this->mainTable.id) as $type")
            );
    }

}
