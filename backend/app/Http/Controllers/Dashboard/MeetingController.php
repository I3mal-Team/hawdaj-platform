<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ContractorRequest;
use App\Models\Department;
use App\Models\MaterialsOfRequests;
use App\Models\Reason;
use App\Models\Visitor;
use App\Models\VisitorHealthCheck;
use App\Models\VisitorRequest;
use App\Models\VisitRequest;
use App\Notifications\VisitRequestNotifaction;
use App\Services\UploadService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\HealthCheck;

class MeetingController extends Controller
{

    public function index($visit_id, $visitor_id, $secret_code)
    {
        $title = __('dashboard.visit_info');
        $visitorRequest = VisitorRequest::where('visitor_id', $visitor_id)
            ->where('visit_request_id', $visit_id)
            ->where('secret_code', $secret_code)
            ->with('visitor', 'visitRequest', 'visitor.company')
            ->first();

        $questions = HealthCheck::select('id', 'question')->get();
        $data['reasons'] = Reason::all();
        $data['departments'] = Department::all();

        if ($visitorRequest) {
            return view('dashboard.meetings.create', compact('title', 'data', 'questions', 'visitorRequest'));
        }
        abort(404);
    }

    public function contractRequest($visit_id, $visitor_id, $secret_code)
    {
        $title = __('dashboard.visit_info');
        $contractorRequest = ContractorRequest::where('contractor_id', $visitor_id)
            ->where('contract_request_id', $visit_id)
            ->where('secret_code', $secret_code)
            ->with('contractor', 'contractRequest.contract', 'contractor.company')
            ->first();

//        $questions = HealthCheck::select('id', 'question')->get();
        $data['departments'] = Department::all();

        if ($contractorRequest) {
            return view('dashboard.meetings.contracts.create', compact('title', 'data', 'contractorRequest'));
        }
        abort(404);
    }

    public function materialRequest($material_request_id, $transporter_id, $material_id, $secret_code)
    {
        $title = __('dashboard.visit_info');
        $materialOfRequest = MaterialsOfRequests::where('material_id', $material_id)
            ->where('material_request_id', $material_request_id)
            ->where('transporter_id', $transporter_id)
            ->where('secret_code', $secret_code)
            ->with('transporter', 'material', 'materialRequest', 'materialRequest.site', 'materialRequest.department', 'materialRequest.host')
            ->first();

        $data['departments'] = Department::all();

        if ($materialOfRequest) {
            return view('dashboard.material_meetings.create', compact('title', 'data', 'materialOfRequest'));
        }
    }

    public function confirm(Request $request)
    {
        try {
            \DB::beginTransaction();

            $visitor = Visitor::find($request->visitor_id)->first();

            $data = $request->only(['first_name', 'last_name', 'id_number', 'id_type', 'mobile', 'email', 'gender',
                'nationality', 'comment', 'vehicle_detail', 'vehicle_material', 'vehicle_remark']);

            if ($request->hasFile('personal_photo')) {
                UploadService::delete($visitor->photo);
                $data['personal_photo'] = UploadService::store($request->personal_photo, 'visitors');
            }

            if ($request->hasFile('id_copy')) {
                UploadService::delete($visitor->photo);
                $data['id_copy'] = UploadService::store($request->id_copy, 'visitors');
            }

            \App\Models\Company::updateOrCreate([
                'name' => $request->company,
            ], [
                'position' => $request->position,
                'mobile' => $request->company_mobile,
                'email' => $request->company_email
            ]);

            $updatedVisitor = $visitor->update($data);

            $questions = HealthCheck::select('id', 'question')->get();

            foreach ($questions as $health) {
                VisitorHealthCheck::create([
                    'visitor_id' => $request->visitor_id,
                    'health_check_id' => $health->id,
                    'answer' => $request->{'health_check_id_' . $health->id} ?? 0,
                    'visitor_request_id' => $request->visitor_request_id
                ]);
            }

            $visitor_request = VisitorRequest::where('id', $request->visitor_request_id)->with('visitRequest')->first();

            $visitor_request->status = 'confirmed';
            $visitor_request->save();

            $visitor_request->histroy()->create([
                'date' => Carbon::now()->toDateString(),
                'time' => Carbon::now()->toTimeString(),
                'activity_type' => 'Health Check Updated'
            ]);

            \DB::commit();

            if ($updatedVisitor) {
                return response()->json([
                    'data' => $visitor,
                    'message' => 'Your Meeting Confirmed Successfully',
                    'code' => 200
                ]);
            }

            return response()->json(['data' => [], 'message' => __('dashboard.errorMessage'), 'code' => 500], 500);

        } catch (\Exception $e) {
            \DB::rollBack();

            $visitor_request->histroy()->create([
                'date' => Carbon::now()->toDateString(),
                'time' => Carbon::now()->toTimeString(),
                'activity_type' => 'issue'
            ]);

            \Log::debug('data', [$e->getMessage()]);

            return unKnownError($e->getMessage());
        }
    }

