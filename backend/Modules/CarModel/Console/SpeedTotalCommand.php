<?php

namespace Modules\CarModel\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\CarModel\Entities\CarDay;
use Modules\CarModel\Entities\CarsDetails;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CarTotalCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'car:total';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Car Add To Total.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $days = CarDay::whereDate('day', Carbon::yesterday())->get();
        if ($days) {
            foreach ($days as $day) {
                $car_details = CarsDetails::where('site_id', $day->site_id)->first();
                if ($car_details) {
                    $car_details->risk_duration += $day->risk_duration;
                    $car_details->no_risk_duration += $day->no_risk_duration;
                    $car_details->save();
                }
            }
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}