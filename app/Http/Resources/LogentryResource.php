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
            'UserID' => $this->UserID,
            'ConversionFactorID' => $this->ConversionFactorID,
            'ConsumedAt' => $this->ConsumedAt,
            'FoodDescription' => $this->conversionfactor->foodname->FoodDescription,
            'MeasureDescription' => $this->conversionfactor->measurename->MeasureDescription,
        ];
    }
}
