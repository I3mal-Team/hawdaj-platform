<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Users\UserRequest;
use App\Models\Company;
use App\Models\Department;
use App\Models\Gate;
use App\Models\Role;
use App\Models\Site;
use App\Models\User;
use App\Notifications\dashboard\userMailNotification;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendors = User::with('roles')
            ->where('id', '<>', auth()->id())
            ->where('id', '<>', 1)
            ->where('is_vendor','=',1)
            ->get();

        return view('dashboard.vendors.index', [
            'title' => trans('dashboard.vendors'),
            'vendors' => $vendors
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.vendors.create', [
            'roles' => Role::all(),
            'title' => __('dashboard.create_user')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $data = $request->except(['_token', 'password']);

            $data['password'] = Hash::make($request->password);
            $data['is_vendor'] = 1; // type vendor in user

            \DB::beginTransaction();

            if ($request->hasFile('photo')) {
                $data['photo'] = UploadService::store($request->photo, 'vendors');
            }
            $user = User::create($data);
            $user->assignRole($request->role);
            $data['password'] = $request->password;
            try {
                $user->notify(new userMailNotification($user, $data));
            } catch (\Exception $e) {
                //
            }
            \DB::commit();
            return redirect(route('dashboard.vendors.index'))->with([
                'message' => trans('dashboard.user_added_successfully')
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $vendor)
    {
        if ($vendor->id != 1 && auth()->id() != $vendor->id) {
            return view('dashboard.vendors.edit', [
                'title' => __('dashboard.edit_user'),
                'roles' => Role::all(),
                'vendor' => $vendor
            ]);
        }
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function update(User $user, UserRequest $request)
    {
        try {
            if ($user->id != 1) {
                $data = $request->except('_token', '_method', 'password');

                if (isset($data['password']) && $data['password'] != null) {
                    $data['password'] = Hash::make($request->password);
                }

                \DB::beginTransaction();

                if ($request->hasFile('photo')) {
                    UploadService::delete($user->photo);
                    $data['photo'] = UploadService::store($request->photo, 'vendors');
                }

                $user->update($data);
                $user->syncRoles($request->role);
                \DB::commit();
                return redirect(route('dashboard.vendors.index'))->with([
                    'message' => trans('dashboard.user_added_successfully')
                ]);
            }

            abort(404);

        } catch (\Exception $e) {
            \DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->id != 1) {

            UploadService::delete($user->photo);
            $user->roles()->detach();
            $user->delete();

            return response()->json([
                'message' => trans('dashboard.user_delete_successfully')
            ]);
        }

        abort(404);
    }
}
