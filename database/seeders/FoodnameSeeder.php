<?php

namespace Database\Seeders;

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
        $foods = $this->importCSV('./storage/csv/foodname2.csv', ["FoodID", "FoodCode", "FoodGroupID", "FoodDescription"]);
        dd($foods->firstWhere('FoodID', 2676));
    }

    private function importCSV(String $filePath, Array $fields): Collection
    {
        return $this->convertRowsIntoKeyedData($this->getCSVDataAsRows($filePath), collect($fields));
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

    private function convertRowsIntoKeyedData(Collection $csvDataRows, Collection $fields): Collection
    {
        $keys = collect($csvDataRows[0]);

        $flippedKeys = $keys->flip();
        $indexes = $fields->map(function($field) use($flippedKeys) {
            return $flippedKeys[$field];
        });

        return $csvDataRows
            ->skip(1)
            ->map(function ($row) use($indexes, $fields, $keys) {
                return $indexes->reduce( function($acc, $index) use($row, $keys) {
                    $acc[$keys[$index]] = $row[$index];
                   return $acc;
                },[]);
            });
    }
}
