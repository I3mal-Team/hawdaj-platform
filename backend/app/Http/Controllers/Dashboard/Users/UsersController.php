<?php

namespace App\Http\Controllers\Dashboard\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Users\UserRequest;
use App\Models\Region;
use App\Models\Role;
use App\Models\User;
use App\Notifications\dashboard\userMailNotification;
use App\Services\UploadService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:read-user', ['only' => ['index']]);
        $this->middleware('permission:create-user', ['only' => ['create', 'store']]);
        $this->middleware('permission:update-user', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-user', ['only' => ['destroy']]);
    }

    /**
     * @return View
     */
    public function index(): View
    {

        $users = User::with('roles')
            ->where('id', '<>', auth()->id())
            ->where('id', '<>', 1);

        if (request('email')){
            $users->where('email', 'like', '%' . request('email') . '%');
        }

        if (request('role_id')){
            $users->whereHas('roles', function($query) {
                $query->where('roles.id', request('role_id'));
            });
        }

        $perPage = request('per_page') == -1 ? $users->count() : request('per_page' , 10);

        $users = $users->paginate($perPage);

        return view('dashboard.users.index', [
            'title' => trans('dashboard.users'),
            'users' => $users,
            'roles' => Role::all(),
        ]);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('dashboard.users.create', [
            'roles' => Role::all(),
            'regions' => Region::all(),
            'title' => __('dashboard.create_user'),
        ]);
    }

    /**
     * @param UserRequest $request
     * @return JsonResponse|RedirectResponse|Redirector
     */
    public function store(UserRequest $request)
    {
        try {
            $data = $request->except(['_token', 'password']);

            $data['password'] = Hash::make($request->password);

            DB::beginTransaction();

            if ($request->hasFile('photo')) {
                $data['photo'] = UploadService::store($request->photo, 'users');
            }

            $user = User::create($data);
            $user->assignRole($request->role);
            $data['password'] = $request->password;

            try {
                $user->notify(new userMailNotification($user, $data));
            } catch (\Exception $e) {
            }

            DB::commit();

            return redirect('users')->with([
                'message' => trans('dashboard.user_added_successfully'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }

    /**
     * @param User $user
     * @return Factory|\Illuminate\Contracts\View\View|void
     */
    public function edit(User $user)
    {
//        dd($user->roles()->first());
        if ($user->id != 1 && auth()->id() != $user->id) {
            return view('dashboard.users.edit', [
                'title' => __('dashboard.edit_user'),
                'roles' => Role::all(),
                'regions' => Region::all(),
                'user' => $user,
                'userRoleId' => $user->roles()->first() ? $user->roles()->first()->id : null,
            ]);
        }
        abort(404);
    }

    /**
     * @param User $user
     * @param UserRequest $request
     * @return JsonResponse|RedirectResponse|Redirector|void
     */
    public function update(User $user, UserRequest $request)
    {
        try {
            if ($user->id != 1) {
                $data = $request->except('_token', '_method', 'password');

                if (isset($data['password']) && $data['password'] != null) {
                    $data['password'] = Hash::make($request->password);
                }

                DB::beginTransaction();

                if ($request->hasFile('photo')) {
                    UploadService::delete($user->photo);
                    $data['photo'] = UploadService::store($request->photo, 'users');
                }

                $user->update($data);
                $user->syncRoles($request->role);

                DB::commit();

                return redirect('users')->with([
                    'message' => trans('dashboard.user_added_successfully'),
                ]);
            }

            abort(404);

        } catch (\Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }

    /**
     * @param User $user
     * @return JsonResponse|void
     */
    public function destroy(User $user)
    {
        if ($user->id != 1) {

            UploadService::delete($user->photo);
            $user->roles()->detach();
            $user->delete();

            return response()->json([
                'message' => trans('dashboard.user_delete_successfully'),
            ]);
        }

        abort(404);
    }
}
