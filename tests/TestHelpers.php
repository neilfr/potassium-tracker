<?php

namespace Tests;

use App\Models\Foodgroup;
use App\Models\Foodname;
use App\Models\Measurename;
use App\Models\Nutrientname;

trait TestHelpers {

    protected function createNutrients(): \Illuminate\Support\Collection
    {
        $nutrientsConfig = collect(explode(',', env('NUTRIENTS')));
        $nutrients = $nutrientsConfig->map(function ($nutrientId) {
            return Nutrientname::factory()->create([
                'NutrientID' => $nutrientId,
            ]);
        });
        return $nutrients;
    }

    public function createConversionFactor($nutrients, $owner_id, $count = 1)
    {
        $data = [];
        for($i=0;$i<$count;$i++){
            $foodgroup = Foodgroup::factory()->create();
            $foodname = Foodname::factory()->create([
                'FoodGroupID' => $foodgroup->FoodGroupID,
            ]);
            $measurename = Measurename::factory()->create();

            $conversionFactorValue = rand(1,5);
            $foodname->measurenames()->attach(
                $measurename,
                [
                    'ConversionFactorValue' => $conversionFactorValue,
                    'user_id' => $owner_id,
                ]
            );

            $nutrientData = $nutrients->map(function($nutrient) use ($foodname) {
                $nutrientValue = rand(100,200);
                $foodname->nutrientnames()->attach($nutrient, [
                    'NutrientValue' => $nutrientValue,
                ]);
                return array_merge($nutrient->toArray(), ['nutrient_value' => $nutrientValue]);
            });
            $data[$i] = [
                'ConversionFactorID' => $foodname->measurenames()->first()->pivot->id,
                'ConversionFactorValue' => $conversionFactorValue,
                'Foodgroup' => $foodgroup,
                'Foodname' => $foodname,
                'Measurename' => $measurename,
                'NutrientData' => $nutrientData,
            ];
        }

        return $data;
    }

}
