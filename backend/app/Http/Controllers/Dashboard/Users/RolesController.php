<?php

namespace App\Http\Controllers\Dashboard\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Users\RoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:read-role', ['only' => ['index']]);
        $this->middleware('permission:create-role', ['only' => ['create', 'store']]);
        $this->middleware('permission:update-role', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-role', ['only' => ['destroy']]);
    }

    /**
     * @return View
     */
    public function index()
    {
        $roleCount = Role::whereNotIn('id', auth()->user()->roles()->pluck('id'))->count();

        $perPage = request('per_page') == -1 ? $roleCount : request('per_page' , 10);

        $roles = Role::whereNotIn('id', auth()->user()->roles()->pluck('id'))->paginate($perPage);

        return view('dashboard.users.roles.index', [
            'title' => trans('dashboard.roles'),
            'roles' => $roles,
        ]);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('dashboard.users.roles.create', [
            'permissions' => Permission::all()->groupBy('model'),
            'title' => __('dashboard.create_role'),
        ]);
    }

    /**
     * @param RoleRequest $request
     * @return JsonResponse|RedirectResponse|Redirector
     */
    public function store(RoleRequest $request)
    {
        try {
            $data = $request->except('_token', 'permissions');

            DB::beginTransaction();

            $role = Role::create($data);
            $role->givePermissionTo($request->permissions);

            DB::commit();

            return redirect('roles')->with([
                'message' => trans('dashboard.role_added_successfully'),
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }

    /**
     * @param Role $role
     * @return Factory|View
     */
    public function edit(Role $role)
    {
        return view('dashboard.users.roles.edit', [
            'permissions' => Permission::all()->groupBy('model'),
            'title' => __('dashboard.edit_role'),
            'role' => $role,
        ]);
    }

    /**
     * @param Role $role
     * @param RoleRequest $request
     * @return JsonResponse|RedirectResponse|Redirector
     */
    public function update(Role $role, RoleRequest $request)
    {
        try {
            $data = $request->except('_token', '_method', 'permissions');

            DB::beginTransaction();

            $role->update($data);
            $role->syncPermissions($request->permissions);

            DB::commit();

            return redirect('roles')->with([
                'message' => trans('dashboard.role_updated_successfully'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }

    /**
     * @param Role $role
     * @return JsonResponse
     */
    public function destroy(Role $role)
    {
        try {
            DB::beginTransaction();

            $role->permissions()->detach();
            $role->delete();

            DB::commit();

            return response()->json([
                'message' => trans('dashboard.role_delete_successfully'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }
}
