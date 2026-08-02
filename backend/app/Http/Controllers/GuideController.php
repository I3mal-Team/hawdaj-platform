<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\ApiModalController;
use App\Http\Resources\Guides\GuideCollection;
use App\Http\Resources\Guides\GuideResource;
use App\Models\Gallery;
use App\Models\Guide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Profile\UserResource;

class GuideController extends ApiModalController
{
    public function index(Request $request)
    {
        $guides = Guide::where('active', 1);

        if (request('search')) {
            $guides = $guides->where(function ($q) use ($request) {
                $q->whereTranslationLike('name', '%' . $request->search . '%')
                    ->orWhereTranslationLike('nickName', '%' . $request->search . '%');
            });
        }

        if (request('experience')) {
            $guides = $guides->where('experience', request('experience'));
        }

        if (request('region_id')) {
            $regions_id = explode(',' , request('region_id'));
            $guides->where(function($query)use($regions_id){
                foreach ($regions_id as $region_id) {
                    $query->orWhereRaw("JSON_CONTAINS(regions, '" . $region_id . "' )");
                }
            });
        }

        if (request('language_id')) {
            $languages_id = explode(',' , request('language_id'));
            $guides->where(function($query)use($languages_id){
                foreach ($languages_id as $language_id) {
                    $query->orWhereRaw("JSON_CONTAINS(languages, '" . $language_id . "' )");
                }
            });
        }

        if (\request('top_rated')) {
            $guides->withCount(['ratings as average_rate' => function ($query) {
                $query->select(DB::raw('COALESCE(AVG(rate), 0)'));
            }])->orderByDesc('average_rate')
                ->orderBy('guides.order_id', 'asc')
                ->orderBy('guides.id', 'asc');
        } else {
            applyListingOrder($guides);
        }

        if (\request('excludedId')) {
            $guides->where('id', '!=', request('excludedId'));
        }

        $guides = $guides->paginate(request('per_page' , 10))->onEachSide(2);

        $guides = new GuideCollection($guides);

        return $this->success($guides,200,'List of guides');
    }

    public function show($id)  {
        $guide = Guide::find($id);
        return $this->success(new GuideResource($guide),200,"Show Guide");
    }

