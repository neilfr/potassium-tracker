<?php

namespace App\Http\Controllers\Conversionfactor;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConversionfactorResource;
use App\Models\Conversionfactor;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
//        $conversionfactors = Conversionfactor::all();
//        $foo = Conversionfactor::first();
//        dd($foo->foodname->nutrientnames->first()->pivot->NutrientValue);
        $conversionfactors = ConversionfactorResource::collection(Conversionfactor::all());
//        return $conversionfactors;

        return Inertia::render('Conversionfactors/Index', [
            'conversionfactors' => $conversionfactors,
        ]);
    }
}
