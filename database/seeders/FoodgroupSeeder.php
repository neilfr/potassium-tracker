<?php

namespace Database\Seeders;

use App\Models\Foodgroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FoodgroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::insert(" INSERT INTO `foodgroups` (`FoodGroupID`, `FoodGroupName`, `created_at`, `updated_at`) VALUES
            (1,'Dairy and Egg Products', now(), now()),
            (2,'Spices and Herbs', now(), now()),
            (3,'Babyfoods', now(), now()),
            (4,'Fats and Oils', now(), now()),
            (5,'Poultry Products', now(), now()),
            (6,'Soups, Sauces and Gravies', now(), now()),
            (7,'Sausages and Luncheon meats', now(), now()),
            (8,'Breakfast cereals', now(), now()),
            (9,'Fruits and fruit juices', now(), now()),
            (10,'Pork Products', now(), now()),
            (11,'Vegetables and Vegetable Products', now(), now()),
            (12,'Nuts and Seeds', now(), now()),
            (13,'Beef Products', now(), now()),
            (14,'Beverages', now(), now()),
            (15,'Finfish and Shellfish Products', now(), now()),
            (16,'Legumes and Legume Products', now(), now()),
            (17,'Lamb, Veal and Game', now(), now()),
            (18,'Baked Products', now(), now()),
            (19,'Sweets', now(), now()),
            (20,'Cereals, Grains and Pasta', now(), now()),
            (21,'Fast Foods', now(), now()),
            (22,'Mixed Dishes', now(), now()),
            (25,'Snacks', now(), now()),
            (26,'Meals', now(), now());
        ");
    }
}
