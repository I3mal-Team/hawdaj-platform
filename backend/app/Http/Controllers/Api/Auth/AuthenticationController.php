<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\ApiModalController;
use App\Http\Resources\Users\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends ApiModalController
{
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return $this->error(422 , $validator->errors()->first());
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return $this->userData($user);
    }

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->error(422 , $validator->errors()->first());
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->error(422,trans('dashboard.invalid_credentials'));
        }

        return $this->userData($user);
    }

    public function userData($user) {
        $authToken = $user->createToken('auth-token')->plainTextToken;
        return $this->success(["user" => new UserResource($user), 'token' => $authToken] ,200, __('dashboard.login_successfully'));
    }

    public function logout()
    {
        if(auth()->user()){
            auth()->user()->currentAccessToken()->delete();
        }

        return $this->success([] ,200, __('dashboard.logout_successfully'));
    }
}
