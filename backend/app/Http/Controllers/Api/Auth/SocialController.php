<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Users\UserResource;
use App\Http\Controllers\Api\ApiModalController;

class SocialController extends ApiModalController
{
    public function callback(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'provider_id' => 'required',
            'provider_type' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->error(422 , $validator->errors()->first());
        }

        $name = explode(" ", $request->name);
        $first_name = $name[0];
        $last_name = $name[1] ?? "";
        $user = User::where('email' , $request->email)->withTrashed()->first();

        if(!$user){
            User::create([
                'email' => $request->email,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'provider_id' => $request->provider_id,
                'provider_type' => $request->provider_type,
            ]);
        }elseif($user->deleted_at){
            $user->deleted_at = null;
            $user->save();
        }

        Auth::login($user);

        $authToken = $user->createToken('auth-token')->plainTextToken;
        return $this->success(["user" => new UserResource($user), 'token' => $authToken] ,200, __('dashboard.login_successfully'));
    }

}
