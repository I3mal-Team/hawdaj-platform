<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Report\Entities\DraftReport;
use Modules\Report\Entities\PinnedReport;
use Modules\Report\Http\Requests\DraftReportRequest;
use Modules\Report\Services\ConfigService;
use Modules\Report\Services\ReportService;

class DraftController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:read-draft_report', ['only' => ['index', 'drawDraft']]);
        $this->middleware('permission:create-draft_report', ['only' => ['storeDraft']]);
        $this->middleware('permission:delete-draft_report', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('report::draft.index', [
            'title' => __('dashboard.draft_reports'),
            'drafts' => DraftReport::latest()->with('createdBy')->get()
        ]);
    }

    public function storeDraft(Request $request): JsonResponse
    {
        try {
            $config = ConfigService::get();
            $catId = $request->type == 'comparison' ? $request->site_ids : \Arr::wrap($request->site_id);

            if (empty($catId)) {
                $catId = [Site::latest()->first('id')->toArray()['id']];
            }

            \DB::beginTransaction();

            $data = $request->all();

            if ($data['time_type'] == 'dynamic') {
                $data['start'] = null;
                $data['end'] = null;
            } else {
                $data['time_range'] = null;
            }

            $draft = DraftReport::create([
                'start' => $data['start'],
                'end' => $data['end'],
                'model_type' => $request->model_type,
                'report_type' => $request->type ?? 'specific',
                'groupBy' => $request->type == 'comparison' ? 'site_name' : 'date',
                'time_type' => $data['time_type'],
                'time_range' => $data['time_range'],
                'unit' => $request->unit ?? 'number',
                'report_list' => $request->report_list ?? 'total',
                'user_id' => auth()->id(),
                'columns' => is_string($request->columns) ? json_decode($request->columns) : $request->columns,
                'site_id' => $catId,
                'title' => $request->title ?? null,
            ]);

            if (!$draft->wasRecentlyCreated) {
                return response()->json([
                    'message' => trans('dashboard.this_report_is_already_drafted')
                ]);
            }

            $details = ConfigService::getChartDetails();
            $charts = ConfigService::handleView($config, 'chart', 'report');
            $card = $config['card']['card'] ? 'card' : false;

            if ($card) {
                $charts[] = $card;
            }

            foreach ($charts as $chart) {
                $draft->charts()->create([
                    'type' => $chart,
                    'title' => $details[$chart]['title'],
                    'description' => $details[$chart]['description'],
                    'time_unit' => $details[$chart]['time_unit'],
                ]);
            }

            \DB::commit();

            return response()->json([
                'message' => trans('dashboard.draft_report_successfully')
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return errorMessage($e->getMessage());
        }
    }

    public function edit(DraftReport $draft)
    {
        if ($draft->user_id != auth()->id()) {
            abort(404);
        }

        return view('report::draft.edit', [
            'draft' => $draft,
            'title' => __('dashboard.edit_draft', ['title' => $draft->title]),
        ]);
    }

    public function update(DraftReport $draft, DraftReportRequest $request)
    {
        try {
            $data = $request->validated();

            if ($data['time_type'] == 'dynamic') {
                $data['start'] = null;
                $data['end'] = null;
            } else {
                $data['time_range'] = null;
            }

            $draft->update($data);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => trans('dashboard.updated_successfully')
                ]);
            }

            return redirect(url('dashboard/report/draft'))->with([
                'message' => trans('dashboard.updated_successfully')
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return errorMessage($e->getMessage());
        }
    }

    public function drawDraft($id)
    {
        try {
            $draft = DraftReport::findOrFail($id);

            if ($draft->user_id != auth()->id()) {
                abort(404);
            }

            if ($draft->time_type == 'dynamic') {
                $date = getStartEndDate($draft->time_range);
                $draft->start = $date['start'];
                $draft->end = $date['end'];
            }

            $result = ReportService::handleDraft($draft);

            return view('report::show', [
                'title' => "Show $draft->model_type Report",
                'data' => $result['data'],
                'add_pinned' => true,
                'filter' => $result['filter'],
                'charts' => $result['charts'],
                'details' => $result['details'],
            ]);

        } catch (\Exception $e) {
            return redirect(route('draft.index'))->with([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $item = DraftReport::whereId($id)->first();
            if ($item->user_id != auth()->id()) {
                abort(404);
            }
            foreach ($item->charts as $chart) {
                foreach ($chart->pinned as $pinned) {
                    cache()->forget("report_pinned_$pinned->id");
                    $pinned->delete();
                }
                $chart->delete();
            }
            $item->charts()->delete();
            $item->delete();

            return response()->json([
                'message' => trans('dashboard.draft_report_delete_successfully')
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => trans('dashboard.failed_to_delete_row')
            ]);
        }
    }
}
