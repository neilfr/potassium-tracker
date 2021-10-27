<?php

namespace App\Http\Controllers\Conversionfactor;

use App\Http\Controllers\Controller;
use App\Http\Resources\FoodgroupResource;
use App\Models\Foodgroup;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CreateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Conversionfactors/Create',
            [
                'foodgroups' => FoodgroupResource::collection(Foodgroup::all())
            ]
        );
    }
}
