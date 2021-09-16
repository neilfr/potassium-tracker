<?php

namespace Database\Seeders;

use App\Models\Measurename;
use App\Traits\CSVSeeder;
use Illuminate\Database\Seeder;

class MeasurenameSeeder extends Seeder
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
            Measurename::class,
            './storage/csv/MEASURE NAME.csv',
            ["MeasureID", "MeasureDescription"]
        );
    }
}
