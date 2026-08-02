<?php

namespace Modules\CarModel\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\CarModel\Entities\CarDay;
use Modules\CarModel\Entities\CarPlate;
use Modules\CarModel\Entities\CarSetting;

class CarModelDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CarSetting::factory()->count(1)->create();
        CarPlate::factory()->count(1)->create();
        CarDay::factory()->count(1)->create();
    }
}
