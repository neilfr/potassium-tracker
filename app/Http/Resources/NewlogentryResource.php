<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewlogentryResource extends JsonResource
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
            'id' => $this->id,
            'UserID' => $this->UserID,
            'NewfoodID' => $this->NewfoodID,
            'ConsumedAt' => $this->ConsumedAt,
            'portion' => $this->portion,
            'FoodDescription' => $this->newfood->FoodDescription,
            'FoodGroupName' => $this->newfood->foodgroup->FoodGroupName,
            'MeasureDescription' => $this->newfood->MeasureDescription,
            'KCalValue' => $this->newfood->KCalValue * $this->portion / 100,
            'PotassiumValue' => $this->newfood->PotassiumValue * $this->portion / 100,
            'NutrientDensity' => $this->newfood->NutrientDensity,
        ];
    }
}
