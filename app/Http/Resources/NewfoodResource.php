<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewfoodResource extends JsonResource
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
            'NewfoodID' => $this->NewfoodID,
            'UserID' => $this->UserID,
            'Editable' => $this->editable,
            'FoodID' => $this->FoodID,
            'Favourite' => $this->favourite,
            'FoodGroupID' => $this->FoodGroupID,
            'MeasureID' => $this->MeasureID,
            'FoodGroupName' => $this->foodgroup->FoodGroupName,
            'FoodDescription'=> $this->FoodDescription,
            'MeasureDescription' => $this->MeasureDescription,
            'ConversionFactorID' => $this->ConversionFactorID,
            'KCalValue' => $this->KCalValue,
            'PotassiumValue' => $this->PotassiumValue,
            'NutrientDensity' => $this->NutrientDensity,
        ];
    }
}
