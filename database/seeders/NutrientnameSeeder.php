<?php

namespace Database\Seeders;

use App\Models\Nutrientname;
use App\Traits\CSVSeeder;
use Illuminate\Database\Seeder;

class NutrientnameSeeder extends Seeder
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
            Nutrientname::class,
            './storage/csv/NUTRIENT NAME.csv',
            ["NutrientID", "NutrientName", "NutrientSymbol", "NutrientUnit"]
        );
    }
}
