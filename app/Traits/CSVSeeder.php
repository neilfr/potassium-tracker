<?php

namespace App\Traits;

use Illuminate\Support\Collection;

trait CSVSeeder

{
    protected function getCSVDataAsRows(String $filePath): Collection
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

    protected function seedFromCSV(String $model, String $filePath, Array $fields): Collection
    {
        return $this->convertRowsIntoKeyedData($model, $this->getCSVDataAsRows($filePath), collect($fields));
    }

    protected function convertRowsIntoKeyedData(String $model, Collection $csvDataRows, Collection $fields): Collection
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
