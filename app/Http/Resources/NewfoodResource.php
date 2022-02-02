<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

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
            'Foo' => 'foo',
            'UserID' => $this->UserID,
            'Editable' => $this->editable,
            'FoodID' => $this->FoodID,
            'Favourite' => $this->favourite,
            'FoodGroupID' => $this->FoodGroupID,
            'MeasureID' => $this->MeasureID,
            'FoodGroupName' => $this->FoodGroupName,
            'FoodDescription'=> $this->FoodDescription,
            'MeasureDescription' => $this->MeasureDescription,
            'ConversionFactorValue' => $this->ConversionFactorValue,
            'ConversionFactorID' => $this->ConversionFactorID,
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
