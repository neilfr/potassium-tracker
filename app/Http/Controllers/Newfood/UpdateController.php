<?php

namespace App\Http\Controllers\Newfood;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateNewfoodRequest;
use App\Models\Newfood;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(UpdateNewfoodRequest $request)
    {
        $newFood = Newfood::find($request->newFoodId);
        $newFood->FoodDescription = $request->foodDescription;
        $newFood->FoodGroupID = $request->foodGroupId;
        $newFood->MeasureDescription = $request->measureDescription;
        $newFood->KCalValue = $request->kCalValue;
        $newFood->PotassiumValue = $request->potassiumValue;
        $newFood->NutrientDensity = $request->kCalValue / $request->potassiumValue;
        $newFood->save();

        return redirect()->route('foods.index');
    }
}
