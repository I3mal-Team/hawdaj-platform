<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Http\Controllers\Api\ApiModalController;

class ResetPasswordController extends ApiModalController
{
    use ResetsPasswords;

    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'emailAddress' => 'required',
            'code' => 'required',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return $this->error(422 , $validator->errors()->first());
        }

        $user = User::where('email', $request->emailAddress)->first();

        if (!$user) {
            return $this->error(404, __('dashboard.user_not_found'));
        }

        if ($user->forget_password_code != $request->code) {
            return $this->error(422, __('dashboard.invalid_code'));
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Revoke all tokens...
        $user->tokens()->delete();

        return $this->success(null, 200, __('dashboard.password_updated_successfully'));
    }
}
