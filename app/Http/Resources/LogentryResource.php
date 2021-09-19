<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LogentryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $nutrientNames = $this->conversionfactor->foodname->nutrientnames;
        $potassium = $nutrientNames->firstWhere('NutrientID', 306);
        $kcal = $nutrientNames->firstWhere('NutrientID', 208);

        return [
            'UserID' => $this->UserID,
            'ConversionFactorID' => $this->ConversionFactorID,
            'ConsumedAt' => $this->ConsumedAt,
            'FoodDescription' => $this->conversionfactor->foodname->FoodDescription,
            'MeasureDescription' => $this->conversionfactor->measurename->MeasureDescription,
            $potassium->NutrientSymbol => $potassium->pivot->NutrientValue * $this->conversionfactor->ConversionFactorValue,
            $kcal->NutrientSymbol => $kcal->pivot->NutrientValue * $this->conversionfactor->ConversionFactorValue,
        ];
    }
}
