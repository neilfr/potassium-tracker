<?php

namespace App\Http\Resources;

use App\Models\User;
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
            'id' => $this->id,
            'UserID' => 5,
//            'UserID' => $this->UserID,
            'FoodID' => $this->FoodID,
            'Favourite' => User::find(auth()->user()->id)
                ->favourites()
                ->where('ConversionFactorID', $this->ConversionFactorID)
                ->exists(),
            'FoodGroupID' => $this->FoodGroupID,
            'MeasureID' => $this->MeasureID,
            'FoodGroupName' => $this->FoodGroupName,
            'FoodDescription'=> $this->FoodDescription,
            'MeasureDescription' => $this->MeasureDescription,
            'ConversionFactorValue' => $this->ConversionFactorValue,
            'ConversionFactorID' => $this->ConversionFactorID,
        ];
    }
}
