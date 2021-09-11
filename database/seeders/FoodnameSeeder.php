<?php

namespace Database\Seeders;

use App\Models\Foodname;
use App\Models\Foodgroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class FoodnameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // should pass in the model... and importCSVtoModel... and not return the whole thing here.
        $foods = $this->importCSV(Foodname::class,'./storage/csv/Food Name.csv', ["FoodID", "FoodCode", "FoodGroupID", "FoodDescription"]);
//        $foodgroups = $this->importCSV(Foodgroup::class,'./storage/csv/Food Group.csv', ["FoodGroupID", "FoodGroupName"]);
        dd('foodgroups from db:', Foodgroup::all());
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
                $data = array_map("utf8_encode", $data); //added
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
                $model::create($row);
                return $row;
            });
    }
}
