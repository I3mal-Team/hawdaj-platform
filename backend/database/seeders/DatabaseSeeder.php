<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Report\Database\Seeders\ReportDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // CategorySeeder::class,
            // RegionSeeder::class,
            // CitySeeder::class,
            SitesSeeder::class,
            RoleSeeder::class,
            SettingSeeder::class,
            // ReportDatabaseSeeder::class,
            // MailTemplatesSeeder::class
            UpdateMostVisitsTableSeeder::class,
            SliderSeeder::class,
        ]);

  }
}
