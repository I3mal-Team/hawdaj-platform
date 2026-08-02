<?php

namespace App\Http\Controllers\Dashboard\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Users\ChangePasswordRequest;
use App\Http\Requests\Dashboard\Users\ProfileRequest;
use App\Services\UploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        return view('dashboard.users.profile.mainInfo', ['title' => __('dashboard.profile')]);
    }

    public function update(ProfileRequest $request): RedirectResponse
    {
        $data = $request->except('_token');
        $user = auth()->user();

        if ($request->hasFile('photo')) {
            UploadService::delete($user->photo);
            $data['photo'] = UploadService::store($request->photo, 'users');
        }

        auth()->user()->update($data);

        return redirect()->back()->with([
            'status' => 'success',
            'message' => __('dashboard.profile_update_successfully'),
        ]);
    }

    public function changePassword(): View
    {
        return view('dashboard.users.profile.changePassword', ['title' => __('dashboard.change_password')]);
    }

    public function updatePassword(ChangePasswordRequest $request): RedirectResponse
    {
        if (!Hash::check(auth()->user()->password, $request->password)) {
            $status = 'error';
            $message = __('dashboard.incorrect_password');

        } else {
            $status = 'success';
            $message = __('dashboard.password_saved_successfully');

            Auth::user()->update(['password' => bcrypt($request->new_password)]);
        }

        return redirect()->back()->with(['status' => $status, 'message' => $message]);
    }
}