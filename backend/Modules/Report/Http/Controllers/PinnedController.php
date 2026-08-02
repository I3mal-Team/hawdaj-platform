<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Report\Entities\DraftChart;
use Modules\Report\Entities\PinnedReport;
use Modules\Report\Http\Requests\PinnedReportRequest;
use Modules\Report\Services\ReportService;

class PinnedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:read-pinned_report', ['only' => ['index', 'drawPinned']]);
        $this->middleware('permission:create-pinned_report', ['only' => ['storePinned']]);
        $this->middleware('permission:delete-pinned_report', ['only' => ['destroy']]);
    }

    public function index()
    {
         return view('report::pinned.index', [
            'title' => __('dashboard.pinned_reports'),
            'pinneds' => PinnedReport::primary()->latest()->withCount('charts')->get()
        ]);
    }

    public function getRelatedDraft(Request $request)
    {
        $chart_id = $request->chart_id;
        $pinneds = PinnedReport::primary()->latest()->pluck('title', 'id')->toArray();
        $chart_pinneds = \DB::table('pinned_charts')->where('chart_id', $chart_id)->pluck('pinned_id')->toArray();

         return view('report::extra._add_pinned', [
            'pinneds' => $pinneds,
            'chart_id' => $chart_id,
            'chart_pinneds' => $chart_pinneds,
        ]);
    }

    public function addDraft(Request $request)
    {
        try {
            \DB::beginTransaction();

            $titles = array_filter($request->titles ?? []);
            $pinned_ids = array_filter($request->pinned_ids ?? []);
            $chart = DraftChart::find($request->chart_id);
            $new_pinned = [];

            if (!empty($titles)) {
                foreach ($titles as $title) {
                    $pinned = PinnedReport::updateOrCreate([
                        'title' => $title ?? carbon()->toDateString() . ' Pinned Report',
                        'user_id' => auth()->id()
                    ]);

                    $new_pinned[] = $pinned->id;
                }
            }

            $all_pinned = array_merge($pinned_ids, $new_pinned);

            foreach (PinnedReport::pluck('id')->toArray() as $item) {
                cache()->forget("report_pinned_$item");
            }

            $chart->pinned()->sync($all_pinned);

            \DB::commit();

            return response()->json([
                'message' => trans('dashboard.chart_pinned_to_reports_successfully')
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return errorMessage($e->getMessage());
        }
    }

    public function edit(PinnedReport $pinned)
    {
        if ($pinned->user_id != auth()->id()) {
            abort(404);
        }

         return view('report::pinned.edit', [
            'pinned' => $pinned,
            'title' => __('dashboard.edit_pinned', ['title' => $pinned->title]),
        ]);
    }

    public function reload($id): JsonResponse
    {
        cache()->forget("report_pinned_$id");

        return response()->json(['message' => __('dashboard.pinned_updated_successfully')]);
    }

    public function status($id, Request $request)
    {
        try {
            $status = $request->status;

            PinnedReport::primary()->whereId($id)->update([
                'active' => (boolean)$status
            ]);

            PinnedReport::primary()->where('id', '<>', $id)->update([
                'active' => false
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => trans('dashboard.report_active_successfully')
                ]);
            }

            return back()->with([
                'message' => trans('dashboard.report_active_successfully')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => trans('dashboard.failed_to_active_report')
            ], 400);
        }
    }

    public function drawPinned($id)
    {
        try {
            $pinned = PinnedReport::findOrFail($id);

            if ($pinned->user_id != auth()->id()) {
                abort(404);
            }

            if ($pinned->global_date) {
                if ($pinned->time_type == 'dynamic') {
                    $date = getStartEndDate($pinned->time_range);
                    $pinned['start'] = $date['start'];
                    $pinned['end'] = $date['end'];
                }
            }

            $report = ReportService::handlePinned($pinned);

             return view('dashboard.index', [
                'title' => __('dashboard.show_title', ['title' => $pinned->title]),
                'report' => $report
            ]);

        } catch (\Exception $e) {
            return redirect(route('pinned.index'));
        }
    }

    public function update(PinnedReport $pinned, PinnedReportRequest $request)
    {
        if ($pinned->user_id != auth()->id()) {
            abort(404);
        }

        try {
            $data = $request->validated();

            if (!$data['global_date']) {
                $data['start'] = null;
                $data['end'] = null;
                $data['time_type'] = null;
                $data['time_range'] = null;
            } else {
                if ($data['time_type'] == 'dynamic') {
                    $data['start'] = null;
                    $data['end'] = null;
                } else {
                    $data['time_range'] = null;
                }
            }
            cache()->forget("report_pinned_$pinned->id");

            $pinned->update($data);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => trans('dashboard.pinned_updated_successfully')
                ]);
            }

            return redirect(url('dashboard/report/pinned'))->with([
                'message' => trans('dashboard.pinned_updated_successfully')
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return errorMessage($e->getMessage());
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $item = PinnedReport::whereId($id)->first();

            if ($item->user_id != auth()->id()) {
                abort(404);
            }

            $item->charts()->detach();
            $item->delete();

            return response()->json([
                'message' => trans('dashboard.pinned_report_delete_successfully')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => trans('dashboard.failed_to_delete_row')
            ]);
        }
    }
}
