<?php

namespace Modules\CarModel\Services\Report;

use Exception;

class BaseReportFactory
{
    /**
     * Handle Creating object for correct report type
     *
     * @throws Exception
     */
    public static function handle($type)
    {
        try {
            $className = config("CarModel.report.$type.className");
            $full_name = "\Modules\CarModel\Services\Report\\" . $className;

            return new $full_name();

        } catch (\Error $e) {
            throw new Exception($e->getMessage());
        }
    }
}
