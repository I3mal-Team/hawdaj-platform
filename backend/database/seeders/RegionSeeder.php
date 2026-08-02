<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Region::firstOrCreate([
            'name' => 'الرياض',
        ]);
        Region::firstOrCreate([
            'name' => 'حائل',
        ]);
        Region::firstOrCreate([
            'name' => 'مكة المكرمة',
        ]);
        Region::firstOrCreate([
            'name' => 'المدينة المنورة',
        ]);
        Region::firstOrCreate([
            'name' => 'القصيم',
        ]);
        Region::firstOrCreate([
            'name' => 'الشرقية',
        ]);
        Region::firstOrCreate([
            'name' => 'عسير',
        ]);
        Region::firstOrCreate([
            'name' => 'تبوك',
        ]);
        
    }
}
