<?php

namespace Database\Seeders;

use App\Models\Foodgroup;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        $this->call(FoodgroupSeeder::class);
        $this->call(FoodnameSeeder::class);
    }
}
