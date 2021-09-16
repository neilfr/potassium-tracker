<?php

namespace Database\Seeders;

use App\Models\Conversionfactor;
use App\Traits\CSVSeeder;
use Illuminate\Database\Seeder;

class ConversionfactorSeeder extends Seeder
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
            Conversionfactor::class,
            './storage/csv/CONVERSION FACTOR.csv',
            ["FoodID", "MeasureID", "ConversionFactorValue"]
        );
    }
}
