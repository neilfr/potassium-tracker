<?php

namespace Database\Seeders;

use App\Models\Foodgroup;
use App\Models\Foodname;
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
//        $foodgroups = $this->importCSV(
//            Foodgroup::class,
//            './storage/csv/Food Group.csv',
//            ["FoodGroupID", "FoodGroupName"]
//        );

//        $foodnames = $this->importCSV(
//            Foodname::class,
//            './storage/csv/Food Name.csv',
//            ["FoodID", "FoodGroupID", "FoodCode", "FoodDescription"]
//        );

//        $this->call(FoodgroupSeeder::class);
        $this->call(FoodnameSeeder::class);
    }

    private function importCSV(String $model, String $filePath, Array $fields): Collection
    {
        return $this->convertRowsIntoKeyedData($model, $this->getCSVDataAsRows($filePath), collect($fields));
    }

    private function getCSVDataAsRows(String $filePath): Collection
    {
        $csvDataRows = [];

        if (($handle = fopen($filePath, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 0,',','"','"')) !== FALSE) {
                $csvDataRows[]= $data;
            }
            fclose($handle);
        }

        return collect($csvDataRows);
    }

    private function convertRowsIntoKeyedData(String $model, Collection $csvDataRows, Collection $fields): Collection
    {
        // should take in the model... and then create a new model for each row here
        $keys = collect($csvDataRows[0]);

        $flippedKeys = $keys->flip();
        $indexes = $fields->map(function($field) use($flippedKeys) {
            return $flippedKeys[$field];
        });

        return $csvDataRows
            ->skip(1)
            ->map(function ($row) use($indexes, $fields, $keys, $model) {
                $row = $indexes->reduce( function($acc, $index) use($row, $keys) {
                    $acc[$keys[$index]] = $row[$index];
                    return $acc;
                },[]);
                dd($row);
                $model::create($row);
                return $row;
            });
    }
}
