<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Report\Entities\Config;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Report\Entities\ChartDetails;
use Modules\Report\Services\ConfigService;

class ConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:read-config', ['only' => ['index']]);
        $this->middleware('permission:update-config', ['only' => ['update']]);
    }

    public function index()
    {
        cache()->forget("chart_details." . app()->getLocale() . '.' . auth()->id());

        return view('report::config.index', [
            'title' => __('dashboard.show_all_config'),
            'config' => ConfigService::get(),
            'details' => ConfigService::getChartDetails()
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $config = Config::updateOrCreate([
            'key' => $request->key,
            'view' => $request->view,
            'value' => $request->value,
            'user_id' => auth()->id(),
        ], [
            'active' => $request->active
        ]);

        cache()->forget("config." . auth()->id());

        if ($config) {
            return response()->json('success');
        }

        return response()->json('error', 400);
    }

    public function updateDetails(Request $request): JsonResponse
    {
        try {
            ChartDetails::updateOrCreate([
                'type' => $request->type,
                'user_id' => auth()->id()
            ], [
                'title' => $request->title,
                'description' => $request->description,
                'time_unit' => $request->time_unit,
            ]);

            cache()->forget("chart_details." . app()->getLocale() . '.' . auth()->id());

            return response()->json([
                'message' => trans('dashboard.updated_successfully'),
                'details' => ConfigService::getChartDetails()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => trans('dashboard.failed_to_update')
            ]);
        }
    }
}
