<?php

namespace Database\Seeders;

use App\Models\Site;
use App\Models\User;
use App\Models\Gate;
use App\Services\RolesService;
use App\Services\SettingService;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        RolesService::CreateRole(['employee', 'guard', 'supervisor', 'contract_manager']);


        $sites = Site::pluck('id')->toArray();
        $gates = Gate::pluck('id')->toArray();

        $root = User::firstOrCreate([
            'email' => 'root@hawdaj.net'
        ], [
            'first_name' => 'Root',
            'password' => bcrypt(123456)
        ]);

        $admin = User::firstOrCreate([
            'email' => 'admin@hawdaj.net'
        ], [
            'first_name' => 'Admin',
            'password' => bcrypt(123456)
        ]);

        $employee = User::firstOrCreate([
            'email' => 'hassan@hawdaj.net'
        ], [
            'first_name' => 'Hassan',
            'password' => bcrypt(123456)
        ]);

        $guard = User::firstOrCreate([
            'email' => 'guard@hawdaj.net'
        ], [
            'first_name' => 'Guard',
            'password' => bcrypt(123456)
        ]);

        $supervisor = User::firstOrCreate([
            'email' => 'supervisor@hawdaj.net'
        ], [
            'first_name' => 'Supervisor',
            'company_id' => null,
            'password' => bcrypt(123456)
        ]);

        $contractManager = User::firstOrCreate([
            'first_name' => 'Contract',
            'email' => 'contract_manager@hawdaj.net'
        ], [
            'last_name' => 'Manager',
            'password' => bcrypt(123456)
        ]);

        $root->assignRole('root');
        $admin->assignRole('admin');
        $employee->assignRole('employee');
        $guard->assignRole('guard');
        $supervisor->assignRole('supervisor');
        $contractManager->assignRole('contract_manager');

        $root->sites()->attach($sites);
        $admin->sites()->attach($sites);
        $employee->sites()->attach($sites);
        $supervisor->sites()->attach($sites);
        $contractManager->sites()->attach($sites);
        $guard->sites()->attach($sites);

        $guard->gates()->attach($gates);

        SettingService::addSetting(null, $admin->id);
    }
}
