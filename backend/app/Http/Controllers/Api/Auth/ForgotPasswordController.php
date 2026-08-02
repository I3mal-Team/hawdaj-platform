<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\ApiModalController;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends ApiModalController
{
    public function forgot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'emailAddress' => 'required|email',
        ]);

        if ($validator->fails()) {
            return $this->error(422 , $validator->errors()->first());
        }

        $user = User::where('email', $request->emailAddress)->first();

        if (!$user) {
            return response()->json(['message' => 'user not found']);
        }

        $data = [];
        $data['userName'] = $user->first_name;
        $data['code'] = env('APP_ENV') === 'production' ? rand(1111,9999) : '1234';
        $data['email'] = $request->emailAddress;
        $data['title'] = 'Password reset';
        $data['body'] = 'Please click on below link to reset your password.';

        try {
            Mail::send('forget_password', ['data' => $data], function ($message) use ($data) {
                $message->to($data['email'])->subject($data['title']);
            });

            $user->forget_password_code = $data['code'];
            $user->save();

            return $this->success(null, 200, __('dashboard.password_reset_email_sent'));
        } catch (\Swift_TransportException $e) {
            Log::error('Mail sending failed: ' . $e->getMessage());
            
            $user->forget_password_code = $data['code'];
            $user->save();
            
            return $this->error(500, __('dashboard.unable_to_send_email'));
        } catch (\Exception $e) {
            Log::error('Mail sending error: ' . $e->getMessage());
            
            $user->forget_password_code = $data['code'];
            $user->save();
            
            return $this->error(500, __('dashboard.email_sending_error'));
        }
    }

    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'emailAddress' => 'required|email',
            'code' => 'required'
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

        return $this->success(null, 200, __('dashboard.code_verified_successfully'));
    }
}
