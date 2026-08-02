<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Favorites\FavoriteCollection;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavoriteController extends ApiModalController
{
    public function store(Request $request)
    {
        $data = $request->all();

        if (! auth('api')->check()) {
            return $this->error(401, trans('dashboard.please_sign_up_first'));
        }
        $validator = Validator::make($data, [
            'favoritable_type' => ['required', 'string', 'in:place,store,swalef,event,zad'],
            'favoritable_id' => ['required', 'numeric']
        ], [], [
            'favoritable_type' => trans('dashboard.favoritable_type'),
            'favoritable_id' => trans('dashboard.favoritable_id')
        ]);

        if ($validator->fails()) {
            return $this->error(422 , $validator->errors()->first());
        }

        if (auth('api')->check()) {
            $data['user_id'] = auth('api')->user()->id;
        }

        $data['favoritable_type'] = ucwords($request->favoritable_type);

        if ($request->favoritable_type == 'zad') {
            $data['favoritable_type'] = 'Zad_elgadel';
        }

        $added_before = Favorite::where('user_id', $data['user_id'])->where('favoritable_type', $data['favoritable_type'])
            ->where('favoritable_id', $request->favoritable_id)->first();

        if ($added_before) {
            $added_before->delete();
            return $this->ok(__('dashboard.item_removed_successfully'));
        }

        Favorite::create($data);
        return $this->ok(__('dashboard.item_saved_successfully'));
    }

    public function listMyFavorites(Request $request)
    {
        $favorites = Favorite::where('user_id', auth('api')->user()->id);

        if (request('favoritable_type')) {
            $favorites->where('favoritable_type', ucwords(request('favoritable_type')));
        }

        $favorites = $favorites->paginate(request('per_page' , 10))->onEachSide(2);

        $favorites = new FavoriteCollection($favorites);

        return $this->success($favorites,200, __('dashboard.list_of_favorites'));
    }
}
