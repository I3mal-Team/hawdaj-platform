<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Swalefs\SwalefResource;
use App\Models\Opinion;
use App\Models\Swalef;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactusController extends ApiModalController
{
    public function sendFormData(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[\pL\s\-]+$/u|max:25',
            'email' => 'required|email',
            'phone' => 'required|max:14',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error(422 , $validator->errors()->first());
        }

        Opinion::create($request->all());
        return $this->success([],200, __('dashboard.message_sent_successfully'));
    }
}
