<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FoodnameNutrientsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $nutrientNames = $this->nutrientnames;
        return [
            'FoodID' => $this->FoodID,
            'FoodGroupID' => $this->FoodGroupID,
            'FoodCode' => $this->FoodCode,
            'FoodDescription' => $this->FoodDescription,
            $nutrientNames->firstWhere('NutrientID', 306)->NutrientName => 100,
            $nutrientNames->firstWhere('NutrientID', 208)->NutrientName => 100,
        ];
    }
}
