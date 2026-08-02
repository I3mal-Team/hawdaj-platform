<?php

namespace App\Http\Controllers\Dashboard\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Users\PermissionRequest;
use App\Models\Permission;
use App\Models\Role;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PermissionsController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:read-permission', ['only' => ['index']]);
        $this->middleware('permission:create-permission', ['only' => ['create', 'store']]);
        $this->middleware('permission:update-permission', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-permission', ['only' => ['destroy']]);
    }

    public function index()
    {
        $perPage = request('per_page') == -1 ? Permission::count() : request('per_page' , 10);

        $all = Permission::paginate($perPage);


        return view('dashboard.users.permissions.index', [
            'title' => trans('dashboard.permissions'),
            'permissions' => $all,
        ]);
    }

    public function create()
    {
        return view('dashboard.users.permissions.create', [
            'title' => __('dashboard.create_permission'),
            'operations' => ['read', 'create', 'update', 'delete'],
        ]);
    }

    public function store(PermissionRequest $request)
    {
        try {
            $data = $request->except('_token');

            DB::beginTransaction();

            $model = Str::of($request->model)->snake()->plural();

            $permissions = collect();
            foreach ($request->operations as $operation) {
                $permissions->push(Permission::create([
                    'name' => $operation . '-' . $model,
                    'label' => ucfirst($operation) . ' ' . ucfirst($request->model),
                    'model' => ucfirst($request->model),
                ]));
            }

            Role::first()->givePermissionTo($permissions);

            DB::commit();

            return redirect('permissions')->with([
                'message' => trans('dashboard.permission_added_successfully'),
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }

    public function edit($group)
    {
        $permission = Permission::where('model', $group)->get()->groupBy('model');

        return view('dashboard.users.permissions.edit', [
            'title' => __('dashboard.edit_permission'),
            'model' => array_key_first($permission->toArray()),
            'operations' => $permission->first(),
        ]);
    }

    public function update($model, PermissionRequest $request)
    {
        try {

            DB::beginTransaction();

            $permission = Permission::where('model', $model)->select('name')->get();

            $old_operations = array_map(function ($item) {
                return explode('-', $item)[0];
            }, Arr::flatten($permission->toArray()));

            $new_operations = $request->operations;

            $diffCreate = array_values(array_diff($new_operations, $old_operations));
            $diffDelete = array_values(array_diff($old_operations, $new_operations));

            if (!empty($diffCreate)) {
                $permissions = collect();
                foreach ($diffCreate as $operation) {
                    $permissions->push(Permission::firstOrCreate([
                        'name' => strtolower($operation) . '-' . Str::of($model)->snake()->plural(),
                        'label' => ucfirst($operation) . ' ' . ucfirst($model),
                        'model' => ucfirst($model),
                    ]));
                }
                Role::find(1)->givePermissionTo($permissions);
            }

            if (!empty($diffDelete)) {
                $permissions = collect();
                foreach ($diffDelete as $operation) {
                    $permissions->push(Permission::where('label', ucfirst($operation) . ' ' . ucfirst($model))->first());
                }

                Role::find(1)->revokePermissionTo($permissions);

                $permissions->each(function ($item) {$item->delete();});
            }

            DB::commit();

            return redirect('permissions')->with([
                'message' => trans('dashboard.permission_added_successfully'),
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }

    public function destroy($id): JsonResponse
    {
        $permissions = Permission::find($id);

        $permissions->delete();
        return response()->json([
            'message' => trans('dashboard.permission_delete_successfully'),
        ]);
    }

}
