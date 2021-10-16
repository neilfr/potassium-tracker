<?php

namespace App\Http\Controllers\Conversionfactor;

use App\Http\Controllers\Controller;
use App\Models\Conversionfactor;
use App\Models\Foodname;
use App\Models\Measurename;
use App\Models\Nutrientname;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $foodname = new Foodname;
        $foodname->FoodDescription = $request->foodDescription;
        $foodname->FoodGroupID = $request->foodGroupId;
        $foodname->save();

        $measurename = new Measurename;
        $measurename->MeasureDescription = $request->measureDescription;
        $measurename->save();

        $conversionfactor = new Conversionfactor;
        $conversionfactor->FoodID = $foodname->FoodID;
        $conversionfactor->MeasureID = $measurename->MeasureID;
        $conversionfactor->ConversionFactorValue = 1;
        $conversionfactor->save();

        $conversionfactor->foodname->nutrientnames()->attach(306,[
            'NutrientValue' => $request->k,
        ]);
        $conversionfactor->foodname->nutrientnames()->attach(208,[
            'NutrientValue' => $request->kcal,
        ]);

        return route('conversionfactors.index');
    }
}
