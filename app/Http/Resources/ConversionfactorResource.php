<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConversionfactorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $nutrientNames = $this->foodname->nutrientnames;
        $potassium = $nutrientNames->firstWhere('NutrientID', 306);
        $kcal = $nutrientNames->firstWhere('NutrientID', 208);

        return [
            'FoodID' => $this->foodname->FoodID,
            'MeasureID' => $this->measurename->MeasureID,
            'FoodGroupID' => $this->foodname->FoodGroupID,
            'FoodCode' => $this->foodname->FoodCode,
            'FoodDescription' => $this->foodname->FoodDescription,
            'MeasureDescription' => $this->measurename->MeasureDescription,
            'ConversionFactorValue' => $this->ConversionFactorValue,
            $potassium->NutrientName => $potassium->pivot->NutrientValue * $this->ConversionFactorValue,
            $kcal->NutrientName => $kcal->pivot->NutrientValue * $this->ConversionFactorValue,
        ];

    }
}
