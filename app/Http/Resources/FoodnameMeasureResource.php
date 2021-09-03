<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FoodnameMeasureResource extends JsonResource
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
            'FoodID' => $this->FoodID,
            'MeasureID' => $this->MeasureID,
            'FoodGroupID' => $this->FoodGroupID,
            'FoodCode' => $this->FoodCode,
            'FoodDescription' => $this->FoodDescription,
            'MeasureDescription' => $this->MeasureDescription,
            'ConversionFactorValue' => $this->ConversionFactorValue,
        ];
    }
}
