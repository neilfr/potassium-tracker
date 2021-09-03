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
//        dd($this);
//        dd('hi', $this->foodname->FoodID);
//        dump($this);
        return [
            'FoodID' => $this->foodname->FoodID,
            'MeasureID' => $this->measurename->MeasureID,
            'FoodGroupID' => $this->foodname->FoodGroupID,
            'FoodCode' => $this->foodname->FoodCode,
            'FoodDescription' => $this->foodname->FoodDescription,
            'MeasureDescription' => $this->measurename->MeasureDescription,
            'ConversionFactorValue' => $this->ConversionFactorValue,
        ];

//        return parent::toArray($request);

//        return [
//            'MeasureDescription' => $this->measurename->MeasureDescription,
//        ];
    }
}
