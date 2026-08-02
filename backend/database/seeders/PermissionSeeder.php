<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Site;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        try {

        // begin Transaction
        DB::beginTransaction();

        // Truncate the role and permissions tables before seeding data
        DB::table('role_has_permissions')->delete();
        DB::table('roles')->delete();
        DB::table('permissions')->delete();

            $operations = [
                'read',
                'create',
                'update',
                'delete',
                'patch'
            ];

            $modules =  [
                'placesCategory',
                'places',
                'users_places',
                'storesCategory',
                'store',
                'zadElgadelCategory',
                'zadElgadel',
                'zadElgadelFood',
                'zadElgadelOffer',
                'opinion',
                'swalefs',
                'swalef-categories',
                'suggest',
                'event',
                'application-categories',
                'applications',
                'guides',
                'sliders',
                'region',
                'city',
                'price',
                'feature',
                'setting',
                'translationsSetting',
                'mailTemplate',
                'user',
                'role',
                'permission',
                'dashboard',
            ];

            $roles = [
                'root',
                'admin',
                'customer',
                'employee',
                'guard',
                'supervisor',
                'contract_manager',
            ];

            foreach ($roles as $role) {
                Role::create([
                    'name' => $role,
                    'label' => $role,
                    'guard_name' => 'web',
                ]);
            }

            foreach ($modules as $module) {
                foreach ($operations as $operation) {
                    Permission::create([
                        'name' => $operation . '-' . $module,
                        'label' => $operation . ' ' . $module,
                        'model' => $module,
                        'guard_name' => 'web',
                    ]);
                }
            }

            $permissions = Permission::all();
            $rootRoleId = Role::where('name', 'root')->firstOrFail()->id;
            foreach ($permissions as $permission) {
                DB::table('role_has_permissions')->insert([
                    'permission_id' => $permission->id,
                    'role_id' => $rootRoleId,
                ]);
            }

            $root =  User::first();

            $root->assignRole('root');

            DB::commit();

        }catch (\Exception $e){
            DB::rollBack(); // Roll back the transaction if an error occurs
            throw $e; // Optionally, rethrow the exception for debugging or logging
        }
    }
}
