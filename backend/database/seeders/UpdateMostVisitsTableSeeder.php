<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateMostVisitsTableSeeder extends Seeder
{
    public function run()
    {
        $locations = [
            ['name' => 'Albania', 'latitude' => 41.1533, 'longitude' => 20.1683],
            ['name' => 'Australia', 'latitude' => -25.2744, 'longitude' => 133.7751],
            ['name' => 'Austria', 'latitude' => 47.5162, 'longitude' => 14.5501],
            ['name' => 'Belarus', 'latitude' => 53.7098, 'longitude' => 27.9534],
            ['name' => 'Belgium', 'latitude' => 50.8503, 'longitude' => 4.3517],
            ['name' => 'Brazil', 'latitude' => -14.2350, 'longitude' => -51.9253],
            ['name' => 'Bulgaria', 'latitude' => 42.7339, 'longitude' => 25.4858],
            ['name' => 'Canada', 'latitude' => 56.1304, 'longitude' => -106.3468],
            ['name' => 'China', 'latitude' => 35.8617, 'longitude' => 104.1954],
            ['name' => 'Croatia', 'latitude' => 45.1, 'longitude' => 15.2],
            ['name' => 'Czechia', 'latitude' => 49.8175, 'longitude' => 15.4730],
            ['name' => 'Denmark', 'latitude' => 56.2639, 'longitude' => 9.5018],
            ['name' => 'Egypt', 'latitude' => 26.8206, 'longitude' => 30.8025],
            ['name' => 'Finland', 'latitude' => 61.9241, 'longitude' => 25.7482],
            ['name' => 'France', 'latitude' => 46.6034, 'longitude' => 1.8883],
            ['name' => 'Germany', 'latitude' => 51.1657, 'longitude' => 10.4515],
            ['name' => 'Greece', 'latitude' => 39.0742, 'longitude' => 21.8243],
            ['name' => 'Hong Kong', 'latitude' => 22.3964, 'longitude' => 114.1095],
            ['name' => 'Hungary', 'latitude' => 47.1625, 'longitude' => 19.5033],
            ['name' => 'India', 'latitude' => 20.5937, 'longitude' => 78.9629],
            ['name' => 'Indonesia', 'latitude' => -0.7893, 'longitude' => 113.9213],
            ['name' => 'Iraq', 'latitude' => 33.2232, 'longitude' => 43.6793],
            ['name' => 'Ireland', 'latitude' => 53.1424, 'longitude' => -7.6921],
            ['name' => 'Israel', 'latitude' => 31.0461, 'longitude' => 34.8516],
            ['name' => 'Italy', 'latitude' => 41.8719, 'longitude' => 12.5674],
            ['name' => 'Japan', 'latitude' => 36.2048, 'longitude' => 138.2529],
            ['name' => 'Latvia', 'latitude' => 56.8796, 'longitude' => 24.6032],
            ['name' => 'Liechtenstein', 'latitude' => 47.1660, 'longitude' => 9.5554],
            ['name' => 'Lithuania', 'latitude' => 55.1694, 'longitude' => 23.8813],
            ['name' => 'Luxembourg', 'latitude' => 49.8153, 'longitude' => 6.1296],
            ['name' => 'Moldova', 'latitude' => 47.4116, 'longitude' => 28.3699],
            ['name' => 'Nepal', 'latitude' => 28.3949, 'longitude' => 84.1240],
            ['name' => 'Netherlands', 'latitude' => 52.1326, 'longitude' => 5.2913],
            ['name' => 'Norway', 'latitude' => 60.4720, 'longitude' => 8.4689],
            ['name' => 'Pakistan', 'latitude' => 30.3753, 'longitude' => 69.3451],
            ['name' => 'Poland', 'latitude' => 51.9194, 'longitude' => 19.1451],
            ['name' => 'Qatar', 'latitude' => 25.3548, 'longitude' => 51.1839],
            ['name' => 'Romania', 'latitude' => 45.9432, 'longitude' => 24.9668],
            ['name' => 'Russia', 'latitude' => 61.5240, 'longitude' => 105.3188],
            ['name' => 'Saudi Arabia', 'latitude' => 23.8859, 'longitude' => 45.0792],
            ['name' => 'Seychelles', 'latitude' => -4.6796, 'longitude' => 55.491977],
            ['name' => 'Singapore', 'latitude' => 1.3521, 'longitude' => 103.8198],
            ['name' => 'South Africa', 'latitude' => -30.5595, 'longitude' => 22.9375],
            ['name' => 'South Korea', 'latitude' => 35.9078, 'longitude' => 127.7669],
            ['name' => 'Spain', 'latitude' => 40.4637, 'longitude' => -3.7492],
            ['name' => 'Sweden', 'latitude' => 60.1282, 'longitude' => 18.6435],
            ['name' => 'Thailand', 'latitude' => 15.8700, 'longitude' => 100.9925],
            ['name' => 'Turkey', 'latitude' => 38.9637, 'longitude' => 35.2433],
            ['name' => 'Ukraine', 'latitude' => 48.3794, 'longitude' => 31.1656],
            ['name' => 'United Arab Emirates', 'latitude' => 23.4241, 'longitude' => 53.8478],
            ['name' => 'United Kingdom', 'latitude' => 55.3781, 'longitude' => -3.4360],
            ['name' => 'United States', 'latitude' => 37.0902, 'longitude' => -95.7129],
        ];

        foreach ($locations as $location) {
            DB::table('most_visits')
                ->where('country', $location['name'])
                ->update([
                    'latitude' => $location['latitude'],
                    'longitude' => $location['longitude'],
                ]);
        }
    }
}
