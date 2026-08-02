<?php

namespace App\Http\Controllers\Api;

use App\Models\Story;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\UploadService;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Stories\StoryCollection;
use App\Http\Resources\LandMarks\LandMarkResource;

class StoryController extends ApiModalController
{

    public function index(Request $request)
    {
        if (! auth('api')->check()) {
            return $this->error(401, trans('dashboard.please_sign_up_first'));
        }

        $stories = applyListingOrder(Story::query());

        $stories = $stories->paginate(request('per_page' , 10))->onEachSide(2);

        $stories = new StoryCollection($stories);

        return $this->success($stories,200, __('dashboard.list_of_stories'));
    }

    public function myStories(Request $request)
    {
        if (! auth('api')->check()) {
            return $this->error(401, trans('dashboard.please_sign_up_first'));
        }

        $stories = applyListingOrder(Story::where('user_id', auth('api')->user()->id));

        $stories = $stories->paginate(request('per_page' , 10))->onEachSide(2);

        $stories = new StoryCollection($stories);

        return $this->success($stories,200, __('dashboard.my_stories_list'));
    }

    public function myLastDayStories(Request $request)
    {
        if (! auth('api')->check()) {
            return $this->error(401, trans('dashboard.please_sign_up_first'));
        }

        $stories = applyListingOrder(
            Story::where('user_id', auth('api')->user()->id)->where('created_at', '>=', Carbon::now()->subDay())
        );

        $stories = $stories->paginate(request('per_page' , 10))->onEachSide(2);

        $stories = new StoryCollection($stories);

        return $this->success($stories,200, __('dashboard.my_last_day_stories_list'));
    }


    public function show($id)
    {
        if (! auth('api')->check()) {
            return $this->error(401, trans('dashboard.please_sign_up_first'));
        }

        $story = Story::findOrFail($id);

        return $this->success(new LandMarkResource($story),200, __('dashboard.story_details'));
    }

    public function store(Request $request)
    {
        $data = $request->all();

        if (! auth('api')->check()) {
            return $this->error(401, trans('dashboard.please_sign_up_first'));
        }

        $validator = Validator::make($data, [
            'type' => ['nullable', 'string', 'in:file,text'],
            'file' => ['required_if:type,file', 'mimes:jpeg,png,jpg,gif,svg,webm,', 'max:2048'],
            'text' => ['required_if:type,text', 'string'],
        ], [], [
            'type' => trans('dashboard.type'),
            'file' => trans('dashboard.file'),
            'text' => trans('dashboard.text'),
        ]);

        if ($validator->fails()) {
            return $this->error(422 , $validator->errors()->first());
        }

        if (auth('api')->check()) {
            $data['user_id'] = auth('api')->user()->id;
        }

        if (request()->hasFile('file')) {
            $data['file'] = UploadService::store($request->file, 'stories');
        }

        $data['status'] = 'active';

        Story::create($data);

        return $this->ok(__('dashboard.story_added_successfully'));
    }

    public function delete($id)
    {
        if (! auth('api')->check()) {
            return $this->error(401, trans('dashboard.please_sign_up_first'));
        }

        $story = Story::findOrFail($id);

        if ($story->user_id != auth('api')->user()->id) {
            return $this->error(422, trans('dashboard.not_allowed_to_delete_this_story'));
        }

        $story->delete();

        return $this->ok(__('dashboard.story_deleted_successfully'));
    }
}
