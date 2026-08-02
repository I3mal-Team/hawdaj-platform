<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Gate;
use App\Models\Reason;
use App\Models\VisitType;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::firstOrCreate([
            'name'  => 'Cairo Category',
            'notes' => 'Cairo Category Notes',
        ]);
        Category::firstOrCreate([
            'name'  => 'KSA Category',
            'notes' => 'KSA Category Notes',
        ]);
        Category::firstOrCreate([
            'name'  => 'UAE Category',
            'notes' => 'UAE Category Notes',
        ]);
        Category::firstOrCreate([
            'name'  => 'USA Category',
            'notes' => 'USA Category Notes',
        ]);

    }
}
