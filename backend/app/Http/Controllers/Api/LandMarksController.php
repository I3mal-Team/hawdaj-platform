<?php

namespace App\Http\Controllers\Api;

use App\Models\LandMark;
use Illuminate\Http\Request;
use App\Services\UploadService;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\LandMarks\LandMarkResource;
use App\Http\Resources\LandMarks\LandMarkCollection;

class LandMarksController extends ApiModalController
{
    public function index(Request $request)
    {
        if (! auth('api')->check()) {
            return $this->error(401, trans('dashboard.please_sign_up_first'));
        }

        $landMarks = LandMark::query();

        $landMarks = $landMarks->paginate(request('per_page' , 10))->onEachSide(2);

        $landMarks = new LandMarkCollection($landMarks);

        return $this->success($landMarks,200, __('dashboard.list_of_landmarks'));
    }

    public function myLandMarks(Request $request)
    {
        if (! auth('api')->check()) {
            return $this->error(401, trans('dashboard.please_sign_up_first'));
        }

        $landMarks = LandMark::where('user_id', auth('api')->user()->id);

        $landMarks = $landMarks->paginate(request('per_page' , 10))->onEachSide(2);

        $landMarks = new LandMarkCollection($landMarks);

        return $this->success($landMarks,200, __('dashboard.my_landmarks_list'));
    }


    public function show($id)  {

        $landMark = LandMark::findOrFail($id);

        return $this->success(new LandMarkResource($landMark),200, __('dashboard.landmark_details'));
    }

    public function store(Request $request)
    {
        $data = $request->all();

        if (! auth('api')->check()) {
            return $this->error(401, trans('dashboard.please_sign_up_first'));
        }

        $validator = Validator::make($data, [
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'address' => ['nullable', 'string'],
            'type' => ['required', 'string', 'in:place,store,swalef,event,zad'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ], [], [
            'title' => trans('dashboard.title'),
            'description' => trans('dashboard.description'),
            'address' => trans('dashboard.address'),
            'type' => trans('dashboard.type'),
            'image' => trans('dashboard.image'),
        ]);

        if ($validator->fails()) {
            return $this->error(422 , $validator->errors()->first());
        }

        if (auth('api')->check()) {
            $data['user_id'] = auth('api')->user()->id;
        }

        $data['status'] = 'pending';
        $data['active'] = '0';

        $landMark = LandMark::create($data);

        if (request()->hasFile('image')) {
            $landMark->clearMediaCollection('image');
            $landMark->addMediaFromRequest('image')->toMediaCollection('image');
        }

        return $this->ok(__('dashboard.landmark_added_successfully'));
    }

}
