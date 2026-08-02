<?php

namespace Database\Seeders;

use App\Models\HealthCheck;
use Illuminate\Database\Seeder;

class HealthCheckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        HealthCheck::firstOrCreate([
            'question' => 'Have you recently traveled to an area with known local spread of COVID-19?',
        ]);
        HealthCheck::firstOrCreate([
            'question' => 'Have you come into close contact ( with 6 feet) with someone who has a laboratory confirmed COVID-19 diagnosis in the past 14 days?',
        ]);
        HealthCheck::firstOrCreate([
            'question' => 'Do you have a fever (Greater than 38.0 C) OR symptoms of lower respiratory illness such as cough, shortness of breath,
                difficulty breathing or sore throat?',
        ]);
        HealthCheck::firstOrCreate([
            'question' => 'Are you a first responder, healthCare worker, or employee or attendee of a child or adult care facility?',
        ]);
    }
}
