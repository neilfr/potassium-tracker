<?php

namespace Database\Seeders;

use App\Models\Foodgroup;
use App\Traits\CSVSeeder;
use Illuminate\Database\Seeder;

class FoodgroupSeeder extends Seeder
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
            Foodgroup::class,
            './storage/csv/FOOD GROUP.csv',
            ["FoodGroupID", "FoodGroupName"]
        );
    }
}
