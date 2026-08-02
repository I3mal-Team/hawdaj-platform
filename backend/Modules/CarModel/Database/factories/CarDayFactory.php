<?php

namespace Modules\CarModel\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Modules\CarModel\Entities\CarDay;

class CarDayFactory extends Factory
{
    protected $model = CarDay::class;

    public function definition()
    {
        $int = random_int(strtotime(carbon()->now()->subDays(60)), strtotime(carbon()->now()));

        $sites = DB::table('sites')->pluck('id')->toArray();

        return [
            'site_id' => $sites[array_rand($sites)],
            'risk_duration' => random_int(40, 400),
            'no_risk_duration' => random_int(500, 1300),
            'day' => date('Y-m-d', $int),
            'created_at' => date('Y-m-d H:i:s', $int),
        ];
    }
}