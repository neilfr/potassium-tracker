<?php

namespace Database\Seeders;

use App\Models\Foodname;
use App\Traits\CSVSeeder;
use Illuminate\Database\Seeder;

class FoodnameSeeder extends Seeder
{
    use CSVSeeder;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedFromCSV(
            Foodname::class,
            './storage/csv/FOOD NAME.csv',
            ["FoodID", "FoodGroupID", "FoodCode", "FoodDescription"]
        );
    }
}
