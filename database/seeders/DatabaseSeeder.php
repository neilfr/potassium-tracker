<?php

namespace Database\Seeders;

use App\Models\Conversionfactor;
use App\Models\Foodgroup;
use App\Models\Foodname;
use App\Models\Measurename;
use App\Models\Nutrientamount;
use App\Models\Nutrientname;
use App\Traits\FooTrait;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(FoodgroupSeeder::class);
        $this->call(FoodnameSeeder::class);
        $this->call(MeasurenameSeeder::class);
        $this->call(ConversionfactorSeeder::class);
        $this->call(NutrientnameSeeder::class);
        $this->call(NutrientamountSeeder::class);

        $this->call(TestUserSeeder::class);
        $this->call(LogentrySeeder::class);
    }

}
