<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Site;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Report\Http\Requests\ReportRequest;
use Modules\Report\Services\ConfigService;
use Modules\Report\Services\ReportService;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:read-report', ['only' => ['index', 'show', 'filter']]);
    }

    public function index()
    {
        return view('report::index', [
            'title' => __('dashboard.builder'),
            'models' => ReportService::getModules()
        ]);
    }

    /**
     * @param $type
     * @return Application|Factory|View
     */
    public function filter($type)
    {
        return view('report::filter', [
            'title' => __('dashboard.report_title', ['title' => $type]),
            'type' => $type
        ]);
    }

    /**
     * @param ReportRequest $request
     * @return Application|Factory|View|JsonResponse|RedirectResponse
     */
    public function show(ReportRequest $request)
    {
        try {
            $columns = config("$request->model_type.report.$request->report_list");
            $config = ConfigService::get();
            $charts = ConfigService::handleView($config, 'chart', 'report');
            $details = ConfigService::getChartDetails();
            $filter = $request->except('_token');
            $filter['type'] = $filter['type'] ?? 'specific';
            $filter['groupBy'] = $filter['type'] === 'specific' ? 'date' : 'site_name';
            $filter['draft'] = false;

            if ($request->has('site_ids')) {
                $filter['site_ids'] = is_array($filter['site_ids'])
                    ? $filter['site_ids']
                    : explode(',', $filter['site_ids']);
            }

            $configColumns = [
                'report_list' => $request->report_list ?? 'total',
                'unit' => $columns['unit'],
                'columns' => $columns,
                'has_card' => in_array('report', $config['card']['card'], true)
            ];

            if ($filter['time_type'] === 'dynamic') {
                $date = getStartEndDate($filter['time_range']);
                $filter = array_merge($filter, $date);
            }

            $filter = array_merge($filter, $configColumns);

            if (!$result = ReportService::handle($filter, $filter['chart_type'] ?? $charts)) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'You not have access to show this report'], 403);
                }
                abort(403);
            }

            if ($request->expectsJson()) {
                return response()->json(['data' => $result, 'message' => 'Chart Drawn Successfully']);
            }

            return view('report::show', [
                'title' => __('dashboard.report_title', ['title' => $request->model_type]),
                'data' => $result,
                'filter' => $filter,
                'charts' => $charts,
                'details' => $details
            ]);

        } catch (Exception $e) {
            return errorMessage($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function getsite(Request $request)
    {
        return view("report::extra._select_{$request->type}", [
            'sites' => Site::query()->get()
        ]);
    }

}
