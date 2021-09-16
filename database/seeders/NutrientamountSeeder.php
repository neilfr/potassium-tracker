<?php

namespace Database\Seeders;

use App\Models\Nutrientamount;
use App\Traits\CSVSeeder;
use Illuminate\Database\Seeder;

class NutrientamountSeeder extends Seeder
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
            Nutrientamount::class,
            './storage/csv/NUTRIENT AMOUNT.csv',
            ["FoodID", "NutrientID", "NutrientValue"]
        );
    }
}
