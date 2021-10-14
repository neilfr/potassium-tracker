<?php

namespace App\Http\Controllers\Conversionfactor;

use App\Http\Controllers\Controller;
use App\Models\Conversionfactor;
use App\Models\Foodname;
use App\Models\Measurename;
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
        $conversionfactor->MeasureID = $foodname->MeasureID;
        $conversionfactor->ConversionFactorValue = 1;

        dd($request->foodDescription);

    }
}
