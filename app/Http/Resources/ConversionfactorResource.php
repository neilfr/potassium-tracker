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
            'user_id' => $this->user_id,
            'FoodID' => $this->foodname->FoodID,
            'MeasureID' => $this->measurename->MeasureID,
            'FoodGroupID' => $this->foodname->FoodGroupID,
            'FoodGroupName' => $this->foodname->foodgroup->FoodGroupName,
            'FoodCode' => $this->foodname->FoodCode,
            'FoodDescription' => $this->foodname->FoodDescription,
            'MeasureDescription' => $this->measurename->MeasureDescription,
            'ConversionFactorValue' => $this->ConversionFactorValue,
            'nutrients' => $this->nutrients,
            'Favourite' => User::find(auth()->user()->id)
                ->favourites()
                ->where('ConversionFactorID', $this->id)
                ->exists(),
            'editable' => $this->user_id === auth()->user()->id,
            'NutrientDensityUnit' => $this->getNutrientDensityUnit(),
            'NutrientDensityValue' => $this->getNutrientDensityValue(),
        ];
    }

    private function getNutrientDensityValue()
    {
        $nutrientsDensityItems = collect(explode(',', env('NUTRIENT_DENSITY')));

        $numeratorNutrient = collect($this->nutrients->filter(function($nutrient) use ($nutrientsDensityItems){
            return $nutrient['NutrientID'] ==  $nutrientsDensityItems[0];
        }))->first();
        $denominatorNutrient = collect($this->nutrients->filter(function($nutrient) use ($nutrientsDensityItems){
            return $nutrient['NutrientID'] ==  $nutrientsDensityItems[1];
        }))->first();

        return round($numeratorNutrient['NutrientAmount'] / $denominatorNutrient['NutrientAmount'], 3);
    }

    private function getNutrientDensityUnit()
    {
        $nutrientsDensityItems = collect(explode(',', env('NUTRIENT_DENSITY')));

        $numeratorNutrient = collect($this->nutrients->filter(function($nutrient) use ($nutrientsDensityItems){
            return $nutrient['NutrientID'] ==  $nutrientsDensityItems[0];
        }))->first();
        $denominatorNutrient = collect($this->nutrients->filter(function($nutrient) use ($nutrientsDensityItems){
            return $nutrient['NutrientID'] ==  $nutrientsDensityItems[1];
        }))->first();
        return $numeratorNutrient['NutrientUnit'] . ' ' . $numeratorNutrient['NutrientSymbol'] .
            ' / ' . $denominatorNutrient['NutrientUnit'] . ' ' . $denominatorNutrient['NutrientSymbol'];
    }
}
