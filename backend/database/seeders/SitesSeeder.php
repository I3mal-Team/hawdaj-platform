<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Site;

class SitesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Site::firstOrCreate([
            'name' => 'Site Number One',
            'address' => 'Site number one address',
            'location_name' => 'Site number one location name',
            'latitude' => 30.033333,
            'longitude' => 31.233334,
        ]);
        Site::firstOrCreate([
            'name' => 'Site Number Two',
            'address' => 'Site number two address',
            'location_name' => 'Site number two location name',
            'latitude' => 37.0902,
            'longitude' => 95.7129,
        ]);
        Site::firstOrCreate([
            'name' => 'Site Number Three',
            'address' => 'Site number three address',
            'location_name' => 'Site number three location name',
            'latitude' => 61.5240,
            'longitude' => 105.3188,
        ]);
    }
}
