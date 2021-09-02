<?php

namespace App\Http\Controllers\FoodnameNutrients;

use App\Http\Controllers\Controller;
use App\Http\Resources\FoodnameNutrientsResource;
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
//        $foodname = Foodname::first();
//        $bar = Foodname::first()->pivot->NutrientName;
//        dd('controller',$foodname->nutrientnames);
        return FoodnameNutrientsResource::collection(Foodname::all());
    }
}
