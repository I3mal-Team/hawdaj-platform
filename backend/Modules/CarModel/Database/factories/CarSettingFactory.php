<?php


namespace Modules\CarModel\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class CarSettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\CarModel\Entities\CarSetting::class;


    public function definition()
    {

        $sites = DB::table('sites')->pluck('id')->toArray();

        return [
            'start_time' => random_int(0, time()),
            'end_time' => random_int(0, time()),
            'start_date' => now()->subDays(30)->toDateString(),
            'end_date' =>  now()->addDays(30)->toDateString(),
            'site_id' => $sites[array_rand($sites)],
            'notification' => (bool)random_int(0, 1),
            'screenshot' => (bool)random_int(0, 1),
            'active' => (bool)random_int(0, 1),
        ];
    }
}
