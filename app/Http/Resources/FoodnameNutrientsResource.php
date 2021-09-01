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
        $potassium = $nutrientNames->firstWhere('NutrientID', 306);
        $kcal = $nutrientNames->firstWhere('NutrientID', 208);
        return [
            'FoodID' => $this->FoodID,
            'FoodGroupID' => $this->FoodGroupID,
            'FoodCode' => $this->FoodCode,
            'FoodDescription' => $this->FoodDescription,
            $potassium->NutrientName => $potassium->pivot->NutrientValue,
            $kcal->NutrientName => $kcal->pivot->NutrientValue,
        ];
    }
}
