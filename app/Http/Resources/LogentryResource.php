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
        return [
            'id' => $this->id,
            'UserID' => $this->UserID,
            'ConversionFactorID' => $this->ConversionFactorID,
            'ConsumedAt' => $this->ConsumedAt,
            'FoodDescription' => $this->conversionfactor->foodname->FoodDescription,
            'FoodGroupName' => $this->conversionfactor->foodname->foodgroup->FoodGroupName,
            'MeasureDescription' => $this->conversionfactor->measurename->MeasureDescription,
            'ConversionFactorValue' => $this->conversionfactor->ConversionFactorValue,
            'nutrients' => $this->conversionfactor->nutrients,
        ];
    }
}
