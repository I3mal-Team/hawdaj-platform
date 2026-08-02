<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        City::firstOrCreate([
            'name'      => 'عفيف',
            'region_id' => 1,
        ]);

        City::firstOrCreate([
            'name'      => 'الرين',
            'region_id' => 1,
        ]);

        City::firstOrCreate([
            'name'      => 'الكامل',
            'region_id' => 3,
        ]);
    }
}
