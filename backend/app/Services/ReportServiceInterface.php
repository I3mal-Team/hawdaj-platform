<?php

namespace App\Services;

interface ReportServiceInterface
{
    public function specificShow($filter);

    public function comparisonShow($filter);
}
