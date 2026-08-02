<?php

namespace App\Http\Controllers\Dashboard\Visits;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Visits\VisitorRequest;
use App\Models\Company;
use App\Models\Visitor;
use App\Services\UploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * @param VisitorRequest $request
     * @return JsonResponse
     */
    public function store(VisitorRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $data = $request->only([
                'first_name', 'last_name', 'id_number', 'id_type', 'mobile', 'email', 'gender',
                'nationality', 'comment', 'vehicle_detail', 'vehicle_material', 'vehicle_remark','type'
            ]);

            if (request()->hasFile('personal_photo')) {
                $data['personal_photo'] = UploadService::store($request->personal_photo, 'visitors');
            }

            if (request()->hasFile('id_copy')) {
                $data['id_copy'] = UploadService::store($request->id_copy, 'visitors');
            }

            //Handle company when create visitor api
            $company = Company::updateOrCreate([
                'name' => $request->company,
            ], [
                'position' => $request->position,
                'mobile' => $request->company_mobile,
                'email' => $request->company_email
            ]);

            $data['company_id'] = $company->id;

            $visitor = Visitor::create($data);

            DB::commit();
            if ($request->expectsJson()) {
                return response()->json([
                    'id' => $visitor->id,
                    'name' => $visitor->first_name . ' ' . $visitor->last_name,
                    'message' => trans('dashboard.visitor_added_successfully'),
                ]);
            }

            return $visitor;
        } catch (\Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }

    /**
     * @return JsonResponse
     */
    public function getVisitor(): JsonResponse
    {
        $query = Visitor::select('id', 'first_name', 'last_name');

        $type = \request('type') == 'contract' ? 'contractor' : 'visitor';

        $data = $query->where('type', $type)->get();

        return response()->json([
            'data' => $data,
            'message' => __('dashboard.success'),
            'code' => 200
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getVisitorByIds(Request $request): JsonResponse
    {
        return response()->json(Visitor::whereIn('id', $request->ids)->get());
    }

    /**
     * @param Visitor $visitor
     * @return JsonResponse
     */
    public function destroy(Visitor $visitor): JsonResponse
    {
        $visitor->delete();

        return response()->json([
            'message' => trans('dashboard.visitor_delete_successfully')
        ]);
    }
}
