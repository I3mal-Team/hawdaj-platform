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

class SubscribeController extends ApiModalController
{
    public function subscribe(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return $this->error(422 , $validator->errors()->first());
        }

        $newsletter = app()->make(Newsletter::class);
        $newsletter->subscribe($data['email'], ['TYPE' => 'members']);
        return $this->success([],200, __('dashboard.subscription_successful'));
    }

}
