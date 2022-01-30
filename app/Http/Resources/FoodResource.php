<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FoodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'UserID' => $this->id,
            'FoodID' => $this->FoodID,
            'FoodGroupID' => $this->FoodGroupID,
            'MeasureID' => $this->MeasureID,
            'Favourite' => $this->Favourite,
            'FoodGroupName' => $this->FoodGroupName,
            'FoodDescription'=> $this->FoodDescription,
            'MeasureDescription' => $this->MeasureDescription,
            'ConversionFactorValue' => $this->ConversionFactorValue,
            'ConversionFactorID' => $this->id,
            'KCalValue' => $this->KCalValue,
            'KCalSymbol' => $this->KCalSymbol,
            'KCalName' => $this->KCalName,
            'KCalUnit' => $this->KCalUnit,
            'PotassiumValue' => $this->PotassiumValue,
            'PotassiumSymbol' => $this->PotassiumSymbol,
            'PotassiumName' => $this->PotassiumName,
            'PotassiumUnit' => $this->PotassiumUnit,
            'NutrientDensity' => $this->NutrientDensity,
        ];
    }
}
