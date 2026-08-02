<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Place;
use App\Models\Store;
use App\Models\Swalef;
use App\Models\Event;
use App\Models\Rate;

class RateController extends ApiModalController
{
    public function store(Request $request)
    {

        $data = $request->all();

        $validator = Validator::make($data, [
            'email' => [Rule::requiredIf(\Auth::guest()), 'email'],
            'name' => [Rule::requiredIf(\Auth::guest()), 'string', 'max:255'],
            'rateText' => 'required|min:10',
            'rate' => 'required',
            'parent_id' => 'required',
            'type' => 'required|in:place,store,zad,swalef,event,app,guide'
        ]);

        if ($validator->fails()) {
            return $this->error(422 , $validator->errors()->first());
        }

        if (auth('api')->check()) {
            $data['user_id'] = auth('api')->user()->id;
            $data['name'] = auth('api')->user()->first_name . ' ' . auth('api')->user()->last_name;
            $data['email'] = auth('api')->user()->email;
        }

        Rate::create($data);
        return $this->ok(__('dashboard.rating_added_successfully'));
    }

    public function delete($id)
    {
        Rate::whereId($id)->where("user_id", \Auth::guard('api')->id())->delete();
        return $this->ok(__('dashboard.rating_removed_successfully'));
    }

}
