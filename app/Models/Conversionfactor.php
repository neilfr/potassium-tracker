<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Conversionfactor extends Pivot
{
    use HasFactory;

    protected $table='conversionfactors';

    protected $with = ['foodname','measurename'];

    public function foodname(){
        return $this->belongsTo(Foodname::class,'FoodID');
    }

    public function measurename(){
        return $this->belongsTo(Measurename::class,'MeasureID');
    }

    public function getNutrientsAttribute(){
        $nutrients = $this->foodname->nutrientnames
            ->whereIn('NutrientID', collect(explode(',', env('NUTRIENTS'))));
        return $nutrients->map( function($nutrient) {
            return array_merge($nutrient->toArray(), [
                'NutrientAmount' => $nutrient->pivot->NutrientValue * $this->ConversionFactorValue,
            ]);
        });
    }

}
