<?php

namespace App\Http\Resources;

use App\Models\Nutrientname;
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
        $nutrientIds = collect(explode(',', env('NUTRIENTS')));
        $nutrients = $this->foodname->nutrientnames->whereIn('NutrientID', $nutrientIds);

        $conversionFactor = [
            'FoodID' => $this->foodname->FoodID,
            'MeasureID' => $this->measurename->MeasureID,
            'FoodGroupID' => $this->foodname->FoodGroupID,
            'FoodCode' => $this->foodname->FoodCode,
            'FoodDescription' => $this->foodname->FoodDescription,
            'MeasureDescription' => $this->measurename->MeasureDescription,
            'ConversionFactorValue' => $this->ConversionFactorValue,
        ];

        $nutrients->each( function($nutrient) use(&$conversionFactor) {
            $conversionFactor = array_merge($conversionFactor, [
               $nutrient->NutrientName => $nutrient->pivot->NutrientValue * $this->ConversionFactorValue,
            ]);
        });

        return $conversionFactor;

    }
}
