<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Swalefs\SwalefResource;
use App\Models\Opinion;
use App\Models\Swalef;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Newsletter\Newsletter;
use App\Models\Setting;
use App\Http\Resources\Services\ServiceResource;
use App\Models\MostVisit;

class ServiceController extends ApiModalController
{
    public function services(){
        $services = Setting::where('group', 'main_services')->where('type', 'repeater')->get();
    	$services = ServiceResource::collection($services);
        return $this->success($services,200, __('dashboard.list_of_services'));
    }
}