    public function confirmContractRequest(Request $request)
    {
        try {
            \DB::beginTransaction();

            $contractor = Visitor::find($request->contractor_id);

            $data = $request->only(['first_name', 'last_name', 'id_number', 'id_type', 'mobile', 'email', 'gender',
                'nationality', 'comment', 'vehicle_detail', 'vehicle_material', 'vehicle_remark']);

            if ($request->hasFile('personal_photo')) {
                UploadService::delete($visitor->photo);
                $data['personal_photo'] = UploadService::store($request->personal_photo, 'visitors');
            }

            if ($request->hasFile('id_copy')) {
                UploadService::delete($visitor->photo);
                $data['id_copy'] = UploadService::store($request->id_copy, 'visitors');
            }

//            \App\Models\Company::updateOrCreate([
//                'name' => $request->company,
//            ], [
//                'position' => $request->position,
//                'mobile' => $request->company_mobile,
//                'email' => $request->company_email
//            ]);

            $updatedContractor = $contractor->update($data);

//            $questions = HealthCheck::select('id', 'question')->get();

//            foreach ($questions as $health) {
//                VisitorHealthCheck::create([
//                    'visitor_id' => $request->visitor_id,
//                    'health_check_id' => $health->id,
//                    'answer' => $request->{'health_check_id_' . $health->id} ?? 0,
//                    'visitor_request_id' => $request->visitor_request_id
//                ]);
//            }

            $contractor_request = ContractorRequest::where('id', $request->contractor_request_id)->with('contractRequest')->first();

            $contractor_request->status = 'confirmed';
            $contractor_request->save();

            $contractor_request->histroy()->create([
                'date' => Carbon::now()->toDateString(),
                'time' => Carbon::now()->toTimeString(),
                'activity_type' => 'health_check_updated'
            ]);

            \DB::commit();

            if ($updatedContractor) {
                return response()->json([
                    'data' => $contractor,
                    'message' => 'Your Meeting Confirmed Successfully',
                    'code' => 200
                ]);
            }

            return response()->json(['data' => [], 'message' => __('dashboard.errorMessage'), 'code' => 500], 500);

        } catch (\Exception $e) {
            \DB::rollBack();

            $contractor_request->histroy()->create([
                'date' => Carbon::now()->toDateString(),
                'time' => Carbon::now()->toTimeString(),
                'activity_type' => 'issue'
            ]);

            \Log::debug('data', [$e->getMessage()]);

            return unKnownError($e->getMessage());
        }
    }

    public function cancel($id)
    {
        $visitorRequest = VisitorRequest::where('id', $id)->first();

        if(empty($visitorRequest)){
            abort(404);
        }

        $visitorRequest->status = 'cancel';
        $visitorRequest->save();

        return redirect(route('dashboard.visitor-get-request', [
            'visit_id' => $visitorRequest->visit_request_id,
            'visitor_id' => $visitorRequest->visitor_id,
            'secret_code' => $visitorRequest->secret_code,
        ]))->with([
            'status' => 'success',
            'message' => 'Your Visitor Cacnel successfully'
        ]);

    }

    public function contractRequestcancel($id)
    {
        $contractorRequest = ContractorRequest::where('id', $id)->first();

        if(empty($contractorRequest)){
            abort(404);
        }

        $contractorRequest->status = 'cancel';
        $contractorRequest->save();

        return redirect(route('dashboard.contractor-get-request', [
            'contract_request_id' => $contractorRequest->contract_request_id,
            'contractor_id' => $contractorRequest->contractor_id,
            'secret_code' => $contractorRequest->secret_code,
        ]))->with([
            'status' => 'success',
            'message' => 'Your Contractor Cancel successfully'
        ]);

    }

}
