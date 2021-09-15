<?php

namespace Database\Seeders;

use App\Models\Conversionfactor;
use App\Models\Foodgroup;
use App\Models\Foodname;
use App\Models\Measurename;
use App\Models\Nutrientamount;
use App\Models\Nutrientname;
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
        $foodgroups = $this->seedFromCSV(
            Foodgroup::class,
            './storage/csv/FOOD GROUP.csv',
            ["FoodGroupID", "FoodGroupName"]
        );

        $foodnames = $this->seedFromCSV(
            Foodname::class,
            './storage/csv/FOOD NAME.csv',
            ["FoodID", "FoodGroupID", "FoodCode", "FoodDescription"]
        );

        $measurenames = $this->seedFromCSV(
            Measurename::class,
            './storage/csv/MEASURE NAME.csv',
            ["MeasureID", "MeasureDescription"]
        );

        $conversionfactors = $this->seedFromCSV(
            Conversionfactor::class,
            './storage/csv/CONVERSION FACTOR.csv',
            ["FoodID", "MeasureID", "ConversionFactorValue"]
        );

        $nutrientnames = $this->seedFromCSV(
            Nutrientname::class,
            './storage/csv/NUTRIENT NAME.csv',
            ["NutrientID", "NutrientName"]
        );

        $nutrientamounts = $this->seedFromCSV(
            Nutrientamount::class,
            './storage/csv/NUTRIENT AMOUNT.csv',
            ["FoodID", "NutrientID", "NutrientValue"]
        );

        $this->call(TestUserSeeder::class);
        $this->call(LogentrySeeder::class);
    }

    private function seedFromCSV(String $model, String $filePath, Array $fields): Collection
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
