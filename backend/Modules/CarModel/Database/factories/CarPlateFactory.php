<?php

namespace Modules\CarModel\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Modules\CarModel\Entities\CarModel;

class CarPlateFactory extends Factory
{
    protected $model = CarPlate::class;

    public function definition()
    {
        $int = random_int(strtotime(carbon()->now()->subDays(60)), strtotime(carbon()->now()));

        $sites = DB::table('sites')->pluck('id')->toArray();

        $item = rand(0, 1);

        return [
            'site_id' => $sites[array_rand($sites)],
            'camID' => $this->faker->randomDigit(),
            'notice_time' => $item ? date("Y-m-d H:i:s", $int) : null,
            'created_at' => date("Y-m-d H:i:s", $int),
            'detection_status' => 'success',
        ];
    }
}