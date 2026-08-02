<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Saves\SaveCollection;
use App\Models\Save;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SavedController extends ApiModalController
{
    public function store(Request $request)
    {
        $data = $request->all();

        if (! auth('api')->check()) {
            return $this->error(401, trans('dashboard.please_sign_up_first'));
        }
        $validator = Validator::make($data, [
            'saved_type' => ['required', 'string', 'in:place,store,swalef,event,zad'],
            'saved_id' => ['required', 'numeric']
        ], [], [
            'saved_type' => trans('dashboard.saved_type'),
            'saved_id' => trans('dashboard.saved_id')
        ]);

        if ($validator->fails()) {
            return $this->error(422 , $validator->errors()->first());
        }

        if (auth('api')->check()) {
            $data['user_id'] = auth('api')->user()->id;
        }

        $data['saved_type'] = ucwords($request->saved_type);

        if ($request->saved_type == 'zad') {
            $data['saved_type'] = 'Zad_elgadel';
        }

        $added_before = Save::where('user_id', $data['user_id'])->where('saved_type', $data['saved_type'])
            ->where('saved_id', $request->saved_id)->first();

        if ($added_before) {
            $added_before->delete();
            return $this->ok(__('dashboard.item_removed_successfully'));
        }

        Save::create($data);
        return $this->ok(__('dashboard.item_saved_successfully'));
    }

    public function listMySaved(Request $request)
    {
        $saved = Save::where('user_id', auth('api')->user()->id);

        $saved = $saved->paginate(request('per_page' , 10))->onEachSide(2);

        $saved = new SaveCollection($saved);

        return $this->success($saved,200, __('dashboard.list_of_saved'));
    }
}