    public function storeGuide(Request $request)
    {
        $is_login = auth('api')->check();
        $user = auth('api')->user();

        if (! $is_login) {
            return $this->error(401, trans('dashboard.please_sign_up_first'));
        }

        $userGuide = Guide::where('user_id', $user->id)->first();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'nickName' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable',
            'experience' => 'nullable|numeric',
            'regions' => 'nullable|array',
            'regions.*' => 'nullable|exists:regions,id',
            'languages' => 'nullable|array',
            // 'facebook' => ['nullable', 'string', 'min:3', 'url'],
            'x' => ['nullable', 'string', 'min:3', 'url'],
            'twitter' => ['nullable', 'string', 'min:3', 'url'],
            'instagram' => ['nullable', 'string', 'min:3', 'url'],
            'linkedin' => ['nullable', 'string', 'min:3', 'url'],
            'personal_account' => ['nullable', 'string', 'min:3', 'url'],
        ], [], [
            'name' => trans('dashboard.name'),
            'nickName' => trans('dashboard.nickName'),
            'description' => trans('dashboard.description'),
            'image' => trans('dashboard.image'),
            'experience' => trans('dashboard.experience'),
            'regions' => trans('dashboard.regions'),
            'languages' => trans('dashboard.languages'),
            // 'facebook' => trans('dashboard.facebook'),
            'x' => trans('dashboard.x'),
            'twitter' => trans('dashboard.twitter'),
            'instagram' => trans('dashboard.instagram'),
            'linkedin' => trans('dashboard.linkedin'),
            'personal_account' => trans('dashboard.personal_account'),
            'images' => trans('dashboard.images'),
        ]);

        if ($validator->fails()) {
            return $this->error(422 , $validator->errors()->first());
        }

        if ($userGuide) {
            $data = [
                'name' => $request->name,
                'nickName' => $request->nickName,
                'description' => $request->description,
                'experience' => $request->experience,
                'languages' => \request('languages'),
                'regions' => $request->regions,
                // 'facebook' => $request->facebook,
                'x' => $request->x,
                'twitter' => $request->twitter,
                'instagram' => $request->instagram,
                'linkedin' => $request->linkedin,
                'personal_account' => $request->personal_account,
            ];

            $userGuide->update($data);

            if ($request->hasFile('image')) {
                $userGuide->clearMediaCollection('image');
                $userGuide->addMediaFromRequest('image')->toMediaCollection('image');
            } elseif ($request->has('image') && ($request->image === null || $request->image === '')) {
                $userGuide->clearMediaCollection('image');
            }
        } else {
            $userGuide = Guide::create([
                'name' => $request->name,
                'nickName' => $request->nickName,
                'description' => $request->description,
                'experience' => $request->experience,
                'languages' => \request('languages'),
                'regions' => $request->regions,
                // 'facebook' => $request->facebook,
                'x' => $request->x,
                'twitter' => $request->twitter,
                'instagram' => $request->instagram,
                'linkedin' => $request->linkedin,
                'personal_account' => $request->personal_account,
                'user_id' => $user->id,
            ]);

            if ($request->hasFile('image')) {
                $userGuide->addMediaFromRequest('image')->toMediaCollection('image');
            }
        }

        if ($request->gallery) {
            $getAllGalleries = Gallery::where('type', 'guides')->where('parent_id', $userGuide->id)->get();
            foreach ($getAllGalleries as $gallery) {
                if (! in_array($gallery->id, $request->gallery)) {
                    $gallery->delete();
                }
            }
        }

        return $this->success([
            'userGuide' => new GuideResource($userGuide),
        ],200,'Your Guide Saved successfully');
    }

    public function getGuide(Request $request)
    {
        $is_login = auth('api')->check();
        $user = auth('api')->user();

        if (! $is_login) {
            return $this->error(401, trans('dashboard.please_sign_up_first'));
        }

        $userGuide = Guide::where('user_id', $user->id)->first();

        if (! $userGuide) {
            return $this->success([
                'userGuide' => null,
            ],200,'get user guide data');
        }

        return $this->success([
            'userGuide' => new GuideResource($userGuide),
        ],200,'get user guide data');
    }

    public function updateGuide(Request $request)
    {
        $is_login = auth('api')->check();
        $user = auth('api')->user();

        if (! $is_login) {
            return $this->error(401, trans('dashboard.please_sign_up_first'));
        }

        $userGuide = Guide::where('user_id', $user->id)->first();

        if (! $userGuide) {
            return $this->error(422, trans('dashboard.you_need_to_join_first'));
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'nickName' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable',
            'experience' => 'nullable|numeric',
            'regions' => 'nullable|array',
            'regions.*' => 'nullable|exists:regions,id',
            'languages' => 'nullable|array',
            // 'facebook' => ['nullable', 'string', 'min:3', 'url'],
            'x' => ['nullable', 'string', 'min:3', 'url'],
            'twitter' => ['nullable', 'string', 'min:3', 'url'],
            'instagram' => ['nullable', 'string', 'min:3', 'url'],
            'linkedin' => ['nullable', 'string', 'min:3', 'url'],
            'personal_account' => ['nullable', 'string', 'min:3', 'url'],
        ], [], [
            'name' => trans('dashboard.name'),
            'nickName' => trans('dashboard.nickName'),
            'description' => trans('dashboard.description'),
            'image' => trans('dashboard.image'),
            'experience' => trans('dashboard.experience'),
            'regions' => trans('dashboard.regions'),
            'languages' => trans('dashboard.languages'),
            // 'facebook' => trans('dashboard.facebook'),
            'x' => trans('dashboard.x'),
            'twitter' => trans('dashboard.twitter'),
            'instagram' => trans('dashboard.twitter'),
            'linkedin' => trans('dashboard.linkedin'),
            'personal_account' => trans('dashboard.personal_account'),
        ]);

        if ($validator->fails()) {
            return $this->error(422 , $validator->errors()->first());
        }

        $data = [
            'name' => $request->name,
            'nickName' => $request->nickName,
            'description' => $request->description,
            'experience' => $request->experience,
            'languages' => \request('languages'),
            'regions' => $request->regions,
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'instagram' => $request->instagram,
            'linkedin' => $request->linkedin,
            'personal_account' => $request->personal_account,
            'user_id' => $user->id,
        ];

        $userGuide->update($data);

        if ($request->hasFile('image')) {
            $userGuide->clearMediaCollection('image');
            $userGuide->addMediaFromRequest('image')->toMediaCollection('image');
        } elseif ($request->has('image') && ($request->image === null || $request->image === '')) {
            $userGuide->clearMediaCollection('image');
        }

        return $this->success([
            'userGuide' => new GuideResource($userGuide),
        ],200,'user guide updated successfully');
    }

    public function updateSocial(Request $request)
    {
        $is_login = auth('api')->check();
        $user = auth('api')->user();

        if (! $is_login) {
            return $this->error(401, trans('dashboard.please_sign_up_first'));
        }

        $userGuide = Guide::where('user_id', $user->id)->first();

        if (! $userGuide) {
            return $this->error(422, trans('dashboard.you_need_to_join_first'));
        }

        $validator = Validator::make($request->all(), [
            // 'facebook' => ['nullable', 'string', 'min:3', 'url'],
            'x' => ['nullable', 'string', 'min:3', 'url'],
            'twitter' => ['nullable', 'string', 'min:3', 'url'],
            'instagram' => ['nullable', 'string', 'min:3', 'url'],
            'linkedin' => ['nullable', 'string', 'min:3', 'url'],
            'personal_account' => ['nullable', 'string', 'min:3', 'url'],
        ]);

        if ($validator->fails()) {
            return $this->error(422 , $validator->errors()->first());
        }

        $userGuide->update([
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'instagram' => $request->instagram,
            'linkedin' => $request->linkedin,
            'personal_account' => $request->personal_account,
        ]);

        return $this->success([
            'userGuide' => new GuideResource($userGuide),
        ],200,'get user guide data');
    }

    public function updatePhoto(Request $request)
    {
        $is_login = auth('api')->check();
        $user = auth('api')->user();

        if (! $is_login) {
            return $this->error(401, trans('dashboard.please_sign_up_first'));
        }

        $userGuide = Guide::where('user_id', $user->id)->first();

        if (! $userGuide) {
            return $this->error(422, trans('dashboard.you_need_to_join_first'));
        }

        if ($request->hasFile('image')) {
            $validator = Validator::make($request->all(), [
                'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
            ]);

            if ($validator->fails()) {
                return $this->error(422 , $validator->errors()->first());
            }

            $userGuide->clearMediaCollection('image');
            $userGuide->addMediaFromRequest('image')->toMediaCollection('image');
        } elseif ($request->has('image') && ($request->image === null || $request->image === '')) {
            $userGuide->clearMediaCollection('image');
        }

        $userGuide->refresh();

        return $this->success([
            'userGuide' => new GuideResource($userGuide),
            'user' => new UserResource($user),
        ], 200, 'Photo updated successfully');
    }
}
