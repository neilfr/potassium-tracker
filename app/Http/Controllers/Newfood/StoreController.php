<?php

namespace App\Http\Controllers\Newfood;

use App\Http\Controllers\Controller;
use App\Models\Foodgroup;
use App\Models\Newfood;
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
//        dd($request->input());
        $newFood = new Newfood();
        $newFood->FoodDescription = $request->foodDescription;
        $newFood->FoodGroupID = $request->foodDescription;
        $newFood->FoodGroupName = Foodgroup::find($request->foodGroupId)->first()->FoodGroupName;
        $newFood->MeasureDescription = $request->measureDescription;
        $newFood->KCalValue = $request->k;
        $newFood->PotassiumValue = $request->kcal;
        $newFood->NutrientDensity = $request->kcal / $request->k;
        $newFood->save();
        auth()->user()->favourites()->attach($newFood);

        return redirect()->route('foods.index');
    }
}