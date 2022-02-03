<?php

namespace App\Http\Controllers\Newfood;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewfoodRequest;
use App\Models\Newfood;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  StoreNewfoodRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(StoreNewfoodRequest $request)
    {
        $newFood = new Newfood();
        $newFood->UserID = auth()->user()->id;
        $newFood->FoodDescription = $request->foodDescription;
        $newFood->FoodGroupID = $request->foodGroupId;
        $newFood->MeasureDescription = $request->measureDescription;
        $newFood->KCalValue = $request->k;
        $newFood->PotassiumValue = $request->kcal;
        $newFood->NutrientDensity = $request->kcal / $request->k;
        $newFood->save();
        auth()->user()->foodfavourites()->attach($newFood);

        return redirect()->route('foods.index');
    }
}
