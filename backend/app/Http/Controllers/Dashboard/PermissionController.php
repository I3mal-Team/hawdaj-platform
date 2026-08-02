<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CarRequest;
use App\Models\ContractRequest;
use App\Models\MaterialRequest;
use App\Models\VisitRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $title = trans('dashboard.requests');
        $type = \request('type');

        $visitPermissions = [];
        $materialPermissions = [];
        $carsPermissions = [];
        $contractsPermissions  = [];

        if ($type == null || $type == 'visitors') {
            $visitPermissions = VisitRequest::with(['host', 'department', 'requester', 'visitors'])
                ->latest()->site()->get();

        } elseif ($type == 'materials') {
            $materialPermissions = MaterialRequest::with([
                'host', 'department', 'requester', 'requests', 'site', 'sender_site', 'sender_department', 'sender_host'
            ])->latest()->get();

        } elseif ($type == 'contracts') {

            $contractsPermissions = ContractRequest::with('site', 'company', 'contract', 'contractorRequests')
                ->latest()->get();

        } elseif ($type == 'cars') {

            $carsPermissions = CarRequest::with(['host', 'department', 'requester', 'site'])
                ->latest()
                ->get();
        }

        return view('dashboard.permissions.index', compact(
            'title', 'visitPermissions', 'materialPermissions', 'contractsPermissions', 'carsPermissions'
        ));
    }
}
