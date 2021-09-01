<?php

namespace App\Http\Controllers\FoodnameMeasures;

use App\Http\Controllers\Controller;
//use App\Http\Resources\FoodnameMeasureResource;
use App\Models\Foodname;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $foodnames = Foodname::all();

        return [ 'data' => $foodnames->map( function ($foodname) {
            return $foodname->measurenames->map( function ($measurename) use ($foodname) {
                return
                    [
                        'FoodID' => $foodname->FoodID,
                        'FoodGroupID' => $foodname->FoodGroupID,
                        'FoodCode' => $foodname->FoodCode,
                        'FoodDescription' => $foodname->FoodDescription,
                        'MeasureDescription' => $measurename->MeasureDescription,
                    ];
            });
        })];
    }
}
