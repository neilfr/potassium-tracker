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
        $nutrientDensityData = $this->getNutrientDensityData();
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
            'NutrientDensityUnit' => $nutrientDensityData['unit'],
            'NutrientDensityValue' => $nutrientDensityData['value'],
        ];
    }

    private function getNutrientDensityData()
    {
        $numeratorNutrient = $this->getNumeratorNutrient();
        $denominatorNutrient = $this->getDenominatorNutrient();
        if(!$denominatorNutrient || !$numeratorNutrient || $denominatorNutrient['NutrientAmount'] == 0 || $denominatorNutrient['NutrientAmount'] == 'NA') return [
            'value' => 'NA',
            'unit' => $numeratorNutrient['NutrientUnit'] . ' ' . $numeratorNutrient['NutrientSymbol'] .
                ' / ' . $denominatorNutrient['NutrientUnit'] . ' ' . $denominatorNutrient['NutrientSymbol']
        ];

        return [
            'value' => round($numeratorNutrient['NutrientAmount'] / $denominatorNutrient['NutrientAmount'], 1),
            'unit' => $numeratorNutrient['NutrientUnit'] . ' ' . $numeratorNutrient['NutrientSymbol'] .
                ' / ' . $denominatorNutrient['NutrientUnit'] . ' ' . $denominatorNutrient['NutrientSymbol']
        ];
    }

    private function getNumeratorNutrient()
    {
        $nutrientsDensityItems = collect(explode(',', env('NUTRIENT_DENSITY')));

        return collect($this->nutrients->filter(function($nutrient) use ($nutrientsDensityItems){
            return $nutrient['NutrientID'] ==  $nutrientsDensityItems[0];
        }))->first();
    }

    private function getDenominatorNutrient()
    {
        $nutrientsDensityItems = collect(explode(',', env('NUTRIENT_DENSITY')));

        return collect($this->nutrients->filter(function($nutrient) use ($nutrientsDensityItems){
            return $nutrient['NutrientID'] ==  $nutrientsDensityItems[1];
        }))->first();
    }
}
