<?php

namespace App\Traits;

use Illuminate\Support\Collection;

trait CSVSeeder

{
    protected function seedFromCSV(String $model, String $filePath, Array $fields): void
    {
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            $keys = $this->getKeyNames($handle);
            $indexes = $this->getIndexesForDesiredKeys($keys, $fields);

            $this->command->getOutput()->progressStart(0);

            while (($data = fgetcsv($handle, 0,',','"','"')) !== FALSE) {
                $model::create($this->buildModelInstance($indexes, $data, $keys));
                $this->command->getOutput()->progressAdvance();
            }
            fclose($handle);
            $this->command->getOutput()->progressFinish();
        }
    }

    /**
     * @param $handle
     * @return Collection
     */
    private function getKeyNames($handle): Collection
    {
        $columnNameRow = fgetcsv($handle, 0, ',', '"', '"');
        $keys = collect($columnNameRow);
        return $keys;
    }

    /**
     * @param Collection $keys
     * @param array $fields
     * @return Collection
     */
    private function getIndexesForDesiredKeys(Collection $keys, array $fields): Collection
    {
        $flippedKeys = $keys->flip();
        $fieldsCollection = collect($fields);
        $indexes = $fieldsCollection->map(function ($field) use ($flippedKeys) {
            return $flippedKeys[$field];
        });
        return $indexes;
    }

    /**
     * @param Collection $indexes
     * @param array|null $data
     * @param Collection $keys
     * @return mixed|null
     */
    private function buildModelInstance(Collection $indexes, ?array $data, Collection $keys)
    {
        return $indexes->reduce(function ($acc, $index) use ($data, $keys) {
            $row = array_map("utf8_encode", $data);
            $acc[$keys[$index]] = $row[$index];
            return $acc;
        }, []);
    }
}
