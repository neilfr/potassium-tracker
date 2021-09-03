<?php

namespace App\Http\Controllers\FoodnameMeasures;

use App\Http\Controllers\Controller;
//use App\Http\Resources\FoodnameMeasureResource;
use App\Http\Resources\FoodnameMeasureResource;
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
        $foobar = $foodnames->map(function($foodname) {
            $baz = $foodname->measurenames->map(function($measurename) use($foodname) {
                return [
                    'FoodID' => $foodname->FoodID,
                    'MeasureID' => $measurename->MeasureID,
                    'FoodGroupID' => $foodname->FoodGroupID,
                    'FoodCode' => $foodname->FoodCode,
                    'FoodDescription' => $foodname->FoodDescription,
                    'MeasureDescription' => $measurename->MeasureDescription,
                    'ConversionFactorValue' => $measurename->pivot->ConversionFactorValue,
                ];
            });
            return $baz;
        });

        return ['data' => $foobar->flatten(1)];
//        return [
//            'data' => $foobar->flatten(1),
//            ];

//        return [ 'data' => $foodnames->map( function ($foodname) {
//            dd($foodname->measurenames()->pivot->ConversionFactorValue);
//            $foo = $foodname->measurenames->map( function ($measurename) use ($foodname) {
//                return
//                    [
//                        'FoodID' => $foodname->FoodID,
//                        'FoodGroupID' => $foodname->FoodGroupID,
//                        'FoodCode' => $foodname->FoodCode,
//                        'FoodDescription' => $foodname->FoodDescription,
//                        'MeasureDescription' => $measurename->MeasureDescription,
//                        'ConversionFactorValue' => '',
//                    ];
//            });
//            return $foo;
//        })];
    }
}
