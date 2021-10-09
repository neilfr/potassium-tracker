<?php

namespace App\Http\Resources;

use App\Models\Nutrientname;
use App\Models\User;
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
        return [
            'id' => $this->id,
            'FoodID' => $this->foodname->FoodID,
            'MeasureID' => $this->measurename->MeasureID,
            'FoodGroupID' => $this->foodname->FoodGroupID,
            'FoodCode' => $this->foodname->FoodCode,
            'FoodDescription' => $this->foodname->FoodDescription,
            'MeasureDescription' => $this->measurename->MeasureDescription,
            'ConversionFactorValue' => $this->ConversionFactorValue,
            'nutrients' => $this->nutrients,
            'Favourite' => User::find(auth()->user()->id)
                ->favourites()
                ->where('ConversionFactorID', $this->id)
                ->exists(),
        ];
    }
}
