<?php

namespace Modules\CarModel\Services;

use App\Services\ReportServiceInterface;
use Exception;
use Modules\CarModel\Services\Report\BaseReportFactory;

class CarModelReport implements ReportServiceInterface
{
    /**
     * Show Report for specific site
     *
     * @param $filter
     * @return array
     * @throws Exception
     */
    public function specificShow($filter): array
    {
        try {
            $timeline = true;
            $catId = \Arr::first(\Arr::wrap($filter['site_id']));

            if ($filter['groupBy'] !== 'date') {
                $timeline = false;
            }

            $reportObject = BaseReportFactory::handle($filter['report_list']);

            $reportObject->prepare($filter, $catId, $timeline);

            return $reportObject->getReport($filter);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Comparison Between multi-site Report
     *
     * @param $filter
     * @return array
     * @throws Exception
     */
    public function comparisonShow($filter): array
    {
        try {
            $timeline = true;
            $catId = \Arr::wrap($filter['site_ids']);

            if ($filter['groupBy'] != 'date') {
                $timeline = false;
            }

            $reportObject = BaseReportFactory::handle($filter['report_list']);

            $reportObject->prepare($filter, $catId, $timeline);

            return $reportObject->getReport($filter);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

}
